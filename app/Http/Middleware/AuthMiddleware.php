<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle(Request $request, Closure $next, $rol)
    {   


        $user = Auth::user();
   
        if (!$user|| strtolower($user->rol->nombre ?? '') !== strtolower($rol)) {
            return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }


}
