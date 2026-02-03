@extends('layouts.student')
@section('title', 'Resultado del Examen')
@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header con resultado -->
    <div class="text-center mb-10">
        @if($attempt->passed)
            <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-r from-emerald-100 to-green-100 flex items-center justify-center animate-bounce">
                <i class="fas fa-trophy text-5xl text-emerald-500"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">¡Felicidades!</h1>
            <p class="text-xl text-emerald-600 font-semibold mb-4">Has aprobado el examen</p>
        @else
            <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-r from-rose-100 to-pink-100 flex items-center justify-center">
                <i class="fas fa-redo text-5xl text-rose-500"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Examen Completado</h1>
            <p class="text-xl text-rose-600 font-semibold mb-4">No has alcanzado el puntaje mínimo</p>
        @endif

        <div class="inline-flex items-center px-6 py-3 rounded-full text-lg font-medium {{ $attempt->passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
            <span class="mr-2">{{ round($attempt->score, 1) }}/{{ $attempt->total_points }} puntos</span>
            <span class="font-bold">({{ round($percentage, 1) }}%)</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Panel de resultado principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                <!-- Barra de progreso -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <div class="text-lg font-semibold text-gray-900">Tu puntuación</div>
                        <div class="text-2xl font-bold {{ $attempt->passed ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ round($percentage, 1) }}%
                        </div>
                    </div>

                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $attempt->passed ? 'text-emerald-600 bg-emerald-200' : 'text-rose-600 bg-rose-200' }}">
                                    {{ $attempt->passed ? 'Aprobado' : 'No aprobado' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block {{ $attempt->passed ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $exam->passing_score }}% puntos requeridos
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-4 mb-4 text-xs flex rounded-full bg-gray-200">
                            <div style="width: {{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $attempt->passed ? 'bg-emerald-500' : 'bg-rose-500' }}">
                            </div>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600">
                            <span>0%</span>
                            <span>Puntaje mínimo: {{ round((($exam->passing_score * $attempt->total_points) / 100), 2).' = '.((int) $exam->passing_score).'%' }}</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>

                <!-- Resumen rápido -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $correctCount }}</div>
                        <div class="text-sm text-gray-600">Correctas</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $incorrectCount }}</div>
                        <div class="text-sm text-gray-600">Incorrectas</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $attempt->attempt_number }}</div>
                        <div class="text-sm text-gray-600">Intento</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl text-center">
                        <div class="text-2xl font-bold text-gray-900">
                            @if($attempt->completed_at && $attempt->started_at)
                                {{ $attempt->started_at->diffInMinutes($attempt->completed_at) }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">Minutos</div>
                    </div>
                </div>

                <!-- Información del examen -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Examen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <i class="fas fa-book text-gray-400 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Curso</div>
                                <div class="font-medium">{{ $exam->course->title }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="far fa-calendar text-gray-400 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Fecha de realización</div>
                                <div class="font-medium">{{ $attempt->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : 'No disponible' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-list-ol text-gray-400 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Total de preguntas</div>
                                <div class="font-medium">{{ $questions->count() }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock text-gray-400 mr-3"></i>
                            <div>
                                <div class="text-sm text-gray-500">Duración del examen</div>
                                <div class="font-medium">{{ $exam->duration }} minutos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral de acciones -->
        <div class="space-y-6">
            <!-- Acciones principales -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Siguientes pasos</h3>
                <div class="space-y-3">

                    <a href="{{ route('student.exams.view', $attempt->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:-translate-y-1">
                        <i class="fas fa-eye mr-2"></i>
                        Ver detalles completos
                    </a>

                    @php

                    @endphp

                    <a href="{{ route('student.certificates.show', $attempt->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:-translate-y-1">
                        <i class="fas fa-certificate text-white-600"></i>
                        &nbsp;Ver certificado
                    </a>

                    <a href="{{ route('student.exams') }}" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>
                        Ir a mis exámenes
                    </a>

                    <a href="{{ route('student.my-courses') }}" class="w-full flex items-center justify-center px-4 py-3 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-book-open mr-2"></i>
                        Continuar con el curso
                    </a>
                </div>
            </div>

            <!-- Posibilidad de reintento -->
            @if($exam->can_retake && $attempt->attempt_number < $exam->max_attempts)
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-6">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-redo text-amber-500 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-amber-800">Puedes reintentar</h3>
                    </div>
                    <p class="text-amber-700 text-sm mb-4">
                        Te quedan {{ $exam->max_attempts - $attempt->attempt_number }} intentos de {{ $exam->max_attempts == 0 ? '∞' : $exam->max_attempts }} disponibles.
                    </p>
                    <a href="{{ route('student.exams.show', $exam->id) }}" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                        <i class="fas fa-play-circle mr-2"></i>
                        Reintentar ahora
                    </a>
                </div>
            @elseif(!$exam->can_retake)
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-ban text-gray-400 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-700">Límite alcanzado</h3>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Has alcanzado el límite de intentos para este examen.
                    </p>
                </div>
            @endif

            <!-- Mensaje personalizado según resultado -->
            <div class="bg-gradient-to-r {{ $attempt->passed ? 'from-emerald-50 to-green-50 border-emerald-200' : 'from-rose-50 to-pink-50 border-rose-200' }} border rounded-xl p-6">
                @if($attempt->passed)
                    <div class="flex items-center mb-3">
                        <i class="fas fa-star text-emerald-500 mr-3"></i>
                        <h3 class="text-lg font-semibold text-emerald-800">¡Excelente trabajo!</h3>
                    </div>
                    <p class="text-emerald-700 text-sm">
                        Has demostrado un buen entendimiento del tema. Continúa así en los próximos exámenes.
                    </p>
                @else
                    <div class="flex items-center mb-3">
                        <i class="fas fa-lightbulb text-rose-500 mr-3"></i>
                        <h3 class="text-lg font-semibold text-rose-800">Áreas de mejora</h3>
                    </div>
                    <p class="text-rose-700 text-sm">
                        Te recomendamos revisar los temas del curso antes de un próximo intento.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Botón para imprimir resultado -->
    <div class="text-center no-print">
        <button onclick="window.print()" class="inline-flex items-center px-6 py-3 border-2 border-blue-500 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors duration-200">
            <i class="fas fa-print mr-2"></i>
            Imprimir resultado
        </button>
    </div>
</div>

<style>
    .animate-bounce {
        animation: bounce 1s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @media print {
        .no-print {
            display: none !important;
        }

        .bg-white, .bg-gray-50, .bg-emerald-50, .bg-rose-50 {
            background: white !important;
            border: 1px solid #ddd !important;
        }

        button, a {
            display: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efecto de confeti si aprobó
        @if($attempt->passed)
        setTimeout(() => {
            // Puedes agregar una librería de confeti aquí si lo deseas
            console.log('¡Examen aprobado! 🎉');
        }, 1000);
        @endif

        // Auto-redirección opcional después de 30 segundos
        setTimeout(() => {
            console.log('Recordatorio: Puedes ver los detalles del examen');
        }, 30000);
    });
</script>
@endsection
