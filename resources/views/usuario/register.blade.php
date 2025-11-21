@extends('layouts.layoutUsuario')
@section('title', 'Registro - Fertilia')

@section('content')
    <!-- Header Section -->
    <div class="hero-section text-center mb-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
                <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                Registro de usuario
            </h1>
            <p class="text-lg text-gray-600 mb-4">
                Crea tu cuenta para acceder a nuestros servicios especializados en fertilidad
            </p>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="max-w-5xl mx-auto">
        <div class="register-card bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
                @csrf

                <!-- Sección: Información Personal -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-user mr-2 text-blue-600"></i>Información Personal
                    </h3>

                    <!-- Primera fila: Nombre, Apellido, Email -->
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-3">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre
                            </label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre') border-red-500 @enderror"
                                placeholder="Ingresa tu nombre" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">
                                Apellido
                            </label>
                            <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('apellido') border-red-500 @enderror"
                                placeholder="Ingresa tu apellido" required>
                            @error('apellido')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="lg:col-span-1 md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo Electrónico
                            </label>
                            <input type="email" id="mail" name="mail" value="{{ old('mail') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                                placeholder="correo@ejemplo.com" required>
                            @error('mail')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Segunda fila: DNI, Fecha de Nacimiento, Teléfono, Ocupación -->
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-3">
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-id-card mr-1 text-blue-600"></i>DNI
                            </label>
                            <input type="text" id="dni" name="dni" value="{{ old('dni') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('dni') border-red-500 @enderror"
                                placeholder="12345678" maxlength="8" pattern="[0-9]{8}" required>
                            @error('dni')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-calendar-alt mr-1 text-blue-600"></i>Fecha de Nacimiento
                            </label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fecha_nacimiento') border-red-500 @enderror"
                                required>
                            @error('fecha_nacimiento')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-phone mr-1 text-blue-600"></i>Teléfono
                            </label>
                            <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telefono') border-red-500 @enderror"
                                placeholder="+54 11 1234-5678" required>
                            @error('telefono')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ocupacion" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-briefcase mr-1 text-blue-600"></i>Ocupación
                            </label>
                            <input type="text" id="ocupacion" name="ocupacion" value="{{ old('ocupacion') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('ocupacion') border-red-500 @enderror"
                                placeholder="Ej: Empleada, Profesional" required>
                            @error('ocupacion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección: Obra Social -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-hospital mr-2 text-blue-600"></i>Información de Obra Social
                    </h3>

                    <!-- Detalles Obra Social -->
                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <label for="obra_social" class="block text-sm font-medium text-gray-700 mb-1">
                                Obra Social *
                            </label>
                            <select id="obra_social" name="obra_social" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('obra_social') border-red-500 @enderror">
                                <option value="" disabled selected>Seleccionar obra social</option>
                                @foreach ($obrasSociales as $obraSocial)
                                    <option value="{{ $obraSocial['id'] }}"
                                        {{ old('obra_social') == $obraSocial['id'] ? 'selected' : '' }}>
                                        {{ $obraSocial['sigla'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('obra_social')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="numero_afiliado" class="block text-sm font-medium text-gray-700 mb-1">
                                Número de Afiliado *
                            </label>
                            <input type="text" id="numero_afiliado" name="numero_afiliado" required
                                value="{{ old('numero_afiliado') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('numero_afiliado') border-red-500 @enderror"
                                placeholder="Número de afiliado">
                            @error('numero_afiliado')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sección: Seguridad -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Seguridad de la Cuenta
                    </h3>

                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                                placeholder="Mínimo 8 caracteres" minlength="8" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar Contraseña
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Repite la contraseña" minlength="8" required>
                        </div>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="flex justify-center pt-5 border-t border-gray-200">
                    <button type="submit"
                        class="btn-primary text-white px-6 py-2.5 rounded-md font-medium text-center hover:shadow-md transition-all">
                        <i class="fas fa-user-plus mr-2"></i>
                        Crear Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
