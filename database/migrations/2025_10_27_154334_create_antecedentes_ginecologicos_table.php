<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tratamiento;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('antecedentes_ginecologicos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tratamiento::class);
            $table->boolean('ciclo_regular');
            $table->integer('duracion'); // duración del ciclo
            $table->text('caracteristicas_sangrado');
            $table->integer('edad_menarca');
            $table->integer('G'); // gestas
            $table->integer('P'); // partos
            $table->integer('AB'); // abortos
            $table->integer('CT'); // cesáreas o controles totales
            $table->text('examen_fisico');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes_ginecologicos');
    }
};
