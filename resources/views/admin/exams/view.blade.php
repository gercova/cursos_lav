{{-- resources/views/admin/exams/view.blade.php --}}
@extends('layouts.admin')
@section('title', 'Exámenes del curso: ' . $course->title)
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    {{-- Cabecera --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Exámenes del curso</h1>
            <p class="text-gray-600 mt-1">{{ $course->title }}</p>
        </div>
        <a href="{{ route('admin.exams.create', $course) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 inline-flex items-center">
            <i class="fas fa-plus-circle mr-2"></i>
            Crear Nuevo Examen
        </a>
    </div>

    {{-- Información del curso --}}
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Categoría</p>
                <p class="font-semibold text-gray-800">{{ $course->category->name ?? 'Sin categoría' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Estado</p>
                <p class="font-semibold">
                    <span class="px-2 py-1 rounded-full text-xs {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Instructor</p>
                <p class="font-semibold text-gray-800">{{ $course->instructor->names ?? 'No asignado' }}</p>
            </div>
        </div>
    </div>

    {{-- Lista de exámenes --}}
    @if($course->exams->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($course->exams as $exam)
                <div class="border border-gray-200 rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5">
                        {{-- Título y estado --}}
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $exam->title }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs {{ $exam->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $exam->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        {{-- Descripción --}}
                        @if($exam->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $exam->description }}</p>
                        @endif

                        {{-- Estadísticas rápidas --}}
                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div class="bg-blue-50 rounded p-2 text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $exam->questions_count ?? 0 }}</p>
                                <p class="text-xs text-gray-600">Preguntas</p>
                            </div>
                            <div class="bg-purple-50 rounded p-2 text-center">
                                <p class="text-2xl font-bold text-purple-600">{{ $exam->duration ?? 0 }}</p>
                                <p class="text-xs text-gray-600">Minutos</p>
                            </div>
                            <div class="bg-green-50 rounded p-2 text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $exam->passing_score ?? 0 }}%</p>
                                <p class="text-xs text-gray-600">Nota mínima</p>
                            </div>
                            <div class="bg-orange-50 rounded p-2 text-center">
                                <p class="text-2xl font-bold text-orange-600">{{ $exam->max_attempts ?? '∞' }}</p>
                                <p class="text-xs text-gray-600">Intentos</p>
                            </div>
                        </div>

                        {{-- Estadísticas de intentos --}}
                        <div class="border-t border-gray-200 pt-3 mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Intentos totales:</span>
                                <span class="font-semibold">{{ $exam->attempts_count ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Aprobados:</span>
                                <span class="font-semibold text-green-600">{{ $exam->passed_count ?? 0 }}</span>
                            </div>
                            @if(($exam->attempts_count ?? 0) > 0)
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" 
                                             style="width: {{ (($exam->passed_count ?? 0) / ($exam->attempts_count ?? 1)) * 100 }}%">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Tasa de aprobación: {{ number_format((($exam->passed_count ?? 0) / ($exam->attempts_count ?? 1)) * 100, 1) }}%
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- Acciones --}}
                        <div class="flex gap-2">
                            <a href="{{ route('admin.exams.edit', $exam) }}" 
                               class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded transition duration-200 text-sm">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a>
                            <a href="{{ route('admin.exams.questions', $exam) }}" 
                               class="flex-1 text-center bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-3 rounded transition duration-200 text-sm">
                                <i class="fas fa-question-circle mr-1"></i> Preguntas
                            </a>
                            <a href="{{ route('admin.exams.results', $exam) }}" 
                               class="flex-1 text-center bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-3 rounded transition duration-200 text-sm">
                                <i class="fas fa-chart-line mr-1"></i> Resultados
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- No hay exámenes --}}
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="fas fa-clipboard-list text-6xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay exámenes creados</h3>
            <p class="text-gray-500 mb-6">Este curso aún no tiene exámenes asociados. ¡Comienza creando uno!</p>
            <a href="{{ route('admin.exams.create', $course) }}" 
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                <i class="fas fa-plus-circle mr-2"></i>
                Crear primer examen
            </a>
        </div>
    @endif
</div>

{{-- Scripts adicionales --}}
@push('scripts')
<script>
    // Si necesitas alguna funcionalidad adicional
    document.addEventListener('DOMContentLoaded', function() {
        // Puedes agregar aquí funcionalidades como tooltips, confirmaciones, etc.
        console.log('Vista de exámenes cargada');
    });
</script>
@endpush
@endsection