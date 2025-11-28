<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:roles',
            'descripcion' => 'nullable'
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }
}
