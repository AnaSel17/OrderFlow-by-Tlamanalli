<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [

            // CAFÉ CALIENTE
            ['nombre' => 'Americano', 'precio' => 30, 'categoria' => 'Café Caliente'],
            ['nombre' => 'Latte', 'precio' => 42, 'categoria' => 'Café Caliente'],
            ['nombre' => 'Mocha', 'precio' => 48, 'categoria' => 'Café Caliente'],
            ['nombre' => 'Caramel Latte', 'precio' => 49, 'categoria' => 'Café Caliente'],
            ['nombre' => 'Matcha Latte Caliente', 'precio' => 52, 'categoria' => 'Café Caliente'],

            // CAFÉ FRÍO
            ['nombre' => 'Cold Brew', 'precio' => 45, 'categoria' => 'Café Frío'],
            ['nombre' => 'Iced Latte', 'precio' => 44, 'categoria' => 'Café Frío'],
            ['nombre' => 'Iced Mocha', 'precio' => 49, 'categoria' => 'Café Frío'],

            // FRAPPÉS
            ['nombre' => 'Frappé de Café', 'precio' => 55, 'categoria' => 'Frappés'],
            ['nombre' => 'Frappé Oreo', 'precio' => 58, 'categoria' => 'Frappés'],
            ['nombre' => 'Frappé Caramelo', 'precio' => 57, 'categoria' => 'Frappés'],

            // TÉS E INFUSIONES
            ['nombre' => 'Té Negro', 'precio' => 28, 'categoria' => 'Tés e Infusiones'],
            ['nombre' => 'Té Verde', 'precio' => 28, 'categoria' => 'Tés e Infusiones'],
            ['nombre' => 'Tizana Frutal', 'precio' => 42, 'categoria' => 'Tés e Infusiones'],

            // ESPECIALES
            ['nombre' => 'Chocolate Caliente', 'precio' => 40, 'categoria' => 'Bebidas Especiales'],
            ['nombre' => 'Golden Milk', 'precio' => 48, 'categoria' => 'Bebidas Especiales'],
            ['nombre' => 'Chai Latte', 'precio' => 47, 'categoria' => 'Bebidas Especiales'],

            // DESAYUNOS
            ['nombre' => 'Chilaquiles Verdes', 'precio' => 68, 'categoria' => 'Desayunos'],
            ['nombre' => 'Molletes', 'precio' => 55, 'categoria' => 'Desayunos'],
            ['nombre' => 'Hot Cakes', 'precio' => 60, 'categoria' => 'Desayunos'],

            // SÁNDWICHES
            ['nombre' => 'Sándwich de Pavo', 'precio' => 48, 'categoria' => 'Sándwiches'],
            ['nombre' => 'Sándwich de Atún', 'precio' => 50, 'categoria' => 'Sándwiches'],
            ['nombre' => 'Panini de Pollo', 'precio' => 62, 'categoria' => 'Sándwiches'],

            // PANADERÍA
            ['nombre' => 'Croissant', 'precio' => 28, 'categoria' => 'Panadería'],
            ['nombre' => 'Concha Artesanal', 'precio' => 22, 'categoria' => 'Panadería'],
            ['nombre' => 'Rol de Canela', 'precio' => 26, 'categoria' => 'Panadería'],

            // POSTRES
            ['nombre' => 'Cheesecake', 'precio' => 48, 'categoria' => 'Postres'],
            ['nombre' => 'Brownie', 'precio' => 32, 'categoria' => 'Postres'],
            ['nombre' => 'Pay de Limón', 'precio' => 45, 'categoria' => 'Postres'],
        ];

        foreach ($productos as $p) {
            $categoria = Categoria::where('nombre', $p['categoria'])->first();

            Producto::firstOrCreate(
                ['sku' => strtoupper(Str::slug($p['nombre'], '_'))],
                [
                    'nombre'       => $p['nombre'],
                    'sku'          => strtoupper(Str::slug($p['nombre'], '_')),
                    'precio'       => $p['precio'],
                    'activo'       => true,
                    'categoria_id' => $categoria->id,
                ]
            );
        }

        $this->command->info('☕ Productos premium creados correctamente.');
    }
}
