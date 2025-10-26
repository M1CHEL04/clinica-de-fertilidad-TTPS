<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriaClinica extends Model
{
    protected $table = 'historias_clinica';

    protected $fillable = [
        'paciente_id',
    ];

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class, 'historia_clinica_id');
    }
}
