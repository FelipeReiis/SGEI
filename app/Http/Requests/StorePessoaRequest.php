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
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => trim($this->name),
            'email' => trim($this->email),
            'telefone' => trim($this->telefone),
            'rg' => trim($this->rg),
            'cpf' =>trim($this->cpf),
            'data_nascimento' =>trim($this->data_nascimento)
        ]);
    }
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:70',
            'email' => 'required|email|max:50',
            'telefone' => 'required|string|max:15',
            'rg' => 'required|string|max:12',
            'cpf' => 'required|string|max:12',
            'data_nascimento' => 'required|date',
        ];
    }
}
