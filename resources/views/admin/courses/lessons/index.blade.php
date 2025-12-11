@extends('layouts.admin')

@section('title', 'Lecciones: ' . $section->title)

@section('content')
<div class="container mx-auto px-4 py-6" x-data="lessonManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-blue-100 to-blue-200">
                        <span class="font-bold text-blue-700">{{ $section->order }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Lecciones</h1>
                        <p class="text-gray-600 mt-1">{{ $section->title }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-sm text-gray-500">
                                Curso: <strong>{{ $course->title }}</strong>
                            </span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $section->is_active ? 'Sección activa' : 'Sección inactiva' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 md:mt-0">
                <a href="{{ route('admin.courses.sections.index', $course) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Secciones
                </a>
                <a href="{{ route('admin.courses.sections.lessons.create', [$course, $section]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Lección
                </a>
            </div>
        </div>
    </div>

    <!-- Panel principal -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Header del panel -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800">Gestión de Lecciones</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Organiza el contenido educativo de esta sección
                    </p>
                </div>

                <div class="text-sm text-gray-600">
                    Total: <span class="font-bold">{{ $lessons->count() }}</span> lecciones
                </div>
            </div>
        </div>

        <!-- Lista de lecciones -->
        <div class="divide-y divide-gray-100" id="lessons-list">
            @forelse($lessons as $lesson)
                <div class="p-6 hover:bg-gray-50 transition duration-200 group"
                     data-lesson-id="{{ $lesson->id }}">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <!-- Información de la lección -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-indigo-100 to-indigo-200 text-indigo-700 font-bold text-sm">
                                    {{ $lesson->order }}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $lesson->title }}</h3>

                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $lesson->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $lesson->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                    @if($lesson->is_free)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Gratis
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($lesson->description)
                                <p class="text-gray-600 mb-3">{{ $lesson->description }}</p>
                            @endif

                            <!-- Detalles de la lección -->
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                @if($lesson->video_url)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        Video incluido
                                    </div>
                                @endif

                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $lesson->duration }} minutos
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="flex items-center gap-2">
                            <!-- Vista previa -->
                            <a href="{{ $lesson->video_url }}"
                               target="_blank"
                               class="p-2 text-purple-600 hover:text-white hover:bg-gradient-to-r hover:from-purple-500 hover:to-purple-600 rounded-lg transition-all duration-200"
                               title="Ver video">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </a>

                            <!-- Editar lección -->
                            <a href="{{ route('admin.courses.sections.lessons.edit', [$course, $section, $lesson]) }}"
                               class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200"
                               title="Editar lección">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <!-- Cambiar estado -->
                            <button onclick="toggleLessonStatus({{ $course->id }}, {{ $section->id }}, {{ $lesson->id }})"
                                    class="p-2 {{ $lesson->is_active ? 'text-orange-600 hover:from-orange-500 hover:to-orange-600' : 'text-green-600 hover:from-green-500 hover:to-green-600' }} hover:text-white hover:bg-gradient-to-r rounded-lg transition-all duration-200"
                                    title="{{ $lesson->is_active ? 'Desactivar' : 'Activar' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($lesson->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    @endif
                                </svg>
                            </button>

                            <!-- Eliminar lección -->
                            <button onclick="deleteLesson({{ $course->id }}, {{ $section->id }}, {{ $lesson->id }})"
                                    class="p-2 text-red-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 rounded-lg transition-all duration-200"
                                    title="Eliminar lección">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Estado vacío -->
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay lecciones aún</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        Comienza creando la primera lección para esta sección.
                    </p>
                    <a href="{{ route('admin.courses.sections.lessons.create', [$course, $section]) }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Primera Lección
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function lessonManager() {
        return {
            init() {
                // Inicializar funcionalidad de arrastrar y soltar para reordenar
                this.initSortable();
            },

            initSortable() {
                // Implementar sortable.js si lo necesitas
            }
        };
    }

    // Función para cambiar estado de lección
    async function toggleLessonStatus(courseId, sectionId, lessonId) {
        try {
            const response = await axios.post(`/admin/courses/${courseId}/sections/${sectionId}/lessons/${lessonId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado de lección actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    // Función para eliminar lección
    async function deleteLesson(courseId, sectionId, lessonId) {
        if (!confirm('¿Estás seguro de eliminar esta lección?')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/courses/${courseId}/sections/${sectionId}/lessons/${lessonId}`);
            if (response.data.success) {
                showNotification('Lección eliminada exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al eliminar lección:', error);
            showNotification('Error al eliminar la lección', 'error');
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
</script>
@endsection
