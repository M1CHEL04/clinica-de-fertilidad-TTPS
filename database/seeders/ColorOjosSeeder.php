<?php

namespace Database\Seeders;

use App\Models\ColorOjo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorOjosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ColorOjo::insert([
            ['nombre' => 'Azul', 'value' => 'azul'],
            ['nombre' => 'Verde', 'value' => 'verde'],
            ['nombre' => 'Marron claro', 'value' => 'marron_claro'],
            ['nombre' => 'Marron oscuro', 'value' => 'marron_oscuro'],
            ['nombre' => 'Gris', 'value' => 'gris'],
        ]);
    }
}
