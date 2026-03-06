<?php
// database/migrations/2024_01_01_000003_create_entregas_sectores_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('entregas_sectores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectores');
            $table->foreignId('direccion_entrega_id')->constrained('direcciones_entregas');
            $table->string('estado_entrega'); // pendiente, asignado, en_ruta, entregado, no_entregado
            $table->dateTime('fecha_programada');
            $table->dateTime('fecha_entregada')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('foto_entrega')->nullable();
            $table->string('firma')->nullable();
            $table->timestamps();

            $table->index(['sector_id', 'estado_entrega']);
            $table->index(['fecha_programada', 'estado_entrega']);
        });
    }
};