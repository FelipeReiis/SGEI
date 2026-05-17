<?php

    namespace App\Services;

    use App\Models\Aluno;
    use App\Models\Evento;
    use App\Models\Pagamento;
    use Carbon\Carbon;
    use Exception;
use Illuminate\Support\Facades\DB;

    class GerenciaService
    {

        public function store($req){
            try{
                DB::beginTransaction();
                $valorEvento = Evento::where('id', $req->evento_id)->select('valor','nome')->first();
                if($req->hasFile('comprovante')){
                    $arquivo = $req->file('comprovante');
                    $nomeNovo = str_replace(' ', '-',$$valorEvento->nome).'-'.Carbon::now()->format('Y-m-d').$arquivo->getClientOriginalName();
                    $caminho = $req->file('comprovante')->storeAs('comprovantes_eventos', $nomeNovo, 'public');
                }
                Pagamento::create([
                    'id_evento' => $req->evento_id,
                    'id_aluno' => $req->aluno_id,
                    'comprovante' => $caminho,
                    'pago_em' => Carbon::now(),
                    'valor' => $valorEvento->valor
                ]);
                DB::commit();
                return  'Alunos cadastrados no evento com sucesso';
            }catch(Exception $e){
                DB::rollback();
                dd($e);
            }
        }

        public function edit($id){
            $alunos = Aluno::join('pessoas', 'alunos.id_pessoa', 'pessoas.id')
                            ->join('pessoas as pessoas_fin', 'alunos.id_resp_fin', 'pessoas_fin.id')
                            ->select('pessoas.nome as aluno_nome', 'pessoas.cpf as aluno_cpf', 'alunos.id as aluno_id', 'pessoas_fin.nome as fin_nome', 'pessoas_fin.cpf as fin_cpf');
            $evento = Evento::find($id);
            $alunosInscritos = Pagamento::where('id_evento', $id)->select('id_aluno as aluno_id')->pluck('aluno_id')->toArray();
            return [$alunos, $evento, $alunosInscritos];
        }
    }
