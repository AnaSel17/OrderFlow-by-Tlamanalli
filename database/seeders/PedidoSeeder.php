<?php

namespace Database\Seeders;

use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // Obtener algunos usuarios tipo mesero
            $usuarios = User::take(3)->get();
            $mesas = Mesa::all();

            if ($usuarios->isEmpty() || $mesas->isEmpty()) {
                $this->command->warn('⚠️ No hay usuarios o mesas para crear pedidos.');
                return;
            }

            // Ejemplo 1: Pedido con una sola mesa
            $pedido1 = Pedido::create([
                'usuario_id' => $usuarios->random()->id,
                'estado' => 'pendiente',
                'total' => 0,
                'propina' => 0,
                'abierta_en' => now()->subMinutes(30),
            ]);
            $pedido1->mesas()->attach($mesas->where('codigo', 'M01')->first()?->id);

            // Ejemplo 2: Pedido que combina dos mesas (M02 + M03)
            $pedido2 = Pedido::create([
                'usuario_id' => $usuarios->random()->id,
                'estado' => 'en_preparacion',
                'total' => 250.00,
                'propina' => 25.00,
                'abierta_en' => now()->subHour(),
            ]);
            $pedido2->mesas()->attach(
                $mesas->whereIn('codigo', ['M02', 'M03'])->pluck('id')
            );

            // Ejemplo 3: Pedido grande (3 mesas combinadas)
            $pedido3 = Pedido::create([
                'usuario_id' => $usuarios->random()->id,
                'estado' => 'pendiente',
                'total' => 0,
                'propina' => null,
                'abierta_en' => now(),
            ]);
            $pedido3->mesas()->attach(
                $mesas->whereIn('codigo', ['T01', 'T02', 'T03'])->pluck('id')
            );

            $this->command->info('✅ Seeder de pedidos con mesas combinadas ejecutado correctamente.');
        });
    }
}
