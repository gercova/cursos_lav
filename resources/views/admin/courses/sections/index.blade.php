@extends('layouts.admin')
@section('title', 'Secciones: ' . $course->title)
@section('content')
<div class="container mx-auto px-4 py-6" x-data="sectionManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    @if($course->image_url)
                        <img src="{{ Storage::url($course->image_url) }}"
                             alt="{{ $course->title }}"
                             class="w-12 h-12 rounded-xl object-cover border border-gray-300">
                    @endif
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Secciones del Curso</h1>
                        <p class="text-gray-600 mt-1">{{ $course->title }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $sections->count() }} secciones
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        {{ $sections->sum('lessons_count') }} lecciones total
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 md:mt-0">
                <a href="{{ route('admin.courses.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Cursos
                </a>
                <a href="{{ route('admin.courses.sections.create', $course) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Sección
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
                    <h2 class="text-lg font-semibold text-gray-800">Gestión de Secciones</h2>
                    <p class="text-sm text-gray-600 mt-1">Organiza el contenido del curso en secciones y lecciones</p>
                </div>
            </div>
        </div>

        <!-- Lista de secciones -->
        <div class="divide-y divide-gray-100" id="sections-list">
            @forelse($sections as $section)
                <div class="p-6 hover:bg-gray-50 transition duration-200 group"
                     data-section-id="{{ $section->id }}">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <!-- Información de la sección -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 font-bold">
                                    {{ $section->order }}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $section->title }}</h3>
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $section->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>

                            @if($section->description)
                                <p class="text-gray-600 mb-3">{{ $section->description }}</p>
                            @endif

                            <!-- Estadísticas de la sección -->
                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    {{ $section->lessons_count }} lecciones
                                </div>
                                @if($section->mediafile)
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ ucfirst($section->media_type) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Lecciones de la sección -->
                            @if($section->lessons->count() > 0)
                                <div class="mt-4 pl-11 border-l-2 border-blue-200">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Lecciones:</h4>
                                    <div class="space-y-2">
                                        @foreach($section->lessons->sortBy('order') as $lesson)
                                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                                <span class="text-xs text-gray-500">{{ $lesson->order }}.</span>
                                                <span class="text-sm text-gray-700">{{ $lesson->title }}</span>
                                                <span class="text-xs text-gray-400 ml-auto">{{ $lesson->duration }} min</span>
                                                @if($lesson->is_free)
                                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Gratis</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Acciones -->
                        <div class="flex items-center gap-2">
                            <!-- Ver/Editar lecciones -->
                            <a href="{{ route('admin.courses.sections.lessons.index', [$course, $section]) }}"
                               class="p-2 text-indigo-600 hover:text-white hover:bg-gradient-to-r hover:from-indigo-500 hover:to-indigo-600 rounded-lg transition-all duration-200"
                               title="Gestionar lecciones">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </a>

                            <!-- Editar sección -->
                            <a href="{{ route('admin.courses.sections.edit', [$course, $section]) }}"
                               class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200"
                               title="Editar sección">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <!-- Eliminar sección -->
                            <button onclick="deleteSection({{ $course->id }}, {{ $section->id }})"
                                    class="p-2 text-red-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 rounded-lg transition-all duration-200"
                                    title="Eliminar sección">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay secciones aún</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando la primera sección para este curso.</p>
                    <a href="{{ route('admin.courses.sections.create', $course) }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Primera Sección
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function sectionManager() {
        return {
            init() {
                // Inicializar funcionalidad de arrastrar y soltar si es necesario
            }
        };
    }

    // Función para eliminar sección
    async function deleteSection(courseId, sectionId) {
        if (!confirm('¿Estás seguro de eliminar esta sección? También se eliminarán todas sus lecciones.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/courses/${courseId}/sections/${sectionId}`);
            if (response.data.success) {
                showNotification('Sección eliminada exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al eliminar sección:', error);
            showNotification('Error al eliminar la sección', 'error');
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
