<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TipoEstadoOvocito;
use App\Models\Ovocito;

class EstadoOvocito extends Model
{
    protected $table = 'estados_ovocitos';

    protected $fillable = [
        'tipo_estado_ovocito_id',
        'motivo_descarte',
        'tiempo'
    ];

    public function TipoEstadoOvocito()
    {
        return $this->belongsTo(TipoEstadoOvocito::class, 'tipo_estado_ovocito_id');
    }

    public function ovocito()
    {
        return $this->hasOne(Ovocito::class, 'estado_ovocito_id');
    }
}
