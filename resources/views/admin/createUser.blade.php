@extends('layouts.layoutInterno')
@section('title', 'Crear Usuario - Admin')

@section('page-header')
    <div class="page-header">
        <h1 class="page-title">Registrar nuevo personal</h1>
        <p class="page-subtitle">Complete los datos para crear un usuario interno</p>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card p-8">
            <form method="POST" action="{{ route('admin.store_user') }}" class="space-y-6">
                @csrf
                <!-- Nombre y Apellido -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Nombre
                        </label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre') border-red-500 @enderror"
                            placeholder="Nombre" required>
                        @error('nombre')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="apellido" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Apellido
                        </label>
                        <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('apellido') border-red-500 @enderror"
                            placeholder="Apellido" required>
                        @error('apellido')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                        placeholder="correo@ejemplo.com" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Rol -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2 text-blue-600"></i>Rol
                    </label>
                    <div class="flex flex-row justify-center gap-4">
                        @foreach ($roles as $rol)
                            <label
                                class="flex items-center cursor-pointer border border-gray-300 rounded-lg px-4 py-2 transition-colors hover:border-blue-500">
                                <input type="radio" name="rol" value="{{ $rol->id }}"
                                    class="form-radio text-blue-600 focus:ring-blue-500"
                                    {{ old('rol') == $rol->id ? 'checked' : '' }} required>
                                <span class="ml-2 text-sm text-gray-700">{{ ucfirst($rol->nombre) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('rol')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Botón -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 btn-primary text-white px-6 py-3 rounded-lg font-semibold text-center hover:shadow-lg transition-all">
                        <i class="fas fa-user-plus mr-2"></i>
                        Registrar usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
