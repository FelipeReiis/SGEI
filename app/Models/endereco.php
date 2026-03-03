<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class endereco extends Model
{
    protected $timestamp = false;
    protected $fillable = [
        'cep',
        'lograduro',
        'complemento',
        'bairro',
        'numero'
    ];
}
