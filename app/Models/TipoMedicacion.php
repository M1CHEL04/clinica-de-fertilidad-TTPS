<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProtocoloEstimulacion;

class TipoMedicacion extends Model
{
    protected $table = 'tipos_medicacion';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function protocolosEstimulacion()
    {
        return $this->hasMany(ProtocoloEstimulacion::class, 'tipo_medicacion_id');
    }
}
