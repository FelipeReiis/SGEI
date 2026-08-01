<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Models\Curso;
use App\Models\pessoa;
use App\Models\profissao;
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
    public function __construct(PessoasService $pessoaService, EnderecoService $enderecoService, DadosBancarios $dadosBancario, FuncionarioService $funcionarioService)
    {
        $this->pessoaService = $pessoaService;
        $this->enderecoService = $enderecoService;
        $this->dadosBancario = $dadosBancario;
        $this->funcionarioService = $funcionarioService;
    }

    public function index(Request $req)
    {
        try{
            $funcionarios = $this->funcionarioService->index($req);
            return Inertia::render('Funcionarios/index', [
                'funcionarios' => $funcionarios->paginate(10)->withQueryString(),
                'busca' => $req->only(['busca'])
            ]);

        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());
        }
    }

    public function create()
    {
        try{
            $profissoes = profissao::select('id', 'descricao')->get();
            $especialidades = Curso::select('id', 'nome')->get();
            return Inertia::render('Funcionarios/create_edit', [
                'profissoes' => $profissoes,
                'especialidades' => $especialidades
            ]);

        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());
        }
    }

    public function store(StorePessoaRequest $req)
    {
        try {

            $req->validated();
            $endId = $this->enderecoService->store($req);
            $pessoaId = $this->pessoaService->store($req, $endId);
            $this->dadosBancario->store($req, $pessoaId);
            if(isset($req->funcionario['especialidades']) && count($req->funcionario['especialidades']) > 0)
                $this->funcionarioService->createProfessor($pessoaId, $req->funcionario['especialidades']);
            $msg = "Funcionário cadastrado com sucesso!!";
            return redirect()->route('funcionarios.index')->with('sucesso', $msg);
        } catch (Exception $e) {
            return redirect()->back()->with('erro', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try{
            $funcionario = $this->funcionarioService->edit($id);
            $profissoes = profissao::all();
            $especialidades = Curso::select('id', 'nome')->get();

            return Inertia::render('Funcionarios/create_edit', [
                'funcionario' => $funcionario[0],
                'especialidades_salvas' => $funcionario[1],
                'especialidades' => $especialidades,
                'profissoes' => $profissoes
            ]);

        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());

        }
    }

    public function update(StorePessoaRequest $req, $id)
    {
        try {

            $req->validated();
            $funEnd = $this->enderecoService->update($req, $id);
            $this->dadosBancario->update($req);
            $msg = $this->pessoaService->update($req, $id);
            if(isset($req->funcionario['especialidades']) && count($req->funcionario['especialidades']) > 0)
                $this->funcionarioService->updateProfessor($id, $req->funcionario['especialidades']);
            return redirect()->route('funcionarios.index')->with('sucesso', $msg);
        } catch (Exception $e) {
            return redirect()->back()->with('erro', $e->getMessage());

        }
    }
}
