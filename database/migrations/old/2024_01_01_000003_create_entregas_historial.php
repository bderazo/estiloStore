<?php
// database/migrations/2024_01_01_000003_create_entregas_sectores_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('historial_entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrega_sector_id')->constrained('entregas_sectores');
            $table->foreignId('user_id')->constrained();
            $table->string('accion'); // asignado, en_ruta, entregado, reprogramado
            $table->text('comentario')->nullable();
            $table->json('datos_previos')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->timestamps();
        });
    }
};