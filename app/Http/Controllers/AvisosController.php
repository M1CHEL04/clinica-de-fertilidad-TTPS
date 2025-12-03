<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tratamiento;

class AvisosController extends Controller
{
    public function enviarOrdenMedica($id)
{
    $tratamiento = Tratamiento::findOrFail($id);

    // Email del paciente
    $emailPaciente = $tratamiento->mail;

    // URL de la API externa
    $url = "https://mvvuegssraetbyzeifov.supabase.co/functions/v1/send_email_v2";

    // Lo que queremos enviar (HTML simple)
    $html = "
        <h2 style='color:#2563eb'>Orden Médica</h2>
        <p>Estimado/a {$tratamiento->nombre} {$tratamiento->apellido},</p>
        <p>El médico ha generado su orden médica correspondiente al tratamiento.</p>
        <p>Saludos cordiales,<br>Fertilia</p>
    ";

    // Armamos el payload
    $payload = [
        "group" => 5, 
        "toEmails" => ["filad48402@cexch.com"], //aca puse un mail temporal
        "subject" => "Orden Médica - ".$tratamiento->nombre." ".$tratamiento->apellido,
        "htmlBody" => $html
    ];

    // Ejecutar POST con cURL
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Si la API respondió OK
    if ($httpCode === 200) {
        return redirect()
            ->back()
            ->with('success', 'La orden médica se envió correctamente al paciente.');
    }

    return redirect()
        ->back()
        ->with('error', 'No se pudo enviar la orden médica. Intente nuevamente.');
}
}
