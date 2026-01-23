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
        // Tabla Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // id de rol, convención Laravel
            $table->string('nombre_rol');
            $table->string('slug');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        // Tabla Usuarios
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // user id
            $table->string('nombre');
            $table->foreignId('role_id')->constrained('roles'); // FK roles.id
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // hash de la contraseña
            $table->rememberToken(); // "remember me"
            $table->timestamps();
        });

        // Tabla de sesiones de Laravel
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index()->constrained('usuarios'); // FK usuarios.id
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Tabla de tokens para reset de contraseña
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
