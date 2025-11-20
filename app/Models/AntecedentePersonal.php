<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class AntecedentePersonal extends Model
{
    protected $table = 'antecedentes_personales';

    protected $fillable = [
        'tratamiento_id',
        'fuma',
        'cuanto_fuma',
        'alcohol',
        'frecuencia_alcohol',
        'bebida_alcohol',
        'droga',
        'observaciones',
        'antecedentes',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}
