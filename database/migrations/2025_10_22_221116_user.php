<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Tabla de usuarios (unificada)
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('mail')->unique();
            $table->string('password');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('cambio_password')->default(true);

            // 🔥 Nuevos campos unificados
            $table->string('dni')->unique()->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('ocupacion')->nullable();

            // 🏥 Campos de obra social
            $table->unsignedBigInteger('obra_social_id')->nullable();
            $table->string('numero_afiliado')->nullable();

            // 🔗 Relación al rol
            $table->foreignId('rol_id')->nullable()
                ->constrained('roles_trabajadores')
                ->nullOnDelete();

            $table->rememberToken();
            $table->timestamps();
        });

        // Tabla de sesiones (para Session Driver = database)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Tabla de tokens de reseteo de contraseña
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles_trabajadores');
    }
};
