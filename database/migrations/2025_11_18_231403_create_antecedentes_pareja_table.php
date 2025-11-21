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
    Schema::create('antecedentes_pareja', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tratamiento_id')->constrained()->onDelete('cascade');

        $table->string('dni')->nullable();
        // Ginecológicos
        $table->string('ciclo_regular')->nullable();
        $table->string('duracion')->nullable();
        $table->string('caracteristicas_sangrado')->nullable();
        $table->integer('edad_menarca')->nullable();
        $table->integer('G')->nullable();
        $table->integer('P')->nullable();
        $table->integer('AB')->nullable();
        $table->integer('CT')->nullable();
        $table->text('examen_fisico')->nullable();

        // Genitales
        $table->text('descripcion_genital')->nullable();

        // Personales
        $table->boolean('fuma')->nullable();
        $table->string('cuanto_fuma')->nullable();
        $table->boolean('alcohol')->nullable();
        $table->string('frecuencia_alcohol')->nullable();
        $table->string('bebida_alcohol')->nullable();
        $table->boolean('droga')->nullable();
        $table->text('observaciones_personales')->nullable();
        $table->text('antecedentes_personales')->nullable();

        // Familiares
        $table->text('descripcion_familiar')->nullable();

        // Fenotipo
        $table->string('color_ojos')->nullable();
        $table->string('tipo_pelo')->nullable();
        $table->decimal('altura', 5, 2)->nullable();
        $table->string('complexion_corporal')->nullable();
        $table->string('rasgos_etnicos')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes_pareja');
    }
};
