<?php
// database/migrations/xxxx_xx_xx_create_testimonials_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cargo')->nullable();
            $table->text('testimonio');
            $table->string('imagen')->nullable();
            $table->tinyInteger('rating')->default(5); // 1-5
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Opcional: Configuración del carrusel
        Schema::create('testimonial_configs', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->default('Testimonios de Clientes');
            $table->string('subtitulo')->nullable();
            $table->boolean('autoplay')->default(true);
            $table->integer('autoplay_speed')->default(5000);
            $table->boolean('mostrar_controles')->default(true);
            $table->boolean('mostrar_indicadores')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('testimonial_configs');
    }
};