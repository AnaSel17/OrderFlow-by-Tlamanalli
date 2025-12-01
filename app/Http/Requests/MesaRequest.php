<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Detectar si se usa para asignación
        if ($this->routeIs('mesas.asignarMesas')) {
            return [
                'mesas' => 'required|array|min:1',
                'mesas.*' => 'exists:mesas,id',
                'num_comensales' => 'required|integer|min:1|max:20',
            ];
        }

        // ✔ Obtener código actual (si existe)
        // Si NO existe (create), usar la palabra NULL literal
        $codigoActual = $this->mesa->codigo ?? 'NULL';

        return [

            // ✔ Validación correcta para código ÚNICO (create y update)
            'codigo' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Za-z0-9\s\-]+$/',
                "unique:mesas,codigo,{$codigoActual},codigo",
            ],

            'capacidad' => 'required|integer|min:1|max:20',

            // ✔ sillas_extra no se captura al crear
            'sillas_extra' => 'nullable|integer|min:0|max:5',

            'estado' => 'required|string|in:disponible,ocupada,reservada,mantenimiento',

            'tipo' => 'required|string|in:mesa,barra,terraza',

            'zona_id' => 'required|exists:zonas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'Debes ingresar un código para la mesa.',
            'codigo.unique' => 'Este código ya está registrado.',
            'codigo.regex' => 'El código solo puede contener letras, números, espacios y guiones.',
            'codigo.max' => 'El código no debe exceder los 20 caracteres.',
        ];
    }
}
