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
        Schema::create('articulo_sabor', function (Blueprint $table) {
            $table->id();

            $table->foreignId('articulo_id')
                ->constrained('articulos')
                ->onDelete('cascade');

            $table->foreignId('sabor_id')
                ->constrained('sabores')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['articulo_id', 'sabor_id']);
            $table->index('articulo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_sabor');
    }
};
