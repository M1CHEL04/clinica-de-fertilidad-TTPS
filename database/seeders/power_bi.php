<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class power_bi extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Desactivar checks FK para truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * DIM TIEMPO
         */
        DB::table('dim_tiempo')->truncate();

        $start = new \DateTime('2023-01-01');
        $end   = new \DateTime('2027-12-31');

        while ($start <= $end) {
            DB::table('dim_tiempo')->insert([
                // tiempo_sk se autoincrementa (id definido en migración)
                'fecha'        => $start->format('Y-m-d'),
                'anio'         => (int)$start->format('Y'),
                'mes'          => (int)$start->format('m'),
                'dia'          => (int)$start->format('d'),
                'mes_nombre'   => $start->format('F'),
                'semana_anio'  => (int)$start->format('W'),
                'trimestre'    => (int)ceil($start->format('m') / 3),
            ]);
            $start->modify('+1 day');
        }

        // mapa fecha => tiempo_sk
        $tiempoSK = DB::table('dim_tiempo')->pluck('tiempo_sk', 'fecha')->toArray();


        /*
         * DIM ROLES (HARDCODEADOS)
         * migración: dim_roles (rol_sk, rol_id, nombre)
         */
        DB::table('dim_roles')->truncate();
        $roles = [
            1 => 'paciente',
            2 => 'medico',
            3 => 'operador',
            4 => 'admin',
            5 => 'jefe',
        ];
        foreach ($roles as $id => $nombre) {
            DB::table('dim_roles')->insert([
                'rol_sk' => $id,
                'rol_id' => $id,
                'nombre' => $nombre,
            ]);
        }


        /*
         * DIM OBJETIVOS (HARDCODEADOS)
         * migración: dim_objetivos (objetivo_sk, objetivo_id, nombre)
         */
        DB::table('dim_objetivos')->truncate();
        $objetivos = [
            1 => 'Embarazo con gametos propios (Pareja masculina)',
            2 => 'Embarazo con esperma donado (Pareja masculina)',
            3 => 'Metodo ROPA',
            4 => 'Conservar ovocitos',
            5 => 'Embarazo sin pareja',
        ];
        foreach ($objetivos as $id => $nombre) {
            DB::table('dim_objetivos')->insert([
                'objetivo_sk' => $id,
                'objetivo_id' => $id,
                'nombre'      => $nombre,
            ]);
        }


        /*
         * DIM ETAPAS (HARDCODEADAS)
         * migración: dim_etapas (etapa_sk, etapa_id, nombre)
         */
        DB::table('dim_etapas')->truncate();
        $etapas = [
            1 => 'Primera Consulta',
            2 => 'Segunda consulta',
            3 => 'Monitoreos',
            4 => 'Puncion',
            5 => 'Fertilizacion',
            6 => 'Transferencia',
            7 => 'Control de embarazo',
            8 => 'Finalizado',
        ];
        foreach ($etapas as $id => $nombre) {
            DB::table('dim_etapas')->insert([
                'etapa_sk' => $id,
                'etapa_id' => $id,
                'nombre'   => $nombre,
            ]);
        }


        /*
         * DIM ESTADOS TRATAMIENTO (HARDCODEADOS)
         * migración: dim_estados_tratamiento (estado_tratamiento_sk, estado_tratamiento_id, nombre)
         */
        DB::table('dim_estados_tratamiento')->truncate();
        $estadosTrat = [
            1 => 'Activo',
            2 => 'Completado',
            3 => 'Cancelado',
        ];
        foreach ($estadosTrat as $id => $nombre) {
            DB::table('dim_estados_tratamiento')->insert([
                'estado_tratamiento_sk' => $id,
                'estado_tratamiento_id' => $id,
                'nombre'                => $nombre,
            ]);
        }


        /*
         * DIM TIPOS FERTILIZACION (HARDCODEADOS)
         * migración: dim_tipos_fertilizacion (tipo_fertilizacion_sk, tipo_fertilizacion_id, nombre)
         */
        DB::table('dim_tipos_fertilizacion')->truncate();
        $tiposFert = [
            1 => 'FIV',
            2 => 'ICSI',
        ];
        foreach ($tiposFert as $id => $nombre) {
            DB::table('dim_tipos_fertilizacion')->insert([
                'tipo_fertilizacion_sk' => $id,
                'tipo_fertilizacion_id' => $id,
                'nombre'                => $nombre,
            ]);
        }


        /*
         * DIM ESTADOS OVOCITO (HARDCODEADOS)
         * migración: dim_estados_ovocito (estado_ovocito_sk, estado_ovocito_id, nombre_tipo, ...)
         */
        DB::table('dim_estados_ovocito')->truncate();
        $estadosOvo = [
            1 => 'Maduro',
            2 => 'Inmaduro',
            3 => 'Muy inmaduro',
            4 => 'Descartado',
        ];
        foreach ($estadosOvo as $id => $nombre) {
            DB::table('dim_estados_ovocito')->insert([
                'estado_ovocito_sk'   => $id,
                'estado_ovocito_id'   => $id,
                'nombre_tipo'         => $nombre,
                'motivo_descarte'     => null,
                'tiempo_maduracion'   => null,
            ]);
        }


        /*
         * DIM ESTADOS EMBRION (HARDCODEADOS) - por si los querés
         * migración: dim_estados_embrion (estado_embrion_sk, estado_embrion_id?, nombre)
         */
        DB::table('dim_estados_embrion')->truncate();
        $estadosEmb = [
            1 => 'Buen estado',
            2 => 'Regular',
            3 => 'Malo',
        ];
        foreach ($estadosEmb as $id => $nombre) {
            DB::table('dim_estados_embrion')->insert([
                'estado_embrion_sk' => $id,
                'estado_embrion_id' => $id,
                'nombre'            => $nombre,
            ]);
        }


        /*
         * DIM PACIENTES (HARDCODEADOS) - migración: dim_pacientes (paciente_sk, paciente_id, ...)
         * Vamos a popular paciente_sk y paciente_id iguales (1..50)
         */
        DB::table('dim_pacientes')->truncate();
        for ($i = 1; $i <= 50; $i++) {
            DB::table('dim_pacientes')->insert([
                'paciente_sk' => $i,
                'paciente_id' => $i,
                'dni'         => (string) $faker->unique()->numberBetween(20000000, 50000000),
                'nombre'      => $faker->firstName,
                'apellido'    => $faker->lastName,
                'telefono'    => $faker->phoneNumber,
                'fecha_nacimiento' => $faker->date('Y-m-d', '-20 years'),
                'ocupacion'   => $faker->jobTitle,
                'obra_social_id' => rand(1,5),
                'obra_social_sigla' => $faker->randomElement(['OSDE','IOMA','PAMI','Galeno','Swiss']),
                'activo'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }


        /*
         * DIM EMPLEADOS (HARDCODEADOS) - migración: dim_empleados (empleado_sk, empleado_id, ...)
         */
        DB::table('dim_empleados')->truncate();
        for ($i = 1; $i <= 20; $i++) {
            DB::table('dim_empleados')->insert([
                'empleado_sk' => $i,
                'empleado_id' => $i,
                'nombre'      => $faker->firstName,
                'apellido'    => $faker->lastName,
                'telefono'    => $faker->phoneNumber,
                'rol_id'      => rand(2,5), // evitar rol paciente
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }


        /*
         * FACT TRATAMIENTOS (HARDCODEADOS)
         * migración: fact_tratamientos (tratamiento_sk, tratamiento_id, paciente_sk, empleado_sk, objetivo_sk, estado_tratamiento_sk, etapa_sk, pago_id, fecha_inicio_sk, fecha_fin_sk, fecha_registro_sk)
         */
        DB::table('fact_tratamientos')->truncate();

        for ($i = 1; $i <= 200; $i++) {
            // generar fechas dentro de dim_tiempo
            $fechaInicio = $faker->dateTimeBetween('2023-01-01', '2027-01-01');
            $fechaFin    = (clone $fechaInicio)->modify('+'.rand(10,120).' days');

            $inicioKey = $fechaInicio->format('Y-m-d');
            $finKey    = $fechaFin->format('Y-m-d');
            $registroKey = date('Y-m-d');

            if (!isset($tiempoSK[$inicioKey]) || !isset($tiempoSK[$finKey]) || !isset($tiempoSK[$registroKey])) {
                // si no existe la fecha en dim_tiempo, salteamos
                continue;
            }

            DB::table('fact_tratamientos')->insert([
                'tratamiento_sk'        => $i,
                'tratamiento_id'        => $i,
                'paciente_sk'           => rand(1, 50),
                'empleado_sk'           => rand(1, 20),
                'objetivo_sk'           => rand(1, 5),
                'estado_tratamiento_sk' => rand(1, 3),
                'etapa_sk'              => rand(1, 8),
                'pago_id'               => rand(1000,99999),
                'fecha_inicio_sk'       => $tiempoSK[$inicioKey],
                'fecha_fin_sk'          => $tiempoSK[$finKey],
                'fecha_registro_sk'     => $tiempoSK[$registroKey],
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }


        /*
         * FACT ESTUDIOS
         * migración: fact_estudios (estudio_sk, estudio_id, tratamiento_sk, tipo_estudio_sk, nombre, resultado, fecha_sk)
         * NOTA: pediste no crear dim_tipos_estudio ahora => usaremos 'nombre' y 'resultado' directo
         */
        DB::table('fact_estudios')->truncate();
        for ($i = 1; $i <= 300; $i++) {
            $fecha = $faker->dateTimeBetween('2023-01-01', '2027-01-01')->format('Y-m-d');
            if (!isset($tiempoSK[$fecha])) continue;

            DB::table('fact_estudios')->insert([
                'estudio_sk'    => $i,
                'estudio_id'    => $i,
                'tratamiento_sk'=> rand(1, 200),
                'tipo_estudio_sk'=> null,
                'nombre'        => $faker->randomElement([
                    'E2','FSH','LH','Progesterona','Ecografía'
                ]),
                'resultado'     => (string) $faker->randomElement([
                    'Normal','Leve variación','Requiere control','Crítico'
                ]),
                'fecha_sk'      => $tiempoSK[$fecha],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }


        /*
         * FACT PUNCIONES
         * migración: fact_punciones (puncion_sk, puncion_id, paciente_sk, empleado_sk, fecha_sk, nro_quirofano)
         */
        DB::table('fact_punciones')->truncate();
        for ($i = 1; $i <= 150; $i++) {
            $fecha = $faker->dateTimeBetween('2023-01-01', '2027-01-01')->format('Y-m-d');
            if (!isset($tiempoSK[$fecha])) continue;

            DB::table('fact_punciones')->insert([
                'puncion_sk'    => $i,
                'puncion_id'    => $i,
                'paciente_sk'   => rand(1,50),
                'empleado_sk'   => rand(1,20),
                'fecha_sk'      => $tiempoSK[$fecha],
                'nro_quirofano' => (string) rand(1,6),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }


        /*
         * FACT OVOCITOS
         * migración: fact_ovocitos (ovocito_sk, ovocito_id, paciente_sk, puncion_sk, estado_ovocito_sk, calidad_morfologica, utilizado, fecha_sk)
         */
        DB::table('fact_ovocitos')->truncate();
        for ($i = 1; $i <= 800; $i++) {
            $fecha = $faker->dateTimeBetween('2023-01-01', '2027-01-01')->format('Y-m-d');
            if (!isset($tiempoSK[$fecha])) continue;

            DB::table('fact_ovocitos')->insert([
                'ovocito_sk'           => $i,
                'ovocito_id'           => $i,
                'paciente_sk'          => rand(1,50),
                'puncion_sk'           => rand(1,150),
                'estado_ovocito_sk'    => rand(1,4),
                'calidad_morfologica'  => rand(1,5),
                'utilizado'            => (int) $faker->boolean(30),
                'fecha_sk'             => $tiempoSK[$fecha],
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }


        /*
         * FACT FERTILIZACIONES
         * migración: fact_fertilizaciones (fertilizacion_sk, fertilizacion_id, tratamiento_sk, empleado_sk, paciente_sk, tipo_fertilizacion_sk, fecha_sk)
         */
        DB::table('fact_fertilizaciones')->truncate();
        for ($i = 1; $i <= 400; $i++) {
            $fecha = $faker->dateTimeBetween('2023-01-01', '2027-01-01')->format('Y-m-d');
            if (!isset($tiempoSK[$fecha])) continue;

            DB::table('fact_fertilizaciones')->insert([
                'fertilizacion_sk'         => $i,
                'fertilizacion_id'         => $i,
                'tratamiento_sk'           => rand(1,200),
                'empleado_sk'              => rand(1,20),
                'paciente_sk'              => rand(1,50),
                'tipo_fertilizacion_sk'    => rand(1,2),
                'fecha_sk'                 => $tiempoSK[$fecha],
                'created_at'               => now(),
                'updated_at'               => now(),
            ]);
        }


        /*
         * FACT EMBRIONES
         * migración: fact_embriones (embrion_sk, embrion_id, ovocito_sk, fertilizacion_sk, estado_embrion_sk, calidad_morfologica, criopreservado, utilizado, motivo_descarte, realizo_pgt, pgt_positivo, fecha_sk)
         */
        DB::table('fact_embriones')->truncate();
        for ($i = 1; $i <= 600; $i++) {
            $fecha = $faker->dateTimeBetween('2023-01-01', '2027-01-01')->format('Y-m-d');
            if (!isset($tiempoSK[$fecha])) continue;

            DB::table('fact_embriones')->insert([
                'embrion_sk'            => $i,
                'embrion_id'            => $i,
                'ovocito_sk'            => rand(1,800),
                'fertilizacion_sk'      => rand(1,400),
                'estado_embrion_sk'     => rand(1,3),
                'calidad_morfologica'   => rand(1,5),
                'criopreservado'        => (int) $faker->boolean(20),
                'utilizado'             => (int) $faker->boolean(30),
                'motivo_descarte'       => $faker->boolean(20) ? $faker->sentence(3) : null,
                'realizo_pgt'           => (int) $faker->boolean(25),
                'pgt_positivo'          => $faker->boolean(15) ? 1 : 0,
                'fecha_sk'              => $tiempoSK[$fecha],
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }


        /*
         * FACT MONITOREOS
         * migración: fact_monitoreos (monitoreo_sk, monitoreo_id, tratamiento_sk, empleado_sk, observacion, fecha_sk)
         */
        DB::table('fact_monitoreos')->truncate();
        for ($i = 1; $i <= 400; $i++) {
            $fecha = $faker->dateTimeBetween('2023-01-01', '2027-01-01')->format('Y-m-d');
            if (!isset($tiempoSK[$fecha])) continue;

            DB::table('fact_monitoreos')->insert([
                'monitoreo_sk'     => $i,
                'monitoreo_id'     => $i,
                'tratamiento_sk'   => rand(1,200),
                'empleado_sk'      => rand(1,20),
                'observacion'      => $faker->sentence(6),
                'fecha_sk'         => $tiempoSK[$fecha],
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }


        /*
         * FACT POST TRANSFERENCIAS
         * migración: fact_post_transferencias (post_transferencia_sk, post_transferencia_id, tratamiento_sk, paciente_sk, beta, saco, embarazo, vivo, fecha_nacimiento, causa_no_nacido, fecha_sk)
         */
        DB::table('fact_post_transferencias')->truncate();
        for ($i = 1; $i <= 150; $i++) {
            $fechaObj = $faker->dateTimeBetween('2023-01-01', '2027-01-01');
            $fecha = $fechaObj->format('Y-m-d');
            if (!isset($tiempoSK[$fecha])) continue;

            // generar datos coherentes: si hay embarazo, puede haber saco y vivo
            $embarazo = $faker->boolean(60);
            $saco = $embarazo ? $faker->boolean(85) : false;
            $vivo = $saco ? $faker->boolean(90) : false;
            
            // fecha de nacimiento entre la transferencia y 30 días después
            $fechaNacimiento = null;
            if ($vivo) {
                $fechaInicio = clone $fechaObj;
                $fechaFin = clone $fechaObj;
                $fechaFin->modify('+30 days');
                $fechaNacimiento = $faker->dateTimeBetween($fechaInicio, $fechaFin)->format('Y-m-d');
            }
            
            $causaNoNacido = (!$vivo && $embarazo) ? $faker->randomElement([
                'Aborto espontáneo',
                'Complicaciones gestacionales',
                'Anomalía fetal',
                'Parto prematuro',
            ]) : null;

            DB::table('fact_post_transferencias')->insert([
                'post_transferencia_sk' => $i,
                'post_transferencia_id' => $i,
                'tratamiento_sk'        => rand(1, 200),
                'paciente_sk'           => rand(1, 50),
                'beta'                  => $faker->boolean(50),
                'saco'                  => $saco,
                'embarazo'              => $embarazo,
                'vivo'                  => $vivo,
                'fecha_nacimiento'      => $fechaNacimiento,
                'causa_no_nacido'       => $causaNoNacido,
                'fecha_sk'              => $tiempoSK[$fecha],
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }


        // Reactivar FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "✔ PowerBiMegaSeeder HARDCODEADO ejecutado correctamente.\n";
    }
}