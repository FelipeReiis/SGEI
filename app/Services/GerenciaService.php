<?php

    namespace App\Services;

    use App\Models\Aluno;
    use App\Models\Evento;
    use App\Models\Pagamento;
use Carbon\Carbon;
use Exception;
    use GuzzleHttp\Psr7\Request;
    use Illuminate\Support\Facades\DB;

    class GerenciaService
    {

        public function store($req){
            try{
                DB::beginTransaction();
                $valorEvento = Evento::where('id', $req->evento_id)->select('valor','nome')->first();
                if($req->hasFile('comprovante')){
                    $arquivo = $req->file('comprovante');
                    $nomeNovo = str_replace(' ', '-',$valorEvento->nome).'-'.Carbon::now()->format('Y-m-d').$arquivo->getClientOriginalName();
                    $arquivo->storeAs('comprovantes_eventos', $nomeNovo, 'public');
                    $caminho =  'comprovantes_eventos/' . $nomeNovo;
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
                throw new Exception ("Houve um erro ao cadastrar o aluno no evento: " . $e->getMessage());
            }
        }

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
                $evento = Evento::find($id);
                $alunosInscritos = Pagamento::where('id_evento', $id)->select('id_aluno as aluno_id')->pluck('aluno_id')->toArray();
                return [$alunos, $evento, $alunosInscritos];

            }catch(Exception $e){
                throw new Exception ("Houve um erro ao carregar as informações: " . $e->getMessage());

            }
        }
    }
