<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CobroPorComensalRequest extends FormRequest
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
            'cobros_por_comensal' => 'required|string', // vendrá un JSON completo
        ];
    }

        public function messages(): array
    {
        return [
            'cobros_por_comensal.required' => 'No se recibió información del cobro por comensal.',
        ];
    }
}
