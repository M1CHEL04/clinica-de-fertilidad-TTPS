@extends('layouts.layoutUsuario')
@section('title', 'Iniciar sesión')

@section('content')
<!-- Header Section -->
<div class="hero-section text-center mb-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">
            <i class="fas fa-sign-in-alt text-blue-600 mr-3"></i>
            Iniciar Sesión
        </h1>
        <p class="text-lg text-gray-600 mb-4">
            Accede a tu cuenta para gestionar tus consultas y tratamientos
        </p>
    </div>
</div>

<!-- Login Form -->
<div class="max-w-md mx-auto">
    <div class="register-card">
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i>Correo Electrónico
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                       placeholder="correo@ejemplo.com"
                       required
                       autofocus>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>Contraseña
                </label>
                <div class="relative">
                    <input type="password" 
                           id="password" 
                           name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                           placeholder="Tu contraseña"
                           required>
                    <button type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                        <i id="toggleIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recordar sesión y Olvidé contraseña -->
            <div class="flex items-center justify-between">
                
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <!-- Botón de Login -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full btn-primary text-white px-6 py-3 rounded-lg font-semibold text-center hover:shadow-lg transition-all">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar sesión
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Register Link -->
<div class="max-w-md mx-auto mt-6">
    <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
        <p class="text-gray-600 text-sm mb-3">
            ¿No tienes cuenta aún?
        </p>
        <a href="{{ route('registro') }}" 
           class="inline-block bg-white text-blue-600 border-2 border-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 hover:text-white transition-colors">
            <i class="fas fa-user-plus mr-2"></i>
            Crear cuenta nueva
        </a>
    </div>
</div>

<!-- Info Section -->
<div class="max-w-4xl mx-auto mt-12">
    <div class="bg-blue-50 rounded-xl p-6 text-center">
        <h3 class="text-xl font-bold text-gray-800 mb-3">
            <i class="fas fa-medical-bag text-blue-600 mr-2"></i>
            Acceso a tu área personal
        </h3>
        <p class="text-gray-600 text-sm max-w-2xl mx-auto">
            Una vez iniciada la sesión podrás acceder a tu historial médico, agendar citas y 
            consultar resultados de estudios.
        </p>
    </div>
</div>

@endsection

@section('scripts')
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection