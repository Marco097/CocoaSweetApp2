<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dato extends Model
{
    use HasFactory;

    //relaciones inversas
    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

     //relaciones inversas
     public function formas_pagos()
     {
         return $this->belongsTo(FormaPago::class);
     }
}
