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
            // Eliminar columnas SKU y precio de las variantes
            // Solo mantener atributos dinámicos, stock y estado
            $table->dropColumn(['sku', 'precio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulo_variantes', function (Blueprint $table) {
            // Restaurar las columnas eliminadas si se necesita rollback
            $table->string('sku')->nullable()->after('atributos');
            $table->decimal('precio', 10, 2)->nullable()->after('sku');
        });
    }
};
