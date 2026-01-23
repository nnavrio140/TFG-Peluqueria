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
            $table->id();
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->foreignId('id_estado')->constrained('estados');
            $table->foreignId('user_id')->constrained('usuarios'); // apunta a usuarios.id
            $table->foreignId('id_servicio')->constrained('servicios');
            $table->foreignId('id_empleado')->constrained('empleados');
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
