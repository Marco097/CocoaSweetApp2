<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

             //relaciones inversas
             public function user()
             {
                 return $this->belongsTo(User::class);
             }
    
                  //relacion de 1:N 
    
         public function detalle_pedidos()
         {
             return $this->hasMany(DetallePedido::class);
         }
    
              //relacion de 1:N con datos
    
              public function datos()
              {
                  return $this->hasMany(Dato::class);
              }
}
