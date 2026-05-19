<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\pessoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlunoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index(Request $req){

    try{
          $alunos = Aluno::join('pessoas as aluno_pessoa', 'alunos.id_pessoa', 'aluno_pessoa.id')
                        ->leftjoin('pessoas as pedag_pessoa', 'alunos.id_resp_pedag', 'pedag_pessoa.id')
                        ->leftjoin('pessoas as fin_pessoa', 'alunos.id_resp_fin', 'fin_pessoa.id')
                        ->select('aluno_pessoa.nome as aluno_nome', 'pedag_pessoa.nome as pedag_nome', 'aluno_pessoa.id as aluno_id', 'fin_pessoa.nome as fin_nome');

        if($req->busca){
            $alunos->where('aluno_pessoa.nome', 'ILIKE', '%'.$req->busca.'%');
        }

        if($req->sort){
            if($req->sort == 'aluno_nome')
                $alunos->orderBy('aluno_pessoa.nome');
            else if($req->sort == 'fin_nome')
                $alunos->orderBy('fin_pessoa.nome');
            else if($req->sort == 'pedag_nome')
                $alunos->orderBy('pedag_pessoa.nome');
        }

        return $alunos;
    }catch(Exception $e){
        throw new Exception ("Erro ao carregar os alunos: " . $e->getMessage());
    }

    }

    public function store($idPessoaAluno){
        DB::beginTransaction();
        try{
            Aluno::create([
                'id_resp_fin' => isset($idPessoaAluno[2]['financeiro']) ? $idPessoaAluno[2]['financeiro'] : $idPessoaAluno[1]['pedagogico'],
                'id_resp_pedag' => isset($idPessoaAluno[1]['pedagogico']) ? $idPessoaAluno[1]['pedagogico'] : $idPessoaAluno[1]['financeiro'],
                'id_pessoa' => $idPessoaAluno[0]['aluno'],
            ]);
            DB::commit();
            $msg = 'Aluno registrado com sucesso!';
            return $msg;
        }catch(Exception $e){
            DB::rollback();
            $msg = "Erro um tentar registrar: $e";
            throw new Exception ("Erro um tentar registrar o aluno: " . $e->getMessage());
        }
    }

    public function edit($id){
        try{
            $aluno = Aluno::join('pessoas as aluno_pessoa', 'alunos.id_pessoa', 'aluno_pessoa.id')
                        ->join('pessoas as pedag_pessoa', 'alunos.id_resp_pedag', 'pedag_pessoa.id')
                        ->join('pessoas as fin_pessoa', 'alunos.id_resp_fin', '=', 'fin_pessoa.id')
                        ->join('enderecos as aluno_end', 'aluno_pessoa.id_end', '=', 'aluno_end.id')
                        ->join('dado_bancarios as banco', 'fin_pessoa.id', '=', 'banco.id_pessoa')
                        ->leftJoin('enderecos as pedag_end', 'pedag_pessoa.id_end', '=', 'pedag_end.id')
                        ->leftJoin('enderecos as fin_end', 'fin_pessoa.id_end', '=', 'fin_end.id')
                        ->where('aluno_pessoa.id', $id)
                    ->select(
                                'aluno_pessoa.id as aluno_id',
                                'aluno_end.id as id_aluno_end',
                                'aluno_pessoa.nome as aluno_nome',
                                'aluno_pessoa.email as aluno_email',
                                'aluno_pessoa.telefone as aluno_telefone',
                                'aluno_pessoa.rg as aluno_rg',
                                'aluno_pessoa.cpf as aluno_cpf',
                                'aluno_pessoa.data_nascimento as aluno_data_nascimento',
                                'aluno_end.cep as aluno_cep',
                                'aluno_end.logradouro as aluno_logradouro',
                                'aluno_end.complemento as aluno_complemento',
                                'aluno_end.bairro as aluno_bairro',
                                'aluno_end.numero as aluno_numero',
                                'alunos.escola as aluno_escola',
                                'pedag_pessoa.id as pedag_id',
                                'pedag_pessoa.nome as pedag_nome',
                                'pedag_pessoa.email as pedag_email',
                                'pedag_pessoa.telefone as pedag_telefone',
                                'pedag_pessoa.rg as pedag_rg',
                                'pedag_pessoa.cpf as pedag_cpf',
                                'pedag_pessoa.data_nascimento as pedag_data_nascimento',
                                'pedag_end.cep as pedag_cep',
                                'pedag_end.logradouro as pedag_logradouro',
                                'pedag_end.complemento as pedag_complemento',
                                'pedag_end.bairro as pedag_bairro',
                                'pedag_end.numero as pedag_numero',
                                'fin_pessoa.id as fin_id',
                                'fin_pessoa.nome as fin_nome',
                                'fin_pessoa.email as fin_email',
                                'fin_pessoa.telefone as fin_telefone',
                                'fin_pessoa.rg as fin_rg',
                                'fin_pessoa.cpf as fin_cpf',
                                'fin_pessoa.data_nascimento as fin_data_nascimento',
                                'fin_end.cep as fin_cep',
                                'fin_end.logradouro as fin_logradouro',
                                'fin_end.complemento as fin_complemento',
                                'fin_end.bairro as fin_bairro',
                                'fin_end.numero as fin_numero',
                                'banco.id as banco_id',
                                'banco.agencia',
                                'banco.conta',
                                'banco.pix',
                                'banco.banco',
                    )->first();
            return $aluno;

        }catch(Exception $e){
            $msg = "Erro ao consultar os dados: $e";
            throw new Exception ("$msg: " . $e->getMessage());
        }
    }

    public function update(Request $req, $id){
      DB::beginTransaction();

        try{
            $aluno = Aluno::find($id);
            $aluno->update([
                'id_resp_fin' => $req->resp_fin,
                'id_resp_pedag' => $req->resp_pedag,
            ]);
            DB::commit();
            $msg = 'Aluno atualizado com sucesso!';
            return $msg;
        }catch(Exception $e){

            DB::rollback();
            $msg = "Erro um tentar atualizar";
            throw new Exception ("$msg: " . $e->getMessage());
        }

    }
}
