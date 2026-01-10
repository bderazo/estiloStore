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
        Schema::create('carrusel', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('subtitulo')->nullable();
            $table->boolean('activar_subtitulo')->default(false);
            $table->boolean('activar_boton')->default(false);
            $table->string('url_boton')->nullable();
            $table->boolean('redirigir_misma_pagina')->default(false);
            $table->enum('posicion_contenido', ['Izquierda', 'Derecha'])->default('Izquierda');
            $table->string('imagen')->nullable();
            $table->boolean('estado')->default(true); // Campo para indicar si el carrusel está activo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrusel');
    }
};
