<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        $totalRoles = $roles->count();
        $totalUsuarios = User::count();
        $totalCategorias = $roles->groupBy('categoria')->count();

        return view('usuarios.roles.roles', compact(
            'roles',
            'totalRoles',
            'totalUsuarios',
            'totalCategorias'
        ));
    }

    public function create()
    {
        return view('usuarios.roles.create_roles');
    }

    public function edit($id)
    {
        $rol = Role::findOrFail($id);

        // Lista base de permisos sugeridos (misma que en create)
        $permisosBase = [
            'Gestión completa',
            'Reportes',
            'Administración de personal',
            'Finanzas',
            'Inventario',
            'Atención al cliente',
            'Tomar pedidos',
            'Procesar pagos',
            'Gestión de mesas',
            'Supervisión',
            'Gestión de turnos',
            'Configuración del sistema'
        ];

        return view('usuarios.roles.edit_roles', compact('rol', 'permisosBase'));
    }

    public function update(Request $request, $id)
    {
        $rol = Role::findOrFail($id);

        $request->validate([
            'nombre' => 'required|unique:roles,nombre,' . $rol->id_rol . ',id_rol',
            'descripcion' => 'nullable',
            'categoria' => 'required'
        ]);

        // Permisos enviados
        $permisos = $request->permisos ?? [];

        // Permiso personalizado
        if ($request->filled('permiso_custom')) {
            $permisos[] = $request->permiso_custom;
        }

        $rol->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'permisos' => $permisos
        ]);

        return redirect()->route('roles.show', $rol->id_rol)
            ->with('success', 'Rol actualizado correctamente.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:roles,nombre',
            'descripcion' => 'nullable',
            'categoria' => 'required'
        ]);

        // Permisos enviados como array
        $permisos = $request->permisos ?? [];

        // Permiso personalizado
        if ($request->filled('permiso_custom')) {
            $permisos[] = $request->permiso_custom;
        }

        Role::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'permisos' => $permisos
        ]);

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    public function show($id)
    {
        $rol = Role::findOrFail($id);
        return view('usuarios.roles.show', compact('rol'));
    }

    public function destroy($id)
{
    $rol = Role::findOrFail($id);

    // Verificar si hay usuarios usando este rol
    if ($rol->usuarios()->count() > 0) {
        return redirect()->route('roles.show', $rol->id_rol)
            ->with('error', 'No puedes eliminar este rol porque tiene usuarios asignados.');
    }

    $rol->delete();

    return redirect()->route('roles.index')
        ->with('success', 'Rol eliminado correctamente.');
}

}
