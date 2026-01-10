<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $columna) {
            // Añadimos el campo imagen, puede ser nulo por si no todas tienen foto
            $columna->string('imagen')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $columna) {
            $columna->dropColumn('imagen');
        });
    }
};