<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    // Mostrar formulario de registro
    public function show()
    {
        return view('usuario.register');
    }

    // Guardar usuario
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'           => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'mail'             => 'required|email|unique:usuarios,mail',
            'telefono'         => 'nullable|string|max:20',
            'password'         => 'required|string|min:8|confirmed',
            'fecha_nacimiento' => 'required|date',
            'dni'              => 'required|digits:8|unique:usuarios,dni',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Crear usuario con rol_id hardcodeado a 1 (paciente)
        $usuario = User::create(array_merge(
            $request->only([
                'nombre', 'apellido', 'mail', 'telefono', 'password', 'dni', 'fecha_nacimiento'
            ]),
            ['rol_id' => 1] // rol predeterminado
        ));

        return redirect()->route('login')->with('success', 'Registro exitoso. Ahora podes iniciar sesión.');
    }
}
