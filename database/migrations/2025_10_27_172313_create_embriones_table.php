<?php

use App\Models\EstadoEmbrion;
use App\Models\Fertilizacion;
use App\Models\Guardado;
use App\Models\Ovocito;
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
            $table->boolean('criopreservado')->default(false);
            $table->foreignIdFor(Fertilizacion::class);
            $table->foreignIdFor(Ovocito::class);
            $table->enum('calidad_morfologica', [1, 2, 3, 4, 5]);
            $table->string('motivo_descarte')->nullable();
            $table->boolean('transferir')->nullable();
            $table->boolean('utilizado')->default(false);

            $table->string('semen_dni')->nullable();
            $table->boolean('semen_fresco')->nullable();
            $table->unsignedBigInteger('gameto_id')->nullable();
            $table->float('compatibilidad_gameto')->nullable();

            //hay que ver como se guarda el PGT
            $table->boolean('realizo_PGT');
            $table->boolean('pgt_positivo')->nullable();
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
