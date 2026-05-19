<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AlunoTurma;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Professor;
use App\Models\ProfessorDias;
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
            if($req->busca){
                $turmas->where('pessoas.nome', 'ILIKE', '%'.$req->busca.'%');
            }

            if($req->sort){
                if($req->sort == 'nome'){
                    $turmas->orderBy('pessoas.nome');
                }else if($req->sort == 'curso_nome')
                    $turmas->orderBy('curso_nome');
                else if($req->sort == 'grau')
                    $turmas->orderBy('grau');
                else{
                    $turmas->orderBy('horario');
                }
            }
            return $turmas;
        }catch(Exception $e){
            throw new Exception ("erro ao carregar as turmas: " . $e->getMessage());
        }
    }

    public function create(){
        try{
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
                            ->select('aluno_pessoa.nome as aluno_nome', 'pedag_pessoa.nome as pedag_nome', 'alunos.id as aluno_id', 'fin_pessoa.nome as fin_nome', 'aluno_pessoa.cpf as aluno_cpf')->get();

            return [$professores, $cursos, $niveis, $alunos];

        }catch(Exception $e){
            throw new Exception ("erro ao carregas os dados para cadastrar a turma: " . $e->getMessage());
        }
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
                AlunoTurma::create(
                    [
                        'id_turma' => $turma->id,
                        'id_aluno' => $aluno
                    ]
                );
            }
            foreach($req->dias_aulas as $dia){
                ProfessorDias::create([
                    'id_professor' => $professor->id,
                    'dia' => $dia,
                    'id_turma' => $turma->id
                ]);
            }
            $msg = 'Turma cadastrada com sucesso!!';
            return $msg;
        }catch(Exception $e){
            throw new Exception ("Houve um problema ao cadastrar a turma: " . $e->getMessage());
        }
    }

    public function edit($id){
        try{
            $turma = Turma::select(
            'turmas.id',
            'professors.id_pessoa',
            'horario',
            'grau',
            'id_curso',
            'turmas.id_nivel',
            DB::raw('array_agg(professor_dias.dia) as dias')
                )
                ->join('professors', 'turmas.id_professor', 'professors.id')
                ->join('professor_dias', function ($join) {
                    $join->on('turmas.id', '=', 'professor_dias.id_turma')
                        ->on('turmas.id_professor', '=', 'professor_dias.id_professor');
                })
                ->where('turmas.id', $id)
                ->groupBy(
                    'turmas.id',
                    'professors.id_pessoa',
                    'horario',
                    'grau',
                    'id_curso',
                    'turmas.id_nivel'
                )
                ->first();
            $turma->dias = explode(',', str_replace(['{', '}'],'', $turma->dias));
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
                            ->leftJoin('aluno_turmas', 'alunos.id', 'aluno_turmas.id_aluno')

                            ->selectRaw("
                                DISTINCT ON (alunos.id)
                                aluno_pessoa.nome as aluno_nome,
                                alunos.id as aluno_id,
                                aluno_pessoa.cpf as aluno_cpf,
                                aluno_turmas.id_turma
                            ")

                            ->orderBy('alunos.id')

                            // prioriza a turma desejada
                            ->orderByRaw("
                                CASE
                                    WHEN aluno_turmas.id_turma = ? THEN 0
                                    ELSE 1
                                END
                            ", [$turma->id])->get();
            return [$turma, $professores, $cursos, $niveis, $alunos];
        }catch(Exception $e){
            throw new Exception ("Houve um problema ao carregar os dados  da turma: " . $e->getMessage());
        }
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
            AlunoTurma::where('id_turma', $req->id)->delete();
            if(count($req->alunos_ids) >= 1){
                foreach($req->alunos_ids as $aluno){
                    AlunoTurma::create(
                        [
                            'id_turma' => $req->id,
                            'id_aluno' => $aluno
                        ]
                    );
                }
            }

            ProfessorDias::where('id_professor', $professor->id)->where('id_turma', $req->id)->delete();
            foreach($req->dias_aulas as $dia){
                ProfessorDias::create([
                    'id_professor' => $professor->id,
                    'dia' => $dia,
                    'id_turma' => $req->id
                ]);
            }
            $msg = 'Turma atualizada com sucesso!';
            return $msg;
        }catch(Exception $e){
            throw new Exception ("Houve um problema ao atualizar a turma: " . $e->getMessage());

        }
    }
}
