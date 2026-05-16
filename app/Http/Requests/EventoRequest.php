<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventoRequest extends FormRequest
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
            'evento' => 'required',
            'preco' => 'required',
            'ativo' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'evento.required' => 'Informe o nome do evento.',
            'preco.required' => 'Informe o valor de evento.',
            'ativo.required' => 'Informe o status do evento.'
        ];
    }
}
