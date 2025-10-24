<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolTrabajador extends Model
{
    use HasFactory;

    protected $table = 'roles_trabajadores';

    protected $fillable = [
        'nombre', // nombre del rol, ej: 'administrador', 'medico', 'secretaria', etc.
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'rol_id', 'id');
    }
}
