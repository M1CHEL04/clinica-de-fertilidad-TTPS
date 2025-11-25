<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class AntecedenteGenital extends Model
{
    protected $table = 'antecedentes_genitales';

    protected $fillable = [
        'tratamiento_id',
        'observacion',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class, 'tratamiento_id');
    }
}
