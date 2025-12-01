<?php

namespace Database\Seeders;

use App\Models\DetallePedido;
use App\Models\Pedido;
use App\Models\Producto;
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
            $this->command->error("❌ No hay productos. Ejecuta ProductoSeeder primero.");
            return;
        }

        $pedidos = Pedido::with('comensales')->get();

        if ($pedidos->isEmpty()) {
            $this->command->warn("⚠️ No hay pedidos. Ejecuta PedidoSeeder primero.");
            return;
        }

        foreach ($pedidos as $pedido) {

            $this->command->info("🍽 Generando detalles para Pedido #{$pedido->id}");

            $totalPedido = 0;

            /* ==========================================================
               ✔ CASO 1: PEDIDO NORMAL → generar por comensal
            ========================================================== */
            if ($pedido->comensales->count() > 0) {
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
                            'estado'         => 'pendiente',
                        ]);

                        $totalPedido += $detalle->cantidad * $detalle->precio_unitario;
                    }
                }
            }

            /* ==========================================================
               ✔ CASO 2: PEDIDO PARA LLEVAR → generar productos generales
            ========================================================== */
            else {
                $items = rand(1, 4); // entre 1 y 4 productos para llevar

                for ($i = 0; $i < $items; $i++) {

                    $producto = $productos->random();

                    $detalle = DetallePedido::create([
                        'pedido_id'      => $pedido->id,
                        'producto_id'    => $producto->id,
                        'comensal_id'    => null,            // ⭐ importante
                        'cantidad'       => rand(1, 3),
                        'precio_unitario'=> $producto->precio,
                        'estado'         => 'pendiente',
                    ]);

                    $totalPedido += $detalle->cantidad * $detalle->precio_unitario;
                }

                $this->command->comment("   🛍 Pedido #{$pedido->id} detectado como PARA LLEVAR");
            }

            // ⭐ Actualizar total
            $pedido->update(['total' => $totalPedido]);

            $this->command->info("   ✔ Total actualizado: $ {$totalPedido}");
        }

        $this->command->info("🎉 Detalles de pedidos generados correctamente.");
    }
}
