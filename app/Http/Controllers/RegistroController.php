<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Paciente;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function show()
{
    return view('usuario.register'); // o el nombre que usaste
}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'mail' => 'required|email|unique:usuarios,mail',
        'telefono' => 'required|string|max:20',
        'password' => 'required|string|min:8|confirmed',
        'dni' => 'required|digits:8|unique:pacientes,dni',
        'fecha_nacimiento' => 'required|date',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $usuario = Usuario::create($request->only([
        'nombre', 'apellido', 'mail', 'telefono', 'password'
    ]));

    $usuario->paciente()->create([
        'dni' => $request->dni,
        'fecha_nacimiento' => $request->fecha_nacimiento,
    ]);

    return redirect()->route('login')->with('success', 'Registro exitoso.');
}
}
