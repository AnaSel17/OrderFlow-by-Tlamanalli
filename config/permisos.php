<?php

return [

    'roles' => [

        // 1. ADMINISTRADOR
        1 => [
            'ver_dashboard',
            'ver_mesas',
            'ver_zonas',
            'asignar_mesas',
            'ver_pedidos',
            'crear_pedido',
            'ver_comandas',
            'ver_devoluciones',
            'ver_caja',
            'ver_cobros',
            'ver_pagos',
            'ver_cuentas_pagadas',
            'ver_tickets',
            'ver_menu',
            'ver_categorias',
            'ver_productos',
            'ver_inventario',
            'ver_reportes',
            'ver_reportes_dia',
            'ver_reportes_producto',
            'ver_usuarios',
            'crear_usuario',
            'ver_roles',
            'ver_actividad',
            'ver_configuracion',
        ],

        // 2. GERENTE GENERAL
        2 => [
            'ver_dashboard',
            'ver_mesas',
            'ver_zonas',
            'asignar_mesas',
            'ver_pedidos',
            'ver_comandas',
            'ver_devoluciones',
            'ver_caja',
            'ver_cobros',
            'ver_pagos',
            'ver_cuentas_pagadas',
            'ver_tickets',
            'ver_menu',
            'ver_categorias',
            'ver_productos',
            'ver_inventario',
            'ver_reportes',
            'ver_reportes_dia',
            'ver_reportes_producto',
            'ver_actividad',
        ],

        // 3. MESERO
        3 => [
            'ver_dashboard',
            'ver_mesas',
            'ver_zonas',
            'asignar_mesas',
            'ver_pedidos',
            'crear_pedido',
            'ver_comandas',
            'ver_devoluciones',
        ],

        // 4. COCINERO
        4 => [
            'ver_dashboard',
            'ver_pedidos',
            'ver_comandas',
        ],

        // 5. CAJERO
        5 => [
            'ver_dashboard',
            'ver_caja',
            'ver_cobros',
            'ver_pagos',
            'ver_cuentas_pagadas',
            'ver_tickets',
            'ver_pedidos',
        ],

    ],

];
