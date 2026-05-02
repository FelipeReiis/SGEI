<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = [
        'grau',
        'horario',
        'id_curso',
        'id_professor',
        'id_nivel'
    ];


}
