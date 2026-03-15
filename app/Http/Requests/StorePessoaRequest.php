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
        $aluno = $this->aluno;
        $pedagogico = $this->pedagogico;
        $financeiro = $this->financeiro;

        if (isset($aluno['cpf'])) $aluno['cpf'] = preg_replace('/\D/', '', $aluno['cpf']);
        if (isset($aluno['cep'])) $aluno['cep'] = preg_replace('/\D/', '', $aluno['cep']);
        if (isset($aluno['telefone'])) $aluno['telefone'] = preg_replace('/\D/', '', $aluno['telefone']);
        if (isset($aluno['cep'])) $aluno['cep'] = preg_replace('/\D/', '', $aluno['cep']);

        if (isset($pedagogico['cpf'])) $pedagogico['cpf'] = preg_replace('/\D/', '', $pedagogico['cpf']);
        if (isset($pedagogico['telefone'])) $pedagogico['telefone'] = preg_replace('/\D/', '', $pedagogico['telefone']);
        if (isset($pedagogico['cep'])) $pedagogico['cep'] = preg_replace('/\D/', '', $pedagogico['cep']);
        if (isset($pedagogico['cep'])) $pedagogico['cep'] = preg_replace('/\D/', '', $pedagogico['cep']);

        if (isset($financeiro['cpf'])) $financeiro['cpf'] = preg_replace('/\D/', '', $financeiro['cpf']);
        if (isset($financeiro['telefone'])) $financeiro['telefone'] = preg_replace('/\D/', '', $financeiro['telefone']);
        if (isset($financeiro['cep'])) $financeiro['cep'] = preg_replace('/\D/', '', $financeiro['cep']);
        if (isset($financeiro['cep'])) $financeiro['cep'] = preg_replace('/\D/', '', $financeiro['cep']);


    }
    public function rules(): array
    {
        return [
            'aluno.nome' => 'required|string|max:70',
            'aluno.email' => 'email|max:50',
            'aluno.telefone' => 'string|max:15',
            'aluno.rg' => 'required|string|max:12',
            'aluno.cpf' => 'required|string|max:12',
            'aluno.data_nascimento' => 'required|date',
            'aluno.complemento' => 'required|string|max:70',
            'aluno.cep' => 'required|string|max:10',
            'aluno.bairro' => 'required|string|max:70',
            'aluno.logradouro' => 'required|string|max:100',

            'pedagogico.nome' => 'required|string|max:70',
            'pedagogico.email' => 'required|email|max:50',
            'pedagogico.telefone' => 'required|string|max:15',
            'pedagogico.rg' => 'required|string|max:12',
            'pedagogico.cpf' => 'required|string|max:12',
            'pedagogico.data_nascimento' => 'required|date',
            'pedagogico.complemento' => 'required|string|max:70',
            'pedagogico.cep' => 'required|string|max:10',
            'pedagogico.bairro' => 'required|string|max:70',
            'pedagogico.logradouro' => 'required|string|max:100',

            'financiero.nome' => 'required|string|max:70',
            'financiero.email' => 'required|email|max:50',
            'financiero.telefone' => 'required|string|max:15',
            'financiero.rg' => 'required|string|max:12',
            'financiero.cpf' => 'required|string|max:12',
            'financiero.data_nascimento' => 'required|date',
            'financiero.complemento' => 'required|string|max:70',
            'financiero.cep' => 'required|string|max:10',
            'financiero.bairro' => 'required|string|max:70',
            'financiero.logradouro' => 'required|string|max:100',


        ];
    }

    public function messages(): array
    {
        return [
            'aluno.nome.required' => 'O nome do aluno é obrigatório.',
            'aluno.cpf.size'      => 'O CPF deve conter exatamente 11 números.',
            'aluno.complemento.required' => 'Os dados de endereço do aluno, são obrigatorios.',
            'aluno.cep.required' => 'Informe o cep do aluno!',
            'aluno.bairro.required' => 'Informe o bairro do aluno!',
            'aluno.logradouro.required' => 'Informe o logradouro do aluno!',
            'aluno.complemento.max' => 'O complemento deve conter no máximo 70 caracteres.',
            'aluno.cep.max' => 'O cep deve conter no máximo 9 caracteres.',
            'aluno.bairro.max' => 'O bairro deve conter no máximo 70 caracteres.',
            'aluno.logradouro.max' => 'O nome deve conter no máximo 100 caracteres.',

            'pedagogico.nome.required' => 'O nome do resposável pedagogico é obrigatório',
            'pedagogico.email.required' => 'O email do resposável pedagogico é obrigatório',
            'pedagogico.telefone.required' => 'O telefone do resposável pedagogico é obrigatório',
            'pedagogico.rg,required' => 'O nome do rg pedagogico é obrigatório',
            'pedagogico.cpf.required' => 'O cpf do resposável pedagogico é obrigatório',
            'pedagogico.data_nascimento.required' => 'a data de nascimento do resposável pedagogico é obrigatório',
            'pedagogico.nome.max' => 'O nome deve conter no máximo 70 caracteres.',
            'pedagogico.email.max' => 'O nome deve conter no máximo 50 caracteres.',
            'pedagogico.telefone.max' => 'O nome deve conter no máximo 15 caracteres.',
            'pedagogico.rg,max' => 'O nome deve conter no máximo 12 caracteres.',
            'pedagogico.cpf.max' => 'O cpf deve conter no máximo 11 caracteres',
            'pedagogico.complemento.required' => 'Os dados de endereço do aluno, são obrigatorios.',
            'pedagogico.cep.required' => 'Informe o cep do aluno!',
            'pedagogico.bairro.required' => 'Informe o bairro do aluno!',
            'pedagogico.logradouro.required' => 'Informe o logradouro do aluno!',
            'pedagogico.complemento.max' => 'O complemento deve conter no máximo 70 caracteres.',
            'pedagogico.cep.max' => 'O cep deve conter no máximo 9 caracteres.',
            'pedagogico.bairro.max' => 'O bairro deve conter no máximo 70 caracteres.',
            'pedagogico.logradouro.max' => 'O nome deve conter no máximo 100 caracteres.',

            'financeiro.nome.required' => 'O nome do resposável financeiro é obrigatório',
            'financeiro.email.required' => 'O email do resposável financeiro é obrigatório',
            'financeiro.telefone.required' => 'O telefone do resposável financeiro é obrigatório',
            'financeiro.rg,required' => 'O nome do rg financeiro é obrigatório',
            'financeiro.cpf.required' => 'O cpf do resposável financeiro é obrigatório',
            'financeiro.data_nascimento.required' => 'a data de nascimento do resposável financeiro é obrigatório',
            'financeiro.complemento.required' => 'Os dados de endereço do aluno, são obrigatorios.',
            'financeiro.cep.required' => 'Informe o cep do aluno!',
            'financeiro.bairro.required' => 'Informe o bairro do aluno!',
            'financeiro.logradouro.required' => 'Informe o logradouro do aluno!',
            'financeiro.nome.max' => 'O nome deve conter no máximo 70 caracteres.',
            'financeiro.email.max' => 'O nome deve conter no máximo 50 caracteres.',
            'financeiro.telefone.max' => 'O nome deve conter no máximo 15 caracteres.',
            'financeiro.rg,max' => 'O nome deve conter no máximo 12 caracteres.',
            'financeiro.cpf.max' => 'O cpf deve conter no máximo 11 caracteres',
            'financeiro.complemento.max' => 'O complemento deve conter no máximo 70 caracteres.',
            'financeiro.cep.max' => 'O cep deve conter no máximo 9 caracteres.',
            'financeiro.bairro.max' => 'O bairro deve conter no máximo 70 caracteres.',
            'financeiro.logradouro.max' => 'O nome deve conter no máximo 100 caracteres.',

        ];
    }
}
