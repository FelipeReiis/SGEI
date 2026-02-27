<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePessoaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:70',
            'email' => 'required|email|max:50',
            'telefone' => 'required|string|max:15',
            'rg' => 'required|string|max:12',
            'cpf' => 'required|string|max:12',
            'dt_nasc' => 'required|date',
        ];
    }
}
