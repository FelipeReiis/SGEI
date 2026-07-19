<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anexo extends Model
{
    use HasFactory;

    // Define explicitamente o nome da tabela se necessário
    protected $table = 'anexos';

    protected $fillable = [
        'id_pagamento',
        'caminho',
    ];

    /**
     * Relacionamento: Um anexo pertence a um Pagamento de Evento
     */
    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class, 'id_pagamento');
    }

    /**
     * Relacionamento: Um anexo pertence a uma Mensalidade
     */
    public function mensalidade()
    {
        return $this->belongsTo(Mensalidade::class, 'id_mensalidade');
    }
}
