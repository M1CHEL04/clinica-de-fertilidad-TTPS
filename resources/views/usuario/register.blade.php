@extends('layouts.layoutUsuario')
@section('title', 'Registro - Fertilia')

@section('content')
<!-- Header Section -->
<div class="hero-section text-center mb-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
            <i class="fas fa-user-plus text-blue-600 mr-3"></i>
            Registro de Usuario
        </h1>
        <p class="text-lg text-gray-600 mb-4">
            Crea tu cuenta para acceder a nuestros servicios especializados en fertilidad
        </p>
    </div>
</div>

<!-- Registration Form -->
<div class="max-w-2xl mx-auto">
    <div class="register-card">
        <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
            @csrf
            
            <!-- Nombre y Apellido -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Nombre
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre') border-red-500 @enderror"
                           placeholder="Ingresa tu nombre"
                           required>
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="apellido" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Apellido
                    </label>
                    <input type="text" 
                           id="apellido" 
                           name="apellido" 
                           value="{{ old('apellido') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('apellido') border-red-500 @enderror"
                           placeholder="Ingresa tu apellido"
                           required>
                    @error('apellido')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i>Correo Electrónico
                </label>
                <input type="email" 
                       id="mail" 
                       name="mail" 
                       value="{{ old('mail') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                       placeholder="correo@ejemplo.com"
                       required>
                @error('mail')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DNI y Fecha de Nacimiento -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="dni" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card mr-2 text-blue-600"></i>DNI
                    </label>
                    <input type="text" 
                           id="dni" 
                           name="dni" 
                           value="{{ old('dni') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('dni') border-red-500 @enderror"
                           placeholder="12345678"
                           maxlength="8"
                           pattern="[0-9]{8}"
                           required>
                    @error('dni')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Fecha de Nacimiento
                    </label>
                    <input type="date" 
                           id="fecha_nacimiento" 
                           name="fecha_nacimiento" 
                           value="{{ old('fecha_nacimiento') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fecha_nacimiento') border-red-500 @enderror"
                           required>
                    @error('fecha_nacimiento')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Teléfono -->
            <div>
                <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-phone mr-2 text-blue-600"></i>Teléfono
                </label>
                <input type="tel" 
                       id="telefono" 
                       name="telefono" 
                       value="{{ old('telefono') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telefono') border-red-500 @enderror"
                       placeholder="+54 11 1234-5678"
                       required>
                @error('telefono')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                           placeholder="Mínimo 8 caracteres"
                           minlength="8"
                           required>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Confirmar Contraseña
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Repite la contraseña"
                           minlength="8"
                           required>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button type="submit" 
                        class="flex-1 btn-primary text-white px-6 py-3 rounded-lg font-semibold text-center hover:shadow-lg transition-all">
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Cuenta
                </button>
            </div>
        </form>
    </div>
</div>

@endsection