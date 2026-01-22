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
        Schema::create('historial_citas', function (Blueprint $table) {
            $table->id('id_historial');
            $table->dateTime('fecha');
            $table->foreignId('id_cita')->constrained('citas', 'id_cita');
            $table->foreignId('id_estado')->constrained('estados', 'id_estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_citas');
    }
};
