<?php

namespace App\Services;

use App\Models\DadoBancario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DadosBancarios{


    public function store(Request $req){
        DB::beginTransaction();
        try{
            if(isset($req->aluno)){
                DadoBancario::create([
                    'agencia' => $req->bancario['agencia'],
                    'conta' => $req->bancario['conta'],
                    'banco' => $req->bancario['banco'],
                    'pix' => $req->bancario['pix'],
                ]);

                DB::commit();
                return true;
            }

            DadoBancario::create([
                'agencia' => $req->agencia,
                'conta' => $req->conta,
                'banco' => $req->banco,
                'pix' => $req->pix,
            ]);

                DB::commit();
                return true;

        }catch(Exception $e){
            DB::rollback();
            return "Ocorreu um erro ao cadastrar os dados bancarios: $e";
        }
    }
}
