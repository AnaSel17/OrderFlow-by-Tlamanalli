<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin',   'description' => 'Administrador general del sistema', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'mesero',  'description' => 'Atiende mesas y registra pedidos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cocina',  'description' => 'Recibe y prepara pedidos', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'caja',    'description' => 'Gestiona cobros y pagos de cuentas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
