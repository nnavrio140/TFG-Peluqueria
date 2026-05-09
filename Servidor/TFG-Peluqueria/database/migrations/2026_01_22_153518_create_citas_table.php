<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {

            $table->id();

            $table->date('fecha');

            $table->time('hora_inicio');

            $table->time('hora_fin');

            $table->foreignId('estado_id')
                ->constrained('estados');

            $table->foreignId('user_id')
                ->constrained('usuarios')
                ->onDelete('cascade');

            $table->foreignId('servicio_id')
                ->constrained('servicios');

            $table->foreignId('empleado_id')
                ->constrained('empleados');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};