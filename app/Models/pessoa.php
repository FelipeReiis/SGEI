<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pessoa extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'rg',
        'cpf',
        'data_nascimento',
        'funcionario',
        'id_end'
    ];
}
