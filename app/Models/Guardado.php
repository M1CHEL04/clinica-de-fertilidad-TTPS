<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Embrion;
use App\Models\Ovocito;

class Guardado extends Model
{

    protected $table = 'guardados';

    protected $fillable = [
        'id_tanque',
        'id_rack'
    ];   

    public function embriones()
    {
        return $this->hasMany(Embrion::class, 'guardado_id');
    }

    public function ovocitos()
    {
        return $this->hasMany(Ovocito::class, 'guardado_id');
    }
}
