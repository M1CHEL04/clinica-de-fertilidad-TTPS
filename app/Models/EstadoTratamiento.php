<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoTratamiento extends Model
{
    protected $table = 'estados_tratamiento';

    protected $fillable = [
        'nombre',
    ];

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'estado_tratamiento_id');
    }
}
