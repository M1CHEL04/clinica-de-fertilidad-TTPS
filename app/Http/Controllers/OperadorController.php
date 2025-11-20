<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Puncion;
use App\Models\Ovocito;
use App\Models\User;
use App\Models\EstadoOvocito;
use App\Models\Guardado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\HistorialOvocito;
use App\Models\TipoEstadoOvocito;

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
        HistorialOvocito::create([
            'ovocito_id'        => $ovocito->id,
            'estado_ovocito_id' => $estado->id,
            'accion'            => 'Estado inicial',
            'descripcion'       => 'Estado inicial: ' . $ovo['estado_inicial'],
            'usuario_id'        => $rol,
        ]);

        // Maduración
        if (!empty($ovo['tiempo_maduracion'])) {
            HistorialOvocito::create([
                'ovocito_id'        => $ovocito->id,
                'estado_ovocito_id' => $estado->id,
                'accion'            => 'Maduración',
                'descripcion'       => 'Tiempo de maduración: ' . $ovo['tiempo_maduracion'] . ' horas.',
                'usuario_id'        => $rol,
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
                    'accion'            => 'Criopreservación',
                    'descripcion'       => 'El ovocito ha sido criopreservado.',
                    'usuario_id'        => $rol,
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




}

