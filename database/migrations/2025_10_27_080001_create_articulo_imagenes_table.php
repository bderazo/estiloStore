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
        Schema::create('articulo_imagenes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('articulo_id')
                ->constrained('articulos')
                ->onDelete('cascade')
                ->comment('ID del artículo');

            $table->string('ruta')->comment('Ruta del archivo de imagen (UUID)');
            $table->integer('orden')->default(0)->comment('Orden de presentación de la imagen');
            $table->json('metadatos')->nullable()->comment('Metadatos: width, height, size, alt text, etc');

            $table->timestamps();

            // Índices
            $table->index('articulo_id');
            $table->index('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_imagenes');
    }
};
