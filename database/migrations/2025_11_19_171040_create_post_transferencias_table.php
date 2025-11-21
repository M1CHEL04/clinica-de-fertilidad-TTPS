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
        Schema::create('post_transferencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tratamiento_id')
                  ->constrained('tratamientos')
                  ->onDelete('cascade');

  
            $table->boolean('beta')->nullable();
            $table->boolean('saco')->nullable();
            $table->boolean('embarazo')->nullable();
            $table->boolean('vivo')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->text('causa_no_nacido')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_transferencias');
    }
};
