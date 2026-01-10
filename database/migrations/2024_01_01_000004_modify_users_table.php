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
        Schema::table('users', function (Blueprint $table) {
            // Añadir numero_documento único y required
            $table->string('numero_documento')->unique()->after('id');
            $table->string('nombres')->after('numero_documento');
            $table->string('apellidos')->after('nombres');
            
            // Hacer email nullable
            $table->string('email')->nullable()->change();
            
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['numero_documento', 'nombres', 'apellidos']);
            $table->string('email')->nullable(false)->change();
        });
    }
};