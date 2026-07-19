<?php

namespace App\Exports;

use App\Models\Pagamento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MensalidadeExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $mensalidadeId;

    public function __construct($mensalidadeId)
    {
        $this->mensalidadeId = $mensalidadeId;
    }
    public function query()
    {
        return Pagamento::query()
            ->select(
                'pessoas.nome',
                'pagamentos.forma_pagamento',
                'pessoas.cpf',
                'turmas.id',
                'cursos.nome as cursos_nome',
                'mensalidades.mes',
                'mensalidades.ano'
            )
            ->join('alunos', 'pagamentos.id_aluno', '=', 'alunos.id')
            ->leftJoin('pessoas', 'alunos.id_pessoa', '=', 'pessoas.id')
            ->leftJoin('turmas', 'alunos.id_turma', '=', 'turmas.id')
            ->leftJoin('cursos', 'turmas.id_curso', '=', 'cursos.id')
            ->leftJoin('mensalidades', 'pagamentos.id_mensalidade', '=', 'mensalidades.id')
            ->where('mensalidades.id', $this->mensalidadeId);
    }

    /**
     * 2. O CABEÇALHO: Define a primeira linha do Excel
     * A ordem aqui deve ser EXATAMENTE a mesma ordem dos campos no select acima.
     */
    public function headings(): array
    {
        return [
            'Nome do Aluno',
            'Forma de Pagamento',
            'CPF',
            'ID da Turma',
            'Nome do Curso',
            'Mês',
            'Ano'
        ];
    }
}
