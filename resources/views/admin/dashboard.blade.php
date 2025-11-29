@extends('layouts.admin')

@section('title', 'Dashboard - Panel Administrativo')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">Bienvenido al panel de control de la plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="text-sm text-gray-500">
                {{ now()->format('l, d F Y') }}
            </div>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                En línea
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Estudiantes -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_students'] }}</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm text-green-600">+12% este mes</span>
                    </div>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Cursos -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Cursos</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_courses'] }}</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm text-green-600">+5% este mes</span>
                    </div>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ingresos Mensuales -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ingresos Mensuales</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">S/ {{ number_format($stats['monthly_revenue'], 2) }}</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm text-green-600">+18% este mes</span>
                    </div>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Inscripciones Hoy -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inscripciones Hoy</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today_enrollments'] }}</p>
                    <div class="flex items-center mt-2">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <span class="text-sm text-green-600">+3 hoy</span>
                    </div>
                </div>
                <div class="bg-orange-100 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gráfico de Ingresos -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Ingresos últimos 6 meses</h2>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Últimos 6 meses</option>
                    <option>Este año</option>
                    <option>Último año</option>
                </select>
            </div>
            <div class="h-80">
                <!-- Gráfico placeholder - En producción usarías Chart.js o similar -->
                <div class="w-full h-full flex items-end justify-between space-x-2">
                    @foreach($revenueData['revenue'] as $data)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-gradient-to-t from-blue-500 to-blue-300 rounded-t-lg"
                             style="height: {{ ($data->revenue / max(array_column($revenueData['revenue']->toArray(), 'revenue'))) * 80 }}%">
                        </div>
                        <span class="text-xs text-gray-500 mt-2">{{ $data->month }}/{{ $data->year }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Actividad Reciente</h2>
            <div class="space-y-4">
                @foreach($recentEnrollments as $enrollment)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-blue-600">
                            {{ strtoupper(substr($enrollment->user->names, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $enrollment->user->names }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            Se inscribió en {{ $enrollment->course->title }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $enrollment->enrolled_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.enrollments.index') }}" class="block text-center mt-4 text-sm text-blue-600 hover:text-blue-700 font-medium">
                Ver todas las inscripciones
            </a>
        </div>
    </div>

    <!-- Cursos Populares y Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cursos Populares -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Cursos Más Populares</h2>
            <div class="space-y-4">
                @foreach($popularCourses as $course)
                <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <img src="{{ $course->image_url ?: 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=100&q=80' }}"
                         alt="{{ $course->title }}"
                         class="w-16 h-12 object-cover rounded-lg">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $course->category->name }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>4.8</span>
                            </div>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">{{ $course->enrollments_count }} estudiantes</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-semibold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.courses.index') }}" class="block text-center mt-4 text-sm text-blue-600 hover:text-blue-700 font-medium">
                Gestionar todos los cursos
            </a>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Acciones Rápidas</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.courses.create') }}" class="p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors duration-200 group">
                    <div class="text-center">
                        <div class="bg-blue-100 p-3 rounded-lg inline-flex group-hover:bg-blue-200 transition-colors duration-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Nuevo Curso</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" class="p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors duration-200 group">
                    <div class="text-center">
                        <div class="bg-green-100 p-3 rounded-lg inline-flex group-hover:bg-green-200 transition-colors duration-200">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Gestionar Usuarios</p>
                    </div>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors duration-200 group">
                    <div class="text-center">
                        <div class="bg-purple-100 p-3 rounded-lg inline-flex group-hover:bg-purple-200 transition-colors duration-200">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Ver Pagos</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports') }}" class="p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition-colors duration-200 group">
                    <div class="text-center">
                        <div class="bg-orange-100 p-3 rounded-lg inline-flex group-hover:bg-orange-200 transition-colors duration-200">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Reportes</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Sistema Status -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Estado del Sistema</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="text-center p-4 border border-gray-200 rounded-xl">
                <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">Servidor Web</p>
                <p class="text-xs text-gray-500">En línea</p>
            </div>
            <div class="text-center p-4 border border-gray-200 rounded-xl">
                <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">Base de Datos</p>
                <p class="text-xs text-gray-500">Conectado</p>
            </div>
            <div class="text-center p-4 border border-gray-200 rounded-xl">
                <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">Almacenamiento</p>
                <p class="text-xs text-gray-500">65% usado</p>
            </div>
            <div class="text-center p-4 border border-gray-200 rounded-xl">
                <div class="w-3 h-3 bg-green-500 rounded-full mx-auto mb-2"></div>
                <p class="text-sm font-medium text-gray-900">Cache</p>
                <p class="text-xs text-gray-500">Activo</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar la hora en tiempo real
        function updateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('current-time').textContent =
                now.toLocaleDateString('es-ES', options);
        }

        updateTime();
        setInterval(updateTime, 1000);

        // Efectos de hover en las cards
        const statCards = document.querySelectorAll('.bg-white');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'all 0.2s ease';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Simular datos del gráfico (en producción usarías Chart.js)
        function animateChart() {
            const bars = document.querySelectorAll('.bg-gradient-to-t');
            bars.forEach((bar, index) => {
                setTimeout(() => {
                    bar.style.opacity = '1';
                }, index * 200);
            });
        }

        animateChart();

        // Notificación de actualización en tiempo real
        function checkNewEnrollments() {
            // En producción, esto se conectaría con WebSockets
            setTimeout(() => {
                // Simular nueva inscripción
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = '🎉 Nueva inscripción recibida';
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }, 10000); // Simular cada 10 segundos
        }

        // checkNewEnrollments(); // Descomentar para simular notificaciones
    });
</script>

<style>
    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .chart-bar {
        transition: all 0.5s ease;
        opacity: 0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>
@endsection
