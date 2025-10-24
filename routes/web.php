<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;
use App\Http\Middleware\AuthMiddleware;

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
});
###########################################################
# Rutas para el admin
###########################################################
Route::prefix('admin')->middleware([AuthMiddleware::class . ':admin'])->group(function () {
    Route::get('/home', function () {
        return view('admin.home');  
    })->name('admin.home');
});


Route::get('/register', [RegistroController::class, 'show'])->name('register');

