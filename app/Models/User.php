<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Models\RolTrabajador;
use App\Models\HistoriaClinica;
use App\Models\Ovocito;
use App\Models\Puncion;
use Illuminate\Support\Facades\Log;

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
        'cambio_password',
        'fecha_nacimiento',
        'ocupacion',
        'obra_social_id',
        'proximo_turno',
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

    public function obtenerDniPareja()
    {
        Log::info("Buscando pareja para paciente", [
            'paciente_id' => $this->id
        ]);

        $historia = $this->historiasClinicas;

        if (!$historia) {
            Log::warning("Paciente NO tiene historia clínica", [
                'paciente_id' => $this->id
            ]);
            return null;
        }

        Log::info("Historia clínica encontrada", [
            'historia_id' => $historia->id
        ]);

        $tratamiento = $historia->tratamientos()->latest()->first();

        if (!$tratamiento) {
            Log::warning("Historia sin tratamientos", [
                'historia_id' => $historia->id
            ]);
            return null;
        }

        Log::info("Tratamiento encontrado", [
            'tratamiento_id' => $tratamiento->id
        ]);

        $antecedente = $tratamiento->antecedentesPareja;

        if (!$antecedente) {
            Log::warning("Tratamiento SIN antecedente pareja", [
                'tratamiento_id' => $tratamiento->id
            ]);
            return null;
        }

        Log::info("Antecedente pareja encontrado", [
            'dni' => $antecedente->dni
        ]);

        return $antecedente->dni;
    }
};
