<?php

namespace Database\Seeders;

use App\Models\TipoFertilizacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoFertilizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoFertilizacion::insert([
            ['nombre' => 'FIV'],
            ['nombre' => 'ICSI'],
        ]);
    }
}
