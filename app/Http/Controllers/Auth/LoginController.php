<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Log;

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

        if ($user->cambio_password == false){
            return redirect()->route('change.password', ['email' => $user->mail])->with('info', 'Debes cambiar tu contraseña. Te hemos enviado un correo con las instrucciones.');
        }

        // Loguear usuario
        Auth::login($user);
        $request->session()->regenerate();

        // Guardar rol en sesión
        session(['rol' => $user->rol_id]);
        session(['user_id' => $user->id]);
        session(['nombre' => $user->nombre]);
        session(['apellido' => $user->apellido]);

        switch ($user->rol_id) {
            case 1:
                return redirect()->route('home') ->with('success', 'Bienvenido, '.$user->nombre.'!');
                break;
            case 2:
                return redirect()->route('medico.home') ->with('success', 'Bienvenido, '.$user->nombre.'!');
                break;
            case 3:
                return redirect()->route('operador.home') ->with('success', 'Bienvenido, '.$user->nombre.'!');
                break;
            case 4:
                return redirect()->route('admin.home') ->with('success', 'Bienvenido, '.$user->nombre.'!');
                break;
            default:
                return redirect()->route('jefe.home')->with('success', 'Bienvenido, '.$user->nombre.'!');
                break;
        }


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

    public function showChangePasswordForm($email)
    {
        // Vista para cambiar la contraseña
        $user = User::where('mail', $email)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado.');
        }

        // Retornar la vista de cambio de contraseña, pasando el usuario
        return view('auth.change-password', compact('user'));
    }

    public function updatePassword(Request $request)
{
    // Validar la nueva contraseña
    $request->validate([
        'new_password' => 'required|string|min:8',
    ]);

    // Encontrar al usuario por correo
    $user = User::where('mail', $request->email)->first();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Usuario no encontrado.');
    }

    if ($request->new_password != $request->new_password_confirmation){
        return redirect()->back()->with('error', 'La Contraseñas ingresadas no coinciden');
    }

    // Actualizar la contraseña
    $user->password = $request->new_password;
    $user->cambio_password = true; // Marcar como que ya cambió la contraseña
    $user->save();

    return redirect()->route('login')->with('success', 'Contraseña cambiada con éxito. Puedes iniciar sesión ahora.');
}

}
