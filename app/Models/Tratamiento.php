<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\HistoriaClinica;
use App\Models\User;
use App\Models\Objetivo;
use App\Models\EstadoTratamiento;
use App\Models\Estudio;
use App\Models\Monitoreo;
use App\Models\AtecedenteFamiliar;
use App\Models\AntecedentePersonal;
use App\Models\AntecedenteGinecologico;
use App\Models\AntecedenteGenital;
use App\Models\AntecedenteFenotipo;
use App\Models\ProtocoloEstimulacion;
use App\Models\Embrion;

class Tratamiento extends Model
{
    protected $table = 'tratamientos';

    protected $fillable = [
        'historia_clinica_id',
        'objetivo_id',
        'estado_tratamiento_id',
        'medico_id',
        'etapa_id',
        'pago_id',
        'fecha_sugerida_inicio',
        'fecha_sugerida_fin',
        'beta',
        'saco',
        'embrion',
        'vivo',
        'consentimiento_pdf'
    ];

    public function historiaClinica()
    {
        return $this->belongsTo(HistoriaClinica::class, 'historia_clinica_id');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    public function etapa()
    {
        return $this->belongsTo(Etapa::class, 'etapa_id');
    }


    public function objetivo()
    {
        return $this->belongsTo(Objetivo::class, 'objetivo_id');
    }

    public function estadoTratamiento()
    {
        return $this->belongsTo(EstadoTratamiento::class, 'estado_tratamiento_id');
    }

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'tratamiento_id');
    }

    public function monitoreos()
    {
        return $this->hasMany(Monitoreo::class, 'tratamiento_id');
    }

    public function antecedentesFamiliares()
    {
        return $this->hasOne(AtecedenteFamiliar::class, 'tratamiento_id');
    }

    public function antecedentesPersonales()
    {
        return $this->hasOne(AntecedentePersonal::class, 'tratamiento_id');
    }

    public function antecedentesGinecologicos()
    {
        return $this->hasOne(AntecedenteGinecologico::class, 'tratamiento_id');
    }

    public function antecedentesGenitales()
    {
        return $this->hasOne(AntecedenteGenital::class, 'tratamiento_id');
    }

    public function antecedentesFenotipos()
    {
        return $this->hasOne(AntecedenteFenotipo::class, 'tratamiento_id');
    }

    public function protocolosEstimulacion()
    {
        return $this->hasMany(ProtocoloEstimulacion::class, 'tratamiento_id');
    }

    public function embriones()
    {
        return $this->hasMany(Embrion::class, 'tratamiento_id');
    }
}
