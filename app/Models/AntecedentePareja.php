<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

use App\Models\AntecedenteFamiliar;

class AntecedentePareja extends Model
{
    protected $table = 'antecedentes_pareja';

    protected $fillable = [
        'tratamiento_id',
        'dni',
        'ciclo_regular',
        'duracion',
        'caracteristicas_sangrado',
        'edad_menarca',
        'G',
        'P',
        'AB',
        'CT',
        'examen_fisico',
        'descripcion_genital',
        'fuma',
        'cuanto_fuma',
        'alcohol',
        'frecuencia_alcohol',
        'bebida_alcohol',
        'droga',
        'observaciones_personales',
        'antecedentes_personales',
        'descripcion_familiar',
        'color_ojos',
        'tipo_pelo',
        'altura',
        'complexion_corporal',
        'rasgos_etnicos',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }

  
}
