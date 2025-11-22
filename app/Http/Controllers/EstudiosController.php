<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Estudio;
use App\Models\Tratamiento;

class EstudiosController extends Controller
{
    public function estudios($paciente_id)
    {
        // Obtener paciente
        $paciente = \App\Models\User::findOrFail($paciente_id);

        // Si necesitás la historia clínica (opcional)
        $historia = \App\Models\HistoriaClinica::firstOrCreate([
            'paciente_id' => $paciente_id
        ]);

        $ginecologicos = Http::withoutVerifying()
            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/estudio_ginecologico")
            ->json();

        $hormonales = Http::withoutVerifying()
            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/estudio_hormonales")
            ->json();

        $prequirurgicos = Http::withoutVerifying()
            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/get-orden-estudio-prequirurgico")
            ->json();

        $semen = Http::withoutVerifying()
            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/estudio_semen")
            ->json();

        return view('medico.estudios', compact(
            'paciente',
            'ginecologicos',
            'hormonales',
            'prequirurgicos',
            'semen'
        ));
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

    

    

    $estudios_guardados = [];
    $tipos = ['ginecologicos', 'hormonales', 'prequirurgicos', 'semen'];

    foreach ($tipos as $tipo) {
        if ($request->has($tipo)) {
            foreach ($request->input($tipo) as $estudio_id) {
                // Obtener el nombre real del estudio desde la API correspondiente
                switch ($tipo) {
                    case 'ginecologicos':
                        $estudio_data = Http::withoutVerifying()
                            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/estudio_ginecologico")
                            ->json();
                        break;
                    case 'hormonales':
                        $estudio_data = Http::withoutVerifying()
                            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/estudio_hormonales")
                            ->json();
                        break;
                    case 'prequirurgicos':
                        $estudio_data = Http::withoutVerifying()
                            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/get-orden-estudio-prequirurgico")
                            ->json();
                        break;
                    case 'semen':
                        $estudio_data = Http::withoutVerifying()
                            ->get("https://srlgceodssgoifgosyoh.supabase.co/functions/v1/estudio_semen")
                            ->json();
                        break;
                }

                // Buscar el nombre del estudio por id
                $nombre_estudio = collect($estudio_data)->firstWhere('id', $estudio_id)['nombre'] ?? $estudio_id;

                $estudio = Estudio::create([
                    'tratamiento_id' => $tratamiento->id,
                    'tipo_estudio' => $tipo,
                    'nombre' => $nombre_estudio,
                    'resultado' => null,
                ]);

                $estudios_guardados[] = $estudio;
            }
        }
    }

    // Construir HTML del mail
    $html = "<h2 style='color:#2563eb'>Estudios Médicos</h2>";
    $html .= "<p>Estimado/a {$tratamiento->nombre} {$tratamiento->apellido},</p>";
    $html .= "<p>Se han registrado los siguientes estudios:</p><ul>";

    foreach ($estudios_guardados as $est) {
        
        $html .= "<li>{$est->tipo_estudio} - {$est->nombre} </li>";
    }

    $html .= "</ul><p>Saludos cordiales,<br>Fertilia</p>";

    // Enviar por la API
    $payload = [
        "group" => 5,
        "toEmails" => [$paciente->mail],
        "subject" => "Estudios Médicos - {$paciente->nombre} {$paciente->apellido}",
        "htmlBody" => $html
    ];

    $ch = curl_init("https://mvvuegssraetbyzeifov.supabase.co/functions/v1/send_email_v2");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        return back()->with('success', 'Estudios guardados y enviados al paciente correctamente.');
    }

    return back()->with('error', 'Estudios guardados, pero no se pudo enviar el mail.');
}



}
