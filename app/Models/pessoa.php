<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pessoa extends Model
{
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'rg',
        'cpf',
        'data_nascimento',
        'funcionario',
        'id_end',
        'id_profissao'
    ];

    public function endereco() {
        return $this->belongsTo(endereco::class, 'id_end');
    }

    public function bancario() {
        return $this->hasOne(DadoBancario::class, 'id_pessoa');
    }

    public function professor(){
        return $this->hasMany(Professor::class, 'id_pessoa');
    }
}
