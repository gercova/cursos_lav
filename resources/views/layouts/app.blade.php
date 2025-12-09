<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/photos/ipf-logo.png') }}">
    <title>@yield('title')</title>
    <link href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.all.min.css') }}">
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
</head>
<body class="bg-gray-50">
    <!-- Header Fijo -->
    <header class="header-fixed bg-white shadow-sm w-full" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo y botón hamburguesa para móviles -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0">
                        <img class="h-8 w-auto" src="{{ asset('storage/photos/ipf-logo.png') }}" alt="Logo">
                    </a>

                    <!-- Botón hamburguesa para móviles -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden ml-4 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors duration-200">
                        <span class="sr-only">Abrir menú principal</span>
                        <!-- Icono hamburguesa -->
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <!-- Icono X (cerrar) -->
                        <svg x-show="mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Navegación para desktop -->
                    <nav class="hidden md:ml-6 md:flex space-x-4">
                        <a href="{{ url('/') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Inicio</a>
                        <a href="{{ route('cursos') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Cursos</a>
                        <a href="{{ url('nosotros') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Nosotros</a>
                        <a href="{{ url('contacto') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Contacto</a>
                    </nav>
                </div>

                <!-- Menú de usuario (siempre visible) -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('cart') }}" class="text-gray-500 hover:text-blue-600 relative transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center transition-all duration-200">0</span>
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition-colors duration-200">
                                {{ auth()->user()->names }}
                                <svg class="ml-1 w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Dashboard</a>
                                <a href="{{ route('student.my-courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Mis Cursos</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 shadow-sm hover:shadow-md">Registrarse</a>
                    @endauth
                </div>
            </div>

            <!-- Menú móvil (se muestra al hacer clic en el botón hamburguesa) -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200 mt-2">
                    <a href="{{ url('/') }}" class="text-gray-900 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Inicio</a>
                    <a href="{{ route('cursos') }}" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Cursos</a>
                    <a href="{{ url('nosotros') }}" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Nosotros</a>
                    <a href="{{ url('contacto') }}" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Contacto</a>

                    <!-- Enlaces de autenticación para móviles (solo para usuarios no autenticados) -->
                    @guest
                        <div class="pt-4 pb-3 border-t border-gray-200">
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 shadow-sm">Registrarse</a>
                        </div>
                    @endguest

                    <!-- Menú de usuario para móviles (solo para usuarios autenticados) -->
                    @auth
                        <div class="pt-4 pb-3 border-t border-gray-200">
                            <div class="px-3 py-2">
                                <div class="text-base font-medium text-gray-800">{{ auth()->user()->names }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Dashboard</a>
                                <a href="{{ route('student.my-courses') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Mis Cursos</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="flex content-with-fixed-header">
        <!-- Mobile sidebar toggle -->
        <div class="lg:hidden fixed bottom-4 right-4 z-40">
            <button id="sidebar-toggle" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile sidebar -->
        <div id="mobile-sidebar" class="lg:hidden fixed inset-0 z-30 hidden">
            <div class="fixed inset-0 bg-black bg-opacity-50" id="sidebar-backdrop"></div>
            <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform duration-300 -translate-x-full">
                <div class="p-4 mt-16"> <!-- mt-16 para evitar superposición con header fijo -->
                    <button id="close-sidebar" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Categorías</h3>
                    <ul class="space-y-2" id="mobile-categories-list">
                        <!-- Las categorías se cargarán via JavaScript -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-0"> <!-- ml-64 para compensar el sidebar fijo -->
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Plataforma de Cursos</h3>
                    <p class="text-gray-300">Ofrecemos los mejores cursos online para tu desarrollo profesional.</p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Inicio</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Cursos</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Nosotros</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li>Email: info@plataforma.com</li>
                        <li>Teléfono: +51 123 456 789</li>
                        <li>Dirección: Lima, Perú</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-300">
                <p>&copy; 2024 Plataforma de Cursos. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
