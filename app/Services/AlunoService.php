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
        $alunos = Aluno::join('pessoas as aluno_pessoa', 'alunos.id_pessoa', 'aluno_pessoa.id')
                        ->leftjoin('pessoas as pedag_pessoa', 'alunos.id_resp_pedag', 'pedag_pessoa.id')
                        // ->leftjoin('turmas', 'alunos.id_turma', 'turmas.id')
                        // ->leftjoin('professors', 'turmas.id_professor', 'professors.id')
                        // ->leftjoin('pessoas as prof_pessoa', 'professors.id_pessoa', 'prof_pessoa.id')
                        ->select('aluno_pessoa.nome as aluno_nome', 'pedag_pessoa.nome', 'aluno_pessoa.id as aluno_id' );


        if($req->busca){
            $alunos->where('aluno_pessoa.nome', 'like', '%'.$req->busca.'%');
        }

        return $alunos;
    }

    public function store($idPessoaAluno, $idResp, $idTurma){
        DB::beginTransaction();

        try{
            Aluno::create([
                'id_resp_fin' => isset($idPessoaAluno['financeiro']) ? $idPessoaAluno['financeiro'] : $idPessoaAluno['pedagogico'],
                'id_resp_pedag' => $idPessoaAluno['pedagogico'],
                'id_pessoa' => $idPessoaAluno['aluno'],
                'id_turma' => null
            ]);
            DB::commit();
            $msg = 'Aluno registrado com sucesso!';
            return $msg;
        }catch(Exception $e){
            dd($e);
            DB::rollback();
            $msg = "Erro um tentar registrar: $e";
            return $msg;
        }
    }

    public function edit($id){
        try{
            $aluno = Aluno::join('pessoas as aluno_pessoa', 'alunos.id_pessoa', 'aluno_pessoa.id')
                    // ->join('pessoas as pedag_pessoa', 'alunos.id_resp_pedag', 'pedag_pessoa.id')
                    // ->join('pessoas as fin_pessoa', 'alunos.id_resp_fin', '=', 'fin_pessoa.id')
                    // ->join('turmas', 'alunos.id_turma', 'turmas.id')
                    ->join('enderecos as aluno_end', 'aluno_pessoa.id_end', '=', 'aluno_end.id')
                    // ->leftJoin('enderecos as pedag_end', 'pedag_pessoa.id_end', '=', 'pedag_end.id')
                    // ->leftJoin('enderecos as fin_end', 'fin_pessoa.id_end', '=', 'fin_end.id')
                    ->where('aluno_pessoa.id', $id)
                    ->select(
                                'aluno_pessoa.*',
                                'aluno_end.*',
                                'aluno_end.id as id_aluno_end',
                                // 'pedag_pessoa.nome as responsavel_pedagogico',

                                // DB::raw("
                                //     CASE
                                //         WHEN alunos.id_resp_pedag != alunos.id_resp_fin
                                //         THEN fin_pessoa.nome
                                //         ELSE NULL
                                //     END as responsavel_financeiro
                                // "),

                                // 'turmas.id'
                    )->first();
            return $aluno;

        }catch(Exception $e){
            $msg = "Erro ao consultado os dados: $e";
            return $msg;
        }
    }

    public function update(Request $req, $id){

      DB::beginTransaction();

        try{
            $aluno = Aluno::find($id);
            $aluno->update([
                'id_resp_fin' => $req->resp_fin,
                'id_resp_pedag' => $req->resp_pedag,
                'id_turma' => $req->turma
            ]);
            DB::commit();
            $msg = 'Aluno atualizado com sucesso!';
            return $msg;
        }catch(Exception $e){

            DB::rollback();
            $msg = "Erro um tentar atualizar: $e";

            return $msg;
        }

    }
}
