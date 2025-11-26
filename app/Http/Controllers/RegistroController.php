<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{

    // Guardar usuario
    public function store(Request $request)
    {
        // Validaciones base
        $rules = [
            'nombre'           => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'mail'             => 'required|email|unique:usuarios,mail',
            'telefono'         => 'required|string|max:20',
            'password'         => 'required|string|min:8|confirmed',
            'fecha_nacimiento' => 'required|date',
            'dni'              => 'required|digits:8|unique:usuarios,dni',
            'ocupacion'        => 'required|string|max:255',
            'posee_obra_social' => 'required|in:si,no',
        ];

        // Validaciones condicionales para obra social
        if ($request->posee_obra_social === 'si') {
            $rules['obra_social'] = 'required|integer';
            $rules['numero_afiliado'] = 'required|string|max:50';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Preparar datos para crear usuario
        $userData = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'mail' => $request->mail,
            'telefono' => $request->telefono,
            'password' => $request->password,
            'dni' => $request->dni,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'ocupacion' => $request->ocupacion,
            'obra_social_id' => $request->posee_obra_social === 'si' ? $request->obra_social : null,
            'numero_afiliado' => $request->posee_obra_social === 'si' ? $request->numero_afiliado : null,
            'rol_id' => 1 // rol predeterminado (paciente)
        ];

        // Crear usuario
        $usuario = User::create($userData);

        return redirect()->route('login')->with('success', 'Registro exitoso. Ahora podes iniciar sesión.');
    }
}
