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
        Schema::create('antecedentes_fenotipo', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Tratamiento::class);
            $table->string('color_ojos');
            $table->string('color_pelo');
            $table->string('tipo_pelo');
            $table->string('altura');
            $table->string('complexion_corporal');
            $table->string('rasgos_etnicos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes_fenotipo');
    }
};
