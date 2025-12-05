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
        Schema::create('antecedentes_personales', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tratamiento::class);
            $table->boolean('fuma');
            $table->float('cuanto_fuma')->nullable(); 
            $table->boolean('alcohol');
            $table->integer('frecuencia_alcohol')->nullable(); 
            $table->text('bebida_alcohol')->nullable();
            $table->boolean('droga');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes_personales');
    }
};
