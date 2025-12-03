<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\TerminosController;
use App\Http\Controllers\EstudiosController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\AvisosController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\OperadorController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\GametosController;

// Importaciones para los modelos de fenotipo
use App\Models\ColorOjo;
use App\Models\ColorPelo;
use App\Models\Complexion;
use App\Models\RasgoEtnico;
use App\Models\TipoPelo;
## Web Routes
Route::get('/borrar-turnos', function () {
    try {
        $response = Http::withToken(env('TOKEN_TURNERO'))->delete(' https://ahlnfxipnieoihruewaj.supabase.co/functions/v1/delete_turnos');
        Log::info('Turnos borrados', ['deleted' => $response->json()['deleted']]);
        return redirect()->back()->with('success', 'Se han borrado ' . $response->json()['deleted'] . ' turnos');
    } catch (\Exception $e) {
        Log::error('Error al conectar con el servicio de turnos', ['exception' => $e]);
        return redirect()->back()->with('error', 'Error al conectar con el servicio de turnos');
    }
});
###########################################################
# Rutas para login, registro y vistas sin session iniiada #
###########################################################
Route::get('/', function () {
    return view('usuario.userHome');
})->name('home');
Route::get('/servicios', function () {
    return view('usuario.servicios');
})->name('servicios');
Route::get('/tratamientos', function () {
    return view('usuario.tratamientos');
})->name('tratamientos');
Route::get('/registro', function () {
    $response = Http::withoutVerifying()->get('https://ueozxvwsckonkqypfasa.supabase.co/functions/v1/getObrasSociales');
    $obrasSociales = $response->json()['data'];
    return view('usuario.register', compact('obrasSociales'));
})->name('registro');
Route::get('/perfil', [App\Http\Controllers\Auth\LoginController::class, 'perfil'])->name('verPerfil');
Route::get('/ver-perfil', [App\Http\Controllers\Auth\LoginController::class, 'verPerfilNoUsuario'])->name('verPerfilNoUsuario');
Route::put('/usuario/perfil', [LoginController::class, 'updatePerfil'])
    ->name('usuario.updatePerfil');

Route::get('/login', function () {
    return view('usuario.login');
})->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('loginBack');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('/register', [RegistroController::class, 'store'])->name('register.store');

###########################################################
# Rutas para el paciente
###########################################################
Route::prefix('paciente')->middleware([AuthMiddleware::class . ':paciente'])->group(function () {
    Route::get('/solicitar-turno', [TurnoController::class, 'showSolicitarTurnoForm'])->name('paciente.solicitar-turno');
    Route::post('/solicitar-turno-store', [TurnoController::class, 'storeTurno'])->name('paciente.store-turno');
    Route::get('/turnos-libres/{id_medico}', [TurnoController::class, 'listarTurnosLibres'])->name('paciente.turnos-libres');
    Route::get('/turnos-sugeridos/{id_medico}/{id_paciente}', [TurnoController::class, 'listarTurnosSugeridos'])->name('paciente.turnos-sugeridos');

    //chatbot
    Route::post('/chat/send-message', [ChatbotController::class, 'sendMessage'])
        ->middleware('auth') // Asumo que solo usuarios logueados pueden usar el chat
        ->name('chatbot.send');
});

Route::post('/tratamiento/{id}/avanzar', [MedicoController::class, 'avanzarEtapa'])
    ->name('tratamiento.avanzar-etapa');

Route::post('/tratamiento/{id}/retroceder', [MedicoController::class, 'retrocederEtapa'])
    ->name('tratamiento.retroceder-etapa');

Route::post('/tratamiento/{id}/agendar-consulta', [MedicoController::class, 'agendarConsulta'])
    ->name('tratamiento.agendar-consulta');

    Route::post('/tratamiento/{id}/notificar-transferencia', [MedicoController::class, 'notificarTransferencia'])
    ->name('tratamiento.notificar-transferencia');

Route::get('/ovocitos/{id}/editar', [OperadorController::class, 'editar'])->name('ovocito.editar');
Route::post('ovocitos/actualizar', [OperadorController::class, 'updateOvocito'])->name('ovocito.actualizar');

Route::get('/ovocito/{id}/json', [OperadorController::class, 'getJson'])->name('ovocito.json');

// Ruta para mostrar el formulario de cambio de contraseña
Route::get('/cambiar-contraseña/{email}', [LoginController::class, 'showChangePasswordForm'])->name('change.password');
Route::get('/cambiar-contraseña-private/{email}', [LoginController::class, 'showChangePasswordFormPrivate'])->name('change.password.private');

// Ruta para actualizar la contraseña
Route::post('/cambiar-contraseña', [LoginController::class, 'updatePassword'])->name('update.password');
Route::post('/cambiar-contraseña-usuario', [LoginController::class, 'updatePasswordUser'])->name('update.password.user');

###########################################################
# Rutas para el medico
###########################################################
Route::prefix('medico')->middleware([AuthMiddleware::class . ':medico'])->group(function () {


    Route::get('/home', [MedicoController::class, 'misPacientes'])->name('medico.home');

    Route::get('paciente/{id}/tratamiento', [App\Http\Controllers\MedicoController::class, 'detalleTratamiento'])
        ->name('medico.tratamiento.detalle');

    Route::get('/tratamientos/{id}/monitoreos', [MedicoController::class, 'monitoreos'])
        ->name('monitoreos');

    Route::post('/tratamientos/monitoreos', [MedicoController::class, 'storeMonitoreo'])
        ->name('monitoreos.store');

    Route::get('/tratamiento/{id}/cargar-estudios', [MedicoController::class, 'cargarEstudios'])
        ->name('tratamiento.cargar-estudios');

    Route::post('/tratamientos/{id}/estudios/guardar', [MedicoController::class, 'guardarEstudios'])
        ->name('tratamiento.guardar-estudios');

    Route::get('/tratamiento/{id}/protocolo', [MedicoController::class, 'protocolo'])
        ->name('tratamiento.protocolo');

    Route::post('/tratamiento/{id}/protocolo', [MedicoController::class, 'guardarProtocolo'])
        ->name('tratamiento.guardar-protocolo');

    Route::post('/tratamiento/{id}/consentimiento', [MedicoController::class, 'subirConsentimiento'])
        ->name('tratamiento.subir-consentimiento');
    
    Route::get('/tratamientos/{id}/descargar-consentimiento', [MedicoController::class, 'descargarConsentimiento'])
    ->name('tratamiento.descargar-consentimiento');

    Route::get('paciente/{id}/tratamientos', [App\Http\Controllers\MedicoController::class, 'tratamientosDeUnPaciente']);

    Route::post('paciente/{id}/tratamiento/dar-de-baja', [MedicoController::class, 'darDeBajaTratamiento'])
        ->name('medico.tratamiento.dar-baja');

    Route::get(
        '/tratamiento/{id}/post-transferencia',
        [MedicoController::class, 'postTransferenciaForm']
    )->name('tratamiento.post');

    Route::post(
        '/tratamiento/{id}/post-transferencia',
        [MedicoController::class, 'guardarPostTransferencia']
    )->name('tratamiento.guardar-post');

    Route::post('/tratamiento/{id}/enviar-orden-medica', [AvisosController::class, 'enviarOrdenMedica'])
        ->name('tratamiento.enviar-orden-medica');

    Route::get('/consulta/partials/hombre-gametos', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.pareja-hombre', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::get('/consulta/partials/hombre-donado', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.semen-donado', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::get('/consulta/partials/pareja-mujer', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.pareja-mujer', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::post('/consulta', [ConsultaController::class, 'store'])->name('consulta.store');

    Route::post('/consulta/update/{tratamiento}', [ConsultaController::class, 'update'])->name('consulta.update');

    Route::get('/consulta/{paciente_id}', [ConsultaController::class, 'create'])
        ->name('medico.primerConsulta.create');

    Route::get('/verConsulta/{paciente_id}', [ConsultaController::class, 'ver'])
        ->name('medico.verConsulta');

    Route::post('/estudios', [EstudiosController::class, 'store'])->name('estudios.store');
    Route::get('/estudios/{paciente_id}', [EstudiosController::class, 'estudios'])
        ->name('estudios.index');
});
###########################################################
# Rutas para el admin
###########################################################
Route::prefix('admin')->middleware([AuthMiddleware::class . ':admin'])->group(function () {

    Route::get('/home', [App\Http\Controllers\AdminController::class, 'home'])->name('admin.home');

    Route::get('/create_user', [App\Http\Controllers\AdminController::class, 'create_user'])->name('admin.create_user');

    Route::post('/create_user', [App\Http\Controllers\AdminController::class, 'store_user'])->name('admin.store_user');

    Route::post('/baja_user', [App\Http\Controllers\AdminController::class, 'baja_user'])->name('admin.baja_user');

    Route::post('/alta_user', [App\Http\Controllers\AdminController::class, 'alta_user'])->name('admin.alta_user');

    Route::post('/set_horarios', [AdminController::class, 'set_horarios'])->name('admin.set_horarios');
});

###########################################################
# Rutas para el operador
###########################################################
Route::prefix('operador')->middleware([AuthMiddleware::class . ':operador'])->group(function () {

    Route::get('/home', [OperadorController::class, 'Pacientes'])->name('operador.home');

    Route::get('paciente/{id}/tratamientos', [App\Http\Controllers\OperadorController::class, 'tratamientosDeUnPaciente']);

    Route::get('paciente/{id}/tratamiento', [App\Http\Controllers\MedicoController::class, 'detalleTratamiento'])
        ->name('operador.tratamiento.detalle');

    Route::get('paciente/{id}/puncion', [App\Http\Controllers\OperadorController::class, 'puncion'])
        ->name('tratamiento.puncion');

    Route::get('/estudios/{paciente_id}', [EstudiosController::class, 'estudios'])
        ->name('operador.estudios.index');
    
    Route::get('/consulta/partials/hombre-gametos', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.pareja-hombre', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::get('/consulta/partials/hombre-donado', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.semen-donado', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::get('/consulta/partials/pareja-mujer', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.pareja-mujer', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });    

     Route::get('/consulta/{paciente_id}', [ConsultaController::class, 'create'])
        ->name('operador.primerConsulta.create');

    Route::get('/verConsulta/{paciente_id}', [ConsultaController::class, 'ver'])
        ->name('operador.verConsulta');

    Route::get('/tratamiento/{id}/protocolo', [MedicoController::class, 'protocolo'])
        ->name('operador.tratamiento.protocolo');    

    Route::get('/tratamiento/{id}/cargar-estudios', [MedicoController::class, 'cargarEstudios'])
        ->name('operador.tratamiento.cargar-estudios');    

    Route::get(
        '/tratamiento/{id}/post-transferencia',
        [MedicoController::class, 'postTransferenciaForm']
    )->name('operador.tratamiento.post');    

    Route::get('/tratamientos/{id}/monitoreos', [MedicoController::class, 'monitoreos'])
        ->name('operador.monitoreos');

    Route::get(
        '/puncion/{paciente_id}',
        [App\Http\Controllers\OperadorController::class, 'formPuncion']
    )
        ->name('puncion.form');

    Route::post(
        '/puncion/buscar-paciente',
        [App\Http\Controllers\OperadorController::class, 'buscarPaciente']
    )
        ->name('puncion.buscarPaciente');

    Route::post('/puncion/guardar', [App\Http\Controllers\OperadorController::class, 'guardarPuncion'])->name('puncion.guardar');

    Route::get('/fertilizacion/{paciente_id}', [OperadorController::class, 'fertilizacion'])->name('operador.fertilizacion');

    Route::get('/fertilizacion/{paciente_id}/nueva', [OperadorController::class, 'nuevaFertilizacion'])->name('fertilizacion.nueva');

    Route::post('/fertilizacion/guardar', [OperadorController::class, 'guardarFertilizacion'])->name('fertilizacion.guardar');

    Route::post('/criopreservar-semen', [OperadorController::class, 'criopreservarSemen'])
        ->name('criopreservar.semen.store');

    Route::post('/embrion/update', [OperadorController::class, 'updateEmbrion'])->name('embrion.update');

    Route::get('/donacion-gametos/nueva', [GametosController::class, 'nuevaDonacion'])->name('donacion.nueva');

    Route::post('/donacion-gametos/registrar', [GametosController::class, 'registrar'])
    ->name('donacion.registrar');
});


###########################################################
# Rutas para el jefe
###########################################################
Route::prefix('jefe')->middleware([AuthMiddleware::class . ':jefe'])->group(function () {
    
    Route::get('/home', [MedicoController::class, 'todosPacientes'])->name('jefe.home');

    Route::get('/usuarios', [AdminController::class, 'index'])->name('jefe.usuarios.index');

    Route::get('paciente/{id}/tratamientos', [App\Http\Controllers\OperadorController::class, 'tratamientosDeUnPaciente']);

    Route::get('paciente/{id}/tratamiento', [App\Http\Controllers\MedicoController::class, 'detalleTratamiento'])
        ->name('jefe.tratamiento.detalle');

     Route::get('/pago/{id}/marcar-pagado', [AdminController::class, 'marcarPagado'])->name('jefe.pago.marcar-pagado');    

    Route::get('/tratamientos/{id}/monitoreos', [MedicoController::class, 'monitoreos'])
        ->name('jefe.monitoreos');

    Route::post('/tratamientos/monitoreos', [MedicoController::class, 'storeMonitoreo'])
        ->name('jefe.monitoreos.store');

    Route::get('/tratamiento/{id}/cargar-estudios', [MedicoController::class, 'cargarEstudios'])
        ->name('jefe.tratamiento.cargar-estudios');

    Route::post('/tratamientos/{id}/estudios/guardar', [MedicoController::class, 'guardarEstudios'])
        ->name('jefe.tratamiento.guardar-estudios');

    Route::get('/tratamiento/{id}/protocolo', [MedicoController::class, 'protocolo'])
        ->name('jefe.tratamiento.protocolo');

    Route::post('/tratamiento/{id}/protocolo', [MedicoController::class, 'guardarProtocolo'])
        ->name('jefe.tratamiento.guardar-protocolo');

    Route::post('/tratamiento/{id}/consentimiento', [MedicoController::class, 'subirConsentimiento'])
        ->name('jefe.tratamiento.subir-consentimiento');

    Route::post('paciente/{id}/tratamiento/dar-de-baja', [MedicoController::class, 'darDeBajaTratamiento'])
        ->name('jefe.tratamiento.dar-baja');

    Route::get(
        '/tratamiento/{id}/post-transferencia',
        [MedicoController::class, 'postTransferenciaForm']
    )->name('jefe.tratamiento.post');

    Route::post(
        '/tratamiento/{id}/post-transferencia',
        [MedicoController::class, 'guardarPostTransferencia']
    )->name('jefe.tratamiento.guardar-post');

    Route::post('/tratamiento/{id}/enviar-orden-medica', [AvisosController::class, 'enviarOrdenMedica'])
        ->name('jefe.tratamiento.enviar-orden-medica');

    Route::get('/consulta/partials/hombre-gametos', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.pareja-hombre', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::get('/consulta/partials/hombre-donado', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.semen-donado', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::get('/consulta/partials/pareja-mujer', function () {
        $coloresPelo = ColorPelo::all();
        $coloresOjos = ColorOjo::all();
        $tipoPelo = TipoPelo::all();
        $complexiones = Complexion::all();
        $rasgos = RasgoEtnico::all();

        return view('medico.partials.pareja-mujer', compact(
            'coloresPelo',
            'coloresOjos',
            'tipoPelo',
            'complexiones',
            'rasgos'
        ));
    });

    Route::post('/consulta', [ConsultaController::class, 'store'])->name('jefe.consulta.store');
    
    Route::post('/consulta/update/{tratamiento}', [ConsultaController::class, 'update'])->name('jefe.consulta.update');

    Route::get('/consulta/{paciente_id}', [ConsultaController::class, 'create'])
        ->name('jefe.primerConsulta.create');

    Route::get('/verConsulta/{paciente_id}', [ConsultaController::class, 'ver'])
        ->name('jefe.verConsulta');

    Route::post('/estudios', [EstudiosController::class, 'store'])->name('jefe.estudios.store');

    Route::get('/estudios/{paciente_id}', [EstudiosController::class, 'estudios'])
        ->name('jefe.estudios.index');
});


Route::get('/register', [RegistroController::class, 'show'])->name('register');


############################################################
# Rutas de prueba primer consulta
############################################################

Route::get('/terminos/search', [TerminosController::class, 'search'])->name('terminos.search');
