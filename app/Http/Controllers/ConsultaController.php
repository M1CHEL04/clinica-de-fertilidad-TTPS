<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    public function store(Request $request)
    {
        // Por ahora solo debug
        dd($request->all());
    }
}
