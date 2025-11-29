@extends('layouts.app')

@section('title', $course->title . ' - Plataforma de Cursos')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section del Curso -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Información principal -->
                <div class="lg:col-span-2">
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm text-gray-500">
                            <li><a href="{{ route('cursos') }}" class="hover:text-blue-600 transition-colors duration-200">Cursos</a></li>
                            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                            <li><a href="/?category={{ $course->category->id }}" class="hover:text-blue-600 transition-colors duration-200">{{ $course->category->name }}</a></li>
                            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                            <li class="text-gray-900 font-medium truncate">{{ Str::limit($course->title, 40) }}</li>
                        </ol>
                    </nav>

                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>

                    <p class="text-lg text-gray-600 mb-6">{{ $course->description }}</p>

                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <div class="flex items-center">
                            <div class="flex items-center text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $course->rating ? 'fill-current' : 'fill-gray-300' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="ml-2 text-sm text-gray-600">{{ $course->rating }} ({{ $course->enrollments_count }} estudiantes)</span>
                        </div>

                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm">{{ $course->duration }} horas</span>
                        </div>

                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm">{{ $course->sections->sum('lessons_count') }} lecciones</span>
                        </div>
                    </div>

                    <!-- Instructor -->
                    <div class="flex items-center mb-6">
                        <img class="w-12 h-12 rounded-full mr-4" src="{{ $course->instructor->photo_url ?? asset('storage/photos/default-avatar.png') }}" alt="{{ $course->instructor->names }}">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $course->instructor->names }}</p>
                            <p class="text-sm text-gray-600">Instructor</p>
                        </div>
                    </div>
                </div>

                <!-- Card de compra -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 sticky top-24">
                        <div class="p-6">
                            <!-- Video preview o imagen del curso -->
                            <div class="mb-4 rounded-lg overflow-hidden bg-gray-200 aspect-video flex items-center justify-center">
                                @if($course->video_preview_url)
                                    <video class="w-full h-full object-cover" poster="{{ $course->cover_image ?? asset('storage/photos/course-placeholder.jpg') }}" controls>
                                        <source src="{{ $course->video_preview_url }}" type="video/mp4">
                                        Tu navegador no soporta el elemento video.
                                    </video>
                                @else
                                    <img src="{{ $course->cover_image ?? asset('storage/photos/course-placeholder.jpg') }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>

                            <div class="text-center mb-6">
                                <span class="text-3xl font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                @if($course->original_price > $course->price)
                                    <span class="ml-2 text-lg text-gray-500 line-through">S/ {{ number_format($course->original_price, 2) }}</span>
                                @endif
                            </div>

                            @if($isEnrolled)
                                <a href="{{ route('course.learn', $course->id) }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Continuar Curso
                                </a>
                            @else
                                <button onclick="addToCart({{ $course->id }})" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Agregar al Carrito
                                </button>
                            @endif

                            <button class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 mb-4">
                                Comprar Ahora
                            </button>

                            <div class="text-center text-sm text-gray-600">
                                Garantía de reembolso de 30 días
                            </div>
                        </div>

                        <!-- Características del curso -->
                        <div class="border-t border-gray-200 p-6">
                            <h4 class="font-semibold text-gray-900 mb-4">Este curso incluye:</h4>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $course->duration }} horas de video bajo demanda
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $course->sections->sum('lessons_count') }} lecciones
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Acceso de por vida
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Acceso en móvil y TV
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Certificado de finalización
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido del curso -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenido principal -->
            <div class="lg:col-span-2">
                <!-- Lo que aprenderás -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Lo que aprenderás</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($course->learning_outcomes ?? ['Contenido valioso', 'Habilidades prácticas', 'Conocimiento aplicable', 'Experiencia real'] as $outcome)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700">{{ $outcome }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Contenido del curso (Temario) -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Contenido del curso</h2>
                        <div class="text-sm text-gray-600">
                            {{ $course->sections->count() }} secciones • {{ $course->sections->sum('lessons_count') }} lecciones • {{ $course->duration }}h de duración total
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($course->sections as $section)
                            <div class="border border-gray-200 rounded-lg">
                                <button class="w-full flex justify-between items-center p-4 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 rounded-lg section-toggle">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $section->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $section->lessons_count }} lecciones • {{ $section->duration }} min
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div class="section-content hidden px-4 pb-4">
                                    <div class="space-y-2 mt-2">
                                        @foreach($section->lessons as $lesson)
                                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    </svg>
                                                    <span class="text-gray-700">{{ $lesson->title }}</span>
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $lesson->duration }} min</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Descripción -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Descripción</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($course->long_description ?? $course->description)) !!}
                    </div>
                </div>

                <!-- Documentos adjuntos -->
                @if($course->documents && $course->documents->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Recursos adicionales</h2>
                        <div class="space-y-3">
                            @foreach($course->documents as $document)
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-gray-700">{{ $document->title }}</span>
                                    </div>
                                    <a href="{{ Storage::url($document->file_path) }}" download class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors duration-200">
                                        Descargar
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar lateral -->
            <div class="lg:col-span-1">
                <!-- Instructor -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Instructor</h3>
                    <div class="flex items-start mb-4">
                        <img class="w-16 h-16 rounded-full mr-4" src="{{ $course->instructor->photo_url ?? asset('storage/photos/default-avatar.png') }}" alt="{{ $course->instructor->names }}">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $course->instructor->names }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $course->instructor->title ?? 'Instructor' }}</p>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $course->instructor->students_count ?? '0' }} estudiantes
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 mb-4">
                        {{ $course->instructor->bio ?? 'Instructor experimentado con pasión por la enseñanza.' }}
                    </p>
                    <button class="w-full text-center text-blue-600 hover:text-blue-700 font-medium text-sm transition-colors duration-200">
                        Ver más cursos de este instructor
                    </button>
                </div>

                <!-- Cursos relacionados -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Cursos relacionados</h3>
                    <div class="space-y-4">
                        <!-- Aquí podrías cargar cursos relacionados de la misma categoría -->
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-sm">Próximamente más cursos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle para las secciones del temario
    document.addEventListener('DOMContentLoaded', function() {
        const sectionToggles = document.querySelectorAll('.section-toggle');

        sectionToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const sectionContent = this.nextElementSibling;
                const icon = this.querySelector('svg');

                sectionContent.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            });
        });
    });

    // Función para agregar al carrito
    async function addToCart(courseId) {
        try {
            const response = await axios.post('/api/cart/add', {
                course_id: courseId
            });

            if (response.data.success) {
                // Mostrar notificación de éxito
                showNotification('Curso agregado al carrito', 'success');
                // Actualizar contador del carrito
                updateCartCount();
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            showNotification('Error al agregar al carrito', 'error');
        }
    }

    function showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remover después de 3 segundos
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection
