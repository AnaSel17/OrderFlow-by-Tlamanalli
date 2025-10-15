<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActividadController extends Controller
{
    /**
     * Muestra el historial de actividad
     */
    public function index()
    {
         return view('usuarios.actividad.actividad');
    }
}
