<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsultaController extends Controller
{
    public function create($paciente_id)
    {
        $objetivos = \App\Models\Objetivo::all();

        
        $paciente = \App\Models\User::findOrFail($paciente_id);

        
        $historia = \App\Models\HistoriaClinica::firstOrCreate([
            'paciente_id' => $paciente_id
        ]);

        return view('medico.primerConsulta', compact('objetivos', 'paciente', 'historia'));
    }


    public function store(Request $request)
{
    $paciente = \App\Models\User::findOrFail($request->paciente_id);

    
    $tratamiento = $paciente->historiasClinicas()
        ->with(['tratamientos.estadoTratamiento'])
        ->get()
        ->flatMap(fn($historia) => $historia->tratamientos)
        ->first(fn($t) =>
            $t->estadoTratamiento &&
            strtolower($t->estadoTratamiento->nombre) == 'activo'
        );

    if (!$tratamiento) {
        return back()->with('error', 'El paciente no tiene un tratamiento activo.');
    }

  

    $tratamiento->antecedentesPersonales()->create([
    'fuma'               => $request->fuma === 'si' ? 1 : 0,
    'cuanto_fuma'        => $request->cantidad,
    'alcohol'            => $request->alcohol === 'si' ? 1 : 0,
    'frecuencia_alcohol' => $request->frecuencia,
    'bebida_alcohol'     => $request->input('bebida-alcohol'),
    'droga'              => $request->droga === 'si' ? 1 : 0,
    'observaciones'      => $request->observaciones,
    'antecedentes'       => json_encode($request->antecedentes ?? []),
    ]);





        if ($request->filled('familiares')) {
        foreach ($request->familiares as $fam) {
            // Saltar si no hay parentesco
            if (empty($fam['parentesco'])) continue;

            // Obtener array de patologías (o dejar vacío)
            $patologias = $fam['antecedentes'] ?? [];

            \App\Models\AntecedenteFamiliar::create([
                'tratamiento_id' => $tratamiento->id,
                'patologias_familiares' => $fam['parentesco'], // ej "Madre"
                'patologias' => $patologias, // se guardará como JSON gracias a $casts
            ]);
        }
    }





    $tratamiento->antecedentesGinecologicos()->create([
        'ciclo_regular' => $request->ciclos === 'regular' ? 1 : 0,              
        'duracion'               => $request->duracion,              
        'caracteristicas_sangrado' => $request->caracteristica,
        'edad_menarca'           => $request->menarca,
        'G'                      => $request->embarazos,
        'P'                      => $request->partos,
        'AB'                     => $request->abortos,
        'CT'                     => $request->ectopicos,
        'examen_fisico'          => $request->examen_fisico,
    ]);



    $tratamiento->antecedentesFenotipos()->create([
        'color_ojos'        => $request->input('color-ojos'),
        'tipo_pelo'         => $request->input('tipo-pelo'),
        'altura'            => $request->altura,
        'complexion_corporal' => $request->complexion,
        'rasgos_etnicos'    => $request->input('rasgos-etnicos'),
    ]);
    if ($request->filled('objetivo')) {
            $tratamiento->update([
                'objetivo_id' => $request->objetivo
            ]);
        }
switch ($request->objetivo) {

    case 1: // Hombre – Gametos propios
        $antecedentePareja = $tratamiento->antecedentesPareja()->create([
            'tratamiento_id' => $tratamiento->id,
            'dni' => $request->dni,
            'antecedentes_personales' => json_encode($request->hombre_antecedentes_personales ?? []),
            'descripcion_familiar' => json_encode($request->hombre_familiares ?? []),
            'examen_fisico' => $request->hombre_genitales,
            'color_ojos' => $request->hombre_ojos,
            'color_pelo' => $request->hombre_pelo,
            'tipo_pelo' => $request->hombre_tipo_pelo,
            'altura' => $request->hombre_altura,
            'complexion_corporal' => $request->hombre_complexion,
            'rasgos_etnicos' => $request->hombre_etnia,
        ]);

        // Guardar familiares individualmente
        foreach ($request->hombre_familiares ?? [] as $fam) {
            if (empty($fam['parentesco'])) continue;

            \App\Models\AntecedenteFamiliar::create([
                'antecedente_pareja_id' => $antecedentePareja->id,
                'patologias_familiares' => $fam['parentesco'],
                'patologias' => json_encode($fam['antecedentes'] ?? []),
            ]);
        }
        break;

    case 2: // Hombre – Semen donado
        $tratamiento->antecedentesPareja()->create([
            'tratamiento_id' => $tratamiento->id,
            'color_ojos' => $request->hombre_ojos,
            'color_pelo' => $request->hombre_pelo,
            'tipo_pelo' => $request->hombre_tipo_pelo,
            'altura' => $request->hombre_altura,
            'complexion_corporal' => $request->hombre_complexion,
            'rasgos_etnicos' => $request->hombre_etnia,
        ]);
        // No hay familiares para este caso
        break;

    case 3: // Pareja mujer – ROPA
        $antecedentePareja = $tratamiento->antecedentesPareja()->create([
            'tratamiento_id' => $tratamiento->id,
            'dni' => $request->dni,
            'antecedentes_personales' => json_encode($request->pareja_antecedentes_personales ?? []),
            'descripcion_familiar' => json_encode($request->pareja_familiares ?? []),
            'ciclo_regular' => $request->pareja_ciclos,
            'duracion' => $request->pareja_duracion,
            'caracteristicas_sangrado' => $request->pareja_caracteristica,
            'G' => $request->pareja_g,
            'P' => $request->pareja_p,
            'AB' => $request->pareja_ab,
            'CT' => $request->pareja_ct,
            'color_ojos' => $request->pareja_ojos,
            'color_pelo' => $request->pareja_pelo,
            'tipo_pelo' => $request->pareja_tipo_pelo,
            'altura' => $request->pareja_altura,
            'complexion_corporal' => $request->pareja_complexion,
            'rasgos_etnicos' => $request->pareja_etnia,
        ]);

        // Guardar familiares individualmente
        foreach ($request->pareja_familiares ?? [] as $fam) {
            if (empty($fam['parentesco'])) continue;

            \App\Models\AntecedenteFamiliar::create([
                'antecedente_pareja_id' => $antecedentePareja->id,
                'patologias_familiares' => $fam['parentesco'],
                'patologias' => json_encode($fam['antecedentes'] ?? []),
            ]);
        }
        break;
}


    $tratamiento->update(['etapa_id' => 1]);

    return redirect()
    ->route('medico.tratamiento.detalle', $tratamiento->id)
    ->with('success', 'Antecedentes guardados correctamente.');

}


}
