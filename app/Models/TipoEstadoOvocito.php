<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EstadoOvocito;

class TipoEstadoOvocito extends Model
{
    protected $table = 'tipo_estado_ovocito';

    protected $fillable = [
        'nombre'
    ];

    public function estados_ovocitos()
    {
        return $this->hasMany(EstadoOvocito::class, 'tipo_estado_ovocito_id');
    }
}
