<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Puncion;
use App\Models\Ovocito;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OperadorController extends Controller
{
 public function Pacientes()
{
    $rol_id = session('rol');
    $pacientes = DB::table('usuarios')
        ->leftJoin('historias_clinica', 'usuarios.id', '=', 'historias_clinica.paciente_id')
        ->leftJoin('tratamientos', 'historias_clinica.id', '=', 'tratamientos.historia_clinica_id')
        ->leftJoin('estados_tratamiento', 'tratamientos.estado_tratamiento_id', '=', 'estados_tratamiento.id')
        ->select(
            'usuarios.id as paciente_id',
            'usuarios.nombre',
            'usuarios.apellido',
            'usuarios.mail',
            'usuarios.dni',
            'usuarios.fecha_nacimiento',
            'usuarios.telefono',
            DB::raw('COALESCE(MAX(estados_tratamiento.nombre), "Sin tratamiento") as estado_tratamiento'),
            DB::raw('MIN(tratamientos.created_at) as fecha_inicio')
        )
        ->where('usuarios.rol_id', 1)   // 🔎 solo rol 1
        ->groupBy(
            'usuarios.id',
            'usuarios.nombre',
            'usuarios.apellido',
            'usuarios.mail',
            'usuarios.dni',
            'usuarios.fecha_nacimiento',
            'usuarios.telefono'
        )
        ->get();

    return view('operador.home', compact('pacientes', 'rol_id'));
}



    public function tratamientosDeUnPaciente($pacienteId)
    {       
        $tratamientos = DB::table('tratamientos')
        ->join('historias_clinica', 'tratamientos.historia_clinica_id', '=', 'historias_clinica.id')
        ->join('usuarios', 'historias_clinica.paciente_id', '=', 'usuarios.id')
        ->join('estados_tratamiento', 'tratamientos.estado_tratamiento_id', '=', 'estados_tratamiento.id')
        ->join('objetivos', 'objetivos.id', '=', 'tratamientos.objetivo_id')
        ->select(
            'tratamientos.id',
            'objetivos.nombre as objetivo',
            'estados_tratamiento.nombre as estado_tratamiento',
            'tratamientos.created_at as fecha_inicio',
            'tratamientos.updated_at as ultima_actualizacion'
        )
        ->where('usuarios.id', $pacienteId)
        ->get();

    return response()->json(['tratamientos' => $tratamientos]);
    }

    public function formPuncion($paciente_id)
    {
        $paciente = User::findOrFail($paciente_id);

        return view('operador.puncion', [
            'paciente' => $paciente,
            'punciones' => collect(), // evita errores en la vista
        ]);
    }

   public function guardarPuncion(Request $request)
{
    $request->validate([
        'paciente_id'     => 'required|exists:usuarios,id',
        'fecha_hora'      => 'required|date',
        'nro_quirofano'   => 'required|string|max:20',

        'ovocitos'                     => 'required|array',
        'ovocitos.*.id'                => 'required|string',
        'ovocitos.*.estado_inicial'    => 'required|string',

        'ovocitos.*.accion_muy_inmaduro' => 'nullable|string',
        'ovocitos.*.tiempo_maduracion'   => 'nullable|numeric',
        'ovocitos.*.destino_maduro'      => 'nullable|string',
        'ovocitos.*.calidad_morfologica' => 'nullable|string',
        'ovocitos.*.motivo_descarte'     => 'nullable|string',
    ]);
    $fechaCompleta = $request->fecha . ' ' . $request->hora . ':00';

    // Crear punción
    $puncion = Puncion::create([
        'fecha_hora'    => $fechaCompleta,
        'nro_quirofano' => $request->nro_quirofano,
        'operador_id'   => Auth::id(),
        'paciente_id'   => $request->paciente_id,
    ]);

    // Guardar ovocitos uno por uno
    foreach ($request->ovocitos as $ovo) {

        Ovocito::create([
            'identificador'      => $ovo['id'],
            'estado_inicial'     => $ovo['estado_inicial'] ?? null,
            'accion_muy_inmaduro'=> $ovo['accion_muy_inmaduro'] ?? null,
            'tiempo_maduracion'  => $ovo['tiempo_maduracion'] ?? null,
            'destino_maduro'     => $ovo['destino_maduro'] ?? null,
            'calidad_morfologica'=> $ovo['calidad_morfologica'] ?? null,
            'motivo_descarte'    => $ovo['motivo_descarte'] ?? null,

            'paciente_id'        => $request->paciente_id,
            'puncion_id'         => $puncion->id,
        ]);
    }

    return redirect()
        ->route('operador.puncion.form', $request->paciente_id)
        ->with('success', 'Punción registrada correctamente.');
}

}

