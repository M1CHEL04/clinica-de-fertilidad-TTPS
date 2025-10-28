<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Ovocito;

class Puncion extends Model
{
    protected $table = 'punciones';

    protected $fillable = [
        'fecha_hora',
        'nro_quirofano',
        'operador_id',
    ];

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }

    public function ovocitos()
    {
        return $this->hasMany(Ovocito::class, 'puncion_id');
    }
}
