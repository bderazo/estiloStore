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
        Schema::create('articulo_talla', function (Blueprint $table) {
            $table->id();

            $table->foreignId('articulo_id')
                ->constrained('articulos')
                ->onDelete('cascade');

            $table->foreignId('talla_id')
                ->constrained('tallas')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['articulo_id', 'talla_id']);
            $table->index('articulo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_talla');
    }
};
