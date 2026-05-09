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
        /*
        |--------------------------------------------------------------------------
        | TABLA ROLES
        |--------------------------------------------------------------------------
        */
        Schema::create('roles', function (Blueprint $table) {

            $table->id();

            $table->string('nombre_rol');

            // IMPORTANTE:
            // Se usará muchísimo para permisos y consultas
            // Ej: admin, empleado, cliente
            $table->string('slug')->unique();

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | TABLA USUARIOS
        |--------------------------------------------------------------------------
        */
        Schema::create('usuarios', function (Blueprint $table) {

            $table->id();

            $table->string('nombre');

            // IMPORTANTE:
            // Restrict evita borrar un rol que esté siendo usado
            $table->foreignId('role_id')
                ->constrained('roles')
                ->onDelete('restrict');

            $table->string('email')->unique();

            $table->timestamp('email_verified_at')->nullable();

            // Laravel espera este nombre
            $table->string('password');

            // MUY recomendable para reservas/contacto
            $table->string('telefono')->nullable();

            // Para activar/desactivar usuarios
            $table->boolean('activo')->default(true);

            $table->rememberToken();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | TABLA SESIONES
        |--------------------------------------------------------------------------
        */
        Schema::create('sessions', function (Blueprint $table) {

            $table->string('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('usuarios')
                ->onDelete('cascade');

            $table->string('ip_address', 45)->nullable();

            $table->text('user_agent')->nullable();

            $table->longText('payload');

            $table->integer('last_activity')->index();
        });

        /*
        |--------------------------------------------------------------------------
        | RESET PASSWORD TOKENS
        |--------------------------------------------------------------------------
        */
        Schema::create('password_reset_tokens', function (Blueprint $table) {

            $table->string('email')->primary();

            $table->string('token');

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');

        Schema::dropIfExists('sessions');

        Schema::dropIfExists('usuarios');

        Schema::dropIfExists('roles');
    }
};