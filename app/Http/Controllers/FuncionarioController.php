<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Services\DadosBancarios;
use App\Services\EnderecoService;
use App\Services\FuncionarioService;
use App\Services\PessoasService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FuncionarioController extends Controller
{
    private $pessoaService;
    private $enderecoService;
    private $dadosBancario;
    private $funcionarioService;
    public function __construct(PessoasService $pessoaService, EnderecoService $enderecoService, DadosBancarios $dadosBancario, FuncionarioService $funcionarioService){
        $this->pessoaService = $pessoaService;
        $this->enderecoService = $enderecoService;
        $this->dadosBancario= $dadosBancario;
        $this->funcionarioService = $funcionarioService;
    }

    public function index(Request $req){
        $funcionarios = $this->funcionarioService->index($req);
        return Inertia::render('Funcionarios/index',[
            'funcionarios' => $funcionarios->paginate(10)->withQueryString(),
            'busca' => $req->only(['busca'])
        ]);
    }

    public function create(){
        return Inertia::render('Funcionarios/create_edit');
    }

     public function store(StorePessoaRequest $req){
        try{

            $req->validated();
            $endId = $this->enderecoService->store($req);
            $pessoaId = $this->pessoaService->store($req, $endId);
            $this->dadosBancario->store($req, $pessoaId);
            $msg = "Funcionário cadastrado com sucesso!!";
            return redirect()->route('funcionarios.index')->with('sucesso', $msg);
        }catch(Exception $e){
            dd('erro: ', $e);
            $msg = 'Houve um erro ao tentar cadastrar um funcionário '. $e;
            return $msg;
        }
    }

    public function edit($id){
        $pessoa = $this->pessoaService->edit($id);
        return $pessoa;
    }

    public function update(StorePessoaRequest $req, $id){
        try{
            $req->validated();
            $funEnd = $this->enderecoService->update($req,$id);
            $this->dadosBancario->update($req);
            $msg = $this->pessoaService->update($req, $id);
            dd('aoba');
        }catch(Exception $e){
            dd('erro: '. $e);
        }
    }

}
