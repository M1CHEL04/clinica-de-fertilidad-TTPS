<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinica;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TurnoController extends Controller
{

    public function showSolicitarTurnoForm()
    {
        // Obtener médicos activos
        $medicos = User::whereHas('rol', function ($query) {
            $query->where('nombre', 'medico');
        })->where('activo', 1)->get();

        // Obtener datos del usuario autenticado
        $usuario = Auth::user();

        return view('usuario.solicitarTurno', compact('medicos', 'usuario'));
    }

    public function storeTurno(Request $request)
    {
        try {
            $paciente = User::find($request->paciente_id);

            if (!$paciente || $paciente->rol->nombre != 'paciente' || !$paciente->activo) {
                Log::error('Paciente no encontrado con ID: ' . $request->paciente_id);
                return redirect()->back()->with('error', 'Paciente no encontrado.');
            }

            // Verificar que tenga historia clínica Y que NO tenga tratamientos activos
            $tieneHistoriaClinica = $paciente->historiasClinicas()->count() > 0;

            $tieneTratamientoActivo = false;

            if ($tieneHistoriaClinica) {
                $tieneTratamientoActivo = $paciente->historiasClinicas()
                    ->with(['tratamientos.estadoTratamiento'])
                    ->get()
                    ->flatMap(function ($historia) {
                        return $historia->tratamientos;
                    })
                    ->filter(function ($tratamiento) {
                        return $tratamiento->estadoTratamiento &&
                            in_array(strtolower($tratamiento->estadoTratamiento->nombre), ['activo']);
                    })
                    ->isNotEmpty();
            }

            if (!$tieneHistoriaClinica) {
                //se crea historia clinica del paciente
                try {
                    HistoriaClinica::create([
                        'paciente_id' => $paciente->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al crear historia clínica: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Error al solicitar el turno. Por favor, intente nuevamente.');
                }

                //aca implementar el cobro
                $pago_id = $this->registrarOrdenPago($paciente->id, $paciente->obra_social_id);

                if (!$pago_id) {
                    Log::error('Error al procesar pago para paciente: ' . $paciente->id);
                    return redirect()->back()->with('error', 'Error al solicitar el turno. Por favor, intente nuevamente.');
                }
            } else if ($tieneHistoriaClinica && !$tieneTratamientoActivo) {

                //aca implementar el cobro
                $pago_id = $this->registrarOrdenPago($paciente->id, $paciente->obra_social_id);

                if (!$pago_id) {
                    Log::error('Error al procesar pago para paciente: ' . $paciente->id);
                    return redirect()->back()->with('error', 'Error al solicitar el turno. Por favor, intente nuevamente.');
                }
            } else {
                // aca lo dejamos por si hay que hacer algo especial para los sobre turnos.

            }

            $token = env('TOKEN_TURNERO');
            $response = Http::withToken($token)->patch(
                'https://ahlnfxipnieoihruewaj.supabase.co/functions/v1/reservar_turno',
                [
                    'id_turno' => $request->turno_id,
                    'id_paciente' => $request->paciente_id,
                ]
            );

            if ($response->failed()) {
                Log::error('Error al llamar a la api', $response->body());
                return redirect()->back()->with('error', 'Error al solicitar el turno. Por favor, intente nuevamente.');
            }

            return redirect()->back()->with('success', 'Turno solicitado correctamente.');
        } catch (\Exception $e) {
            Log::error('Excepción al solicitar turno: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al solicitar el turno. Por favor, intente nuevamente.');
        }
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

    private function registrarOrdenPago($pacienteId, $obraSocialId)
    {
        try {
            $response = Http::post('https://ueozxvwsckonkqypfasa.supabase.co/functions/v1/registrar-orden-pago', [
                'grupo' => 5,
                'id_paciente' => $pacienteId,
                'monto' => 100000,
                'id_obra' => $obraSocialId,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Retornar el ID del pago si está presente en la respuesta
                if (isset($data['pago']['id'])) {
                    return $data['pago']['id'];
                }
                Log::info('Orden de pago registrada exitosamente: ', $data);
            } else {
                Log::error('Error al registrar orden de pago - Status: ' . $response->status() . ' - Body: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar orden de pago: ' . $e->getMessage());
            return false;
        }
    }
}
