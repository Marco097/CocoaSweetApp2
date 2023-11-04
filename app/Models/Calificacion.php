<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = "calificaciones";

    //relacion de 1:N con user

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
