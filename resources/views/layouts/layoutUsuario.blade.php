<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fertilia - Clínica de Fertilidad')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Blade Icons - Fluent UI -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/icons-sprite.svg">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/layoutUsuario.css') }}">
    
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="header-gradient shadow-lg">
        <nav class="container mx-auto px-6 py-2">
            <div class="flex justify-center items-center relative">
                <!-- Logo - Positioned absolutely to left -->
                <div class="absolute left-0 flex items-center">
                    <h1 class="text-white text-xl font-bold">
                        <i class="fas fa-heart text-pink-300 mr-2"></i>
                        Fertilia
                    </h1>
                </div>
                
                <!-- Navigation Menu - Perfectly Centered -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link text-white hover:text-blue-100 font-medium px-4 py-2 rounded-lg transition-all">Inicio</a>
                    <a href="{{ route('servicios') }}" class="nav-link text-white hover:text-blue-100 font-medium px-4 py-2 rounded-lg transition-all">Servicios</a>
                    <a href="{{ route('tratamientos') }}" class="nav-link text-white hover:text-blue-100 font-medium px-4 py-2 rounded-lg transition-all">Tratamientos</a>
                </div>
                
                <!-- User Menu - Positioned absolutely to right -->
                <div class="absolute right-0 flex items-center space-x-3">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center text-white hover:text-blue-100 font-medium">
                                <i class="fas fa-user-circle text-xl mr-2"></i>
                                <span class="text-sm">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="py-2">
                                    <a href="#" class="block px-3 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 text-sm">
                                        <i class="fas fa-user mr-2"></i>Mi Perfil
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <a href="#" class="w-full text-left block px-3 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600 text-sm">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="#" class="text-white hover:text-blue-100 font-medium text-sm">
                            Iniciar Sesión
                        </a>
                        <a href="#" class="bg-white text-blue-800 px-3 py-1.5 rounded-full font-medium hover:bg-blue-50 transition-colors text-sm">
                            Registrarse
                        </a>
                    @endauth
                    
                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-white focus:outline-none ml-1">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden mt-4 pb-4 hidden">
                <div class="flex flex-col space-y-2">
                    <a href="#" class="text-white hover:text-blue-100 py-2">Inicio</a>
                    <a href="#" class="text-white hover:text-blue-100 py-2">Servicios</a>
                    <a href="#" class="text-white hover:text-blue-100 py-2">Tratamientos</a>
                    @guest
                        <div class="border-t border-blue-600 pt-2 mt-2">
                            <a href="#" class="text-white hover:text-blue-100 py-2 block">Iniciar Sesión</a>
                            <a href="#" class="text-white hover:text-blue-100 py-2 block">Registrarse</a>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Main Content -->
    <main class="container mx-auto px-6 py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-medium">Por favor, corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer-gradient text-white mt-auto">
        <div class="container mx-auto px-6 py-2">
            <div class="flex justify-center items-center relative">
                <!-- Company Info - Shifted left to align with navigation -->
                <div class="text-center mb-1.5 -ml-16">
                    <div class="flex items-center justify-center mb-0.5">
                        <i class="fas fa-heart text-pink-300 mr-2 text-base"></i>
                        <h3 class="text-base font-bold">Fertilia</h3>
                    </div>
                    <p class="text-blue-100 text-xs mb-0">"Juntos, creamos futuros"</p>
                    <p class="text-blue-100 text-xs">Clínica especializada en fertilidad</p>
                </div>
            </div>
            
            <!-- Treatments Grid - Aligned with header navigation -->
            <div class="flex justify-center mb-1.5 -ml-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-center max-w-4xl">
                    <div class="flex flex-col items-center text-blue-100 hover:text-white transition-colors p-0.5 rounded-lg hover:bg-blue-800/20">
                        <i class="fas fa-baby text-base mb-0.5"></i>
                        <span class="text-xs leading-tight">Embarazo con<br>Gametos Propios</span>
                    </div>
                    <div class="flex flex-col items-center text-blue-100 hover:text-white transition-colors p-0.5 rounded-lg hover:bg-blue-800/20">
                        <i class="fas fa-heart text-base mb-0.5"></i>
                        <span class="text-xs leading-tight">Esperma<br>Donado</span>
                    </div>
                    <div class="flex flex-col items-center text-blue-100 hover:text-white transition-colors p-0.5 rounded-lg hover:bg-blue-800/20">
                        <i class="fas fa-users text-base mb-0.5"></i>
                        <span class="text-xs leading-tight">Método<br>ROPA</span>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-blue-700 pt-1.5 text-center text-blue-100 -ml-16">
                <p class="text-xs">&copy; {{ date('Y') }} Fertilia - Clínica de Fertilidad. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="{{ asset('js/hideAlert.js') }}"></script>
    <script src="{{ asset('js/smoothScrolling.js') }}"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
    
    @yield('scripts')
</body>
</html>
