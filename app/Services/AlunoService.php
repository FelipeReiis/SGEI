<?php

namespace App\Services;

use App\Models\Aluno;
use Illuminate\Http\Request;

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
                        ->join('pessoas as pedag_pessoa', 'alunos.id_resp_pedag', 'pedag_pessoa.id')
                        ->join('turmas', 'alunos.id_turma', 'turmas.id')
                        ->join('professors', 'turmas.id_professor', 'professors.id')
                        ->join('pessoas as prof_pessoa', 'professors.id_pessoa', 'prof_pessoa.id')
                        ->select('aluno_pessoa.nome', 'pedag_pessoa.nome', 'turmas.id', 'prof_pessoa.nome' );

        if($req->busca){
            $alunos->where('aluno_pessoa.nome', 'like', '%'.$req->busca.'%');
        }

        return $alunos;
    }
}
