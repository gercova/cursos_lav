<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path }}">
    <title>Dashboard Estudiante - @yield('title')</title>
    <link href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!--<link rel="stylesheet" href="{{ asset('css/font-awesome.all.min.css') }}">-->
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <script src="{{ asset('js/alpine.js') }}" defer></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
</head>
<body class="bg-gray-50">
    <!-- Header Fijo -->
    <header class="header-fixed header-glass">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo y botón hamburguesa -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0">
                        <img class="h-8 w-auto" src="{{ $enterprise->logo_path }}" alt="Logo {{ $enterprise->trade_name }}">
                    </a>

                    <!-- Título Dashboard -->
                    <span class="ml-4 text-lg font-semibold text-gray-800 hidden md:inline">{{ $enterprise->trade_name }} - Mi Dashboard</span>

                    <!-- Botón hamburguesa para móviles -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden ml-4 inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                        <span class="sr-only">Abrir menú principal</span>
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Menú de usuario -->
                <div class="flex items-center space-x-3">
                    <!-- Notificaciones -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span id="notification-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full notification-badge">0</span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl py-1 z-50 border border-gray-200 max-h-96 overflow-y-auto custom-scrollbar">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                                <h3 class="text-sm font-semibold text-gray-700">Notificaciones</h3>
                            </div>
                            <div id="notifications-list" class="divide-y divide-gray-100">
                                <div class="px-4 py-8 text-center">
                                    <div class="loading-spinner w-6 h-6 mx-auto mb-2"></div>
                                    <p class="text-sm text-gray-500">Cargando notificaciones...</p>
                                </div>
                            </div>
                            <a href="{{ route('student.notifications') }}" class="block px-4 py-3 text-sm text-center text-blue-600 hover:bg-gray-50 border-t border-gray-100 font-medium">
                                Ver todas las notificaciones
                            </a>
                        </div>
                    </div>

                    <!-- Carrito -->
                    <a href="{{ route('cart') }}" class="relative p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full transition-all duration-200">0</span>
                    </a>

                    <!-- Perfil usuario -->
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition-all duration-200 group">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                {{ substr(auth()->user()->names, 0, 1) }}
                            </div>
                            <span class="hidden md:inline">{{ auth()->user()->names }}</span>
                            <svg class="ml-1 w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        @include('layouts.partials.student-profile')
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar fijo para estudiantes -->
    <aside id="sidebar" class="sidebar-fixed sidebar-transition sidebar-expanded bg-white custom-scrollbar hidden lg:block">
        <div class="p-4">
            <!-- Navegación principal -->
            <nav class="space-y-1">

                <!-- Botón colapsar sidebar -->
                <div class="flex justify-end mb-6">
                    <span class="sidebar-text font-medium rounded-lg">Panel de Administración</span>
                    <button id="sidebar-toggle-btn" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                </div>
                <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student.dashboard') ? 'text-gray-900 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group sidebar-link-hover' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 group sidebar-link-hover' }}">
                    <i class="fas fa-tachometer-alt mr-3 text-blue-600 text-base"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('student.my-courses') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student.my-courses') ? 'text-gray-900 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group sidebar-link-hover' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 group sidebar-link-hover' }}">
                    <i class="fas fa-book mr-3 text-emerald-600 text-base"></i>
                    <span class="sidebar-text">Mis Cursos</span>
                    {{-- <span id="active-courses-badge" class="ml-auto bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full font-medium"></span> --}}
                </a>

                <a href="{{ route('student.certificates') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student.certificates') ? 'text-gray-900 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group sidebar-link-hover' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 group sidebar-link-hover' }}">
                    <i class="fas fa-certificate mr-3 text-amber-600 text-base"></i>
                    <span class="sidebar-text">Certificados</span>
                </a>

                <a href="{{ route('student.progress') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student.progress') ? 'text-gray-900 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group sidebar-link-hover' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 group sidebar-link-hover' }}">
                    <i class="fas fa-chart-line mr-3 text-purple-600 text-base"></i>
                    <span class="sidebar-text">Mi Progreso</span>
                </a>

                <a href="{{ route('student.exams') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student.exams') ? 'text-gray-900 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group sidebar-link-hover' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 group sidebar-link-hover' }}">
                    <i class="fas fa-file-alt mr-3 text-rose-600 text-base"></i>
                    <span class="sidebar-text">Exámenes</span>
                    {{-- <span id="exams-badge" class="ml-auto bg-rose-100 text-rose-800 text-xs px-2 py-1 rounded-full font-medium"></span> --}}
                </a>

                <a href="{{ route('student.profile') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('student.profile') ? 'text-gray-900 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group sidebar-link-hover' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 group sidebar-link-hover' }}">
                    <i class="fas fa-user mr-3 text-gray-600 text-base"></i>
                    <span class="sidebar-text">Mi Perfil</span>
                </a>
            </nav>

            <!-- Sección de cursos en progreso -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 sidebar-text">
                    <i class="fas fa-spinner mr-1 text-blue-500"></i> Cursos en Progreso
                </h3>
                <div id="progress-courses-list" class="space-y-3">
                    <div class="px-3 py-4 text-center">
                        <div class="loading-spinner w-6 h-6 mx-auto mb-2"></div>
                        <p class="text-xs text-gray-500">Cargando cursos...</p>
                    </div>
                </div>
            </div>

            <!-- Sección de metas -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 sidebar-text">
                    <i class="fas fa-bullseye mr-1 text-emerald-500"></i> Metas del Mes
                </h3>
                <div class="px-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-600 sidebar-text font-medium">Progreso</span>
                        <span class="text-xs font-bold text-emerald-600" id="monthly-progress">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div id="monthly-progress-bar" class="bg-gradient-to-r from-emerald-400 to-emerald-500 h-2.5 rounded-full progress-bar shadow-sm" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 sidebar-text">Completa tus cursos para alcanzar tus metas</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Contenido principal -->
    <div class="main-content-wrapper sidebar-transition">
        <div class="content-container">
            <main class="p-4 lg:p-6">
                @yield('content')
            </main>
        </div>

        <!-- Footer normal (no fijo) -->
        <footer id="main-footer" class="footer-normal sidebar-transition py-6">
            <div class="max-w-full mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <img class="h-7 w-auto opacity-90" src="{{ $enterprise->logo_path }}" alt="Logo">
                        <span class="text-sm text-gray-400">{{ $enterprise->trade_name }} - Plataforma de aprendizaje en línea</span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-200 hover:scale-105 transform">Inicio</a>
                        <a href="{{ route('cursos') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-200 hover:scale-105 transform">Cursos</a>
                        <a href="{{ url('contacto') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-200 hover:scale-105 transform">Contacto</a>
                        <a href="{{ url('privacidad') }}" class="text-sm text-gray-400 hover:text-white transition-colors duration-200 hover:scale-105 transform">Privacidad</a>
                    </div>
                    <div class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} Todos los derechos reservados.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts para el dashboard del estudiante -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar layout
            initLayout();

            // Cargar datos del dashboard
            loadDashboardData();
            loadProgressCourses();
            loadNotifications();
            updateCartCount();

            // Configurar eventos
            setupEventListeners();

            // Efecto de scroll suave
            setupScrollEffects();
        });

        // Funciones del layout
        function initLayout() {
            const sidebar       = document.getElementById('sidebar');
            const mainContent   = document.querySelector('.main-content-wrapper');
            const footer        = document.getElementById('main-footer');
            const toggleBtn     = document.getElementById('sidebar-toggle-btn');
            const sidebarTexts  = document.querySelectorAll('.sidebar-text');

            // Estado inicial del sidebar
            let isCollapsed     = localStorage.getItem('sidebarCollapsed') === 'true';

            // Aplicar estado inicial
            if (isCollapsed) {
                collapseSidebar();
            }

            // Evento para colapsar/expandir sidebar
            toggleBtn.addEventListener('click', toggleSidebar);

            function toggleSidebar() {
                if (isCollapsed) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            }

            function collapseSidebar() {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.add('main-content-collapsed');
                footer.classList.add('footer-collapsed');
                toggleBtn.innerHTML = '<i class="fas fa-chevron-right text-sm"></i>';

                sidebarTexts.forEach(text => text.classList.add('hidden'));
                isCollapsed = true;
                localStorage.setItem('sidebarCollapsed', 'true');

                // Actualizar variables CSS
                updateCSSVariables('collapsed');
            }

            function expandSidebar() {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainContent.classList.remove('main-content-collapsed');
                footer.classList.remove('footer-collapsed');
                toggleBtn.innerHTML = '<i class="fas fa-chevron-left text-sm"></i>';

                sidebarTexts.forEach(text => text.classList.remove('hidden'));
                isCollapsed = false;
                localStorage.setItem('sidebarCollapsed', 'false');

                // Actualizar variables CSS
                updateCSSVariables('expanded');
            }

            // Actualizar variables CSS dinámicamente
            function updateCSSVariables(state) {
                const root = document.documentElement;
                if (state === 'collapsed') {
                    root.style.setProperty('--sidebar-expanded', '250px');
                    root.style.setProperty('--sidebar-collapsed', '70px');
                } else {
                    root.style.setProperty('--sidebar-expanded', '250px');
                    root.style.setProperty('--sidebar-collapsed', '70px');
                }
            }

            // Manejar cambios en el tamaño de ventana
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 1024) {
                    // En móvil/tablet, ajustar layout
                    mainContent.classList.remove('main-content-collapsed');
                    footer.classList.remove('footer-collapsed');
                }
            });

            // Asegurar que el footer se posicione correctamente
            updateFooterPosition();
        }

        function updateFooterPosition() {
            const sidebar   = document.getElementById('sidebar');
            const footer    = document.getElementById('main-footer');

            // Forzar reflow para asegurar posición correcta
            setTimeout(() => {
                footer.style.display = 'none';
                footer.offsetHeight; // Trigger reflow
                footer.style.display = 'block';
            }, 100);
        }

        // Cargar datos del dashboard
        async function loadDashboardData() {
            try {
                const response  = await axios.get('/api/student/dashboard-stats');
                const data      = response.data;

                // Actualizar progreso mensual con animación
                if (data.monthlyProgress !== undefined) {
                    const progressBar = document.getElementById('monthly-progress-bar');
                    const progressText = document.getElementById('monthly-progress');
                    const targetProgress = data.monthlyProgress;

                    // Animar progreso
                    animateProgress(progressBar, progressText, targetProgress);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        function animateProgress(progressBar, progressText, target) {
            let current = 0;
            const increment = target / 50;
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
        }

        // Cargar cursos en progreso
        async function loadProgressCourses() {
            try {
                const response = await axios.get('/api/student/progress-courses');
                const courses = response.data;
                const container = document.getElementById('progress-courses-list');

                if (courses && courses.length > 0) {
                    container.innerHTML = courses.map(course => `
                        <a href="/course/${course.slug}/learn" class="block group animate-slide-in">
                            <div class="flex items-center px-3 py-3 rounded-lg hover:bg-gray-50 transition-all duration-200 border border-gray-100 hover:border-blue-200">
                                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-${course.color || 'blue'}-100 to-${course.color || 'blue'}-50 flex items-center justify-center shadow-sm group-hover:shadow transition-shadow duration-200">
                                    <i class="fas fa-${course.icon || 'book'} text-${course.color || 'blue'}-600 text-sm"></i>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-900 truncate sidebar-text">${course.title}</p>
                                    <div class="flex items-center mt-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-gradient-to-r from-${course.color || 'blue'}-400 to-${course.color || 'blue'}-500 h-full rounded-full transition-all duration-500" style="width: ${course.progress || 0}%"></div>
                                        </div>
                                        <span class="ml-2 text-xs font-bold text-${course.color || 'blue'}-600 sidebar-text">${course.progress || 0}%</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="px-3 py-4 text-center animate-fade-in">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-book-open text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">No tienes cursos activos</p>
                            <a href="{{ route('cursos') }}" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Explorar cursos
                                <i class="fas fa-arrow-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading progress courses:', error);
                const container = document.getElementById('progress-courses-list');
                container.innerHTML = `
                    <div class="px-3 py-4 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-400 text-lg"></i>
                        </div>
                        <p class="text-sm text-gray-600">Error al cargar cursos</p>
                    </div>
                `;
            }
        }

        // Cargar notificaciones
        /*async function loadNotifications() {
            try {
                const response = await axios.get('/api/student/notifications');
                const notifications = response.data.notifications;
                const count = response.data.unreadCount;

                // Actualizar contador
                const badge = document.getElementById('notification-count');
                if (badge) {
                    badge.textContent = count;
                    badge.classList.toggle('notification-badge', count > 0);
                    badge.classList.toggle('bg-red-500', count > 0);
                    badge.classList.toggle('bg-gray-400', count === 0);
                }

                // Actualizar lista
                const container = document.getElementById('notifications-list');
                if (notifications && notifications.length > 0) {
                    container.innerHTML = notifications.slice(0, 5).map(notification => `
                        <a href="${notification.link || '#'}" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-200 ${!notification.read_at ? 'bg-blue-50/50' : ''}">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-${notification.color || 'blue'}-100 flex items-center justify-center">
                                    <i class="fas fa-${notification.icon || 'bell'} text-${notification.color || 'blue'}-500 text-xs"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-semibold text-gray-900 truncate">${notification.title}</p>
                                        ${!notification.read_at ?
                                            '<span class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full ml-2 mt-1"></span>' :
                                            ''
                                        }
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">${notification.message}</p>
                                    <p class="text-xs text-gray-400 mt-2 flex items-center">
                                        <i class="far fa-clock mr-1"></i>
                                        ${notification.time}
                                    </p>
                                </div>
                            </div>
                        </a>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="px-4 py-8 text-center animate-fade-in">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="far fa-bell text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-sm text-gray-600">No hay notificaciones</p>
                            <p class="text-xs text-gray-500 mt-1">Todo está al día</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }*/

        async function loadNotifications() {
            try {
                const response      = await axios.get('/api/student/notifications');
                const notifications = response.data.notifications;
                const count         = response.data.unreadCount;

                // Actualizar contador
                const badge = document.getElementById('notification-count');
                if (badge) {
                    badge.textContent = count;
                    badge.classList.toggle('notification-badge', count > 0);
                    badge.classList.toggle('bg-red-500', count > 0);
                    badge.classList.toggle('bg-gray-400', count === 0);

                    // Animación para nuevas notificaciones
                    if (count > parseInt(badge.dataset.previousCount || 0)) {
                        badge.classList.add('animate-ping');
                        setTimeout(() => {
                            badge.classList.remove('animate-ping');
                        }, 1000);
                    }
                    badge.dataset.previousCount = count;
                }

                // ... resto del código existente ...
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Actualizar contador del carrito
        async function updateCartCount() {
            try {
                const response  = await axios.get('/api/cart/count');
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = response.data.count;
                    cartCount.classList.toggle('bg-blue-500', response.data.count > 0);
                    cartCount.classList.toggle('bg-gray-400', response.data.count === 0);

                    if (response.data.count > 0) {
                        cartCount.classList.add('animate-pulse');
                        setTimeout(() => {
                            cartCount.classList.remove('animate-pulse');
                        }, 2000);
                    }
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Configurar event listeners
        function setupEventListeners() {
            // Marcar notificaciones como leídas
            document.addEventListener('click', function(e) {
                const notificationLink = e.target.closest('#notifications-list a');
                if (notificationLink) {
                    const notificationId = notificationLink.dataset.id;
                    if (notificationId) {
                        axios.post(`/api/student/notifications/${notificationId}/read`);
                    }
                    setTimeout(loadNotifications, 300);
                }
            });

            // Actualizar datos periódicamente
            setInterval(() => {
                loadDashboardData();
                loadNotifications();
                updateCartCount();
            }, 30000);
        }

        // Efectos de scroll
        function setupScrollEffects() {
            const header = document.querySelector('.header-fixed');

            window.addEventListener('scroll', function() {
                const currentScroll = window.pageYOffset;

                // Efecto de sombra en header al hacer scroll
                if (currentScroll > 20) {
                    header.classList.add('shadow-md');
                    header.style.backdropFilter = 'blur(15px)';
                } else {
                    header.classList.remove('shadow-md');
                    header.style.backdropFilter = 'blur(12px)';
                }
            });
        }

        // Funciones globales
        window.studentDashboard = {
            refreshStats: loadDashboardData,
            refreshNotifications: loadNotifications,
            refreshCourses: loadProgressCourses,
            refreshCart: updateCartCount,
            toggleSidebar: function() {
                document.getElementById('sidebar-toggle-btn').click();
            }
        };
    </script>
    <style>
        /* Variables CSS para consistencia */
        :root {
            --sidebar-expanded: 250px;
            --sidebar-collapsed: 70px;
            --header-height: 64px;
        }

        /* Reset para evitar scroll horizontal */
        html, body {
            overflow-x: hidden;
        }

        /* Transiciones suaves */
        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Estados del sidebar */
        .sidebar-expanded {
            width: var(--sidebar-expanded);
        }

        .sidebar-collapsed {
            width: var(--sidebar-collapsed);
        }

        /* Header siempre fijo */
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            z-index: 50;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Sidebar siempre fijo */
        .sidebar-fixed {
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            z-index: 40;
            box-shadow: 1px 0 10px rgba(0, 0, 0, 0.03);
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
        }

        /* Ajuste del contenido principal */
        .main-content-wrapper {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-expanded);
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content-collapsed {
            margin-left: var(--sidebar-collapsed);
        }

        /* Contenido principal con padding para el footer */
        .content-container {
            min-height: calc(100vh - var(--header-height) - 6rem);
            padding: 1.5rem;
        }

        /* Footer normal (no fijo) */
        .footer-normal {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            border-top: 1px solid #374151;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .footer-collapsed {
            /* margin-left: var(--sidebar-collapsed);
            width: calc(100% - var(--sidebar-collapsed)); */
        }

        /* Mejoras visuales */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.08), 0 5px 10px -5px rgba(0, 0, 0, 0.02);
        }

        .progress-bar {
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .notification-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Animaciones */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(8px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Clases de utilidad */
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        .loading-spinner {
            border: 3px solid rgba(243, 244, 246, 0.3);
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Mejoras para el header con efecto glassmorphism */
        .header-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.6);
        }

        /* Efecto hover para enlaces del sidebar */
        .sidebar-link-hover:hover {
            background: linear-gradient(to right, rgba(59, 130, 246, 0.08), transparent);
            border-left: 3px solid #3b82f6;
        }

        /* Badge animado */
        .badge-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Scrollbar personalizado */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Asegurar que el footer siempre esté al final */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Ajustes responsivos */
        @media (max-width: 1024px) {
            .sidebar-fixed {
                display: none;
            }

            .main-content-wrapper {
                margin-left: 0 !important;
            }

            .footer-normal {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }

        @media (max-width: 768px) {
            .content-container {
                padding: 1rem;
            }

            .footer-normal > div {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
        }
    </style>
</body>
</html>
