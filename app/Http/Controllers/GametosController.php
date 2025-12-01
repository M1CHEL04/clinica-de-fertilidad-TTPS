<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GametosController extends Controller
{
    public function nuevaDonacion()
    {
        // Llamada a la API
        $response = Http::get("https://omtalaimckjolwtkgqjw.supabase.co/functions/v1/fenotipos");

        if ($response->failed()) {
            return back()->with('error', 'No se pudieron cargar las opciones del formulario.');
        }

        $data = $response->json();

        // Extraemos directamente los values
        $enums = [
            'eye_color'   => $data['enums']['eye_color']['values'],
            'hair_color'  => $data['enums']['hair_color']['values'],
            'hair_type'   => $data['enums']['hair_type']['values'],
            'complexion'  => $data['enums']['complexion']['values'],
            'ethnicity'   => $data['enums']['ethnicity']['values'],
            'gamete_type' => $data['enums']['gamete_type']['values'],
        ];

        return view('operador.gametosDonacion', compact('enums'));
    }

    public function registrar(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'tipo_donacion' => 'required|in:esperma,ovocito',
            'color_ojos' => 'required|string',
            'color_pelo' => 'required|string',
            'tipo_pelo' => 'required|string',
            'altura' => 'required|integer|min:0|max:250',
            'complexion' => 'required|string',
            'etnicidad' => 'required|string',
        ]);

        // Payload base para donación
        $payloadDonation = [
            "group_number" => 5,
            "type" => $validated['tipo_donacion'],
            "phenotype" => [
                "eye_color"   => $validated['color_ojos'],
                "hair_color"  => $validated['color_pelo'],
                "hair_type"   => $validated['tipo_pelo'],
                "height"   => intval($validated['altura']),
                "complexion"  => $validated['complexion'],
                "ethnicity"   => $validated['etnicidad'],
            ]
        ];

        //1) Intentar registrar la donación
        $response = Http::post(
            'https://omtalaimckjolwtkgqjw.supabase.co/functions/v1/gametos-donacion',
            $payloadDonation
        );
        

        // Si funciona al primer intento → perfecto
        if ($response->successful()) {
            return redirect()->route('donacion.nueva')
                ->with('success', 'Donación registrada exitosamente.');
        }

        // 2) Si devuelve 404 = NO HAY RACKS
        if ($response->status() === 404) {

            // Crear los tanques (con racks)
            $payloadTanque = [
                "group_number" => 5,
                "type" => $validated['tipo_donacion'],
                "rack_count" => 10 // por ahora que cree de a 10 racks
            ];

            $crearTanque = Http::post(
                'https://omtalaimckjolwtkgqjw.supabase.co/functions/v1/tanques',
                $payloadTanque
            );

            // Si falló crear tanques → error real
            if (!$crearTanque->successful()) {
                return back()->with('error', 'No se pudieron generar los racks para esta donación.')->withInput();
            }

            // 👉 3) Reintentar la donación ahora que existen racks
            $retry = Http::post(
                'https://omtalaimckjolwtkgqjw.supabase.co/functions/v1/gametos-donacion',
                $payloadDonation
            );

            if ($retry->successful()) {
                return redirect()->route('donacion.nueva')
                    ->with('success', 'Donación registrada exitosamente (racks generados automáticamente).');
            }

            return back()->with('error', 'Hubo un error al registrar la donación luego de generar los racks.')
                        ->withInput();
        }

        // 👉 Error inesperado
        return back()->with('error', 'Error inesperado al registrar la donación.')->withInput();
    }

}
