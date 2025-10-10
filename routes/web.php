<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;

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
    
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::resource('usuarios', UsuarioController::class);

});
