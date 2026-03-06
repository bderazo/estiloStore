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
        Schema::table('articulos', function (Blueprint $table) {
            $table->decimal('precio_oferta', 10, 2)->nullable()->after('precio');
            $table->integer('porcentaje_descuento')->nullable()->after('precio_oferta');
            $table->boolean('en_oferta')->default(false)->after('porcentaje_descuento');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulos', function (Blueprint $table) {
            //
        });
    }
};
