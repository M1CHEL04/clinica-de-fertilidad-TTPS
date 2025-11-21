<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Tratamiento;
use App\Models\Estudio;
use App\Models\ProtocoloEstimulacion;
use App\Models\TipoMedicacion;
use App\Models\Monitoreo;
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

        

        return view('medico.home', compact('pacientes', 'rol_id'));
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
                'objetivos.nombre as objetivo',
                'usuarios.id as id_usuario',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.mail',
                'usuarios.dni',
                'usuarios.fecha_nacimiento',
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

        // Estudios pendientes
        $estudiosPendientes = Estudio::where('tratamiento_id', $id)
            ->whereNull('resultado')
            ->get();

        // Estudios finalizados
        $estudiosCompletados = Estudio::where('tratamiento_id', $id)
            ->whereNotNull('resultado')
            ->get();

        return view(
            'medico.cargarEstudios',
            compact('tratamiento', 'estudiosPendientes', 'estudiosCompletados')
        );
    }

    public function guardarEstudios($id)
    {
        $resultados = request('resultados');

        foreach ($resultados as $estudioId => $resultado) {
            if (trim($resultado) !== '') {
                Estudio::where('id', $estudioId)
                    ->where('tratamiento_id', $id)
                    ->update(['resultado' => $resultado]);
            }
        }

        return redirect()
            ->route('medico.tratamiento.detalle', $id)
            ->with('success', 'Resultados cargados correctamente.');
    }

    public function protocolo($id)
    {
        $tratamiento = Tratamiento::findOrFail($id);
        $protocolo = ProtocoloEstimulacion::where('tratamiento_id', $id)->first();
        $tiposMedicacion = TipoMedicacion::all();

        return view('medico.protocolo', compact('tratamiento', 'protocolo', 'tiposMedicacion'));
    }

    public function guardarProtocolo(Request $request, $id)
    {
        $request->validate([
            'tipo_medicacion_id' => 'required|exists:tipos_medicacion,id',
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
    ]);

    Monitoreo::create([
        'tratamiento_id' => $validated['tratamiento_id'],
        'observacion'    => $validated['observacion'],
    ]);

    return back()->with('success', 'Monitoreo cargado correctamente');
}

private $etapas = [
    1 => 'Primera Consulta',
    2 => 'Segunda Consulta',
    3 => 'Monitoreos',
    4 => 'Punción',
    5 => 'Transferencia',
    6 => 'Control de embarazo',
    7 => 'Finalizado',
];


public function avanzarEtapa($id)
{
    $tratamiento = DB::table('tratamientos')->where('id', $id)->first();

    if (!$tratamiento) {
        return back()->with('error', 'Tratamiento no encontrado.');
    }

    $actual = (int) $tratamiento->etapa_id;

    if ($actual >= 7) {
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
        'nombre'=> $nombre,
        'apellido'=>$apellido
    ]
);


    return redirect()->back()->with('success', 'Consulta agendada correctamente.');
}



}
