<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sabor extends Model
{
    use HasFactory;

    protected $table = "sabores";

        //relacion de 1:N con sabores

        public function producto_sobores()
        {
            return $this->hasMany(ProductoSabor::class);
        }
}
