<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Embrion;

class Guardado extends Model
{
    public function embriones()
    {
        return $this->hasMany(Embrion::class, 'guardado_id');
    }
}
