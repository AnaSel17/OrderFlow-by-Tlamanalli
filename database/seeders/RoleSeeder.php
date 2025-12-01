<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [

            // ==============================
            // GERENCIAL / ADMINISTRATIVO
            // ==============================
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Acceso completo a la configuración y gestión del sistema.',
                'categoria' => 'Administrativo',
                'permisos' => [
                    'Gestión completa',
                    'Reportes avanzados',
                    'Administración de personal',
                    'Inventario',
                    'Finanzas',
                    'Configuración del sistema'
                ],
            ],
            [
                'nombre' => 'Gerente General',
                'descripcion' => 'Supervisa todas las operaciones del restaurante y toma decisiones estratégicas.',
                'categoria' => 'Gerencial',
                'permisos' => [
                    'Reportes',
                    'Gestión completa',
                    'Supervisión',
                    'Gestión de turnos',
                    'Finanzas'
                ],
            ],

            // ==============================
            // OPERATIVOS
            // ==============================
            [
                'nombre' => 'Mesero',
                'descripcion' => 'Encargado de tomar pedidos y atender a los clientes en mesa.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Tomar pedidos',
                    'Atención al cliente',
                    'Gestión de mesas',
                    'Procesar pagos'
                ],
            ],
            [
                'nombre' => 'Cocinero',
                'descripcion' => 'Responsable de la preparación de los platillos.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Preparar alimentos',
                    'Gestión de ingredientes',
                    'Control de calidad'
                ],
            ],
            [
                'nombre' => 'Cajero',
                'descripcion' => 'Encargado de cobrar y registrar pagos.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Procesar pagos',
                    'Corte de caja',
                    'Atención al cliente'
                ],
            ],


        ];

        foreach ($roles as $rol) {
            Role::updateOrCreate(
                ['nombre' => $rol['nombre']],
                [
                    'descripcion' => $rol['descripcion'],
                    'categoria' => $rol['categoria'],
                    'permisos' => $rol['permisos'],
                ]
            );
        }

        $this->command->info('Roles creados y actualizados correctamente.');
    }
}
