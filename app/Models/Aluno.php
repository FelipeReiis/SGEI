<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = [
        'id_pessoa',
        'id_resp_fin',
        'id_resp_pedag',
        'escola'
    ];
}
