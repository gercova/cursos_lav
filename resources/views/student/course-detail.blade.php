@extends('layouts.app')
@section('title', $course->title . ' - Plataforma de Cursos')
@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section del Curso -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna principal - Información del curso -->
                <div class="lg:col-span-2">
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-sm text-gray-500">
                            <li><a href="{{ route('cursos') }}" class="hover:text-blue-600 transition-colors duration-200">Cursos</a></li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('cursos') }}?category={{ $course->category->id }}" class="hover:text-blue-600 transition-colors duration-200">{{ $course->category->name }}</a>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-900 font-medium">{{ $course->title }}</span>
                            </li>
                        </ol>
                    </nav>

                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>

                    <p class="text-lg text-gray-600 mb-6">{{ $course->description }}</p>

                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-semibold">4.8</span>
                            <span class="mx-1">•</span>
                            <span>125 reseñas</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $course->duration }} horas</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                            <span>{{ $course->level }}</span>
                        </div>
                    </div>

                    <div class="flex items-center mb-6">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ $course->instructor->photo ? asset('storage/' . $course->instructor->photo) : asset('storage/instructors/instructor-default.png') }}" alt="{{ $course->instructor->names }}">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Instructor: {{ $course->instructor->names }}</p>
                                <p class="text-sm text-gray-600">{{ $course->instructor->profession ?? 'Instructor' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Card de inscripción -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 sticky top-24">
                        <!-- Collage de imágenes del curso -->
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <div class="col-span-2">
                                    <img src="{{ $course->image_url ? Storage::url($course->image_url) : 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80' }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded-lg">
                                </div>
                            </div>

                            <div class="text-center mb-4">
                                <span class="text-3xl font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                @if($course->original_price > $course->price)
                                    <span class="text-lg text-gray-500 line-through ml-2">S/ {{ number_format($course->original_price, 2) }}</span>
                                    <span class="ml-2 bg-red-100 text-red-800 text-sm font-medium px-2 py-1 rounded">
                                        {{ round(($course->original_price - $course->price) / $course->original_price * 100) }}% OFF
                                    </span>
                                @endif
                            </div>

                            @if($isEnrolled)
                                <a href="{{ route('learning', $course->id) }}"
                                   class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Continuar Curso
                                </a>
                            @else
                                <button onclick="addToCart({{ $course->id }})"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Agregar al Carrito
                                </button>
                            @endif

                            <button class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 mb-4">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                Agregar a Favoritos
                            </button>

                            <div class="text-center text-sm text-gray-600">
                                <p>✅ Garantía de devolución de 30 días</p>
                                <p>🔄 Acceso de por vida</p>
                                <p>📱 Acceso en dispositivos móviles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido detallado del curso -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenido principal -->
            <div class="lg:col-span-2">
                <!-- Lo que aprenderás -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Lo que aprenderás</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @if($course->what_you_learn && is_array($course->what_you_learn) && count($course->what_you_learn) > 0)
                            @foreach($course->what_you_learn as $outcome)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-700">{{ $outcome }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600">No se han especificado los objetivos de aprendizaje para este curso.</p>
                        @endif
                    </div>
                </div>

                <!-- Contenido del curso -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Contenido del curso</h2>
                    <div class="space-y-4">
                        @foreach($course->sections as $section)
                            <div class="border border-gray-200 rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                    <h3 class="font-semibold text-gray-900">{{ $section->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $section->lessons->count() }} lecciones • {{ $section->duration }} min</p>
                                </div>
                                <div class="divide-y divide-gray-200">
                                    @foreach($section->lessons as $lesson)
                                        <div class="px-4 py-3 flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-gray-700">{{ $lesson->title }}</span>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $lesson->duration }} min</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Descripción completa -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Descripción</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! $course->full_description ?? $course->description !!}
                    </div>
                </div>

                <!-- Requisitos -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Requisitos</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        @if($course->requirements && is_array($course->requirements) && count($course->requirements) > 0)
                            @foreach($course->requirements as $requirement)
                                <li>{{ $requirement }}</li>
                            @endforeach
                        @else
                            <li class="text-gray-600">No hay requisitos específicos para este curso.</li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Sidebar información adicional -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Características del curso -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Este curso incluye</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $course->sections->sum(fn($section) => $section->lessons->count()) }} horas de video bajo demanda
                        </li>
                        <li class="flex items-center text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $course->documents->count() }} recursos descargables
                        </li>
                        <li class="flex items-center text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Acceso de por vida
                        </li>
                        <li class="flex items-center text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Acceso en dispositivos móviles y TV
                        </li>
                        <li class="flex items-center text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Certificado de finalización
                        </li>
                    </ul>
                </div>

                <!-- Instructor -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructor</h3>
                    <div class="flex items-start space-x-4">
                        <img class="h-16 w-16 rounded-full object-cover"
                            src="{{ $course->instructor->photo ? asset('storage/' . $course->instructor->photo) : asset('storage/instructors/instructor-default.png') }}"
                            alt="{{ $course->instructor->names }}">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $course->instructor->names }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $course->instructor->profession ?? 'Instructor' }}</p>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span>4.8 • 2,548 estudiantes</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 mt-3">{{ $course->instructor->bio ?? 'Instructor experimentado en su campo.' }}</p>
                </div>

                <!-- Carousel de imágenes del curso -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Galería del Curso</h3>
                    <div x-data="courseCarousel()" class="relative">
                        <!-- Contenedor del carousel -->
                        <div class="overflow-hidden rounded-lg">
                            <div class="flex transition-transform duration-300 ease-in-out"
                                :style="`transform: translateX(-${currentIndex * 100}%)`">
                                <!-- Imagen principal del curso -->
                                <div class="w-full flex-shrink-0">
                                    <img src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : asset('storage/photos/default-course.jpg') }}"
                                        alt="{{ $course->title }}"
                                        class="w-full h-48 object-cover rounded-lg">
                                </div>

                                <!-- Imágenes adicionales del curso -->
                                @if($course->documents->where('type', 'image')->count() > 0)
                                    @foreach($course->documents->where('type', 'image') as $image)
                                        <div class="w-full flex-shrink-0">
                                            <img src="{{ asset('storage/' . $image->file_path) }}"
                                                alt="Imagen del curso - {{ $loop->iteration }}"
                                                class="w-full h-48 object-cover rounded-lg">
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Placeholders si no hay imágenes adicionales -->
                                    @foreach([1, 2, 3] as $placeholder)
                                        <div class="w-full flex-shrink-0">
                                            <img src="{{ asset('storage/photos/course-placeholder-' . $placeholder . '.jpg') }}"
                                                alt="Imagen del curso - {{ $placeholder }}"
                                                class="w-full h-48 object-cover rounded-lg bg-gray-200">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Controles de navegación -->
                        <template x-if="totalSlides > 1">
                            <div>
                                <!-- Botones anterior/siguiente -->
                                <button @click="prevSlide()"
                                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button @click="nextSlide()"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>

                                <!-- Indicadores -->
                                <div class="flex justify-center space-x-2 mt-4">
                                    <template x-for="i in totalSlides" :key="i">
                                        <button @click="goToSlide(i - 1)"
                                            class="w-3 h-3 rounded-full transition-all duration-200"
                                            :class="i - 1 === currentIndex ? 'bg-blue-600' : 'bg-gray-300 hover:bg-gray-400'">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Mensaje si solo hay una imagen -->
                        <template x-if="totalSlides <= 1">
                            <p class="text-center text-sm text-gray-500 mt-2">Explora la imagen del curso</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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

    function showNotification(message, type) {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-transform duration-300 translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Remover después de 3 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Carousel functionality
    function courseCarousel() {
        return {
            currentIndex: 0,
            totalSlides: {{ $course->documents->where('type', 'image')->count() + 1 }}, // +1 for cover image
            nextSlide() {
                this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
            },
            prevSlide() {
                this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
            },
            goToSlide(index) {
                this.currentIndex = index;
            },
            init() {
                // Auto-play opcional (descomenta si quieres auto-play)
                // setInterval(() => {
                //     this.nextSlide();
                // }, 5000);
            }
        }
    }

    // Inicializar tooltips si es necesario
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar cualquier inicialización adicional que necesites
    });
</script>
@endsection
