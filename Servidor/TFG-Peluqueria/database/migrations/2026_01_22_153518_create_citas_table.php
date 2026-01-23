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
        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_cita');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->foreignId('id_estado')->constrained('estados', 'id_estado');
            $table->foreignId('user_id')->constrained('usuarios'); // apunta a usuarios.id
            $table->foreignId('id_servicio')->constrained('servicios', 'id_servicio');
            $table->foreignId('id_empleado')->constrained('empleados', 'id_empleado');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
