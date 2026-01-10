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
        Schema::table('articulo_variantes', function (Blueprint $table) {
            $table->dropColumn('reserved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulo_variantes', function (Blueprint $table) {
            $table->integer('reserved')->default(0)->after('stock')->comment('Stock reservado (pre-venta)');
        });
    }
};
