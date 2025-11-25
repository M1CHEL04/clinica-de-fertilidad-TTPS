<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Guardado;
use App\Models\Tratamiento;
use App\Models\EstadoEmbrion;

class Embrion extends Model
{
    protected $table = 'embriones';

    protected $fillable = [
        'identificador',
        'guardado_id',
        'estado_embrion_id',
        'fertilizacion_id',
        'ovocito_id',
        'calidad_morfologica',
        'semen_dni',
        'gameto_id',
        'urlPGT'
    ];

    public function guardado()
    {
        return $this->belongsTo(Guardado::class, 'guardado_id');
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoEmbrion::class, 'estado_embrion_id');
    }

    public function fertilizacion()
    {
        return $this->belongsTo(Fertilizacion::class, 'fertilizacion_id');
    }

    public function ovocito()
    {
        return $this->belongsTo(Ovocito::class, 'ovocito_id');
    }
}
