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
        Schema::create('articulo_variantes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('articulo_id')
                ->constrained('articulos')
                ->onDelete('cascade')
                ->comment('ID del artículo');

            // Atributos de variante (JSON: {talla_id: 1, color_id: 2, ...})
            $table->json('atributos')->comment('Atributos de la variante (talla, color, plaza, etc)');

            // SKU y precio para variante (opcional, si no existe usa del artículo base)
            $table->string('sku')->nullable()->comment('SKU específico de la variante');
            $table->decimal('precio', 10, 2)->nullable()->comment('Precio específico de variante (si es null usa del artículo)');

            // Stock y reservas
            $table->integer('stock')->default(0)->comment('Stock total de la variante');
            $table->integer('reserved')->default(0)->comment('Stock reservado (pre-venta)');

            // Estado
            $table->boolean('activo')->default(true)->comment('Variante activa/inactiva');

            $table->timestamps();

            // Índices
            $table->index('articulo_id');
            $table->index(['articulo_id', 'activo']);
            $table->unique('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulo_variantes');
    }
};
