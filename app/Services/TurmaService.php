<?php

namespace App\Services;

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
        return [$professores, $cursos, $niveis];
    }

    public function store(Request $req){
        try{
            $professor = Professor::select('id')->where('id_pessoa', $req->professor_id)
                                    ->where('id_especialidade', $req->curso_id)->first();
            Turma::create([
                'id_professor' => $professor->id,
                'horario' => $req->horario,
                'grau' => $req->grau,
                'id_curso' =>$req->curso_id,
                'id_nivel' => $req->nivel_id
            ]);

            return 'Turma cadastrada com sucesso!!';
        }catch(Exception $e){
            dd($e);
            return 'Houve um problema ao cadastrar a turma.';
        }
    }

    public function edit($id){
        $turma = Turma::select('turmas.id','professors.id_pessoa', 'horario', 'grau', 'id_curso')
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
        return [$turma,$professores, $cursos, $niveis];
    }
}
