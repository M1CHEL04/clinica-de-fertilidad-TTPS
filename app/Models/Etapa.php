<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class etapa extends Model
{
    protected $table = 'etapa';

    protected $fillable = [
        'nombre',
    ];

    public function tratamientos()
{
    return $this->hasMany(Tratamiento::class, 'etapa_id');
}


}
