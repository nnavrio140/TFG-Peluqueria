<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {

            $table->id();

            $table->string('especialidad');

            $table->decimal('salario', 10, 2)->nullable();

            $table->boolean('activo')->default(true);

            $table->foreignId('user_id')
                ->constrained('usuarios')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};