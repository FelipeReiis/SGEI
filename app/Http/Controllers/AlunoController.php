<?php

namespace App\Http\Controllers;

use App\Services\AlunoService;
use App\Services\EnderecoService;
use App\Services\PessoasService;
use Exception;
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
            'alunos' => $alunos->paginate(10)->withQueryString(),
            'busca' => $req->only(['busca'])
        ]);
    }

    public function create(){
        return Inertia::render('Alunos/create_edit');
    }

    public function store(Request $req){
        try{
            $dados = $req->validated();
            $enderecoId = $this->enderecoService->store($dados);
            $pessoaAlunoId = $this->pessoaService->store($dados, $enderecoId);
            $this->alunoService->store($pessoaAlunoId);
            return redirect()->route('alunos.index')->with('sucesso', 'Aluno cadastrado com sucesso!');

        }catch(Exception $e){
            return redirect()->route('alunos.index')->with('erro', 'houve um erro ao tentar cadastrar: '.$e );
        }

    }

    public function edit($id){
       $aluno = $this->alunoService->edit($id);
        return Inertia::render('Alunos/create_edit', [
            'aluno' => $aluno,
        ]);

    }

    public function update(Request $req, $id){
        try{
            $dados = $req->validated();
            $alunoEnd = $this->enderecoService->update($dados,$id);
            $alunoMsg = $this->pessoaService->update($dados, $id);
            return redirect()->route('alunos.index')->with('sucesso', $alunoMsg);
        }catch(Exception $e){
            return redirect()->route('alunos.index')->with('erro', 'houve um erro ao tentar atualizar os dados: '.$e);
        }

    }




}
