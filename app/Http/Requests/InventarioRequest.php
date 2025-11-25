<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventarioRequest extends FormRequest
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

        $rules = [
        'stock_actual'  => 'required|integer|min:0|max:1000',
        'punto_reorden' => 'required|integer|min:0|max:1000',
    ];

    if ($this->isMethod('POST')) {
        // Al crear un nuevo inventario
        $rules['producto_id'] = 'required|exists:productos,id|unique:inventarios,producto_id';
    } else {
        // Al actualizar uno existente
        $rules['producto_id'] = 'nullable|exists:productos,id';
    }

    return $rules;
    }

    public function messages(): array
    {
        return [
            'producto_id.required'   => 'Debes seleccionar un producto.',
            'producto_id.exists'     => 'El producto seleccionado no existe.',
            'producto_id.unique'     => 'Este producto ya tiene un inventario registrado.',
            'stock_actual.required'  => 'El campo stock actual es obligatorio.',
            'stock_actual.integer'   => 'El stock debe ser un número entero.',
            'stock_actual.min'       => 'El stock no puede ser negativo.',
            'punto_reorden.required' => 'El campo punto de reorden es obligatorio.',
            'punto_reorden.integer'  => 'El punto de reorden debe ser un número entero.',
        ];
    }
}
