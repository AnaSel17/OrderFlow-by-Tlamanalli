<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Bebidas Calientes', 'descripcion' => 'Cafés, tés y bebidas preparadas con leche o espresso.'],
            ['nombre' => 'Bebidas Frías', 'descripcion' => 'Jugos naturales y bebidas frías refrescantes.'],
            ['nombre' => 'Sandwiches y Baguettes', 'descripcion' => 'Opciones saladas listas para comer.'],
            ['nombre' => 'Postres', 'descripcion' => 'Rebanadas dulces y panadería artesanal.'],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }

        $this->command->info('✅ Categorías de cafetería creadas correctamente.');
    }
}
