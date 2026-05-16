<?php

namespace App\Http\Controllers;

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

    public function index(){
        $eventos =  Evento::select('id', 'nome', 'data', 'valor', 'status');
        return Inertia::render('Evento/index',[
            'eventos' => $eventos->paginate(10)->withQueryString()
        ]);
    }

    public function create(){
        return Inertia::render('Evento/create_edit');
    }

    public function store(Request $req){
        try{
            $msg = $this->eventoService->store($req);

            return redirect()->route('eventos.index')->with('sucesso', $msg);

        }catch(Exception $e){
            return redirect()->route('eventos.index')->with('erro', 'Houve um problema ao cadastrar o evento:'.$e);
        }
    }

    public function edit($id){
        try{
            $evento = $this->eventoService->edit($id);
            return Inertia::render('Evento/create_edit',[
                'evento' => $evento
            ]);
        }catch(Exception $e){
            return 'Houve um problema ao tentar resgatar informaçoes do evento '.$e;
        }
    }

    public function update(Request $req, $id){
        try{
            $msg = $this->eventoService->update($req, $id);

            return redirect()->route('eventos.index')->with('sucesso', $msg);

        }catch(Exception $e){
            return redirect()->route('eventos.index')->with('erro', 'Houve um problema ao cadastrar o evento:'. $e);
        }
    }

    public function delete($id){
        $msg = $this->eventoService->delete($id);
        return redirect()->route('eventos.index')->with('sucesso', $msg);

    }
}
