<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermisosController extends Controller
{
    /**
     * Muestra la vista de gestión de permisos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retorna la vista que contiene la interfaz de permisos
       return view('usuarios.permisos');
    }
}
