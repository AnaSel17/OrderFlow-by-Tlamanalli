<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController; 


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

Route::resource('usuarios', UsuarioController::class);

