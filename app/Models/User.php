<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Models\RolTrabajador;
use App\Models\HistoriaClinica;
use App\Models\Ovocito;
use App\Models\Puncion;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'apellido',
        'mail',
        'telefono',
        'password',
        'dni',
        'fecha_nacimiento',
        'ocupacion',
        'obra_social_id',
        'numero_afiliado',
        'rol_id', // FK al rol
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ⚙️ Autenticación por mail
    public function getAuthIdentifierName()
    {
        return 'mail';
    }

    // Relación con rol
    public function rol()
    {
        return $this->belongsTo(RolTrabajador::class, 'rol_id', 'id');
    }

    public function esPaciente()
    {
        return $this->rol && $this->rol_id == 1;
    }

    public function tieneObraSocial()
    {
        return !is_null($this->obra_social_id);
    }

    // Hash automático al asignar password
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function historiasClinicas()
    {
        return $this->hasOne(HistoriaClinica::class, 'paciente_id');
    }

    public function ovocitos()
    {
        return $this->hasMany(Ovocito::class, 'paciente_id');
    }

    function puncionesOperador()
    {
        return $this->hasMany(Puncion::class, 'operador_id');
    }

    public function fertilizacionesPaciente()
    {
        return $this->hasMany(Fertilizacion::class, 'paciente_id');
    }

    public function fertilizacionesOperador()
    {
        return $this->hasMany(Fertilizacion::class, 'operador_id');
    }
};
