<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;


use Illuminate\Foundation\Http\FormRequest;

class ZonaRequest extends FormRequest
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
        $zonaId = $this->route('zona')?->id ?? null;

        return [

            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('zonas', 'nombre')->ignore($zonaId),
            ],
            'descripcion' => 'nullable|string|max:150',
            'activa' => 'nullable|boolean',
            'hora_apertura' => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d|(?:1[0-2]):[0-5]\d\s?(?:a\.m\.|p\.m\.)$/i'],
            'hora_cierre'   => ['nullable', 'regex:/^(?:[01]\d|2[0-3]):[0-5]\d|(?:1[0-2]):[0-5]\d\s?(?:a\.m\.|p\.m\.)$/i'],

            'dias_activos' => 'nullable|array',
            'color_hex' => [
                'nullable',
                'regex:/^#([A-Fa-f0-9]{3}){1,2}$/',
                'max:10'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la zona es obligatorio.',
            'nombre.unique' => 'Ya existe una zona con este nombre.',
            'hora_cierre.after' => 'La hora de cierre debe ser posterior a la de apertura.',
            'color_hex.regex' => 'El color debe tener un formato hexadecimal válido (ej. #4CAF50).',
        ];
    }
}
