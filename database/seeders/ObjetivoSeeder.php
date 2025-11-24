<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Objetivo;

class ObjetivoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Objetivo::insert([
            ['nombre' => 'Embarazo con gametos propios pareja hombre'],
            ['nombre' => 'Embarazo con esperma donado pareja hombre'],
            ['nombre' => 'Metodo ROPA pareja mujer'],
            ['nombre' => 'Conservar ovocitos'],
            ['nombre' => 'Embarazo sin pareja'],
        ]);
    }
}
