<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Verificar si el usuario ya existe para evitar duplicados
        $adminEmail = 'admin@miempresa.com';

        if (User::where('email', $adminEmail)->exists()) {
            $this->command->warn('El usuario administrador ya existe. Saltando creación.');
            return;
        }

        // 2. Crear el usuario administrador
        User::create([
            'id_rol' => 4, // **IMPORTANTE: Asume que el ID del rol de Administrador es 1**
            'name' => 'Admin Principal',
            'apellido_paterno' => 'Sistema',
            'apellido_materno' => 'Master',
            'email' => $adminEmail,
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password'), // ¡CAMBIA ESTA CONTRASEÑA EN PRODUCCIÓN!
            'telefono' => '5512345678',
            'user_estado' => 'activo',
            'last_login_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $this->command->info('Usuario Administrador creado exitosamente.');
    }
}
