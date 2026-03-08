<?php

namespace App\Services;

use App\Models\endereco;
use App\Models\pessoa;
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
            if(isset($req->aluno)){
            $idEnderecos = [];
                foreach($req->all() as $chave => $valor){
                    if($chave == 'mesmo_responsavel'){
                        break;
                    }

                    $endereco = endereco::create([
                        'complemento' => $valor['complemento'],
                        'cep' => $valor['cep'],
                        'numero' => 121,
                        'logradouro' => $valor['logradouro'],
                        'bairro' => $valor['bairro'],
                    ]);
                    $idEnderecos[] = [$chave => $endereco->id];

                    if(isset($req->mesmo_responsavel) && $req->mesmo_responsavel && $chave == 'pedagogico'){
                        break;
                    }
                }
            DB::commit();
            return $idEnderecos;
        }else{
            $endereco = endereco::create([
                'complemento' => $req->complemento,
                'cep' => $req->cep,
                'numero' => 121,
                'logradouro' => $req->logradouro,
                'bairro' => $req->bairro,
            ]);
            DB::commit();
            return $endereco->id;
        }

        }catch(Exception $e){
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
            if()
            $id_end = pessoa::select('id_end')->where('id', $id)->first();
            $endereco = endereco::find($id_end->id_end);

            $endereco->update($req->only([
                'complemento',
                'cep',
                'numero',
                'bairro',
                'logradouro'
            ]));

            DB::commit();
            $msg = 'Registro atualizado com sucesso!';
            return $msg;
        }catch(Exception $e){
            DB::rollback();
            $msg = "Erro ao atualizar registro: $e";
            return $msg;
        }
    }
}
