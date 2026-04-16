<?php

namespace App\Services;

use App\Models\DadoBancario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DadosBancarios{


    public function store($req, $pessoasId){
        DB::beginTransaction();
        try{
            if(isset($req->aluno)){
                DadoBancario::create([
                    'id_pessoa' => isset($pessoasId[2]['financeiro']) ? $pessoasId[2]['financeiro'] : $pessoasId[1]['pedagogico'] ,
                    'agencia' => $req->bancario['agencia'],
                    'conta' => $req->bancario['conta'],
                    'banco' => $req->bancario['banco'],
                    'pix' => $req->bancario['pix'],
                ]);

                DB::commit();
                return true;
            }
            $tipo = array_keys($req->all())[0];
            DadoBancario::create([
                'id_pessoa' => $pessoasId,
                'agencia' => $req->all()[$tipo]['agencia'],
                'conta' => $req->all()[$tipo]['conta'],
                'banco' => $req->all()[$tipo]['banco'],
                'pix' => $req->all()[$tipo]['pix'],
            ]);

                DB::commit();
                return true;

        }catch(Exception $e){
            dd($e);
            DB::rollback();
            return "Ocorreu um erro ao cadastrar os dados bancarios: $e";
        }
    }

    public function update($req){
        DB::beginTransaction();
        try{
            $tipo = array_keys($req->all())[0];
            $dadoBancario = DadoBancario::where('id_pessoa', $req->all()[$tipo]['id'])->first();
            $dadoBancario->update([
                'agencia' => $req->all()[$tipo]['agencia'],
                'conta' => $req->all()[$tipo]['conta'],
                'banco' => $req->all()[$tipo]['banco'],
                'pix' => $req->all()[$tipo]['pix'],
            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return "Ocorreu um erro ao atualizar os dados bancarios: $e";
        }
    }
}
