<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TerminosController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        if (strlen($query) < 3) {
            return response()->json([
                'rows' => [],
                'total_count' => 0
            ], 400);
        }

        $response = Http::withoutVerifying()->get(
            'https://stqzgokdxgpqjinrmpfu.supabase.co/functions/v1/search_terminos',
            [
                'q' => $query,
                'page' => $page,
                'limit' => $limit,
            ]
        );


        return $response->json();
    }
}
