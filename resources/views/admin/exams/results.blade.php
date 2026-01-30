@extends('layouts.admin')
@section('title', 'Resultados - ' . $exam->title)
@section('content')
<div class="container mx-auto px-4 py-6" x-data="resultsManager()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Resultados del Examen</h1>
                        <p class="text-gray-600 mt-1">{{ $exam->title }}</p>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="flex flex-wrap gap-3 mt-4">
                    @php
                        $totalAttempts  = $results->total();
                        $passedCount    = $results->where('passed', true)->count();
                        $averageScore   = $results->avg('score') ?? 0;
                        $passRate       = $totalAttempts > 0 ? ($passedCount / $totalAttempts * 100) : 0;
                    @endphp
                    <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-sm text-blue-700 font-medium">Total Intentos</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $totalAttempts }}</div>
                    </div>
                    <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="text-sm text-green-700 font-medium">Aprobados</div>
                        <div class="text-2xl font-bold text-green-900">{{ $passedCount }}</div>
                    </div>
                    <div class="px-4 py-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                        <div class="text-sm text-purple-700 font-medium">Tasa de Aprobación</div>
                        <div class="text-2xl font-bold text-purple-900">{{ number_format($passRate, 1) }}%</div>
                    </div>
                    <div class="px-4 py-3 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                        <div class="text-sm text-orange-700 font-medium">Puntuación Promedio</div>
                        <div class="text-2xl font-bold text-orange-900">{{ number_format($averageScore, 1) }}</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 lg:mt-0">
                <a href="{{ route('admin.exams.edit', $exam) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Configuración
                </a>
                <a href="{{ route('admin.exams.questions', $exam) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Preguntas
                </a>
                <button @click="exportResults()"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar CSV
                </button>
            </div>
        </div>

        <!-- Pestañas de navegación -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <a href="{{ route('admin.exams.edit', $exam) }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Información General
                    </div>
                </a>
                <a href="{{ route('admin.exams.questions', $exam) }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Preguntas
                    </div>
                </a>
                <a href="#"
                   class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 01-2-2z"></path>
                        </svg>
                        Resultados ({{ $results->total() }})
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Filtrar Resultados</h3>
                <p class="text-sm text-gray-600 mt-1">Encuentra resultados específicos rápidamente</p>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text"
                           x-model="searchQuery"
                           @input.debounce.500ms="filterResults()"
                           placeholder="Buscar por nombre o email..."
                           class="w-full lg:w-64 pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                </div>

                <select x-model="statusFilter" @change="filterResults()" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <option value="">Todos los estados</option>
                    <option value="passed">Aprobados</option>
                    <option value="failed">Reprobados</option>
                </select>

                <button @click="resetFilters()" class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de resultados -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Estudiante</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Intento #</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha/Hora</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Duración</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Puntuación</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($results as $attempt)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <!-- Estudiante -->
                            <td class="px-6 py-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                        <span class="font-semibold text-blue-800 text-sm">
                                            {{ substr($attempt->user->name ?? 'N/A', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $attempt->user->names ?? 'Usuario Eliminado' }}</div>
                                        <div class="text-sm text-gray-500">{{ $attempt->user->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Número de intento -->
                            <td class="px-6 py-5">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 font-semibold text-sm">
                                    {{ $attempt->attempt_number }}
                                </div>
                            </td>

                            <!-- Fecha y hora -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $attempt->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $attempt->created_at->format('H:i:s') }}</div>
                            </td>

                            <!-- Duración -->
                            <td class="px-6 py-5">
                                @if($attempt->started_at && $attempt->completed_at)
                                    @php
                                        $duration = $attempt->completed_at->diff($attempt->started_at);
                                        $minutes = $duration->i;
                                        $seconds = $duration->s;
                                    @endphp
                                    <div class="text-sm text-gray-900">{{ $minutes }}:{{ sprintf('%02d', $seconds) }}</div>
                                    <div class="text-xs text-gray-500">minutos</div>
                                @else
                                    <span class="text-sm text-gray-400">No completado</span>
                                @endif
                            </td>

                            <!-- Puntuación -->
                            <td class="px-6 py-5">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-3">
                                        @php
                                            $percentage = ($attempt->score / $attempt->total_points) * 100;
                                            $color = $percentage >= 70 ? 'bg-green-600' : ($percentage >= 50 ? 'bg-yellow-500' : 'bg-red-600');
                                        @endphp
                                        <div class="h-2.5 rounded-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $attempt->score }} / {{ $attempt->total_points }}
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}%</div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $attempt->passed
                                        ? 'bg-green-100 text-green-800 border border-green-200'
                                        : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    @if($attempt->passed)
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Aprobado
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reprobado
                                    @endif
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-5 whitespace-nowrap text-sm">
                                <button @click="showAttemptDetails({{ $attempt->id }})"
                                        class="text-blue-600 hover:text-blue-900 font-medium transition duration-200">
                                    Ver Detalles
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay resultados aún</h3>
                                <p class="text-gray-500 mb-6 max-w-md mx-auto">Los estudiantes aún no han realizado este examen.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($results->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $results->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $results->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $results->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center space-x-2">
                        {{ $results->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Panel lateral de estadísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <!-- Distribución de puntajes -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribución de Puntajes</h3>
            <div class="h-64 flex items-end space-x-2">
                @php
                    $scoreRanges = [
                        '0-49' => 0,
                        '50-69' => 0,
                        '70-89' => 0,
                        '90-100' => 0
                    ];

                    foreach($results as $attempt) {
                        $percentage = ($attempt->score / $attempt->total_points) * 100;
                        if($percentage < 50) $scoreRanges['0-49']++;
                        elseif($percentage < 70) $scoreRanges['50-69']++;
                        elseif($percentage < 90) $scoreRanges['70-89']++;
                        else $scoreRanges['90-100']++;
                    }

                    $maxCount = max($scoreRanges);
                @endphp

                @foreach($scoreRanges as $range => $count)
                    @php
                        $height = $maxCount > 0 ? ($count / $maxCount * 200) : 0;
                        $colors = [
                            '0-49' => 'from-red-500 to-red-600',
                            '50-69' => 'from-yellow-500 to-yellow-600',
                            '70-89' => 'from-green-500 to-green-600',
                            '90-100' => 'from-emerald-500 to-emerald-600'
                        ];
                    @endphp
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full flex flex-col items-center">
                            <div class="text-xs text-gray-500 mb-2">{{ $range }}%</div>
                            <div class="w-3/4 bg-gray-100 rounded-t-lg overflow-hidden" style="height: 200px">
                                <div class="w-full bg-gradient-to-t {{ $colors[$range] }} rounded-t-lg transition-all duration-500"
                                     style="height: {{ $height }}px"></div>
                            </div>
                        </div>
                        <div class="mt-2 text-sm font-semibold">{{ $count }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Resumen de rendimiento -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm">
            <h3 class="text-xl font-bold text-blue-900 mb-6">Resumen de Rendimiento</h3>

            <div class="space-y-4">
                <!-- Puntuación más alta -->
                <div class="p-4 bg-white rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-blue-700">Puntuación Más Alta</div>
                            <div class="text-2xl font-bold text-blue-900">
                                {{ $results->max('score') ?? 0 }} / {{ $attempt->total_points ?? 0 }}
                            </div>
                        </div>
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Puntuación más baja -->
                <div class="p-4 bg-white rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-blue-700">Puntuación Más Baja</div>
                            <div class="text-2xl font-bold text-blue-900">
                                {{ $results->min('score') ?? 0 }} / {{ $attempt->total_points ?? 0 }}
                            </div>
                        </div>
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Tiempo promedio -->
                <div class="p-4 bg-white rounded-xl border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm text-blue-700">Tiempo Promedio</div>
                            <div class="text-2xl font-bold text-blue-900">
                                @php
                                    $avgMinutes = 0;
                                    $completedAttempts = $results->filter(function($a) {
                                        return $a->started_at && $a->completed_at;
                                    });
                                    if($completedAttempts->count() > 0) {
                                        $totalSeconds = 0;
                                        foreach($completedAttempts as $attempt) {
                                            $totalSeconds += $attempt->completed_at->diffInSeconds($attempt->started_at);
                                        }
                                        $avgMinutes = floor($totalSeconds / $completedAttempts->count() / 60);
                                    }
                                @endphp
                                {{ $avgMinutes }} min
                            </div>
                        </div>
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles del intento -->
<div x-data="attemptDetailsModal()" x-cloak>
    <div x-show="showModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
        @click.self="closeModal"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden"
            >
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Detalles del Intento</h3>
                            <p class="text-sm text-gray-600 mt-1" x-text="attemptData.user?.name || 'Cargando...'"></p>
                        </div>
                        <button @click="closeModal"
                                class="p-2 hover:bg-gray-100 rounded-lg transition duration-200">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div x-show="loading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-4 text-gray-600">Cargando detalles del intento...</p>
                    </div>

                    <div x-show="!loading && attemptData.id">
                        <!-- Información general -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <div class="text-sm text-blue-700">Puntuación</div>
                                <div class="text-2xl font-bold text-blue-900" x-text="`${attemptData.score} / ${attemptData.total_points}`"></div>
                                <div class="text-sm text-blue-600" x-text="`${((attemptData.score/attemptData.total_points)*100).toFixed(1)}%`"></div>
                            </div>
                            <div class="p-4 bg-green-50 rounded-xl border border-green-200">
                                <div class="text-sm text-green-700">Estado</div>
                                <div class="text-2xl font-bold text-green-900" x-text="attemptData.passed ? 'Aprobado' : 'Reprobado'"></div>
                                <div class="text-sm text-green-600" x-text="attemptData.passed ? '🎉 ¡Excelente!' : '📚 Sigue practicando'"></div>
                            </div>
                            <div class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                                <div class="text-sm text-purple-700">Duración</div>
                                <div class="text-2xl font-bold text-purple-900" x-text="formatDuration(attemptData.duration)"></div>
                                <div class="text-sm text-purple-600">Tiempo utilizado</div>
                            </div>
                        </div>

                        <!-- Respuestas -->
                        <div x-show="attemptData.answers && attemptData.answers.length > 0">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Respuestas del Estudiante</h4>
                            <div class="space-y-4">
                                <template x-for="(answer, index) in attemptData.answers" :key="index">
                                    <div class="p-4 border border-gray-200 rounded-xl hover:border-blue-300 transition duration-200"
                                         :class="answer.is_correct ? 'bg-green-50' : 'bg-red-50'">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-sm font-semibold"
                                                          :class="answer.is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                                        <span x-text="index + 1"></span>
                                                    </span>
                                                    <span class="font-medium text-gray-900" x-text="answer.question_text"></span>
                                                </div>
                                                <div class="ml-9">
                                                    <div class="mb-2">
                                                        <span class="text-sm text-gray-600">Respuesta del estudiante: </span>
                                                        <span class="font-medium" x-text="answer.student_answer || 'Sin respuesta'"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-sm text-gray-600">Respuesta correcta: </span>
                                                        <span class="font-medium text-green-700" x-text="answer.correct_answer"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="px-2 py-1 rounded text-xs font-semibold"
                                                      :class="answer.is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                      x-text="answer.is_correct ? '✓ Correcta' : '✗ Incorrecta'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="!attemptData.answers || attemptData.answers.length === 0" class="text-center py-8">
                            <p class="text-gray-500">No hay detalles de respuestas disponibles para este intento.</p>
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
    function resultsManager() {
        return {
            searchQuery: '',
            statusFilter: '',

            filterResults() {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('search', this.searchQuery);
                if (this.statusFilter) params.append('status', this.statusFilter);

                const url = `{{ route('admin.exams.results', $exam) }}?${params.toString()}`;
                window.location.href = url;
            },

            resetFilters() {
                this.searchQuery = '';
                this.statusFilter = '';
                this.filterResults();
            },

            exportResults() {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('search', this.searchQuery);
                if (this.statusFilter) params.append('status', this.statusFilter);

                const url = `{{ route('admin.exams.results.export', $exam) }}?${params.toString()}`;
                window.open(url, '_blank');
            },

            async showAttemptDetails(attemptId) {
                try {
                    const modalEl = document.querySelector('[x-data="attemptDetailsModal()"]');
                    if (!modalEl) {
                        console.error('Modal element not found');
                        return;
                    }

                    const modalComponent = Alpine.$data(modalEl);
                    if (!modalComponent) {
                        console.error('Modal component not found');
                        return;
                    }

                    await modalComponent.loadAttemptDetails(attemptId);
                } catch (error) {
                    console.error('Error al mostrar detalles:', error);
                    showNotification('Error al cargar los detalles del intento', 'error');
                }
            }
        };
    }

    function attemptDetailsModal() {
        return {
            showModal: false,
            loading: false,
            attemptData: {},

            async loadAttemptDetails(attemptId) {
                this.showModal = true;
                this.loading = true;

                try {
                    const response = await axios.get(`/admin/exam-attempts/${attemptId}/details`);
                    this.attemptData = response.data;
                } catch (error) {
                    console.error('Error loading attempt details:', error);
                    showNotification('Error al cargar los detalles', 'error');
                    this.closeModal();
                } finally {
                    this.loading = false;
                }
            },

            formatDuration(seconds) {
                if (!seconds) return 'N/A';
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            },

            closeModal() {
                this.showModal = false;
                this.loading = false;
                this.attemptData = {};
            }
        };
    }

    // Función de notificación (puedes reusar la de questions.blade.php)
    function showNotification(message, type = 'success') {
        // Implementar función de notificación aquí
        console.log(`${type}: ${message}`);
    }
</script>

<style>
    [x-cloak] { display: none !important; }

    /* Estilos para la tabla */
    table {
        border-spacing: 0;
        border-collapse: separate;
    }

    th {
        position: sticky;
        top: 0;
        background-color: #f9fafb;
        z-index: 10;
    }

    /* Barra de progreso */
    .progress-bar {
        transition: width 0.5s ease-in-out;
    }

    /* Animaciones */
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
