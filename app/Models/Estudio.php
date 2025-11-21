<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $table = 'estudios';

    protected $fillable = [
        'tratamiento_id',
        'tipo_estudio',
        'nombre',
        'resultado',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }
    
}
