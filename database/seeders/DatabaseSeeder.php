<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $this->call([
        RoleSeeder::class,
        UserSeeder::class,
        CategoriaSeeder::class,
        ProductoSeeder::class,
        InventarioSeeder::class,
        ZonaSeeder::class,
        MesaSeeder::class,
        MesaPedidoSeeder::class,
        DetallePedidoSeeder::class,
        //ComandaSeeder::class,
        //SimulacionCocinaSeeder::class,
        
    ]);
        
    }
}
