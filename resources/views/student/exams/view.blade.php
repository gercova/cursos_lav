@extends('layouts.student')
@section('title', 'Detalles del Examen')
@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Detalles del Examen</h1>
                <div class="flex items-center text-gray-600">
                    <a href="{{ route('student.exams') }}" class="hover:text-blue-600">
                        <i class="fas fa-arrow-left mr-2"></i> Volver a mis exámenes
                    </a>
                </div>
            </div>

            @if($attempt->passed)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                    <i class="fas fa-check-circle mr-2"></i> Aprobado
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-rose-100 text-rose-800">
                    <i class="fas fa-times-circle mr-2"></i> No Aprobado
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Panel izquierdo - Información del examen -->
        <div class="lg:col-span-2">
            <!-- Resumen del examen -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                    Resumen del Examen
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $exam->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $exam->description }}</p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-book text-gray-400 w-5 mr-3"></i>
                                <span class="text-gray-700">Curso: <span class="font-medium">{{ $exam->course->title }}</span></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user text-gray-400 w-5 mr-3"></i>
                                <span class="text-gray-700">Estudiante: <span class="font-medium">{{ auth()->user()->names }}</span></span>
                            </div>
                            <div class="flex items-center">
                                <i class="far fa-calendar text-gray-400 w-5 mr-3"></i>
                                <span class="text-gray-700">Fecha: <span class="font-medium">{{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : 'No disponible' }}</span></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5">
                            <div class="text-center mb-4">
                                <div class="text-5xl font-bold text-gray-900 mb-2">{{ round($percentage, 1) }}%</div>
                                <div class="text-gray-600">Puntuación Final</div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Puntos obtenidos:</span>
                                    <span class="font-bold text-gray-900">{{ round($attempt->score, 1) }}/{{ $attempt->total_points }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Puntaje mínimo (%):</span>
                                    <span class="font-bold {{ $attempt->passed ? 'text-emerald-600' : 'text-rose-600' }}">{{ round($exam->passing_score, 1) }} %</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Estado:</span>
                                    @if($attempt->passed)
                                        <span class="font-bold text-emerald-600">APROBADO</span>
                                    @else
                                        <span class="font-bold text-rose-600">NO APROBADO</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preguntas y respuestas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-question-circle mr-3 text-blue-500"></i>
                    Revisión de Preguntas ({{ $questions->count() }})
                </h2>

                <div class="space-y-6">
                    @foreach($questions as $index => $question)
                        @php
                            $userAnswer = isset($attempt->answers[$question->id]) ? $attempt->answers[$question->id] : null;
                            $isCorrect  = $userAnswer == $question->correct_answer;
                            $points     = $isCorrect ? $question->points : 0;
                        @endphp

                        <div class="border border-gray-200 rounded-xl p-5 hover:border-blue-300 transition-colors duration-200 {{ $isCorrect ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200' }}">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $isCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }} font-medium mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $question->question }}</h3>
                                        <div class="flex items-center mt-1">
                                            <span class="text-sm {{ $isCorrect ? 'text-emerald-600' : 'text-rose-600' }} font-medium mr-3">
                                                <i class="fas {{ $isCorrect ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                {{ $isCorrect ? 'Correcta' : 'Incorrecta' }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                Puntos: {{ $points }}/{{ $question->points }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-lg font-bold {{ $isCorrect ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $points }}
                                </div>
                            </div>

                            <!-- Opciones de respuesta -->
                            <div class="ml-11">
                                <div class="space-y-2">
                                    @if(is_array($question->options))
                                        @foreach($question->options as $key => $option)
                                            @php
                                                $isThisCorrect  = $key == $question->correct_answer;
                                                $isUserAnswer   = $userAnswer == $key;
                                            @endphp
                                            <div class="flex items-center p-3 rounded-lg border {{ $isThisCorrect ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200' }} {{ $isUserAnswer ? ($isThisCorrect ? 'ring-2 ring-emerald-500' : 'ring-2 ring-rose-500') : '' }}">
                                                <div class="flex items-center flex-1">
                                                    <div class="w-6 h-6 rounded-full border flex items-center justify-center mr-3 {{ $isThisCorrect ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-gray-300' }}">
                                                        @if($isThisCorrect)
                                                            <i class="fas fa-check text-xs"></i>
                                                        @endif
                                                    </div>
                                                    <span class="{{ $isThisCorrect ? 'font-medium text-emerald-800' : 'text-gray-700' }}">
                                                        {{ $option }}
                                                    </span>
                                                </div>

                                                @if($isUserAnswer)
                                                    <div class="ml-3 px-2 py-1 rounded text-xs font-medium {{ $isThisCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                        Tu respuesta
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                @if($question->explanation ?? false)
                                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-lightbulb text-blue-500 mr-2"></i>
                                            <span class="font-medium text-blue-800">Explicación:</span>
                                        </div>
                                        <p class="text-blue-700 text-sm">{{ $question->explanation }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Panel derecho - Estadísticas -->
        <div class="space-y-6">
            <!-- Estadísticas del intento -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-chart-bar mr-3 text-blue-500"></i>
                    Estadísticas
                </h2>

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-700">Progreso:</span>
                            <span class="font-medium">{{ $correctCount }}/{{ $questions->count() }} correctas</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="{{ $attempt->passed ? 'bg-emerald-500' : 'bg-rose-500' }} h-2.5 rounded-full" style="width: {{ $questions->count() > 0 ? ($correctCount / $questions->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-emerald-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-emerald-700">{{ $correctCount }}</div>
                            <div class="text-sm text-emerald-600">Correctas</div>
                        </div>
                        <div class="bg-rose-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-rose-700">{{ $incorrectCount }}</div>
                            <div class="text-sm text-rose-600">Incorrectas</div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-700">Intento número:</span>
                                <span class="font-medium">{{ $attempt->attempt_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Tiempo utilizado:</span>
                                <span class="font-medium">
                                    @if($attempt->completed_at && $attempt->started_at)
                                        {{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} minutos
                                    @else
                                        No disponible
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Tiempo límite:</span>
                                <span class="font-medium">{{ $exam->duration }} minutos</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Puntos totales:</span>
                                <span class="font-medium">{{ $attempt->total_points }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-bolt mr-3 text-blue-500"></i>
                    Acciones
                </h2>

                <div class="space-y-3">
                    <a href="{{ route('student.exams') }}" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Volver a mis exámenes
                    </a>

                    @if($exam->can_retake && $attempt->attempt_number < $exam->max_attempts)
                        <a href="{{ route('student.exams.show', $exam->id) }}"
                           class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:-translate-y-1">
                            <i class="fas fa-redo mr-2"></i>
                            Reintentar Examen
                        </a>
                    @elseif(!$exam->can_retake)
                        <button class="w-full flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed" disabled>
                            <i class="fas fa-ban mr-2"></i>
                            No se puede reintentar
                        </button>
                    @endif

                    <button onclick="window.print()" class="w-full flex items-center justify-center px-4 py-3 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-print mr-2"></i>
                        Imprimir Resultado
                    </button>
                </div>
            </div>

            <!-- Resumen rápido -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-6 text-white">
                <h3 class="text-lg font-bold mb-4">Resumen del Intento</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-300">Curso:</span>
                        <span class="font-medium">{{ $exam->course->title ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Porcentaje:</span>
                        <span class="font-bold text-xl {{ $attempt->passed ? 'text-emerald-300' : 'text-rose-300' }}">
                            {{ round($percentage, 1) }}%
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Fecha:</span>
                        <span>{{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: white !important;
            color: black !important;
        }

        .bg-white, .bg-gray-50, .bg-emerald-50, .bg-rose-50 {
            background: white !important;
            border: 1px solid #ddd !important;
        }

        .text-emerald-600, .text-rose-600 {
            color: black !important;
        }
    }
</style>
@endsection
