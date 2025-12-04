<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tratamiento;

class Monitoreo extends Model
{
    protected $fillable = [
        'tratamiento_id',
        'observacion',
        'user_id',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
