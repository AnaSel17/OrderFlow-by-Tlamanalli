<?php

namespace Database\Seeders;

use App\Models\Pedido;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SimulacionCocinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pedidos = Pedido::with('detalles')->get();

        if ($pedidos->isEmpty()) {
            $this->command->warn("⚠ No hay pedidos para simular.");
            return;
        }

        $this->command->info("🔥 Simulación completa de cocina → entregas → cobro");

        foreach ($pedidos as $pedido) {

            $detalles = $pedido->detalles;

            if ($detalles->isEmpty()) {
                continue;
            }

            $this->command->info("➡ Pedido #{$pedido->id}");

            //
            // 1️⃣ ENVIAR TODO A COCINA
            //
            $pedido->detalles()->update(['estado' => 'enviado_cocina']);
            $pedido->update(['estado' => 'enviado_cocina']);
            $this->command->info("   📤 Todos los detalles → enviado_cocina");

            //
            // 2️⃣ MARCAR TODO COMO ENTREGADO
            //
            $pedido->detalles()->update(['estado' => 'entregado']);
            $this->command->info("   📦 Todos los detalles → entregado");

            //
            // 3️⃣ CALCULAR ESTADO FINAL DEL PEDIDO
            //
            $this->actualizarEstadoPedido($pedido);

            $this->command->info("   ✔ Estado final del pedido: {$pedido->estado}");
        }

        $this->command->info("🎉 Simulación finalizada.");
    }

    private function actualizarEstadoPedido($pedido)
    {
        $detalles = $pedido->detalles;

        $total = $detalles->count();
        $entregados = $detalles->where('estado', 'entregado')->count();

        // Si TODOS los detalles están entregados → listo para cobrar
        if ($entregados === $total) {
            $pedido->update(['estado' => 'listo_para_cobrar']);
            return;
        }

        // Por si acaso, estado de seguridad:
        $pedido->update(['estado' => 'en_preparacion']);
    }
}
