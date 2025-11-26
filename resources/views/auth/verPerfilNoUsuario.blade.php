@extends('layouts.layoutInterno')
@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto mt-6">

    {{-- Card de perfil completo --}}
    <div class="card p-8 shadow-md rounded-xl bg-white border border-gray-200">

        {{-- Título general --}}
        <h1 class="text-2xl font-semibold mb-6 border-b pb-3">Mi Perfil</h1>

        {{-- Datos personales --}}
        <section class="mb-8">
    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Datos personales</h2>

    <form method="POST" action="{{ route('usuario.updatePerfil') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Nombre --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}" disabled
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- Apellido --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Apellido</label>
                <input type="text" name="apellido" value="{{ old('apellido', $user->apellido) }} " disabled
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- Email (NO editable) --}}
            <div>
                <label class="text-sm font-medium text-gray-700">Email</label>
                <input type="email" value="{{ $user->mail }}" disabled
                       class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-gray-600 cursor-not-allowed">
            </div>
@php
    $rol = session('rol');

    $rutaVolver = match ($rol) {
        2 => route('medico.home'),
        3 => route('operador.home'),
        4 => route('admin.home'),
        5 => route('jefe.home'),
        default => route('home')
    };
@endphp
        {{-- Botones --}}
        <div class="flex justify-end mt-6 gap-4">
            {{-- Volver atrás --}}
   <a href="{{ $rutaVolver }}"
   class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-gray-400 transition">
    <i class="fas fa-arrow-left"></i> Volver atrás
</a>
            <a href="{{ route('change.password.private', ['email' => $user->mail]) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition">
                <i class="fas fa-key"></i> Cambiar contraseña
            </a>

        </div>

    </form>
</section>


    </div>
</div>
@endsection
