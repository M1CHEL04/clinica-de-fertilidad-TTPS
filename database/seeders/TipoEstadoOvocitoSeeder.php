<?php

namespace Database\Seeders;

use App\Models\TipoEstadoOvocito;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEstadoOvocitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoEstadoOvocito::insert([
            ['nombre' => 'Maduro'],
            ['nombre' => 'Inmaduro'],
            ['nombre' => 'Muy inmaduro'],
            ['nombre' => 'Descartado'],
        ]);
    }
}
