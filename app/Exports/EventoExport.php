<?php

namespace App\Exports;

use App\Models\Pagamento;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventoExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $eventoId;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }
    public function query()
    {
       return Pagamento::query()
                            ->select(
                                'pessoas.nome',
                                'pessoas.cpf',
                                'pagamentos.forma_pagamento',
                                'pagamentos.qtd_parcela',

                                
                                DB::raw('(SELECT COUNT(*) FROM anexos WHERE anexos.id_pagamento = pagamentos.id) as parcelas_pagas')
                            )
                            ->join('alunos', 'pagamentos.id_aluno', '=', 'alunos.id')
                            ->leftJoin('pessoas', 'alunos.id_pessoa', '=', 'pessoas.id')
                            ->leftJoin('eventos', 'pagamentos.id_evento', '=', 'eventos.id')
                            ->where('eventos.id', $this->eventoId);
    }

    /**
     * 2. O CABEÇALHO: Define a primeira linha do Excel
     * A ordem aqui deve ser EXATAMENTE a mesma ordem dos campos no select acima.
     */
    public function headings(): array
    {
        return [
            'Nome do Aluno',
            'CPF',
            'Forma de Pagamento',
            'Qtd. Parcelas',
            'Parcelas Pagas'
        ];
    }
}
