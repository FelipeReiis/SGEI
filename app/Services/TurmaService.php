<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Professor;
use App\Models\Turma;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurmaService{

    public function __construct(){

    }

    public function index(Request $req){
        try{
            $turmas = Turma::join('professors', 'turmas.id_professor', 'professors.id')
                            ->join('pessoas', 'professors.id_pessoa', 'pessoas.id')
                            ->join('cursos', 'turmas.id_curso', 'cursos.id')
                            ->select('turmas.id','turmas.horario', 'turmas.grau', 'pessoas.nome', 'cursos.nome as curso_nome');
            return $turmas;
        }catch(Exception $e){
            return "erro ao consultar turmas: $e";
        }
    }

    public function create(){
        $professores = Professor::join('pessoas', 'professors.id_pessoa', '=', 'pessoas.id')
                                ->select(
                                    'pessoas.id',
                                    'pessoas.nome',
                                    DB::raw('ARRAY_AGG(professors.id) as professor_ids'),
                                    DB::raw('ARRAY_AGG(professors.id_especialidade) as especialidades')
                                )
                                ->groupBy('professors.id_pessoa', 'pessoas.nome', 'pessoas.id')
                                ->get();
        foreach($professores as $professor){
            $professor['especialidades'] =  explode(',',str_replace(['{','}'], '', $professor['especialidades']));
        }
        $cursos = Curso::all();

        $niveis = Nivel::all();

        $alunos = Aluno::join('pessoas as aluno_pessoa', 'alunos.id_pessoa', 'aluno_pessoa.id')
                        ->leftjoin('pessoas as pedag_pessoa', 'alunos.id_resp_pedag', 'pedag_pessoa.id')
                        ->leftjoin('pessoas as fin_pessoa', 'alunos.id_resp_fin', 'fin_pessoa.id')
                        ->select('aluno_pessoa.nome as aluno_nome', 'pedag_pessoa.nome as pedag_nome', 'aluno_pessoa.id as aluno_id', 'fin_pessoa.nome as fin_nome', 'aluno_pessoa.cpf as aluno_cpf')->get();

        return [$professores, $cursos, $niveis, $alunos];
    }

    public function store(Request $req){
        try{
            $professor = Professor::select('id')->where('id_pessoa', $req->professor_id)
                                    ->where('id_especialidade', $req->curso_id)->first();
           $turma =  Turma::create([
                'id_professor' => $professor->id,
                'horario' => $req->horario,
                'grau' => $req->grau,
                'id_curso' =>$req->curso_id,
                'id_nivel' => $req->nivel_id
            ]);
            foreach($req->alunos_ids as $aluno){
                Aluno::where('id', $aluno)->update(['id_turma' => $turma->id]);
            }

            return 'Turma cadastrada com sucesso!!';
        }catch(Exception $e){
            dd($e);
            return 'Houve um problema ao cadastrar a turma.';
        }
    }

    public function edit($id){
        $turma = Turma::select('turmas.id','professors.id_pessoa', 'horario', 'grau', 'id_curso','turmas.id_nivel')
                        ->join('professors', 'turmas.id_professor', 'professors.id')
                        ->where('turmas.id', $id)->first();

        $professores = Professor::join('pessoas', 'professors.id_pessoa', '=', 'pessoas.id')
                                ->select(
                                    'pessoas.id',
                                    'pessoas.nome',
                                    DB::raw('ARRAY_AGG(professors.id) as professor_ids'),
                                    DB::raw('ARRAY_AGG(professors.id_especialidade) as especialidades')
                                )
                                ->groupBy('professors.id_pessoa', 'pessoas.nome', 'pessoas.id')
                                ->get();
        foreach($professores as $professor){
            $professor['especialidades'] =  explode(',',str_replace(['{','}'], '', $professor['especialidades']));
        }
        $cursos = Curso::all();

        $niveis = Nivel::all();

        $alunos = Aluno::join('pessoas as aluno_pessoa', 'alunos.id_pessoa', 'aluno_pessoa.id')
                        ->leftjoin('pessoas as pedag_pessoa', 'alunos.id_resp_pedag', 'pedag_pessoa.id')
                        ->leftjoin('pessoas as fin_pessoa', 'alunos.id_resp_fin', 'fin_pessoa.id')
                        ->select('aluno_pessoa.nome as aluno_nome', 'pedag_pessoa.nome as pedag_nome', 'aluno_pessoa.id as aluno_id', 'fin_pessoa.nome as fin_nome', 'aluno_pessoa.cpf as aluno_cpf', 'alunos.id_turma')->get();
        return [$turma,$professores, $cursos, $niveis, $alunos];
    }

    public function update(Request $req){
        try{
            $professor = Professor::select('id')->where('id_pessoa', $req->professor_id)
                                    ->where('id_especialidade', $req->curso_id)->first();

            Turma::where('id', $req->id)->update([
                'id_professor' => $professor->id,
                'horario' => $req->horario,
                'grau' => $req->grau,
                'id_curso' =>$req->curso_id,
                'id_nivel' => $req->nivel_id
            ]);

             foreach($req->alunos_ids as $aluno){
                Aluno::where('id', $aluno)->update(['id_turma' => $req->id]);
            }

            return 'Turma atualizada com sucesso!';
        }catch(Exception $e){
            return "Houve um problema ao atualizar a turma: $e";
        }
    }
}
