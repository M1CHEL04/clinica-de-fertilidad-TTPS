<?php

namespace Database\Seeders;

use App\Models\Complexion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class complexionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Complexion::insert([
            ['nombre' => 'Delgada', 'value' => 'delgada'],
            ['nombre' => 'Media', 'value' => 'media'],
            ['nombre' => 'Robusta', 'value' => 'robusta'],
        ]);
    }
}
