@extends('layouts.layoutUsuario')
@section('title', 'Cambiar Contraseña')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg mt-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Cambiar Contraseña</h2>

   
    @if(session('rol') == 1)
    <form action="{{ route('update.password.user') }}" method="POST">
        @csrf
        
        <input type="hidden" name="email" value="{{ $user->mail }}">

        <div class="mb-4">
            <label for="new_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
            <input type="password" id="new_password" name="new_password" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="mb-6">
            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="flex justify-between items-center">
            <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Cambiar Contraseña</button>
            <a href="{{ route('verPerfil') }}" class="text-sm text-blue-600 hover:text-blue-700">Volver al perfil</a>
        </div>
    </form>
    @else
    <form action="{{ route('update.password') }}" method="POST">
        @csrf
        
        <input type="hidden" name="email" value="{{ $user->mail }}">

        <div class="mb-4">
            <label for="new_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
            <input type="password" id="new_password" name="new_password" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="mb-6">
            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="flex justify-between items-center">
            <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Cambiar Contraseña</button>
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-700">Volver al login</a>
        </div>
    </form>
    @endif
</div>
@endsection
