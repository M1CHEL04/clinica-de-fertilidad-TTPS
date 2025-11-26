<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complexion extends Model
{
    protected $table = 'complexiones';

    protected $fillable = [
        'nombre',
        'value'
    ];
}
