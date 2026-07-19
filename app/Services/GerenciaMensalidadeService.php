<?php
    namespace App\Services;

    use App\Exports\MensalidadeExport;
    use App\Models\Aluno;
use App\Models\Anexo;
use App\Models\Mensalidade;
    use App\Models\Pagamento;
    use Exception;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;
    use Maatwebsite\Excel\Facades\Excel;

    class GerenciaMensalidadeService{
        public function edit($id, $req){
            try{
                $alunos = Aluno::join('pessoas', 'alunos.id_pessoa', 'pessoas.id')
                                ->join('pessoas as pessoas_fin', 'alunos.id_resp_fin', 'pessoas_fin.id')
                                ->select('pessoas.nome as aluno_nome', 'pessoas.cpf as aluno_cpf', 'alunos.id as aluno_id', 'pessoas_fin.nome as fin_nome', 'pessoas_fin.cpf as fin_cpf');
                if($req->busca){
                    $alunos->where('pessoas.nome', 'ILIKE', '%'.$req->busca.'%');
                }
                if($req->sort){
                    if($req->sort == 'aluno_nome')
                        $alunos->orderBy('aluno_nome');
                    else{
                        $alunos->orderBy('aluno_cpf');
                    }
                }
                $mensalidade = Mensalidade::find($id);
                $alunosInscritos = Pagamento::where('id_mensalidade', $id)->select('id_aluno as aluno_id')->pluck('aluno_id')->toArray();
                return [$alunos, $mensalidade, $alunosInscritos];

            }catch(Exception $e){
                throw new Exception ("Houve um erro ao carregar as informações: " . $e->getMessage());

            }
        }

        public function store($req){
            try{
                
                DB::beginTransaction();
                $valorMensalidade = Mensalidade::where('id', $req->mensalidade_id)->select('id', 'valor','mes')->first();
                if($req->hasFile('comprovante')){
                    $arquivo = $req->file('comprovante');
                    $nomeNovo = str_replace(' ', '-',$valorMensalidade->mes).'-'.Carbon::now()->format('Y-m-d').$arquivo->getClientOriginalName();
                    $arquivo->storeAs('comprovantes_mensalidades', $nomeNovo, 'public');
                    $caminho =  'comprovantes_mensalidades/' . $nomeNovo;
                }
               $pagamento =  Pagamento::create([
                    'id_mensalidade' => $valorMensalidade->id,
                    'id_aluno' => $req->aluno_id,
                    'pago_em' => Carbon::now(),
                    'valor' => $valorMensalidade->valor
                ]);

                Anexo::create([
                    'id_pagamento' => $pagamento->id,
                    'caminho' => $caminho
                ]);
                DB::commit();
                return  'Alunos cadastrados no evento com sucesso';
            }catch(Exception $e){
                DB::rollback();
                throw new Exception ("Houve um erro ao cadastrar o aluno no evento: " . $e->getMessage());
            }
        }

         public function relacaoMensalidadeAlunoExport($idMensalidade){
            return Excel::download(new MensalidadeExport($idMensalidade), 'mensalidade_alunos.xlsx');

        }
    }



