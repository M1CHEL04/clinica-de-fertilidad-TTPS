<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TipoFertilizacion;
use App\Models\Embrion;
use App\Models\User;

class Fertilizacion extends Model
{
    protected $table = 'fertilizaciones';

    protected $fillable = [
        'tipo_fertilizacion_id',
        'calidad',
    ];

    public function tipo_fertilizacion()
    {
        return $this->belongsTo(TipoFertilizacion::class, 'tipo_fertilizacion_id');
    }

    public function embrion()
    {
        return $this->belongsTo(Embrion::class, 'embrion_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }
}
