<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RolTrabajador;

// este es el seeder para crear trabajadores de prueba. 

class trabajadoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los roles
        $roles = RolTrabajador::all();

        foreach ($roles as $rol) {
            User::create([
                'mail' => strtolower($rol->nombre) . '@demo.com',
                'password' => strtolower($rol->nombre) . '12345',
                'nombre' => ucfirst($rol->nombre),
                'apellido' => $rol->nombre,
                'telefono' => null,
                'dni' => null,
                'fecha_nacimiento' => null,
                'rol_id' => $rol->id,
            ]);
        }
    }
}
