<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventoRequest;
use App\Models\Evento;
use App\Services\EventoService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventoController extends Controller
{
    private $eventoService;

    public function __construct(EventoService $eventoService)
    {
        $this->eventoService = $eventoService;
    }

    public function index(Request $req){
        try{
            $eventos =  Evento::select('id', 'nome', 'data', 'valor', 'status');
            if($req->busca){
                $eventos->where('nome', 'ILIKE', '%'.$req->busca.'%');
            }
            if($req->sort){
                if($req->sort == 'nome')
                    $eventos->orderBy('nome', 'asc');
                else if($req->sort == 'data')
                    $eventos->orderBy('data', 'asc');
                else if ($req->sort == 'status')
                    $eventos->orderBy('status','asc');
            }
            return Inertia::render('Evento/index',[
                'eventos' => $eventos->paginate(10)->withQueryString()
            ]);

        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());
        }
    }

    public function create(){
        return Inertia::render('Evento/create_edit');
    }

    public function store(EventoRequest $req){
        try{
            $msg = $this->eventoService->store($req);
            if($msg === 'Já existe um evento com os mesmos dados.')
                return redirect()->back()->with('erro', $msg);

            return redirect()->route('eventos.index')->with('sucesso', $msg);

        }catch(Exception $e){
            return redirect()->back()()->with('erro', $e->getMessage());

        }
    }

    public function edit($id){
        try{
            $evento = $this->eventoService->edit($id);
            return Inertia::render('Evento/create_edit',[
                'evento' => $evento
            ]);
        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());
        }
    }

    public function update(EventoRequest $req, $id){
        try{
            $msg = $this->eventoService->update($req, $id);

            return redirect()->route('eventos.index')->with('sucesso', $msg);

        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());
        }
    }

    public function destroy($id){
        try{
            $msg = $this->eventoService->delete($id);
            return redirect()->route('eventos.index')->with('sucesso', $msg);

        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());
        }

    }
}
