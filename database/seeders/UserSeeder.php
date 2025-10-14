<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Obtén los IDs de roles directamente desde la base de datos
        $roles = DB::table('roles')->pluck('id', 'name');

        $users = [
            [
                'role_id' => $roles['admin'] ?? 1,
                'name' => 'Ana Selene',
                'apellido_paterno' => 'Vargas',
                'apellido_materno' => 'Mauricio',
                'email' => 'ana.vargas@tlamanalli.mx',
                'password' => Hash::make('admin123'),
                'telefono' => '5512345678',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => $roles['mesero'] ?? 2,
                'name' => 'Itzel Aide',
                'apellido_paterno' => 'Pacheco',
                'apellido_materno' => 'Vargas',
                'email' => 'itzel.pacheco@tlamanalli.mx',
                'password' => Hash::make('mesero123'),
                'telefono' => '5523456789',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => $roles['cocina'] ?? 3,
                'name' => 'Dulce Monserrat',
                'apellido_paterno' => 'Arguello',
                'apellido_materno' => 'Martinez',
                'email' => 'dulce.arguello@tlamanalli.mx',
                'password' => Hash::make('cocina123'),
                'telefono' => '5534567890',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => $roles['caja'] ?? 4,
                'name' => 'Eric Said',
                'apellido_paterno' => 'Sosa',
                'apellido_materno' => 'Martinez',
                'email' => 'eric.sosa@tlamanalli.mx',
                'password' => Hash::make('caja123'),
                'telefono' => '5545678901',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // 🔹 Insertar directamente (sin modelo) para evitar dependencias
        DB::table('users')->upsert($users, ['email']);
    }
}
