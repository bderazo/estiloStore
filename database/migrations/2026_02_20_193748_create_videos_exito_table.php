<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos_exito', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('url_youtube'); // Guardamos la URL completa o el ID
            $table->string('youtube_id'); // Extraemos solo el ID para el embed
            $table->string('thumbnail_url')->nullable(); // Por si queremos thumbnail personalizado
            $table->string('nombre_persona');
            $table->string('cargo_persona')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla para configuración de la página
        Schema::create('videos_exito_config', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->default('Casos de Éxito');
            $table->string('subtitulo')->nullable()->default('Historias inspiradoras de nuestras clientas');
            $table->integer('videos_por_pagina')->default(8);
            $table->boolean('mostrar_cargar_mas')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos_exito');
        Schema::dropIfExists('videos_exito_config');
    }
};