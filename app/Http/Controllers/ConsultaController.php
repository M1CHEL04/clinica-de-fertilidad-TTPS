<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsultaController extends Controller
{
    public function create()
    {

        $objetivos = \App\Models\Objetivo::all();
        return view('medico.primerConsulta', compact('objetivos'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
