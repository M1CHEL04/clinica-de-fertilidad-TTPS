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
        Schema::create('historial_ovocitos', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('ovocito_id');

    // estado previo / estado nuevo
    $table->unsignedBigInteger('estado_anterior_id')->nullable();
    $table->unsignedBigInteger('estado_nuevo_id')->nullable();

    // información adicional
    $table->string('accion')->nullable();
    $table->string('motivo_descarte')->nullable();
    $table->integer('tiempo_maduracion')->nullable();

    $table->unsignedBigInteger('operador_id')->nullable();

    $table->timestamp('fecha_cambio')->useCurrent();
    $table->timestamps();

    // Relaciones
    $table->foreign('ovocito_id')
          ->references('id')->on('ovocitos')
          ->cascadeOnDelete();

    $table->foreign('estado_anterior_id')
          ->references('id')->on('tipo_estado_ovocito') // ← AJUSTAR NOMBRE REAL
          ->nullOnDelete();

    $table->foreign('estado_nuevo_id')
          ->references('id')->on('tipo_estado_ovocito') // ← AJUSTAR NOMBRE REAL
          ->cascadeOnDelete();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::dropIfExists('historial_ovocitos');
    }
};
