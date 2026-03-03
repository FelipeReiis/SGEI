<?php

namespace App\Http\Controllers;

use App\Services\AlunoService;
use App\Services\EnderecoService;
use App\Services\PessoasService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlunoController extends Controller
{
    private $alunoService;
    private $pessoaService;
    private $enderecoService;
    public function __construct(AlunoService $alunoService, PessoasService $pessoaService, EnderecoService $enderecoService) {
        $this->alunoService = $alunoService;
        $this->pessoaService = $pessoaService;
        $this->enderecoService = $enderecoService;
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
        $enderecoId = $this->enderecoService->store($req);
        $this->pessoaService->store($req, $enderecoId);
    }

    public function edit($id){

        $this->alunoService->edit($id);
        return Inertia::render('Alunos/create_edit');

    }




}
