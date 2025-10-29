<?php

use App\Models\EstadoEmbrion;
use App\Models\Guardado;
use App\Models\Semen;
use App\Models\Tratamiento;
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
        Schema::create('embriones', function (Blueprint $table) {
            $table->id();
            $table->string('identificador')->unique();
            $table->foreignIdFor(Guardado::class);
            $table->foreignIdFor(Tratamiento::class);
            $table->foreignIdFor(EstadoEmbrion::class);
            $table->enum('calidad_morfologica', [1, 2, 3, 4, 5]);

            $table->string('dni_donante')->nullable();

            //hay que ver como se guarda el PGT
            $table->string('urlPGT')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embriones');
    }
};
