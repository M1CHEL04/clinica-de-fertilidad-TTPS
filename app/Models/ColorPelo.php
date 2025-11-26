<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorPelo extends Model
{
    protected $table = 'colores_pelo';

    protected $fillable = [
        'nombre',
        'value'
    ];
}
