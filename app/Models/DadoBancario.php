<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadoBancario extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'banco',
        'agencia',
        'conta',
        'pix',
        'id_pessoa'
    ];
}
