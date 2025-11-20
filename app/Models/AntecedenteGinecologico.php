<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class AntecedenteGinecologico extends Model
{
    protected $table =  'antecedentes_ginecologicos';

    protected $fillable = [
        'tratamiento_id',
        'ciclo_regular',
        'duracion',
        'caracteristicas_sangrado',
        'edad_menarca',
        'G',
        'P',
        'AB',
        'CT',
        'examen_fisico'
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}
