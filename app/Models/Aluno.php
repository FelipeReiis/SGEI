<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = [
        'id_pessoa',
        'id_resp_fin',
        'id_resp_pedag',
        'escola',
        'status'
    ];

    public function getComprovantesSalvosAttribute($value)
    {
        // Se o valor for uma string, decodifica. Se for nulo ou vazio, retorna um array limpo
        return is_string($value) ? json_decode($value) : ($value ?? []);
    }
}
