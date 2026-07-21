<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CpfValido;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePessoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation() {}

    private function regrasBase(string $p)
    {
        $bancarios = [
            "$p.banco"=> 'required|max:20',
            "$p.agencia"=> 'required|max:8',
            "$p.conta"=> 'required|max:14',
            "$p.pix"=> 'required|max:50',
        ];
        $pessoa = [
            "$p.cpf" => ['required', 'string', 'max:14', new CpfValido],
            "$p.nome" => 'required|string|max:70',
            "$p.email" => 'nullable|email|max:50',
            "$p.telefone" => 'nullable|string|max:15',
            "$p.rg" => 'required|string|max:13',
            "$p.data_nascimento" => 'required|date',
            "$p.cep" => 'required|string|max:9',
            "$p.logradouro" => 'required|string|max:100',
            "$p.bairro" => 'required|string|max:70',
            "$p.complemento" => 'nullable|string|max:70',
            "$p.documentos" => ['nullable', 'array'],
            "$p.documentos.*" => [
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120'
            ],
        ];
        if($p === 'aluno')
            $pessoa[] =   "$p.escola => 'required|string|max:100'";
        $campos = $p === 'bancario' ? $bancarios : $pessoa;
        return $campos;
    }

    public function rules(): array
    {

        if ($this->has('aluno')) {
            return array_merge(
                $this->regrasBase('aluno'),
                $this->regrasBase('pedagogico'),
                $this->regrasBase('financeiro'),
                $this->regrasBase('bancario')
            );
        }

        if ($this->has('funcionario')) {
            return $this->regrasBase('funcionario');
        }
        return [
            'bancario.banco' => 'required|max:20',
            'bancario.agencia' => 'required|max:8',
            'bancario.conta' => 'required|max:14',
            'bancario.pix' => 'required|max:50',
        ];
    }

    private function mensagensBase(string $p)
    {


        return [
            "$p.cpf.required" => "O CPF do $p é obrigatório.",
            "$p.cpf.string" => "O CPF do $p deve ser um texto válido.",
            "$p.cpf.max" => "O CPF do $p deve ter no máximo 14 caracteres.",

            "$p.nome.required" => "O nome do $p é obrigatório.",
            "$p.nome.string" => "O nome do $p deve ser um texto válido.",
            "$p.nome.max" => "O nome do $p deve ter no máximo 70 caracteres.",

            "$p.email.email" => "O e-mail do $p deve ser um endereço válido.",
            "$p.email.max" => "O e-mail do $p deve ter no máximo 50 caracteres.",

            "$p.telefone.string" => "O telefone do $p deve ser um texto válido.",
            "$p.telefone.max" => "O telefone do $p deve ter no máximo 15 caracteres.",

            "$p.rg.required" => "O RG do $p é obrigatório.",
            "$p.rg.string" => "O RG do $p deve ser um texto válido.",
            "$p.rg.max" => "O RG do $p deve ter no máximo 12 caracteres.",

            "$p.data_nascimento.required" => "A data de nascimento do $p é obrigatória.",
            "$p.data_nascimento.date" => "A data de nascimento do $p deve ser uma data válida.",

            "$p.cep.required" => "O CEP do $p é obrigatório.",
            "$p.cep.string" => "O CEP do $p deve ser um texto válido.",
            "$p.cep.max" => "O CEP do $p deve ter no máximo 9 caracteres.",

            "$p.escola.required" => "O campo escola do $p é obrigatório.",
            "$p.escola.max" => "A escola deve ter no máximo 100 caracteres.",


            "$p.logradouro.required" => "O logradouro do $p é obrigatório.",
            "$p.logradouro.string" => "O logradouro do $p deve ser um texto válido.",
            "$p.logradouro.max" => "O logradouro do $p deve ter no máximo 100 caracteres.",

            "$p.bairro.required" => "O bairro do $p é obrigatório.",
            "$p.bairro.string" => "O bairro do $p deve ser um texto válido.",
            "$p.bairro.max" => "O bairro do $p deve ter no máximo 70 caracteres.",

            "$p.complemento.string" => "O complemento do $p deve ser um texto válido.",
            "$p.complemento.max" => "O complemento do $p deve ter no máximo 70 caracteres.",
            "$p.banco.max" => "O nome do banco deve ter no máximo 20 caracteres",
            "$p.banco.required" => "Preencha o nome do banco, do $p responsável",
            "$p.agencia.required" => "Informe a agência, do $p responsável",
            "$p.agencia.max" => "A agência deve ter no máximo 8 caracteres",
            "$p.conta.required" => "Informe a conta, do $p responsável",
            "$p.conta.max" => "A conta deve ter no máximo 14 caracteres",
            "$p.pix.required" => "Informe a chave pix, do $p responsável",
            "$p.pix.max" => "a chave pix deve conter no máximo, 50 caracters",
        ];
    }

    public function messages(): array
    {
        if ($this->has('aluno')) {
            return array_merge(
                $this->mensagensBase('aluno'),
                $this->mensagensBase('pedagogico'),
                $this->mensagensBase('financeiro'),
                $this->mensagensBase('bancario')
            );
        }

        if ($this->has('funcionario')) {
            return $this->mensagensBase('funcionario');
        }
    }

        protected function failedValidation(Validator $validator)
        {
            // Mostra os erros e interrompe
            // dd($validator->errors()->all());

            // Ou mostre os erros com os dados que causaram a falha
            // dd($validator->errors()->all(), $this->all());
        }
}
