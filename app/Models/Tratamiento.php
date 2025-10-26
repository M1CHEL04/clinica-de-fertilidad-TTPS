<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $table = 'tratamientos';

    protected $fillable = [
        'historia_clinica_id',
    ];

    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'historia_clinica_id');
    }

    public function objetivo()
    {
        return $this->belongsTo(objetivo::class, 'objetivo_id');
    }

    public function estadoTratamiento()
    {
        return $this->belongsTo(EstadoTratamiento::class, 'estado_tratamiento_id');
    }

    public function estudios(){
        return $this->hasMany(Estudio::class, 'tratamiento_id');
    }
}
