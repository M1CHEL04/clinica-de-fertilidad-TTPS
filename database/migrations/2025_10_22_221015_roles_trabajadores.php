<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles_trabajadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // ej: 'paciente', 'administrativo', 'medico'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles_trabajadores');
    }
};
