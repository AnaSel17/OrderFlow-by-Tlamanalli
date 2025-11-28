<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CobroCompletoRequest extends FormRequest
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
            'metodo_pago'     => 'required|string',
            'total_cobrado'   => 'required|numeric|min:0',
            'propina'         => 'nullable|numeric|min:0',
            'detalle_ids'     => 'required|array|min:1',
            'detalle_ids.*'   => 'exists:detalle_pedidos,id',
            'pagos_globales'  => 'nullable|string', // vendrá JSON
        ];
    }

    public function messages(): array
    {
        return [
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'total_cobrado.required' => 'El total final es obligatorio.',
            'detalle_ids.required' => 'Debe seleccionar al menos un producto para cobrar.',
        ];
    }
}
