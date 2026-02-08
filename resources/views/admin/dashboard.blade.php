@extends('layouts.admin')
@section('title', 'Dashboard - Panel Administrativo')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">Resumen completo de la plataforma educativa</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="text-sm text-gray-500 bg-white px-3 py-1 rounded-lg shadow-sm">
                <i class="far fa-calendar-alt mr-2"></i>
                {{ now()->format('d/m/Y - H:i') }}
            </div>
        </div>
    </div>

    <!-- Stats Grid Mejorado -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Estudiantes -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-5 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Estudiantes</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_students'] }}</p>
                    <div class="flex items-center mt-3">
                        <span class="text-xs bg-blue-700 px-2 py-1 rounded-full">
                            <i class="fas fa-user-graduate mr-1"></i>Activos: {{ $stats['active_students_week'] }}
                        </span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Cursos -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg p-5 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Cursos</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_courses'] }}</p>
                    <div class="flex items-center mt-3">
                        <span class="text-xs bg-green-700 px-2 py-1 rounded-full">
                            <i class="fas fa-star mr-1"></i>Rating: {{ number_format($stats['avg_course_rating'], 1) }}/5
                        </span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-book-open text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Exámenes -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl shadow-lg p-5 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Exámenes</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_exams'] }}</p>
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <span class="text-xs bg-purple-700 px-2 py-1 rounded-full text-center">
                            <i class="fas fa-check mr-1"></i>{{ $stats['completed_exams'] }}
                        </span>
                        <span class="text-xs bg-purple-800 px-2 py-1 rounded-full text-center">
                            <i class="fas fa-times mr-1"></i>{{ $stats['failed_exams'] }}
                        </span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-clipboard-list text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Ingresos Mensuales -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl shadow-lg p-5 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Ingresos Mensuales</p>
                    <p class="text-2xl font-bold mt-2">S/ {{ number_format($stats['monthly_revenue'], 2) }}</p>
                    <div class="flex items-center mt-3">
                        <span class="text-xs bg-orange-700 px-2 py-1 rounded-full">
                            <i class="fas fa-chart-line mr-1"></i>Semanal: S/ {{ number_format($stats['weekly_revenue'], 2) }}
                        </span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-full">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda Fila de Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Inscripciones Hoy -->
        <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inscripciones Hoy</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today_enrollments'] }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-calendar-day text-green-500 mr-2"></i>
                        <span class="text-sm text-gray-500">Total: {{ $stats['total_enrollments'] }}</span>
                    </div>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user-plus text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Instructores Activos -->
        <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Instructores</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['active_instructors'] }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-chalkboard-teacher text-blue-500 mr-2"></i>
                        <span class="text-sm text-gray-500">Cursos: {{ $stats['total_courses'] }}</span>
                    </div>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Certificados -->
        <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Certificados</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_certificates'] }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-award text-yellow-500 mr-2"></i>
                        <span class="text-sm text-gray-500">Emitidos</span>
                    </div>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-certificate text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pago Pendientes -->
        <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pagos Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['pending_payments'] }}</p>
                    <div class="flex items-center mt-2">
                        <i class="fas fa-clock text-red-500 mr-2"></i>
                        <span class="text-sm text-gray-500">Por procesar</span>
                    </div>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Inscripciones por Mes -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Inscripciones por Mes</h2>
                    <p class="text-sm text-gray-500">Últimos 12 meses</p>
                </div>
                <div class="text-sm text-blue-600 font-medium">
                    <i class="fas fa-chart-bar mr-2"></i>Gráfico de Barras
                </div>
            </div>
            <div class="h-80">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de Cursos con Mayor Demanda -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Top 10 Cursos Más Demandados</h2>
                    <p class="text-sm text-gray-500">Por número de inscripciones</p>
                </div>
                <div class="text-sm text-green-600 font-medium">
                    <i class="fas fa-chart-pie mr-2"></i>Gráfico de Barras Horizontal
                </div>
            </div>
            <div class="h-80">
                <canvas id="topCoursesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tercera Sección: Gráfico de Ingresos y Actividad Reciente -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gráfico de Ingresos -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Ingresos últimos 6 meses</h2>
                <div class="text-sm text-purple-600 font-medium">
                    <i class="fas fa-money-bill-wave mr-2"></i>Tendencia de Ingresos
                </div>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Actividad Reciente</h2>
            <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                @foreach($recentEnrollments as $enrollment)
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 border border-gray-100">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-semibold text-white">
                            {{ strtoupper(substr($enrollment->user->names, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $enrollment->user->names }}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            <i class="fas fa-book mr-1"></i>{{ Str::limit($enrollment->course->title, 30) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="far fa-clock mr-1"></i>{{ $enrollment->enrolled_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-800">
                            S/ {{ number_format($enrollment->course->price, 2) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.enrollments.index') }}" class="block text-center mt-4 text-sm text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-list mr-1"></i>Ver todas las inscripciones
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
                <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 border border-gray-100">
                    <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-16 h-12 object-cover rounded-lg shadow-sm">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $course->title }}</h3>
                        <p class="text-xs text-gray-500">{{ $course->category->name ?? 'Sin categoría' }}</p>
                        <div class="flex items-center space-x-3 mt-1">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-users mr-1"></i>
                                <span>{{ $course->enrollments_count }} estudiantes</span>
                            </div>
                            <span class="text-xs text-gray-400">•</span>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-money-bill-wave mr-1"></i>
                                <span>S/ {{ number_format($course->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($course->is_active)
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-800">
                                Activo
                            </span>
                        @else
                            <span class="text-xs font-medium px-2 py-1 rounded-full bg-red-100 text-red-800">
                                Inactivo
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.courses.index') }}" class="block text-center mt-4 text-sm text-blue-600 hover:text-blue-700 font-medium">
                <i class="fas fa-cogs mr-1"></i>Gestionar todos los cursos
            </a>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Acciones Rápidas</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.courses.create') }}" class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300 group border border-blue-200">
                    <div class="text-center">
                        <div class="bg-white p-3 rounded-lg inline-flex group-hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-plus text-blue-600 text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Nuevo Curso</p>
                        <p class="text-xs text-gray-500">Crear nuevo contenido</p>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-300 group border border-green-200">
                    <div class="text-center">
                        <div class="bg-white p-3 rounded-lg inline-flex group-hover:bg-green-50 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Usuarios</p>
                        <p class="text-xs text-gray-500">Gestionar usuarios</p>
                    </div>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-300 group border border-purple-200">
                    <div class="text-center">
                        <div class="bg-white p-3 rounded-lg inline-flex group-hover:bg-purple-50 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Pagos</p>
                        <p class="text-xs text-gray-500">Ver transacciones</p>
                    </div>
                </a>

                <a href="{{ route('admin.reports') }}" class="p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl hover:from-orange-100 hover:to-orange-200 transition-all duration-300 group border border-orange-200">
                    <div class="text-center">
                        <div class="bg-white p-3 rounded-lg inline-flex group-hover:bg-orange-50 transition-colors duration-200 shadow-sm">
                            <i class="fas fa-chart-bar text-orange-600 text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm font-medium text-gray-900">Reportes</p>
                        <p class="text-xs text-gray-500">Análisis detallados</p>
                    </div>
                </a>
            </div>
            
            <!-- Sistema Status -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Estado del Sistema</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full mx-auto mb-2 animate-pulse"></div>
                        <p class="text-xs font-medium text-gray-900">Servidor</p>
                        <p class="text-xs text-gray-500">En línea</p>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full mx-auto mb-2 animate-pulse"></div>
                        <p class="text-xs font-medium text-gray-900">Base de Datos</p>
                        <p class="text-xs text-gray-500">Conectado</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos para gráficos
        const enrollmentData = @json($enrollmentChart);
        const topCoursesData = @json($topCoursesChart);
        const revenueData = @json($revenueData);

        // 1. Gráfico de Inscripciones por Mes
        const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
        const enrollmentChart = new Chart(enrollmentCtx, {
            type: 'bar',
            data: {
                labels: enrollmentData.labels,
                datasets: [{
                    label: 'Inscripciones',
                    data: enrollmentData.data,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(59, 130, 246, 0.9)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#4F46E5',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} inscripciones`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Gráfico de Top 10 Cursos Más Demandados (Horizontal Bar)
        const topCoursesCtx = document.getElementById('topCoursesChart').getContext('2d');
        const topCoursesChart = new Chart(topCoursesCtx, {
            type: 'bar',
            data: {
                labels: topCoursesData.labels,
                datasets: [{
                    label: 'Inscripciones',
                    data: topCoursesData.data,
                    backgroundColor: topCoursesData.colors,
                    borderColor: topCoursesData.colors.map(color => color.replace('0.7', '1')),
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.x} inscripciones`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // 3. Gráfico de Ingresos (Line Chart)
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        
        // Preparar datos de ingresos
        const revenueLabels = revenueData.revenue.map(item => 
            `${item.month}/${item.year.toString().slice(-2)}`
        );
        const revenueAmounts = revenueData.revenue.map(item => parseFloat(item.revenue) || 0);
        
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Ingresos (S/)',
                    data: revenueAmounts,
                    borderColor: 'rgb(139, 92, 246)',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(139, 92, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        callbacks: {
                            label: function(context) {
                                return `S/ ${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'S/ ' + value.toLocaleString('es-PE');
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                }
            }
        });

        // Efectos de hover en las cards
        const statCards = document.querySelectorAll('.transform');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '';
            });
        });

        // Actualizar la hora en tiempo real
        function updateTime() {
            const now = new Date();
            const options = {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const timeString = now.toLocaleDateString('es-ES', options);
            const timeElement = document.querySelector('.text-gray-500.bg-white');
            if (timeElement) {
                timeElement.innerHTML = `<i class="far fa-calendar-alt mr-2"></i>${timeString}`;
            }
        }

        updateTime();
        setInterval(updateTime, 60000); // Actualizar cada minuto

        // Simular animación de carga en los gráficos
        function animateChartLoad() {
            const charts = document.querySelectorAll('canvas');
            charts.forEach((chart, index) => {
                chart.style.opacity = '0';
                chart.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    chart.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                    chart.style.opacity = '1';
                    chart.style.transform = 'translateY(0)';
                }, index * 200);
            });
        }

        animateChartLoad();

        // Añadir efecto de contador animado en las stats
        function animateCounters() {
            const counters = document.querySelectorAll('.text-3xl.font-bold, .text-2xl.font-bold');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/,/g, ''));
                const increment = target / 50;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current).toLocaleString();
                }, 20);
            });
        }

        // Ejecutar animación después de un breve retraso
        setTimeout(animateCounters, 500);
    });
</script>

<style>
    /* Animaciones personalizadas */
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

    @keyframes pulse-glow {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
        }
        50% {
            box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .pulse-glow {
        animation: pulse-glow 2s infinite;
    }

    /* Scrollbar personalizado para el área de actividad */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Efecto de gradiente animado en las cards principales */
    .gradient-card {
        background: linear-gradient(45deg, var(--from-color), var(--to-color));
        background-size: 200% 200%;
        animation: gradientShift 3s ease infinite;
    }

    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
</style>
@endsection