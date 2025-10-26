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
            ['nombre' => 'Embarazo con gametos propios'],
            ['nombre' => 'Embarazo con esperma donado'],
            ['nombre' => 'Metodo ROPA'],
        ]);
    }
}
