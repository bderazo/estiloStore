<?php
// database/migrations/2024_01_01_000002_create_direcciones_entregas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('direcciones_entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pedido_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('ubicacion'); // Quevedo, Buena Fe, etc
            $table->foreignId('sector_id')->nullable()->constrained('sectores');
            $table->string('barrio')->nullable();
            $table->string('calle_principal');
            $table->string('calle_secundaria')->nullable();
            $table->string('color_casa')->nullable();
            $table->text('referencia')->nullable();
            $table->text('instrucciones_especiales')->nullable();
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();
            $table->boolean('es_principal')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsquedas rápidas
            $table->index(['user_id', 'es_principal']);
            $table->index(['ubicacion', 'sector_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('direcciones_entregas');
    }
};