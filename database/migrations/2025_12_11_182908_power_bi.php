<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | DIMENSIONES
        |--------------------------------------------------------------------------
        */

        // -------------------------------
        // Dim Pacientes
        // -------------------------------
        Schema::create('dim_pacientes', function (Blueprint $table) {
            $table->id('paciente_sk');          // surrogate key (BIGINT AUTO)
            $table->string('paciente_id');      // ID OLTP --> TEXTO (Â¡IMPORTANTE!)
            $table->string('dni')->nullable();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('obra_social_id')->nullable();
            $table->string('obra_social_sigla')->nullable();
            $table->boolean('activo')->nullable();
            $table->timestamps();

            $table->index('paciente_id');
        });

        // -------------------------------
        // Dim Empleados
        // -------------------------------
        Schema::create('dim_empleados', function (Blueprint $table) {
            $table->id('empleado_sk');
            $table->string('empleado_id');    // texto
            $table->string('nombre');
            $table->string('apellido');
            $table->string('telefono')->nullable();
            $table->string('rol_id')->nullable(); // text
            $table->timestamps();

            $table->index('empleado_id');
        });

        // -------------------------------
        // Dim Roles
        // -------------------------------
        Schema::create('dim_roles', function (Blueprint $table) {
            $table->id('rol_sk');
            $table->string('rol_id');
            $table->string('nombre');
        });

        // -------------------------------
        // Dim Objetivos
        // -------------------------------
        Schema::create('dim_objetivos', function (Blueprint $table) {
            $table->id('objetivo_sk');
            $table->string('objetivo_id');
            $table->string('nombre');
        });

        // -------------------------------
        // Dim Estado Tratamiento
        // -------------------------------
        Schema::create('dim_estados_tratamiento', function (Blueprint $table) {
            $table->id('estado_tratamiento_sk');
            $table->string('estado_tratamiento_id');
            $table->string('nombre');
        });

        // -------------------------------
        // Dim Etapas
        // -------------------------------
        Schema::create('dim_etapas', function (Blueprint $table) {
            $table->id('etapa_sk');
            $table->string('etapa_id');
            $table->string('nombre');
        });

        // -------------------------------
        // Dim Tipo Estudio
        // -------------------------------
        Schema::create('dim_tipos_estudio', function (Blueprint $table) {
            $table->id('tipo_estudio_sk');
            $table->string('tipo_estudio');
        });

        // -------------------------------
        // Dim Tipo Fertilizacion
        // -------------------------------
        Schema::create('dim_tipos_fertilizacion', function (Blueprint $table) {
            $table->id('tipo_fertilizacion_sk');
            $table->string('tipo_fertilizacion_id');
            $table->string('nombre');
        });

        // -------------------------------
        // Dim Estado Ovocito
        // -------------------------------
        Schema::create('dim_estados_ovocito', function (Blueprint $table) {
            $table->id('estado_ovocito_sk');
            $table->string('estado_ovocito_id')->nullable();
            $table->string('nombre_tipo')->nullable();
            $table->string('motivo_descarte')->nullable();
            $table->integer('tiempo_maduracion')->nullable();
        });

        // -------------------------------
        // Dim Estado Embrion
        // -------------------------------
        Schema::create('dim_estados_embrion', function (Blueprint $table) {
            $table->id('estado_embrion_sk');
            $table->string('estado_embrion_id')->nullable();
            $table->string('nombre');
        });

        // -------------------------------
        // Dim Tiempo (Calendario)
        // -------------------------------
        Schema::create('dim_tiempo', function (Blueprint $table) {
            $table->id('tiempo_sk');
            $table->date('fecha')->unique();
            $table->integer('anio');
            $table->integer('mes');
            $table->integer('dia');
            $table->string('mes_nombre');
            $table->integer('semana_anio');
            $table->integer('trimestre');

            $table->index('anio');
            $table->index('mes');
        });


        /*
        |--------------------------------------------------------------------------
        | FACT TABLES
        |--------------------------------------------------------------------------
        */

        // -------------------------------
        // Fact Tratamientos
        // -------------------------------
        Schema::create('fact_tratamientos', function (Blueprint $table) {
            $table->id('tratamiento_sk');

            $table->string('tratamiento_id');    // texto

            // surrogate keys
            $table->unsignedBigInteger('paciente_sk');
            $table->unsignedBigInteger('empleado_sk');
            $table->unsignedBigInteger('objetivo_sk')->nullable();
            $table->unsignedBigInteger('estado_tratamiento_sk');
            $table->unsignedBigInteger('etapa_sk')->nullable();

            $table->string('pago_id')->nullable();  // texto

            $table->unsignedBigInteger('fecha_inicio_sk')->nullable();
            $table->unsignedBigInteger('fecha_fin_sk')->nullable();
            $table->unsignedBigInteger('fecha_registro_sk');

            $table->timestamps();
        });

        // -------------------------------
        // Fact Estudios
        // -------------------------------
        Schema::create('fact_estudios', function (Blueprint $table) {
            $table->id('estudio_sk');
            $table->string('estudio_id');
            $table->unsignedBigInteger('tratamiento_sk');
            $table->unsignedBigInteger('tipo_estudio_sk')->nullable();
            $table->string('nombre');
            $table->string('resultado')->nullable();
            $table->unsignedBigInteger('fecha_sk');
            $table->timestamps();
        });

        // -------------------------------
        // Fact Monitoreos
        // -------------------------------
        Schema::create('fact_monitoreos', function (Blueprint $table) {
            $table->id('monitoreo_sk');
            $table->string('monitoreo_id');
            $table->unsignedBigInteger('tratamiento_sk');
            $table->unsignedBigInteger('empleado_sk');
            $table->string('observacion')->nullable();
            $table->unsignedBigInteger('fecha_sk');
            $table->timestamps();
        });

        // -------------------------------
        // Fact Fertilizaciones
        // -------------------------------
        Schema::create('fact_fertilizaciones', function (Blueprint $table) {
            $table->id('fertilizacion_sk');
            $table->string('fertilizacion_id');
            $table->unsignedBigInteger('tratamiento_sk');
            $table->unsignedBigInteger('empleado_sk');
            $table->unsignedBigInteger('paciente_sk');
            $table->unsignedBigInteger('tipo_fertilizacion_sk');
            $table->unsignedBigInteger('fecha_sk');
            $table->timestamps();
        });

        // -------------------------------
        // Fact Punciones
        // -------------------------------
        Schema::create('fact_punciones', function (Blueprint $table) {
            $table->id('puncion_sk');
            $table->string('puncion_id');
            $table->unsignedBigInteger('paciente_sk');
            $table->unsignedBigInteger('empleado_sk');
            $table->unsignedBigInteger('fecha_sk');
            $table->string('nro_quirofano')->nullable();
            $table->timestamps();
        });

        // -------------------------------
        // Fact Ovocitos
        // -------------------------------
        Schema::create('fact_ovocitos', function (Blueprint $table) {
            $table->id('ovocito_sk');
            $table->string('ovocito_id');
            $table->unsignedBigInteger('paciente_sk');
            $table->unsignedBigInteger('puncion_sk');
            $table->unsignedBigInteger('estado_ovocito_sk')->nullable();
            $table->integer('calidad_morfologica')->nullable();
            $table->boolean('utilizado')->nullable();
            $table->unsignedBigInteger('fecha_sk');
            $table->timestamps();
        });

        // -------------------------------
        // Fact Embriones
        // -------------------------------
        Schema::create('fact_embriones', function (Blueprint $table) {
            $table->id('embrion_sk');
            $table->string('embrion_id');
            $table->unsignedBigInteger('ovocito_sk')->nullable();
            $table->unsignedBigInteger('fertilizacion_sk')->nullable();
            $table->unsignedBigInteger('estado_embrion_sk')->nullable();
            $table->integer('calidad_morfologica')->nullable();
            $table->boolean('criopreservado')->nullable();
            $table->boolean('utilizado')->nullable();
            $table->string('motivo_descarte')->nullable();
            $table->boolean('realizo_pgt')->nullable();
            $table->boolean('pgt_positivo')->nullable();
            $table->unsignedBigInteger('fecha_sk');
            $table->timestamps();
        });

        // -------------------------------
        // Fact Post Transferencias
        // -------------------------------
        Schema::create('fact_post_transferencias', function (Blueprint $table) {
            $table->id('post_transferencia_sk');
            $table->string('post_transferencia_id');
            $table->unsignedBigInteger('tratamiento_sk');
            $table->unsignedBigInteger('paciente_sk');
            $table->boolean('beta')->nullable();
            $table->boolean('saco')->nullable();
            $table->boolean('embarazo')->nullable();
            $table->boolean('vivo')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('causa_no_nacido')->nullable();
            $table->unsignedBigInteger('fecha_sk');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fact_post_transferencias');
        Schema::dropIfExists('fact_embriones');
        Schema::dropIfExists('fact_ovocitos');
        Schema::dropIfExists('fact_punciones');
        Schema::dropIfExists('fact_fertilizaciones');
        Schema::dropIfExists('fact_monitoreos');
        Schema::dropIfExists('fact_estudios');
        Schema::dropIfExists('fact_tratamientos');

        Schema::dropIfExists('dim_tiempo');
        Schema::dropIfExists('dim_estados_embrion');
        Schema::dropIfExists('dim_estados_ovocito');
        Schema::dropIfExists('dim_tipos_fertilizacion');
        Schema::dropIfExists('dim_tipos_estudio');
        Schema::dropIfExists('dim_etapas');
        Schema::dropIfExists('dim_estados_tratamiento');
        Schema::dropIfExists('dim_objetivos');
        Schema::dropIfExists('dim_roles');
        Schema::dropIfExists('dim_empleados');
        Schema::dropIfExists('dim_pacientes');
    }
};