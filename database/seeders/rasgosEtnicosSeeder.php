<?php

namespace Database\Seeders;

use App\Models\RasgoEtnico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class rasgosEtnicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RasgoEtnico::insert([
            ['nombre' => 'Caucásico', 'value' => 'caucasico'],
            ['nombre' => 'Latino', 'value' => 'latino'],
            ['nombre' => 'Afrodescendiente', 'value' => 'afrodescendiente'],
            ['nombre' => 'Asiático', 'value' => 'asiatico'],
        ]);
    }
}
