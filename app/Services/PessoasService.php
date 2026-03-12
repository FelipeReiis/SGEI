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

            if(isset($req->aluno)){
                $cont = 0;
                $pessoasId = [];
                foreach($req->all() as $chave => $valor){
                    if($chave == 'mesmo_responsavel'){
                        break;
                    }

                    $pessoa = pessoa::create([
                        'nome' => $valor['nome'],
                        'email' => $valor['email'],
                        'telefone' => $valor['telefone'],
                        'rg' => $valor['rg'],
                        'cpf' => $valor['cpf'],
                        'data_nascimento' => $valor['data_nascimento'],
                        'funcionario' => $valor['funcionario'] ?? 0,
                        'id_end' => $enderecoId[$cont][$chave]
                    ]);
                    $pessoasId[] =  [$chave => $pessoa->id];

                    if(isset($req->mesmo_responsavel) && $req->mesmo_responsavel && $chave == 'pedagogico'){
                        break;
                    }
                    $cont++;
                }
                DB::commit();
                return $pessoasId;
            }else{
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
            }

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
            if(isset($req->aluno)){

                foreach($req->all() as $chave => $valor){
                    if($chave == 'mesmo_responsavel'){
                        break;
                    }
                    $pessoa = pessoa::find($valor['id']);

                    $pessoa->update([
                        'nome' => $valor['nome'],
                        'email' => $valor['email'],
                        'telefone' => $valor['telefone'],
                        'rg' => $valor['rg'],
                        'cpf' => $valor['cpf'],
                        'data_nascimento' => $valor['data_nascimento'],
                        'funcionario' => $valor['funcionario'] ?? 0,
                    ]);

                    if(isset($req->mesmo_responsavel) && $req->mesmo_responsavel && $chave == 'pedagogico'){
                        break;
                    }
                }
                DB::commit();
                $msg = 'Registros atualizados com sucesso!';
                return $msg;
            }
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
