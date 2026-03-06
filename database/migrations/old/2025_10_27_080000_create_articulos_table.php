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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->comment('Nombre del artículo');
            $table->string('slug')->unique()->comment('Slug único para URLs');
            $table->text('descripcion')->nullable()->comment('Descripción del artículo');
            $table->text('especificaciones')->nullable()->comment('Especificaciones técnicas');
            $table->decimal('precio', 10, 2)->comment('Precio base del artículo');
            $table->string('sku')->nullable()->unique()->comment('SKU del artículo base');

            // Relaciones
            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('categorias')
                ->onDelete('cascade')
                ->comment('ID de la categoría');

            $table->foreignId('marca_id')
                ->nullable()
                ->constrained('marcas')
                ->onDelete('cascade')
                ->comment('ID de la marca');

            // Estados
            $table->boolean('activo')->default(true)->index()->comment('Artículo activo/inactivo');
            $table->boolean('destacado')->default(false)->index()->comment('Artículo destacado');

            // Soft deletes
            $table->softDeletes();

            $table->timestamps();

            // Índices
            $table->index('categoria_id');
            $table->index('marca_id');
            $table->index(['slug', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};
