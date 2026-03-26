{{-- resources/views/admin/exams/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Crear Nuevo Examen para: ' . $course->title)
@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ formProgress: 0 }" x-init="updateProgress()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Crear Nuevo Examen</h1>
                <p class="text-gray-600 mt-2">Configura un nuevo examen para evaluar a los estudiantes del curso: <strong class="text-blue-600">{{ $course->title }}</strong></p>
            </div>

            <div class="flex items-center gap-2 mt-4 lg:mt-0">
                <a href="{{ route('admin.exams.view', $course) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Exámenes
                </a>
            </div>
        </div>

        <!-- Barra de progreso -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-700">Completado del formulario</span>
                <span class="text-sm font-bold text-blue-600" x-text="`${formProgress}%`"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500"
                     :style="`width: ${formProgress}%`"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                <span>Información Básica</span>
                <span>Configuración</span>
                <span>Estado</span>
                <span>Completado</span>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.exams.store') }}" method="POST" id="examForm" oninput="updateProgress()">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="p-6 space-y-6">
                {{-- Información Básica --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Información Básica
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Título del examen --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título del Examen <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   placeholder="Ej: Examen Final - Módulo 1"
                                   value="{{ old('title') }}">
                            <p class="mt-1 text-xs text-gray-500">Nombre descriptivo que identifique el examen</p>
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                      placeholder="Describe el contenido y objetivos del examen...">{{ old('description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Información adicional para los estudiantes (opcional)</p>
                        </div>
                    </div>
                </div>

                {{-- Configuración del Examen --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-sliders-h text-green-500"></i>
                        Configuración del Examen
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Duración --}}
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                Duración (minutos) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="duration" 
                                       id="duration" 
                                       required
                                       min="1"
                                       max="480"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       placeholder="60"
                                       value="{{ old('duration', 60) }}">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-gray-500" id="duration-preview">
                                Equivalente a <span id="duration-hours">1</span> hora(s) y <span id="duration-minutes">0</span> minuto(s)
                            </div>
                        </div>

                        {{-- Nota Mínima --}}
                        <div>
                            <label for="passing_score" class="block text-sm font-medium text-gray-700 mb-2">
                                Nota Mínima (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       name="passing_score" 
                                       id="passing_score" 
                                       required
                                       min="0"
                                       max="100"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       placeholder="70"
                                       value="{{ old('passing_score', 70) }}">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="passing-score-bar" class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: 70%"></div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Calificación mínima para aprobar: <span id="passing-score-display">70%</span></p>
                            </div>
                        </div>

                        {{-- Intentos Máximos --}}
                        <div>
                            <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                                Intentos Máximos <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="max_attempts" 
                                        id="max_attempts" 
                                        required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 appearance-none">
                                    <option value="1">1 intento</option>
                                    <option value="2">2 intentos</option>
                                    <option value="3" {{ old('max_attempts', 3) == 3 ? 'selected' : '' }}>3 intentos (Recomendado)</option>
                                    <option value="5">5 intentos</option>
                                    <option value="10">10 intentos</option>
                                    <option value="0">Ilimitados</option>
                                </select>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                <span id="attempts-display">{{ old('max_attempts', 3) }}</span> intento(s) permitido(s)
                                <span id="unlimited-attempts" class="hidden text-green-600 font-medium">(Ilimitados)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Estado y Publicación --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-globe text-purple-500"></i>
                        Estado y Publicación
                    </h3>
                    
                    <div class="space-y-4">
                        {{-- Estado Activo --}}
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <div>
                                <label for="is_active" class="font-medium text-gray-700 cursor-pointer">
                                    Examen Activo
                                </label>
                                <p class="text-sm text-gray-500 mt-1" id="status-text">Los estudiantes pueden tomar este examen</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       class="sr-only peer"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        {{-- Información adicional --}}
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">¿Cómo funciona la publicación?</p>
                                    <ul class="space-y-1">
                                        <li>• <strong>Activo:</strong> Los estudiantes podrán ver y realizar el examen</li>
                                        <li>• <strong>Inactivo:</strong> El examen estará oculto para los estudiantes</li>
                                        <li>• Puedes cambiar el estado en cualquier momento desde la edición</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vista Previa del Examen --}}
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-eye text-indigo-500"></i>
                        Vista Previa del Examen
                    </h3>
                    
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6">
                        <div class="flex items-start justify-between flex-wrap gap-4">
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-gray-800" id="preview-title">Nuevo Examen</h4>
                                <p class="text-gray-600 mt-1 text-sm" id="preview-description">Sin descripción</p>
                                <div class="flex flex-wrap gap-3 mt-3">
                                    <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                        <i class="fas fa-clock text-blue-500"></i>
                                        <span id="preview-duration">60</span> minutos
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        Nota mínima: <span id="preview-passing-score">70%</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-sm text-gray-600">
                                        <i class="fas fa-redo-alt text-purple-500"></i>
                                        Intentos: <span id="preview-attempts">3</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-sm">
                                        <i class="fas fa-circle"></i>
                                        Estado: <span id="preview-status" class="font-semibold text-green-600">Activo</span>
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center bg-white rounded-lg px-6 py-3 shadow-sm">
                                <i class="fas fa-clipboard-list text-4xl text-blue-500 mb-2"></i>
                                <p class="text-xs text-gray-500">Curso: {{ $course->title }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información del Curso --}}
                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-graduation-cap text-green-600 mt-0.5 text-xl"></i>
                        <div class="text-sm text-green-800">
                            <p class="font-medium mb-1">Curso asociado</p>
                            <p class="text-xs text-green-700 mb-1">{{ $course->category->name ?? 'Sin categoría' }}</p>
                            <p class="font-semibold">{{ $course->title }}</p>
                            @if($course->instructor)
                                <p class="text-xs mt-1">Instructor: {{ $course->instructor->names }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Acciones del Formulario --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.exams.view', $course) }}" 
                       class="px-6 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Crear Examen
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Información de ayuda -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-blue-100">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-blue-900">Configuración Recomendada</h4>
            </div>
            <ul class="space-y-2 text-sm text-blue-800">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Duración: 60-90 minutos para exámenes finales</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Puntaje mínimo: 70% para aprobación estándar</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Intentos: 3 intentos como valor recomendado</span>
                </li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-green-100">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-green-900">Seguridad y Control</h4>
            </div>
            <ul class="space-y-2 text-sm text-green-800">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Tiempo límite ayuda a prevenir consultas externas</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Límite de intentos evita abusos del sistema</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Activar examen solo cuando esté listo</span>
                </li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-purple-100">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-purple-900">Próximos Pasos</h4>
            </div>
            <ul class="space-y-2 text-sm text-purple-800">
                <li class="flex items-start gap-2">
                    <span class="font-medium text-purple-900">1.</span>
                    <span>Crear examen con esta configuración</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-medium text-purple-900">2.</span>
                    <span>Agregar preguntas al examen</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-medium text-purple-900">3.</span>
                    <span>Probar el examen antes de activarlo</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="font-medium text-purple-900">4.</span>
                    <span>Revisar resultados de los estudiantes</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Actualizar barra de progreso
    function updateProgress() {
        const form = document.getElementById('examForm');
        const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
        let filled = 0;

        requiredFields.forEach(field => {
            if (field.type === 'checkbox') {
                if (field.checked) filled++;
            } else if (field.value.trim() !== '' && field.value !== '0') {
                filled++;
            }
        });

        const percentage = Math.min(100, Math.round((filled / requiredFields.length) * 100));

        // Actualizar Alpine.js data
        const alpineElement = document.querySelector('[x-data]');
        if (alpineElement && alpineElement.__x) {
            alpineElement.__x.$data.formProgress = percentage;
        }
    }

    // Funciones para actualizar vistas previas
    function updateDurationPreview() {
        const durationInput = document.getElementById('duration');
        const duration = parseInt(durationInput.value) || 60;

        const hours = Math.floor(duration / 60);
        const minutes = duration % 60;

        const hoursSpan = document.getElementById('duration-hours');
        const minutesSpan = document.getElementById('duration-minutes');
        const previewDuration = document.getElementById('preview-duration');

        if (hoursSpan) hoursSpan.textContent = hours;
        if (minutesSpan) minutesSpan.textContent = minutes;
        if (previewDuration) previewDuration.textContent = duration;
    }

    function updatePassingScorePreview() {
        const scoreInput = document.getElementById('passing_score');
        const score = parseInt(scoreInput.value) || 70;

        const displaySpan = document.getElementById('passing-score-display');
        const bar = document.getElementById('passing-score-bar');
        const previewScore = document.getElementById('preview-passing-score');

        if (displaySpan) displaySpan.textContent = score + '%';
        if (bar) bar.style.width = score + '%';
        if (previewScore) previewScore.textContent = score + '%';
    }

    function updateAttemptsPreview() {
        const attemptsSelect = document.getElementById('max_attempts');
        const attempts = parseInt(attemptsSelect.value) || 3;

        const displaySpan = document.getElementById('attempts-display');
        const unlimitedSpan = document.getElementById('unlimited-attempts');
        const previewAttempts = document.getElementById('preview-attempts');

        if (displaySpan) displaySpan.textContent = attempts === 0 ? 'Ilimitados' : attempts;
        if (unlimitedSpan) {
            if (attempts === 0) {
                unlimitedSpan.classList.remove('hidden');
            } else {
                unlimitedSpan.classList.add('hidden');
            }
        }
        if (previewAttempts) previewAttempts.textContent = attempts === 0 ? '∞' : attempts;
    }

    function updateStatusPreview() {
        const statusCheckbox = document.getElementById('is_active');
        const statusText = document.getElementById('status-text');
        const previewStatus = document.getElementById('preview-status');

        if (statusCheckbox.checked) {
            if (statusText) statusText.textContent = 'Los estudiantes pueden tomar este examen';
            if (previewStatus) {
                previewStatus.textContent = 'Activo';
                previewStatus.classList.remove('text-red-600');
                previewStatus.classList.add('text-green-600');
            }
        } else {
            if (statusText) statusText.textContent = 'Examen desactivado - Solo visible en administración';
            if (previewStatus) {
                previewStatus.textContent = 'Inactivo';
                previewStatus.classList.remove('text-green-600');
                previewStatus.classList.add('text-red-600');
            }
        }
    }

    function updateTitlePreview() {
        const title = document.getElementById('title').value;
        const previewTitle = document.getElementById('preview-title');
        if (previewTitle) previewTitle.textContent = title.trim() || 'Nuevo Examen';
    }

    function updateDescriptionPreview() {
        const desc = document.getElementById('description').value;
        const previewDesc = document.getElementById('preview-description');
        if (previewDesc) previewDesc.textContent = desc.trim() || 'Sin descripción';
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar eventos para actualizar vista previa
        const titleInput = document.getElementById('title');
        const descInput = document.getElementById('description');
        const durationInput = document.getElementById('duration');
        const scoreInput = document.getElementById('passing_score');
        const attemptsSelect = document.getElementById('max_attempts');
        const statusCheckbox = document.getElementById('is_active');

        if (titleInput) titleInput.addEventListener('input', updateTitlePreview);
        if (descInput) descInput.addEventListener('input', updateDescriptionPreview);
        if (durationInput) durationInput.addEventListener('input', updateDurationPreview);
        if (scoreInput) scoreInput.addEventListener('input', updatePassingScorePreview);
        if (attemptsSelect) attemptsSelect.addEventListener('change', updateAttemptsPreview);
        if (statusCheckbox) statusCheckbox.addEventListener('change', updateStatusPreview);

        // Inicializar vistas previas
        updateDurationPreview();
        updatePassingScorePreview();
        updateAttemptsPreview();
        updateStatusPreview();
        updateTitlePreview();
        updateDescriptionPreview();
        updateProgress();
    });
</script>
@endsection