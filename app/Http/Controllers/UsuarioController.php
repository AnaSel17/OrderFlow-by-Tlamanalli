<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios, excluyendo a los administradores.
     */
    public function index(Request $request)
    {
        // Se inicia la consulta filtrando para excluir a los usuarios
        // cuyo rol tenga el nombre 'Administrador'.
        $query = User::whereHas('rol', function ($q) {
            $q->where('nombre', '!=', 'Administrador');
        });

        // Filtro de búsqueda por 'name' y 'apellido_paterno'
        $query->when($request->input('search'), function ($q, $search) {
            return $q->where('name', 'like', "%{$search}%")
                     ->orWhere('apellido_paterno', 'like', "%{$search}%");
        });

        // Filtro de rol
        $query->when($request->input('rol'), function ($q, $rolId) {
            return $q->where('id_rol', $rolId);
        });

        // Pagina los usuarios y precarga la relación 'rol'
        $usuarios = $query->with('rol')->paginate(10); 
        
        // Para el filtro de la vista, también obtenemos los roles que NO son Administrador
        $roles = Rol::where('nombre', '!=', 'Administrador')->get();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        $roles = Rol::where('nombre', '!=', 'Administrador')->get();
        return view('usuarios.create', compact('roles'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:15',
            'id_rol' => 'required|exists:roles,id_rol',
        ]);

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
        $roles = Rol::where('nombre', '!=', 'Administrador')->get();
        return view('usuarios.create', compact('usuario', 'roles'));
    }

    /**
     * Actualiza un usuario específico en la base de datos.
     */
    public function update(Request $request, User $usuario) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)], 
            'telefono' => 'nullable|string|max:15',
            'id_rol' => 'required|exists:roles,id_rol',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

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
