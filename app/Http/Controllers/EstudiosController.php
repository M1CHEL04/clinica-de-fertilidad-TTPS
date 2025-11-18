<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EstudiosController extends Controller
{
    public function estudios()
    {
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
            'ginecologicos',
            'hormonales',
            'prequirurgicos',
            'semen'
        ));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

}
