<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path }}">
    <title>Dashboard Estudiante - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <style>
        /* Variables globales */
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 0px;
            --header-height: 64px;
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f9fafb;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Layout principal */
        .app-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            border-right: 1px solid #e5e7eb;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 60;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            transform: translateX(calc(var(--sidebar-width) * -1));
        }

        /* Header fijo */
        .header {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.6);
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        .header.sidebar-collapsed {
            left: 0;
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-top: var(--header-height);
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - var(--header-height));
            display: flex;
            flex-direction: column;
        }

        .main-content.sidebar-collapsed {
            margin-left: 0;
        }

        /* Contenedor de contenido */
        .content-wrapper {
            flex: 1;
            padding: 1.5rem;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: #9ca3af;
            padding: 1.5rem;
            border-top: 1px solid #374151;
            width: 100%;
        }

        .footer-content {
            max-width: 1600px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        /* Botón hamburguesa */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #4b5563;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .menu-toggle:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        /* Overlay para móvil */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 55;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Elementos del sidebar */
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.75rem;
            color: #4b5563;
            transition: all 0.2s;
            font-weight: 500;
            text-decoration: none;
        }

        .sidebar-link i {
            width: 1.5rem;
            font-size: 1.1rem;
            margin-right: 0.75rem;
            color: #6b7280;
            transition: color 0.2s;
        }

        .sidebar-link:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .sidebar-link:hover i {
            color: var(--primary-color);
        }

        .sidebar-link.active {
            background: linear-gradient(to right, #eff6ff, #dbeafe);
            color: #1f2937;
            border-left: 3px solid var(--primary-color);
        }

        .sidebar-link.active i {
            color: var(--primary-color);
        }

        /* Badges y contadores */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            margin-left: auto;
        }

        /* Grid responsivo */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Tarjetas responsivas */
        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
            height: 100%;
        }

        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Media queries */
        @media (max-width: 1024px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(calc(var(--sidebar-width) * -1));
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: block;
            }

            .content-wrapper {
                padding: 1rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 1rem;
            }

            .header-content {
                gap: 0.5rem;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .user-info span {
                display: none;
            }

            .card-body {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .content-wrapper {
                padding: 0.75rem;
            }

            .header-actions {
                gap: 0.25rem;
            }

            .action-button {
                padding: 0.5rem;
            }

            .logo-text {
                display: none;
            }
        }

        /* Utilidades adicionales */
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .action-button {
            position: relative;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            color: #4b5563;
            transition: all 0.2s;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .action-button:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            min-width: 1.25rem;
            height: 1.25rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transform: translate(25%, -25%);
        }

        /* Scrollbar personalizado */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="app-layout" x-data="dashboardLayout()" x-init="init()">
        <!-- Overlay para móvil -->
        <div class="sidebar-overlay" :class="{ 'active': mobileMenuOpen }" @click="toggleMobileMenu"></div>

        <!-- Sidebar -->
        <aside class="sidebar" :class="{ 'mobile-open': mobileMenuOpen }">
            <div class="sidebar-header">
                <div class="flex items-center justify-between">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <img class="h-8 w-auto" src="{{ $enterprise->logo_path }}" alt="Logo">
                        <span class="font-semibold text-gray-800 text-sm hidden lg:inline">{{ $enterprise->trade_name }}</span>
                    </a>
                    <button @click="toggleMobileMenu" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-times text-gray-500"></i>
                    </button>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                @if ($hasAnyPackage)
                    <hr>
                    <a href="{{ route('company.list') }}" class="sidebar-link {{ request()->routeIs('company.dashboard') ? 'active bg-purple-700' : '' }}">
                        <i class="fa-solid fa-gauge mr-2"></i> Mi panel de empresa
                    </a>
                    
                    <a href="{{ route('company.enroll.users') }}" class="sidebar-link {{ request()->routeIs('company.enroll.*') ? 'active bg-purple-300' : '' }}">
                        <i class="bi bi-book-fill mr-2"></i> Inscribir mis usuarios
                    </a>
                    <hr>
                @endif

                <a href="{{ route('student.my-courses') }}" class="sidebar-link {{ request()->routeIs('student.my-courses') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Mis Cursos</span>
                </a>

                <a href="{{ route('student.certificates') }}" class="sidebar-link {{ request()->routeIs('student.certificates') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i>
                    <span>Certificados</span>
                </a>

                <a href="{{ route('student.exams') }}" class="sidebar-link {{ request()->routeIs('student.exams') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Exámenes</span>
                </a>

                <a href="{{ route('student.profile') }}" class="sidebar-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Mi Perfil</span>
                </a>

                @if(auth()->user()->hasPromotionCode())
                    <a href="{{ route('student.affiliate.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.affiliate.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Mis Ventas</span>
                        @php $salesCount = auth()->user()->courses_sold_count ?? 0; @endphp
                        @if($salesCount > 0)
                            <span class="badge bg-purple-100 text-purple-800">{{ $salesCount }}</span>
                        @endif
                    </a>
                @endif
            </nav>

            <!-- Sección de metas (solo en desktop) -->
            <div class="sidebar-footer p-4 border-t border-gray-200 hidden lg:block">
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-3">
                    <i class="fas fa-bullseye text-emerald-500 mr-1"></i> Metas del Mes
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Progreso</span>
                        <span class="font-bold text-emerald-600" id="monthly-progress">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div id="monthly-progress-bar" class="bg-gradient-to-r from-emerald-400 to-emerald-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500">Completa tus cursos</p>
                </div>
            </div>
        </aside>

        <!-- Header -->
        <header class="header" :class="{ 'sidebar-collapsed': !isDesktop && mobileMenuOpen }">
            <div class="header-content">
                <div class="header-left">
                    <button class="menu-toggle" @click="toggleMobileMenu">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                    <span class="logo-text text-gray-800 font-medium hidden sm:inline">
                        {{ $enterprise->trade_name }}
                    </span>
                </div>

                <div class="header-actions">
                    <!-- Notificaciones -->
                    {{-- <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="action-button">
                            <i class="far fa-bell text-lg"></i>
                            <span id="notification-count" class="notification-badge">0</span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50">
                            <div class="p-3 border-b bg-gray-50 rounded-t-xl">
                                <h3 class="font-semibold text-gray-700">Notificaciones</h3>
                            </div>
                            <div id="notifications-list" class="max-h-96 overflow-y-auto">
                                <div class="p-4 text-center text-gray-500">
                                    <div class="loading-spinner mx-auto mb-2"></div>
                                    <p class="text-sm">Cargando...</p>
                                </div>
                            </div>
                            <a href="{{ route('student.notifications') }}" class="block p-3 text-center text-blue-600 hover:bg-gray-50 border-t font-medium text-sm">
                                Ver todas
                            </a>
                        </div>
                    </div> --}}

                    <!-- Carrito -->
                    <a href="{{ route('cart') }}" class="action-button">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count" class="notification-badge bg-blue-500">0</span>
                    </a>

                    <!-- Perfil -->
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ substr(auth()->user()->names, 0, 1) }}
                            </div>
                            <span class="user-info hidden md:block text-sm font-medium text-gray-700">
                                {{ auth()->user()->names }}
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500" :class="{ 'rotate-180': open }"></i>
                        </button>
                        @include('layouts.partials.student-profile')
                    </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Contenido principal -->
        <main class="main-content" :class="{ 'sidebar-collapsed': !isDesktop && mobileMenuOpen }">
            <div class="content-wrapper">
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
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="footer-content">
                    <div class="flex items-center gap-3">
                        <img class="h-6 w-auto opacity-80" src="{{ $enterprise->logo_path }}" alt="Logo">
                        <span class="text-xs text-gray-400">{{ $enterprise->trade_name }}</span>
                    </div>
                    <div class="flex flex-wrap justify-center gap-4 text-xs">
                        <a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition">Inicio</a>
                        <a href="{{ route('cursos') }}" class="text-gray-400 hover:text-white transition">Cursos</a>
                        <a href="{{ route('paquetes') }}" class="text-gray-400 hover:text-white transition">Servicios para empresas</a>
                        <a href="{{ url('contacto') }}" class="text-gray-400 hover:text-white transition">Contacto</a>
                        <a href="{{ url('privacidad') }}" class="text-gray-400 hover:text-white transition">Privacidad</a>
                    </div>
                    <div class="text-xs text-gray-500">
                        &copy; {{ date('Y') }} Todos los derechos reservados.
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <script>
        function dashboardLayout() {
            return {
                mobileMenuOpen: false,
                isDesktop: window.innerWidth >= 1024,
                
                init() {
                    this.checkScreenSize();
                    window.addEventListener('resize', () => this.checkScreenSize());
                    this.loadDashboardData();
                    this.loadNotifications();
                    this.updateCartCount();
                    setInterval(() => {
                        this.loadDashboardData();
                        this.loadNotifications();
                        this.updateCartCount();
                    }, 30000);
                },

                checkScreenSize() {
                    this.isDesktop = window.innerWidth >= 1024;
                    if (this.isDesktop) {
                        this.mobileMenuOpen = false;
                    }
                },

                toggleMobileMenu() {
                    this.mobileMenuOpen = !this.mobileMenuOpen;
                },

                async loadDashboardData() {
                    try {
                        const response = await axios.get('/api/student/dashboard-stats');
                        const data = response.data;
                        
                        if (data.monthlyProgress !== undefined) {
                            this.animateProgress(data.monthlyProgress);
                        }
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                    }
                },

                animateProgress(target) {
                    const progressBar = document.getElementById('monthly-progress-bar');
                    const progressText = document.getElementById('monthly-progress');
                    if (!progressBar || !progressText) return;

                    let current = 0;
                    const increment = target / 30;
                    const interval = setInterval(() => {
                        if (current >= target) {
                            clearInterval(interval);
                            return;
                        }
                        current += increment;
                        if (current > target) current = target;
                        
                        progressBar.style.width = current + '%';
                        progressText.textContent = Math.round(current) + '%';
                    }, 20);
                },

                async loadNotifications() {
                    try {
                        const response = await axios.get('/api/student/notifications');
                        const badge = document.getElementById('notification-count');
                        if (badge) {
                            badge.textContent = response.data.unreadCount || 0;
                        }
                    } catch (error) {
                        console.error('Error loading notifications:', error);
                    }
                },

                async updateCartCount() {
                    try {
                        const response = await axios.get('/api/cart/count');
                        const cartCount = document.getElementById('cart-count');
                        if (cartCount) {
                            cartCount.textContent = response.data.count || 0;
                        }
                    } catch (error) {
                        console.error('Error updating cart count:', error);
                    }
                }
            }
        }
    </script>

    <style>
        /* Loading spinner */
        .loading-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #e5e7eb;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Transiciones */
        .rotate-180 {
            transform: rotate(180deg);
        }

        /* Utilidades responsive */
        @media (max-width: 640px) {
            .action-button {
                padding: 0.4rem;
            }
            
            .action-button i {
                font-size: 1rem;
            }
        }

        /* Mejoras de accesibilidad */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Print styles */
        @media print {
            .sidebar,
            .header,
            .footer,
            .action-button {
                display: none !important;
            }
            
            .main-content {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</body>
</html>