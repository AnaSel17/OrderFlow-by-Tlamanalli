<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Importante importar la clase DB!
use Illuminate\Support\Carbon;     // Para usar la fecha y hora actual

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usamos DB::table para insertar los datos.
        DB::table('roles')->insert([
            [
                'nombre' => 'Mesero',
                'descripcion' => 'Encargado de tomar pedidos y atender a los clientes en las mesas.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Cocinero',
                'descripcion' => 'Responsable de la preparación de los platillos en la cocina.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Barista',
                'descripcion' => 'Especialista en la preparación de café y otras bebidas.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Opcional: Puedes dejar un rol de administrador si lo necesitas
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo a la configuración y gestión del sistema.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}