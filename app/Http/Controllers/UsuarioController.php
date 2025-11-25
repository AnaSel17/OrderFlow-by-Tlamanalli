<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use App\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios, excluyendo a los administradores.
     */
    public function index(Request $request)
    {
        // 🔹 Cargar usuarios con su rol, excluyendo al Administrador
    $query = \App\Models\User::with('rol')
        ->whereHas('rol', function ($q) {
            $q->where('nombre', '!=', 'Administrador');
        });

    // 🔹 Filtro de búsqueda por nombre o apellidos (sin importar acentos o mayúsculas)
    if ($request->filled('search')) {
        $search = mb_strtolower(trim($request->search));

        // Normaliza acentos
        $normalizedSearch = str_replace(
            ['á','é','í','ó','ú','Á','É','Í','Ó','Ú'],
            ['a','e','i','o','u','a','e','i','o','u'],
            $search
        );

        $query->where(function ($q) use ($normalizedSearch) {
            $q->whereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u')) LIKE ?", ["%{$normalizedSearch}%"])
              ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(apellido_paterno,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u')) LIKE ?", ["%{$normalizedSearch}%"])
              ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(apellido_materno,'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u')) LIKE ?", ["%{$normalizedSearch}%"]);
        });
    }

    // 🔹 Filtro por rol (id_rol viene del select)
    if ($request->filled('rol')) {
        $query->where('id_rol', $request->rol);
    }

    // 🔹 Orden y paginación
    $usuarios = $query->orderBy('id', 'asc')->paginate(10);

    // 🔹 Roles sin incluir Administrador
    $roles = \App\Models\Role::where('nombre', '!=', 'Administrador')->get();

    // 🔹 Mantiene filtros en la vista
    return view('usuarios.index', compact('usuarios', 'roles'))
        ->with('search', $request->search)
        ->with('rol', $request->rol);
    
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        $roles = Role::where('nombre', '!=', 'Administrador')->get();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'id_rol' => $request->id_rol,
            'user_estado' => 'activo',
        ]);

        return redirect()->route('usuarios.index')
                         ->with('success', '¡Usuario creado exitosamente!');
    }

    /**
     * Muestra los detalles de un usuario específico.
     * ESTE ES EL MÉTODO AGREGADO
     */
    public function show(User $usuario)
    {
        // Gracias al Route Model Binding, Laravel ya nos entrega el usuario encontrado.
        // Solo necesitamos pasarlo a la vista.
        // Asegúrate de tener una vista en: resources/views/usuarios/show.blade.php
        return view('usuarios.show', compact('usuario'));
    }


    /**
     * Muestra el formulario para editar un usuario específico.
     */
    public function edit(User $usuario) 
    {
        $roles = Role::where('nombre', '!=', 'Administrador')->get();
        return view('usuarios.create', compact('usuario', 'roles'));
    }

    /**
     * Actualiza un usuario específico en la base de datos.
     */
    public function update(UserRequest $request, User $usuario) 
    {
        $validated = $request->validated();

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    
    public function destroy(User $usuario)
    {
        if (Auth::id() == $usuario->id) {
            return redirect()->route('usuarios.index')
                             ->with('error', '¡No puedes eliminar tu propio usuario!');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
