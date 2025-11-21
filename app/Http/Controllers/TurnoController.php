<?php

namespace App\Http\Controllers;

use App\Models\HistoriaClinica;
use App\Models\Tratamiento;
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
                    $HistoriaClinica = HistoriaClinica::create([
                        'paciente_id' => $paciente->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al crear historia clínica: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Error al solicitar el turno. Por favor, intente nuevamente.');
                }

                //aca implementar el cobro
                $pago_id = $this->registrarOrdenPago($paciente->id, $paciente->obra_social_id);

                Log::info('Resultado de registrar orden de pago: ' . ($pago_id ? $pago_id : 'NULL/FALSE'));

                if (!$pago_id) {
                    Log::error('Error al procesar pago para paciente: ' . $paciente->id);
                    return redirect()->back()->with('error', 'Error al procesar el pago. Por favor, intente nuevamente.');
                }

                try {
                    Tratamiento::create([
                        'historia_clinica_id' => $HistoriaClinica->id,
                        'estado_tratamiento_id' => 1,
                        'medico_id' => $request->medico_id,
                        'pago_id' => $pago_id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al crear tratamiento: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Error al solicitar el turno. Por favor, intente nuevamente.');
                }
            } else if ($tieneHistoriaClinica && !$tieneTratamientoActivo) {

                $HistoriaClinica = $paciente->historiasClinicas()->first();

                //aca implementar el cobro
                $pago_id = $this->registrarOrdenPago($paciente->id, $paciente->obra_social_id);

                Log::info('Resultado de registrar orden de pago (caso 2): ' . ($pago_id ? $pago_id : 'NULL/FALSE'));

                if (!$pago_id) {
                    Log::error('Error al procesar pago para paciente: ' . $paciente->id);
                    return redirect()->back()->with('error', 'Error al procesar el pago. Por favor, intente nuevamente.');
                }

                Tratamiento::create([
                    'historia_clinica_id' => $HistoriaClinica->id,
                    'estado_tratamiento_id' => 1,
                    'medico_id' => $request->medico_id,
                    'pago_id' => $pago_id,
                ]);
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

    public function listarTurnosSugeridos($id_medico, $id_paciente)
    {
        try {
            $paciente = User::find($id_paciente);

            if (!$paciente) {
                return response()->json(['error' => 'Paciente no encontrado'], 404);
            }

            // Buscar tratamiento activo con fecha sugerida
            $tratamientoActivo = $paciente->historiasClinicas()
                ->with(['tratamientos.estadoTratamiento'])
                ->get()
                ->flatMap(function ($historia) {
                    return $historia->tratamientos;
                })
                ->filter(function ($tratamiento) {
                    return $tratamiento->estadoTratamiento &&
                        in_array(strtolower($tratamiento->estadoTratamiento->nombre), ['activo']) &&
                        ($tratamiento->fecha_sugerida_inicio !== null || $tratamiento->fecha_sugerida_fin !== null);
                })
                ->first();

            if (!$tratamientoActivo || ($tratamientoActivo->fecha_sugerida_inicio === null && $tratamientoActivo->fecha_sugerida_fin === null)) {
                return response()->json([
                    'success' => false,
                    'has_suggested_date' => false,
                    'message' => 'No hay fecha sugerida para este paciente'
                ]);
            }

            // Usar las fechas de inicio y fin del tratamiento
            $fechaInicio = $tratamientoActivo->fecha_sugerida_inicio;
            $fechaFin = $tratamientoActivo->fecha_sugerida_fin;

            // Si solo hay una fecha, usar esa fecha para ambos extremos
            if ($fechaInicio === null) {
                $fechaInicio = $fechaFin;
            }
            if ($fechaFin === null) {
                $fechaFin = $fechaInicio;
            }

            // Obtener todos los turnos del médico
            $token = env('TOKEN_TURNERO');
            $response = Http::withToken($token)->get('https://ahlnfxipnieoihruewaj.supabase.co/functions/v1/get_turnos_medico', [
                'id_medico' => $id_medico,
            ]);

            if ($response->failed()) {
                Log::error('Error al obtener los turnos: ' . $response->body());
                return response()->json(['error' => 'Error al obtener los turnos.'], 500);
            }

            $todosLosTurnos = $response->json()['data'];

            // Filtrar turnos libres en el rango de fechas
            $turnosEnRango = collect($todosLosTurnos)->filter(function ($turno) use ($fechaInicio, $fechaFin) {
                if (!is_null($turno['id_paciente'])) {
                    return false; // Solo turnos libres
                }

                $turnoFecha = date('Y-m-d', strtotime($turno['fecha_hora']));
                return $turnoFecha >= $fechaInicio && $turnoFecha <= $fechaFin;
            })->values();

            return response()->json([
                'success' => true,
                'has_suggested_date' => true,
                'suggested_date_start' => $fechaInicio,
                'suggested_date_end' => $fechaFin,
                'date_range' => [
                    'start' => $fechaInicio,
                    'end' => $fechaFin
                ],
                'data' => $turnosEnRango
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener turnos sugeridos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al comunicarse con el servicio de turnos sugeridos.'], 500);
        }
    }

    private function registrarOrdenPago($pacienteId, $obraSocialId)
    {
        try {
            Log::info('Iniciando registro de orden de pago para paciente: ' . $pacienteId . ' con obra social: ' . $obraSocialId);

            $response = Http::post('https://ueozxvwsckonkqypfasa.supabase.co/functions/v1/registrar-orden-pago', [
                'grupo' => 5,
                'id_paciente' => $pacienteId,
                'monto' => 100000,
                'id_obra' => $obraSocialId,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Respuesta exitosa de orden de pago', ['data' => $data]);

                // Retornar el ID del pago si está presente en la respuesta
                if (isset($data['pago']['id'])) {
                    Log::info('ID de pago encontrado: ' . $data['pago']['id']);
                    return $data['pago']['id'];
                }

                Log::error('No se encontró ID de pago en la respuesta exitosa', ['data' => $data]);
                return false;
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
