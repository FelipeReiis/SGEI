<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class endereco extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'cep',
        'logradouro',
        'complemento',
        'bairro',
        'numero'
    ];
}
