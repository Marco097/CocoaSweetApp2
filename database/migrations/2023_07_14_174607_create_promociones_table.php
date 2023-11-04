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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 70);
            $table->string('descripcion', 200);
            $table->string('tipo', 70);
            $table->decimal('descuento', 5, 2);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->decimal('precio_promocion', 5,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
