<?php

namespace App\Http\Controllers;

use App\Models\ColorOjo;
use App\Models\ColorPelo;
use App\Models\Complexion;
use App\Models\RasgoEtnico;
use App\Models\TipoPelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsultaController extends Controller
{
    public function create($paciente_id)
    {
        $objetivos = \App\Models\Objetivo::all();

        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();


        $paciente = \App\Models\User::findOrFail($paciente_id);

        // Buscar tratamiento ACTIVO sin objetivo_id
        $tratamientoActivo = \App\Models\Tratamiento::whereHas('historiaClinica', function ($q) use ($paciente_id) {
            $q->where('paciente_id', $paciente_id);
        })
            ->whereHas('estadoTratamiento', function ($q) {
                $q->whereRaw('LOWER(nombre) = "activo"');
            })
            ->whereNull('objetivo_id')
            ->first();

        if (!$tratamientoActivo) {
            return redirect()
                ->back()
                ->with('error', 'El paciente no tiene un tratamiento activo sin objetivo asignado.');
        }

        // Crear o recuperar historia clínica
        $historia = \App\Models\HistoriaClinica::firstOrCreate([
            'paciente_id' => $paciente_id
        ]);
        $tratamiento = null;
       
        if(session('rol') == 5){
            return view('jefe.primerConsulta', compact(
                'objetivos',
            'paciente',
            'historia',
            'tratamiento',
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
            ));
        }
        else {
        return view('medico.primerConsulta', compact(
            'objetivos',
            'paciente',
            'historia',
            'tratamiento',
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    }
    }



    public function ver($paciente_id)
    {
        $objetivos = \App\Models\Objetivo::all();
        $paciente = \App\Models\User::findOrFail($paciente_id);

        // 🔍 Buscar TRATAMIENTO ACTIVO que YA TIENE objetivo asignado
        $tratamiento = \App\Models\Tratamiento::whereHas('historiaClinica', function ($q) use ($paciente_id) {
            $q->where('paciente_id', $paciente_id);
        })
            ->whereHas('estadoTratamiento', function ($q) {
                $q->whereRaw('LOWER(nombre) = "activo"');
            })
            ->whereNotNull('objetivo_id')
            ->with([
                'antecedentesPersonales',
                'antecedentesGinecologicos',
                'antecedentesFenotipos',
                'antecedentesFamiliares',
                'antecedentesPareja',
                'antecedentesGenitales',
                'estadoTratamiento',
                'objetivo'
            ])
            ->first();


        if (!$tratamiento) {
            return redirect()
                ->back()
                ->with('error', 'El paciente no tiene un tratamiento activo con datos ya cargados.');
        }

        // Recuperar historia clínica solo para mostrar
        $historia = \App\Models\HistoriaClinica::firstOrCreate([
            'paciente_id' => $paciente_id
        ]);

        // Variables necesarias para el formulario
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        //dd($tratamiento);

        if(session('rol') == 5){
            return view('jefe.primerConsulta', compact(
                'objetivos',
                'paciente',
                'historia',
                'tratamiento',
                'coloresPelo',
                'coloresOjos',
                'tipoPelo',
                'complexiones',
                'rasgos'
            ));
        }
        else {
            return view('medico.primerConsulta', compact(
                'objetivos',
                'paciente',
                'historia',
                'tratamiento',
                'coloresPelo',
                'coloresOjos',
                'tipoPelo',
                'complexiones',
                'rasgos'
            ));
        }
    }




    public function store(Request $request)
    {

        $paciente = \App\Models\User::findOrFail($request->paciente_id);

        // Obtener el tratamiento activo
        $tratamiento = $paciente->historiasClinicas()
            ->with(['tratamientos.estadoTratamiento'])
            ->get()
            ->flatMap(fn($historia) => $historia->tratamientos)
            ->first(
                fn($t) =>
                $t->estadoTratamiento &&
                    strtolower($t->estadoTratamiento->nombre) == 'activo'
            );

        if (!$tratamiento) {
            return back()->with('error', 'El paciente no tiene un tratamiento activo.');
        }
        // dd($request->all());
        // --- ANTECEDENTES PERSONALES ---
        
        $antecedentesPersonales = $tratamiento->antecedentesPersonales()->firstOrNew([]);
        $antecedentesPersonales->fill([
            'fuma'               => $request->fuma === 'si' ? 1 : 0,
            'cuanto_fuma'        => $request->cantidad,
            'alcohol'            => $request->alcohol === 'si' ? 1 : 0,
            'frecuencia_alcohol' => $request->frecuencia,
            'bebida_alcohol'     => $request->input('bebida-alcohol'),
            'droga'              => $request->droga === 'si' ? 1 : 0,
            'observaciones'      => $request->observaciones,
            'antecedentes'       => json_encode($request->antecedentes ?? []),
        ]);
        $antecedentesPersonales->tratamiento_id = $tratamiento->id;
        $antecedentesPersonales->save();

        // --- ANTECEDENTES FAMILIARES (paciente) ---
        if ($request->filled('familiares')) {
            foreach ($request->familiares as $fam) {
                if (empty($fam['parentesco'])) continue;

                $patologias = $fam['antecedentes'] ?? [];
                if (is_string($patologias)) $patologias = json_decode($patologias, true) ?? [];

                \App\Models\AntecedenteFamiliar::updateOrCreate(
                    [
                        'tratamiento_id' => $tratamiento->id,
                        'patologias_familiares' => $fam['parentesco'],
                    ],
                    [
                        'patologias' => json_encode($patologias),
                    ]
                );
            }
        }


        // --- ANTECEDENTES GINECOLÓGICOS ---
        $antecedentesGinecologicos = $tratamiento->antecedentesGinecologicos()->firstOrNew([]);
        $antecedentesGinecologicos->fill([
            'ciclo_regular' => $request->ciclos === 'regular' ? 1 : 0,
            'duracion' => $request->duracion,
            'caracteristicas_sangrado' => $request->caracteristica,
            'edad_menarca' => $request->menarca,
            'G' => $request->embarazos,
            'P' => $request->partos,
            'AB' => $request->abortos,
            'CT' => $request->ectopicos,
            'examen_fisico' => $request->examen_fisico,
        ]);
        $antecedentesGinecologicos->tratamiento_id = $tratamiento->id;
        $antecedentesGinecologicos->save();

        // --- ANTECEDENTES FENOTIPOS ---
        $antecedentesFenotipos = $tratamiento->antecedentesFenotipos()->firstOrNew([]);


        $antecedentesFenotipos->fill([
            'color_ojos' => $request->input('color-ojos'),
            'color_pelo' => $request->input('color-pelo'),
            'tipo_pelo' => $request->input('tipo-pelo'),
            'altura' => $request->altura,
            'complexion_corporal' => $request->complexion,
            'rasgos_etnicos' => $request->input('rasgos-etnicos'),
        ]);
        $antecedentesFenotipos->tratamiento_id = $tratamiento->id;
        $antecedentesFenotipos->save();

        // --- OBJETIVO ---
        if ($request->filled('objetivo')) {
            $tratamiento->update(['objetivo_id' => $request->objetivo]);
        }

        // --- ANTECEDENTES PAREJA ---
        if (in_array($request->objetivo, [1, 3])) {
            $antecedentePareja = $tratamiento->antecedentesPareja()->firstOrNew([]);
            $antecedentePareja->tratamiento_id = $tratamiento->id;

            // Dependiendo del objetivo, usar los campos correspondientes
            if ($request->objetivo == 1) { // Hombre – Gametos propios
                $antecedentePareja->fill([
                    'dni' => $request->p_dni,
                    'antecedentes_personales' => json_encode($request->p_antecedentes_personales ?? []),
                    'descripcion_familiar' => json_encode($request->p_familiares ?? []),
                    'examen_fisico' => $request->p_genitales,
                    'color_ojos' => $request->p_color_ojos,
                    'color_pelo' => $request->p_color_pelo,
                    'tipo_pelo' => $request->p_tipo_pelo,
                    'altura' => $request->p_altura,
                    'complexion_corporal' => $request->p_complexion,
                    'rasgos_etnicos' => $request->p_rasgos_etnicos,
                ]);
                $antecedentePareja->save();


                // Guardar familiares de la pareja
                foreach ($request->hombre_familiares ?? [] as $fam) {
                    if (empty($fam['parentesco'])) continue;

                    $patologias = $fam['antecedentes'] ?? [];
                    if (is_string($patologias)) $patologias = json_decode($patologias, true) ?? [];

                    \App\Models\AntecedenteFamiliar::updateOrCreate(
                        [
                            'tratamiento_id' => $tratamiento->id,
                            'patologias_familiares' => $fam['parentesco'],
                            'antecedente_pareja_id' => $antecedentePareja->id,
                        ],
                        ['patologias' => json_encode($patologias)]
                    );
                }
            } elseif ($request->objetivo == 3) { // Pareja mujer – ROPA
                $antecedentePareja->fill([
                    'dni' => $request->p_dni,
                    'antecedentes_personales' => json_encode($request->p_antecedentes_personales ?? []),
                    'descripcion_familiar' => json_encode($request->p_familiares ?? []),
                    'ciclo_regular' => $request->p_ciclo_regular,
                    'duracion' => $request->p_duracion,
                    'caracteristicas_sangrado' => $request->p_caracteristicas_sangrado,
                    'G' => $request->p_g,
                    'P' => $request->p_p,
                    'AB' => $request->p_ab,
                    'CT' => $request->p_ct,
                    'color_ojos' => $request->p_color_ojos,
                    'color_pelo' => $request->p_color_pelo,
                    'tipo_pelo' => $request->p_tipo_pelo,
                    'altura' => $request->p_altura,
                    'complexion_corporal' => $request->p_complexion,
                    'rasgos_etnicos' => $request->p_etnia,
                ]);
                $antecedentePareja->save();

                // Guardar familiares de la pareja
                foreach ($request->pareja_familiares ?? [] as $fam) {
                    if (empty($fam['parentesco'])) continue;

                    $patologias = $fam['antecedentes'] ?? [];
                    if (is_string($patologias)) $patologias = json_decode($patologias, true) ?? [];

                    \App\Models\AntecedenteFamiliar::updateOrCreate(
                        [
                            'tratamiento_id' => $tratamiento->id,
                            'patologias_familiares' => $fam['parentesco'],
                            'antecedente_pareja_id' => $antecedentePareja->id,
                        ],
                        ['patologias' => json_encode($patologias)]
                    );
                }
            }

            // Guardamos cambios en la pareja
            $antecedentePareja->save();
        } elseif ($request->objetivo == 2) { // Hombre – Semen donado
            $tratamiento->antecedentesPareja()->firstOrCreate([
                'tratamiento_id' => $tratamiento->id,
                'color_ojos' => $request->p_color_ojos,
                'color_pelo' => $request->p_color_pelo,
                'tipo_pelo' => $request->p_tipo_pelo,
                'altura' => $request->p_altura,
                'complexion_corporal' => $request->p_complexion,
                'rasgos_etnicos' => $request->p_etnia,
            ]);
        }
        if ($request->filled('p_antecedentes_genitales')) {
            \App\Models\AntecedenteGenital::updateOrCreate(
                ['tratamiento_id' => $tratamiento->id],
                ['observacion' => $request->p_antecedentes_genitales]
            );
        }
        $tratamiento->update(['etapa_id' => 1]);
        if (session('rol') == 5){
            return redirect()
            ->route('jefe.tratamiento.detalle', $tratamiento->id)
            ->with('success', 'Antecedentes guardados correctamente.');
        }
        else {
        return redirect()
            ->route('medico.tratamiento.detalle', $tratamiento->id)
            ->with('success', 'Antecedentes guardados correctamente.');
        }
    }



    public function update(Request $request, $tratamiento_id)
    {
        //dd($request->p_complexion);
        // dd($request->all());
        $tratamiento = \App\Models\Tratamiento::with([
            'antecedentesPersonales',
            'antecedentesFamiliares',
            'antecedentesGinecologicos',
            'antecedentesFenotipos',
            'antecedentesPareja'
        ])->findOrFail($tratamiento_id);

        // --- ANTECEDENTES PERSONALES ---
        if ($tratamiento->antecedentesPersonales) {
            $tratamiento->antecedentesPersonales->update([
                'fuma'               => $request->fuma === 'si' ? 1 : 0,
                'cuanto_fuma'        => $request->cantidad,
                'alcohol'            => $request->alcohol === 'si' ? 1 : 0,
                'frecuencia_alcohol' => $request->frecuencia,
                'bebida_alcohol'     => $request->input('bebida-alcohol'),
                'droga'              => $request->droga === 'si' ? 1 : 0,
                'observaciones'      => $request->observaciones,
                'antecedentes'       => json_encode($request->antecedentes ?? []),
            ]);
        }

        // --- ANTECEDENTES FAMILIARES ---
        if ($request->filled('familiares')) {
            foreach ($request->familiares as $fam) {
                if (empty($fam['parentesco'])) continue;

                // Buscamos por tratamiento + parentesco
                \App\Models\AntecedenteFamiliar::updateOrCreate(
                    [
                        'tratamiento_id' => $tratamiento->id,
                        'patologias_familiares' => $fam['parentesco'],
                    ],
                    [
                        'patologias' => json_encode($fam['antecedentes'] ?? []),
                    ]
                );
            }
        }


        // --- ANTECEDENTES GINECOLÓGICOS ---
        if ($tratamiento->antecedentesGinecologicos) {
            $tratamiento->antecedentesGinecologicos->update([
                'ciclo_regular' => $request->ciclos === 'regular' ? 1 : 0,
                'duracion' => $request->duracion,
                'caracteristicas_sangrado' => $request->caracteristica,
                'edad_menarca' => $request->menarca,
                'G' => $request->embarazos,
                'P' => $request->partos,
                'AB' => $request->abortos,
                'CT' => $request->ectopicos,
                'examen_fisico' => $request->examen_fisico,
            ]);
        }

        // --- ANTECEDENTES FENOTIPOS ---
        if ($tratamiento->antecedentesFenotipos) {
            $tratamiento->antecedentesFenotipos->update([
                'color_ojos' => $request->input('color-ojos'),
                'color_pelo' => $request->input('color-pelo'),
                'tipo_pelo' => $request->input('tipo-pelo'),
                'altura' => $request->altura,
                'complexion_corporal' => $request->complexion,
                'rasgos_etnicos' => $request->input('rasgos-etnicos'),
            ]);
        }

        // --- OBJETIVO ---
        if ($request->filled('objetivo')) {
            $tratamiento->update(['objetivo_id' => $request->objetivo]);
        }

        // --- ANTECEDENTES PAREJA ---
        if (in_array($request->objetivo, [1, 3])) {
            $antecedentePareja = $tratamiento->antecedentesPareja()->firstOrNew([]);
            $antecedentePareja->tratamiento_id = $tratamiento->id;

            // Todo estandarizado con prefijo p_
            $antecedentePareja->dni = $request->p_dni;
            $antecedentePareja->antecedentes_personales = json_encode($request->p_antecedentes_personales ?? []);
            $antecedentePareja->descripcion_familiar = json_encode($request->p_familiares ?? []);
            $antecedentePareja->ciclo_regular = $request->p_ciclo_regular;
            $antecedentePareja->duracion = $request->p_duracion;
            $antecedentePareja->caracteristicas_sangrado = $request->p_caracteristicas_sangrado;
            $antecedentePareja->G = $request->p_g;
            $antecedentePareja->P = $request->p_p;
            $antecedentePareja->AB = $request->p_ab;
            $antecedentePareja->CT = $request->p_ct;
            $antecedentePareja->color_ojos = $request->p_color_ojos;
            $antecedentePareja->color_pelo = $request->p_color_pelo;
            $antecedentePareja->tipo_pelo = $request->p_tipo_pelo;
            $antecedentePareja->altura = $request->p_altura;
            $antecedentePareja->complexion_corporal = $request->p_complexion;
            $antecedentePareja->rasgos_etnicos = $request->p_rasgos_etnicos;

            $antecedentePareja->save();
        }

        if ($request->filled('p_antecedentes_genitales')) {
            \App\Models\AntecedenteGenital::updateOrCreate(
                ['tratamiento_id' => $tratamiento->id],
                ['observacion' => $request->p_antecedentes_genitales]
            );
        }

      
         if (session('rol') == 5){
            return redirect()
            ->route('jefe.tratamiento.detalle', $tratamiento->id)
            ->with('success', 'Antecedentes guardados correctamente.');
        }
        else {
        return redirect()
            ->route('medico.tratamiento.detalle', $tratamiento->id)
            ->with('success', 'Antecedentes actualizados correctamente.');
    }
}
}
