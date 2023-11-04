<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoCobertura extends Model
{
    use HasFactory;

    protected $table = "producto_coberturas";

            //relaciones inversas
            public function cobertura()
            {
                return $this->belongsTo(Cobertura::class);
            }
            
      //relaciones inversas
    public function producto()
            {
              return $this->belongsTo(Producto::class);
            }
}
