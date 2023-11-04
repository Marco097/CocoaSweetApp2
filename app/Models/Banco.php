<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

     //relacion de 1:N con datos
     public function datos()
     {
         return $this->hasMany(Dato::class);
     }
}
