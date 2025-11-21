<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('antecedentes_ginecologicos', function (Blueprint $table) {
        $table->text('examen_fisico')->nullable()->change();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antecedentes_ginecologicos', function (Blueprint $table) {
            //
        });
    }
};
