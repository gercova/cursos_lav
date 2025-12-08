@extends('layouts.student')
@section('title', 'Dashboard - Estudiante')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Bienvenida y resumen rápido -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">¡Bienvenido, {{ auth()->user()->names }}!</h1>
        <p class="text-gray-600">Aquí puedes ver tu progreso, cursos activos y próximas actividades.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Cursos Activos</h3>
                    <p class="text-2xl font-bold text-gray-900" id="stats-courses">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Exámenes Próximos</h3>
                    <p class="text-2xl font-bold text-gray-900" id="stats-exams">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 card-hover border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-certificate text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Certificados</h3>
                    <p class="text-2xl font-bold text-gray-900" id="stats-certificates">0</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cursos en progreso -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Cursos en Progreso</h2>
                </div>
                <div class="p-6">
                    <div id="dashboard-courses-list" class="space-y-4">
                        <!-- Los cursos se cargarán via JavaScript -->
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="mt-4 text-gray-500">Cargando cursos...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad reciente -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Actividad Reciente</h2>
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
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
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
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
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
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('cursos') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg p-4 flex flex-col items-center justify-center transition-colors duration-200">
                            <i class="fas fa-search text-xl mb-2"></i>
                            <span class="text-sm font-medium">Buscar Cursos</span>
                        </a>
                        <a href="{{ route('my-courses') }}" class="bg-green-50 hover:bg-green-100 text-green-700 rounded-lg p-4 flex flex-col items-center justify-center transition-colors duration-200">
                            <i class="fas fa-play text-xl mb-2"></i>
                            <span class="text-sm font-medium">Continuar</span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Cargar datos específicos de la página de dashboard
    async function loadDashboardCourses() {
        try {
            const response = await axios.get('/api/student/dashboard-courses');
            const courses = response.data;
            const container = document.getElementById('dashboard-courses-list');

            if (courses.length > 0) {
                container.innerHTML = courses.map(course => `
                    <a href="/course/${course.slug}/learn" class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 card-hover">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-${course.color || 'blue'}-100 flex items-center justify-center">
                            <i class="fas fa-${course.icon || 'book'} text-${course.color || 'blue'}-600 text-lg"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="font-medium text-gray-900">${course.title}</h3>
                            <p class="text-sm text-gray-500 mt-1">${course.instructor}</p>
                            <div class="flex items-center mt-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-${course.color || 'blue'}-600 h-2 rounded-full progress-bar" style="width: ${course.progress}%"></div>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-700">${course.progress}%</span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-2">
                                <span>${course.last_activity || 'Sin actividad reciente'}</span>
                                <span>${course.next_lesson || 'Próxima lección'}</span>
                            </div>
                        </div>
                    </a>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 mb-4">No tienes cursos activos</p>
                        <a href="{{ route('cursos') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
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
                    <p class="text-red-500">Error cargando los cursos</p>
                </div>
            `;
        }
    }

    async function loadRecentActivity() {
        try {
            const response = await axios.get('/api/student/recent-activity');
            const activities = response.data;
            const container = document.getElementById('recent-activity');

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
                            <p class="text-xs text-gray-500 mt-1">${activity.time}</p>
                        </div>
                        ${activity.badge ? `<span class="ml-2 px-2 py-1 text-xs rounded-full ${activity.badge_color}">${activity.badge}</span>` : ''}
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-gray-500">No hay actividad reciente</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading recent activity:', error);
        }
    }

    async function loadUpcomingEvents() {
        try {
            const response = await axios.get('/api/student/upcoming-events');
            const events = response.data;
            const container = document.getElementById('upcoming-events');

            if (events.length > 0) {
                container.innerHTML = events.map(event => `
                    <a href="${event.link || '#'}" class="flex items-start p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200">
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
                    </a>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-gray-500">No hay eventos próximos</p>
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
                    <div class="flex items-center p-3 rounded-lg border border-gray-200">
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
                        <p class="text-gray-500">Aún no tienes logros</p>
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

            // Actualizar valores en las cards
            if (stats.activeCourses) {
                document.getElementById('stats-courses').textContent = stats.activeCourses;
            }
            if (stats.pendingAssignments) {
                document.getElementById('stats-assignments').textContent = stats.pendingAssignments;
            }
            if (stats.upcomingExams) {
                document.getElementById('stats-exams').textContent = stats.upcomingExams;
            }
            if (stats.certificatesCount) {
                document.getElementById('stats-certificates').textContent = stats.certificatesCount;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
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
