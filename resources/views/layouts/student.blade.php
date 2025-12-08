<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Estudiante - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        .sidebar-collapsed {
            width: 70px;
        }
        .sidebar-expanded {
            width: 250px;
        }
        .content-expanded {
            margin-left: 70px;
        }
        .content-collapsed {
            margin-left: 250px;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .progress-bar {
            transition: width 1s ease-in-out;
        }
        .notification-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header Fijo -->
    <header class="header-fixed bg-white shadow-sm w-full z-40" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo y botón hamburguesa para móviles -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0">
                        <img class="h-8 w-auto" src="{{ asset('storage/photos/ipf-logo.png') }}" alt="Logo">
                    </a>

                    <!-- Título Dashboard -->
                    <span class="ml-4 text-lg font-semibold text-gray-700 hidden md:inline">Dashboard Estudiante</span>

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
                </div>

                <!-- Menú de usuario -->
                <div class="flex items-center space-x-4">
                    <!-- Notificaciones -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-gray-500 hover:text-blue-600 transition-colors duration-200 p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center notification-badge" id="notification-count">0</span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200 max-h-96 overflow-y-auto">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <h3 class="text-sm font-semibold text-gray-700">Notificaciones</h3>
                            </div>
                            <div id="notifications-list">
                                <!-- Notificaciones cargadas via JS -->
                                <div class="px-4 py-3 text-center text-gray-500 text-sm">
                                    Cargando notificaciones...
                                </div>
                            </div>
                            <a href="{{ route('student.notifications') }}" class="block px-4 py-2 text-sm text-center text-blue-600 hover:bg-gray-50 border-t border-gray-100">
                                Ver todas
                            </a>
                        </div>
                    </div>

                    <!-- Carrito -->
                    <a href="{{ route('cart') }}" class="text-gray-500 hover:text-blue-600 relative transition-colors duration-200 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center transition-all duration-200">0</span>
                    </a>

                    <!-- Perfil usuario -->
                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition-colors duration-200">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->names, 0, 1) }}
                            </div>
                            <span class="hidden md:inline">{{ auth()->user()->names }}</span>
                            <svg class="ml-1 w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->names }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-user mr-2"></i>Mi Perfil
                            </a>
                            <a href="{{ route('my-courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-book mr-2"></i>Mis Cursos
                            </a>
                            <a href="{{ route('student.certificates') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-certificate mr-2"></i>Certificados
                            </a>
                            <a href="{{ route('student.progress') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-chart-line mr-2"></i>Mi Progreso
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Menú móvil -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200 mt-2">
                    @auth
                    <div class="px-3 py-2">
                        <div class="text-base font-medium text-gray-800">{{ auth()->user()->names }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="{{ route('my-courses') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-book mr-2"></i>Mis Cursos
                        </a>
                        <a href="{{ route('student.profile') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-user mr-2"></i>Mi Perfil
                        </a>
                        <a href="{{ route('student.certificates') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-certificate mr-2"></i>Certificados
                        </a>
                        <a href="{{ route('student.progress') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-blue-600 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-chart-line mr-2"></i>Mi Progreso
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-700 hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="flex pt-16"> <!-- pt-16 para compensar header fijo -->
        <!-- Sidebar para estudiantes -->
        <aside id="sidebar" class="fixed left-0 top-16 h-screen bg-white shadow-lg sidebar-transition sidebar-expanded z-30 overflow-y-auto hidden lg:block">
            <div class="p-4">
                <!-- Botón colapsar sidebar -->
                <div class="flex justify-end mb-4">
                    <button id="sidebar-toggle-btn" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>

                <!-- Navegación principal -->
                <nav class="space-y-1">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-900 bg-blue-50 hover:bg-blue-100 transition-colors duration-200 group">
                        <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>

                    <a href="{{ route('my-courses') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200 group">
                        <i class="fas fa-book mr-3 text-green-600"></i>
                        <span class="sidebar-text">Mis Cursos</span>
                        <span id="active-courses-badge" class="ml-auto bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full"></span>
                    </a>

                    <a href="{{ route('student.certificates') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200 group">
                        <i class="fas fa-certificate mr-3 text-yellow-600"></i>
                        <span class="sidebar-text">Certificados</span>
                    </a>

                    <a href="{{ route('student.progress') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200 group">
                        <i class="fas fa-chart-line mr-3 text-purple-600"></i>
                        <span class="sidebar-text">Mi Progreso</span>
                    </a>

                    <a href="{{ route('student.exams') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200 group">
                        <i class="fas fa-file-alt mr-3 text-red-600"></i>
                        <span class="sidebar-text">Exámenes</span>
                        <span id="exams-badge" class="ml-auto bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full"></span>
                    </a>

                    <a href="{{ route('student.profile') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200 group">
                        <i class="fas fa-user mr-3 text-gray-600"></i>
                        <span class="sidebar-text">Mi Perfil</span>
                    </a>

                    <a href="{{ route('student.settings') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200 group">
                        <i class="fas fa-cog mr-3 text-gray-600"></i>
                        <span class="sidebar-text">Configuración</span>
                    </a>
                </nav>

                <!-- Sección de cursos en progreso -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 sidebar-text">
                        Cursos en Progreso
                    </h3>
                    <div id="progress-courses-list" class="space-y-2">
                        <!-- Cursos cargados via JS -->
                    </div>
                </div>

                <!-- Sección de metas -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 sidebar-text">
                        Mis Metas
                    </h3>
                    <div class="px-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-600 sidebar-text">Progreso Mensual</span>
                            <span class="text-xs font-semibold" id="monthly-progress">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="monthly-progress-bar" class="bg-green-600 h-2 rounded-full progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Contenido principal -->
        <main id="main-content" class="flex-1 ml-0 lg:ml-64 sidebar-transition p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-8">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <img class="h-8 w-auto" src="{{ asset('storage/photos/ipf-logo.png') }}" alt="Logo">
                    <p class="mt-2 text-gray-400 text-sm">Plataforma de aprendizaje en línea</p>
                </div>
                <div class="flex space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Inicio</a>
                    <a href="{{ route('cursos') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Cursos</a>
                    <a href="{{ url('contacto') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Contacto</a>
                    <a href="{{ url('privacidad') }}" class="text-gray-400 hover:text-white transition-colors duration-200">Privacidad</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Scripts para el dashboard del estudiante -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar sidebar
            initSidebar();

            // Cargar datos del dashboard
            loadDashboardData();
            loadProgressCourses();
            loadNotifications();
            updateCartCount();

            // Configurar eventos
            setupEventListeners();

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

        // Funciones del sidebar
        function initSidebar() {
            const sidebar       = document.getElementById('sidebar');
            const mainContent   = document.getElementById('main-content');
            const toggleBtn     = document.getElementById('sidebar-toggle-btn');
            const sidebarTexts  = document.querySelectorAll('.sidebar-text');

            // Estado inicial del sidebar (expandido)
            let isCollapsed = false;

            // Cargar estado del sidebar desde localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                collapseSidebar();
            }

            // Evento para colapsar/expandir sidebar
            toggleBtn.addEventListener('click', function() {
                if (isCollapsed) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            });

            function collapseSidebar() {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('lg:ml-64');
                mainContent.classList.add('lg:ml-20');
                toggleBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';

                // Ocultar textos del sidebar
                sidebarTexts.forEach(text => {
                    text.classList.add('hidden');
                });

                isCollapsed = true;
                localStorage.setItem('sidebarCollapsed', 'true');
            }

            function expandSidebar() {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainContent.classList.remove('lg:ml-20');
                mainContent.classList.add('lg:ml-64');
                toggleBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';

                // Mostrar textos del sidebar
                sidebarTexts.forEach(text => {
                    text.classList.remove('hidden');
                });

                isCollapsed = false;
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }

        // Cargar datos del dashboard
        async function loadDashboardData() {
            try {
                const response = await axios.get('/api/student/dashboard-stats');
                const data = response.data;

                // Actualizar badges
                if (data.activeCourses) {
                    document.getElementById('active-courses-badge').textContent = data.activeCourses;
                }
                if (data.pendingAssignments) {
                    document.getElementById('assignments-badge').textContent = data.pendingAssignments;
                }
                if (data.upcomingExams) {
                    document.getElementById('exams-badge').textContent = data.upcomingExams;
                }
                if (data.unreadDiscussions) {
                    document.getElementById('discussions-badge').textContent = data.unreadDiscussions;
                }

                // Actualizar progreso mensual
                if (data.monthlyProgress) {
                    const progressBar = document.getElementById('monthly-progress-bar');
                    const progressText = document.getElementById('monthly-progress');

                    progressBar.style.width = data.monthlyProgress + '%';
                    progressText.textContent = data.monthlyProgress + '%';
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        // Cargar cursos en progreso
        async function loadProgressCourses() {
            try {
                const response = await axios.get('/api/student/progress-courses');
                const courses = response.data;
                const container = document.getElementById('progress-courses-list');

                if (courses.length > 0) {
                    container.innerHTML = courses.map(course => `
                        <a href="/course/${course.slug}/learn" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 transition-colors duration-200 group">
                            <div class="flex-shrink-0 w-8 h-8 rounded-md bg-${course.color || 'blue'}-100 flex items-center justify-center">
                                <i class="fas fa-book text-${course.color || 'blue'}-600 text-xs"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-xs font-medium text-gray-900 truncate sidebar-text">${course.title}</p>
                                <div class="flex items-center mt-1">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-${course.color || 'blue'}-600 h-1.5 rounded-full" style="width: ${course.progress || 0}%"></div>
                                    </div>
                                    <span class="ml-2 text-xs text-gray-500 sidebar-text">${course.progress || 0}%</span>
                                </div>
                            </div>
                        </a>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="px-3 py-2 text-center">
                            <p class="text-xs text-gray-500 sidebar-text">No hay cursos activos</p>
                            <a href="{{ route('cursos') }}" class="text-xs text-blue-600 hover:text-blue-800 mt-1 inline-block sidebar-text">Explorar cursos</a>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading progress courses:', error);
            }
        }

        // Cargar notificaciones
        async function loadNotifications() {
            try {
                const response = await axios.get('/api/student/notifications');
                const notifications = response.data.notifications;
                const count = response.data.unreadCount;

                // Actualizar contador
                const badge = document.getElementById('notification-count');
                if (badge) {
                    badge.textContent = count;
                    if (count > 0) {
                        badge.classList.add('notification-badge');
                    } else {
                        badge.classList.remove('notification-badge');
                    }
                }

                // Actualizar lista
                const container = document.getElementById('notifications-list');
                if (notifications.length > 0) {
                    container.innerHTML = notifications.map(notification => `
                        <a href="${notification.link || '#'}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 ${!notification.read_at ? 'bg-blue-50' : ''}">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-${notification.icon || 'bell'} text-${notification.color || 'blue'}-500 mt-1"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                    <p class="text-xs text-gray-500 mt-1">${notification.message}</p>
                                    <p class="text-xs text-gray-400 mt-1">${notification.time}</p>
                                </div>
                                ${!notification.read_at ?
                                    '<div class="flex-shrink-0"><span class="w-2 h-2 bg-blue-500 rounded-full"></span></div>' :
                                    ''
                                }
                            </div>
                        </a>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="px-4 py-3 text-center">
                            <p class="text-sm text-gray-500">No hay notificaciones</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Actualizar contador del carrito
        async function updateCartCount() {
            try {
                const response = await axios.get('/api/cart/count');
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = response.data.count;
                    if (response.data.count > 0) {
                        cartCount.classList.add('animate-pulse');
                        setTimeout(() => {
                            cartCount.classList.remove('animate-pulse');
                        }, 1000);
                    }
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Configurar event listeners adicionales
        function setupEventListeners() {
            // Marcar notificaciones como leídas al hacer clic
            document.addEventListener('click', function(e) {
                if (e.target.closest('#notifications-list a')) {
                    setTimeout(() => {
                        loadNotifications();
                    }, 1000);
                }
            });

            // Actualizar datos cada 60 segundos
            setInterval(() => {
                loadDashboardData();
                loadNotifications();
            }, 60000);
        }

        // Funciones globales para usar en otras páginas
        window.studentDashboard = {
            refreshStats: loadDashboardData,
            refreshNotifications: loadNotifications,
            refreshCourses: loadProgressCourses,
            refreshCart: updateCartCount
        };
    </script>

    @yield('scripts')
</body>
</html>
