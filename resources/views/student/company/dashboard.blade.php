@extends('layouts.student')
@section('title', 'Dashboard Corporativo')
@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Panel de Administración de la Empresa</h1>
    <p class="text-gray-600 text-sm">Rendimiento detallado de tus colaboradores.</p>
</div>

{{-- 1. CARDS DE MÉTRICAS --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-gray-500 font-bold uppercase">Total Colaboradores</p>
        <p class="text-2xl font-black text-gray-800">{{ $totalEmployees }}</p>
    </div>

    <div class="bg-white p-5 rounded-xl border border-emerald-100 shadow-sm">
        <p class="text-xs text-emerald-600 font-bold uppercase">Capacitados (100%)</p>
        <p class="text-2xl font-black text-gray-800">{{ $totalTrained }}</p>
    </div>

    <div class="bg-white p-5 rounded-xl border border-indigo-100 shadow-sm">
        <p class="text-xs text-indigo-600 font-bold uppercase">Progreso Promedio</p>
        <p class="text-2xl font-black text-gray-800">{{ $overallProgressAvg }}%</p>
        <div class="w-full bg-gray-100 h-1.5 mt-2 rounded-full overflow-hidden">
            <div class="bg-indigo-500 h-full" style="width: {{ $overallProgressAvg }}%"></div>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-amber-600 font-bold uppercase">Certificados</p>
        <p class="text-2xl font-black text-gray-800">{{ $totalCertificates }}</p>
    </div>

    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
        <p class="text-xs text-purple-600 font-bold uppercase">Exámenes</p>
        <p class="text-2xl font-black text-gray-800">{{ $totalExams }}</p>
    </div>
</div>

{{-- 2. ALERTA CRÍTICA: USUARIOS SIN LOGIN --}}
@if($neverLoggedIn->count() > 0)
<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
    <div class="flex items-center mb-2">
        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
        <h3 class="text-red-800 font-bold">Alerta Crítica: Colaboradores sin acceso</h3>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($neverLoggedIn as $noLogin)
            <span class="bg-white border border-red-200 text-red-700 text-xs px-3 py-1 rounded-full shadow-sm">
                <strong>{{ $noLogin->names }}</strong> ({{ $noLogin->profession ?? 'Sin puesto' }})
            </span>
        @endforeach
    </div>
</div>
@endif

{{-- 3. GRÁFICOS --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Progreso Promedio por Puesto</h3>
        <div class="h-64">
            <canvas id="professionChart"></canvas>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Estado de Cursos</h3>
        <div class="h-64">
            <canvas id="enrollmentsChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Resultados de Exámenes</h3>
        <div class="h-64">
            <canvas id="examsChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Top 5 Cursos Más Populares</h3>
        <div class="h-64">
            <canvas id="popularCoursesChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Certificados Obtenidos (Últimos 6 Meses)</h3>
        <div class="h-64">
            <canvas id="certificatesChart"></canvas>
        </div>
    </div>
</div>

{{-- 4. TABLA: PROGRESO TOTAL POR COLABORADOR --}}
{{-- <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50">
        <h3 class="font-bold text-gray-800">Ranking de Progreso por Colaborador</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <th class="p-4">Colaborador</th>
                    <th class="p-4">Puesto</th>
                    <th class="p-4 text-center">Cursos</th>
                    <th class="p-4">Progreso Promedio</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($collaboratorProgress as $collab)
                <tr class="hover:bg-gray-50">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                {{ substr($collab['name'], 0, 1) }}
                            </div>
                            <span class="text-sm font-medium">{{ $collab['name'] }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-500">{{ $collab['profession'] }}</td>
                    <td class="p-4 text-center text-sm font-bold">{{ $collab['completed'] }}/{{ $collab['total_courses'] }}</td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-emerald-500 h-full" style="width: {{ $collab['avg_progress'] }}%"></div>
                            </div>
                            <span class="text-xs font-bold">{{ $collab['avg_progress'] }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> --}}
{{-- 4. TABLA: PROGRESO TOTAL POR COLABORADOR (CON SCROLL) --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">
            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
            Ranking de Progreso por Colaborador
        </h3>
        <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
            <i class="fas fa-users mr-1"></i> {{ $collaboratorProgress->count() }} colaboradores
        </span>
    </div>
    
    {{-- Contenedor con scroll vertical --}}
    <div class="overflow-y-auto" style="max-height: 500px;">
        <table class="w-full text-left border-collapse">
            <thead class="sticky top-0 bg-gray-50 z-10">
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-200">
                    <th class="p-4">#</th>
                    <th class="p-4">Colaborador</th>
                    <th class="p-4">Puesto</th>
                    <th class="p-4 text-center">Cursos</th>
                    <th class="p-4">Progreso Promedio</th>
                    <th class="p-4 text-center">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($collaboratorProgress as $index => $collab)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="p-4 text-sm font-medium text-gray-500">
                        @if($index == 0)
                            <span class="text-yellow-500"><i class="fas fa-crown"></i></span>
                        @elseif($index == 1)
                            <span class="text-gray-400"><i class="fas fa-medal"></i></span>
                        @elseif($index == 2)
                            <span class="text-amber-600"><i class="fas fa-medal"></i></span>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            @if($collab['photo'])
                                <img src="{{ $collab['photo'] }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                    {{ substr($collab['name'], 0, 1) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-800">{{ $collab['name'] }}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            {{ $collab['profession'] }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <span class="text-sm font-bold {{ $collab['completed'] == $collab['total_courses'] && $collab['total_courses'] > 0 ? 'text-emerald-600' : 'text-gray-700' }}">
                            {{ $collab['completed'] }}/{{ $collab['total_courses'] }}
                        </span>
                        @if($collab['total_courses'] > 0 && $collab['completed'] == $collab['total_courses'])
                            <i class="fas fa-check-circle text-emerald-500 ml-1 text-xs"></i>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 h-2 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-green-500 h-full transition-all duration-500" 
                                     style="width: {{ $collab['avg_progress'] }}%">
                                </div>
                            </div>
                            <span class="text-xs font-bold {{ $collab['avg_progress'] >= 80 ? 'text-emerald-600' : ($collab['avg_progress'] >= 50 ? 'text-amber-600' : 'text-gray-500') }}">
                                {{ $collab['avg_progress'] }}%
                            </span>
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        @php
                            $status = 'inactive';
                            $statusText = 'Sin iniciar';
                            $statusColor = 'gray';
                            
                            if($collab['avg_progress'] == 100) {
                                $status = 'completed';
                                $statusText = 'Completado';
                                $statusColor = 'emerald';
                            } elseif($collab['avg_progress'] > 0) {
                                $status = 'progress';
                                $statusText = 'En progreso';
                                $statusColor = 'blue';
                            } elseif($collab['total_courses'] == 0) {
                                $statusText = 'Sin cursos';
                                $statusColor = 'gray';
                            }
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            @if($statusColor == 'emerald') bg-emerald-100 text-emerald-700
                            @elseif($statusColor == 'blue') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $statusText }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{-- Footer con resumen --}}
    @if($collaboratorProgress->count() > 0)
    <div class="p-4 border-t border-gray-100 bg-gray-50 flex justify-between items-center text-xs text-gray-500">
        <div class="flex items-center gap-4">
            <span><i class="fas fa-chart-line mr-1"></i> Promedio general: <strong class="text-gray-700">{{ round($collaboratorProgress->avg('avg_progress'), 1) }}%</strong></span>
            <span><i class="fas fa-check-circle mr-1 text-emerald-500"></i> Completaron todo: <strong class="text-gray-700">{{ $collaboratorProgress->where('completed', $collaboratorProgress->where('total_courses', '>', 0)->sum('total_courses'))->count() }}</strong></span>
        </div>
        <div>
            <i class="fas fa-arrow-up mr-1 text-emerald-500"></i> Ordenado por mejor progreso
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Datos desde el controlador
        const enrollmentStats = @json($enrollmentStats);
        const examStats = @json($examStats);
        const popularCoursesStats = @json($popularCoursesStats);
        const certificateStats = @json($certificateStats);
        const professionStats = @json($professionStats);

        // 1. Gráfico de Progreso por Puesto (Barra)
        const profCtx = document.getElementById('professionChart');
        if (profCtx && professionStats.labels.length > 0) {
            new Chart(profCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: professionStats.labels,
                    datasets: [{
                        label: '% Progreso Promedio',
                        data: professionStats.data,
                        backgroundColor: '#6366f1',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } } },
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        } else if (profCtx) {
            console.warn("No hay datos de profesiones para mostrar.");
        }

        // 2. Gráfico de Estado de Cursos (Doughnut)
        const enrollCtx = document.getElementById('enrollmentsChart');
        if (enrollCtx) {
            new Chart(enrollCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Completados', 'En Progreso'],
                    datasets: [{ 
                        data: [enrollmentStats.completed, enrollmentStats.in_progress],
                        backgroundColor: ['#10B981', '#3B82F6'], 
                        borderWidth: 0, 
                        hoverOffset: 6 
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false, 
                    cutout: '70%',
                    plugins: { 
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18, font: { size: 12 } } } 
                    }
                }
            });
        }

        // 3. Gráfico de Resultados de Exámenes (Pie)
        const examsCtx = document.getElementById('examsChart');
        if (examsCtx) {
            new Chart(examsCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Aprobados', 'Reprobados'],
                    datasets: [{ 
                        data: [examStats.passed, examStats.failed],
                        backgroundColor: ['#34D399', '#F87171'], 
                        borderWidth: 0, 
                        hoverOffset: 6 
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 18, font: { size: 12 } } } 
                    }
                }
            });
        }

        // 4. Gráfico de Cursos Populares (Barra Horizontal)
        const popularCtx = document.getElementById('popularCoursesChart');
        if (popularCtx && popularCoursesStats.labels.length > 0) {
            new Chart(popularCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: popularCoursesStats.labels,
                    datasets: [{
                        label: 'Inscritos', 
                        data: popularCoursesStats.data,
                        backgroundColor: '#8B5CF6', 
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y', 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, ticks: { stepSize: 1 } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // 5. Gráfico de Certificados por Mes (Línea)
        const certCtx = document.getElementById('certificatesChart');
        if (certCtx) {
            new Chart(certCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: certificateStats.labels,
                    datasets: [{
                        label: 'Certificados Emitidos', 
                        data: certificateStats.data,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        borderWidth: 2, 
                        fill: true, 
                        tension: 0.35,
                        pointBackgroundColor: '#10B981', 
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        }
    });
</script>
@endsection