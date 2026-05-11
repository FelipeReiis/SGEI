<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = [
        'grau',
        'horario',
        'id_curso',
        'id_nivel',
        'id_professor'
    ];

    public function professorDias(){
        return $this->hasMany(ProfessorDias::class, 'id_professor')->whereColumn('id_turma','turmas.id');
    }

}
