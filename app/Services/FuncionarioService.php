<?php

namespace App\Services;

use App\Models\pessoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuncionarioService{

    private $enderecoService;
    private $pessoasService;
    public function __construct(EnderecoService $enderecoService, PessoasService $pessoasService)
    {
        $this->enderecoService = $enderecoService;
        $this->pessoasService = $pessoasService;
    }

    public function index(Request $req){
        $funcionarios = pessoa::select('nome', 'cpf', 'id')->where('funcionario', 1);

        if($req->busca){
            $funcionarios->where('nome', 'like', '%'.$req->busca.'%');
        }

        return $funcionarios;
    }


}
