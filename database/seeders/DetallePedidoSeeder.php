<?php

namespace Database\Seeders;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetallePedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = Producto::all();

        if ($productos->isEmpty()) {
            $this->command->error("❌ No hay productos. Ejecuta primero ProductoSeeder.");
            return;
        }

        $pedidos = Pedido::with('comensales')->get();

        if ($pedidos->isEmpty()) {
            $this->command->warn("⚠️ No hay pedidos. Ejecuta MesaPedidoSeeder primero.");
            return;
        }

        foreach ($pedidos as $pedido) {

            $this->command->info("🍽 Generando detalles para Pedido #{$pedido->id}");

            $totalPedido = 0;

            foreach ($pedido->comensales as $comensal) {

                // Cada comensal pide de 1 a 3 productos
                $itemsComensal = rand(1, 3);

                for ($i = 0; $i < $itemsComensal; $i++) {

                    $producto = $productos->random();

                    $detalle = DetallePedido::create([
                        'pedido_id'      => $pedido->id,
                        'producto_id'    => $producto->id,
                        'comensal_id'    => $comensal->id,
                        'cantidad'       => rand(1, 2),
                        'precio_unitario'=> $producto->precio,
                        'estado'         => 'pendiente', // 👈 cocina empieza así
                    ]);

                    $totalPedido += $detalle->cantidad * $detalle->precio_unitario;
                }
            }

            // 🔹 Actualizar total del pedido
            $pedido->update(['total' => $totalPedido]);

            $this->command->info("   ✔ Total actualizado: $ {$totalPedido}");
        }

        $this->command->info("🎉 Detalles de pedidos generados correctamente.");
    }
}
