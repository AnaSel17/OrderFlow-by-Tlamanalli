<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Tlamanalli | Dashboard',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    // --- Logo sidebar (logo arriba + texto abajo) ---
    'logo'            => '<span class="brand-text"></span>',
    'logo_img'        => 'images/logo.png',      
    'logo_img_class'  => 'brand-image',                  
    'logo_img_xl'     => null,
    'logo_img_xl_class'=> 'brand-image',                 
    'logo_img_alt'    => 'Tonalli Logo',


    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled'      => true,
    'usermenu_image'        => true,
    'usermenu_desc'         => true,
    'usermenu_profile_url'  => true,
    'use_route_url'         => true,
    'logout_url'            => 'logout',
    
    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => 'tonalli-bg',     // fondo beige del contenido
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light',          // sidebar claro nativo de AdminLTE
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'tonalli-navbar navbar-expand', // header dorado + expand
    'classes_topnav_nav' => 'navbar-nav',
    'classes_topnav_container' => 'container-fluid',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',
    'custom_css' => 'css/tonalli.css',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

'menu' => [
    // ---- TOPBAR ----
    ['type' => 'navbar-search', 'text' => 'Buscar', 'topnav_right' => true],
    ['type' => 'fullscreen-widget', 'topnav_right' => true],

    // ---- SIDEBAR ----
    ['type' => 'sidebar-menu-search', 'text' => 'Buscar'],
    ['header' => 'OPERACIÓN'],

    // DASHBOARD
    [
        'text' => 'Dashboard',
        'icon' => 'fas fa-home',
        'url'  => '/dashboard',
    ],

    // USUARIOS
    [
        'text' => 'Usuarios',
        'icon' => 'fas fa-user',
        'submenu' => [
            ['text' => 'Listado',           'url' => '/usuarios'],
            ['text' => 'Crear usuario',     'url' => '/usuarios/crear'],
            ['text' => 'Listado Roles',             'url' => '/roles'],         // can: manage-roles
            ['text' => 'Permisos',          'url' => '/permissions'],   // can: manage-permissions
            ['text' => 'Actividad',         'url' => '/users/actividad'], 
        ],
    ],

    // MENÚ (PRODUCTOS)
    [
        'text' => 'Menú',
        'icon' => 'fas fa-list',
        'submenu' => [
            ['text' => 'Categorías',        'url' => '/menu/categories'],
            ['text' => 'Platillos',         'url' => '/menu/items'],
            ['text' => 'Modificadores',     'url' => '/menu/modifiers'],       // extras: leche, tamaño…
            ['text' => 'Combos',            'url' => '/menu/combos'],
            ['text' => 'Precios & costos',  'url' => '/menu/pricing'],
            ['text' => 'Inventario',        'url' => '/inventory'],            // stock por insumo
            ['text' => 'Importar/Exportar', 'url' => '/menu/import-export'],
        ],
    ],

    // PEDIDOS
    [
        'text' => 'Pedidos',
        'icon' => 'fas fa-receipt',
        'submenu' => [
            ['text' => 'Nuevo pedido',      'url' => '/orders/create'],
            ['text' => 'En preparación',    'url' => '/orders/in-progress'],
            ['text' => 'Listos',            'url' => '/orders/ready'],
            ['text' => 'Entregados',        'url' => '/orders/delivered'],
            ['text' => 'Cancelados',        'url' => '/orders/cancelled'],
            ['text' => 'Mesas',             'url' => '/tables'],
            ['text' => 'Comandas',          'url' => '/kds'],                   // pantalla cocina (KDS)
            ['text' => 'Devoluciones',      'url' => '/orders/returns'],
        ],
    ],

    // REPORTES
    [
        'text' => 'Reportes',
        'icon' => 'fas fa-chart-bar',
        'submenu' => [
            ['text' => 'Ventas por día',        'url' => '/reports/sales-daily'],
            ['text' => 'Ventas por producto',    'url' => '/reports/sales-products'],
            ['text' => 'Top clientes',           'url' => '/reports/top-customers'],
            ['text' => 'Pedidos por estado',     'url' => '/reports/orders-status'],
            ['text' => 'Inventario bajo',        'url' => '/reports/low-stock'],
            ['text' => 'Exportar (CSV/PDF)',     'url' => '/reports/export'],
        ],
    ],

    ['header' => 'NEGOCIO'],

    // PROMOCIONES / MARKETING
    [
        'text' => 'Promociones',
        'icon' => 'fas fa-tags',
        'submenu' => [
            ['text' => 'Cupones',              'url' => '/promos/coupons'],
            ['text' => 'Happy hours',          'url' => '/promos/happy-hour'],
            ['text' => 'Programas de lealtad', 'url' => '/promos/loyalty'],
        ],
    ],

    // CONFIGURACIÓN
    [
        'text' => 'Configuración',
        'icon' => 'fas fa-cog',
        'submenu' => [
            ['text' => 'Tienda (datos)',    'url' => '/settings/store'],
            ['text' => 'Impuestos/propina', 'url' => '/settings/tax-tip'],
            ['text' => 'Pagos',             'url' => '/settings/payments'],   // métodos: cash, tarjeta…
            ['text' => 'Impresoras',        'url' => '/settings/printers'],
            ['text' => 'Notificaciones',    'url' => '/settings/notifications'],
            ['text' => 'Usuarios & seguridad','url' => '/settings/security'],
        ],
    ],

    // AYUDA
    [
        'text' => 'Ayuda',
        'icon' => 'fas fa-question-circle',
        'submenu' => [
            ['text' => 'Guía rápida',    'url' => '/help/quickstart'],
            ['text' => 'Preguntas frecuentes', 'url' => '/help/faq'],
            ['text' => 'Soporte',        'url' => '/help/support'],
        ],
    ],
],


    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                'type' => 'js',
                'asset' => false,
                'location' => '//cdn.jsdelivr.net/npm/chart.js', // siempre la última estable
                ],
            ],
        ],

        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'TonalliTheme' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css', 
                    'asset' => true, 
                    'location' => 'css/tonalli.css'],
            ],
             [ 
                'type' => 'css',
                'asset' => true, // true = busca en la carpeta /public
                'location' => 'css/custom-admin.css' // Tu nuevo archivo de estilos
            ],
        ],
    ],
    


    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
