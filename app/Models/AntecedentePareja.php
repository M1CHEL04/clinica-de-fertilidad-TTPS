<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class AntecedentePareja extends Model
{
    protected $table = 'antecedentes_pareja';

    protected $fillable = [
        'tratamiento_id',
        // Ginecológicos
        'ciclo_regular',
        'duracion',
        'caracteristicas_sangrado',
        'edad_menarca',
        'G',
        'P',
        'AB',
        'CT',
        'examen_fisico',
        // Genitales
        'descripcion_genital',
        // Personales
        'fuma',
        'cuanto_fuma',
        'alcohol',
        'frecuencia_alcohol',
        'bebida_alcohol',
        'droga',
        'observaciones_personales',
        'antecedentes_personales',
        // Familiares
        'descripcion_familiar',
        // Fenotipo
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
