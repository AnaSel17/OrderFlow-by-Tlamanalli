<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriaRequest extends FormRequest
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
        
        // Detectar si es actualización (por si ya existe el nombre)
        $id = $this->route('categoria')?->id ?? null;

        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-.,()]+$/u',
                Rule::unique('categorias', 'nombre')->ignore($id),
            ],

            'descripcion' => [
                'nullable',
                'string',
                'min:5',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Ya existe una categoría con este nombre.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, números, espacios y signos básicos como guiones, comas o paréntesis.',
            'descripcion.min' => 'La descripción debe tener al menos 5 caracteres.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',
        ];
    
    }
}
