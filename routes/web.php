<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/medico/home', function () {
    return view('medico.home');
})->name('medico.home');
Route::get('/admin/home', function () {
    return view('admin.home');
})->name('admin.home');
