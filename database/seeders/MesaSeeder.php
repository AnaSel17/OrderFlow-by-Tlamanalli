<?php

namespace Database\Seeders;

use App\Models\Mesa;
use App\Models\Zona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $zonas = Zona::all();

        if ($zonas->isEmpty()) {
            $this->command->warn('⚠️ No hay zonas registradas, ejecuta el ZonaSeeder primero.');
            return;
        }

        $data = [
            // 🔹 Interior
            ['codigo' => 'M01', 'capacidad' => 4, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M02', 'capacidad' => 2, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M03', 'capacidad' => 6, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M04', 'capacidad' => 8, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M05', 'capacidad' => 4, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M06', 'capacidad' => 4, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M07', 'capacidad' => 6, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M08', 'capacidad' => 2, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M09', 'capacidad' => 4, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M10', 'capacidad' => 4, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M11', 'capacidad' => 6, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M12', 'capacidad' => 2, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M13', 'capacidad' => 4, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M14', 'capacidad' => 4, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M15', 'capacidad' => 6, 'tipo' => 'mesa', 'zona' => 'Interior'],
            ['codigo' => 'M16', 'capacidad' => 2, 'tipo' => 'mesa', 'zona' => 'Interior'],

            // 🔹 Terraza
            ['codigo' => 'T01', 'capacidad' => 4, 'tipo' => 'terraza', 'zona' => 'Terraza'],
            ['codigo' => 'T02', 'capacidad' => 4, 'tipo' => 'terraza', 'zona' => 'Terraza'],
            ['codigo' => 'T03', 'capacidad' => 6, 'tipo' => 'terraza', 'zona' => 'Terraza'],
            ['codigo' => 'T04', 'capacidad' => 2, 'tipo' => 'terraza', 'zona' => 'Terraza'],

            // 🔹 Barra
            ['codigo' => 'B01', 'capacidad' => 1, 'tipo' => 'barra', 'zona' => 'Barra'],
            ['codigo' => 'B02', 'capacidad' => 1, 'tipo' => 'barra', 'zona' => 'Barra'],
            ['codigo' => 'B03', 'capacidad' => 1, 'tipo' => 'barra', 'zona' => 'Barra'],
            ['codigo' => 'B04', 'capacidad' => 1, 'tipo' => 'barra', 'zona' => 'Barra'],
        ];

        foreach ($data as $mesa) {
            $zona = $zonas->where('nombre', $mesa['zona'])->first();

            Mesa::create([
                'codigo'       => $mesa['codigo'],
                'capacidad'    => $mesa['capacidad'],
                'tipo'         => $mesa['tipo'],
                'zona_id'      => $zona->id ?? 1,
                'sillas_extra' => 0,
                'estado'       => 'disponible',
            ]);
        }

        $this->command->info('🪑 Mesas creadas correctamente.');
    
    }
}
