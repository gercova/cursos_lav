<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/photos/ipf-logo.png') }}">
    <title>@yield('title') - Admin</title>
    <link href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <style>
        /* Transición suave para el sidebar */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        /* Overlay para móviles */
        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }

        @media (min-width: 768px) {
            .sidebar-overlay {
                display: none !important;
            }
        }
    </style>
    <script>
        const API_URL = "{{ url('/') }}";

        // Inicializar Alpine.js para el sidebar
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebar', () => ({
                open: window.innerWidth >= 768,

                init() {
                    // Cerrar sidebar automáticamente en móviles
                    if (window.innerWidth < 768) {
                        this.open = false;
                    }

                    // Actualizar estado al cambiar tamaño de ventana
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 768) {
                            this.open = true;
                        } else {
                            this.open = false;
                        }
                    });
                },

                toggle() {
                    this.open = !this.open;
                },

                close() {
                    if (window.innerWidth < 768) {
                        this.open = false;
                    }
                }
            }));
        });
    </script>
</head>
<body class="bg-gray-100">
    <div x-data="sidebar" class="flex h-screen">
        <!-- Overlay para móviles -->
        <div x-show="open && window.innerWidth < 768"
            x-transition:enter="sidebar-overlay"
            x-transition:leave="sidebar-overlay"
            @click="close()"
            class="fixed inset-0 bg-black bg-opacity-50 z-20"
            style="display: none;">
        </div>

        <!-- Sidebar -->
        <div :class="{'translate-x-0': open, '-translate-x-full': !open}" class="sidebar-transition fixed md:relative w-64 bg-blue-800 text-white h-full z-30">
            <div class="p-4 flex justify-between items-center">
                <h1 class="text-xl font-bold">Panel Admin</h1>
                <!-- Botón para cerrar en móviles -->
                <button @click="toggle()" class="md:hidden">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}"
                    @click="close()"
                    class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    @click="close()"
                    class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-folder mr-2"></i>Categorías
                </a>
                <a href="{{ route('admin.courses.index') }}"
                    @click="close()"
                    class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.courses.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-book mr-2"></i>Cursos
                </a>
                <a href="{{ route('admin.documents.index') }}"
                    @click="close()"
                    class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.documents.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-file-alt mr-2"></i>Documentos
                </a>
                <a href="{{ route('admin.exams.index') }}"
                    @click="close()"
                    class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.exams.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-clipboard-list mr-2"></i>Exámenes
                </a>
                <a href="{{ route('admin.users.index') }}"
                    @click="close()"
                    class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-solid fa-users"></i> Usuarios
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <div class="flex items-center">
                        <!-- Botón Hamburguesa -->
                        <button @click="toggle()" class="mr-4 text-gray-700 hover:text-blue-600 md:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">@yield('title')</h2>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->names }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700 flex items-center">
                                <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
