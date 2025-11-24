<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    // URL de la API del chatbot
    protected $apiUrl = 'https://talfxkyomlmfzbumscdm.supabase.co/functions/v1/fertility-chat';
    
    // Clave secreta 
    protected $secretKey = 'sb_secret_4WooxMVhsK9iatg_nz916A_pekESoxM'; 

    public function sendMessage(Request $request)
    {
        Log::info("LLEGO AL CONTROLADOR", $request->all());

        // Validar mensaje
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Obtener usuario autenticado
        $user = Auth::user();

        Log::info("AUTH USER:", ['user' => Auth::user()]);

        if (!$user) {
            Log::error("No hay usuario autenticado");
            return response()->json(['error' => 'No autenticado'], 401);
        }

        // Historial guardado en sesión
        $messages = Session::get('chat_history', []);

        // Mensaje del usuario
        $userMessage = [
            "role" => "user",
            "parts" => [
                ["text" => $request->input('message')]
            ]
        ];

        // Agregarlo al historial
        $messages[] = $userMessage;

        // Datos del paciente
        $patientData = [
            "patientId" => $user->id,
            "patientName" => $user->nombre ?? "no especificado",
            "birthDate" => $user->fecha_nacimiento ?? "1990-01-01",
            "gender" => $user->gender ?? "No especificado", //no tenemos genero definido en la db creo 
            "messages" => $messages
        ];

        Log::info("JSON enviado a la API:", $patientData);

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, $patientData);

            Log::info("Chatbot API Status:", ['status' => $response->status()]);
            Log::info("Chatbot API Response Body:", $response->json());

            $response->throw();

            $botResponse = $response->json();

            // Guardar respuesta del bot
            $modelMessage = [
                "role" => "model",
                "parts" => [
                    ["text" => $botResponse['respuesta']]
                ]
            ];

            Session::put('chat_history', array_merge($messages, [$modelMessage]));

            return response()->json([
                "respuesta" => $botResponse['respuesta']
            ]);

        } catch (\Throwable $e) {

            Log::error("Chatbot ERROR:", [
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ]);

            return response()->json([
                "error" => "Hubo un error interno. Revisar logs."
            ], 500);
        }
    }
    
}
