@extends('layouts.student')
@section('title', 'Mi panel de empresa')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Panel de Administración de la Empresa</h1>
    <p class="text-gray-600 text-sm mt-1">Vista general del rendimiento y avance de tus colaboradores.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-4">
            <i class="fas fa-users text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Usuarios Inscritos</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalEmployees }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 mr-4">
            <i class="fas fa-certificate text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Certificados Obtenidos</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalCertificates }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mr-4">
            <i class="fas fa-file-alt text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Exámenes Realizados</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalExams }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Estado de Inscripciones</h3>
        <div class="relative h-64 w-full flex justify-center">
            <canvas id="enrollmentsChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Resultados de Exámenes</h3>
        <div class="relative h-64 w-full flex justify-center">
            <canvas id="examsChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Top 5 Cursos Más Populares</h3>
        <div class="relative h-64 w-full">
            <canvas id="popularCoursesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Certificados (Últimos 6 meses)</h3>
        <div class="relative h-64 w-full">
            <canvas id="certificatesChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Progreso por Colaborador</h3>
        </div>
        <div class="overflow-x-auto flex-1 max-h-96">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 bg-white shadow-sm z-10">
                    <tr class="text-gray-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">Colaborador</th>
                        <th class="p-4 font-semibold">Curso</th>
                        <th class="p-4 font-semibold">Progreso</th>
                        <th class="p-4 font-semibold">Estado</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    @forelse($userProgress as $enrollment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-medium text-gray-800">
                            {{ $enrollment->user->names }}
                        </td>
                        <td class="p-4 text-gray-600">
                            {{ $enrollment->course->title ?? 'N/A' }}
                        </td>
                        <td class="p-4 min-w-[120px]">
                            <div class="flex items-center gap-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="{{ $enrollment->progress == 100 ? 'bg-emerald-500' : 'bg-blue-500' }} h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-600">{{ $enrollment->progress }}%</span>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($enrollment->progress == 100)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-semibold">Completado</span>
                            @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-xs font-semibold">En curso</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-3 text-gray-300 block"></i>
                            Aún no hay inscripciones registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col h-full">
        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Últimas 10 Actividades</h3>
        </div>
        <div class="p-5 overflow-y-auto flex-1 max-h-96">
            <div class="relative border-l-2 border-gray-200 ml-3 space-y-6">
                @forelse($topActivities as $activity)
                <div class="relative pl-6">
                    <span class="absolute -left-3.5 top-1 flex items-center justify-center w-7 h-7 rounded-full bg-{{ $activity->color ?? 'blue' }}-100 ring-4 ring-white">
                        <i class="fas fa-{{ $activity->icon ?? 'check' }} text-{{ $activity->color ?? 'blue' }}-500 text-xs"></i>
                    </span>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div class="flex justify-between items-start mb-1 gap-2">
                            <span class="text-sm font-bold text-gray-800 leading-tight">{{ $activity->user->names }}</span>
                            <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">{{ $activity->description }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4 text-sm">
                    No hay actividades recientes para mostrar.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Métricas Generales por Curso</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-white border-b border-gray-100">
                <tr class="text-gray-500 text-xs uppercase tracking-wider">
                    <th class="p-4 font-semibold">Nombre del Curso</th>
                    <th class="p-4 font-semibold text-center">Colaboradores Inscritos</th>
                    <th class="p-4 font-semibold">Promedio de Progreso Global</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                @forelse($courseAverages as $stat)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 font-medium text-gray-800">
                        <i class="fas fa-book-open text-blue-500 mr-2 opacity-70"></i> {{ $stat['course'] }}
                    </td>
                    <td class="p-4 text-center font-bold text-gray-600">
                        {{ $stat['total_students'] }}
                    </td>
                    <td class="p-4 w-1/3">
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $stat['avg_progress'] }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-600">{{ $stat['avg_progress'] }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="p-8 text-center text-gray-500">
                        Aún no hay datos de cursos disponibles.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Data inyectada desde el Controlador
        const enrollData = @json($enrollmentStats);
        const examData = @json($examStats);
        const popularCourses = @json($popularCoursesStats);
        const certData = @json($certificateStats);

        // 1. Gráfico: Estado de Inscripciones (Doughnut)
        new Chart(document.getElementById('enrollmentsChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Completados', 'En Progreso'],
                datasets: [{
                    data: [enrollData.completed, enrollData.in_progress],
                    backgroundColor: ['#10B981', '#3B82F6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } }
            }
        });

        // 2. Gráfico: Exámenes Realizados (Pie)
        new Chart(document.getElementById('examsChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Aprobados', 'Reprobados'],
                datasets: [{
                    data: [examData.passed, examData.failed],
                    backgroundColor: ['#34D399', '#F87171'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } }
            }
        });

        // 3. Gráfico: Top 5 Cursos Más Populares (Barra Horizontal)
        new Chart(document.getElementById('popularCoursesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: popularCourses.labels,
                datasets: [{
                    label: 'Inscritos',
                    data: popularCourses.data,
                    backgroundColor: '#8B5CF6', // Purple-500
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y', // Lo hace horizontal
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, ticks: { stepSize: 1 } },
                    y: { grid: { display: false } }
                }
            }
        });

        // 4. Gráfico: Certificados por Mes (Líneas)
        new Chart(document.getElementById('certificatesChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: certData.labels,
                datasets: [{
                    label: 'Certificados Emitidos',
                    data: certData.data,
                    borderColor: '#10B981', // Emerald-500
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3, // Suaviza la línea
                    pointBackgroundColor: '#10B981'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@endsection