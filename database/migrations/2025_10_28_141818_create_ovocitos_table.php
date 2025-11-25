<?php

use App\Models\Guardado;
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
        Schema::create('ovocitos', function (Blueprint $table) {
            $table->id();
            $table->string('identificador')->unique();
            $table->enum('calidad_morfologica', [1, 2, 3, 4, 5])->nullable();
            $table->foreignIdFor(Guardado::class)->nullable();
            $table->foreignIdFor(User::class, 'paciente_id');
            $table->boolean('utilizado')->default(false);
            $table->foreignIdFor(\App\Models\Puncion::class, 'puncion_id');
            $table->foreignIdFor(\App\Models\EstadoOvocito::class, 'estado_ovocito_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ovocitos');
    }
};
