<?php

use App\Models\Embrion;
use App\Models\Guardado;
use App\Models\User;
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
        Schema::create('historiales_embrion', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Embrion::class, 'embrion_id');
            $table->foreignIdFor(User::class, 'operador_id'); // Usuario que realizó el cambio

            // Información de la acción realizada
            $table->string('accion'); // 'criopreservar', 'descartar', 'transferir', 'retirar_criopreservacion'
            $table->text('descripcion')->nullable(); // Descripción detallada del cambio

            // Campos específicos para diferentes acciones
            $table->string('motivo_descarte_anterior')->nullable();
            $table->string('motivo_descarte_nuevo')->nullable();
            $table->boolean('transferir_anterior')->nullable();
            $table->boolean('transferir_nuevo')->nullable();
            $table->foreignIdFor(Guardado::class, 'guardado_id_anterior')->nullable();
            $table->foreignIdFor(Guardado::class, 'guardado_id_nuevo')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiales_embrion');
    }
};
