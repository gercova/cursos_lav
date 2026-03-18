@extends('layouts.student')
@section('title', 'Detalles del Examen')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header con diseño mejorado -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                    <a href="{{ route('student.exams') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i> Mis Exámenes
                    </a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-900">Resultado</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $exam->title }}</h1>
                <p class="text-gray-600">{{ $exam->course->title }}</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Badge de estado mejorado -->
                <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ $attempt->passed ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200' }}">
                    @if($attempt->passed)
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-sm text-emerald-600 font-medium">Estado</span>
                            <span class="text-lg font-bold text-emerald-700">APROBADO</span>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                            <i class="fas fa-times-circle text-rose-600 text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-sm text-rose-600 font-medium">Estado</span>
                            <span class="text-lg font-bold text-rose-700">NO APROBADO</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Panel izquierdo - Preguntas y respuestas -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tarjeta de resumen rápido -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 text-white">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Puntaje</p>
                        <p class="text-2xl font-bold">{{ round($attempt->score, 1) }}/{{ $attempt->total_points }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Porcentaje</p>
                        <p class="text-2xl font-bold">{{ round($percentage, 1) }}%</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Correctas</p>
                        <p class="text-2xl font-bold text-emerald-300">{{ $correctCount }}/{{ $questions->count() }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Intento #</p>
                        <p class="text-2xl font-bold">{{ $attempt->attempt_number }}</p>
                    </div>
                </div>
            </div>

            <!-- Preguntas y respuestas mejoradas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-question-circle mr-3 text-blue-500"></i>
                        Revisión de Preguntas ({{ $questions->count() }})
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    @foreach($questions as $index => $question)
                        @php
                            $userAnswer = isset($attempt->answers[$question->id]) ? $attempt->answers[$question->id] : null;
                            $isCorrect  = $userAnswer == $question->correct_answer;
                            $points     = $isCorrect ? $question->points : 0;
                        @endphp

                        <div class="border rounded-xl overflow-hidden {{ $isCorrect ? 'border-emerald-200' : 'border-rose-200' }} hover:shadow-md transition-shadow">
                            <!-- Cabecera de la pregunta -->
                            <div class="px-5 py-4 {{ $isCorrect ? 'bg-emerald-50' : 'bg-rose-50' }} border-b {{ $isCorrect ? 'border-emerald-200' : 'border-rose-200' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg {{ $isCorrect ? 'bg-emerald-500' : 'bg-rose-500' }} flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $question->question }}</h3>
                                            <div class="flex items-center gap-3 mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                    <i class="fas {{ $isCorrect ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                    {{ $isCorrect ? 'Correcta' : 'Incorrecta' }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-star mr-1 text-amber-400"></i>
                                                    {{ $points }}/{{ $question->points }} puntos
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cuerpo con opciones -->
                            <div class="p-5 bg-white">
                                @if($question->type === 'multiple_choice' || $question->type === 'true_false')
                                    <div class="space-y-3">
                                        @php
                                            $options = is_array($question->options) ? $question->options : json_decode($question->options, true);
                                        @endphp

                                        @if($question->type === 'true_false')
                                            @php
                                                // Para verdadero/falso, aseguramos que las opciones sean claras
                                                $options = [
                                                    'true' => 'Verdadero',
                                                    'false' => 'Falso'
                                                ];
                                            @endphp
                                        @endif

                                        @foreach($options as $key => $option)
                                            @php
                                                $isThisCorrect  = (string)$key === (string)$question->correct_answer;
                                                $isUserAnswer   = (string)$userAnswer === (string)$key;
                                                
                                                // Determinar clases para el diseño
                                                if($isThisCorrect && $isUserAnswer) {
                                                    $cardClass = 'border-emerald-300 bg-emerald-50 ring-2 ring-emerald-200';
                                                    $iconClass = 'bg-emerald-500 text-white';
                                                    $icon = 'fa-check';
                                                } elseif($isThisCorrect) {
                                                    $cardClass = 'border-emerald-200 bg-emerald-50/50';
                                                    $iconClass = 'bg-emerald-500 text-white';
                                                    $icon = 'fa-check';
                                                } elseif($isUserAnswer) {
                                                    $cardClass = 'border-rose-300 bg-rose-50 ring-2 ring-rose-200';
                                                    $iconClass = 'bg-rose-500 text-white';
                                                    $icon = 'fa-times';
                                                } else {
                                                    $cardClass = 'border-gray-200 hover:border-gray-300';
                                                    $iconClass = 'border-2 border-gray-300';
                                                    $icon = '';
                                                }
                                            @endphp

                                            <div class="flex items-center p-4 rounded-xl border-2 transition-all {{ $cardClass }}">
                                                <div class="flex items-center flex-1 gap-3">
                                                    <!-- Indicador visual -->
                                                    <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $iconClass }} flex items-center justify-center">
                                                        @if($icon)
                                                            <i class="fas {{ $icon }} text-xs"></i>
                                                        @else
                                                            <span class="text-xs font-medium text-gray-600">{{ chr(65 + $loop->index) }}</span>
                                                        @endif
                                                    </div>

                                                    <!-- Texto de la opción -->
                                                    <span class="flex-1 {{ $isThisCorrect ? 'font-semibold text-gray-900' : 'text-gray-700' }}">
                                                        {{ $option }}
                                                        @if($isThisCorrect)
                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                                                <i class="fas fa-check-circle mr-1"></i> Respuesta correcta
                                                            </span>
                                                        @endif
                                                    </span>

                                                    <!-- Indicador de respuesta del usuario -->
                                                    @if($isUserAnswer)
                                                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold {{ $isThisCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                            <i class="fas {{ $isThisCorrect ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                                                            Tu respuesta
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Para otros tipos de preguntas -->
                                    <div class="space-y-3">
                                        <div class="flex items-center p-4 rounded-xl border-2 {{ $isCorrect ? 'border-emerald-200 bg-emerald-50' : 'border-rose-200 bg-rose-50' }}">
                                            <div class="flex items-center flex-1 gap-3">
                                                <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $isCorrect ? 'bg-emerald-500' : 'bg-rose-500' }} flex items-center justify-center text-white">
                                                    <i class="fas {{ $isCorrect ? 'fa-check' : 'fa-times' }}"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <span class="block text-sm text-gray-600 mb-1">Tu respuesta:</span>
                                                    <span class="font-medium">{{ $userAnswer ?? 'No respondida' }}</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="block text-sm text-gray-600 mb-1">Respuesta correcta:</span>
                                                    <span class="font-medium">{{ $question->correct_answer }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Explicación (si existe) -->
                                @if($question->explanation ?? false)
                                    <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-lightbulb text-amber-500 mt-1"></i>
                                            <div>
                                                <span class="text-sm font-semibold text-blue-900 block mb-1">Explicación:</span>
                                                <p class="text-sm text-blue-800">{{ $question->explanation }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Panel derecho - Estadísticas y acciones mejoradas -->
        <div class="space-y-6">
            <!-- Tarjeta de estadísticas detalladas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-chart-pie mr-3 text-blue-500"></i>
                        Estadísticas
                    </h2>
                </div>

                <div class="p-6">
                    <!-- Gráfico de progreso circular -->
                    <div class="flex justify-center mb-6">
                        <div class="relative w-32 h-32">
                            <svg class="w-full h-full" viewBox="0 0 100 100">
                                <!-- Círculo de fondo -->
                                <circle
                                    class="text-gray-200 stroke-current"
                                    stroke-width="10"
                                    cx="50"
                                    cy="50"
                                    r="40"
                                    fill="transparent"
                                ></circle>
                                <!-- Círculo de progreso -->
                                <circle
                                    class="{{ $attempt->passed ? 'text-emerald-500' : 'text-rose-500' }} stroke-current"
                                    stroke-width="10"
                                    stroke-linecap="round"
                                    cx="50"
                                    cy="50"
                                    r="40"
                                    fill="transparent"
                                    stroke-dasharray="251.2"
                                    stroke-dashoffset="{{ 251.2 - (251.2 * ($correctCount / $questions->count())) }}"
                                    transform="rotate(-90 50 50)"
                                ></circle>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-2xl font-bold">{{ round(($correctCount / $questions->count()) * 100, 0) }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas en grid -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-emerald-50 rounded-xl p-4 text-center">
                            <span class="text-3xl font-bold text-emerald-600 block">{{ $correctCount }}</span>
                            <span class="text-sm text-emerald-700 font-medium">Correctas</span>
                            <span class="text-xs text-emerald-600 block mt-1">
                                {{ $questions->count() > 0 ? round(($correctCount / $questions->count()) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="bg-rose-50 rounded-xl p-4 text-center">
                            <span class="text-3xl font-bold text-rose-600 block">{{ $incorrectCount }}</span>
                            <span class="text-sm text-rose-700 font-medium">Incorrectas</span>
                            <span class="text-xs text-rose-600 block mt-1">
                                {{ $questions->count() > 0 ? round(($incorrectCount / $questions->count()) * 100, 1) : 0 }}%
                            </span>
                        </div>
                    </div>

                    <!-- Detalles adicionales -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Tiempo utilizado:</span>
                            <span class="font-semibold">
                                @if($attempt->completed_at && $attempt->started_at)
                                    {{ floor($attempt->started_at->diffInMinutes($attempt->completed_at)) }} min
                                    {{ $attempt->started_at->diffInSeconds($attempt->completed_at) % 60 }} seg
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Puntaje mínimo:</span>
                            <span class="font-semibold">{{ $exam->passing_score }}%</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Fecha:</span>
                            <span class="font-semibold">{{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones mejoradas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-cog mr-3 text-blue-500"></i>
                        Acciones
                    </h2>
                </div>

                <div class="p-6 space-y-3">
                    <a href="{{ route('student.exams') }}"
                       class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 group">
                        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                        Volver a mis exámenes
                    </a>

                    @php
                        $canRetake = $exam->max_attempts == 0 || $attempt->attempt_number < $exam->max_attempts;
                    @endphp

                    @if($canRetake && !$attempt->passed)
                        <a href="{{ route('student.exams.show', $exam->id) }}"
                           class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl group">
                            <i class="fas fa-redo-alt mr-2 group-hover:rotate-180 transition-transform duration-500"></i>
                            Reintentar Examen
                        </a>
                    @elseif(!$canRetake)
                        <div class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-500 rounded-xl cursor-not-allowed">
                            <i class="fas fa-ban mr-2"></i>
                            Límite de intentos alcanzado
                        </div>
                    @endif

                    <button onclick="window.print()" class="w-full flex items-center justify-center px-4 py-3 border-2 border-blue-200 text-blue-700 rounded-xl hover:bg-blue-50 transition-all duration-200 group">
                        <i class="fas fa-print mr-2 group-hover:scale-110 transition-transform"></i>
                        Imprimir Resultado
                    </button>

                    @if(!$attempt->passed)
                        <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-200">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-amber-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-semibold text-amber-800">Certificado pendiente</p>
                                    <p class="text-xs text-amber-700 mt-1">El certificado estará disponible en breve.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('student.certificates.download-exact', $certificate->id) }}" class="w-full flex items-center justify-center px-4 py-3 border-2 border-green-200 text-green-700 rounded-xl hover:bg-green-50 transition-all duration-200 group">
                            <i class="fas fa-print mr-2 group-hover:scale-110 transition-transform"></i>
                            Descargar certificado
                        </a>
                    @endif
                </div>
            </div>

            <!-- Información del curso (mini) -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 text-white">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-book-open text-blue-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold">{{ $exam->course->title ?? 'N/A' }}</h3>
                        <p class="text-sm text-gray-400">Curso asociado</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Duración del curso:</span>
                        <span class="font-medium">{{ $exam->course->duration ?? 0 }} horas</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Exámenes:</span>
                        <span class="font-medium">{{ $exam->course->exams->count() ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    @media print {
        body {
            background: white !important;
        }

        .no-print {
            display: none !important;
        }

        .bg-emerald-50, .bg-rose-50, .bg-blue-50 {
            background-color: #f9fafb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .bg-emerald-500, .bg-rose-500, .bg-blue-600 {
            background-color: #e5e7eb !important;
            color: black !important;
        }

        .border-emerald-200, .border-rose-200 {
            border-color: #d1d5db !important;
        }

        .text-emerald-600, .text-rose-600 {
            color: #374151 !important;
        }
    }

    /* Animaciones suaves */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 200ms;
    }

    /* Hover effects */
    .hover\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }

    /* Para el gráfico circular */
    .stroke-current {
        stroke: currentColor;
    }

    circle {
        transition: stroke-dashoffset 0.5s ease;
    }
</style>

<!-- Script para animaciones -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animar la entrada de las preguntas
        const questions = document.querySelectorAll('.border.rounded-xl');
        questions.forEach((question, index) => {
            question.style.animation = `slideIn 0.3s ease-out ${index * 0.05}s forwards`;
            question.style.opacity = '0';
        });

        // Animar el gráfico circular
        setTimeout(() => {
            const circle = document.querySelector('circle.progress-circle');
            if (circle) {
                circle.style.strokeDashoffset = circle.getAttribute('stroke-dashoffset');
            }
        }, 100);
    });

    // Keyframes para animación
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection