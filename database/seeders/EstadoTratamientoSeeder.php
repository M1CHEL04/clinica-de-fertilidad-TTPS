<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoTratamiento;

class EstadoTratamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EstadoTratamiento::insert([
            ['nombre' => 'Activo'],
            ['nombre' => 'Completado'],
            ['nombre' => 'Cancelado'],
        ]);
    }
}
