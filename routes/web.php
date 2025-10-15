<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\PermisosController;
use Illuminate\Support\Facades\Auth;

// Redirige la raíz a la página de login
Route::get('/', function () {
    return redirect()->route('login');
});
Auth::routes();

// Grupo de rutas que requieren que el usuario esté autenticado
Route::middleware(['auth'])->group(function () {
    
    // Ruta del dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Rutas para la gestión de usuarios
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::resource('usuarios', UsuarioController::class);


    // Rutas para la gestión de roles
    Route::get('/roles', function () {
    return view('usuarios.roles.roles');
})->name('roles.index');

Route::get('/roles/create', function () {
    return view('usuarios.roles.create_roles');
})->name('roles.create');

Route::get('/roles/{id}', function ($id) {
    return view('usuarios.roles.show', ['id' => $id]);
})->name('roles.show');


    //Ruta para la interfaz de actividad
    Route::get('/users/actividad', [ActividadController::class, 'index'])->name('actividad.index');

    // Ruta para la interfaz de permisos
   Route::get('/permissions', [PermisosController::class, 'index'])->name('permissions.index');


    

    
});
