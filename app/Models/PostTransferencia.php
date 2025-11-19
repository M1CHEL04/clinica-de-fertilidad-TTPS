<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTransferencia extends Model
{
    protected $table = 'post_transferencias';

    protected $fillable = [
        'tratamiento_id',
        'beta',
        'saco',
        'embarazo',
        'vivo',
        'fecha_nacimiento',
        'causa_no_nacido',
    ];

    public function tratamiento()
    {
        return $this->belongsTo(Tratamiento::class);
    }
}
