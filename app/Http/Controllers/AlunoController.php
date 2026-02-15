<?php

namespace App\Http\Controllers;

use App\Services\AlunoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlunoController extends Controller
{
    private $alunoService;
    public function __construct(AlunoService $alunoService) {
        $this->alunoService = $alunoService;
    }

    public function index(Request $req){
        $alunos = $this->alunoService->index($req);

        return Inertia::render('Alunos/index',[
            $alunos->paginate(10)->withQueryString(),

            'busca' => $req->only(['busca'])
        ]);
    }


}
