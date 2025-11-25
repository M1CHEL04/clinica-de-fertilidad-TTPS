<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEmbrion extends Model
{
    protected $table = 'historiales_embrion';

    protected $fillable = [
        'embrion_id',
        'estado_anterior',
        'estado_nuevo',
        'fecha_cambio',
        'observaciones'
    ];

    public function embrion()
    {
        return $this->belongsTo(Embrion::class, 'embrion_id');
    }
}
