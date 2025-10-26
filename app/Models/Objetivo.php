<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objetivo extends Model
{
    protected $table = 'objetivos';

    protected $fillable = [
        'nombre',    
    ];

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'objetivo_id');
    }

}
