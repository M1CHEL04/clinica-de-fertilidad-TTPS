<?php

namespace Database\Seeders;

use App\Models\ColorPelo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorPeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ColorPelo::insert([
            ['nombre' => 'Negro', 'value' => 'negro'],
            ['nombre' => 'Castaño claro', 'value' => 'castanio_claro'],
            ['nombre' => 'Castaño oscuro', 'value' => 'castanio_oscuro'],
            ['nombre' => 'Rubio', 'value' => 'rubio'],
            ['nombre' => 'Pelirrojo', 'value' => 'pelirrojo'],
        ]);
    }
}
