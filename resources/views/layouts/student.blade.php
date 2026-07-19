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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
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
            display: block;
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
            height: 100%;
        }

        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Media queries */
        @media (max-width: 1024px) {

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
        <aside class="sidebar" :class="{ 'mobile-open': mobileMenuOpen, 'collapsed': isDesktop && sidebarCollapsed }">
            <div class="sidebar-header">
                <div class="flex items-center justify-between">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2">
                        <img class="h-8 w-auto" src="{{ $enterprise->logo_path }}" alt="Logo">
                        <span
                            class="font-semibold text-gray-800 text-sm hidden lg:inline">{{ $enterprise->trade_name }}</span>
                    </a>
                    <button @click="toggleMobileMenu" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-times text-gray-500 mr-2"></i>
                    </button>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('student.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                @if ($hasAnyPackage)
                    <hr>
                    {{-- @if ($purchasedPackage && in_array($purchasedPackage->plan_type_id, [5, 6, 7])) --}}
                    @if ($purchasedPackage)
                        <a href="{{ route('company.dashboard-admin') }}"
                            class="sidebar-link {{ request()->routeIs('company.dashboard-admin') ? 'active bg-purple-700' : '' }}">
                            <i class="fa-solid fa-gauge mr-2"></i> Mi panel de empresa
                        </a>
                    @endif
                    <a href="{{ route('company.list') }}"
                        class="sidebar-link {{ request()->routeIs('company.dashboard') ? 'active bg-purple-700' : '' }}">
                        <i class="fa-regular fa-building mr-2"></i> Gestionar mis usuarios
                    </a>
                    <a href="{{ route('company.enroll.users') }}"
                        class="sidebar-link {{ request()->routeIs('company.enroll.*') ? 'active bg-purple-300' : '' }}">
                        <i class="bi bi-book-fill mr-2"></i> Inscribir mis usuarios
                    </a>
                    {{-- Cronograma de Capacitaciones --}}
                    @if (auth()->user()->company_code || (auth()->user()->parent && auth()->user()->parent->company_code))
                        <a href="{{ route('company.schedule') }}"
                            class="sidebar-link {{ request()->routeIs('company.schedule') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i> Programa Anual de Capacitación
                        </a>
                    @endif
                    <hr>
                @else
                    {{-- Cronograma de Capacitaciones para colaboradores asociados --}}
                    @if (auth()->user()->company_code && auth()->user()->parent_id)
                        <hr>
                        <a href="{{ route('company.schedule') }}"
                            class="sidebar-link {{ request()->routeIs('company.schedule') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i> Programa Anual de Capacitación
                        </a>
                        <hr>
                    @endif
                @endif

                <a href="{{ route('student.my-courses') }}"
                    class="sidebar-link {{ request()->routeIs('student.my-courses') ? 'active' : '' }}">
                    <i class="fas fa-book mr-2"></i>
                    <span>Mis Cursos</span>
                </a>
                <a href="{{ route('student.certificates') }}"
                    class="sidebar-link {{ request()->routeIs('student.certificates') ? 'active' : '' }}">
                    <i class="fas fa-certificate mr-2"></i>
                    <span>Certificados</span>
                </a>
                <a href="{{ route('student.exams') }}"
                    class="sidebar-link {{ request()->routeIs('student.exams') ? 'active' : '' }}">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span>Exámenes</span>
                </a>
                <a href="{{ route('student.progress') }}"
                    class="sidebar-link {{ request()->routeIs('student.progress') ? 'active' : '' }}">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span>Mi Progreso</span>
                </a>
                <a href="{{ route('student.profile') }}"
                    class="sidebar-link {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Mi Perfil</span>
                </a>
                @if (auth()->user()->hasPromotionCode())
                    <a href="{{ route('student.affiliate.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('student.affiliate.*') ? 'active' : '' }}">
                        <i class="fas fa-users mr-2"></i>
                        <span>Mis Ventas</span>
                        @php $salesCount = auth()->user()->courses_sold_count ?? 0; @endphp
                        @if ($salesCount > 0)
                            <span class="badge bg-purple-100 text-purple-800">{{ $salesCount }}</span>
                        @endif
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Header -->
        <header class="header" :class="{ 'sidebar-collapsed': isDesktop && sidebarCollapsed }">
            <div class="header-content">
                <div class="header-left">
                    <button class="menu-toggle" @click="toggleSidebar">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                    <span class="logo-text text-gray-800 font-medium hidden sm:inline">
                        {{ $enterprise->trade_name }}
                    </span>
                </div>

                <div class="header-actions">
                    <!-- Notificaciones -->
                    <div class="relative">
                        <button @click="openNotifications = !openNotifications" class="action-button">
                            <i class="far fa-bell text-lg"></i>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="notification-badge bg-red-500"
                                x-cloak></span>
                        </button>
                        <div x-show="openNotifications" @click.away="openNotifications = false" x-cloak
                            class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                            <div class="p-3 border-b bg-gray-50 rounded-t-xl flex items-center justify-between">
                                <h3 class="font-semibold text-xs text-gray-700">Notificaciones</h3>
                                <button x-show="unreadCount > 0" @click.stop="markAllNotificationsAsRead()"
                                    class="text-[10px] text-blue-600 hover:text-blue-800 font-semibold transition">
                                    Marcar leídas
                                </button>
                            </div>
                            <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                                <!-- Loading State -->
                                <div x-show="notificationsLoading" class="p-4 text-center text-gray-500">
                                    <div class="loading-spinner mx-auto mb-2"></div>
                                    <p class="text-xs">Cargando...</p>
                                </div>
                                <!-- Empty State -->
                                <div x-show="!notificationsLoading && notifications.length === 0"
                                    class="p-6 text-center text-gray-500">
                                    <i class="far fa-bell text-gray-300 text-xl mb-1.5 block"></i>
                                    <p class="text-xs font-medium">No tienes notificaciones</p>
                                </div>
                                <!-- Notifications List -->
                                <template x-for="item in notifications" :key="item.id">
                                    <div @click="handleNotificationClick(item)"
                                        class="p-3 hover:bg-gray-50 cursor-pointer transition-all duration-200 flex items-start gap-2.5"
                                        :class="!item.read_at ? 'bg-blue-50/20' : ''">
                                        <div class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-[10px]"
                                            :class="'bg-' + (item.color || 'blue') + '-50 text-' + (item.color || 'blue') +
                                            '-600'">
                                            <i :class="'fas fa-' + (item.icon || 'bell')"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                                <p class="text-xs font-semibold text-gray-900 truncate"
                                                    x-text="item.title"></p>
                                                <span x-show="!item.read_at"
                                                    class="w-1.5 h-1.5 bg-blue-500 rounded-full flex-shrink-0"></span>
                                            </div>
                                            <p class="text-[10px] text-gray-500 line-clamp-2 leading-snug"
                                                x-text="item.message"></p>
                                            <p class="text-[8px] text-gray-400 mt-1" x-text="item.time"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <a href="{{ route('student.notifications') }}"
                                class="block p-2.5 text-center text-blue-600 hover:bg-gray-50 border-t font-semibold text-xs">
                                Ver todas las notificaciones
                            </a>
                        </div>
                    </div>

                    <!-- Carrito -->
                    <a href="{{ route('cart') }}" class="action-button">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="cart-count" class="notification-badge bg-blue-500">0</span>
                    </a>

                    <!-- Perfil -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
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
        <main class="main-content" :class="{ 'sidebar-collapsed': isDesktop && sidebarCollapsed }">
            <div class="content-wrapper">
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                @endif
                @yield('content')
                @yield('scripts')
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
                        <a href="{{ route('paquetes') }}" class="text-gray-400 hover:text-white transition">Servicios
                            para empresas</a>
                        <a href="{{ url('contacto') }}"
                            class="text-gray-400 hover:text-white transition">Contacto</a>
                        <a href="{{ url('privacidad') }}"
                            class="text-gray-400 hover:text-white transition">Privacidad</a>
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
                sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',

                openNotifications: false,
                notifications: [],
                unreadCount: 0,
                notificationsLoading: false,

                init() {
                    // Configurar token CSRF para Axios
                    if (window.axios) {
                        const token = document.querySelector('meta[name="csrf-token"]');
                        if (token) {
                            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
                        }
                    }
                    window.studentDashboard = this;
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

                toggleSidebar() {
                    if (this.isDesktop) {
                        this.sidebarCollapsed = !this.sidebarCollapsed;
                        localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
                    } else {
                        this.mobileMenuOpen = !this.mobileMenuOpen;
                    }
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
                    this.notificationsLoading = true;
                    try {
                        const response = await axios.get('/api/student/notifications');
                        if (response.data && response.data.success) {
                            console.log(response.data);
                            this.notifications = response.data.notifications.slice(0, 5);
                            this.unreadCount = response.data.unreadCount || 0;
                        }
                    } catch (error) {
                        console.error('Error loading notifications:', error);
                    } finally {
                        this.notificationsLoading = false;
                    }
                },

                refreshNotifications() {
                    this.loadNotifications();
                },

                async handleNotificationClick(item) {
                    if (!item.read_at) {
                        try {
                            await axios.post(`/notifications/${item.id}/read`);
                            this.loadNotifications();
                        } catch (error) {
                            console.error('Error marking notification as read:', error);
                        }
                    }
                    if (item.link) {
                        window.location.href = item.link;
                    }
                },

                async markAllNotificationsAsRead() {
                    try {
                        await axios.post('/notifications/read-all');
                        this.loadNotifications();
                        window.dispatchEvent(new CustomEvent('notifications-read-all'));
                    } catch (error) {
                        console.error('Error marking all notifications as read:', error);
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
            to {
                transform: rotate(360deg);
            }
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
