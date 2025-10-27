<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class AntecedenteFenotipo extends Model
{
    protected $table = 'antecedentes_fenotipo';

    protected $fillable = [
        'tratamiento_id',
        'color_ojos',
        'tipo_pelo',
        'altura',
        'complexion_corporal',
        'rasgos_etnicos',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }
}
