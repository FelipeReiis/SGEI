<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docs_funcionario extends Model
{
    protected $table = 'docs_funcionarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_funcionario',
        'caminho',
    ];
}
