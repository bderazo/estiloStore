<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('seccion')->unique(); // Ej: 'promos', 'ofertas', 'nosotros'
            $table->string('titulo')->nullable();
            $table->string('subtitulo')->nullable();
            $table->string('imagen_ruta');
            $table->string('url_destino')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('banners');
    }
};