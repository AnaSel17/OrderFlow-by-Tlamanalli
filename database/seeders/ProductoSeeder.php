<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            // BEBIDAS CALIENTES
            ['nombre' => 'Café Expreso',      'precio' => 25.00, 'categoria' => 'Bebidas Calientes'],
            ['nombre' => 'Capuchino',         'precio' => 38.00, 'categoria' => 'Bebidas Calientes'],
            // BEBIDAS FRÍAS
            ['nombre' => 'Jugo de Naranja',   'precio' => 30.00, 'categoria' => 'Bebidas Frías'],
            ['nombre' => 'Jugo Verde',        'precio' => 32.00, 'categoria' => 'Bebidas Frías'],
            // SANDWICHES Y BAGUETTES
            ['nombre' => 'Sándwich de Pavo',  'precio' => 45.00, 'categoria' => 'Sandwiches y Baguettes'],
            ['nombre' => 'Sándwich de Atún',  'precio' => 48.00, 'categoria' => 'Sandwiches y Baguettes'],
            ['nombre' => 'Baguette de Pechuga', 'precio' => 52.00, 'categoria' => 'Sandwiches y Baguettes'],
            // POSTRES
            ['nombre' => 'Pay de Zarzamora',  'precio' => 42.00, 'categoria' => 'Postres'],
        ];

        foreach ($productos as $p) {
            $categoria = Categoria::where('nombre', $p['categoria'])->first();
            if ($categoria) {
                Producto::firstOrCreate(
                    ['sku' => Str::slug($p['nombre'])],
                    [
                        'nombre'       => $p['nombre'],
                        'sku'          => strtoupper(Str::slug($p['nombre'], '_')),
                        'precio'       => $p['precio'],
                        'activo'       => true,
                        'categoria_id' => $categoria->id,
                    ]
                );
            }
        }

        $this->command->info(' Productos de cafetería creados correctamente.');
    }
}
