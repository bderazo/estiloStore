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
        Schema::create('articulo_tono', function (Blueprint $table) {
            $table->id();

            $table->foreignId('articulo_id')
                ->constrained('articulos')
                ->onDelete('cascade');

            $table->foreignId('tono_id')
                ->constrained('tonos')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['articulo_id', 'tono_id']);
            $table->index('articulo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_tono');
    }
};
