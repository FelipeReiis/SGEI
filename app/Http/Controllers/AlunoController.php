<?php

namespace App\Http\Controllers;

use App\Services\AlunoService;
use App\Services\PessoasService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlunoController extends Controller
{
    private $alunoService;
    private $pessoaService;
    public function __construct(AlunoService $alunoService, PessoasService $pessoaService) {
        $this->alunoService = $alunoService;
        $this->pessoaService = $pessoaService;
    }

    public function index(Request $req){
        $alunos = $this->alunoService->index($req);

        return Inertia::render('Alunos/index',[
            $alunos->paginate(10)->withQueryString(),

            'busca' => $req->only(['busca'])
        ]);
    }

    public function create(){
        return Inertia::render('Alunos/create_edit');
    }

    public function store(Request $req){
        $this->pessoaService->store($req);
    }

    public function edit($id){

        $this->alunoService->edit($id);
        return Inertia::render('Alunos/create_edit');

    }




}
