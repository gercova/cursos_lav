@extends('layouts.student')
@section('title', 'Examen: ' . $exam->title)
@section('content')

<div class="min-h-screen bg-gray-50">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header del examen -->
        <div class="mb-6 pt-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center text-sm text-gray-600 mb-2">
                        <a href="{{ route('student.exams') }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver a Exámenes
                        </a>
                        <span class="mx-2">•</span>
                        <a href="{{ route('student.course.learn', $exam->course->slug) }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                            {{ $exam->course->title }}
                        </a>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $exam->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $exam->course->title }}</p>

                    @if(isset($isNewAttempt) && $isNewAttempt)
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                            <i class="fas fa-info-circle mr-2"></i>
                            Nuevo intento #{{ $attemptNumber }}
                        </span>
                    </div>
                    @endif
                </div>

                <div>
                    @if($attempt && $attempt->id)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Intento en progreso
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón para iniciar examen (solo para nuevos intentos) -->
        @if(isset($isNewAttempt) && $isNewAttempt && !$attempt)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 card-hover">
            <div class="p-8 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i class="fas fa-play-circle text-blue-600 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-3">¿Listo para comenzar?</h2>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Este es tu intento número <span class="font-semibold text-blue-700">{{ $attemptNumber }}</span> de
                    <span class="font-semibold">{{ $exam->max_attempts > 0 ? $exam->max_attempts : 'ilimitados' }}</span>.
                    Tienes <span class="font-semibold text-blue-700">{{ $exam->duration }}</span> minutos para completar el examen.
                </p>

                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6 max-w-md mx-auto">
                    <div class="flex items-center justify-center space-x-6">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm">
                                <i class="fas fa-question-circle text-blue-600"></i>
                            </div>
                            <p class="text-sm text-gray-700">{{ $questions->count() }}</p>
                            <p class="text-xs text-gray-500">Preguntas</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm">
                                <i class="fas fa-clock text-green-600"></i>
                            </div>
                            <p class="text-sm text-gray-700">{{ $exam->duration }} min</p>
                            <p class="text-xs text-gray-500">Duración</p>
                        </div>
                        <div class="text-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm">
                                <i class="fas fa-trophy text-amber-600"></i>
                            </div>
                            <p class="text-sm text-gray-700">{{ $exam->passing_score }}</p>
                            <p class="text-xs text-gray-500">Para aprobar</p>
                        </div>
                    </div>
                </div>

                <form id="start-exam-form" action="{{ route('student.exams.start', $exam->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-play mr-3"></i>
                        Iniciar Examen
                    </button>
                    <p class="text-sm text-gray-500 mt-4">
                        <i class="fas fa-exclamation-triangle mr-1 text-yellow-500"></i>
                        El tiempo comenzará inmediatamente al iniciar
                    </p>
                </form>
            </div>
        </div>
        @endif

        <!-- Contenido del examen (solo mostrar si hay intento activo) -->
        @if($attempt && $attempt->id)
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Panel izquierdo - Información y navegación -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Información del examen -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Información
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-redo text-blue-500 mr-3"></i>
                                    <span class="text-sm text-gray-600">Intento</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $attempt->attempt_number }}/{{ $exam->max_attempts > 0 ? $exam->max_attempts : '∞' }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-green-500 mr-3"></i>
                                    <span class="text-sm text-gray-600">Duración</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $exam->duration }} min</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-trophy text-amber-500 mr-3"></i>
                                    <span class="text-sm text-gray-600">Para aprobar</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $exam->passing_score }} pts</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-question-circle text-purple-500 mr-3"></i>
                                    <span class="text-sm text-gray-600">Preguntas</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $questions->count() }}</span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-500 mr-3"></i>
                                    <span class="text-sm text-gray-600">Puntos totales</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $questions->sum('points') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navegación de preguntas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-compass text-emerald-600 mr-2"></i>
                            Navegación
                        </h3>

                        <div class="grid grid-cols-5 gap-2 mb-6">
                            @foreach($questions as $index => $question)
                            <button type="button"
                                onclick="scrollToQuestion({{ $index }})"
                                class="w-10 h-10 flex items-center justify-center rounded-lg border transition-all duration-200 question-nav-btn"
                                data-question-id="{{ $question->id }}"
                                data-index="{{ $index }}"
                                id="nav-btn-{{ $index }}"
                            >
                                {{ $index + 1 }}
                            </button>
                            @endforeach
                        </div>

                        <div class="pt-6 border-t border-gray-200">
                            <div class="text-center">
                                <button type="submit" form="exam-form" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Finalizar Examen
                                </button>
                                <p class="text-xs text-gray-500 mt-3">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Al enviar, no podrás modificar tus respuestas
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progreso de respuestas -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 card-hover">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                            Tu Progreso
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Preguntas respondidas</span>
                                    <span id="answered-count">0/{{ $questions->count() }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div id="answered-progress" class="h-2.5 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 progress-bar" style="width: 0%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Tiempo utilizado</span>
                                    <span id="time-used">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div id="time-progress-bar" class="h-2.5 rounded-full bg-gradient-to-r from-green-400 to-green-500 progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal - Preguntas -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <!-- Timer y barra de progreso -->
                    <div class="sticky top-0 z-10 bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Respondiendo preguntas</h2>
                                <p class="text-sm text-gray-600">Intento #{{ $attempt->attempt_number }} - {{ $exam->title }}</p>
                            </div>

                            <div class="text-center">
                                <div id="timer" class="text-3xl font-bold {{ $timeRemaining <= 300 ? 'text-red-600 animate-pulse' : 'text-gray-800' }} font-mono"></div>
                                <div class="flex items-center justify-center text-sm text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Tiempo restante</span>
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    Total: {{ $exam->duration }} minutos
                                </div>
                            </div>

                            <div class="hidden md:block">
                                <button onclick="saveProgress()" class="px-4 py-2 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de examen -->
                    <form id="exam-form" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

                        <div class="p-6">
                            <div class="space-y-8">
                                @foreach($questions as $index => $question)
                                <div id="question-{{ $question->id }}" class="border border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-all duration-200 question-card" data-index="{{ $index }}" data-question-id="{{ $question->id }}">
                                    <div class="flex items-start">
                                        <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-4 shadow-sm">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                        {{ $question->question }}
                                                    </h3>
                                                    @if($question->points > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-star mr-1"></i>
                                                        {{ $question->points }} punto{{ $question->points != 1 ? 's' : '' }}
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="hidden md:block">
                                                    <span class="text-xs font-medium px-2 py-1 rounded bg-gray-100 text-gray-700">
                                                        @if($question->type === 'multiple_choice')
                                                        <i class="fas fa-list-ul mr-1"></i> Opción múltiple
                                                        @elseif($question->type === 'true_false')
                                                        <i class="fas fa-check-circle mr-1"></i> Verdadero/Falso
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="space-y-3 ml-2">
                                                @if($question->type === 'multiple_choice')
                                                    @php
                                                        // Decodificar las opciones de manera segura
                                                        $options = $question->options;

                                                        // Si es string, intentar decodificar JSON
                                                        if (is_string($options)) {
                                                            $decoded = json_decode($options, true);
                                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                                $options = $decoded;
                                                            } else {
                                                                // Si no es JSON válido, intentar unserialize
                                                                $options = @unserialize($options) ?: [];
                                                            }
                                                        }

                                                        // Asegurar que sea array
                                                        $options = is_array($options) ? $options : [];
                                                    @endphp

                                                    @if(count($options) > 0)
                                                        @foreach($options as $key => $option)
                                                        <div class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 answer-option group cursor-pointer" data-question-id="{{ $question->id }}" data-answer-value="{{ $key }}">
                                                            <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-300 group-hover:border-blue-400 flex items-center justify-center mr-3">
                                                                <div class="w-2.5 h-2.5 rounded-full bg-blue-500 hidden selected-dot"></div>
                                                            </div>
                                                            <input type="radio"
                                                                id="question_{{ $question->id }}_{{ $key }}"
                                                                name="answers[{{ $question->id }}]"
                                                                value="{{ $key }}"
                                                                class="hidden"
                                                            >
                                                            <label for="question_{{ $question->id }}_{{ $key }}" class="block text-sm font-medium text-gray-700 cursor-pointer flex-1 select-none">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    @else
                                                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                                            <p class="text-red-600 text-sm">No hay opciones disponibles para esta pregunta</p>
                                                        </div>
                                                    @endif

                                                @elseif($question->type === 'true_false')
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                        <div class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all duration-200 answer-option group cursor-pointer" data-question-id="{{ $question->id }}" data-answer-value="true">
                                                            <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-300 group-hover:border-green-400 flex items-center justify-center mr-3">
                                                                <div class="w-2.5 h-2.5 rounded-full bg-green-500 hidden selected-dot"></div>
                                                            </div>
                                                            <input type="radio"
                                                                id="question_{{ $question->id }}_true"
                                                                name="answers[{{ $question->id }}]"
                                                                value="true"
                                                                class="hidden"
                                                            >
                                                            <label for="question_{{ $question->id }}_true" class="block text-sm font-medium text-gray-700 cursor-pointer flex-1 select-none">
                                                                <span class="flex items-center">
                                                                    <i class="fas fa-check text-green-500 mr-3 text-lg"></i>
                                                                    <div>
                                                                        <span class="font-semibold">Verdadero</span>
                                                                        <p class="text-xs text-gray-500 mt-1">Esta afirmación es correcta</p>
                                                                    </div>
                                                                </span>
                                                            </label>
                                                        </div>

                                                        <div class="flex items-center p-4 rounded-lg border border-gray-200 hover:border-red-300 hover:bg-red-50 transition-all duration-200 answer-option group cursor-pointer" data-question-id="{{ $question->id }}" data-answer-value="false">
                                                            <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-300 group-hover:border-red-400 flex items-center justify-center mr-3">
                                                                <div class="w-2.5 h-2.5 rounded-full bg-red-500 hidden selected-dot"></div>
                                                            </div>
                                                            <input type="radio"
                                                                id="question_{{ $question->id }}_false"
                                                                name="answers[{{ $question->id }}]"
                                                                value="false"
                                                                class="hidden"
                                                            >
                                                            <label for="question_{{ $question->id }}_false" class="block text-sm font-medium text-gray-700 cursor-pointer flex-1 select-none">
                                                                <span class="flex items-center">
                                                                    <i class="fas fa-times text-red-500 mr-3 text-lg"></i>
                                                                    <div>
                                                                        <span class="font-semibold">Falso</span>
                                                                        <p class="text-xs text-gray-500 mt-1">Esta afirmación es incorrecta</p>
                                                                    </div>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Botones de navegación -->
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                                    <button type="button" class="previous-btn px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center w-full md:w-auto justify-center">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Pregunta anterior
                                    </button>

                                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-3">
                                        <button type="button" class="save-btn px-5 py-3 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-all duration-200 flex items-center">
                                            <i class="fas fa-save mr-2"></i>
                                            Guardar progreso
                                        </button>

                                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex items-center">
                                            Finalizar examen
                                            <i class="fas fa-paper-plane ml-2"></i>
                                        </button>
                                    </div>

                                    <button type="button" class="next-btn px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center w-full md:w-auto justify-center">
                                        Siguiente pregunta
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                </div>

                                <!-- Indicadores de progreso -->
                                <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                        <span>Respondida</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-blue-600 mr-2"></div>
                                        <span>Actual</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-gray-300 mr-2"></div>
                                        <span>Sin responder</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmación -->
<div id="confirmModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95" id="modalContent">
        <div class="p-6">
            <div class="flex items-center mb-5">
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-full flex items-center justify-center mr-4 shadow-inner">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">¿Finalizar examen?</h3>
                    <p class="text-gray-600 mt-1">Esta acción no se puede deshacer</p>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-500 mt-0.5 mr-3"></i>
                    <div>
                        <p class="text-sm text-yellow-800 font-medium mb-1">Verifica tus respuestas</p>
                        <p class="text-xs text-yellow-700">
                            Asegúrate de haber respondido todas las preguntas antes de enviar.
                            Una vez finalizado, no podrás modificar tus respuestas.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" class="cancel-btn px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
                    Cancelar
                </button>
                <button type="button" class="confirm-submit-btn px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white rounded-lg transition-all duration-200 font-medium shadow-md hover:shadow-lg">
                    Sí, finalizar examen
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variables globales - DEFINIDAS FUERA DE CUALQUIER CONDICIONAL
    let timeRemaining = {{ $timeRemaining ?? $exam->duration * 60 }};
    let totalDuration = {{ $exam->duration * 60 }};
    let timerInterval;
    let currentQuestionIndex = 0;
    let questions = [];
    let answeredQuestions = new Set();

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar variables solo si hay intento activo
        @if($attempt && $attempt->id)
            questions = document.querySelectorAll('.question-card');
            initializeExam();
        @endif
    });

    // Inicializar el examen
    function initializeExam() {
        if (questions.length === 0) return;

        startTimer();
        loadSavedAnswers();
        updateProgress();

        // Configurar event listeners
        setupEventListeners();

        // Auto-guardar cada 30 segundos
        setInterval(autoSaveProgress, 30000);

        // Inicializar navegación
        scrollToQuestion(0);
    }

    // Funciones del timer
    function startTimer() {
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    }

    function updateTimer() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        // Actualizar display
        const timerElement = document.getElementById('timer');
        if (timerElement) {
            timerElement.textContent = timeString;

            // Cambiar color cuando quede poco tiempo
            if (timeRemaining <= 300) { // 5 minutos
                timerElement.classList.remove('text-gray-800');
                timerElement.classList.add('text-red-600', 'animate-pulse');
            }

            // Actualizar barra de progreso de tiempo
            const elapsed = totalDuration - timeRemaining;
            const progressPercent = (elapsed / totalDuration) * 100;
            const timeProgressBar = document.getElementById('time-progress-bar');
            const timeUsedText = document.getElementById('time-used');

            if (timeProgressBar) {
                timeProgressBar.style.width = `${progressPercent}%`;
            }
            if (timeUsedText) {
                timeUsedText.textContent = `${Math.round(progressPercent)}%`;
            }
        }

        // Finalizar automáticamente cuando se acabe el tiempo
        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            submitExam();
        } else {
            timeRemaining--;
        }
    }

    // Funciones de navegación
    function scrollToQuestion(index) {
        if (index >= 0 && index < questions.length) {
            currentQuestionIndex = index;
            const questionElement = questions[index];

            // Remover clase activa de todos los botones de navegación
            document.querySelectorAll('.question-nav-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            });

            // Añadir clase activa al botón actual
            const currentNavBtn = document.getElementById(`nav-btn-${index}`);
            if (currentNavBtn) {
                currentNavBtn.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            }

            // Scroll suave a la pregunta
            questionElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Resaltar pregunta actual
            questions.forEach(q => q.classList.remove('border-blue-400', 'bg-blue-50'));
            questionElement.classList.add('border-blue-400', 'bg-blue-50');
        }
    }

    function nextQuestion() {
        if (currentQuestionIndex < questions.length - 1) {
            currentQuestionIndex++;
            scrollToQuestion(currentQuestionIndex);
        }
    }

    function previousQuestion() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            scrollToQuestion(currentQuestionIndex);
        }
    }

    // Funciones de manejo de respuestas - CORREGIDAS
    function selectAnswer(questionId, answerValue) {
        // Encontrar todos los answer-option del mismo grupo de pregunta
        const allOptions = document.querySelectorAll(`.answer-option[data-question-id="${questionId}"]`);

        // Remover selección visual de todas las opciones
        allOptions.forEach(option => {
            option.classList.remove('border-blue-400', 'bg-blue-100');
            const dot = option.querySelector('.selected-dot');
            if (dot) dot.classList.add('hidden');
        });

        // Encontrar y marcar el radio button
        const radioInput = document.querySelector(`input[name="answers[${questionId}]"][value="${answerValue}"]`);
        if (radioInput) {
            radioInput.checked = true;

            // Actualizar UI de la opción seleccionada
            const selectedOption = document.querySelector(`.answer-option[data-question-id="${questionId}"][data-answer-value="${answerValue}"]`);
            if (selectedOption) {
                selectedOption.classList.add('border-blue-400', 'bg-blue-100');
                const selectedDot = selectedOption.querySelector('.selected-dot');
                if (selectedDot) {
                    selectedDot.classList.remove('hidden');
                }
            }

            // Guardar respuesta
            saveAnswer(questionId, answerValue);
        }
    }

    function saveAnswer(questionId, answerValue) {
        answeredQuestions.add(questionId);
        updateProgress();
        updateNavigation();

        // Guardar en localStorage
        localStorage.setItem(`exam_attempt_{{ $attempt ? $attempt->id : 0 }}_q${questionId}`, answerValue);
    }

    function isAnswered(questionId) {
        return answeredQuestions.has(questionId) || localStorage.getItem(`exam_attempt_{{ $attempt ? $attempt->id : 0 }}_q${questionId}`) !== null;
    }

    // Cargar respuestas guardadas
    function loadSavedAnswers() {
        questions.forEach((questionElement, index) => {
            const questionId = questionElement.dataset.questionId;
            const savedAnswer = localStorage.getItem(`exam_attempt_{{ $attempt ? $attempt->id : 0 }}_q${questionId}`);

            if (savedAnswer) {
                answeredQuestions.add(questionId);
                const inputElement = document.querySelector(`input[name="answers[${questionId}]"][value="${savedAnswer}"]`);

                if (inputElement) {
                    inputElement.checked = true;
                    const answerOption = document.querySelector(`.answer-option[data-question-id="${questionId}"][data-answer-value="${savedAnswer}"]`);

                    if (answerOption) {
                        answerOption.classList.add('border-blue-400', 'bg-blue-100');
                        const selectedDot = answerOption.querySelector('.selected-dot');
                        if (selectedDot) {
                            selectedDot.classList.remove('hidden');
                        }
                    }
                }
            }
        });

        updateProgress();
        updateNavigation();
    }

    // Actualizar progreso y navegación
    function updateProgress() {
        const answeredCount = answeredQuestions.size;
        const totalQuestions = questions.length;
        const progressPercent = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;

        // Actualizar contador
        const answeredCountElement = document.getElementById('answered-count');
        if (answeredCountElement) {
            answeredCountElement.textContent = `${answeredCount}/${totalQuestions}`;
        }

        // Actualizar barra de progreso
        const answeredProgressBar = document.getElementById('answered-progress');
        if (answeredProgressBar) {
            answeredProgressBar.style.width = `${progressPercent}%`;
        }
    }

    function updateNavigation() {
        document.querySelectorAll('.question-nav-btn').forEach(btn => {
            const questionId = parseInt(btn.dataset.questionId);
            const index = parseInt(btn.dataset.index);

            // Solo actualizar si no es la pregunta actual
            if (index !== currentQuestionIndex) {
                if (isAnswered(questionId)) {
                    btn.classList.add('bg-green-100', 'border-green-300', 'text-green-700');
                    btn.classList.remove('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                } else {
                    btn.classList.remove('bg-green-100', 'border-green-300', 'text-green-700');
                    btn.classList.add('border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
                }
            }
        });
    }

    // Funciones de guardado
    async function autoSaveProgress() {
        await saveToServer();
    }

    async function saveProgress() {
        await saveToServer();
        showToast('Progreso guardado correctamente', 'success');
    }

    async function saveToServer() {
        const answers = {};
        questions.forEach((questionElement, index) => {
            const questionId = questionElement.dataset.questionId;
            const saved = localStorage.getItem(`exam_attempt_{{ $attempt ? $attempt->id : 0 }}_q${questionId}`);
            if (saved) {
                answers[questionId] = saved;
            }
        });

        try {
            const response = await fetch('{{ route("student.exams.save-answers", $exam->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    attempt_id: {{ $attempt ? $attempt->id : 0 }},
                    answers: answers
                })
            });

            return await response.json();
        } catch (error) {
            console.error('Error al guardar progreso:', error);
            return { success: false };
        }
    }

    // Funciones del modal
    function showModal() {
        const modal = document.getElementById('confirmModal');
        const content = document.getElementById('modalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Animación de entrada
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function hideModal() {
        const modal = document.getElementById('confirmModal');
        const content = document.getElementById('modalContent');

        // Animación de salida
        content.classList.remove('scale-100');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 300);
    }

    // Envío del examen
    async function submitExam() {
        // Guardar progreso final
        await saveToServer();

        // Recolectar respuestas
        const form = document.getElementById('exam-form');
        const answers = {};

        questions.forEach((questionElement, index) => {
            const questionId = questionElement.dataset.questionId;
            const saved = localStorage.getItem(`exam_attempt_{{ $attempt ? $attempt->id : 0 }}_q${questionId}`);
            if (saved) {
                answers[questionId] = saved;
            }
        });

        // Agregar respuestas al formulario
        const answersInput = document.createElement('input');
        answersInput.type = 'hidden';
        answersInput.name = 'answers';
        answersInput.value = JSON.stringify(answers);
        form.appendChild(answersInput);

        // Limpiar localStorage
        questions.forEach((questionElement, index) => {
            const questionId = questionElement.dataset.questionId;
            localStorage.removeItem(`exam_attempt_{{ $attempt ? $attempt->id : 0 }}_q${questionId}`);
        });

        // Enviar formulario
        form.submit();
    }

    // Mostrar notificaciones
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full bg-white`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} mr-3 text-lg ${type === 'success' ? 'text-green-500' : 'text-blue-500'}"></i>
                <span class="font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Animación de entrada
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, 10);

        // Animación de salida
        setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');

            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Configurar event listeners - CORREGIDO
    function setupEventListeners() {
        // Event listeners para respuestas - SIMPLIFICADO Y CORREGIDO
        document.querySelectorAll('.answer-option').forEach(option => {
            option.addEventListener('click', function(e) {
                // Prevenir que el click se propague si viene del label
                if (e.target.tagName === 'LABEL') {
                    return;
                }

                const questionId = this.dataset.questionId;
                const answerValue = this.dataset.answerValue;
                selectAnswer(questionId, answerValue);
            });
        });

        // Navegación con teclado
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight' || e.key === ' ') {
                e.preventDefault();
                nextQuestion();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                previousQuestion();
            } else if (e.key === 's' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                saveProgress();
            }
        });

        // Prevenir acciones no deseadas
        window.addEventListener('beforeunload', function(e) {
            if (timeRemaining > 0) {
                e.preventDefault();
                e.returnValue = '¿Estás seguro de que quieres salir? Tu progreso se guardará automáticamente.';
            }
        });

        // Interceptar envío del formulario
        const examForm = document.getElementById('exam-form');
        if (examForm) {
            examForm.addEventListener('submit', function(e) {
                e.preventDefault();
                showModal();
            });
        }

        // Event listeners para botones de navegación
        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', nextQuestion);
        });

        document.querySelectorAll('.previous-btn').forEach(btn => {
            btn.addEventListener('click', previousQuestion);
        });

        document.querySelectorAll('.save-btn').forEach(btn => {
            btn.addEventListener('click', saveProgress);
        });

        document.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.addEventListener('click', hideModal);
        });

        document.querySelectorAll('.confirm-submit-btn').forEach(btn => {
            btn.addEventListener('click', submitExam);
        });

        // Event listeners para botones de navegación numérica
        document.querySelectorAll('.question-nav-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                scrollToQuestion(index);
            });
        });
    }
</script>

<style>
    /* Estilos personalizados consistentes con student.blade.php */
    :root {
        --sidebar-expanded: 250px;
        --sidebar-collapsed: 70px;
        --header-height: 64px;
    }

    /* Animaciones */
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.05); }
    }

    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* Card hover effect */
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -5px rgba(0, 0, 0, 0.02);
    }

    /* Progress bars */
    .progress-bar {
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Answer options */
    .answer-option {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .answer-option:hover {
        transform: translateX(4px);
    }

    /* Timer styling */
    .font-mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }

    /* Question cards */
    .question-card {
        scroll-margin-top: 120px;
        transition: all 0.3s ease;
    }

    /* Gradient backgrounds */
    .bg-gradient-to-br {
        background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
    }

    /* Custom scrollbar */
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

    /* Selection animation */
    @keyframes selectAnswer {
        0% { transform: scale(0.95); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }

    .selected-dot {
        animation: selectAnswer 0.3s ease-out;
    }

    /* Modal animations */
    #modalContent {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .question-card {
            padding: 1rem;
        }

        .answer-option {
            padding: 0.75rem;
        }

        #timer {
            font-size: 1.75rem;
        }
    }
</style>
@endsection
