<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\AvisosController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\OperadorController;

## Web Routes

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

Route::get('/ovocitos/{id}/editar', [OperadorController::class, 'editar'])->name('ovocito.editar');
Route::post('ovocitos/actualizar', [OperadorController::class, 'updateOvocito'])->name('ovocito.actualizar');

Route::get('/ovocito/{id}/json', [OperadorController::class, 'getJson'])->name('ovocito.json');


###########################################################
# Rutas para el medico
###########################################################
Route::prefix('medico')->middleware([AuthMiddleware::class . ':medico'])->group(function () {
    
        
    Route::get('/home', [MedicoController::class, 'misPacientes'])->name('medico.home');

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

    Route::get('paciente/{id}/tratamientos', [App\Http\Controllers\MedicoController::class, 'tratamientosDeUnPaciente']);

    Route::get('/tratamiento/{id}/post-transferencia', [MedicoController::class, 'postTransferenciaForm']
        )->name('tratamiento.post');

    Route::post('/tratamiento/{id}/post-transferencia', [MedicoController::class, 'guardarPostTransferencia']
        )->name('tratamiento.guardar-post');
    
    Route::post('/tratamiento/{id}/enviar-orden-medica', [AvisosController::class, 'enviarOrdenMedica'])
    ->name('tratamiento.enviar-orden-medica');
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
        // 1. Formulario general de punción
    Route::get('/puncion/{paciente_id}', 
        [App\Http\Controllers\OperadorController::class, 'formPuncion'])
        ->name('puncion.form');

    // 2. Buscar paciente por nombre+apellido o DNI
    Route::post('/puncion/buscar-paciente',
        [App\Http\Controllers\OperadorController::class, 'buscarPaciente'])
        ->name('puncion.buscarPaciente');

    // 3. Guardar punción
    Route::post('/puncion/guardar',
        [App\Http\Controllers\OperadorController::class, 'guardarPuncion'])
        ->name('puncion.guardar');
});


###########################################################
# Rutas para el jefe
###########################################################
Route::prefix('jefe')->middleware([AuthMiddleware::class . ':jefe'])->group(function () {
    Route::get('/home', function () {
        return view('jefe.home');
    })->name('jefe.home');
});


Route::get('/register', [RegistroController::class, 'show'])->name('register');
