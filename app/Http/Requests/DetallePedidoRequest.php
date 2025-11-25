<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetallePedidoRequest extends FormRequest
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
        return [
            'pedido_id'     => 'required|exists:pedidos,id',
            'producto_id'   => 'required|exists:productos,id',
            'cantidad'      => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'notas'         => 'nullable|string|max:200',
        ];
    }

     public function messages(): array
    {
        return [
            'pedido_id.required'   => 'Debe asociar el detalle a un pedido.',
            'producto_id.required' => 'Debe seleccionar un producto.',
            'cantidad.required'    => 'Debe indicar la cantidad.',
            'cantidad.min'         => 'Debe ser al menos 1.',
            'precio_unitario.required' => 'Debe indicar el precio unitario.',
        ];
    }
}
