<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Guardado;
use App\Models\User;
use App\Models\Puncion;
use App\Models\EstadoOvocito;



class Ovocito extends Model
{
    protected $table = 'ovocitos';

    protected $fillable = [
        'identificador',
        'guardado_id',
        'paciente_id',
        'puncion_id',
    ];

    public function guardado()
    {
        return $this->belongsTo(Guardado::class, 'guardado_id');
    }

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    public function puncion()
    {
        return $this->belongsTo(Puncion::class, 'puncion_id');
    }

    public function estado_ovocito()
    {
        return $this->belongsTo(EstadoOvocito::class, 'estado_ovocito_id');
    }
}
