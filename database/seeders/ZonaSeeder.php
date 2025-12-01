<?php

namespace Database\Seeders;

use App\Models\Zona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zonas = [
            [
                'nombre' => 'Interior',
                'descripcion' => 'Salón principal con aire acondicionado.',
                'activa' => true,
                'hora_apertura' => '08:00:00',
                'hora_cierre' => '22:00:00',
                'dias_activos' => ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'],
                'color_hex' => '#4CAF50',
            ],
            [
                'nombre' => 'Terraza',
                'descripcion' => 'Área al aire libre, ideal para fumadores.',
                'activa' => true,
                'hora_apertura' => '10:00:00',
                'hora_cierre' => '20:00:00',
                'dias_activos' => ['Viernes','Sábado','Domingo'],
                'color_hex' => '#FF9800',
            ],
            [
                'nombre' => 'Barra',
                'descripcion' => 'Mostrador rápido para clientes solos o para llevar.',
                'activa' => true,
                'hora_apertura' => '07:00:00',
                'hora_cierre' => '23:00:00',
                'dias_activos' => ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'],
                'color_hex' => '#2196F3',
            ],
        ];

        foreach ($zonas as $zona) {
            Zona::create($zona); // 👈 sin json_encode
        }

        /*
        foreach ($zonas as $zona) {
            Zona::create([
                'nombre'        => $zona['nombre'],
                'descripcion'   => $zona['descripcion'],
                'activa'        => $zona['activa'],
                'hora_apertura' => $zona['hora_apertura'],
                'hora_cierre'   => $zona['hora_cierre'],
                // 👇 aquí forzamos la conversión a JSON real
                'dias_activos'  => json_encode($zona['dias_activos']),
                'color_hex'     => $zona['color_hex'],
            ]);
        }*/
    }
    
}
