<?php

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
        Schema::create('punciones', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->string('nro_quirofano');
            $table->foreignIdFor(User::class, 'operador_id');
            $table->foreignIdFor(User::class, 'paciente_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punciones');
    }
};
