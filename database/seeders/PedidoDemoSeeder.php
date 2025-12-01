<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Comensale;
use App\Models\DetallePedido;
use App\Models\Cuenta;
use App\Models\CuentaDetalle;
use App\Models\Pago;
use Carbon\Carbon;

class PedidoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $mesas = Mesa::all();
        $usuarios = User::all();
        $productos = Producto::all();

        if ($mesas->isEmpty() || $usuarios->isEmpty() || $productos->isEmpty()) {
            dd("Faltan mesas, usuarios o productos para generar pedidos demo.");
        }

        // 🌟 Generar 50 pedidos del último mes
        for ($i = 0; $i < 50; $i++) {

            $fecha = Carbon::now()->subDays(rand(0, 30))->setTime(rand(10, 22), rand(0, 59));

            $mesa = $mesas->random();
            $usuario = $usuarios->random();

            // Crear pedido SIN mesa_id (no existe esa columna)
            $pedido = Pedido::create([
                'usuario_id'  => $usuario->id,
                'estado'      => 'pagado',
                'total'       => 0,
                'created_at'  => $fecha,
                'updated_at'  => $fecha,
            ]);

            // Asignar mesa en la tabla pivote
            $pedido->mesas()->attach($mesa->id);

            // Comensales (1 a 5 personas)
            $numCom = rand(1, 5);
            $comensales = [];

            for ($c = 1; $c <= $numCom; $c++) {
                $com = Comensale::create([
                    'pedido_id' => $pedido->id,
                    'numero'    => $c,
                ]);
                $comensales[] = $com;
            }

            // Crear detalles (de 3 a 8 productos)
            $totalPedido = 0;
            $detalles = [];

            foreach (range(1, rand(3, 8)) as $d) {
                $producto = $productos->random();
                $cantidad = rand(1, 3);
                $precio = $producto->precio;

                $comensal = rand(0, 1) ? $comensales[array_rand($comensales)] : null;

                $detalle = DetallePedido::create([
                    'pedido_id'      => $pedido->id,
                    'producto_id'    => $producto->id,
                    'comensal_id'    => $comensal?->id,
                    'cantidad'       => $cantidad,
                    'precio_unitario' => $precio,
                    'estado'         => 'pagado',
                ]);

                $subtotal = $cantidad * $precio;
                $totalPedido += $subtotal;

                $detalles[] = [
                    'detalle'  => $detalle,
                    'subtotal' => $subtotal,
                    'comensal' => $comensal,
                ];
            }

            // Propina aleatoria
            $propina = round($totalPedido * (rand(5, 15) / 100), 2);
            $totalFinal = $totalPedido + $propina;

            // Crear cuenta COMPLETA (para simplificar)
            $cuenta = Cuenta::create([
                'pedido_id' => $pedido->id,
                'tipo'      => 'completa',
                'subtotal'  => $totalPedido,
                'propina'   => $propina,
                'descuento' => 0,
                'total'     => $totalFinal,
                'estado'    => 'pagada',
                'created_at' => $fecha,
            ]);

            // Cuenta_detalles
            foreach ($detalles as $d) {
                CuentaDetalle::create([
                    'cuenta_id'        => $cuenta->id,
                    'detalle_id'       => $d['detalle']->id,
                    'comensal_id'      => $d['comensal']?->id,
                    'cantidad_asignada' => $d['detalle']->cantidad,
                    'precio_unitario'  => $d['detalle']->precio_unitario,
                    'subtotal_asignado' => $d['subtotal'],
                    'asignado_completo' => 1,
                    'created_at'       => $fecha,
                ]);
            }

            // Pago (efectivo o tarjeta)
            Pago::create([
                'cuenta_id'    => $cuenta->id,
                'metodo'       => rand(0, 1) ? 'efectivo' : 'tarjeta',
                'monto'        => $totalFinal,
                'recibido_por' => $usuario->id,
                'created_at'   => $fecha,
            ]);

            // Actualizar total del pedido
            $pedido->update(['total' => $totalFinal]);
        }

        echo "✔ Se generaron pedidos demo con cuentas y pagos.";
    }
}
