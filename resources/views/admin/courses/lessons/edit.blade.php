@extends('layouts.admin')

@section('title', 'Editar Lección: ' . $lesson->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-blue-100 to-blue-200">
                        <span class="font-bold text-blue-700">{{ $section->order }}</span>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Editar Lección</h1>
                        <p class="text-gray-600 mt-1">{{ $lesson->title }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-sm text-gray-500">
                                Curso: <strong>{{ $course->title }}</strong>
                            </span>
                            <span class="text-sm text-gray-500">•</span>
                            <span class="text-sm text-gray-500">
                                Sección {{ $section->order }}: {{ $section->title }}
                            </span>
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
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4 md:mt-0">
                <a href="{{ route('admin.courses.sections.lessons.index', [$course, $section]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Lecciones
                </a>
                @if($lesson->video_url)
                    <a href="{{ $lesson->video_url }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white hover:from-purple-700 hover:to-purple-800 rounded-xl font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Ver Video
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.courses.sections.lessons.update', [$course, $section, $lesson]) }}"
              method="POST"
              id="lessonForm"
              class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Título y Orden -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Título de la Lección *
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title', $lesson->title) }}"
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Orden en la sección *
                        </label>
                        <input type="number"
                               id="order"
                               name="order"
                               value="{{ old('order', $lesson->order) }}"
                               required min="1"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Posición actual: {{ $lesson->order }} de {{ $section->lessons()->count() }} lecciones
                        </p>
                    </div>
                </div>

                <!-- Duración -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                        Duración (minutos) *
                    </label>
                    <div class="relative">
                        <input type="number"
                               id="duration"
                               name="duration"
                               value="{{ old('duration', $lesson->duration) }}"
                               required min="1"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            minutos
                        </div>
                    </div>
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URL del video -->
                <div>
                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">
                        URL del Video (Opcional)
                    </label>
                    <input type="url"
                           id="video_url"
                           name="video_url"
                           value="{{ old('video_url', $lesson->video_url) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                           placeholder="https://youtube.com/watch?v=... o URL de Vimeo">
                    @error('video_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if($lesson->video_url)
                        <div class="mt-2">
                            <div class="flex items-center gap-2 text-sm text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Video configurado correctamente
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Vista previa del video actual -->
                @if($lesson->video_url)
                    <div id="current-video-preview" class="border border-gray-200 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Video Actual</h3>
                        <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden border border-gray-300">
                            @if(str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be'))
                                @php
                                    // Extraer ID de YouTube
                                    $videoId = '';
                                    if (str_contains($lesson->video_url, 'youtube.com/watch?v=')) {
                                        $videoId = explode('v=', $lesson->video_url)[1];
                                        $ampersandPosition = strpos($videoId, '&');
                                        if ($ampersandPosition !== false) {
                                            $videoId = substr($videoId, 0, $ampersandPosition);
                                        }
                                    } elseif (str_contains($lesson->video_url, 'youtu.be/')) {
                                        $videoId = explode('youtu.be/', $lesson->video_url)[1];
                                    }
                                @endphp
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                        class="w-full h-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            @elseif(str_contains($lesson->video_url, 'vimeo.com'))
                                @php
                                    $videoId = explode('vimeo.com/', $lesson->video_url)[1];
                                @endphp
                                <iframe src="https://player.vimeo.com/video/{{ $videoId }}"
                                        class="w-full h-full"
                                        frameborder="0"
                                        allow="autoplay; fullscreen; picture-in-picture"
                                        allowfullscreen>
                                </iframe>
                            @elseif(str_ends_with($lesson->video_url, '.mp4'))
                                <video controls class="w-full h-full">
                                    <source src="{{ $lesson->video_url }}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-100">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Formato de video no soportado para vista previa</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Nueva vista previa de video -->
                <div id="new-video-preview" class="hidden border border-gray-200 rounded-xl p-4 bg-blue-50">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Nueva Vista Previa del Video</h3>
                    <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden border border-gray-300">
                        <iframe id="video-iframe"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción de la Lección
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                              placeholder="Describe el contenido de esta lección, objetivos de aprendizaje, temas cubiertos, etc.">{{ old('description', $lesson->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opciones avanzadas -->
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Opciones Avanzadas</h3>

                    <div class="space-y-4">
                        <!-- Lección gratis -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="is_free"
                                       name="is_free"
                                       {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="is_free" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Lección gratuita</span>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Los usuarios podrán acceder a esta lección sin necesidad de estar inscritos en el curso
                                    </p>
                                </label>
                            </div>
                            <span id="is-free-label" class="px-2 py-1 rounded-full text-xs font-medium {{ $lesson->is_free ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $lesson->is_free ? 'Gratis' : 'Premium' }}
                            </span>
                        </div>

                        <!-- Estado activo -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       {{ old('is_active', $lesson->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Lección activa</span>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Los estudiantes podrán ver esta lección si está activa
                                    </p>
                                </label>
                            </div>
                            <span id="is-active-label" class="px-2 py-1 rounded-full text-xs font-medium {{ $lesson->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $lesson->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas de la lección -->
                <div class="border border-gray-200 rounded-xl p-4 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Estadísticas de la Lección</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-3 bg-white rounded-lg border border-blue-100 text-center">
                            <div class="text-xs text-blue-800 mb-1">Orden Actual</div>
                            <div class="text-xl font-bold text-blue-900">{{ $lesson->order }}</div>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-blue-100 text-center">
                            <div class="text-xs text-blue-800 mb-1">Duración</div>
                            <div class="text-xl font-bold text-blue-900">{{ $lesson->duration }} min</div>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-blue-100 text-center">
                            <div class="text-xs text-blue-800 mb-1">Estado</div>
                            <div class="text-lg font-bold {{ $lesson->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $lesson->is_active ? 'Activa' : 'Inactiva' }}
                            </div>
                        </div>
                        <div class="p-3 bg-white rounded-lg border border-blue-100 text-center">
                            <div class="text-xs text-blue-800 mb-1">Acceso</div>
                            <div class="text-lg font-bold {{ $lesson->is_free ? 'text-green-600' : 'text-purple-600' }}">
                                {{ $lesson->is_free ? 'Gratis' : 'Premium' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div>
                        <button type="button"
                                onclick="deleteLesson({{ $course->id }}, {{ $section->id }}, {{ $lesson->id }})"
                                class="px-4 py-2.5 border border-red-300 text-red-700 hover:bg-red-50 rounded-xl font-medium transition duration-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Lección
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.courses.sections.lessons.index', [$course, $section]) }}"
                           class="px-6 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200">
                            Actualizar Lección
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Vista previa de video en tiempo real
    const videoUrlInput = document.getElementById('video_url');
    const newVideoPreview = document.getElementById('new-video-preview');
    const videoIframe = document.getElementById('video-iframe');
    const currentVideoPreview = document.getElementById('current-video-preview');

    videoUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        const originalUrl = "{{ old('video_url', $lesson->video_url) }}";

        if (!url || url === originalUrl) {
            newVideoPreview.classList.add('hidden');
            if (currentVideoPreview) {
                currentVideoPreview.classList.remove('hidden');
            }
            return;
        }

        // Ocultar vista previa actual si existe
        if (currentVideoPreview) {
            currentVideoPreview.classList.add('hidden');
        }

        // Convertir URL de YouTube a embed
        let embedUrl = url;

        // YouTube
        if (url.includes('youtube.com/watch?v=')) {
            const videoId = url.split('v=')[1];
            const ampersandPosition = videoId.indexOf('&');
            if (ampersandPosition !== -1) {
                embedUrl = `https://www.youtube.com/embed/${videoId.substring(0, ampersandPosition)}`;
            } else {
                embedUrl = `https://www.youtube.com/embed/${videoId}`;
            }
        }
        // YouTube short URL
        else if (url.includes('youtu.be/')) {
            const videoId = url.split('youtu.be/')[1];
            embedUrl = `https://www.youtube.com/embed/${videoId}`;
        }
        // Vimeo
        else if (url.includes('vimeo.com/')) {
            const videoId = url.split('vimeo.com/')[1];
            embedUrl = `https://player.vimeo.com/video/${videoId}`;
        }
        // MP4 directo
        else if (url.endsWith('.mp4')) {
            videoIframe.src = url;
            newVideoPreview.classList.remove('hidden');
            return;
        }

        videoIframe.src = embedUrl;
        newVideoPreview.classList.remove('hidden');
    });

    // Cambiar estado del checkbox de lección gratis
    const isFreeCheckbox = document.getElementById('is_free');
    const isFreeLabel = document.getElementById('is-free-label');

    isFreeCheckbox.addEventListener('change', function() {
        if (this.checked) {
            isFreeLabel.className = 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
            isFreeLabel.textContent = 'Gratis';
        } else {
            isFreeLabel.className = 'px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
            isFreeLabel.textContent = 'Premium';
        }
    });

    // Cambiar estado del checkbox de estado activo
    const isActiveCheckbox = document.getElementById('is_active');
    const isActiveLabel = document.getElementById('is-active-label');

    isActiveCheckbox.addEventListener('change', function() {
        if (this.checked) {
            isActiveLabel.className = 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
            isActiveLabel.textContent = 'Activa';
        } else {
            isActiveLabel.className = 'px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
            isActiveLabel.textContent = 'Inactiva';
        }
    });

    // Función para eliminar lección
    async function deleteLesson(courseId, sectionId, lessonId) {
        if (!confirm('¿Estás seguro de eliminar esta lección? Esta acción no se puede deshacer.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/courses/${courseId}/sections/${sectionId}/lessons/${lessonId}`);
            if (response.data.success) {
                showNotification('Lección eliminada exitosamente', 'success');
                setTimeout(() => {
                    window.location.href = `/admin/courses/${courseId}/sections/${sectionId}/lessons`;
                }, 1000);
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

    // Inicializar
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar nueva vista previa si el URL ha cambiado
        const currentUrl = "{{ old('video_url', $lesson->video_url) }}";
        const inputUrl = videoUrlInput.value.trim();

        if (inputUrl && inputUrl !== currentUrl) {
            videoUrlInput.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection
