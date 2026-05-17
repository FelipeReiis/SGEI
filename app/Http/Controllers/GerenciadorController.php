<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Services\EventoService;
use App\Services\GerenciaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GerenciadorController extends Controller
{
    private $eventoService;
    private $gerenciaService;

    public function __construct(EventoService $eventoService, GerenciaService $gerenciaService){
        $this->eventoService = $eventoService;
        $this->gerenciaService = $gerenciaService;
    }

    public function index(){
        $eventos =  Evento::select('id', 'nome', 'data', 'valor', 'status');
        return Inertia::render('Gerencia/index',[
            'eventos' => $eventos->paginate(10)->withQueryString()
        ]);
    }

    public function store(Request $req){
        $msg = $this->gerenciaService->store($req);
        return redirect()->back()->with('sucesso', $msg);
    }

    public function edit($id){
        [$alunos, $evento, $inscritosIds] = $this->gerenciaService->edit($id);
        return Inertia::render('Gerencia/edit',[
            'alunos' => $alunos->paginate(10)->withQueryString(),
            'evento' => $evento,
            'inscritosIds' => $inscritosIds
        ]);
    }
}
