<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoSabor extends Model
{
    use HasFactory;

    protected $table = "producto_sabores";

        //relaciones inversas
    public function sabor()
            {
                return $this->belongsTo(Sabor::class);
            }
            
      //relaciones inversas
    public function producto()
            {
              return $this->belongsTo(Producto::class);
            }
}
