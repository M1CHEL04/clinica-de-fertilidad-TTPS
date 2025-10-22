<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'mail',
        'password',
        'nombre',
        'apellido',
        'telefono',
    ];

    protected $hidden = [
        'password',
    ];

    // Mutator para hashear automáticamente la contraseña
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Relación uno a uno con Paciente
    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }
}