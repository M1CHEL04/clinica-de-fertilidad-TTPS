<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;
use App\Models\TipoMedicacion;

class ProtocoloEstimulacion extends Model
{
    protected $table = 'protocolos_estimulacion';

    protected $fillable = [
        'tratamiento_id',
        'tipo_medicacion_id',
        'dosis',
        'tiempo',
        'droga'
    ];

    public function tipoMedicacion()
    {
        return $this->belongsTo(TipoMedicacion::class);
    }

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}
