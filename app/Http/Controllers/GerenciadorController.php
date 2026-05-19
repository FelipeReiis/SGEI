<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Services\EventoService;
use App\Services\GerenciaService;
use Exception;
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

    public function index(Request $req){
        try{

            $eventos =  Evento::select('id', 'nome', 'data', 'valor', 'status');
            if($req->busca){
                $eventos->where('nome', 'ILIKE', '%'.$req->busca.'%');
            }
            if($req->sort){
            dd($req->sort);
                if($req->sort == 'nome')
                    $eventos->orderBy('nome', 'asc');
                else if($req->sort == 'data')
                    $eventos->orderBy('data', 'asc');
                else if ($req->sort == 'status')
                    $eventos->orderBy('status','asc');
                else{
                    $eventos->orderBy('valor','asc');
                }
            }
            return Inertia::render('Gerencia/index',[
                'eventos' => $eventos->paginate(10)->withQueryString()
            ]);
        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());

        }
    }

    public function store(Request $req){
        try{

            $msg = $this->gerenciaService->store($req);
            return redirect()->back()->with('sucesso', $msg);
        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());

        }
    }

    public function edit($id, Request $req){
        try{

            [$alunos, $evento, $inscritosIds] = $this->gerenciaService->edit($id, $req);
            return Inertia::render('Gerencia/edit',[
                'alunos' => $alunos->paginate(10)->withQueryString(),
                'evento' => $evento,
                'inscritosIds' => $inscritosIds
            ]);
        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());

        }
    }
}
