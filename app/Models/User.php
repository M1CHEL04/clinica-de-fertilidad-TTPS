<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

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

    // Hash automático al asignar password
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
