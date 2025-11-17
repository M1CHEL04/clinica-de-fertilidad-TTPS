<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TurnoController extends Controller
{

    public function showSolicitarTurnoForm()
    {
        // Obtener médicos activos
        $medicos = User::whereHas('rol', function ($query) {
            $query->where('nombre', 'medico');
        })->where('activo', 1)->get();

        return view('usuario.solicitarTurno', compact('medicos'));
    }

    public function storeTurno(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email',
            'sexo_biologico' => 'required|in:femenino,masculino',
            'dni' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'medico_id' => 'required|exists:users,id',
            'fecha_turno' => 'required|date|after:today',
            'hora_turno' => 'required|string'
        ]);

        // Aquí puedes agregar la lógica para guardar el turno
        // Por ejemplo, crear un modelo Turno y guardarlo en la base de datos

        return redirect()->back()->with('success', 'Turno solicitado correctamente. Nos contactaremos con usted para confirmar la cita.');
    }
}
