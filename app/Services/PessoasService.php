<?php

namespace App\Services;

use App\Models\pessoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PessoasService
{

    public function __construct()
    {

    }

    public function store(Request $req, $enderecoId){
        DB::beginTransaction();
        try{
        $pessoa = pessoa::create([
            'nome' => $req->nome,
            'email' => $req->email,
            'telefone' => $req->telefone,
            'rg' => $req->rg,
            'cpf' => $req->cpf,
            'data_nascimento' => $req->data_nascimento,
            'funcionario' => $req->funcionario ?? 0,
            'id_end' => $enderecoId
        ]);
            DB::commit();
            return $pessoa->id;
        }catch(Exception $e){
            dd($e);
            DB::rollback();
            return "Houve um erro no cadastro: $e";
        }


    }

    public function edit($id){

        $pessoa = pessoa::find($id);

        return $pessoa;
    }

    public function update(Request $req, $id){
        DB::beginTransaction();
        try{
            $pessoa = pessoa::find($id);

            $pessoa->update($req->only([
                    'nome',
                    'email',
                    'telefone',
                    'rg',
                    'cpf',
                    'data_nascimento',
                    'funcionario'
            ]));

            DB::commit();
            $msg = 'Registro atualizado com sucesso!';
            return $msg;
        }catch(Exception $e){
            DB::rollback();
            $msg = "Houve um erro no atualizar: $e";
            return $msg;
        }

    }
}
