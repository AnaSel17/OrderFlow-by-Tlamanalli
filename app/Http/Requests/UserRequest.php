<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('usuario')?->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:60',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u'
            ],

            'apellido_paterno' => [
                'required',
                'string',
                'min:2',
                'max:60',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u'
            ],

            'apellido_materno' => [
                'nullable',
                'string',
                'min:2',
                'max:60',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u'
            ],

            'email' => [
                'required',
                'email',
                'max:160',
                Rule::unique('users', 'email')->ignore($id),
            ],

            'telefono' => [
                'nullable',
                'string',
                'regex:/^[0-9\s\-]+$/',
                'max:10',
            ],

            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_\-.,])[A-Za-z\d@$!%*?&#_\-.,]{8,}$/',
                'confirmed',
            ],

            'id_rol' => [
                'required',
                'exists:roles,id_rol'
            ],

            'user_estado' => [
                'nullable',
                Rule::in(['activo', 'inactivo', 'bloqueado', 'pendiente']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'email.email' => 'El formato del correo no es válido.',
            'telefono.regex' => 'El teléfono solo puede tener números, espacios o guiones.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.regex' => 'La contraseña debe tener al menos una mayúscula, una minúscula, un número y un carácter especial.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'id_rol.required' => 'Debes asignar un rol al usuario.',
            'id_rol.exists' => 'El rol seleccionado no existe.',
        ];
    }
}
