<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialOvocito extends Model
{
    protected $table = 'historial_ovocitos';

    protected $fillable = [
        'ovocito_id',
        'estado_anterior_id',
        'estado_nuevo_id',
        'accion',
        'motivo_descarte',
        'tiempo_maduracion',
        'operador_id',
        'fecha_cambio',
    ];

    public function ovocito() {
        return $this->belongsTo(Ovocito::class);
    }

    public function estadoAnterior()
{
    return $this->belongsTo(TipoEstadoOvocito::class, 'estado_anterior_id');
}

public function estadoNuevo()
{
    return $this->belongsTo(TipoEstadoOvocito::class, 'estado_nuevo_id');
}


    public function operador() {
        return $this->belongsTo(User::class,'operador_id');
    }
}
