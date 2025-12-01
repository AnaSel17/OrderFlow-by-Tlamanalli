<?php

namespace Database\Seeders;

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

        // 🔹 Lista de pedidos de prueba (personas por pedido EN MESA)
        $pedidosDemo = [
            2, 4, 5, 8, 10, 12
        ];

        /* ============================================
           1) PEDIDOS NORMALES CON MESA(S)
        ============================================ */
        foreach ($pedidosDemo as $personas) {
            DB::beginTransaction();
            try {

                // 🔍 Buscar mesas suficientes
                $capacidadAcumulada = 0;
                $mesasSeleccionadas = [];

                foreach ($mesas as $m) {
                    if ($m->estado !== 'disponible') continue;

                    $capacidadMesa      = $m->capacidad + $m->sillas_extra;
                    $mesasSeleccionadas[] = $m;
                    $capacidadAcumulada += $capacidadMesa;

                    if ($capacidadAcumulada >= $personas) break;
                }

                if ($capacidadAcumulada < $personas) {
                    // 👉 Agregar sillas extra a la primera mesa
                    $faltantes = $personas - $capacidadAcumulada;
                    $mesaExtra = $mesasSeleccionadas[0];

                    $mesaExtra->sillas_extra = min(5, $faltantes);
                    $mesaExtra->save();

                    $capacidadAcumulada += $mesaExtra->sillas_extra;
                }

                // 🔸 Crear pedido NORMAL (tipo mesa)
                $pedido = Pedido::create([
                    'usuario_id'     => 1,          // Admin por defecto
                    'estado'         => 'pendiente',
                    'total'          => 0,
                    'num_comensales' => $personas,
                    'modo_cuenta'    => 'completa',
                    'tipo'           => 'mesa',     // ⭐ IMPORTANTE
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

                $this->command->info("🍽 Pedido #{$pedido->id} (MESA) creado con "
                    . count($mesasSeleccionadas) . " mesa(s) para {$personas} personas.");

            } catch (\Throwable $e) {
                DB::rollBack();
                $this->command->error("❌ Error asignando mesas: " . $e->getMessage());
            }
        }

        /* ============================================
           2) PEDIDOS PARA LLEVAR (SIN MESA)
        ============================================ */

        // Por ejemplo, 3 pedidos para llevar
        $pedidosParaLlevar = [1, 2, 3]; // número de "personas" (solo para num_comensales)

        foreach ($pedidosParaLlevar as $personas) {
            DB::beginTransaction();
            try {
                $pedido = Pedido::create([
                    'usuario_id'     => 1,
                    'estado'         => 'pendiente',
                    'total'          => 0,
                    'num_comensales' => $personas,   // puedes poner 0 si quieres
                    'modo_cuenta'    => 'completa',
                    'tipo'           => 'llevar',    // ⭐ CLAVE
                ]);

                // 🛍 Para llevar → NO tiene mesas asociadas
                // 🛍 Si quieres comensales para prueba, puedes crearlos; yo aquí NO los creo
                // y dejo que tu DetallePedidoSeeder genere detalles generales sin comensal

                DB::commit();

                $this->command->info("🛍 Pedido #{$pedido->id} creado COMO PARA LLEVAR (sin mesa).");

            } catch (\Throwable $e) {
                DB::rollBack();
                $this->command->error("❌ Error creando pedido para llevar: " . $e->getMessage());
            }
        }
    }
}
