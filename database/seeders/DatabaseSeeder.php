<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Solo cargar los roles

        $this->call(\Database\Seeders\RolTrabajadorSeeder::class);
        $this->call(\Database\Seeders\EstadoTratamientoSeeder::class);
        $this->call(\Database\Seeders\ObjetivoSeeder::class);
        $this->call(\Database\Seeders\trabajadoresSeeder::class);
        $this->call(\Database\Seeders\TipoMedicacionSeeder::class);
        $this->call(\Database\Seeders\TipoEstadoOvocitoSeeder::class);
        $this->call(\Database\Seeders\TipoFertilizacionSeeder::class);
        $this->call(\Database\Seeders\EtapaSeeder::class);
    }
}
