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
        Schema::create('articulo_plaza', function (Blueprint $table) {
            $table->id();

            $table->foreignId('articulo_id')
                ->constrained('articulos')
                ->onDelete('cascade');

            $table->foreignId('plaza_id')
                ->constrained('plazas')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['articulo_id', 'plaza_id']);
            $table->index('articulo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_plaza');
    }
};
