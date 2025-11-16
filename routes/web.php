<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\TerminosController;

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
    return view('usuario.register');
})->name('registro');
Route::get('/login', function () {
    return view('usuario.login');
})->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('loginBack');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('/register', [RegistroController::class, 'store'])->name('register.store');

###########################################################
# Rutas para el medico
###########################################################
Route::prefix('medico')->middleware([AuthMiddleware::class . ':medico'])->group(function () {
    Route::get('/home', function () {
        return view('medico.home');
    })->name('medico.home');

    
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
});

###########################################################
# Rutas para el operador
###########################################################


Route::prefix('medico')->middleware([AuthMiddleware::class . ':medico'])->group(function () {
    Route::get('/home', [MedicoController::class, 'misPacientes'])->name('medico.home');
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


############################################################
# Rutas de prueba primer consulta
############################################################

Route::get('/consulta', function () {
    return view('medico.primerConsulta'); 
})->name('consulta.index');

Route::post('/consulta', [App\Http\Controllers\ConsultaController::class, 'store'])
    ->name('consulta.store');

Route::get('/terminos/search', [TerminosController::class, 'search'])->name('terminos.search');