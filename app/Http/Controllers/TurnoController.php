<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        return redirect()->back()->with('success', 'Turno solicitado correctamente. Nos contactaremos con usted para confirmar la cita.');
    }

    public function listarTurnosLibres($id_medico)
    {
        try {
            $token = env('TOKEN_TURNERO');
            $response = Http::withToken($token)->get('https://ahlnfxipnieoihruewaj.supabase.co/functions/v1/get_turnos_medico', [
                'id_medico' => $id_medico,
            ]);

            if ($response->failed()) {
                Log::error('Error al obtener los turnos libres: ' . $response->body());
                return response()->json(['error' => 'Error al obtener los turnos libres.'], 500);
            }

            $turnos = $response->json()['data'];

            // Filtrar solo turnos libres (donde id_paciente es null)
            $turnosLibres = collect($turnos)->filter(function ($turno) {
                return is_null($turno['id_paciente']);
            })->values();

            return response()->json([
                'success' => true,
                'data' => $turnosLibres
            ]);
        } catch (\Exception $e) {
            Log::error('Error al comunicarse con el servicio de turnos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al comunicarse con el servicio de turnos.'], 500);
        }
    }
}
