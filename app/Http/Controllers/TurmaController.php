<?php

namespace App\Http\Controllers;

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
       [ $professores, $cursos, $niveis] = $this->turmaService->create();
        return Inertia::render('Turmas/create_edit', [
            'professores' => $professores,
            'cursos' => $cursos,
            'niveis' => $niveis
        ]);
    }

    public function store(Request $req){
        try{
            $msg = $this->turmaService->store($req);

            return redirect()->route('turmas.index')->with('sucesso', $msg);
        }catch(Exception $e){
            dd($e);

        }
    }

    public function edit($id){
        try{
            [$turma, $professores, $cursos, $niveis] = $this->turmaService->edit($id);

            return Inertia::render('Turmas/create_edit', [
                'professores' => $professores,
                'cursos' => $cursos,
                'niveis' => $niveis,
                'turma' => $turma
            ]);
        }catch(Exception $e){
            dd($e);
        }
    }
}
