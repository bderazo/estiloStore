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
        Schema::create('nosotros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('cuerpo_principal')->nullable();
            $table->text('cuerpo_secundario')->nullable();
            $table->text('imagen_portada_url')->nullable();
            $table->text('imagen_cuerpo_1_url')->nullable();
            $table->text('imagen_cuerpo_2_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nosotros');
    }
};
