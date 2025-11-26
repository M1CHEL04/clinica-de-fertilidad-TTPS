<?php

namespace Database\Seeders;

use App\Models\TipoPelo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoPeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoPelo::insert([
            ['nombre' => 'Liso', 'value' => 'liso'],
            ['nombre' => 'Ondulado', 'value' => 'ondulado'],
            ['nombre' => 'Rizado', 'value' => 'rizado'],
        ]);
    }
}
