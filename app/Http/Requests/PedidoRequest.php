<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoRequest extends FormRequest
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
            'mesa_id' => 'required_if:tipo_pedido,mesa|nullable|exists:mesas,id',
            'usuario_id'  => 'required|exists:users,id',
            'estado'      => 'required|string|in:pendiente,en_preparacion,listo,entregado,cerrado,cancelado',
            
            'propina'     => 'nullable|numeric|min:0',
            'abierta_en'  => 'nullable|date',
            'cerrada_en'  => 'nullable|date|after_or_equal:abierta_en',
        ];
    }

     public function messages(): array
    {
        return [
            'mesa_id.required'    => 'Debe seleccionar una mesa.',
            'mesa_id.exists'      => 'La mesa seleccionada no existe.',
            'usuario_id.required' => 'Debe indicar el usuario que tomó el pedido.',
            'usuario_id.exists'   => 'El usuario no existe.',
            'estado.in'           => 'El estado no es válido.',
            
            'total.numeric'       => 'El total debe ser un número válido.',
            'propina.numeric'     => 'La propina debe ser un número.',
        ];
    }
}
