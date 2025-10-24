<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Solo cargar los roles
        
        $this->call(\Database\Seeders\RolTrabajadorSeeder::class);
    }
}
