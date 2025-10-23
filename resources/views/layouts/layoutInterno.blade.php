<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Interno - Fertilia')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/layoutInterno.css') }}">
    
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo y Título -->
                <div class="flex items-center">
                    <i class="fas fa-heart text-blue-600 text-xl mr-3"></i>
                    <h1 class="text-xl font-semibold text-gray-800">Fertilia - Panel interno</h1>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center">
                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">
                                    {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                                </span>
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-gray-700">
                                    {{ Auth::check() ? Auth::user()->name : 'Usuario' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ Auth::check() ? (Auth::user()->role ?? 'Médico') : 'Médico' }}
                                </p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user mr-2 text-gray-400"></i>Mi Perfil
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-6 flex-1">
        <!-- Page Header -->
        @hasSection('page-header')
            <div class="mb-6">
                @yield('page-header')
            </div>
        @endif
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-medium">Por favor, corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="container mx-auto px-6 py-6">
            <div class="text-center">
                <div class="flex items-center justify-center mb-2">
                    <i class="fas fa-heart text-blue-600 text-sm mr-2"></i>
                    <p class="text-sm font-semibold text-gray-800">Fertilia</p>
                </div>
                <div class="w-16 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent mx-auto mb-2"></div>
                <p class="text-xs text-gray-500 font-medium">Grupo 5</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/hideAlerts.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
