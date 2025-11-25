<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Importante importar la clase DB!
use Illuminate\Support\Carbon;     // Para usar la fecha y hora actual
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $roles = [
            [
                'nombre' => 'Mesero',
                'descripcion' => 'Encargado de tomar pedidos y atender a los clientes en las mesas.',
            ],
            [
                'nombre' => 'Cocinero',
                'descripcion' => 'Responsable de la preparación de los platillos en la cocina.',
            ],
            [
                'nombre' => 'Barista',
                'descripcion' => 'Especialista en la preparación de café y otras bebidas.',
            ],
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo a la configuración y gestión del sistema.',
            ],
            [
                'nombre' => 'Cajero',
                'descripcion' => 'Encargado de cobrar y registrar los pagos de los clientes.',
            ],
        ];

         foreach ($roles as $rol) {
            Role::firstOrCreate(['nombre' => $rol['nombre']], $rol);
        }

        $this->command->info('Roles (Mesero, Cocinero, Barista, Administrador) creados correctamente.');
    
    }
    
}