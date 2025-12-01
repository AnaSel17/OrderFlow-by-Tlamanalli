<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CobroController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;
use App\Models\DetallePedido;
use App\Models\Producto;

/*
|--------------------------------------------------------------------------
| Redirección raíz al login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas autenticadas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::resource('usuarios', UsuarioController::class);


    /*
    |--------------------------------------------------------------------------
    | ROLES (TUS VISTAS PERSONALIZADAS)
    |--------------------------------------------------------------------------
    */
    



    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('roles.create');
    Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('roles.edit');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RolesController::class, 'show'])->name('roles.show');
    Route::resource('roles', RolesController::class);




    /*
    |--------------------------------------------------------------------------
    | ACTIVIDAD / PERMISOS
    |--------------------------------------------------------------------------
    */
    Route::get('/users/actividad', [ActividadController::class, 'index'])->name('actividad.index');
    Route::get('/permissions', [PermisosController::class, 'index'])->name('permissions.index');


    /*
    |--------------------------------------------------------------------------
    | CATEGORÍAS / PRODUCTOS / INVENTARIO
    |--------------------------------------------------------------------------
    */
    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('inventarios', InventarioController::class);


    /*
    |--------------------------------------------------------------------------
    | MESAS (PRIMERO TUS RUTAS PERSONALIZADAS)
    |--------------------------------------------------------------------------
    */
    Route::get('/mesas/asignar', [MesaController::class, 'asignar'])->name('mesas.asignar');
    Route::post('/mesas/asignar-mesas', [MesaController::class, 'asignarMesas'])->name('mesas.asignarMesas');
    Route::post('/mesas/agregar-sillas', [MesaController::class, 'agregarSillas'])->name('mesas.agregarSillas');

    // Resource al final
    Route::resource('mesas', MesaController::class);


    /*
    |--------------------------------------------------------------------------
    | PEDIDOS — TODAS LAS RUTAS PERSONALIZADAS ANTES DEL RESOURCE
    |--------------------------------------------------------------------------
    */

    // Cobrar
    Route::get('/pedidos/{pedido}/cobrar', [PedidoController::class, 'cobrar'])
        ->name('pedidos.cobrar');
    Route::patch('/pedidos/{pedido}/cobrar',
    [CobroController::class, 'finalizarCobro'])
    ->name('pedidos.finalizarCobro');

    // Ticket
    Route::get('/pedidos/{pedido}/ticket', [PedidoController::class, 'ticket'])
        ->name('pedidos.ticket');

    // Marcar entregado (PEDIDO)
    Route::patch('/pedidos/{pedido}/entregar', [PedidoController::class, 'marcarEntregado'])
        ->name('pedidos.entregar');

    // AHORA sí, el resource
    Route::resource('pedidos', PedidoController::class);


    //Route::patch('/pedidos/{pedido}/cobrar',     [CobroController::class, 'finalizarCobro']
//)       ->name('pedidos.finalizarCobro');



    Route::get('/pagos', [PagoController::class, 'index'])
    ->name('pagos.index');

    Route::get('/cuentas/abiertas', [CuentaController::class, 'abiertas'])->name('cuentas.abiertas');
Route::get('/cuentas/pagadas', [CuentaController::class, 'pagadas'])->name('cuentas.pagadas');

Route::get('/tickets', [CuentaController::class, 'tickets'])->name('tickets.index');

Route::get('/menu', function () {
    $categorias = \App\Models\Categoria::with('productos')->get();
    return view('menu.index', compact('categorias'));
})->name('menu.index');


Route::prefix('reportes')->name('reportes.')->group(function () {
    
    Route::get('/', [ReporteController::class, 'index'])->name('index');

    Route::get('/ventas-dia', [ReporteController::class, 'ventasDia'])->name('ventas-dia');
    Route::get('/ventas-producto', [ReporteController::class, 'ventasProducto'])->name('ventas-producto');
    Route::get('/top-clientes', [ReporteController::class, 'topClientes'])->name('top-clientes');
    Route::get('/pedidos-estado', [ReporteController::class, 'pedidosEstado'])->name('pedidos-estado');
    Route::get('/inventario-bajo', [ReporteController::class, 'inventarioBajo'])->name('inventario-bajo');

    Route::get('/ventas-ultimos-7-dias', [ReporteController::class, 'ventasUltimos7Dias'])
    ->name('ventas.ultimos7dias');


    Route::get('/exportar', [ReporteController::class, 'exportar'])->name('exportar');

});
    /*
    |--------------------------------------------------------------------------
    | DETALLES DE PEDIDOS
    |--------------------------------------------------------------------------
    */

    // Acciones personalizadas primero
    Route::patch('/detalles/{detalle}/preparar', [DetallePedidoController::class, 'marcarEnPreparacion'])
        ->name('detalles.preparar');

    Route::patch('/detalles/{detalle}/listo', [DetallePedidoController::class, 'marcarListo'])
        ->name('detalles.listo');

    Route::patch('/detalles/{detalle}/entregar', [DetallePedidoController::class, 'marcarEntregado'])
        ->name('detalles.entregar');

    Route::patch('/detalle-pedido/{detallePedido}/cancelar', [DetallePedidoController::class, 'cancelar'])
        ->name('detallePedido.cancelar');

    // Acciones grupales
    Route::post('/detalles/preparar-grupo', [DetallePedidoController::class, 'prepararGrupo'])
        ->name('detalles.preparar.grupo');

    Route::post('/detalles/listo-grupo', [DetallePedidoController::class, 'listoGrupo'])
        ->name('detalles.listo.grupo');

    Route::patch('/detalles/entregar-seleccionados', [DetallePedidoController::class, 'entregarSeleccionados'])
        ->name('detalles.entregar.seleccionados');

    // Resource al final
    Route::resource('detalle_pedidos', DetallePedidoController::class);




    /*
    |--------------------------------------------------------------------------
    | COMANDAS
    |--------------------------------------------------------------------------
    */

    // Personalizadas primero
    Route::post('/comandas/{id}/start', [ComandaController::class, 'start'])->name('comandas.start');
    Route::post('/comandas/{id}/finish', [ComandaController::class, 'finish'])->name('comandas.finish');

    // Resource
    Route::resource('comandas', ComandaController::class);


    /*
    |--------------------------------------------------------------------------
    | ZONAS
    |--------------------------------------------------------------------------
    */
    Route::resource('zonas', ZonaController::class);
});
