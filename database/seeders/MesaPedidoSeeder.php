<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mesa;
use App\Models\MesaPedido;
use App\Models\Pedido;
use App\Models\Comensale;
use Illuminate\Support\Facades\DB;

class MesaPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mesas = Mesa::where('estado', 'disponible')->get();

        if ($mesas->isEmpty()) {
            $this->command->warn("⚠️ No hay mesas disponibles. Ejecuta ZonaSeeder y MesaSeeder primero.");
            return;
        }

        // 🔹 Lista de pedidos de prueba (personas por pedido)
        $pedidosDemo = [
            2, 4, 5, 8, 10, 12
        ];

        foreach ($pedidosDemo as $personas) {
            DB::beginTransaction();
            try {

                // 🔍 Buscar mesas suficientes (tipo: combinaciones simples)
                $capacidadAcumulada = 0;
                $mesasSeleccionadas = [];

                foreach ($mesas as $m) {
                    if ($m->estado !== 'disponible') continue;

                    $capacidadMesa = $m->capacidad + $m->sillas_extra;
                    $mesasSeleccionadas[] = $m;
                    $capacidadAcumulada += $capacidadMesa;

                    if ($capacidadAcumulada >= $personas) break;
                }

                if ($capacidadAcumulada < $personas) {
                    // 👉 Agregar sillas a la primera mesa
                    $faltantes = $personas - $capacidadAcumulada;
                    $mesaExtra = $mesasSeleccionadas[0];

                    $mesaExtra->sillas_extra = min(5, $faltantes);
                    $mesaExtra->save();

                    $capacidadAcumulada += $mesaExtra->sillas_extra;
                }

                // 🔸 Crear pedido
                $pedido = Pedido::create([
                    'usuario_id'     => 1,  // Admin por defecto
                    'estado'         => 'pendiente',
                    'total'          => 0,
                    'num_comensales' => $personas,
                    'modo_cuenta'    => 'completa',
                ]);

                // 🔸 Asociar mesas
                foreach ($mesasSeleccionadas as $m) {
                    MesaPedido::create([
                        'mesa_id'   => $m->id,
                        'pedido_id' => $pedido->id,
                    ]);

                    $m->update(['estado' => 'ocupada']);
                }

                // 🔸 Crear comensales del pedido
                for ($i = 1; $i <= $personas; $i++) {
                    Comensale::create([
                        'pedido_id' => $pedido->id,
                        'numero'    => $i,
                        'nombre'    => null,
                    ]);
                }

                DB::commit();

                $this->command->info("🍽 Pedido #{$pedido->id} creado con "
                    . count($mesasSeleccionadas) . " mesa(s) para {$personas} personas.");

            } catch (\Throwable $e) {
                DB::rollBack();
                $this->command->error("❌ Error asignando mesas: " . $e->getMessage());
            }
        }
    }
}
