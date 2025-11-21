<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class AntecedenteFamiliar extends Model
{
    protected $table = 'antecedentes_familiares';

    protected $fillable = [
        'tratamiento_id',
        'patologias_familiares', // usaremos para 'parentesco'
        'patologias',            // guardaremos JSON de patologías
    ];

    protected $casts = [
        'patologias' => 'array', // Laravel convertirá automáticamente JSON <-> array
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}


