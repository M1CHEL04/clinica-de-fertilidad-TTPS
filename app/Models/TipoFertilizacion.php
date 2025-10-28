<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Fertilizacion;

class TipoFertilizacion extends Model
{
    protected $table = 'tipos_fertilizacion';

    protected $fillable = [
        'nombre',
    ];

    public function fertilizaciones()
    {
        return $this->hasMany(Fertilizacion::class, 'tipo_fertilizacion_id');
    }
}
