<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEmbrion extends Model
{
    protected $table = 'historiales_embrion';

    protected $fillable = [
        'embrion_id',
        'operador_id',
        'accion',
        'motivo_descarte_anterior',
        'motivo_descarte_nuevo',
        'transeferir_anterior',
        'transeferir_nuevo',
        'descripcion',
        'guardado_id_anterior',
        'guardado_id_nuevo',
    ];

    public function embrion()
    {
        return $this->belongsTo(Embrion::class, 'embrion_id');
    }
}
