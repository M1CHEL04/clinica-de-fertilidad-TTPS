<?php

use App\Models\TipoMedicacion;
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
        Schema::create('protocolos_estimulacion', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tratamiento::class);
            $table->foreignIdFor(TipoMedicacion::class);
            $table->string('dosis');
            $table->string('tiempo');
            $table->string('droga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocolos_estimulacion');
    }
};
