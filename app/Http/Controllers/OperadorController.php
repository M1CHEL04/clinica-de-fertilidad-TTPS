<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Puncion;
use App\Models\Ovocito;
use App\Models\User;
use App\Models\EstadoOvocito;
use App\Models\Fertilizacion;
use App\Models\Guardado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\HistorialOvocito;
use App\Models\TipoEstadoOvocito;
use App\Models\Tratamiento;

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


        // Traemos TODAS las punciones del paciente con toda la información necesaria
        $punciones = Puncion::where('paciente_id', $paciente_id)
            ->with([
                'operador', // usuario que registró la punción

                'ovocitos.estado_ovocito', // estado del ovocito
                'ovocitos.estado_ovocito.TipoEstadoOvocito', // tipo de estado
                'ovocitos.guardado', // si existe relación de guardado
                'ovocitos.paciente', // paciente dueño del ovocito
            ])
            ->orderBy('fecha_hora', 'desc')
            ->get();
        $estados = TipoEstadoOvocito::all();
        //dd( $punciones->toArray());
        return view('operador.puncion', [
            'paciente'  => $paciente,
            'punciones' => $punciones,
            'estados'   => $estados,
        ]);
    }



    private function mapTipoEstado($estado)
    {
        return match ($estado) {
            'muy_inmaduro' => 3,
            'inmaduro'     => 2,
            'maduro'       => 1,
            default        => 1,
        };
    }


    public function guardarPuncion(Request $request)
    {
        $request->validate([
            'paciente_id'   => 'required|exists:usuarios,id',
            'fecha'         => 'required|date',
            'hora'          => 'required',
            'numero_quirofano' => 'required|string|max:20',

            'ovocitos'                     => 'required|array',
            'ovocitos.*.id'                => 'required|string',
            'ovocitos.*.estado_inicial'    => 'required|string',

            'ovocitos.*.accion_muy_inmaduro' => 'nullable|string',
            'ovocitos.*.tiempo_maduracion'   => 'nullable|numeric',
            'ovocitos.*.destino_maduro'      => 'nullable|string',
            'ovocitos.*.calidad_morfologica' => 'nullable|string',
            'ovocitos.*.motivo_descarte'     => 'nullable|string',
        ]);

        // Unificación fecha + hora
        $fechaCompleta = $request->fecha . ' ' . $request->hora . ':00';

        $rol = session('user_id');

        // Crear punción
        $puncion = Puncion::create([
            'fecha_hora'    => $fechaCompleta,
            'nro_quirofano' => $request->numero_quirofano,
            'operador_id'   => $rol,
            'paciente_id'   => $request->paciente_id,
        ]);

        // Guardar ovocitos con su estado
        foreach ($request->ovocitos as $ovo) {

            // 1) Crear estado según el caso
            $estado = new EstadoOvocito();
            $estado->tipo_estado_ovocito_id = $this->mapTipoEstado($ovo['estado_inicial']);
            $estado->motivo_descarte  = $ovo['motivo_descarte']     ?? null;
            $estado->tiempo_maduracion = $ovo['tiempo_maduracion']  ?? null;
            $estado->save();

            // 2) Crear ovocito asociado
            $ovocito = Ovocito::create([
                'identificador'       => $ovo['id'],
                'calidad_morfologica' => $ovo['calidad_morfologica'] ?? null,
                'paciente_id'         => $request->paciente_id,
                'puncion_id'          => $puncion->id,
                'estado_ovocito_id'   => $estado->id,
            ]);

            /*
        ──────────────────────────────────────────
        REGISTRO DE HISTORIAL
        ──────────────────────────────────────────
        */

            // Estado inicial


            // Maduración
            if (!empty($ovo['tiempo_maduracion'])) {
                HistorialOvocito::create([
                    'ovocito_id'        => $ovocito->id,
                    'estado_ovocito_id' => $estado->id,
                    'accion'            => 'Maduración',
                    'descripcion'       => 'Tiempo de maduración: ' . $ovo['tiempo_maduracion'] . ' horas.',
                    'usuario_id'        => $rol,
                    'estado_anterior_id' => $estado->TipoEstadoOvocito->id,
                    'estado_nuevo_id' => $estado->TipoEstadoOvocito->id,
                ]);
            }

            // Descarte
            if (!empty($ovo['motivo_descarte'])) {
                HistorialOvocito::create([
                    'ovocito_id'        => $ovocito->id,
                    'estado_ovocito_id' => $estado->id,
                    'accion'            => 'Descarte',
                    'descripcion'       => 'Motivo: ' . $ovo['motivo_descarte'],
                    'usuario_id'        => $rol,
                    'estado_anterior_id' => $estado->TipoEstadoOvocito->id,
                    'estado_nuevo_id' => $estado->TipoEstadoOvocito->id,
                ]);
            }

            // Criopreservación
            if (!empty($ovo['destino_maduro']) && $ovo['destino_maduro'] === 'criopreservar') {

                $guardadoId = $this->registrarCriopreservacion($ovocito->id);

                if ($guardadoId) {

                    $ovocito->update([
                        'guardado_id' => $guardadoId
                    ]);

                    HistorialOvocito::create([
                        'ovocito_id'        => $ovocito->id,
                        'estado_ovocito_id' => $estado->id,
                        'accion'            => 'criopreservar',
                        'descripcion'       => 'El ovocito ha sido criopreservado.',
                        'usuario_id'        => $rol,
                        'estado_anterior_id' => $estado->TipoEstadoOvocito->id,
                        'estado_nuevo_id' => $estado->TipoEstadoOvocito->id,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Acción realizada correctamente.');
    }


    public function registrarCriopreservacion($ovocito_id)
    {
        try {
            Log::info("Iniciando registro de criopreservación para ovocito $ovocito_id");

            // Llamada al endpoint
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(
                'https://ssewaxrnlmnyizqsbzxe.supabase.co/functions/v1/assign-ovocyte',
                [
                    'nro_grupo' => 5,
                    'ovocito_id' => $ovocito_id,
                ]
            );

            if (!$response->successful()) {
                Log::error('Error al registrar criopreservación', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
                return false;
            }

            // El endpoint devuelve un array → tomamos el primer elemento
            $data = $response->json();

            if (!is_array($data) || empty($data)) {
                Log::error("Respuesta inesperada del módulo", ['data' => $data]);
                return false;
            }

            $registro = $data[0];

            Log::info('Criopreservación asignada correctamente', $registro);

            // Guardar en la tabla guardados
            $guardado = Guardado::create([
                'id_tanque'   => $registro['tanque_id'],
                'id_rack'     => $registro['rack_id'],
            ]);

            return $guardado->id;
        } catch (\Exception $e) {
            Log::error('Excepción registrando criopreservación: ' . $e->getMessage());
            return false;
        }
    }

    private function deallocateOvocyte($ovocito)
    {
        try {
            Log::info("Iniciando retiro de  ovocito $ovocito");
            if (!$ovocito->guardado_id || !$ovocito->guardado) {
                return false; // nada que liberar
            }

            $datos = $ovocito->guardado;

            $payload = [
                "ovocito_id" => (string)$ovocito->id,
                "nro_grupo"  => '5',
                "id_tanque"  => $datos->id_tanque,
                "id_rack"    => $datos->id_rack,
            ];


            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post(
                'https://ssewaxrnlmnyizqsbzxe.supabase.co/functions/v1/deallocate-ovocyte',
                $payload
            );

            if ($response->successful()) {
                // Liberado correctamente
                $ovocito->update(['guardado_id' => null]);
                $ovocito->guardado->delete();
                Log::info("Retiro exitoso");
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Excepción registrando retiro de ovocito: ' . $e->getMessage());
            return false;
        }
    }


    public function getJson($id)
    {
        $ovocito = Ovocito::with('estado_ovocito.TipoEstadoOvocito', 'guardado')->findOrFail($id);

        // Convertimos en JSON y devolvemos solo lo necesario
        return response()->json([
            'id' => $ovocito->id,
            'identificador' => $ovocito->identificador,
            'calidad_morfologica' => $ovocito->calidad_morfologica,
            'guardado' => [
                'id_tanque' => $ovocito->guardado->id_tanque ?? null,
                'id_rack' => $ovocito->guardado->id_rack ?? null,
            ],
            'estado_ovocito' => [
                'tipo' => $ovocito->estado_ovocito->TipoEstadoOvocito->nombre ?? null,
                'tiempo_maduracion' => $ovocito->estado_ovocito->tiempo_maduracion ?? null,
                'motivo_descarte' => $ovocito->estado_ovocito->motivo_descarte ?? null,
            ],
        ]);
    }


    public function updateOvocito(Request $request)
    {
        // 1️⃣ Tomar el ID desde el formulario
        $id = $request->input('ovocito_id');
        $ovocito = Ovocito::with('estado_ovocito')->findOrFail($id);

        // 2️⃣ Guardar datos actuales para historial
        $estadoAnterior = $ovocito->estado_ovocito
            ? $ovocito->estado_ovocito->replicate()
            : null;

        // 3️⃣ Determinar estado nuevo
        $estadoInicial = $request->input('estado_inicial'); // muy_inmaduro, inmaduro, maduro
        $motivoDescarte = $request->input('motivo_descarte');
        $tiempoMaduracion = $request->input('tiempo_maduracion');

        if ($estadoInicial == 'muy_inmaduro') $estadoInicial = "Muy inmaduro";

        // Buscar el tipo de estado
        $tipoEstado = TipoEstadoOvocito::where('nombre', ucfirst($estadoInicial))->first();
        if (!$tipoEstado) {
            return redirect()->back()->with('error', 'Estado inválido.');
        }

        // 4️⃣ Crear o actualizar el EstadoOvocito
        $estado = $ovocito->estado_ovocito ?? new EstadoOvocito();
        $estado->tipo_estado_ovocito_id = $tipoEstado->id;
        $estado->motivo_descarte = $motivoDescarte ?? null;
        $estado->tiempo_maduracion = $tiempoMaduracion ?? null;
        $estado->save();

        // Asociar al ovocito
        $ovocito->estado_ovocito_id = $estado->id;

        // 5️⃣ Calidad morfológica si corresponde
        if ($request->has('calidad_morfologica')) {
            $ovocito->calidad_morfologica = $request->input('calidad_morfologica');
        }

        $ovocito->save();

        $isOK = false;
        $eraMaduro = $estadoAnterior?->TipoEstadoOvocito?->nombre === 'Maduro';
        $estaCriopreservado = $ovocito->guardado;
        if ($eraMaduro && $estaCriopreservado) {
            $isOK = $this->deallocateOvocyte($ovocito);

            if (!$isOK) {
                return redirect()->back()->with('error', 'No se pudo liberar la posición del ovocito.');
            }
        }



        // 6️⃣ Crear historial
        $ovocito->historial()->create([
            'fecha_cambio' => now(),
            'accion' => $request->accion,
            'estado_anterior_id' => $estadoAnterior->tipo_estado_ovocito_id,
            'estado_nuevo_id' => $estado->tipo_estado_ovocito_id,
            'motivo_descarte' => $motivoDescarte,
            'tiempo_maduracion' => $tiempoMaduracion,
        ]);
        if ($isOK) return redirect()->back()->with('success', "Ovocito {$ovocito->identificador} actualizado correctamente. Ademas se retiro el ovocito de la zona de criopreservacion");
        if ($request->accion == 'criopreservar' && !$estaCriopreservado) {
            $guardadoId = $this->registrarCriopreservacion($id);

            if ($guardadoId) {

                $ovocito->update([
                    'guardado_id' => $guardadoId
                ]);
            }
        } elseif ($estaCriopreservado) {
            return redirect()->back()->with('error', "Este ovocito ya se encuentra criopreservado");
        }
        return redirect()->back()->with('success', "Ovocito {$ovocito->identificador} actualizado correctamente.");
    }

    public function fertilizacion($paciente_id)
    {
        $paciente = User::findOrFail($paciente_id);

        // Obtener todas las fertilizaciones del paciente con las relaciones necesarias
        $fertilizaciones = \App\Models\Fertilizacion::where('paciente_id', $paciente_id)
            ->with([
                'operador',
                'tipo_fertilizacion',
                'embrion.ovocito',
                'embrion.estado',
                'embrion.guardado',
                'tratamiento'
            ])
            ->orderBy('fecha_fertilizacion', 'desc')
            ->get();

        // Obtener todos los embriones del paciente
        $embriones = \App\Models\Embrion::whereHas('fertilizacion', function ($query) use ($paciente_id) {
            $query->where('paciente_id', $paciente_id);
        })
            ->with([
                'fertilizacion.tipo_fertilizacion',
                'estado',
                'guardado',
                'ovocito'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener tipos de fertilización para el formulario
        $tiposFertilizacion = \App\Models\TipoFertilizacion::all();


        return view('operador.fertilizacion', compact('paciente', 'fertilizaciones', 'embriones', 'tiposFertilizacion'));
    }


    public function nuevaFertilizacion($paciente_id)
    {
        $paciente = User::findOrFail($paciente_id);

        $tratamiento_id = Tratamiento::whereHas('historiaClinica', function ($query) use ($paciente_id) {
            $query->where('paciente_id', $paciente_id);
        })
            ->where('estado_tratamiento_id', 2)
            ->first()?->id;

        // Obtener tipos de fertilización para el formulario
        $tiposFertilizacion = \App\Models\TipoFertilizacion::all();

        // Obtener estados de embrión para el formulario
        $estadosEmbrion = \App\Models\EstadoEmbrion::all();

        // Debug: Primero ver todos los ovocitos del paciente
        $todosOvocitos = \App\Models\Ovocito::where('paciente_id', $paciente_id)
            ->with(['estado_ovocito.TipoEstadoOvocito'])
            ->get();

        Log::info("Debug - Total ovocitos del paciente $paciente_id: " . $todosOvocitos->count());

        foreach ($todosOvocitos as $ovocito) {
            $tipoEstado = $ovocito->estado_ovocito?->TipoEstadoOvocito?->nombre ?? 'Sin estado';
            $utilizado = $ovocito->utilizado ? 'Sí' : 'No';
            Log::info("Ovocito {$ovocito->identificador} - Estado: {$tipoEstado} - Utilizado: {$utilizado}");
        }

        // Obtener ovocitos maduros disponibles directamente
        $ovocitosDisponibles = \App\Models\Ovocito::where('paciente_id', $paciente_id)
            ->where('utilizado', false) // Filtrar por no utilizados
            ->whereHas('estado_ovocito.TipoEstadoOvocito', function ($query) {
                $query->where('nombre', 'Maduro');
            })
            ->whereDoesntHave('embriones') // No han sido utilizados para crear embriones
            ->with(['estado_ovocito.TipoEstadoOvocito'])
            ->select('id', 'identificador', 'calidad_morfologica')
            ->get();

        Log::info("Ovocitos maduros y disponibles para fertilización: " . $ovocitosDisponibles->count());

        return view('operador.fertilizacionCarga', compact('paciente', 'tiposFertilizacion', 'estadosEmbrion', 'ovocitosDisponibles', 'tratamiento_id'));
    }
    public function guardarFertilizacion(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:usuarios,id',
            'fecha_fertilizacion' => 'required|date',
            'hora_fertilizacion' => 'required',
            'tipo_fertilizacion_id' => 'required|exists:tipos_fertilizacion,id',
            'embriones' => 'required|array',
            'embriones.*.ovocito_id' => 'required|exists:ovocitos,id',
            'embriones.*.calidad_morfologica' => 'required|in:1,2,3,4,5',
            'embriones.*.identificador' => 'required|string',
            'embriones.*.fuente_semen' => 'required|in:pareja,donado',
            'embriones.*.accion' => 'required|in:descartar,criopreservar,transferir',
            'embriones.*.motivo_descarte' => 'required_if:embriones.*.accion,descartar',
        ]);

        try {

            DB::beginTransaction();

            // Crear fertilización
            $fertilizacion = Fertilizacion::create([
                'tipo_fertilizacion_id' => $request->tipo_fertilizacion_id,
                'operador_id' => session('user_id'),
                'paciente_id' => $request->paciente_id,
                'tratamiento_id' => $request->tratamiento_id,
                'fecha_fertilizacion' => $request->fecha_fertilizacion,
            ]);

            // Crear embriones
            foreach ($request->embriones as $embrionData) {

                $embrion = \App\Models\Embrion::create([
                    'identificador' => $embrionData['identificador'],
                    'guardado_id' => null, // Se asignará cuando se criopreserve
                    'fertilizacion_id' => $fertilizacion->id,
                    'ovocito_id' => $embrionData['ovocito_id'],
                    'calidad_morfologica' => $embrionData['calidad_morfologica'],
                ]);

                switch ($embrionData['fuente_semen']) {
                    case 'pareja':
                        try {
                            $dni_pareja = Tratamiento::where('id', $request->tratamiento_id)->first()->antecedentesPareja->dni;

                            try {
                                // aca tengo que maracar como utilizado el semen en la API.
                                $response = Http::withHeaders([
                                    'Content-Type' => 'application/json',
                                ])->post(
                                    'https://bmcgxbtbcmlzoetyqajn.supabase.co/functions/v1/dni-tiene-muestra',
                                    [
                                        'group_id' => 5,
                                        'dni' => $dni_pareja,
                                    ]
                                );

                                if (!$response->successful()) {
                                    Log::error('Error al buscar la muestra: El dni no tiene muestra de semen criopreservado', [
                                        'status' => $response->status(),
                                        'body'   => $response->body()
                                    ]);
                                    return redirect()->back()
                                        ->withInput()
                                        ->with('error', 'Error al buscar la muestra: El dni no tiene muestra de semen criopreservado.');
                                } else {

                                    $responseData = Http::withHeaders([
                                        'Content-Type' => 'application/json',
                                    ])->post(
                                        'https://bmcgxbtbcmlzoetyqajn.supabase.co/functions/v1/descongelar-semen',
                                        [
                                            'group_id' => 5,
                                            'dni' => $dni_pareja,
                                        ]
                                    );

                                    if (!$responseData->successful()) {
                                        Log::error('Error al marcar el semen como utilizado', [
                                            'status' => $responseData->status(),
                                            'body'   => $responseData->body()
                                        ]);
                                        return redirect()->back()
                                            ->withInput()
                                            ->with('error', 'Error guardar el ovocito.');
                                    }
                                }
                            } catch (\Exception $e) {
                                Log::error('Error en la conexión a la API de semen: ' . $e->getMessage());
                                return redirect()->back()
                                    ->withInput()
                                    ->with('error', 'Error de conexión al buscar la muestra de semen.');
                            }

                            $embrion->update([
                                'semen_dni' => $dni_pareja
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error al obtener DNI de la pareja: ' . $e->getMessage());
                            $dni_pareja = null;
                        }

                        break;
                    case 'donado':
                        //Aca tengo que ir buscar el gameto a la api con los datos de fenotipo ingresados.

                        break;
                }

                // Procesar acción del embrión
                switch ($embrionData['accion']) {
                    case 'descartar':
                        $embrion->update([
                            'motivo_descarte' => $embrionData['motivo_descarte']
                        ]);
                        break;

                    case 'criopreservar':
                        // Llamar al método de criopreservación
                        $response = Http::withHeaders([
                            'Content-Type' => 'application/json',
                        ])->post(
                            'https://ssewaxrnlmnyizqsbzxe.supabase.co/functions/v1/assign-ovocyte',
                            [
                                'nro_grupo' => 5,
                                'ovocito_id' => $embrion->id,
                            ]
                        );

                        if (!$response->successful()) {
                            Log::error('Error al registrar criopreservación', [
                                'status' => $response->status(),
                                'body'   => $response->body()
                            ]);
                            return false;
                        }

                        $data = $response->json();

                        if (!is_array($data) || empty($data)) {
                            Log::error("Respuesta inesperada del módulo", ['data' => $data]);
                            return false;
                        }

                        $registro = $data[0];

                        Log::info('Criopreservación asignada correctamente', $registro);

                        // Guardar en la tabla guardados
                        $guardado = Guardado::create([
                            'id_tanque'   => $registro['tanque_id'],
                            'id_rack'     => $registro['rack_id'],
                        ]);

                        $embrion->update([
                            'guardado_id' => $guardado->id
                        ]);
                        break;
                    case 'transferir':
                        $embrion->update([
                            'transferir' => true
                        ]);
                        break;
                }

                // Registrar en historial que el ovocito fue usado para fertilización
                $ovocito = \App\Models\Ovocito::find($embrionData['ovocito_id']);
                if ($ovocito) {
                    // Marcar el ovocito como utilizado
                    $ovocito->update(['utilizado' => true]);

                    \App\Models\HistorialOvocito::create([
                        'ovocito_id' => $ovocito->id,
                        'fecha_cambio' => now(),
                        'accion' => 'fertilizacion',
                        'descripcion' => "Ovocito utilizado para fertilización #{$fertilizacion->id}",
                        'usuario_id' => session('user_id'),
                        'estado_anterior_id' => $ovocito->estado_ovocito->tipo_estado_ovocito_id,
                        'estado_nuevo_id' => $ovocito->estado_ovocito->tipo_estado_ovocito_id,
                    ]);
                }
            }

            DB::commit();

            Log::info("Fertilización creada exitosamente", [
                'fertilizacion_id' => $fertilizacion->id,
                'embriones_creados' => count($request->embriones),
                'paciente_id' => $request->paciente_id
            ]);

            return redirect()->route('operador.fertilizacion', ['paciente_id' => $request->paciente_id])
                ->with('success', "Fertilización registrada exitosamente. Se crearon " . count($request->embriones) . " embriones.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar fertilización: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar la fertilización. Por favor, inténtelo nuevamente.');
        }
    }
}
