<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'data',
        'valor',
        'imagem',
        'observacao',
        'status'
    ];
}
