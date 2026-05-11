<?php

namespace App\Http\Controllers;

use App\Http\Requests\TurmaRequest;
use App\Services\TurmaService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TurmaController extends Controller
{
    private $turmaService;

    public function __construct(TurmaService $turmaService)
    {
        $this->turmaService= $turmaService;
    }
    public function index(Request $req){
        $turmas = $this->turmaService->index($req);

        return Inertia::render('Turmas/index',[
            'turmas' => $turmas->paginate(10)->withQueryString(),
            'busca'=> $req->only(['busca'])
        ]);
    }

    public function create(){
       [ $professores, $cursos, $niveis, $alunos] = $this->turmaService->create();
        return Inertia::render('Turmas/create_edit', [
            'professores' => $professores,
            'cursos' => $cursos,
            'niveis' => $niveis,
            'alunos' =>$alunos
        ]);
    }

    public function store(TurmaRequest $req){
        try{

            $msg = $this->turmaService->store($req);

            return redirect()->route('turmas.index')->with('sucesso', $msg);
        }catch(Exception $e){
            dd($e);

        }
    }

    public function edit($id){
        try{
            [$turma, $professores, $cursos, $niveis, $alunos] = $this->turmaService->edit($id);
            return Inertia::render('Turmas/create_edit', [
                'professores' => $professores,
                'cursos' => $cursos,
                'niveis' => $niveis,
                'turma' => $turma,
                'alunos' => $alunos,
            ]);
        }catch(Exception $e){
            dd($e);
        }
    }

    public function update(TurmaRequest $req){
        try{
            $msg = $this->turmaService->update($req);
            return redirect()->route('turmas.index')->with('sucesso', $msg);
        }catch(Exception $e){
            return redirect()->route('turmas.index')->with('erro', 'Houve um erro ao atualizar a turma');
        }
    }
}
