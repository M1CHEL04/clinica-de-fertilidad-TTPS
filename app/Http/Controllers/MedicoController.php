<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MedicoController extends Controller
{
    public function misPacientes()
    {
        $medicoId = session('user_id');
    

        // Trae pacientes con tratamientos del médico logueado
        $pacientes = DB::table('tratamientos')
            ->join('historias_clinica', 'tratamientos.historia_clinica_id', '=', 'historias_clinica.id')
            ->join('usuarios', 'historias_clinica.paciente_id', '=', 'usuarios.id')
            ->join('estados_tratamiento', 'tratamientos.estado_tratamiento_id', '=', 'estados_tratamiento.id')
            ->select(
                'usuarios.id as paciente_id',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.mail',
                'usuarios.dni',
                'usuarios.fecha_nacimiento',
                'usuarios.telefono',
                'estados_tratamiento.nombre as estado_tratamiento',
                'tratamientos.created_at as fecha_inicio'
            )
            ->where('tratamientos.medico_id', $medicoId)
            ->distinct()
            ->get();

        

        return view('medico.home', compact('pacientes'));
    }

    public function detalleTratamiento($id)
    {
        // ✅ Trae la info completa del tratamiento
        $tratamiento = DB::table('tratamientos')
            ->join('historias_clinica', 'tratamientos.historia_clinica_id', '=', 'historias_clinica.id')
            ->join('usuarios', 'historias_clinica.paciente_id', '=', 'usuarios.id')
            ->join('estados_tratamiento', 'tratamientos.estado_tratamiento_id', '=', 'estados_tratamiento.id')
            ->join('objetivos', 'objetivos.id', '=', 'tratamientos.objetivo_id')
            ->select(
                'tratamientos.id',
                'objetivos.nombre as objetivo',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.mail',
                'usuarios.dni',
                'usuarios.fecha_nacimiento',
                'estados_tratamiento.nombre as estado_tratamiento',
                'tratamientos.created_at as fecha_inicio',
                'tratamientos.updated_at as ultima_actualizacion'
            )
            ->where('tratamientos.id', $id)
            ->first();

        // ✅ Intentamos traer consultas si existe la tabla (por si aún no la tenés creada)
        $consultas = [];
        if (DB::getSchemaBuilder()->hasTable('consultas')) {
            $consultas = DB::table('consultas')
                ->where('tratamiento_id', $id)
                ->orderBy('fecha', 'asc')
                ->get();
        }

        // ✅ Evita error si no hay consultas (pasa array vacío)
        return view('medico.detalleTratamiento', compact('tratamiento', 'consultas'));
    }

    public function tratamientosDeUnPaciente($pacienteId)
    {
         $medicoId = session('user_id');
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
        ->where('tratamientos.medico_id', $medicoId)
        ->get();

    return response()->json(['tratamientos' => $tratamientos]);
    }
}
