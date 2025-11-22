<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('antecedentes_pareja', function (Blueprint $table) {
        $table->string('color_pelo')->nullable()->after('color_ojos');
    });
}

public function down()
{
    Schema::table('antecedentes_pareja', function (Blueprint $table) {
        $table->dropColumn('color_pelo');
    });
}

};
