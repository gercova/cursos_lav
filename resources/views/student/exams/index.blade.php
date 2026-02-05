@extends('layouts.student')
@section('title', 'Mis Exámenes')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Mis Exámenes</h1>
        <p class="text-gray-600">Gestiona tus exámenes pendientes y revisa los resultados de los realizados.</p>
    </div>

    <!-- Pestañas -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button id="pending-tab" class="tab-button active py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200" data-tab="pending">
                    <span class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        Pendientes
                        <span id="pending-count" class="ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ count($pendingExams) }}</span>
                    </span>
                </button>
                <button id="completed-tab" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200" data-tab="completed">
                    <span class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Realizados
                        <span id="completed-count" class="ml-2 bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">{{ count($completedExams) }}</span>
                    </span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Contenido de las pestañas -->
    <div id="tab-content">
        <!-- Exámenes Pendientes -->
        <div id="pending-content" class="tab-pane active animate-fade-in">
            @if($pendingExams->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pendingExams as $exam)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300 card-hover">
                        <div class="p-6">
                            <!-- Header del examen -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-clock mr-1"></i> Pendiente
                                    </span>
                                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ $exam->attempt_count + 1 }}/{{ $exam->max_attempts == 0 ? '∞' : $exam->max_attempts }}
                                    </span>
                                </div>
                                <div class="text-2xl text-blue-600">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>

                            <!-- Título y descripción -->
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $exam->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $exam->description }}</p>

                            <!-- Curso -->
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i class="fas fa-book mr-2"></i>
                                <span>{{ $exam->course->title }}</span>
                            </div>

                            <!-- Detalles del examen -->
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">{{ $exam->duration }}</div>
                                    <div class="text-xs text-gray-500">Minutos</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">{{ $exam->questions()->count() }}</div>
                                    <div class="text-xs text-gray-500">Preguntas</div>
                                </div>
                            </div>

                            <!-- Puntaje para aprobar -->
                            <div class="mb-6">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">Porcentaje para aprobar:</span>
                                    <span class="font-semibold text-gray-900">{{ round($exam->passing_score, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: 60%"></div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="flex space-x-3">
                                <!-- CORREGIDO: Usar student.exams.show con parámetro $exam->id -->
                                <a href="{{ route('student.exams.show', $exam->id) }}" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                                    <i class="fas fa-play-circle mr-2"></i>
                                    Iniciar Examen
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-blue-500 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">¡No tienes exámenes pendientes!</h3>
                    <p class="text-gray-600 max-w-md mx-auto mb-6">Todos los exámenes asignados han sido completados. Revisa la pestaña de exámenes realizados para ver tus resultados.</p>
                    <!-- CORREGIDO: Cambiar student.my-courses por student.courses si esa es tu ruta -->
                    <a href="{{ route('student.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-book-open mr-2"></i>
                        Ir a mis cursos
                    </a>
                </div>
            @endif
        </div>

        <!-- Exámenes Realizados -->
        <div id="completed-content" class="tab-pane hidden animate-fade-in">
            @if($completedExams->count() > 0)
                <div class="space-y-6">
                    @foreach($completedExams as $exam)
                    @php
                        // Tomar el último intento completado
                        $attempt = $exam->last_attempt;
                        $percentage = $attempt && $attempt->total_points > 0 ? ($attempt->score / $attempt->total_points) * 100 : 0;
                        $isPassed = $attempt ? $attempt->passed : false;
                        // O contar todos los intentos completados para este examen
                        $completedAttemptsCount = $exam->attempts ? $exam->attempts->count() : 0;
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300 card-hover">
                        <div class="p-6">
                            <!-- Header con estado -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    @if($isPassed)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                            <i class="fas fa-check-circle mr-1"></i> Aprobado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-rose-100 text-rose-800">
                                            <i class="fas fa-times-circle mr-1"></i> No Aprobado
                                        </span>
                                    @endif
                                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-history mr-1"></i>
                                        {{ $completedAttemptsCount }} intento(s)
                                    </span>
                                </div>
                                <div class="text-2xl {{ $isPassed ? 'text-emerald-600' : 'text-rose-600' }}">
                                    <i class="fas {{ $isPassed ? 'fa-trophy' : 'fa-redo' }}"></i>
                                </div>
                            </div>

                            <!-- Título y fecha -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $exam->title }}</h3>
                                    @if($attempt && $attempt->completed_at)
                                    <p class="text-sm text-gray-500">
                                        <i class="far fa-calendar mr-1"></i>
                                        Último intento: {{ $attempt->completed_at->format('d/m/Y H:i') }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                            @if($attempt)
                            <!-- Puntaje y progreso -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <div>
                                        <span class="text-2xl font-bold text-gray-900">{{ $attempt->score }}/{{ $attempt->total_points }}</span>
                                        <span class="text-gray-600 ml-2">puntos</span>
                                    </div>
                                    <div class="text-lg font-bold {{ $isPassed ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ round($percentage, 1) }}%
                                    </div>
                                </div>

                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full {{ $isPassed ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>

                                <div class="flex justify-between text-sm text-gray-500 mt-1">
                                    <span>0%</span>
                                    <span class="font-medium">Puntaje mínimo: {{ $exam->passing_score }}%</span>
                                    <span>100%</span>
                                </div>
                            </div>

                            <!-- Detalles del intento -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-lg font-bold text-gray-900">{{ $exam->duration }}</div>
                                    <div class="text-xs text-gray-500">Duración</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-lg font-bold text-gray-900">{{ $exam->questions()->count() }}</div>
                                    <div class="text-xs text-gray-500">Preguntas</div>
                                </div>

                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-lg font-bold text-gray-900">{{ $attempt->attempt_number }}</div>
                                    <div class="text-xs text-gray-500">Intento actual</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-lg font-bold text-gray-900">{{ $completedAttemptsCount }}</div>
                                    <div class="text-xs text-gray-500">Total realizados</div>
                                </div>
                            </div>
                            @endif

                            <!-- Botones de acción -->
                            <div class="flex space-x-3">
                                @if($attempt)
                                <a href="{{ route('student.exams.view', $attempt->id) }}" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-4 rounded-lg text-center transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    Ver Último Intento
                                </a>
                                @endif

                                <!-- Opcional: Enlace para ver todos los intentos si hay más de uno -->
                                @if($completedAttemptsCount > 1)
                                <a href="{{ route('student.exams') }}?exam={{ $exam->id }}" class="flex-1 border border-blue-500 text-blue-600 hover:bg-blue-50 font-medium py-3 px-4 rounded-lg text-center transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                                    <i class="fas fa-list mr-2"></i>
                                    Ver Todos
                                </a>
                                @endif

                                @if(isset($exam->can_retake) && $exam->can_retake)
                                <a href="{{ route('student.exams.show', $exam->id) }}" class="flex-1 border border-blue-500 text-blue-600 hover:bg-blue-50 font-medium py-3 px-4 rounded-lg text-center transition-all duration-200 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center">
                                    <i class="fas fa-redo mr-2"></i>
                                    Reintentar
                                </a>
                                @else
                                <button class="flex-1 border border-gray-300 text-gray-500 font-medium py-3 px-4 rounded-lg text-center cursor-not-allowed flex items-center justify-center" disabled>
                                    <i class="fas fa-ban mr-2"></i>
                                    Límite alcanzado
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay exámenes realizados</h3>
                    <p class="text-gray-600 max-w-md mx-auto mb-6">Aún no has completado ningún examen. Revisa la pestaña de exámenes pendientes para comenzar.</p>
                    <button id="show-pending-tab" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Ver exámenes pendientes
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .tab-button {
        position: relative;
        color: #6b7280;
        border-color: transparent;
    }

    .tab-button.active {
        color: #3b82f6;
        border-color: #3b82f6;
    }

    .tab-button:hover:not(.active) {
        color: #4b5563;
        border-color: #d1d5db;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo de pestañas
        const tabs = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.dataset.tab;

                // Actualizar pestañas activas
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Mostrar contenido correspondiente
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    content.classList.add('hidden');
                });

                const activeContent = document.getElementById(`${tabId}-content`);
                activeContent.classList.remove('hidden');
                activeContent.classList.add('active');
            });
        });

        // Botón para mostrar pestaña pendientes
        const showPendingBtn = document.getElementById('show-pending-tab');
        if (showPendingBtn) {
            showPendingBtn.addEventListener('click', function() {
                document.getElementById('pending-tab').click();
            });
        }

        // Inicializar tooltips si los hubiera
        initializeTooltips();
    });

    function initializeTooltips() {
        // Aquí puedes inicializar tooltips si usas alguna librería
    }
</script>
@endsection
