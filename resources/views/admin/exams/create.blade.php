@extends('layouts.admin')

@section('title', 'Crear Nuevo Examen')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ formProgress: 0 }" x-init="updateProgress()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Crear Nuevo Examen</h1>
                <p class="text-gray-600 mt-2">Configura un nuevo examen para evaluar a los estudiantes</p>
            </div>

            <div class="flex items-center gap-2 mt-4 lg:mt-0">
                <a href="{{ route('admin.exams.index') }}"
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
                <span>Revisar</span>
                <span>Completado</span>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 p-2">
        <form action="{{ route('admin.exams.store') }}"
              method="POST"
              id="examForm"
              oninput="updateProgress()">

            @include('admin.exams.partials.form')

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

        // Agregar puntaje por campos opcionales llenos
        const optionalFields = form.querySelectorAll('input:not([required]), textarea:not([required])');
        optionalFields.forEach(field => {
            if (field.value.trim() !== '') filled += 0.2;
        });

        const percentage = Math.min(100, Math.round((filled / (requiredFields.length + optionalFields.length * 0.2)) * 100));

        // Actualizar Alpine.js data
        const alpineElement = document.querySelector('[x-data]');
        if (alpineElement && alpineElement.__x) {
            alpineElement.__x.$data.formProgress = percentage;
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar eventos para actualizar vista previa
        const form = document.getElementById('examForm');

        // Actualizar vista previa en tiempo real
        form.addEventListener('input', function(e) {
            if (e.target.name === 'duration') {
                updateDurationPreview();
            } else if (e.target.name === 'passing_score') {
                updatePassingScorePreview();
            } else if (e.target.name === 'max_attempts') {
                updateAttemptsPreview();
            } else if (e.target.name === 'is_active') {
                updateStatusPreview();
            } else if (e.target.name === 'course_id') {
                updateCoursePreview();
            }
        });

        // Inicializar vistas previas
        updateDurationPreview();
        updatePassingScorePreview();
        updateAttemptsPreview();
        updateStatusPreview();
        updateCoursePreview();
    });

    // Funciones para actualizar vistas previas
    function updateDurationPreview() {
        const durationInput = document.getElementById('duration');
        const duration = parseInt(durationInput.value) || 60;

        // Actualizar horas y minutos
        const hours = Math.floor(duration / 60);
        const minutes = duration % 60;

        document.getElementById('duration-hours').textContent = hours;
        document.getElementById('duration-minutes').textContent = minutes;
        document.getElementById('preview-duration').textContent = duration;
    }

    function updatePassingScorePreview() {
        const scoreInput = document.getElementById('passing_score');
        const score = parseInt(scoreInput.value) || 70;

        // Actualizar barra y display
        document.getElementById('passing-score-display').textContent = score + '%';
        document.getElementById('passing-score-bar').style.width = score + '%';
        document.getElementById('preview-passing-score').textContent = score + '%';
    }

    function updateAttemptsPreview() {
        const attemptsInput = document.getElementById('max_attempts');
        const attempts = parseInt(attemptsInput.value) || 3;

        // Actualizar display
        document.getElementById('attempts-display').textContent = attempts === 0 ? 'Ilimitados' : attempts;
        document.getElementById('preview-attempts').textContent = attempts === 0 ? '∞' : attempts;

        // Mostrar/ocultar etiqueta de ilimitados
        const unlimitedSpan = document.getElementById('unlimited-attempts');
        if (attempts === 0) {
            unlimitedSpan.classList.remove('hidden');
        } else {
            unlimitedSpan.classList.add('hidden');
        }
    }

    function updateStatusPreview() {
        const statusCheckbox = document.getElementById('is_active');
        const statusText = document.getElementById('status-text');
        const previewStatus = document.getElementById('preview-status');

        if (statusCheckbox.checked) {
            statusText.textContent = 'Los estudiantes pueden tomar este examen';
            previewStatus.textContent = 'Activo';
            previewStatus.parentElement.parentElement.classList.remove('bg-gradient-to-br', 'from-red-50', 'to-red-100', 'border-red-200');
            previewStatus.parentElement.parentElement.classList.add('bg-gradient-to-br', 'from-orange-50', 'to-orange-100', 'border-orange-200');
        } else {
            statusText.textContent = 'Examen desactivado - Solo visible en administración';
            previewStatus.textContent = 'Inactivo';
            previewStatus.parentElement.parentElement.classList.remove('bg-gradient-to-br', 'from-orange-50', 'to-orange-100', 'border-orange-200');
            previewStatus.parentElement.parentElement.classList.add('bg-gradient-to-br', 'from-red-50', 'to-red-100', 'border-red-200');
        }
    }

    function updateCoursePreview() {
        const courseSelect = document.getElementById('course_id');
        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
        const courseInfo = document.getElementById('selected-course-info');
        const generalCourseInfo = document.getElementById('course-info');

        if (selectedOption.value) {
            // Mostrar información del curso seleccionado
            courseInfo.classList.remove('hidden');
            generalCourseInfo.classList.remove('hidden');

            // Actualizar información
            document.getElementById('selected-course-title').textContent = selectedOption.text;
            document.getElementById('selected-course-category').textContent = selectedOption.dataset.category || 'Sin categoría';
            document.getElementById('course-category').textContent = selectedOption.dataset.category || 'Sin categoría';

            // Aquí podrías agregar más información si la tuvieras (ej: número de estudiantes)
            document.getElementById('selected-course-students').textContent = '';
        } else {
            courseInfo.classList.add('hidden');
            generalCourseInfo.classList.add('hidden');
        }
    }
</script>
@endsection
