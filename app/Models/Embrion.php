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
        'criopreservado',
        'utilizado',
        'estado_embrion_id',
        'fertilizacion_id',
        'ovocito_id',
        'calidad_morfologica',
        'realizo_PGT',
        'pgt_positivo',
        'semen_dni',
        'transferir',
        'motivo_descarte',
        'gameto_id',
        'semen_fresco',
        'compatibilidad_gameto',
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

    public function historial()
    {
        return $this->hasMany(HistorialEmbrion::class, 'embrion_id');
    }
}
