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

    protected function prepareForValidation() {}

    private function regrasBase(string $p)
    {
        return [
            "$p.cpf" => ['required', 'string', 'max:14', new CpfValido],
            "$p.nome" => 'required|string|max:70',
            "$p.email" => 'nullable|email|max:50',
            "$p.telefone" => 'nullable|string|max:15',
            "$p.rg" => 'required|string|max:12',
            "$p.data_nascimento" => 'required|date',
            "$p.cep" => 'required|string|max:9',
            "$p.logradouro" => 'required|string|max:100',
            "$p.bairro" => 'required|string|max:70',
            "$p.complemento" => 'nullable|string|max:70',
            "$p.banco"=> 'required|max:20',
            "$p.agencia"=> 'required|max:8',
            "$p.conta"=> 'required|max:14',
            "$p.pix"=> 'required|max:50',
        ];
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
            // 'aluno.cpf' => ['required', 'string', 'max:14', new CpfValido],
            // 'aluno.nome' => 'required|string|max:70',
            // 'aluno.email' => 'nullable|email|max:50',
            // 'aluno.telefone' => 'nullable|string|max:15',
            // 'aluno.rg' => 'required|string|max:12',
            // 'aluno.data_nascimento' => 'required|date',
            // 'aluno.cep' => 'required|string|max:9',
            // 'aluno.logradouro' => 'required|string|max:100',
            // 'aluno.bairro' => 'required|string|max:70',
            // 'aluno.complemento' => 'nullable|string|max:70',

            // 'pedagogico.cpf' => ['required', 'string','max:14', new CpfValido],
            // 'pedagogico.nome' => 'required|string|max:70',
            // 'pedagogico.email' => 'required|email|max:50',
            // 'pedagogico.telefone' => 'required|string|max:15',
            // 'pedagogico.rg' => 'required|string|max:12',
            // 'pedagogico.data_nascimento' => 'required|date',
            // 'pedagogico.cep' => 'required|string|max:9',
            // 'pedagogico.logradouro' => 'required|string|max:100',
            // 'pedagogico.bairro' => 'required|string|max:70',
            // 'pedagogico.complemento' => 'nullable|string|max:70',

            // 'financeiro.cpf' => ['required', 'string','max:14', new CpfValido],
            // 'financeiro.nome' => 'required|string|max:70',
            // 'financeiro.email' => 'required|email|max:50',
            // 'financeiro.telefone' => 'required|string|max:15',
            // 'financeiro.rg' => 'required|string|max:12',
            // 'financeiro.data_nascimento' => 'required|date',
            // 'financeiro.cep' => 'required|string|max:9',
            // 'financeiro.logradouro' => 'required|string|max:100',
            // 'financeiro.bairro' => 'required|string|max:70',
            // 'financeiro.complemento' => 'nullable|string|max:70',

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
        // return [
        //     /*
        // |--------------------------------------------------------------------------
        // | ALUNO
        // |--------------------------------------------------------------------------
        // */
        //     'aluno.cpf.required' => 'O CPF do aluno é obrigatório.',
        //     'aluno.cpf.string' => 'O CPF do aluno deve ser um texto válido.',
        //     'aluno.cpf.max' => 'O CPF do aluno deve ter no máximo 14 caracteres.',

        //     'aluno.nome.required' => 'O nome do aluno é obrigatório.',
        //     'aluno.nome.string' => 'O nome do aluno deve ser um texto válido.',
        //     'aluno.nome.max' => 'O nome do aluno deve ter no máximo 70 caracteres.',

        //     'aluno.email.email' => 'O e-mail do aluno deve ser um endereço válido.',
        //     'aluno.email.max' => 'O e-mail do aluno deve ter no máximo 50 caracteres.',

        //     'aluno.telefone.string' => 'O telefone do aluno deve ser um texto válido.',
        //     'aluno.telefone.max' => 'O telefone do aluno deve ter no máximo 15 caracteres.',

        //     'aluno.rg.required' => 'O RG do aluno é obrigatório.',
        //     'aluno.rg.string' => 'O RG do aluno deve ser um texto válido.',
        //     'aluno.rg.max' => 'O RG do aluno deve ter no máximo 12 caracteres.',

        //     'aluno.data_nascimento.required' => 'A data de nascimento do aluno é obrigatória.',
        //     'aluno.data_nascimento.date' => 'A data de nascimento do aluno deve ser uma data válida.',

        //     'aluno.cep.required' => 'O CEP do aluno é obrigatório.',
        //     'aluno.cep.string' => 'O CEP do aluno deve ser um texto válido.',
        //     'aluno.cep.max' => 'O CEP do aluno deve ter no máximo 9 caracteres.',

        //     'aluno.logradouro.required' => 'O logradouro do aluno é obrigatório.',
        //     'aluno.logradouro.string' => 'O logradouro do aluno deve ser um texto válido.',
        //     'aluno.logradouro.max' => 'O logradouro do aluno deve ter no máximo 100 caracteres.',

        //     'aluno.bairro.required' => 'O bairro do aluno é obrigatório.',
        //     'aluno.bairro.string' => 'O bairro do aluno deve ser um texto válido.',
        //     'aluno.bairro.max' => 'O bairro do aluno deve ter no máximo 70 caracteres.',

        //     'aluno.complemento.string' => 'O complemento do aluno deve ser um texto válido.',
        //     'aluno.complemento.max' => 'O complemento do aluno deve ter no máximo 70 caracteres.',


        //     /*
        // |--------------------------------------------------------------------------
        // | RESPONSÁVEL PEDAGÓGICO
        // |--------------------------------------------------------------------------
        // */
        //     'pedagogico.cpf.required' => 'O CPF do responsável pedagógico é obrigatório.',
        //     'pedagogico.cpf.string' => 'O CPF do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.cpf.max' => 'O CPF do responsável pedagógico deve ter no máximo 14 caracteres.',

        //     'pedagogico.nome.required' => 'O nome do responsável pedagógico é obrigatório.',
        //     'pedagogico.nome.string' => 'O nome do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.nome.max' => 'O nome do responsável pedagógico deve ter no máximo 70 caracteres.',

        //     'pedagogico.email.required' => 'O e-mail do responsável pedagógico é obrigatório.',
        //     'pedagogico.email.email' => 'O e-mail do responsável pedagógico deve ser válido.',
        //     'pedagogico.email.max' => 'O e-mail do responsável pedagógico deve ter no máximo 50 caracteres.',

        //     'pedagogico.telefone.required' => 'O telefone do responsável pedagógico é obrigatório.',
        //     'pedagogico.telefone.string' => 'O telefone do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.telefone.max' => 'O telefone do responsável pedagógico deve ter no máximo 15 caracteres.',

        //     'pedagogico.rg.required' => 'O RG do responsável pedagógico é obrigatório.',
        //     'pedagogico.rg.string' => 'O RG do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.rg.max' => 'O RG do responsável pedagógico deve ter no máximo 12 caracteres.',

        //     'pedagogico.data_nascimento.required' => 'A data de nascimento do responsável pedagógico é obrigatória.',
        //     'pedagogico.data_nascimento.date' => 'A data de nascimento do responsável pedagógico deve ser válida.',

        //     'pedagogico.cep.required' => 'O CEP do responsável pedagógico é obrigatório.',
        //     'pedagogico.cep.string' => 'O CEP do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.cep.max' => 'O CEP do responsável pedagógico deve ter no máximo 9 caracteres.',

        //     'pedagogico.logradouro.required' => 'O logradouro do responsável pedagógico é obrigatório.',
        //     'pedagogico.logradouro.string' => 'O logradouro do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.logradouro.max' => 'O logradouro do responsável pedagógico deve ter no máximo 100 caracteres.',

        //     'pedagogico.bairro.required' => 'O bairro do responsável pedagógico é obrigatório.',
        //     'pedagogico.bairro.string' => 'O bairro do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.bairro.max' => 'O bairro do responsável pedagógico deve ter no máximo 70 caracteres.',

        //     'pedagogico.complemento.string' => 'O complemento do responsável pedagógico deve ser um texto válido.',
        //     'pedagogico.complemento.max' => 'O complemento do responsável pedagógico deve ter no máximo 70 caracteres.',


        //     /*
        // |--------------------------------------------------------------------------
        // | RESPONSÁVEL FINANCEIRO
        // |--------------------------------------------------------------------------
        // */
        //     'financeiro.cpf.required' => 'O CPF do responsável financeiro é obrigatório.',
        //     'financeiro.cpf.string' => 'O CPF do responsável financeiro deve ser um texto válido.',
        //     'financeiro.cpf.max' => 'O CPF do responsável financeiro deve ter no máximo 14 caracteres.',

        //     'financeiro.nome.required' => 'O nome do responsável financeiro é obrigatório.',
        //     'financeiro.nome.string' => 'O nome do responsável financeiro deve ser um texto válido.',
        //     'financeiro.nome.max' => 'O nome do responsável financeiro deve ter no máximo 70 caracteres.',

        //     'financeiro.email.required' => 'O e-mail do responsável financeiro é obrigatório.',
        //     'financeiro.email.email' => 'O e-mail do responsável financeiro deve ser válido.',
        //     'financeiro.email.max' => 'O e-mail do responsável financeiro deve ter no máximo 50 caracteres.',

        //     'financeiro.telefone.required' => 'O telefone do responsável financeiro é obrigatório.',
        //     'financeiro.telefone.string' => 'O telefone do responsável financeiro deve ser um texto válido.',
        //     'financeiro.telefone.max' => 'O telefone do responsável financeiro deve ter no máximo 15 caracteres.',

        //     'financeiro.rg.required' => 'O RG do responsável financeiro é obrigatório.',
        //     'financeiro.rg.string' => 'O RG do responsável financeiro deve ser um texto válido.',
        //     'financeiro.rg.max' => 'O RG do responsável financeiro deve ter no máximo 12 caracteres.',

        //     'financeiro.data_nascimento.required' => 'A data de nascimento do responsável financeiro é obrigatória.',
        //     'financeiro.data_nascimento.date' => 'A data de nascimento do responsável financeiro deve ser válida.',

        //     'financeiro.cep.required' => 'O CEP do responsável financeiro é obrigatório.',
        //     'financeiro.cep.string' => 'O CEP do responsável financeiro deve ser um texto válido.',
        //     'financeiro.cep.max' => 'O CEP do responsável financeiro deve ter no máximo 9 caracteres.',

        //     'financeiro.logradouro.required' => 'O logradouro do responsável financeiro é obrigatório.',
        //     'financeiro.logradouro.string' => 'O logradouro do responsável financeiro deve ser um texto válido.',
        //     'financeiro.logradouro.max' => 'O logradouro do responsável financeiro deve ter no máximo 100 caracteres.',

        //     'financeiro.bairro.required' => 'O bairro do responsável financeiro é obrigatório.',
        //     'financeiro.bairro.string' => 'O bairro do responsável financeiro deve ser um texto válido.',
        //     'financeiro.bairro.max' => 'O bairro do responsável financeiro deve ter no máximo 70 caracteres.',

        //     'financeiro.complemento.string' => 'O complemento do responsável financeiro deve ser um texto válido.',
        //     'financeiro.complemento.max' => 'O complemento do responsável financeiro deve ter no máximo 70 caracteres.',

        //     'bancario.banco.max' => 'O nome do banco deve ter no máximo 20 caracteres',
        //     'bancario.banco.required' => 'Preencha o nome do banco, do responsável financeiro',
        //     'bancario.agencia.required' => 'Informe a agência, do responsável financeiro',
        //     'bancario.agencia.max' => 'A agência deve ter no máximo 8 caracteres',
        //     'bancario.conta.required' => 'Informe a conta, do responsável financeiro',
        //     'bancario.conta.max' => 'A conta deve ter no máximo 14 caracteres',
        //     'bancario.pix.required' => 'Informe a chave pix, do responsável financeiro',
        //     'bancario.pix.max' => 'a chave pix deve conter no máximo, 50 caracters',
        // ];
    }
}
