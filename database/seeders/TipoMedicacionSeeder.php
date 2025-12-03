<?php

namespace Database\Seeders;

use App\Models\TipoMedicacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoMedicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Oral',
            'Inyectable',
            'Mixta',
        ];
        foreach ($tipos as $tipo) {
            TipoMedicacion::firstOrCreate(['nombre' => $tipo]);
        }
    }
}
