<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessorDias extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_professor',
        'dia',
        'id_turma'
    ];
}
