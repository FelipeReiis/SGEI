<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_aluno',
        'valor',
        'pago_em',
        'id_evento',
        'forma_pagamento',
        'qtd_parcela'
    ];
}
