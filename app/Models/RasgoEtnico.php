<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RasgoEtnico extends Model
{
    protected $table = 'rasgos_etnicos';

    protected $fillable = [
        'nombre',
        'value'
    ];
}
