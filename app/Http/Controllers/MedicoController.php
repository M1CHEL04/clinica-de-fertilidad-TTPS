<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Tratamiento;
use App\Models\Estudio;
use App\Models\ProtocoloEstimulacion;
use App\Models\TipoMedicacion;
use App\Models\Monitoreo;
use App\Models\PostTransferencia;
use Illuminate\Http\Request;
use App\Http\Controllers\MailController;
use App\Models\User;

class MedicoController extends Controller
{
    public function misPacientes()
    {
        $medicoId = session('user_id');
        $rol_id = session('rol');


        // Trae pacientes con tratamientos del médico logueado
        $pacientes = DB::table('tratamientos')
            ->join('historias_clinica', 'tratamientos.historia_clinica_id', '=', 'historias_clinica.id')
            ->join('usuarios', 'historias_clinica.paciente_id', '=', 'usuarios.id')
            ->join('estados_tratamiento', 'tratamientos.estado_tratamiento_id', '=', 'estados_tratamiento.id')
            ->where('tratamientos.medico_id', $medicoId)
            ->select(
                'usuarios.id as paciente_id',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.mail',
                'usuarios.dni',
                'usuarios.fecha_nacimiento',
                'usuarios.telefono',
                'usuarios.proximo_turno',
                DB::raw('MAX(estados_tratamiento.nombre) as estado_tratamiento'),
                DB::raw('MIN(tratamientos.created_at) as fecha_inicio')
            )
            ->groupBy(
                'usuarios.id',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.mail',
                'usuarios.dni',
                'usuarios.fecha_nacimiento',
                'usuarios.telefono',
                'usuarios.proximo_turno'
            )
            ->get();




        return view('medico.home', compact('pacientes', 'rol_id'));
    }

    public function todosPacientes()
    {
        $medicoId = session('user_id');
        $rol_id = session('rol');


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
                'usuarios.proximo_turno',
                DB::raw('MAX(estados_tratamiento.nombre) as estado_tratamiento'),
                DB::raw('MIN(tratamientos.created_at) as fecha_inicio')
            )
            ->groupBy(
                'usuarios.id',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.mail',
                'usuarios.dni',
                'usuarios.fecha_nacimiento',
                'usuarios.telefono',
                'usuarios.proximo_turno'
            )
            ->get();

        return view('jefe.home', compact('pacientes', 'rol_id'));
    }

    public function detalleTratamiento($id)
    {

        // ✅ Trae la info completa del tratamiento
        $rol_id = session('rol');
        $tratamiento = DB::table('tratamientos')
            ->join('historias_clinica', 'tratamientos.historia_clinica_id', '=', 'historias_clinica.id')
            ->join('usuarios', 'historias_clinica.paciente_id', '=', 'usuarios.id')
            ->join('estados_tratamiento', 'tratamientos.estado_tratamiento_id', '=', 'estados_tratamiento.id')
            ->join('objetivos', 'objetivos.id', '=', 'tratamientos.objetivo_id')
            ->join('etapa', 'etapa.id', '=', 'tratamientos.etapa_id')
            ->select(
                'tratamientos.id',
                'usuarios.id as paciente_id',
                'objetivos.nombre as objetivo',
                'usuarios.id as id_usuario',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.mail',
                'usuarios.dni',
                'usuarios.fecha_nacimiento',
                'usuarios.proximo_turno',
                'estados_tratamiento.nombre as estado_tratamiento',
                'etapa.nombre as etapa',
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
        return view('medico.detalleTratamiento', compact('tratamiento', 'consultas', 'rol_id'));
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

    public function cargarEstudios($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);

        // Estudios pendientes agrupados por tipo_estudio
        $estudiosPendientes = Estudio::where('tratamiento_id', $id)
            ->whereNull('resultado')
            ->orderBy('tipo_estudio')
            ->get()
            ->groupBy('tipo_estudio');

        // Estudios completados agrupados por tipo_estudio
        $estudiosCompletados = Estudio::where('tratamiento_id', $id)
            ->whereNotNull('resultado')
            ->orderBy('tipo_estudio')
            ->get()
            ->groupBy('tipo_estudio');

        // Obtener antecedentes de pareja por tratamiento_id
        $antecedentePareja = $tratamiento->antecedentesPareja;

        return view(
            'medico.cargarEstudios',
            compact('tratamiento', 'estudiosPendientes', 'estudiosCompletados', 'antecedentePareja')
        );
    }


    public function guardarEstudios($id)
    {
        $resultados = request('resultados');
        $viabilidadSemen = request('viabilidad_semen_' . $id);

        // Actualizar viabilidad del semen si se proporcionó
        if ($viabilidadSemen !== null) {
            $semenViable = null;
            if ($viabilidadSemen === 'positivo') {
                $semenViable = true;
            } elseif ($viabilidadSemen === 'negativo') {
                $semenViable = false;
            }

            $antecedentesPareja = Tratamiento::find($id)->antecedentesPareja;
            $antecedentesPareja->update(['semen_viable' => $semenViable]);
        }

        // Guardar resultados de estudios
        if ($resultados) {
            foreach ($resultados as $estudioId => $resultado) {
                if (trim($resultado) !== '') {
                    Estudio::where('id', $estudioId)
                        ->where('tratamiento_id', $id)
                        ->update(['resultado' => $resultado]);
                }
            }
        }
        if (session('rol') == 5) {
            return redirect()
                ->route('jefe.tratamiento.cargar-estudios', $id)
                ->with('success', 'Resultados cargados correctamente.');
        } else {
            return redirect()
                ->route('tratamiento.cargar-estudios', $id)
                ->with('success', 'Resultados cargados correctamente.');
        }
    }

    public function protocolo($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        $protocolo = ProtocoloEstimulacion::where('tratamiento_id', $id)->get();
        $tiposMedicacion = TipoMedicacion::all();
        return view('medico.protocolo', compact('tratamiento', 'protocolo', 'tiposMedicacion'));
    }

    public function guardarProtocolo(Request $request, $id)
    {
        $request->validate([
            'dosis' => 'required',
            'tiempo' => 'required',
            'droga' => 'required'
        ]);

        ProtocoloEstimulacion::create([
            'tratamiento_id' => $id,
            'tipo_medicacion_id' => $request->tipo_medicacion_id,
            'dosis' => $request->dosis,
            'tiempo' => $request->tiempo,
            'droga' => $request->droga,
        ]);

        return back()->with('success', 'Protocolo registrado correctamente.');
    }

    public function subirConsentimiento(Request $request, $id)
    {
        $request->validate([
            'consentimiento' => 'required|mimes:pdf|max:2048'
        ]);

        $tratamiento = Tratamiento::findOrFail($id);

        $path = $request->file('consentimiento')->store('consentimientos', 'public');

        $tratamiento->consentimiento_pdf = $path;
        $tratamiento->save();

        return back()->with('success', 'Consentimiento informado cargado.');
    }

    public function descargarConsentimiento($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);

        if (!$tratamiento->consentimiento_pdf) {
            abort(404, 'No hay archivo cargado');
        }

        $path = storage_path('app/public/' . $tratamiento->consentimiento_pdf);

        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->download($path);
    }

    public function monitoreos($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);

        // ejemplo: traer monitoreos asociados
        $monitoreos = Monitoreo::where('tratamiento_id', $id)->orderBy('created_at', 'desc')->get();

        return view('medico.monitoreos', compact('tratamiento', 'monitoreos'));
    }

    public function storeMonitoreo(Request $request)
    {
        $validated = $request->validate([
            'tratamiento_id' => 'required|exists:tratamientos,id',
            'observacion'    => 'required|string',
            'user_id'       => 'required|exists:usuarios,id',
        ]);

        Monitoreo::create([
            'tratamiento_id' => $validated['tratamiento_id'],
            'observacion'    => $validated['observacion'],
            'user_id'   => $validated['user_id'],
        ]);

        return back()->with('success', 'Monitoreo cargado correctamente');
    }

    private $etapas = [
        1 => 'Primera Consulta',
        2 => 'Segunda Consulta',
        3 => 'Monitoreos',
        4 => 'Punción',
        5 => 'Fertilizacion',
        6 => 'Transferencia',
        7 => 'Control de embarazo',
        8 => 'Finalizado',
    ];


    public function avanzarEtapa($id)
    {
        $tratamiento = DB::table('tratamientos')->where('id', $id)->first();

        if (!$tratamiento) {
            return back()->with('error', 'Tratamiento no encontrado.');
        }

        $actual = (int) $tratamiento->etapa_id;

        if ($actual >= 8) {
            return back()->with('error', 'No se puede avanzar más la etapa.');
        }

        $nuevoId = $actual + 1;

        $updateData = [
            'etapa_id' => $nuevoId,
            'updated_at' => now(),
        ];

        // Si la etapa actual es "Monitoreos", limpiar fechas
        if ($actual === 3) { // 3 = Monitoreos
            $updateData['fecha_sugerida_inicio'] = null;
            $updateData['fecha_sugerida_fin'] = null;
        }

        DB::table('tratamientos')->where('id', $id)->update($updateData);

        return back()->with('success', 'Etapa actualizada a: ' . $this->etapas[$nuevoId]);
    }




    public function retrocederEtapa($id)
    {
        $tratamiento = DB::table('tratamientos')->where('id', $id)->first();

        if (!$tratamiento) {
            return back()->with('error', 'Tratamiento no encontrado.');
        }

        $actual = (int) $tratamiento->etapa_id;

        if ($actual <= 1) {
            return back()->with('error', 'No se puede retroceder más la etapa.');
        }

        $nuevoId = $actual - 1;

        DB::table('tratamientos')->where('id', $id)->update([
            'etapa_id' => $nuevoId,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Etapa actualizada a: ' . $this->etapas[$nuevoId]);
    }

    //POST TRANSFERENCIA


    public function agendarConsulta(Request $request, $id)
    {
        $fechaHoy = date('Y-m-d');
        $fechaInicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fechaFin = date('Y-m-d', strtotime($request->fecha_fin));

        // Validaciones
        if ($fechaInicio < $fechaHoy) {
            return redirect()->back()->with('error', 'La fecha de inicio no puede ser anterior a hoy.');
        }

        if ($fechaInicio > $fechaFin) {
            return redirect()->back()->with('error', 'La fecha de inicio no puede ser mayor que la fecha de fin.');
        }

        $tratamiento = DB::table('tratamientos')->where('id', $id)->update([
            'fecha_sugerida_inicio' => $fechaInicio,
            'fecha_sugerida_fin' => $fechaFin,
            'updated_at' => now(),
        ]);
        $trat = Tratamiento::findOrFail($id);
        $user = $trat->historiaClinica->paciente;
        $nombre = session('nombre');
        $apellido = session('apellido');
        $mailController = new MailController();

        $mailController->enviarMail(
            [$user->mail],
            'Dias sugeridos para tu consulta',
            'mails.horariosSugeridos',
            [
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'nombre' => $nombre,
                'apellido' => $apellido
            ]
        );


        return redirect()->back()->with('success', 'Consulta agendada correctamente.');
    }

    public function notificarTransferencia(Request $request, $id)
    {

        $trat = Tratamiento::findOrFail($id);
        $user = $trat->historiaClinica->paciente;
        $nombre = session('nombre');
        $apellido = session('apellido');
        $mailController = new MailController();

        $mailController->enviarMail(
            [$user->mail],
            'Estado actual del tratamiento',
            'mails.notificarTransferencia',
            [
                'nombre' => $nombre,
                'apellido' => $apellido
            ]
        );


        return redirect()->back()->with('success', 'Aviso enviado correctamente.');
    }



    //POST TRANSFERENCIA

    public function postTransferenciaForm($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);

        // Si ya existe, lo traemos. Si no, generamos uno vacío.
        $post = PostTransferencia::where('tratamiento_id', $id)->first();

        return view('medico.post-transferencia', compact('tratamiento', 'post'));
    }

    public function guardarPostTransferencia(Request $request, $id)
    {
        $post = PostTransferencia::firstOrNew(['tratamiento_id' => $id]);

        // Validaciones simples según etapa
        $rules = [
            'beta' => 'nullable|numeric',
            'saco' => 'nullable|boolean',
            'embarazo' => 'nullable|boolean',
            'vivo' => 'nullable|boolean',
            'fecha_nacimiento' => 'nullable|date',
            'causa_no_nacido' => 'nullable|string|max:255',
        ];

        $validated = $request->validate($rules);

        // Guardar
        $post->fill($validated);
        $post->save();

        return redirect()->back()->with('success', 'Datos guardados correctamente');
    }

    public function darDeBajaTratamiento(Request $request, $id)
    {
        try {
            // Obtener el ID del tratamiento desde el request (input hidden)
            $tratamientoId = $request->input('tratamiento_id', $id);

            // Verificar que el tratamiento existe y pertenece al médico logueado
            $medicoId = session('user_id');

            $tratamiento = DB::table('tratamientos')
                ->where('id', $tratamientoId)
                ->where('medico_id', $medicoId)
                ->first();

            if (!$tratamiento) {
                Log::error('Tratamiento no encontrado. ID Tratamiento: ' . $tratamientoId . ', ID Médico: ' . $medicoId);
                return redirect()->back()->with('error', 'Tratamiento no encontrado o no autorizado.');
            }

            // Verificar que el tratamiento esté activo (estado_tratamiento_id = 1)
            if ($tratamiento->estado_tratamiento_id != 1) {
                return redirect()->back()->with('error', 'Solo se pueden cancelar tratamientos activos.');
            }

            // Cambiar estado a "Cancelado" (ID = 3)
            DB::table('tratamientos')
                ->where('id', $tratamientoId)
                ->update([
                    'estado_tratamiento_id' => 3, // Cancelado
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Tratamiento cancelado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al cancelar tratamiento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error interno al cancelar el tratamiento.');
        }
    }

    public function marcarTurnoAtendido($pacienteId)
    {
        try {
            // Verificar que el paciente existe
            $paciente = User::findOrFail($pacienteId);

            // Verificar que el paciente tenga un turno hoy
            if (!$paciente->proximo_turno || !\Carbon\Carbon::parse($paciente->proximo_turno)->isToday()) {
                return redirect()->back()->with('error', 'El paciente no tiene un turno programado para hoy.');
            }

            // Actualizar el campo proximo_turno a null
            DB::table('usuarios')
                ->where('id', $pacienteId)
                ->update([
                    'proximo_turno' => null,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Turno marcado como atendido correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al marcar turno como atendido: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la solicitud.');
        }
    }
}
