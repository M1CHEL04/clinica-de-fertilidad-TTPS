<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Embrion;

class EstadoEmbrion extends Model
{
    protected $table = 'estados_embrion';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function embriones()
    {
        return $this->hasMany(Embrion::class, 'estado_embrion_id');
    }
}
