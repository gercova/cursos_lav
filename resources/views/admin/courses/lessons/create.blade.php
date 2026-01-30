@extends('layouts.admin')
@section('title', 'Nueva Lección: '.$section->title)
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
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Nueva Lección</h1>
                        <p class="text-gray-600 mt-1">{{ $section->title }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-sm text-gray-500">
                                Curso: <strong>{{ $course->title }}</strong>
                            </span>
                            <span class="text-sm text-gray-500">•</span>
                            <span class="text-sm text-gray-500">
                                Sección {{ $section->order }} de {{ $course->sections()->count() }}
                            </span>
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
            </div>
        </div>

        <!-- Progreso del formulario -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Completa los campos requeridos</span>
                <span class="text-sm font-medium text-blue-600" id="progressPercentage">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300" id="progressBar" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form
            action="{{ route('admin.courses.sections.lessons.store', [$course, $section]) }}"
            method="post"
            id="lessonForm"
            oninput="updateProgress()"
            class="p-4"
        >
            @csrf
            <div class="space-y-6">
                <!-- Título y Orden -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Título de la Lección *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200" placeholder="Ej: Introducción al tema principal">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Orden en la sección *
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', $section->lessons()->count() + 1) }}" required min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Actualmente hay {{ $section->lessons()->count() }} lecciones en esta sección
                        </p>
                    </div>
                </div>
                <!-- Duración -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                        Duración (minutos) *
                    </label>
                    <div class="relative">
                        <input type="number" id="duration" name="duration" value="{{ old('duration', 15) }}" required min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200" placeholder="Duración en minutos">
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
                    <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200" placeholder="https://youtube.com/watch?v=... o URL de Vimeo">
                    @error('video_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Soporta YouTube, Vimeo, o enlaces directos a archivos .mp4
                    </p>
                </div>
                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción de la Lección
                    </label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200" placeholder="Describe el contenido de esta lección, objetivos de aprendizaje, temas cubiertos, etc.">{{ old('description') }}</textarea>
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
                                <input type="checkbox" id="is_free" name="is_free" {{ old('is_free') ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="is_free" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Lección gratuita</span>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Los usuarios podrán acceder a esta lección sin necesidad de estar inscritos en el curso
                                    </p>
                                </label>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Gratis
                            </span>
                        </div>

                        <!-- Estado activo -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Lección activa</span>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Los estudiantes podrán ver esta lección si está activa
                                    </p>
                                </label>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ old('is_active', true) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ old('is_active', true) ? 'Activa' : 'Inactiva' }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Vista previa de video -->
                <div id="video-preview" class="hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Vista Previa del Video</h3>
                    <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden border border-gray-300">
                        <iframe id="video-iframe" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <!-- Botones -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.courses.sections.lessons.index', [$course, $section]) }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200">
                        Crear Lección
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Resumen de la sección -->
    <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Resumen de la Sección</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-white rounded-lg border border-blue-100">
                <div class="text-sm text-blue-800">Total Lecciones</div>
                <div class="text-2xl font-bold text-blue-900">{{ $section->lessons()->count() }}</div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-blue-100">
                <div class="text-sm text-blue-800">Estado</div>
                <div class="text-lg font-bold {{ $section->is_active ? 'text-green-600' : 'text-red-600' }}">
                    {{ $section->is_active ? 'Activa' : 'Inactiva' }}
                </div>
            </div>
            <div class="p-4 bg-white rounded-lg border border-blue-100">
                <div class="text-sm text-blue-800">Orden</div>
                <div class="text-2xl font-bold text-blue-900">{{ $section->order }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Actualizar progreso del formulario
    function updateProgress() {
        const form      = document.getElementById('lessonForm');
        const inputs    = form.querySelectorAll('input[required], textarea[required]');
        let filled      = 0;

        inputs.forEach(input => {
            if (input.type === 'checkbox' && input.checked) {
                filled++;
            } else if (input.value.trim() !== '') {
                filled++;
            }
        });

        const percentage = Math.min(100, Math.round((filled / inputs.length) * 100));
        document.getElementById('progressBar').style.width = percentage + '%';
        document.getElementById('progressPercentage').textContent = percentage + '%';
    }

    // Vista previa de video en tiempo real
    const videoUrlInput = document.getElementById('video_url');
    const videoPreview  = document.getElementById('video-preview');
    const videoIframe   = document.getElementById('video-iframe');

    videoUrlInput.addEventListener('input', function() {
        const url = this.value.trim();

        if (!url) {
            videoPreview.classList.add('hidden');
            return;
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
            videoPreview.classList.remove('hidden');
            return;
        }

        videoIframe.src = embedUrl;
        videoPreview.classList.remove('hidden');
    });

    // Cambiar estado del checkbox de estado activo
    const isActiveCheckbox = document.getElementById('is_active');
    const isActiveLabel = isActiveCheckbox.closest('div').querySelector('.bg-green-100, .bg-red-100');

    isActiveCheckbox.addEventListener('change', function() {
        if (this.checked) {
            isActiveLabel.className     = 'px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
            isActiveLabel.textContent   = 'Activa';
        } else {
            isActiveLabel.className     = 'px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800';
            isActiveLabel.textContent   = 'Inactiva';
        }
    });

    // Inicializar progreso
    document.addEventListener('DOMContentLoaded', function() {
        updateProgress();

        // Mostrar vista previa si ya hay un URL
        if (videoUrlInput.value.trim()) {
            videoUrlInput.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection
