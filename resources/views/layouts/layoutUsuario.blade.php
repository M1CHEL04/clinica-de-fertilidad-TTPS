<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fertilia - Clínica de Fertilidad')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/layoutUsuario.css') }}">

    @yield('styles')
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <header class="header-gradient shadow-md">
        <nav class="max-w-screen-xl mx-auto px-4 sm:px-6 py-3">
            <div class="flex items-center justify-between">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <h1 class="text-white text-xl font-bold flex items-center">
                        <i class="fas fa-heart text-pink-300 mr-2"></i> Fertilia
                    </h1>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-white hover:text-blue-100 transition">Inicio</a>
                    <a href="{{ route('servicios') }}" class="text-white hover:text-blue-100 transition">Servicios</a>
                    <a href="{{ route('tratamientos') }}" class="text-white hover:text-blue-100 transition">Tratamientos</a>
                </div>

                <!-- User Menu + Mobile Buttons -->
                <div class="flex items-center space-x-3">

                    @auth
                        <!-- Desktop user dropdown -->
                        <div class="relative group hidden md:block">
                            <button class="flex items-center text-white hover:text-blue-100 font-medium">
                                <i class="fas fa-user-circle text-xl mr-2"></i>
                                <div class="flex flex-col leading-tight text-left">
                                    <span class="text-sm font-semibold">
                                        {{ Auth::user()->nombre }}
                                    </span>
                                    <span class="text-xs text-blue-100">
                                        {{ ucfirst(Auth::user()->rol->nombre ?? 'Sin rol') }}
                                    </span>
                                </div>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>

                            <div class="absolute right-0 w-40 mt-2 bg-white shadow-lg rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                <a href="{{ route('verPerfil') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 text-sm">
                                    <i class="fas fa-user mr-2"></i>Mi Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 text-left text-gray-700 hover:bg-red-50 text-sm">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:block text-white hover:text-blue-100 text-sm">Iniciar Sesión</a>
                        <a href="{{ route('registro') }}" class="hidden md:block bg-white text-blue-800 px-4 py-1.5 rounded-full text-sm hover:bg-blue-50 transition">Registrarse</a>
                    @endauth

                    <!-- Mobile: menu button -->
                    <button id="mobile-menu-button" class="md:hidden text-white text-xl">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Mobile: profile button -->
                    @auth
                        <button id="mobile-user-button" class="md:hidden text-white text-2xl ml-1">
                            <i class="fas fa-user-circle"></i>
                        </button>
                    @endauth
                </div>

            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 bg-blue-900/20 rounded-lg p-4">
                <a href="{{ route('home') }}" class="block text-white py-2">Inicio</a>
                <a href="{{ route('servicios') }}" class="block text-white py-2">Servicios</a>
                <a href="{{ route('tratamientos') }}" class="block text-white py-2">Tratamientos</a>

                @guest
                    <div class="border-t border-blue-600 mt-2 pt-2">
                        <a href="{{ route('login') }}" class="block text-white py-2">Iniciar Sesión</a>
                        <a href="{{ route('registro') }}" class="block text-white py-2">Registrarse</a>
                    </div>
                @endguest
            </div>

            <!-- Mobile User Menu -->
            @auth
                <div id="mobile-user-menu" class="hidden md:hidden mt-3 bg-white rounded-lg shadow-lg">
                    <a href="{{ route('verPerfil') }}" class="block px-4 py-2 text-gray-700 border-b hover:bg-blue-50">
                        <i class="fas fa-user mr-2"></i> Mi Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            @endauth

        </nav>
    </header>

    <!-- MAIN -->
    <main class="flex-grow max-w-screen-xl mx-auto px-4 sm:px-6 py-6">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 text-center">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 text-center">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 text-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>Corrige los siguientes errores:
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer-gradient text-white mt-8">
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 py-6">

            <!-- Logo & phrase -->
            <div class="text-center mb-6">
                <div class="flex justify-center items-center mb-1">
                    <i class="fas fa-heart text-pink-300 mr-2"></i>
                    <h3 class="font-bold text-lg">Fertilia</h3>
                </div>
                <p class="text-blue-100 text-sm">"Juntos, creamos futuros"</p>
                <p class="text-blue-100 text-xs">Clínica especializada en fertilidad</p>
            </div>

            <!-- Treatments -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center max-w-3xl mx-auto mb-6">
                <div class="flex flex-col items-center text-blue-100 hover:text-white transition p-2 rounded-lg hover:bg-blue-800/20">
                    <i class="fas fa-baby text-xl mb-1"></i>
                    <span class="text-sm leading-tight">Embarazo con<br>Gametos Propios</span>
                </div>
                <div class="flex flex-col items-center text-blue-100 hover:text-white transition p-2 rounded-lg hover:bg-blue-800/20">
                    <i class="fas fa-heart text-xl mb-1"></i>
                    <span class="text-sm leading-tight">Esperma Donado</span>
                </div>
                <div class="flex flex-col items-center text-blue-100 hover:text-white transition p-2 rounded-lg hover:bg-blue-800/20">
                    <i class="fas fa-users text-xl mb-1"></i>
                    <span class="text-sm leading-tight">Método ROPA</span>
                </div>
            </div>

            <div class="border-t border-blue-700 pt-3 text-center text-blue-100 text-xs">
                © {{ date('Y') }} Fertilia - Clínica de Fertilidad. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        const menuBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const userBtn = document.getElementById('mobile-user-button');
        const userMenu = document.getElementById('mobile-user-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            userMenu?.classList.add('hidden');
        });

        if (userBtn) {
            userBtn.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
                mobileMenu.classList.add('hidden');
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
