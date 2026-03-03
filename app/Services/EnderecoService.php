<?php

namespace App\Services;

use App\Models\endereco;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnderecoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(Request $req){

        DB::beginTransaction();
        try{
            $endereco = endereco::create([
                'complemento' => $req->complemento,
                'cep' => $req->cep,
                'numero' => 121,
                'bairro' => $req->bairro,
                'logradouro' => $req->logradouro
            ]);
            DB::commit();
            dd($endereco);
            return $endereco->id;
        }catch(Exception $e){
            dd($e);
            DB::rollback();
            return "Ocorreu um erro ao cadastrar: $e";
        }

    }

    public function edit($id){
        $endereco = endereco::find($id);

        return $endereco;
    }

    public function update(Request $req, $id){
        DB::beginTransaction();
        try{

            $endereco = endereco::find($id);

            $endereco->update($req->only([
                'compelmento',
                'cep',
                'numero',
                'bairro',
                'logradouro'
            ]));

            DB::commit();
            return 'Registro atualizado com sucesso!';
        }catch(Exception $e){
            DB::rollback();
            return "Erro ao atualizar registro: $e";
        }
    }
}
