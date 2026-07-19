<?php

namespace App\Http\Controllers;

use App\Models\Mensalidade;
use App\Services\GerenciaMensalidadeService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GerenciaMensalidadeController extends Controller
{
    private $gerenciaMensalidadeService;
    public function __construct(GerenciaMensalidadeService $gerenciaMensalidadeService){
        $this->gerenciaMensalidadeService = $gerenciaMensalidadeService;
    }
    public function index(Request $req){

        try{
            $mensalidades = Mensalidade::select('id', 'mes', 'ano', 'valor', 'data_vencimento');
             if($req->sort){
                if($req->sort == 'mes')
                    $mensalidades->orderBy('mes', 'asc');
                else if($req->sort == 'ano')
                    $mensalidades->orderBy('ano', 'asc');
                else if ($req->sort == 'valor')
                    $mensalidades->orderBy('valor','asc');
            }
            return Inertia::render('Gerencia/index_mensalidades',[
                'mensalidades' => $mensalidades->paginate(10)->withQueryString()
            ]);
        }catch(Exception $e){
            dd($e);
            return redirect()->back()->with('erro', $e->getMessage());
        }

    }

    public function store(Request $req){
        try{

            $msg = $this->gerenciaMensalidadeService->store($req);
            return redirect()->back()->with('sucesso', $msg);
        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());

        }
    }

     public function edit($id, Request $req){
        try{

            [$alunos, $mensalidade, $inscritosIds] = $this->gerenciaMensalidadeService->edit($id, $req);
            return Inertia::render('Gerencia/edit_mensalidades',[
                'alunos' => $alunos->paginate(10)->withQueryString(),
                'mensalidade' => $mensalidade,
                'inscritosIds' => $inscritosIds
            ]);
        }catch(Exception $e){
            return redirect()->back()->with('erro', $e->getMessage());

        }
    }

    public function mensalidadeAlunoExport($idMensalidade){
        return $this->gerenciaMensalidadeService->relacaoMensalidadeAlunoExport($idMensalidade);
    }
}
