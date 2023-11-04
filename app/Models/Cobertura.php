<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobertura extends Model
{
    use HasFactory;

    public function producto_coberturas()
    {
        return $this->hasMany(ProductoCobertura::class);
    }
}
