@extends('layouts.student')
@section('title', 'Dashboard - Estudiante')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Bienvenida y resumen rápido -->
    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <div class="flex items-center mb-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 mr-3">
                        <i class="fas fa-user-graduate mr-1"></i> Estudiante
                    </span>
                    <span class="text-sm text-gray-600" id="current-date">
                        {{ now()->format('l, d F Y') }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Bienvenido de nuevo, {{ auth()->user()->names }}! 👋</h1>
                <p class="text-gray-600 mb-4">Tu progreso actual y próximas actividades</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Progreso Global</p>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                    <div id="global-progress-bar" class="bg-green-600 h-2 rounded-full progress-bar" style="width: 0%"></div>
                                </div>
                                <span id="global-progress-text" class="text-lg font-bold text-gray-900">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-book-open text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Cursos Activos</h3>
                    <p class="text-2xl font-bold text-gray-900" id="stats-courses">0</p>
                    <div class="mt-1">
                        <span class="text-xs text-green-600" id="courses-trend">
                            <i class="fas fa-arrow-up mr-1"></i>0% este mes
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Exámenes Próximos</h3>
                    <p class="text-2xl font-bold text-gray-900" id="stats-exams">0</p>
                    <div class="mt-1">
                        <span class="text-xs text-gray-500" id="next-exam-date">Sin próximos exámenes</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-certificate text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Certificados</h3>
                    <p class="text-2xl font-bold text-gray-900" id="stats-certificates">0</p>
                    <div class="mt-1">
                        <span class="text-xs text-yellow-600">
                            <i class="fas fa-award mr-1"></i>Logros
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Horas de Estudio</h3>
                    <p class="text-2xl font-bold text-gray-900" id="stats-hours">0h</p>
                    <div class="mt-1">
                        <span class="text-xs text-gray-500">Este mes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cursos en progreso -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Cursos en Progreso</h2>
                    <a href="{{ route('student.my-courses') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Ver todos <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    <div id="dashboard-courses-list" class="space-y-4">
                        <!-- Los cursos se cargarán via JavaScript -->
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="mt-4 text-gray-500">Cargando tus cursos...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad reciente -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-8 border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Actividad Reciente</h2>
                    <button onclick="loadRecentActivity()" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div id="recent-activity" class="space-y-4">
                        <!-- La actividad se cargará via JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel derecho -->
        <div class="space-y-8">
            <!-- Próximos eventos -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Próximos Eventos</h2>
                </div>
                <div class="p-6">
                    <div id="upcoming-events" class="space-y-4">
                        <!-- Eventos se cargarán via JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Logros -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Mis Logros</h2>
                </div>
                <div class="p-6">
                    <div id="achievements" class="space-y-4">
                        <!-- Logros se cargarán via JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('cursos') }}" class="bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-700 rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 border border-blue-200 card-hover">
                            <i class="fas fa-search text-xl mb-2"></i>
                            <span class="text-sm font-medium">Buscar Cursos</span>
                        </a>
                        <a href="{{ route('student.my-courses') }}" class="bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 text-green-700 rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 border border-green-200 card-hover">
                            <i class="fas fa-play text-xl mb-2"></i>
                            <span class="text-sm font-medium">Continuar</span>
                        </a>
                        <a href="{{ route('student.exams') }}" class="bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 text-red-700 rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 border border-red-200 card-hover">
                            <i class="fas fa-file-alt text-xl mb-2"></i>
                            <span class="text-sm font-medium">Exámenes</span>
                        </a>
                        <a href="{{ route('student.certificates') }}" class="bg-gradient-to-r from-yellow-50 to-yellow-100 hover:from-yellow-100 hover:to-yellow-200 text-yellow-700 rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 border border-yellow-200 card-hover">
                            <i class="fas fa-certificate text-xl mb-2"></i>
                            <span class="text-sm font-medium">Certificados</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metas del día -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
                <h3 class="font-semibold text-gray-900 mb-4">Metas del Día</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Lecciones completadas</span>
                            <span>0/3</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Minutos de estudio</span>
                            <span>0/60min</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Función para formatear fechas
    function formatDate(date) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('es-ES', options);
    }

    // Actualizar fecha actual
    document.getElementById('current-date').textContent = formatDate(new Date());

    // Cargar datos específicos de la página de dashboard
    async function loadDashboardCourses() {
        try {
            const response = await axios.get('/api/student/dashboard-courses');
            const courses = response.data;
            const container = document.getElementById('dashboard-courses-list');

            if (courses.length > 0) {
                container.innerHTML = courses.map(course => `
                    <a href="/course/${course.slug}/learn" class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 card-hover">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-${course.color || 'blue'}-100 flex items-center justify-center">
                            <i class="fas fa-${course.icon || 'book'} text-${course.color || 'blue'}-600 text-lg"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="font-medium text-gray-900 truncate">${course.title}</h3>
                                <span class="text-sm font-bold text-${course.color || 'blue'}-600 ml-2">${course.progress}%</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">${course.instructor}</p>
                            <div class="flex items-center mt-3">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-${course.color || 'blue'}-600 h-2 rounded-full progress-bar" style="width: ${course.progress}%"></div>
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-2">
                                <span class="flex items-center">
                                    <i class="far fa-clock mr-1"></i>${course.last_activity}
                                </span>
                                <span>${course.next_lesson || 'Próxima lección'}</span>
                            </div>
                        </div>
                    </a>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book-open text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 mb-4">No tienes cursos activos</p>
                        <a href="{{ route('cursos') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-search mr-2"></i>
                            Explorar cursos
                        </a>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading dashboard courses:', error);
            document.getElementById('dashboard-courses-list').innerHTML = `
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <p class="text-red-500">Error cargando los cursos</p>
                    <button onclick="loadDashboardCourses()" class="mt-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-redo mr-1"></i> Reintentar
                    </button>
                </div>
            `;
        }
    }

    async function loadRecentActivity() {
        try {
            const container = document.getElementById('recent-activity');
            container.innerHTML = `
                <div class="text-center py-4">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                </div>
            `;

            const response = await axios.get('/api/student/recent-activity');
            const activities = response.data;

            if (activities.length > 0) {
                container.innerHTML = activities.map(activity => `
                    <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-8 h-8 rounded-full bg-${activity.color || 'blue'}-100 flex items-center justify-center">
                                <i class="fas fa-${activity.icon || 'circle'} text-${activity.color || 'blue'}-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-900">${activity.description}</p>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-xs text-gray-500">${activity.time}</p>
                                ${activity.badge ? `
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full ${activity.badge_color || 'bg-blue-100 text-blue-800'}">
                                        ${activity.badge}
                                    </span>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-history text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">No hay actividad reciente</p>
                        <p class="text-sm text-gray-400 mt-1">Empieza a explorar cursos</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading recent activity:', error);
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Error cargando actividad</p>
                </div>
            `;
        }
    }

    async function loadUpcomingEvents() {
        try {
            const response = await axios.get('/api/student/upcoming-events');
            const events = response.data;
            const container = document.getElementById('upcoming-events');

            if (events.length > 0) {
                container.innerHTML = events.map(event => `
                    <a href="${event.link || '#'}" class="flex items-start p-3 rounded-lg border border-gray-200 hover:border-${event.color || 'red'}-300 hover:bg-${event.color || 'red'}-50 transition-colors duration-200">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-${event.color || 'red'}-100 rounded-lg flex flex-col items-center justify-center">
                                <span class="text-xs font-semibold text-${event.color || 'red'}-700">${event.day}</span>
                                <span class="text-sm font-bold text-${event.color || 'red'}-700">${event.date}</span>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-sm font-medium text-gray-900">${event.title}</h4>
                            <p class="text-xs text-gray-500 mt-1">${event.course}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="far fa-clock mr-1"></i>${event.time}
                            </p>
                        </div>
                        <div class="flex-shrink-0 ml-2">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </a>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="far fa-calendar-alt text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">No hay eventos próximos</p>
                        <p class="text-sm text-gray-400 mt-1">Disfruta tu tiempo de estudio</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading upcoming events:', error);
        }
    }

    async function loadAchievements() {
        try {
            const response = await axios.get('/api/student/achievements');
            const achievements = response.data;
            const container = document.getElementById('achievements');

            if (achievements.length > 0) {
                container.innerHTML = achievements.map(achievement => `
                    <div class="flex items-center p-3 rounded-lg border border-gray-200 bg-gradient-to-r from-${achievement.color || 'yellow'}-50 to-white">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-${achievement.color || 'yellow'}-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-${achievement.icon || 'trophy'} text-${achievement.color || 'yellow'}-600"></i>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-sm font-medium text-gray-900">${achievement.title}</h4>
                            <p class="text-xs text-gray-500 mt-1">${achievement.description}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-award text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500">Aún no tienes logros</p>
                        <p class="text-sm text-gray-400 mt-1">Completa cursos para desbloquear</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading achievements:', error);
        }
    }

    async function loadStats() {
        try {
            const response = await axios.get('/api/student/dashboard-stats');
            const stats = response.data;

            // Actualizar valores en las cards con animación
            animateCounter('stats-courses', stats.activeCourses || 0);
            animateCounter('stats-exams', stats.upcomingExams || 0);
            animateCounter('stats-certificates', stats.certificatesCount || 0);

            // Progreso global
            const progressBar = document.getElementById('global-progress-bar');
            const progressText = document.getElementById('global-progress-text');
            const progress = stats.monthlyProgress || 0;

            setTimeout(() => {
                progressBar.style.width = `${progress}%`;
                animateCounterText('global-progress-text', progress, '%');
            }, 500);

        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    // Animaciones para contadores
    function animateCounter(elementId, targetValue) {
        const element = document.getElementById(elementId);
        const currentValue = parseInt(element.textContent) || 0;
        const duration = 1000;
        const startTime = Date.now();

        function updateCounter() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const current = Math.floor(progress * (targetValue - currentValue) + currentValue);

            element.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = targetValue;
            }
        }

        updateCounter();
    }

    function animateCounterText(elementId, targetValue, suffix = '') {
        const element = document.getElementById(elementId);
        const currentValue = parseFloat(element.textContent.replace(suffix, '')) || 0;
        const duration = 1000;
        const startTime = Date.now();

        function updateCounter() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const current = (progress * (targetValue - currentValue) + currentValue).toFixed(1);

            element.textContent = current + suffix;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = targetValue.toFixed(1) + suffix;
            }
        }

        updateCounter();
    }

    // Cargar todo al iniciar
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardCourses();
        loadRecentActivity();
        loadUpcomingEvents();
        loadAchievements();
        loadStats();

        // Actualizar cada 30 segundos
        setInterval(() => {
            loadRecentActivity();
            loadUpcomingEvents();
            loadStats();
        }, 30000);
    });
</script>
@endsection
