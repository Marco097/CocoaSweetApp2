<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

            //relaciones inversas
            public function catalogo()
            {
                return $this->belongsTo(Catalogo::class);
            }
            public function relleno()
            {
                return $this->belongsTo(Relleno::class);
            }
           //relacion de 1:N con producto sabor
           public function producto_sabores()
           {
               return $this->hasMany(ProductoSabor::class);
           }
   
               //relacion de 1:N con 
   
           public function detalle_pedidos()
           {
               return $this->hasMany(DetallePedido::class);
           }

              //relacion de 1:N con producto promocion
            public function producto_promociones()
                {
                    return $this->hasMany(ProductoPromocion::class);
                }

              //relacion de 1:N con producto sabor
           public function producto_coberturas()
           {
               return $this->hasMany(ProductoCobertura::class);
           }
}
