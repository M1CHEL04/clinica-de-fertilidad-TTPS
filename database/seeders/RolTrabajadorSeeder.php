<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolTrabajadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'paciente',
            'medico',
            'operador',
            'admin',
            'jefe',
        ];

        foreach ($roles as $rol) {
            DB::table('roles_trabajadores')->insert([
                'nombre' => $rol,
            ]);
        }
    }
}
