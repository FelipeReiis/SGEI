<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CpfValido;

class StorePessoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {

    }

    public function rules(): array
    {
        return [
            'aluno.cpf' => ['required', 'string', new CpfValido],
            'aluno.nome' => 'required|string|max:70',
            'aluno.email' => 'nullable|email|max:50',
            'aluno.telefone' => 'nullable|string|max:15',
            'aluno.rg' => 'required|string|max:12',
            'aluno.cpf' => 'required|string|max:14',
            'aluno.data_nascimento' => 'required|date',
            'aluno.cep' => 'required|string|max:9',
            'aluno.logradouro' => 'required|string|max:100',
            'aluno.bairro' => 'required|string|max:70',
            'aluno.complemento' => 'nullable|string|max:70',

            'pedagogico.cpf' => ['required', 'string', new CpfValido],
            'pedagogico.nome' => 'required|string|max:70',
            'pedagogico.email' => 'required|email|max:50',
            'pedagogico.telefone' => 'required|string|max:15',
            'pedagogico.rg' => 'required|string|max:12',
            'pedagogico.cpf' => 'required|string|max:14',
            'pedagogico.data_nascimento' => 'required|date',
            'pedagogico.cep' => 'required|string|max:9',
            'pedagogico.logradouro' => 'required|string|max:100',
            'pedagogico.bairro' => 'required|string|max:70',
            'pedagogico.complemento' => 'nullable|string|max:70',

            'financeiro.cpf' => ['required', 'string', new CpfValido],
            'financeiro.nome' => 'required|string|max:70',
            'financeiro.email' => 'required|email|max:50',
            'financeiro.telefone' => 'required|string|max:15',
            'financeiro.rg' => 'required|string|max:12',
            'financeiro.cpf' => 'required|string|max:14',
            'financeiro.data_nascimento' => 'required|date',
            'financeiro.cep' => 'required|string|max:9',
            'financeiro.logradouro' => 'required|string|max:100',
            'financeiro.bairro' => 'required|string|max:70',
            'financeiro.complemento' => 'nullable|string|max:70',
        ];
    }

    public function messages(): array
    {
        return [
            // Usei 'aluno.*' para encurtar, mas você pode manter os seus
            'aluno.cpf.required' => 'O CPF do aluno é obrigatório.',
            'aluno.cpf.max' => 'O CPF deve ter no máximo 11 números.',
            'pedagogico.rg.required' => 'O RG do pedagógico é obrigatório.', // Corrigido de vírgula para ponto
            'financeiro.cpf.required' => 'O CPF do financeiro é obrigatório.',
            // ... suas outras mensagens
        ];
    }
}
