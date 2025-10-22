<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'usuario_id',
        'fecha_nacimiento',
        'dni',
    ];

    // Relación inversa con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}