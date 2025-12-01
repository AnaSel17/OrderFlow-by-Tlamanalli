<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Café Caliente',      'descripcion' => 'Espresso, lattes y bebidas calientes.'],
            ['nombre' => 'Café Frío',          'descripcion' => 'Cold brew y bebidas frías con café.'],
            ['nombre' => 'Frappés',            'descripcion' => 'Bebidas frappé cremosas y dulces.'],
            ['nombre' => 'Tés e Infusiones',   'descripcion' => 'Tés calientes y fríos artesanales.'],
            ['nombre' => 'Bebidas Especiales', 'descripcion' => 'Opciones gourmet y de temporada.'],
            ['nombre' => 'Desayunos',          'descripcion' => 'Platillos ligeros para comenzar el día.'],
            ['nombre' => 'Sándwiches',         'descripcion' => 'Opciones saladas preparadas al momento.'],
            ['nombre' => 'Panadería',          'descripcion' => 'Pan dulce, croissants y piezas horneadas.'],
            ['nombre' => 'Postres',            'descripcion' => 'Repostería artesanal y rebanadas.'],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }

        $this->command->info('🍽 Categorías gourmet creadas correctamente.');
    }
}
