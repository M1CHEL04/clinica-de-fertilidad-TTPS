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
        'fuma'             => $request->fuma,               
        'cuanto_fuma'      => $request->cantidad,           
        'alcohol'          => $request->alcohol,            
        'frecuencia_alcohol' => $request->frecuencia,       
        'bebida_alcohol'   => $request->input('bebida-alcohol'),
        'droga'            => $request->droga,              
        'observaciones'    => $request->observaciones,
        'antecedentes'     => json_encode($request->antecedentes ?? []),
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
        'ciclo_regular'          => $request->ciclos,              
        'duracion'               => $request->duracion,              
        'caracteristicas_sangrado' => $request->caracteristica,
        'edad_menarca'           => $request->menarca,
        'G'                      => $request->embarazos,
        'P'                      => $request->partos,
        'AB'                     => $request->abortos,
        'CT'                     => $request->ectopicos,
        'examen_fisico'          => $request->examen_fisico,
    ]);



    $tratamiento->antecedentesFenotipo()->create([
        'color_ojos'        => $request->input('color-ojos'),
        'tipo_pelo'         => $request->input('tipo-pelo'),
        'altura'            => $request->altura,
        'complexion_corporal' => $request->complexion,
        'rasgos_etnicos'    => $request->input('rasgos-etnicos'),
    ]);



    return back()->with('success', 'Antecedentes guardados correctamente.');
}


}
