<?php

use App\Models\Embrion;
use App\Models\TipoFertilizacion;
use App\Models\Tratamiento;
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
        Schema::create('fertilizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TipoFertilizacion::class, 'tipo_fertilizacion_id');
            $table->foreignIdFor(User::class, 'operador_id');
            $table->foreignIdFor(User::class, 'paciente_id');
            $table->foreignIdFor(Tratamiento::class, 'tratamiento_id');
            $table->date('fecha_fertilizacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizaciones');
    }
};
