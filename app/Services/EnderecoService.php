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
            $limpar = fn($valor) => preg_replace('/\D/', '', $valor);
            if(isset($req->aluno)){
            $idEnderecos = [];
                foreach($req->all() as $chave => $valor){
                    if($chave == 'mesmo_responsavel' or $chave == 'bancario'){
                        break;
                    }

                    $endereco = endereco::create([
                        'complemento' => trim($valor['complemento']),
                        'cep' => trim($limpar($valor['cep'])),
                        'numero' =>  trim($valor['numero']),
                        'logradouro' => trim($valor['logradouro']),
                        'bairro' => trim($valor['bairro']),
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
                'complemento' => trim($req->complemento),
                'cep' => trim($limpar($req->cep)),
                'numero' =>trim($req->numero),
                'logradouro' =>trim($req->logradouro),
                'bairro' =>trim($req->bairro),
            ]);
            DB::commit();
            return $endereco->id;
        }

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
            $limpar = fn($valor) => preg_replace('/\D/', '', $valor);
            if(isset($req->aluno)){
                foreach($req->all() as $chave => $valor){
                    if($chave == 'mesmo_responsavel' or $chave == 'bancario'){
                        break;
                    }

                    $id_end = pessoa::select('id_end')->where('id', $valor['id'])->first();

                    $endereco = endereco::find($id_end->id_end);

                    $endereco->update([
                        'complemento' => trim($valor['complemento']),
                        'cep' => trim($limpar($valor['cep'])),
                        'numero' => trim($valor['numero']),
                        'logradouro' => trim($valor['logradouro']),
                        'bairro' => trim($valor['bairro']),
                    ]);

                    if(isset($req->mesmo_responsavel) && $req->mesmo_responsavel && $chave == 'pedagogico'){
                        break;
                    }
                }
                DB::commit();
                $msg = 'Registros atualizados com sucesso!';
                return $msg;
            }

            $id_end = pessoa::select('id_end')->where('id', $id)->first();
            $endereco = endereco::find($id_end->id_end);

            $endereco->update([
                'complemento' => trim($req->complemento),
                'cep' => trim($limpar($req->cep)),
                'numero' => trim($req->numero),
                'bairro' => trim($req->bairro),
                'logradouro' => trim($req->logradouro)
            ]);

            DB::commit();
            $msg = 'Registro atualizado com sucesso!';
            return $msg;
        }catch(Exception $e){
            dd($e);
            DB::rollback();
            $msg = "Erro ao atualizar registro: $e";
            return $msg;
        }
    }
}
