<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPelo extends Model
{
    protected $table = 'tipos_pelo';

    protected $fillable = [
        'nombre',
        'value'
    ];
}
