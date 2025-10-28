<?php

use App\Models\Embrion;
use App\Models\TipoFertilizacion;
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
            $table->enum('calidad', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']);
            $table->foreignIdFor(Embrion::class, 'embrion_id');
            $table->foreignIdFor(User::class, 'medico_id');
            $table->foreignIdFor(User::class, 'paciente_id');
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
