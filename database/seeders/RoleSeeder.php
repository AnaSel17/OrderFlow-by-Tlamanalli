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
                'nombre' => 'Chef Pastelero',
                'descripcion' => 'Encargado de la elaboración de postres y repostería.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Preparar productos',
                    'Gestión de ingredientes',
                    'Control de calidad'
                ],
            ],
            [
                'nombre' => 'Barista',
                'descripcion' => 'Especialista en café y bebidas artesanales.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Preparar bebidas',
                    'Atención al cliente',
                    'Gestión de inventario de café'
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

            // ==============================
            // SOPORTE Y LOGÍSTICA
            // ==============================
            [
                'nombre' => 'Repartidor',
                'descripcion' => 'Entrega pedidos a domicilio garantizando tiempos y calidad.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Entregar pedidos',
                    'Rutas de entrega',
                    'Atención al cliente'
                ],
            ],
            [
                'nombre' => 'Almacenista',
                'descripcion' => 'Encargado del inventario y control de productos.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Inventario',
                    'Gestión de insumos',
                    'Recepción de mercancía'
                ],
            ],
            [
                'nombre' => 'Auxiliar de Limpieza',
                'descripcion' => 'Mantiene las áreas del restaurante limpias y desinfectadas.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Limpieza general',
                    'Manejo de residuos'
                ],
            ],

            // ==============================
            // ESPECIALIDADES
            // ==============================
            [
                'nombre' => 'Hostess',
                'descripcion' => 'Recibe a los clientes y asigna mesas.',
                'categoria' => 'Operativo',
                'permisos' => [
                    'Asignar mesas',
                    'Atención al cliente',
                    'Organización de sala'
                ],
            ],
            [
                'nombre' => 'Supervisor de Turno',
                'descripcion' => 'Supervisa al personal durante su turno y asegura el cumplimiento de procesos.',
                'categoria' => 'Gerencial',
                'permisos' => [
                    'Supervisión',
                    'Gestión de turnos',
                    'Reportes'
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
