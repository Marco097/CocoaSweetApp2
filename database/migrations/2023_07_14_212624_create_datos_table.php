<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('datos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 70);
            $table->string('apellido', 70);
            $table->decimal('num_targe', 6, 2);   
            $table->date('fecha_ven');
            $table->char('codi_segu', 5)->unique();  
            $table->unsignedBigInteger('formas_pago_id');
            $table->foreign('formas_pago_id')->references('id')->on('formas_pagos');
            $table->unsignedBigInteger('banco_id');
            $table->foreign('banco_id')->references('id')->on('bancos');
            $table->unsignedBigInteger('pedido_id');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos');
    }
};
