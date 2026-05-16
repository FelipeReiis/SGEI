<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $primaryKey = 'id';
    public$timestamps = false;
    protected $fillable = [
        'nome',
        'data',
        'valor',
        'imagem',
        'observacao',
        'status'
    ];
}
