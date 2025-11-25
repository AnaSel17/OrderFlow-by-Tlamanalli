<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Importante importar la clase DB!
use Illuminate\Support\Carbon;     // Para usar la fecha y hora actual
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::pluck('id_rol', 'nombre');

        $usuarios = [
            ['name' => 'Ana Selene', 'apellido_paterno' => 'Vargas', 'apellido_materno' => 'Mauricio', 'email' => 'selene@tlamanalli.com', 'telefono' => '5551234567', 'rol' => 'Administrador'],
            ['name' => 'Itzel Aide', 'apellido_paterno' => 'Pacheco', 'apellido_materno' => 'Vargas', 'email' => 'itzel@tlamanalli.com', 'telefono' => '5559876543', 'rol' => 'Mesero'],
            ['name' => 'Dulce Monserrat', 'apellido_paterno' => 'Arguello', 'apellido_materno' => 'Martínez', 'email' => 'dulce@tlamanalli.com', 'telefono' => '5554567890', 'rol' => 'Cocinero'],
            ['name' => 'Eric Said', 'apellido_paterno' => 'Sosa', 'apellido_materno' => 'Martínez', 'email' => 'eric@tlamanalli.com', 'telefono' => '5553219876', 'rol' => 'Cajero'],
        ];

        foreach ($usuarios as $u) {
            if (!isset($roles[$u['rol']])) {
                continue;
            }

            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'id_rol'           => $roles[$u['rol']],
                    'name'             => $u['name'],
                    'apellido_paterno' => $u['apellido_paterno'],
                    'apellido_materno' => $u['apellido_materno'],
                    'telefono'         => $u['telefono'],
                    'password'         => Hash::make('Tlamanalli2025!'),
                    'user_estado'      => 'activo',
                ]
            );
        }

        $this->command->info('✅ Usuarios de prueba creados correctamente.');
    
    }
    
    
}