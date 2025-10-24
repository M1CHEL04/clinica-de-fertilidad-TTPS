<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('usuario.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

        $user = User::where('mail', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Credenciales inválidas');
        }

        // Loguear usuario
        Auth::login($user);
        $request->session()->regenerate();

        // Guardar rol en sesión
        session(['rol' => $user->rol_id]);

        return redirect()->route('home')->with('success', 'Bienvenido, '.$user->nombre.'!');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }
}
