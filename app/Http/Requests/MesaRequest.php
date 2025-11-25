<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MesaRequest extends FormRequest
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
          // Detectar si se usa para asignación
    if ($this->routeIs('mesas.asignarMesas')) {
        return [
            'mesas' => 'required|array|min:1',
            'mesas.*' => 'exists:mesas,id',
            'num_comensales' => 'required|integer|min:1|max:20',
        ];
    }

    // Reglas normales del CRUD
    $mesaId = $this->route('mesa')?->id;
    return [
        'codigo' => [
            'required',
            'string',
            'max:20',
            'regex:/^[A-Za-z0-9\s\-]+$/',
            'unique:mesas,codigo,' . $mesaId,
        ],
        'capacidad' => 'required|integer|min:1|max:20',
        'sillas_extra' => 'nullable|integer|min:0|max:5',
        'estado' => 'required|string|in:disponible,ocupada,reservada,mantenimiento',
        'tipo' => 'required|string|in:mesa,barra,terraza',
        'zona_id' => 'required|exists:zonas,id',
    ];
        
    }

     public function messages(): array
    {
        return [
             // CÓDIGO
            'codigo.required' => 'Debes ingresar un código para la mesa.',
            'codigo.unique' => 'Este código ya está registrado.',
            'codigo.regex' => 'El código solo puede contener letras, números, espacios y guiones.',
            'codigo.max' => 'El código no debe exceder los 20 caracteres.',

            // CAPACIDAD
            'capacidad.required' => 'Debes indicar la capacidad de la mesa.',
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
            'capacidad.min' => 'Debe haber al menos 1 asiento.',
            'capacidad.max' => 'No se permiten más de 20 personas por mesa.',

            // SILLAS EXTRA
            'sillas_extra.integer' => 'Las sillas adicionales deben ser un número entero.',
            'sillas_extra.min' => 'No puede haber un número negativo de sillas adicionales.',
            'sillas_extra.max' => 'No se permiten más de 5 sillas adicionales por mesa.',

            // ESTADO
            'estado.required' => 'Debes indicar el estado de la mesa.',
            'estado.in' => 'El estado debe ser disponible, ocupada, reservada o mantenimiento.',

            // TIPO
            'tipo.required' => 'Debes indicar el tipo de mesa (mesa, barra o terraza).',
            'tipo.in' => 'El tipo seleccionado no es válido.',

            // ZONA
            'zona_id.required' => 'Debes seleccionar una zona válida.',
            'zona_id.exists' => 'La zona seleccionada no existe o fue eliminada.',

            'mesas.required' => 'Debes seleccionar al menos una mesa.',
            'mesas.*.exists' => 'Alguna de las mesas seleccionadas no existe.',
            'num_comensales.required' => 'Debes indicar el número de comensales.',
        ];

    }
}
