<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MailController extends Controller
{
    //

    public function enviarMail($toEmail, $subject, $bladeTemplate, $data = []){
        try {
            Log::info('Iniciando Envio de mail');
            
            $htmlBody = view($bladeTemplate, $data)->render();
            
            $response = Http::post('https://mvvuegssraetbyzeifov.supabase.co/functions/v1/send_email_v2', [
                'group' => 5,
                'toEmails' => $toEmail,
                'subject' => $subject,
                'htmlBody' => $htmlBody,
            ]);
           
            if ($response->successful()) {
            $data = $response->json();
            Log::info('Respuesta exitosa del envío de email:', $data);
            } else {
                Log::error('Error al enviar mail - Status: ' . $response->status() . ' - Body: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar mail: ' . $e->getMessage());
            return false;
        }
    }

}
