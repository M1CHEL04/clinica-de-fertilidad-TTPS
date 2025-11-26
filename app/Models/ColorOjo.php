<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorOjo extends Model
{
    protected $table = 'colores_ojo';

    protected $fillable = [
        'nombre',
        'value'
    ];
}
