<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @hasSection('meta')
        @yield('meta')
    @else
        <meta name="description" content="Plataforma líder en capacitación y certificación en Seguridad y Salud en el Trabajo (SST), Gestión de Calidad y Medio Ambiente en Perú. Estudia con {{ $enterprise->trade_name ?? 'nosotros' }}.">
        <meta name="keywords" content="cursos SST Perú, capacitación seguridad y salud en el trabajo, certificación normas ISO, gestión de calidad, medio ambiente, prevención de riesgos, auditoría SST">
        <meta name="author" content="{{ $enterprise->trade_name ?? 'Plataforma de Capacitación' }}">
        <meta name="robots" content="index, follow">

        <meta property="og:title" content="{{ $enterprise->trade_name ?? 'Capacitación Especializada en SST y Calidad' }}">
        <meta property="og:description" content="Impulsa tu carrera profesional con nuestros cursos y diplomados en Seguridad, Salud Ocupacional, Calidad y Medio Ambiente.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        @if(isset($enterprise->logo_path))
            <meta property="og:image" content="{{ asset($enterprise->logo_path) }}">
        @endif
    @endif

    <link rel="icon" type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path }}">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <meta name="google-site-verification" content="aMGrxQTlV-Zasf1Z3OoKmkT-u9prEHoNoUzhQ6zS0hc" />
</head>
<body class="bg-gray-50" x-data="{ mobileMenuOpen: false, mobileSidebarOpen: false }" @close-mobile-sidebar.window="mobileSidebarOpen = false">
    <!-- Header Fijo -->
    <header class="header-fixed bg-white shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo y botón hamburguesa para móviles -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0">
                        <img class="h-8 w-auto" src="{{ $enterprise->logo_path }}" alt="Logo">
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
                        <a href="{{ route('paquetes') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Servicios para empresas</a>
                        <a href="{{ url('nosotros') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Nosotros</a>
                        <a href="{{ url('contacto') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Contacto</a>
                    </nav>
                </div>

                <!-- Menú de usuario (siempre visible) -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role == 'student')
                            <a href="{{ route('cart') }}" class="text-gray-500 hover:text-blue-600 relative transition-colors duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center transition-all duration-200"></span>
                            </a>
                        @endif
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition-colors duration-200">
                                {{ auth()->user()->names }}
                                <svg class="ml-1 w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            @include('layouts.partials.student-profile')
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 shadow-sm hover:shadow-md">Registrarse</a>
                    @endauth
                </div>
            </div>

            <!-- Menú móvil (se muestra al hacer clic en el botón hamburguesa) -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200 mt-2">
                    <a href="{{ url('/') }}" @click="mobileMenuOpen = false" class="text-gray-900 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Inicio</a>
                    <a href="{{ route('cursos') }}" @click="mobileMenuOpen = false" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Cursos</a>
                    <a href="{{ route('paquetes') }}" @click="mobileMenuOpen = false" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Servicios para empresas</a>
                    <a href="{{ route('nosotros') }}" @click="mobileMenuOpen = false" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Nosotros</a>
                    <a href="{{ route('contacto') }}" @click="mobileMenuOpen = false" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Contacto</a>
                </div>
            </div>
        </div>
    </header>

    <div class="flex content-with-fixed-header">
        <!-- Mobile sidebar toggle -->
        <div class="lg:hidden fixed bottom-4 right-4 z-40">
            <button id="sidebar-toggle" @click="mobileSidebarOpen = !mobileSidebarOpen" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile sidebar (Modal) -->
        <div id="mobile-sidebar" 
             x-show="mobileSidebarOpen" 
             class="lg:hidden fixed inset-0 z-50" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:leave="transition ease-in duration-200"
             x-cloak>
            <!-- Backdrop overlay -->
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
                 x-show="mobileSidebarOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"></div>
            
            <!-- Panel content -->
            <div class="fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-white shadow-2xl flex flex-col z-50 transform transition-transform duration-300"
                 x-show="mobileSidebarOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                
                <!-- Header -->
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img class="h-8 w-auto" src="{{ $enterprise->logo_path }}" alt="Logo">
                    </a>
                    <button @click="mobileSidebarOpen = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Scrollable Body -->
                <div class="flex-1 overflow-y-auto px-5 py-6 space-y-8">
                    <!-- Navigation links (similar to mobileMenuOpen) -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menú Principal</h3>
                        <nav class="flex flex-col space-y-1">
                            <a href="{{ url('/') }}" @click="mobileSidebarOpen = false" class="text-gray-900 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2.5 rounded-lg text-base font-medium transition-colors duration-200">
                                <i class="fas fa-home w-6 text-gray-400 mr-2 text-lg"></i> Inicio
                            </a>
                            <a href="{{ route('cursos') }}" @click="mobileSidebarOpen = false" class="text-gray-905 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2.5 rounded-lg text-base font-medium transition-colors duration-200">
                                <i class="fas fa-book w-6 text-gray-400 mr-2 text-lg"></i> Cursos
                            </a>
                            <a href="{{ route('paquetes') }}" @click="mobileSidebarOpen = false" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2.5 rounded-lg text-base font-medium transition-colors duration-200">
                                <i class="fas fa-cubes w-6 text-gray-400 mr-2 text-lg"></i> Servicios Empresas
                            </a>
                            <a href="{{ url('nosotros') }}" @click="mobileSidebarOpen = false" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2.5 rounded-lg text-base font-medium transition-colors duration-200">
                                <i class="fas fa-info-circle w-6 text-gray-400 mr-2 text-lg"></i> Nosotros
                            </a>
                            <a href="{{ url('contacto') }}" @click="mobileSidebarOpen = false" class="text-gray-500 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2.5 rounded-lg text-base font-medium transition-colors duration-200">
                                <i class="fas fa-envelope w-6 text-gray-400 mr-2 text-lg"></i> Contacto
                            </a>
                        </nav>
                    </div>

                    <!-- Categories section (dynamic or static if available) -->
                    <div id="sidebar-categories-section" class="space-y-4 hidden">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Categorías de Cursos</h3>
                        <ul class="space-y-1.5" id="mobile-categories-list">
                            <!-- Populated dynamically via Javascript from #category-filter -->
                        </ul>
                    </div>

                    <!-- User Account / Actions -->
                    <div class="space-y-4 pt-4 border-t border-gray-100">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Cuenta</h3>
                        @auth
                            <div class="px-3 py-2 bg-gray-50 rounded-xl mb-4">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->names }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <nav class="flex flex-col space-y-1">
                                @if(auth()->user()->role == 'student')
                                    <a href="{{ route('student.dashboard') }}" class="text-gray-700 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-tachometer-alt w-5 text-gray-400 mr-2"></i> Mi Dashboard
                                    </a>
                                    <a href="{{ route('student.profile') }}" class="text-gray-700 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-user w-5 text-gray-400 mr-2"></i> Mi Perfil
                                    </a>
                                    <a href="{{ route('student.my-courses') }}" class="text-gray-700 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-play-circle w-5 text-gray-400 mr-2"></i> Mis Cursos
                                    </a>
                                @elseif(auth()->user()->role == 'admin' || auth()->user()->role == 'instructor')
                                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-tachometer-alt w-5 text-gray-400 mr-2"></i> Dashboard Admin
                                    </a>
                                @elseif(auth()->user()->role == 'business')
                                    <a href="{{ route('company.list') }}" class="text-gray-700 hover:text-blue-600 hover:bg-gray-50 flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-building w-5 text-gray-400 mr-2"></i> Panel Empresa
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-red-600 hover:bg-red-50 flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt w-5 text-red-500 mr-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </nav>
                        @else
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('login') }}" class="text-center py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">Iniciar Sesión</a>
                                <a href="{{ route('register') }}" class="text-center py-2.5 bg-blue-600 rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">Registrarse</a>
                            </div>
                        @endauth
                    </div>
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
                    <h3 class="text-lg font-semibold mb-4">{{ $enterprise->trade_name }}</h3>
                    <p class="text-gray-300">Ofrecemos los mejores cursos especializados en Seguridad y Salud en el Trabajo, Medio Ambiente y Calidad para tu desarrollo y crecimiento profesional.</p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ url('/') }}" class="text-gray-300 hover:text-white">Inicio</a></li>
                        <li><a href="{{ route('cursos') }}" class="text-gray-300 hover:text-white">Cursos</a></li>
                        <li><a href="{{ route('paquetes') }}" class="text-gray-300 hover:text-white">Servicios para empresas</a></li>
                        <li><a href="{{ route('nosotros') }}" class="text-gray-300 hover:text-white">Nosotros</a></li>
                        <li><a href="{{ route('contacto') }}" class="text-gray-300 hover:text-white">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li>Email: {{ $enterprise->email }}</li>
                        <li>Teléfono: +51 {{ $enterprise->phone_number_1 }}</li>
                        {{-- <li>Dirección: Lima, Perú</li> --}}
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
                <p>{{ $enterprise->trade_name }} &copy; 2024 Plataforma de Cursos. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    {{-- =====================================================================
        COOKIE CONSENT SYSTEM
        - Banner principal (aparece en todas las páginas)
        - Modal de preferencias granulares
        - Botón flotante para reabrir preferencias (post-consentimiento)
        Almacena en localStorage bajo la clave "cookie_consent" con estructura:
        { version, status, preferences: {necessary, analytics, marketing, functional}, timestamp }
        ===================================================================== --}}
 
    {{-- Banner principal --}}
    <div id="cookie-consent-banner" class="fixed bottom-0 left-0 right-0 z-[9998] transform translate-y-full transition-transform duration-500 ease-in-out" role="dialog" aria-label="Aviso de cookies" aria-live="polite">
        <div class="bg-gray-900 border-t-2 border-amber-500 shadow-2xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                    <!-- Icono + Texto -->
                    <div class="flex items-start gap-3 flex-1">
                        <div class="flex-shrink-0 mt-0.5">
                            <span class="text-amber-400 text-2xl">
                                <i class="bi bi-cookie"></i>
                            </span>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-sm sm:text-base mb-1">
                                Usamos cookies para mejorar tu experiencia
                            </h4>
                            <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                                Utilizamos cookies propias y de terceros para el funcionamiento del sitio, analizar el tráfico y personalizar contenido.
                                Puedes aceptarlas, rechazarlas o gestionar tus preferencias.
                                <a href="{{ route('politicas-de-cookies') }}" class="text-amber-400 hover:text-amber-300 underline underline-offset-2 transition-colors">
                                    Más información
                                </a>
                            </p>
                        </div>
                    </div>
                    <!-- Botones de acción -->
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto flex-shrink-0">
                        <button id="cookie-reject-banner" class="flex-1 md:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-gray-300 border border-gray-600 rounded-lg hover:bg-gray-800 hover:text-white transition-colors duration-200 whitespace-nowrap">
                            Rechazar
                        </button>
                        <button id="cookie-settings-banner" class="flex-1 md:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-amber-400 border border-amber-600 rounded-lg hover:bg-amber-900/40 transition-colors duration-200 whitespace-nowrap">
                            <i class="fas fa-sliders-h mr-1"></i> Personalizar
                        </button>
                        <button id="cookie-accept-banner" class="flex-1 md:flex-none px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-amber-600 hover:bg-amber-500 rounded-lg transition-colors duration-200 whitespace-nowrap shadow-md">
                            <i class="fas fa-check mr-1"></i> Aceptar todas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- Modal de preferencias granulares --}}
    <div id="cookie-preferences-modal" class="fixed inset-0 z-[9999] hidden" role="dialog" aria-modal="true" aria-labelledby="cookie-modal-title">
        {{-- Overlay --}}
        <div id="cookie-modal-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        {{-- Panel --}}
        <div class="relative flex items-end sm:items-center justify-center min-h-screen p-0 sm:p-4">
            <div id="cookie-modal-panel" class="relative bg-white w-full sm:max-w-lg sm:rounded-2xl shadow-2xl transform transition-all duration-300 translate-y-full sm:translate-y-4 sm:scale-95 opacity-0 max-h-screen sm:max-h-[90vh] overflow-y-auto">
 
                {{-- Header --}}
                <div class="sticky top-0 bg-white border-b border-gray-100 px-5 py-4 flex items-center justify-between z-10">
                    <div class="flex items-center gap-3">
                        <div class="bg-amber-100 p-2 rounded-lg">
                            <i class="fas fa-cookie-bite text-amber-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 id="cookie-modal-title" class="text-base font-bold text-gray-900">
                                Gestionar preferencias de cookies
                            </h3>
                            <p class="text-xs text-gray-500">Activa o desactiva cada categoría</p>
                        </div>
                    </div>
                    <button id="cookie-modal-close" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors" aria-label="Cerrar">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
 
                {{-- Body --}}
                <div class="px-5 py-5 space-y-4">
 
                    {{-- Cookies necesarias (siempre activas) --}}
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">Cookies necesarias</span>
                                <span class="text-xs font-medium text-white bg-gray-500 px-2 py-0.5 rounded-full">Siempre activas</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Imprescindibles para el funcionamiento básico del sitio: sesión, seguridad (CSRF), carrito de compras.
                            </p>
                        </div>
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="cookie-switch disabled" aria-disabled="true">
                                <span class="cookie-switch__thumb"></span>
                            </div>
                        </div>
                    </div>
 
                    {{-- Cookies funcionales --}}
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">Cookies funcionales</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Recuerdan tus preferencias de idioma, región y configuración de visualización para personalizar tu experiencia.
                            </p>
                        </div>
                        <div class="flex-shrink-0 mt-0.5">
                            <button id="toggle-functional" role="switch" aria-checked="true" data-pref="functional" class="cookie-toggle cookie-switch active">
                                <span class="cookie-switch__thumb"></span>
                            </button>
                        </div>
                    </div>
 
                    {{-- Cookies analíticas --}}
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">Cookies analíticas</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Nos ayudan a entender cómo los usuarios interactúan con el sitio (páginas visitadas, tiempo de sesión, errores). No se comparten con terceros.
                            </p>
                        </div>
                        <div class="flex-shrink-0 mt-0.5">
                            <button id="toggle-analytics" role="switch" aria-checked="true" data-pref="analytics" class="cookie-toggle cookie-switch active">
                                <span class="cookie-switch__thumb"></span>
                            </button>
                        </div>
                    </div>
 
                    {{-- Cookies de marketing --}}
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900">Cookies de marketing</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Permiten mostrarte publicidad relevante y medir la efectividad de nuestras campañas dentro y fuera del sitio.
                            </p>
                        </div>
                        <div class="flex-shrink-0 mt-0.5">
                            <button id="toggle-marketing" role="switch" aria-checked="false" data-pref="marketing" class="cookie-toggle cookie-switch">
                                <span class="cookie-switch__thumb"></span>
                            </button>
                        </div>
                    </div>
 
                    {{-- Nota legal --}}
                    <p class="text-xs text-gray-400 text-center leading-relaxed px-2">
                        Tu elección se guarda en este navegador durante 1 año. Puedes cambiarla en cualquier momento desde
                        <a href="{{ route('politicas-de-cookies') }}" class="text-amber-500 hover:text-amber-600 underline underline-offset-1">nuestra política de cookies</a>.
                    </p>
                </div>
 
                {{-- Footer del modal --}}
                <div class="sticky bottom-0 bg-white border-t border-gray-100 px-5 py-4 flex flex-col sm:flex-row gap-2">
                    <button id="cookie-reject-modal" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                        Rechazar opcionales
                    </button>
                    <button id="cookie-save-preferences" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-500 rounded-xl transition-colors duration-200 shadow">
                        <i class="fas fa-save mr-1.5"></i> Guardar preferencias
                    </button>
                    <button id="cookie-accept-all-modal" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-xl transition-colors duration-200 shadow">
                        <i class="fas fa-check-double mr-1.5"></i> Aceptar todas
                    </button>
                </div>
            </div>
        </div>
    </div>
 
    {{-- Botón flotante para reabrir preferencias (visible solo después de haber dado consentimiento) --}}
    <button id="cookie-reopen-btn" onclick="CookieConsent.openModal()" title="Gestionar preferencias de cookies" aria-label="Gestionar preferencias de cookies" class="hidden fixed bottom-4 left-4 z-[9990] bg-gray-800 hover:bg-gray-700 text-white p-2.5 rounded-full shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
        <i class="fas fa-cookie-bite text-amber-400 text-base"></i>
    </button>
 
    {{-- =====================================================================
        SCRIPT GLOBAL DE CONSENTIMIENTO DE COOKIES
        ===================================================================== --}}
    <script>
        window.CookieConsent = (function () {
    
            // ── Configuración ────────────────────────────────────────────────────
            const STORAGE_KEY    = 'cookie_consent';
            const POLICY_VERSION = '1.0';           
            const EXPIRY_DAYS    = 365;
    
            // ── Elementos del DOM ────────────────────────────────────────────────
            const banner    = document.getElementById('cookie-consent-banner');
            const modal     = document.getElementById('cookie-preferences-modal');
            const overlay   = document.getElementById('cookie-modal-overlay');
            const panel     = document.getElementById('cookie-modal-panel');
            const reopenBtn = document.getElementById('cookie-reopen-btn');
            const toggles   = document.querySelectorAll('.cookie-toggle');
    
            // ── Helpers de almacenamiento ────────────────────────────────────────
            function _save(data) {
                try {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
                } catch (e) {
                    console.warn('[CookieConsent] No se pudo guardar en localStorage:', e);
                }
            }
    
            function _load() {
                try {
                    const raw = localStorage.getItem(STORAGE_KEY);
                    if (!raw) return null;
                    const data = JSON.parse(raw);
    
                    // Invalida si es una versión de política distinta
                    if (data.version !== POLICY_VERSION) return null;
    
                    // Invalida si han pasado más de EXPIRY_DAYS días
                    const ageMs = Date.now() - (data.timestamp || 0);
                    if (ageMs > EXPIRY_DAYS * 24 * 60 * 60 * 1000) return null;
    
                    return data;
                } catch (e) {
                    return null;
                }
            }
    
            // ── Lógica del banner ────────────────────────────────────────────────
            function _showBanner() {
                // Espera un tick para que la transición CSS sea visible
                setTimeout(() => {
                    banner.classList.remove('translate-y-full');
                }, 50);
                reopenBtn.classList.add('hidden');
            }
    
            function _hideBanner() {
                banner.classList.add('translate-y-full');
            }
    
            function _showReopenButton() {
                reopenBtn.classList.remove('hidden');
            }
    
            // ── Lógica del modal ─────────────────────────────────────────────────
            function _openModal() {
                _hideBanner();
                _syncTogglesToStorage();
    
                modal.classList.remove('hidden');
                // Trigger reflow para que las transiciones funcionen
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        overlay.classList.remove('opacity-0');
                        overlay.classList.add('opacity-100');
                        panel.classList.remove('translate-y-full', 'sm:translate-y-4', 'sm:scale-95', 'opacity-0');
                        panel.classList.add('translate-y-0', 'sm:translate-y-0', 'sm:scale-100', 'opacity-100');
                    });
                });
    
                // Foco accesible
                setTimeout(() => panel.querySelector('button')?.focus(), 300);
            }
    
            function _closeModal(showBannerAgainIfNoConsent) {
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
                panel.classList.add('translate-y-full', 'sm:translate-y-4', 'sm:scale-95', 'opacity-0');
                panel.classList.remove('translate-y-0', 'sm:translate-y-0', 'sm:scale-100', 'opacity-100');
    
                setTimeout(() => {
                    modal.classList.add('hidden');
                    if (showBannerAgainIfNoConsent && !_load()) {
                        _showBanner();
                    }
                }, 300);
            }
    
            // ── Sincronización de toggles con estado guardado ────────────────────
            function _syncTogglesToStorage() {
                const consent = _load();
                const prefs   = consent?.preferences ?? { functional: true, analytics: true, marketing: false };
    
                toggles.forEach(btn => {
                    const pref    = btn.dataset.pref;
                    const isActive = prefs[pref] !== false; // default true excepto marketing
                    _setToggle(btn, isActive);
                });
            }
    
            function _setToggle(btn, active) {
                btn.setAttribute('aria-checked', active ? 'true' : 'false');
                if (active) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            }
    
            function _readToggles() {
                const prefs = { necessary: true };
                toggles.forEach(btn => {
                    prefs[btn.dataset.pref] = btn.getAttribute('aria-checked') === 'true';
                });
                return prefs;
            }
    
            // ── Acciones principales ─────────────────────────────────────────────
            function _acceptAll() {
                _save({
                    version     : POLICY_VERSION,
                    status      : 'accepted',
                    preferences : { necessary: true, functional: true, analytics: true, marketing: true },
                    timestamp   : Date.now()
                });
                _hideBanner();
                _closeModal(false);
                _showReopenButton();
                _dispatchEvent('accepted');
            }
    
            function _rejectAll() {
                _save({
                    version     : POLICY_VERSION,
                    status      : 'rejected',
                    preferences : { necessary: true, functional: false, analytics: false, marketing: false },
                    timestamp   : Date.now()
                });
                _hideBanner();
                _closeModal(false);
                _showReopenButton();
                _dispatchEvent('rejected');
            }
    
            function _saveCustom() {
                const prefs = _readToggles();
                _save({
                    version     : POLICY_VERSION,
                    status      : 'custom',
                    preferences : prefs,
                    timestamp   : Date.now()
                });
                _closeModal(false);
                _showReopenButton();
                _dispatchEvent('custom', prefs);
            }
    
            function _revokeConsent() {
                try { localStorage.removeItem(STORAGE_KEY); } catch(e) {}
                reopenBtn.classList.add('hidden');
                _showBanner();
                _dispatchEvent('revoked');
            }
    
            // ── Evento personalizado para integraciones externas ─────────────────
            function _dispatchEvent(action, prefs) {
                window.dispatchEvent(new CustomEvent('cookieConsentUpdated', {
                    detail: { action, preferences: prefs ?? _load()?.preferences }
                }));
            }
    
            // ── Inicialización ───────────────────────────────────────────────────
            function _init() {
                const consent = _load();
    
                if (consent) {
                    // Ya tiene consentimiento válido → mostrar solo el botón flotante
                    _showReopenButton();
                } else {
                    // Sin consentimiento → mostrar banner después de 1.5 s
                    setTimeout(_showBanner, 1500);
                }
    
                // ── Event listeners ──────────────────────────────────────────────
    
                // Banner
                document.getElementById('cookie-accept-banner') ?.addEventListener('click', _acceptAll);
                document.getElementById('cookie-reject-banner') ?.addEventListener('click', _rejectAll);
                document.getElementById('cookie-settings-banner') ?.addEventListener('click', _openModal);
    
                // Modal — botones
                document.getElementById('cookie-accept-all-modal') ?.addEventListener('click', _acceptAll);
                document.getElementById('cookie-reject-modal') ?.addEventListener('click', _rejectAll);
                document.getElementById('cookie-save-preferences') ?.addEventListener('click', _saveCustom);
                document.getElementById('cookie-modal-close') ?.addEventListener('click', () => _closeModal(true));
    
                // Modal — overlay (clic fuera cierra)
                overlay?.addEventListener('click', () => _closeModal(true));
    
                // Modal — tecla Escape
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                        _closeModal(true);
                    }
                });
    
                // Toggles individuales
                toggles.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const isActive = btn.getAttribute('aria-checked') === 'true';
                        _setToggle(btn, !isActive);
                    });
                });
            }
    
            // ── API pública ──────────────────────────────────────────────────────
            return {
                init          : _init,
                hasConsent    : () => !!_load(),
                getConsent    : _load,
                allows        : (category) => _load()?.preferences?.[category] ?? false,
                openModal     : _openModal,
                acceptAll     : _acceptAll,
                revokeConsent : _revokeConsent,
            };
    
        })();
    
        // Iniciar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function () {
            CookieConsent.init();
        });
    </script>

    <script>
        // 1. Estado global para control de flujo
        window.cartState = {
            isUpdating: false,
            lastCount: null
        };

        async function updateCartCount() {
            // Buscamos el elemento y lo guardamos en una constante
            const cartCountEl = document.getElementById('cart-count');

            // SI NO EXISTE EL ELEMENTO (ej. en el login), CORTAMOS AQUÍ
            if (!cartCountEl) return;
            
            // SI YA SE ESTÁ ACTUALIZANDO, CORTAMOS AQUÍ
            if (window.cartState.isUpdating) return;

            try {
                window.cartState.isUpdating = true;
                const response = await axios.get('/api/cart/count');
                const count = response.data.count;

                // Solo actualizamos si el número cambió para no estresar al navegador
                if (window.cartState.lastCount !== count) {
                    cartCountEl.textContent = count;
                    window.cartState.lastCount = count;

                    if (count > 0) {
                        cartCountEl.classList.add('animate-pulse');
                        setTimeout(() => {
                            // Verificamos de nuevo que el elemento siga ahí antes de quitar la clase
                            const el = document.getElementById('cart-count');
                            if (el) el.classList.remove('animate-pulse');
                        }, 1000);
                    }
                }
            } catch (error) {
                // Silenciamos errores 401 (No autorizado) para que no ensucien la consola en el login
                if (error.response && error.response.status !== 401) {
                    console.error('Error al actualizar contador:', error);
                }
            } finally {
                window.cartState.isUpdating = false;
            }
        }

        // Escuchador para actualizaciones manuales
        window.addEventListener('cart-updated', updateCartCount);
        // Cargar categorías
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();

            // Sidebar móvil (desactivado a favor de mobileMenuOpen global)
            /*
            const sidebarToggle     = document.getElementById('sidebar-toggle');
            const mobileSidebar     = document.getElementById('mobile-sidebar');
            const closeSidebar      = document.getElementById('close-sidebar');
            const sidebarBackdrop   = document.getElementById('sidebar-backdrop');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    mobileSidebar.classList.remove('hidden');
                    setTimeout(() => {
                        const panel = mobileSidebar.querySelector('.w-64');
                        if (panel) panel.classList.remove('-translate-x-full');
                    }, 50);
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', closeMobileSidebar);
            }

            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', closeMobileSidebar);
            }

            function closeMobileSidebar() {
                const panel = mobileSidebar.querySelector('.w-64');
                if (panel) panel.classList.add('-translate-x-full');
                setTimeout(() => {
                    mobileSidebar.classList.add('hidden');
                }, 300);
            }

            // Cerrar menú móvil al hacer clic en un enlace
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    Alpine.store('mobileMenuOpen', false);
                });
            });
            */

            // Dynamically populate categories in the mobile sidebar if category filter exists
            const categoryFilter = document.getElementById('category-filter');
            const mobileCategoriesList = document.getElementById('mobile-categories-list');
            const sidebarCategoriesSection = document.getElementById('sidebar-categories-section');
            if (categoryFilter && mobileCategoriesList && sidebarCategoriesSection) {
                mobileCategoriesList.innerHTML = '';
                const options = Array.from(categoryFilter.options);
                options.slice(1).forEach(option => {
                    const li = document.createElement('li');
                    const a = document.createElement('a');
                    a.href = '#';
                    a.className = 'block px-3 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-lg transition-colors';
                    a.textContent = option.text;
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        categoryFilter.value = option.value;
                        categoryFilter.dispatchEvent(new Event('change'));
                        window.dispatchEvent(new CustomEvent('close-mobile-sidebar'));
                    });
                    li.appendChild(a);
                    mobileCategoriesList.appendChild(li);
                });
                sidebarCategoriesSection.classList.remove('hidden');
            }

            // Efecto de scroll en header
            window.addEventListener('scroll', function() {
                const header = document.querySelector('.header-fixed');
                if (window.scrollY > 10) {
                    header.classList.add('shadow-lg');
                } else {
                    header.classList.remove('shadow-lg');
                }
            });
        });

        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response) {
                    switch (error.response.status) {
                        case 401:
                            // No autorizado - redirigir al login
                            window.location.href = "{{ route('login') }}";
                            break;
                        case 403:
                            // Acceso denegado
                            alert('No tienes permisos para realizar esta acción');
                            break;
                        case 419:
                            // Sesión expirada
                            window.location.href = "{{ route('login') }}";
                            break;
                        case 429:
                            // Demasiadas solicitudes
                            alert('Demasiadas solicitudes. Por favor, espera unos segundos.');
                            break;
                        case 500:
                            // Error del servidor
                            alert('Error interno del servidor. Por favor, intenta más tarde.');
                            break;
                    }
                }
                return Promise.reject(error);
            }
        );
    </script>

    {{-- =====================================================================
        CSS switches de cookies
        ===================================================================== --}}
    <style>
        /* ── Track ─────────────────────────────────────────────────────────── */
        .cookie-switch {
            position:        relative;
            display:         inline-flex;
            align-items:     center;
            width:           48px;
            height:          26px;
            border-radius:   9999px;
            background:      #d1d5db;         /* gray-300  — estado OFF */
            border:          none;
            cursor:          pointer;
            padding:         0;
            transition:      background 0.25s ease, box-shadow 0.2s ease;
            outline:         none;
            flex-shrink:     0;
        }
 
        /* Estado ON */
        .cookie-switch.active {
            background: #f59e0b;              /* amber-500 */
        }
 
        /* Focus ring accesible */
        .cookie-switch:focus-visible {
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.45);
        }
 
        /* Hover ligero */
        .cookie-switch:not(.disabled):hover {
            filter: brightness(1.08);
        }
 
        /* Disabled (cookies necesarias) */
        .cookie-switch.disabled {
            background: #9ca3af;             /* gray-400 */
            cursor:     not-allowed;
            opacity:    0.55;
        }
 
        /* ── Thumb ──────────────────────────────────────────────────────────── */
        .cookie-switch__thumb {
            position:         absolute;
            top:              3px;
            left:             3px;
            width:            20px;
            height:           20px;
            border-radius:    9999px;
            background:       #ffffff;
            box-shadow:       0 1px 4px rgba(0, 0, 0, 0.22),
                              0 0 0 0.5px rgba(0, 0, 0, 0.06);
            transition:       transform 0.25s cubic-bezier(0.34, 1.3, 0.64, 1),
                              box-shadow 0.2s ease;
            pointer-events:   none;
            display:          block;
        }
 
        /* Thumb posición ON: 48 - 3 - 3 - 20 = 22px */
        .cookie-switch.active     .cookie-switch__thumb,
        .cookie-switch.disabled   .cookie-switch__thumb {
            transform: translateX(22px);
        }
 
        /* Pequeño scale al hacer clic */
        .cookie-switch:active:not(.disabled) .cookie-switch__thumb {
            width:     24px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.28);
        }
    </style>
    @yield('scripts')
</body>
</html>
