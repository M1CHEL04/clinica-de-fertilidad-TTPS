<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolTrabajador;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\MailController;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminController extends Controller
{
    public function home(Request $request)
    {
        $perPage = 5;
        $query = User::with('rol')->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhere('apellido', 'like', "%$search%")
                    ->orWhere('mail', 'like', "%$search%");
            });
        }
        if ($request->filled('rol')) {
            $rol = $request->input('rol');
            $query->whereHas('rol', function ($q) use ($rol) {
                $q->where('nombre', $rol);
            });
        }
        if ($request->filled('estado')) {
            $estado = $request->input('estado');
            $query->where('activo', $estado === 'activo' ? 1 : 0);
        }

        $usuarios = $query->paginate($perPage)->appends($request->except('page'));

        // Estadísticas generales (sin filtros)
        $totalActivo = User::where('activo', 1)->count();
        $medicos = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'medico');
        })->where('activo', 1)->count();
        $operadores = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'operador');
        })->where('activo', 1)->count();
        $admins = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'admin');
        })->where('activo', 1)->count();

        return view('admin.home', compact('usuarios', 'totalActivo', 'medicos', 'operadores', 'admins'));
    }

    public function create_user()
    {
        $roles = RolTrabajador::where('id', '!=', 1)->get();
        return view('admin.createUser', compact('roles'));
    }

    public function store_user(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email',
            'rol' => 'required',
        ]);

        try {
            if (User::where('mail', $request->input('email'))->exists()) {
                return redirect()->back()->withErrors(['email' => 'El correo electrónico ya está en uso.'])->withInput();
            }

            $password = Str::random(8);

            $user = User::create([
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'mail' => $request->input('email'),
                'password' => $password,
                'cambio_password' => false,
                'rol_id' => $request->input('rol'),
            ]);
            // Enviar correo al usuario con su contraseña temporal
            $mailController = new MailController();
            $mailController->enviarMail([$user->mail], 'Password Temporal', 'mails.envioPassword', [
            'nombre' => $user->nombre,
            'password' => $password]);
            
            return redirect()->route('admin.home')->with('success', 'Usuario creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.home')->with('error', 'Ocurrió un error al crear el usuario.');
        }
    }

    public function baja_user(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            if ($user) {
                $user->activo = false;
                $user->save();
                return redirect()->route('admin.home')->with('success', 'Usuario dado de baja exitosamente.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.home')->with('error', 'Ocurrió un error al dar de baja el usuario.');
        }
    }

    public function alta_user(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            if ($user) {
                $user->activo = true;
                $user->save();
                return redirect()->route('admin.home')->with('success', 'Usuario dado de alta exitosamente.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.home')->with('error', 'Ocurrió un error al dar de alta el usuario.');
        }
    }

    public function set_horarios(Request $request)
    {
        try {
            $user = User::find($request->medico_id);
            if ($user) {
                $token = env('TOKEN_TURNERO');
                $dia = $request->integer('dia_semana');
                $response = Http::withToken($token)->post(
                    'https://ahlnfxipnieoihruewaj.supabase.co/functions/v1/post_turnos',
                    [
                        'id_medico' => $user->id,
                        'hora_inicio' => $request->hora_inicio,
                        'hora_fin' => $request->hora_fin,
                        'dia_semana' => $dia,
                    ]
                );

                if ($response->failed()) {
                    Log::error('Error al llamar a la API', ['response' => $response->body()]);
                    return redirect()->route('admin.home')->with('error', 'Ocurrió un error al cargar los horarios del medico');
                }

                return redirect()->route('admin.home')->with('success', 'Horarios actualizados exitosamente.');
            } else {
                return redirect()->route('admin.home')->with('error', 'Usuario no encontrado.');
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar horarios: ' . $e->getMessage());
            return redirect()->route('admin.home')->with('error', 'Ocurrió un error al actualizar los horarios.');
        }
    }

 public function index()
{
    // 1) Traer pagos de Supabase
    $url = "https://ueozxvwsckonkqypfasa.supabase.co/functions/v1/get-pagos-grupo";

    $response = Http::withHeaders([
        "Content-Type" => "application/json",
        "Authorization" => "Bearer " . env('SUPABASE_SERVICE_ROLE')
    ])->post($url, ["group" => 5]);

    if (!$response->ok()) {
        return abort(500, "Error obteniendo pagos del servidor");
    }

    $pagosArray = $response->json("pagos") ?? [];
    $pagosCollection = collect($pagosArray);

    // 2) Usuarios de la DB
    $usuarios = \App\Models\User::where('rol_id', 1)
        ->orderBy('apellido')
        ->get();

    // 3) Relacionar pagos con usuario
    $usuariosById = $usuarios->keyBy(fn($u) => (int)$u->id);

    $pagosConUsuario = $pagosCollection->filter(function ($p) use ($usuariosById) {
        $pid = $p['id_paciente'] ?? $p['paciente_id'] ?? $p['idPaciente'] ?? null;
        return $pid && $usuariosById->has((int)$pid);
    })->map(function ($p) use ($usuariosById) {
        $pid = (int)($p['id_paciente'] ?? $p['paciente_id'] ?? $p['idPaciente']);
        $p['usuario'] = $usuariosById[$pid];
        return $p;
    });

    // 4) Agrupar pagos por paciente
    $pagosPorPaciente = $pagosConUsuario->groupBy(fn($p) =>
        (int)($p['id_paciente'] ?? $p['paciente_id'] ?? $p['idPaciente'])
    );

    // 5) Usuarios con pagos
    $usuariosConPagos = $usuarios->filter(fn($u) =>
        $pagosPorPaciente->has((int)$u->id)
    );

    // -----------------------
    // 6) Aplicar FILTROS
    // -----------------------
    $search = request('search');
    $rol = request('rol');
    $estado = request('estado');

    $usuariosConPagos = $usuariosConPagos->filter(function ($u) use ($search, $rol, $estado) {

        // FILTRO POR TEXTO
        if ($search) {
            $texto = strtolower($search);
            if (
                !str_contains(strtolower($u->nombre), $texto) &&
                !str_contains(strtolower($u->apellido), $texto) &&
                !str_contains(strtolower($u->mail), $texto)
            ) {
                return false;
            }
        }

        // FILTRO POR ROL
        if ($rol) {
            if (!$u->rol || strtolower($u->rol->nombre) !== strtolower($rol)) {
                return false;
            }
        }

        // FILTRO POR ESTADO
        if ($estado) {
            $esActivo = $u->activo ? "activo" : "inactivo";
            if ($estado !== $esActivo) {
                return false;
            }
        }

        return true;
    })->values();

    // -----------------------
    // 7) Paginación
    // -----------------------
    $perPage = 10;
    $currentPage = (int) request()->get('page', 1);
    $total = $usuariosConPagos->count();

    $items = $usuariosConPagos->forPage($currentPage, $perPage);

    $usuariosPaginados = new LengthAwarePaginator(
        $items,
        $total,
        $perPage,
        $currentPage,
        [
            'path' => request()->url(),
            'query' => request()->query()
        ]
    );

    return view("admin.usuariosIndex", [
        'usuarios' => $usuariosPaginados,
        'pagosPorPaciente' => $pagosPorPaciente
    ]);
}


public function marcarPagado($id)
{
    $url = "https://ueozxvwsckonkqypfasa.supabase.co/functions/v1/registrar-pago-obra-social";

    $response = Http::withHeaders([
        "Content-Type" => "application/json",
        "Authorization" => "Bearer " . env("SUPABASE_SERVICE_ROLE"),
    ])->post($url, [
        "id_grupo" => 5,
        "id_pago" => (int) $id,
        "paciente_pagado" => true
    ]);

    if (!$response->ok()) {
        return redirect()->back()->with('error', 'Error registrando pago.');
    }

    return redirect()->back()->with('success', 'Pago marcado como completado.');
}

}
