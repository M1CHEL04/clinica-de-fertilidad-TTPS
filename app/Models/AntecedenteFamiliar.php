<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class AntecedenteFamiliar extends Model
{
    protected $table = 'antecedentes_familiares';

    protected $fillable = [
        'tratamiento_id',
        'descripcion',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}
