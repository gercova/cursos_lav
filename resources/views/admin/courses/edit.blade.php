@extends('layouts.admin')
@section('title', 'Editar Curso: ' . $course->title)
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    @if($course->image_url)
                        <img src="{{ Storage::url($course->image_url) }}" alt="{{ $course->title }}" class="w-12 h-12 rounded-xl object-cover border border-gray-300">
                    @endif
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Editar Curso</h1>
                        <p class="text-gray-600 mt-1">{{ $course->title }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        {{ $course->students_count }} estudiantes
                    </span>
                    @if($course->is_on_promotion)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                            En promoción
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 md:mt-0">
                <a href="{{ route('admin.courses.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
                <a href="{{ route('course.show', $course->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white hover:from-green-700 hover:to-green-800 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Vista previa
                </a>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <a href="#" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Información General
                </a>
                <a href="{{ route('admin.courses.sections.index', $course) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Secciones y Contenido
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Documentos
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Exámenes
                </a>
                <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Estudiantes
                </a>
            </nav>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.courses.update', $course) }}"
            method="POST"
            enctype="multipart/form-data"
            id="courseForm">
            @include('admin.courses.partials.form')
        </form>
    </div>

    <!-- Sección de estadísticas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Estadísticas del Curso</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800">Total Estudiantes</span>
                    <span class="text-2xl font-bold text-blue-900">{{ $course->students_count }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800">Total Secciones</span>
                    <span class="text-2xl font-bold text-blue-900">{{ $course->sections_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800">Fecha de Creación</span>
                    <span class="text-sm font-medium text-blue-900">{{ $course->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-green-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.courses.sections.index', $course) }}"
                   class="flex items-center gap-3 p-3 bg-white rounded-lg border border-green-200 hover:border-green-300 transition duration-200">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-900">Agregar Nueva Sección</span>
                </a>
                <a href="#"
                   class="flex items-center gap-3 p-3 bg-white rounded-lg border border-green-200 hover:border-green-300 transition duration-200">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-900">Subir Documentos</span>
                </a>
                <button onclick="toggleCourseStatus({{ $course->id }})"
                        class="w-full flex items-center gap-3 p-3 bg-white rounded-lg border border-green-200 hover:border-green-300 transition duration-200">
                    <svg class="w-5 h-5 {{ $course->is_active ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($course->is_active)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        @endif
                    </svg>
                    <span class="text-sm font-medium {{ $course->is_active ? 'text-red-900' : 'text-green-900' }}">
                        {{ $course->is_active ? 'Desactivar Curso' : 'Activar Curso' }}
                    </span>
                </button>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">Información de Precios</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-purple-800">Precio Regular</span>
                    <span class="text-xl font-bold text-purple-900">S/ {{ number_format($course->price, 2) }}</span>
                </div>
                @if($course->is_on_promotion)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-purple-800">Precio Promoción</span>
                        <span class="text-xl font-bold text-purple-900">S/ {{ number_format($course->promotion_price, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-purple-800">Descuento</span>
                        <span class="text-xl font-bold text-purple-900">
                            {{ number_format((($course->price - $course->promotion_price) / $course->price) * 100, 0) }}%
                        </span>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-4 border-t border-purple-200">
                    <span class="text-sm font-semibold text-purple-900">Precio Final</span>
                    <span class="text-2xl font-bold text-purple-900">S/ {{ number_format($course->final_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Función para cambiar estado del curso
    async function toggleCourseStatus(courseId) {
        if (!confirm('¿Estás seguro de cambiar el estado del curso?')) {
            return;
        }

        try {
            const response = await axios.post(`/admin/courses/${courseId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del curso actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
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
