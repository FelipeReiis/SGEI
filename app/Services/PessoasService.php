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
            $limpar = fn($valor) => preg_replace('/\D/', '', $valor);

            if(isset($req->aluno)){
                $cont = 0;
                $pessoasId = [];
                foreach($req->all() as $chave => $valor){
                    if($chave == 'mesmo_responsavel' or $chave == 'bancario'){
                        break;
                    }

                    $pessoa = pessoa::create([
                        'nome' => trim($valor['nome']),
                        'email' => trim($valor['email']),
                        'telefone' => trim($limpar($valor['telefone'])),
                        'rg' => trim($valor['rg']),
                        'cpf' => trim($limpar($valor['cpf'])),
                        'data_nascimento' => trim($valor['data_nascimento']),
                        'funcionario' => trim($valor['funcionario'] ?? 0) ,
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
                    'nome' => trim($req->nome),
                    'email' => trim($req->email),
                    'telefone' => trim($limpar($req->telefone)),
                    'rg' => trim($req->rg),
                    'cpf' => trim($limpar($req->cpf)),
                    'data_nascimento' => trim($req->data_nascimento),
                    'funcionario' => trim($req->funcionario ?? 0 ) ,
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
            $limpar = fn($valor) => preg_replace('/\D/', '', $valor);
            if(isset($req->aluno)){

                foreach($req->all() as $chave => $valor){
                   if($chave == 'mesmo_responsavel' or $chave == 'bancario'){
                        break;
                    }
                    $pessoa = pessoa::find($valor['id']);

                    $pessoa->update([
                        'nome' => trim($valor['nome']),
                        'email' => trim($valor['email']),
                        'telefone' => trim($limpar($valor['telefone'])),
                        'rg' => trim($valor['rg']),
                        'cpf' => trim($limpar($valor['cpf'])),
                        'data_nascimento' => trim($valor['data_nascimento']),
                        'funcionario' => trim($valor['funcionario'] ?? 0),
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

            $pessoa->update([
                    'nome' => trim($req->nome),
                    'email' => trim($req->email),
                    'telefone' => trim($limpar($req->telefone)),
                    'rg' => trim($req->rg),
                    'cpf' => $limpar($req->cpf),
                    'data_nascimento' => trim($req->data_nascimento),
                    'funcionario' => trim($req->funcionario ?? 0),
                ]);

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
