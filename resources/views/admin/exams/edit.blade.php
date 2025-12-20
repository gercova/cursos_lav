@extends('layouts.admin')
@section('title', 'Editar Examen: ' . $exam->title)
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Editar Examen</h1>
                        <p class="text-gray-600 mt-1">{{ $exam->title }}</p>
                    </div>
                </div>

                <!-- Badges de estado -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $exam->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $exam->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        {{ $exam->questions_count ?? 0 }} preguntas
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                        {{ $exam->duration }} minutos
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                        {{ $exam->attempts_count ?? 0 }} intentos realizados
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 lg:mt-0">
                <a href="{{ route('admin.exams.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Exámenes
                </a>
                <button onclick="toggleExamStatus({{ $exam->id }})"
                        class="inline-flex items-center gap-2 px-4 py-2.5 {{ $exam->is_active ? 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800' : 'bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800' }} text-white rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($exam->is_active)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        @endif
                    </svg>
                    {{ $exam->is_active ? 'Desactivar' : 'Activar' }}
                </button>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <a href="#"
                   class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
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
                        Preguntas ({{ $exam->questions_count ?? 0 }})
                    </div>
                </a>
                <a href="{{ route('admin.exams.results', $exam) }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Resultados
                    </div>
                </a>
                <a href="#"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Vista Previa
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-8 p-2">
        <form action="{{ route('admin.exams.update', $exam) }}" method="POST" id="examForm">
            @include('admin.exams.partials.form')
        </form>
    </div>

    <!-- Estadísticas y Acciones Rápidas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Estadísticas del Examen -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Estadísticas del Examen</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-900">{{ $exam->attempts_count ?? 0 }}</div>
                            <div class="text-sm text-blue-700 mt-1">Intentos Totales</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-900">{{ $exam->passed_count ?? 0 }}</div>
                            <div class="text-sm text-green-700 mt-1">Estudiantes Aprobados</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-900">
                                {{ $exam->attempts_count > 0 ? round((($exam->passed_count ?? 0) / $exam->attempts_count) * 100) : 0 }}%
                            </div>
                            <div class="text-sm text-purple-700 mt-1">Tasa de Aprobación</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-900">{{ $exam->questions_count ?? 0 }}</div>
                            <div class="text-sm text-orange-700 mt-1">Total Preguntas</div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico simple (puedes implementar un gráfico real más adelante) -->
                <div class="mt-6 p-4 bg-white rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-medium text-gray-900">Distribución de Puntajes</h4>
                        <span class="text-sm text-gray-500">Últimos 30 días</span>
                    </div>
                    <div class="h-32 flex items-end space-x-1">
                        <!-- Aquí podrías agregar un gráfico de barras real -->
                        <div class="flex-1 bg-gradient-to-t from-blue-500 to-blue-600 rounded-t" style="height: 70%"></div>
                        <div class="flex-1 bg-gradient-to-t from-green-500 to-green-600 rounded-t" style="height: 85%"></div>
                        <div class="flex-1 bg-gradient-to-t from-purple-500 to-purple-600 rounded-t" style="height: 60%"></div>
                        <div class="flex-1 bg-gradient-to-t from-yellow-500 to-yellow-600 rounded-t" style="height: 90%"></div>
                        <div class="flex-1 bg-gradient-to-t from-red-500 to-red-600 rounded-t" style="height: 45%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm sticky top-6">
                <h3 class="text-xl font-bold text-blue-900 mb-6">Acciones Rápidas</h3>

                <div class="space-y-4">
                    <!-- Gestionar Preguntas -->
                    <a href="{{ route('admin.exams.questions', $exam) }}"
                       class="flex items-center gap-3 p-4 bg-white rounded-xl border border-blue-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-blue-900">Gestionar Preguntas</h4>
                            <p class="text-sm text-blue-700 mt-1">{{ $exam->questions_count ?? 0 }} preguntas</p>
                        </div>
                        <svg class="w-5 h-5 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- Ver Resultados -->
                    <a href="{{ route('admin.exams.results', $exam) }}"
                       class="flex items-center gap-3 p-4 bg-white rounded-xl border border-green-200 hover:border-green-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-green-100 to-green-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-green-900">Ver Resultados</h4>
                            <p class="text-sm text-green-700 mt-1">{{ $exam->attempts_count ?? 0 }} intentos</p>
                        </div>
                        <svg class="w-5 h-5 text-green-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- Vista Previa -->
                    <a href="#"
                       class="flex items-center gap-3 p-4 bg-white rounded-xl border border-purple-200 hover:border-purple-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-purple-900">Vista Previa</h4>
                            <p class="text-sm text-purple-700 mt-1">Ver como estudiante</p>
                        </div>
                        <svg class="w-5 h-5 text-purple-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- Duplicar Examen -->
                    <button onclick="duplicateExam({{ $exam->id }})"
                            class="w-full flex items-center gap-3 p-4 bg-white rounded-xl border border-orange-200 hover:border-orange-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-orange-900">Duplicar Examen</h4>
                            <p class="text-sm text-orange-700 mt-1">Crear copia con preguntas</p>
                        </div>
                    </button>

                    <!-- Eliminar Examen -->
                    <button onclick="deleteExam({{ $exam->id }})"
                            class="w-full flex items-center gap-3 p-4 bg-white rounded-xl border border-red-200 hover:border-red-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-red-100 to-red-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-red-900">Eliminar Examen</h4>
                            <p class="text-sm text-red-700 mt-1">Eliminar permanentemente</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Función para cambiar estado del examen
    async function toggleExamStatus(examId) {
        if (!confirm('¿Estás seguro de cambiar el estado del examen?')) {
            return;
        }

        try {
            const response = await axios.post(`/admin/exams/${examId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del examen actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    // Función para eliminar examen
    async function deleteExam(examId) {
        if (!confirm('¿Estás seguro de eliminar este examen? También se eliminarán todas las preguntas y resultados asociados.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/exams/${examId}`);
            if (response.data.success) {
                showNotification('Examen eliminado exitosamente', 'success');
                setTimeout(() => window.location.href = '{{ route('admin.exams.index') }}', 1000);
            }
        } catch (error) {
            console.error('Error al eliminar examen:', error);
            showNotification('Error al eliminar el examen', 'error');
        }
    }

    // Función para duplicar examen
    async function duplicateExam(examId) {
        if (!confirm('¿Deseas crear una copia de este examen con todas sus preguntas?')) {
            return;
        }

        try {
            const response = await axios.post(`/admin/exams/${examId}/duplicate`);
            if (response.data.success) {
                showNotification('Examen duplicado exitosamente', 'success');
                setTimeout(() => window.location.href = `/admin/exams/${response.data.new_exam_id}/edit`, 1500);
            }
        } catch (error) {
            console.error('Error al duplicar examen:', error);
            showNotification('Error al duplicar el examen', 'error');
        }
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
            type === 'success'
            ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
            : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
        }`;

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                    }
                </svg>
                <span class="font-medium">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('translate-y-0', 'opacity-100');
        }, 10);

        setTimeout(() => {
            notification.classList.remove('translate-y-0', 'opacity-100');
            notification.classList.add('-translate-y-2', 'opacity-0');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Inicializar vistas previas en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
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

    // Funciones para actualizar vistas previas (mismas que en create.blade.php)
    function updateDurationPreview() {
        const durationInput = document.getElementById('duration');
        const duration = parseInt(durationInput.value) || 60;

        const hours = Math.floor(duration / 60);
        const minutes = duration % 60;

        const hoursEl = document.getElementById('duration-hours');
        const minutesEl = document.getElementById('duration-minutes');
        const previewEl = document.getElementById('preview-duration');

        if (hoursEl) hoursEl.textContent = hours;
        if (minutesEl) minutesEl.textContent = minutes;
        if (previewEl) previewEl.textContent = duration;
    }

    function updatePassingScorePreview() {
        const scoreInput = document.getElementById('passing_score');
        const score = parseInt(scoreInput.value) || 70;

        const displayEl = document.getElementById('passing-score-display');
        const barEl = document.getElementById('passing-score-bar');
        const previewEl = document.getElementById('preview-passing-score');

        if (displayEl) displayEl.textContent = score + '%';
        if (barEl) barEl.style.width = score + '%';
        if (previewEl) previewEl.textContent = score + '%';
    }

    function updateAttemptsPreview() {
        const attemptsInput = document.getElementById('max_attempts');
        const attempts = parseInt(attemptsInput.value) || 3;

        const displayEl = document.getElementById('attempts-display');
        const previewEl = document.getElementById('preview-attempts');
        const unlimitedSpan = document.getElementById('unlimited-attempts');

        if (displayEl) displayEl.textContent = attempts === 0 ? 'Ilimitados' : attempts;
        if (previewEl) previewEl.textContent = attempts === 0 ? '∞' : attempts;

        if (unlimitedSpan) {
            if (attempts === 0) {
                unlimitedSpan.classList.remove('hidden');
            } else {
                unlimitedSpan.classList.add('hidden');
            }
        }
    }

    function updateStatusPreview() {
        const statusCheckbox = document.getElementById('is_active');
        const statusText = document.getElementById('status-text');
        const previewStatus = document.getElementById('preview-status');

        if (!statusCheckbox || !statusText || !previewStatus) return;

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
        if (!courseSelect) return;

        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
        const courseInfo = document.getElementById('selected-course-info');
        const generalCourseInfo = document.getElementById('course-info');

        if (selectedOption.value) {
            if (courseInfo) courseInfo.classList.remove('hidden');
            if (generalCourseInfo) generalCourseInfo.classList.remove('hidden');

            const titleEl = document.getElementById('selected-course-title');
            const categoryEl = document.getElementById('selected-course-category');
            const generalCategoryEl = document.getElementById('course-category');

            if (titleEl) titleEl.textContent = selectedOption.text;
            if (categoryEl) categoryEl.textContent = selectedOption.dataset.category || 'Sin categoría';
            if (generalCategoryEl) generalCategoryEl.textContent = selectedOption.dataset.category || 'Sin categoría';
        } else {
            if (courseInfo) courseInfo.classList.add('hidden');
            if (generalCourseInfo) generalCourseInfo.classList.add('hidden');
        }
    }
</script>
@endsection
