<?php

namespace App\Http\Controllers;

use App\Models\Usuario; // Se usa el modelo Usuario
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
// No es necesario importar Hash si el modelo ya se encarga del cifrado

class UsuarioController extends Controller
{
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
    
        // 1. Validación con los nombres de columna correctos y la tabla 'usuarios'
        $request->validate([
            'user_nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:15',
            'id_rol' => 'required|exists:roles,id_rol',
        ]);

        // 2. Se crea el registro usando el modelo Usuario
        Usuario::create([
            'user_nombre' => $request->user_nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'email' => $request->email,
            'password' => $request->password, // El modelo se encarga de cifrarlo gracias al $cast
            'telefono' => $request->telefono,
            'id_rol' => $request->id_rol,
            'user_estado' => 'activo',
        ]);

        // 3. Redirección
        return redirect()->route('usuarios.index')
                         ->with('success', '¡Usuario creado exitosamente!');
    }

    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index(Request $request)
    {
        // Se inicia la consulta con el modelo Usuario
        $query = Usuario::query();


        // Filtro de búsqueda con las columnas correctas
        $query->when($request->input('search'), function ($q, $search) {
            return $q->where('user_nombre', 'like', "%{$search}%")
                     ->orWhere('apellido_paterno', 'like', "%{$search}%");
        });

        // Filtro de rol con la columna correcta
        $query->when($request->input('rol'), function ($q, $rolId) {
            return $q->where('id_rol', $rolId);
        });

        $usuarios = $query->with('rol')->paginate(10);
        $roles = Rol::all();

         $roles = Rol::where('nombre', '!=', 'Administrador')->get();

        return view('usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Muestra el formulario para editar un usuario específico.
     * Se usa Route Model Binding con el modelo Usuario.
     */
    public function edit(Usuario $usuario)
    {
        $roles = Rol::where('nombre', '!=', 'Administrador')->get();
        // Se pasa la variable $usuario a la vista
        return view('usuarios.create', compact('usuario', 'roles'));
    }

    /**
     * Actualiza un usuario específico en la base de datos.
     * Se usa Route Model Binding con el modelo Usuario.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'user_nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            // La regla 'unique' apunta a la tabla 'usuarios' e ignora el 'user_id' correcto
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios')->ignore($usuario->user_id, 'user_id')],
            'telefono' => 'nullable|string|max:15',
            'id_rol' => 'required|exists:roles,id_rol',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = $request->password; // El modelo se encargará de cifrarlo
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina (borrado lógico) un usuario específico.
     * Se usa Route Model Binding con el modelo Usuario.
     */
    public function destroy(Usuario $usuario)
    {
        // Gracias al trait SoftDeletes, esto hará un borrado lógico
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
