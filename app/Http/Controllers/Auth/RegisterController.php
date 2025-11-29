<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // CAMBIADO de 'name' a 'nombre'
            'nombre' => ['required', 'string', 'max:255'], 
            'apellido_paterno' => ['required', 'string', 'max:60'], // Añadido
            // Asumo que tu formulario de registro envía todos los campos
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            // CAMBIADO de 'name' a 'nombre'
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'], // Añadido
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'active', // Añadido un default para satisfacer NOT NULL 
            'rol_id' => 2, // ASUMIDO: Debes asignar un rol por defecto (ej. Mesero)
        ]);
    }
}
