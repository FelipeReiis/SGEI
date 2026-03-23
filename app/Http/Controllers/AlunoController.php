<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Services\AlunoService;
use App\Services\EnderecoService;
use App\Services\PessoasService;
use App\Services\DadosBancarios;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlunoController extends Controller
{
    private $alunoService;
    private $pessoaService;
    private $enderecoService;
    private $dadosBancario;
    public function __construct(AlunoService $alunoService, PessoasService $pessoaService, EnderecoService $enderecoService,  DadosBancarios $dadosBancario) {
        $this->alunoService = $alunoService;
        $this->pessoaService = $pessoaService;
        $this->enderecoService = $enderecoService;
        $this->dadosBancario = $dadosBancario;
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

    public function store(StorePessoaRequest $req){
        try{
            $req->validated();
            $enderecoId = $this->enderecoService->store($req);
            $pessoasId = $this->pessoaService->store($req, $enderecoId);
            $this->dadosBancario->store($req, $pessoasId);
            $this->alunoService->store($pessoasId);
            return redirect()->route('alunos.index')->with('sucesso', 'Aluno cadastrado com sucesso!');

        }catch(Exception $e){
            dd($e);
            return redirect()->route('alunos.index')->with('erro', 'houve um erro ao tentar cadastrar: '.$e );
        }

    }

    public function edit($id){
       $aluno = $this->alunoService->edit($id);
        return Inertia::render('Alunos/create_edit', [
            'aluno' => $aluno,
        ]);

    }

    public function update(StorePessoaRequest $req, $id){
        try{

            $req->validated();
            $alunoEnd = $this->enderecoService->update($req,$id);
            $alunoMsg = $this->pessoaService->update($req, $id);
            $this->dadosBancario->update($req);
            return redirect()->route('alunos.index')->with('sucesso', $alunoMsg);
        }catch(Exception $e){
            return redirect()->route('alunos.index')->with('erro', 'houve um erro ao tentar atualizar os dados: '.$e);
        }

    }




}
