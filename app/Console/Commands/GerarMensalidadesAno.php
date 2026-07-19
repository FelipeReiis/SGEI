<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GerarMensalidadesAno extends Command
{
    // O comando que você pode testar manualmente pelo terminal
    protected $signature = 'mensalidades:gerar-ano';

    protected $description = 'Gera automaticamente os 12 meses de mensalidades para o ano seguinte na virada do ano';

    public function handle()
    {
        // 1. Pega o ano atual em que o comando rodou (Ex: se rodar em 01/01/2027, pegará 2027)
        $novoAno = Carbon::now()->format('Y');

        // Lista de meses por extenso conforme o padrão do seu banco
        $meses = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        $this->info("Iniciando a geração de mensalidades para o ano {$novoAno}...");

        foreach ($meses as $index => $mesNome) {
            $numeroMes = $index + 1;

            // 2. Define a data de vencimento padrão: Todo dia 10 do mês correspondente
            // Ex: 2027-01-10, 2027-02-10...
            $dataVencimentoPadrao = Carbon::create($novoAno, $numeroMes, 10)->format('Y-m-d');

            // 3. Insere no banco se ainda não existir (evita duplicidade caso o comando rode duas vezes)
            // 💡 AJUSTE: Mude 'mensalidades' para o nome exato da sua tabela se for diferente
            DB::table('mensalidades')->updateOrInsert(
                [
                    'mes' => $mesNome,
                    'ano' => (string) $novoAno
                ],
                [
                    'valor' => 0.00, // Valor padrão temporário (pode ser editado depois no painel)
                    'data_vencimento' => $dataVencimentoPadrao,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
        }

        $this->info("Mensalidades do ano {$novoAno} geradas com sucesso!");
        return Command::SUCCESS;
    }
}
