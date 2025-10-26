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
        Schema::create('tratamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\HistoriaClinica::class, 'historia_clinica_id');
            $table->foreignIdFor(\App\Models\Objetivo::class, 'objetivo_id');
            $table->foreignIdFor(\App\Models\EstadoTratamiento::class, 'estado_tratamiento_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tratamientos');
    }
};
