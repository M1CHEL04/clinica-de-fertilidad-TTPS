<?php

use App\Models\TipoEstadoOvocito;
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
        Schema::create('estados_ovocitos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TipoEstadoOvocito::class, 'tipo_estado_ovocito_id');
            $table->string('motivo_descarte')->nullable();
            $table->integer('tiempo_maduracion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_ovocitos');
    }
};
