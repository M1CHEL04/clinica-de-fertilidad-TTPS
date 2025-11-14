<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etapa;

class EtapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Etapa::insert([
            ['nombre' => 'Primera Consulta'],
            ['nombre' => 'Segunda consulta'],
            ['nombre' => 'Monitoreos'],
            ['nombre' => 'Puncion'],
            ['nombre' => 'Transferencia'],
            ['nombre' => 'Control de embarazo'],
            ['nombre' => 'Finalizado'],
        ]);
    }
}
