<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = [
        'grau',
        'horario',
        'horario_final',
        'id_curso',
        'id_nivel',
        'id_professor'
    ];

    public function professorDias(){
        return $this->hasMany(ProfessorDias::class, 'id_professor')->whereColumn('id_turma','turmas.id');
    }

}
