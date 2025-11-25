<?php

namespace Database\Seeders;

use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = Producto::all();

        foreach ($productos as $producto) {
            if (Inventario::where('producto_id', $producto->id)->exists()) {
                continue;
            }

            $stock_actual  = rand(0, 30);
            $punto_reorden = rand(5, 10);

            $estado = $stock_actual <= 0
                ? 'Agotado'
                : ($stock_actual <= $punto_reorden ? 'Bajo' : 'Suficiente');

            Inventario::create([
                'producto_id'   => $producto->id,
                'stock_actual'  => $stock_actual,
                'punto_reorden' => $punto_reorden,
                'estado'        => $estado,
            ]);
        }

        $this->command->info('Inventarios iniciales generados correctamente.');
    }
}
