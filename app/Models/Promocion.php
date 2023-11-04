<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = "promociones";

                //relacion de 1:N con productos
                public function producto_promociones()
                {
                    return $this->hasMany(Producto::class);
                }
}
