<?php

    namespace App\Services;

    use App\Models\Aluno;
    use App\Models\Evento;
    use App\Models\Pagamento;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Support\Facades\DB;
    use App\Exports\EventoExport;
use App\Models\Anexo;
use Maatwebsite\Excel\Facades\Excel;

    class GerenciaService
    {

    public function store($req){
        try{
            DB::beginTransaction();

            $valorEvento = Evento::where('id', $req->evento_id)->select('valor','nome')->first();

            // 1. Primeiro criamos o registro do pagamento (sem a coluna 'comprovante')
            $pagamento = Pagamento::create([
                'id_evento' => $req->evento_id,
                'id_aluno' => $req->aluno_id,
                'forma_pagamento' => $req->forma_pagamento,
                'qtd_parcela' => $req->qtd_parcelas,
                'pago_em' => Carbon::now(),
                'valor' => $valorEvento->valor
            ]);

            // 2. Agora verificamos se existem múltiplos arquivos enviados pelo front-end
            if($req->hasFile('comprovantes')){
                $arquivos = $req->file('comprovantes'); // Recebe o array de arquivos

                foreach ($arquivos as $arquivo) {
                    // Montamos o nome exclusivo do arquivo para evitar sobrescrever
                    $nomeLimpoEvento = str_replace(' ', '-', $valorEvento->nome);
                    $dataAtual = Carbon::now()->format('Y-m-d');
                    $hashUnico = uniqid();

                    $nomeNovo = $nomeLimpoEvento . '-' . $dataAtual . '-' . $hashUnico . '-' . $arquivo->getClientOriginalName();

                    // Salva o arquivo fisicamente na pasta pública do Storage
                    $arquivo->storeAs('comprovantes_eventos', $nomeNovo, 'public');
                    $caminho = 'comprovantes_eventos/' . $nomeNovo;

                    // 3. Salvamos cada anexo na tabela separada vinculando ao ID do pagamento criado acima
                    // 💡 Ajuste o nome do Model e os campos abaixo de acordo com a sua tabela!
                    Anexo::create([
                        'id_pagamento' => $pagamento->id, // Chave estrangeira ligando ao pagamento
                        'caminho'      => $caminho,
                        // 'tipo'      => 'evento', // Opcional: caso use a mesma tabela para mensalidades e eventos
                    ]);
                }
            }

            DB::commit();
            return 'Alunos cadastrados no evento com sucesso';

        }catch(Exception $e){
            DB::rollback();
            throw new Exception ("Houve um erro ao cadastrar o aluno no evento: " . $e->getMessage());
        }
    }

        public function edit($id, $req){
            try{
                $alunos = Aluno::join('pessoas', 'alunos.id_pessoa', '=', 'pessoas.id')
                                ->join('pessoas as pessoas_fin', 'alunos.id_resp_fin', '=', 'pessoas_fin.id')
                                ->leftJoin('pagamentos', function($join) use ($id) {
                                    $join->on('pagamentos.id_aluno', '=', 'alunos.id')
                                        ->where('pagamentos.id_evento', '=', $id); // Filtra o evento DENTRO do join
                                    })

                                ->select(
                                    'pessoas.nome as aluno_nome',
                                    'pessoas.cpf as aluno_cpf',
                                    'alunos.id as aluno_id',
                                    'pessoas_fin.nome as fin_nome',
                                    'pessoas_fin.cpf as fin_cpf',

                                    // 👇 Trazendo os campos novos de pagamento (virão nulos se o aluno não estiver inscrito) 👇
                                    'pagamentos.forma_pagamento',
                                    'pagamentos.qtd_parcela'
                                );
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
        public function relacaoEventoAlunoExport($idEvento){
            return Excel::download(new EventoExport($idEvento), 'evento_alunos.xlsx');
        }
    }
