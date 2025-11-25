<?php

namespace Database\Seeders;

use App\Models\Comanda;
use App\Models\Pedido;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComandaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pedidos = Pedido::with('detalles')->get();

        if ($pedidos->isEmpty()) {
            $this->command->warn("⚠️ No hay pedidos para generar comandas.");
            return;
        }

        foreach ($pedidos as $pedido) {

            $pendientes = $pedido->detalles()->where('estado', 'pendiente')->get();

            if ($pendientes->isEmpty()) {
                $this->command->warn("➡ Pedido #{$pedido->id} no tiene detalles pendientes, se omite.");
                continue;
            }

            $this->command->info("🍳 Generando comandas para Pedido #{$pedido->id}");

            // Simula que cocina trabaja por tandas de 3 productos
            $tandas = $pendientes->chunk(3);

            foreach ($tandas as $index => $grupo) {

                $ronda = $index + 1;

                $comanda = Comanda::create([
                    'pedido_id'  => $pedido->id,
                    'estado'     => 'enviado_cocina',
                    'enviada_en' => now(),
                ]);

                foreach ($grupo as $detalle) {
                    $detalle->update([
                        'estado'     => 'enviado_cocina',
                        'comanda_id' => $comanda->id
                    ]);
                }

                $this->command->info("   ✔ Comanda #{$comanda->id} (Ronda {$ronda}) generada con " . $grupo->count() . " platillos.");
            }

            // Marcar pedido como enviado a cocina
            if ($pedido->estado === 'pendiente') {
                $pedido->update(['estado' => 'enviado_cocina']);
            }

                }

        $this->command->info("🎉 Comandas generadas correctamente.");
    }
}
