@extends('layouts.app')

@section('title', 'IPF Consultores - Inicio')
@section('content')
<!-- Hero Section con Carousel Full Width -->
<div class="relative w-full">
    <!-- Carousel Container Full Width -->
    <div class="relative h-96 sm:h-[500px] lg:h-[600px] xl:h-[700px] w-full overflow-hidden">
        <!-- Slide 1 -->
        <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100" id="slide-1">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <img src="https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                alt="Aprende desde cualquier lugar"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white px-4 w-full max-w-7xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 animate-fade-in">
                        Aprende Sin Límites
                    </h1>
                    <p class="text-xl sm:text-2xl lg:text-3xl mb-8 animate-slide-up">
                        Cursos profesionales desde la comodidad de tu hogar
                    </p>
                    <a href="#cursos" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 animate-pulse inline-block">
                        Explorar Cursos
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="slide-2">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/70 to-purple-900/70"></div>
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80"
                 alt="Cursos profesionales"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white px-4 w-full max-w-7xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4">
                        Educación de Calidad
                    </h1>
                    <p class="text-xl sm:text-2xl lg:text-3xl mb-8">
                        Impartida por expertos en la industria
                    </p>
                    <a href="#cursos" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-block">
                        Ver Catálogo
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="slide-3">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-900/70 to-pink-900/70"></div>
            <img src="https://images.unsplash.com/photo-1535223289827-42f1e9919769?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                 alt="Certificaciones reconocidas"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white px-4 w-full max-w-7xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4">
                        Certificaciones
                    </h1>
                    <p class="text-xl sm:text-2xl lg:text-3xl mb-8">
                        Obtén certificados válidos y verificables
                    </p>
                    <a href="#cursos" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-block">
                        Obtener Certificado
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 4 -->
        <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="slide-4">
            <div class="absolute inset-0 bg-gradient-to-r from-orange-900/70 to-red-900/70"></div>
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                 alt="Comunidad de aprendizaje"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white px-4 w-full max-w-7xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4">
                        Comunidad Activa
                    </h1>
                    <p class="text-xl sm:text-2xl lg:text-3xl mb-8">
                        Conecta con otros estudiantes y profesionales
                    </p>
                    <a href="{{ route('register') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-block">
                        Unirse Ahora
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 5 -->
        <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="slide-5">
            <div class="absolute inset-0 bg-gradient-to-r from-teal-900/70 to-blue-900/70"></div>
            <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                 alt="Flexibilidad de horarios"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center text-white px-4 w-full max-w-7xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4">
                        A Tu Ritmo
                    </h1>
                    <p class="text-xl sm:text-2xl lg:text-3xl mb-8">
                        Estudia cuando quieras, donde quieras
                    </p>
                    <a href="#cursos" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-block">
                        Comenzar Hoy
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <button id="prev-slide" class="absolute left-4 sm:left-6 lg:left-8 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white p-3 sm:p-4 rounded-full transition-all duration-300 backdrop-blur-sm z-20">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button id="next-slide" class="absolute right-4 sm:right-6 lg:right-8 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white p-3 sm:p-4 rounded-full transition-all duration-300 backdrop-blur-sm z-20">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-4 sm:bottom-6 lg:bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2 sm:space-x-3 z-20">
            <button class="carousel-indicator w-3 h-3 sm:w-4 sm:h-4 bg-white rounded-full transition-all duration-300 opacity-100 hover:opacity-100" data-slide="0"></button>
            <button class="carousel-indicator w-3 h-3 sm:w-4 sm:h-4 bg-white rounded-full transition-all duration-300 opacity-50 hover:opacity-100" data-slide="1"></button>
            <button class="carousel-indicator w-3 h-3 sm:w-4 sm:h-4 bg-white rounded-full transition-all duration-300 opacity-50 hover:opacity-100" data-slide="2"></button>
            <button class="carousel-indicator w-3 h-3 sm:w-4 sm:h-4 bg-white rounded-full transition-all duration-300 opacity-50 hover:opacity-100" data-slide="3"></button>
            <button class="carousel-indicator w-3 h-3 sm:w-4 sm:h-4 bg-white rounded-full transition-all duration-300 opacity-50 hover:opacity-100" data-slide="4"></button>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="bg-white py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
            <div class="p-4">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-600 mb-2">500+</div>
                <div class="text-gray-600 text-sm sm:text-base">Cursos Disponibles</div>
            </div>
            <div class="p-4">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-green-600 mb-2">10,000+</div>
                <div class="text-gray-600 text-sm sm:text-base">Estudiantes Activos</div>
            </div>
            <div class="p-4">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-purple-600 mb-2">50+</div>
                <div class="text-gray-600 text-sm sm:text-base">Instructores Expertos</div>
            </div>
            <div class="p-4">
                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-orange-600 mb-2">98%</div>
                <div class="text-gray-600 text-sm sm:text-base">Satisfacción</div>
            </div>
        </div>
    </div>
</div>
<!-- Cursos Destacados Section -->
<div id="cursos" class="py-12 sm:py-16 lg:py-0 mb-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Cursos Destacados</h2>
            <p class="text-lg sm:text-xl text-gray-600">Los cursos más populares entre nuestros estudiantes</p>
        </div>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-3 sm:gap-4 mb-8 justify-center">
            <select id="category-filter" class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <select id="sort-filter" class="px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 text-sm sm:text-base">
                <option value="newest">Más recientes</option>
                <option value="popular">Más populares</option>
                <option value="price_low">Precio: menor a mayor</option>
                <option value="price_high">Precio: mayor a menor</option>
            </select>
        </div>

        <!-- Grid de Cursos -->
        <div id="courses-container">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
                @foreach($courses as $course)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-gray-100">
                        <div class="relative">
                            <img src="{{ $course->image_url ? Storage::url($course->image_url) : 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80' }}"
                                alt="{{ $course->title }}"
                                class="w-full h-48 object-cover">
                            @if($course->promotion_price)
                                <span class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                    -{{ number_format((($course->price - $course->promotion_price) / $course->price) * 100, 0) }}%
                                </span>
                            @endif
                            <span class="absolute bottom-3 left-3 bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium shadow-lg">
                                {{ $course->category->name }}
                            </span>
                        </div>

                        <div class="p-6">
                            <h3 class="font-bold text-lg mb-2 text-gray-900 line-clamp-2 hover:text-blue-600 transition-colors duration-200">
                                <a href="{{ route('course.show', $course->id) }}">{{ $course->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->short_description ?: $course->description }}</p>

                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-sm text-gray-600 ml-1">4.8</span>
                                    </div>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-sm text-gray-600">{{ $course->students_count ?? 125 }} estudiantes</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    @if($course->promotion_price)
                                        <span class="text-xl font-bold text-gray-900">S/ {{ number_format($course->promotion_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through">S/ {{ number_format($course->price, 2) }}</span>
                                    @else
                                        <span class="text-xl font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                    @endif
                                </div>
                                <button onclick="addToCart({{ $course->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                    Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($courses->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="bg-white px-4 py-3 rounded-lg shadow-lg">
                        {{ $courses->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="bg-gray-900 text-white py-12 sm:py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4">¿Por qué elegirnos?</h2>
            <p class="text-lg sm:text-xl text-gray-300 max-w-3xl mx-auto">La mejor experiencia de aprendizaje online con resultados comprobados</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="text-center p-6 sm:p-8">
                <div class="bg-blue-600 w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-4">Acceso Ilimitado</h3>
                <p class="text-gray-300 text-sm sm:text-base">Aprende a tu propio ritmo con acceso 24/7 a todos los cursos desde cualquier dispositivo</p>
            </div>

            <div class="text-center p-6 sm:p-8">
                <div class="bg-green-600 w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-4">Certificados Válidos</h3>
                <p class="text-gray-300 text-sm sm:text-base">Obtén certificados verificables con código QR que respaldan tus conocimientos</p>
            </div>

            <div class="text-center p-6 sm:p-8">
                <div class="bg-purple-600 w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-4">Soporte 24/7</h3>
                <p class="text-gray-300 text-sm sm:text-base">Nuestro equipo de expertos está siempre disponible para resolver tus dudas</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-blue-600 py-12 sm:py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-4">¿Listo para comenzar?</h2>
        <p class="text-lg sm:text-xl text-blue-100 mb-8 max-w-3xl mx-auto">Únete a miles de estudiantes que ya están transformando sus carreras y alcanzando sus metas profesionales</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-base sm:text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg inline-block">
                Crear Cuenta Gratis
            </a>
            <a href="#cursos" class="border-2 border-white text-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-base sm:text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:bg-white hover:text-blue-600 inline-block">
                Explorar Cursos
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/home.js') }}"></script>
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }

    .animate-slide-up {
        animation: slide-up 1s ease-out 0.3s both;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    /* Mejoras para el carousel full width */
    .carousel-indicator {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .carousel-indicator:hover {
        opacity: 0.8 !important;
        transform: scale(1.1);
    }

    /* Asegurar que las imágenes ocupen todo el ancho */
    .carousel-image {
        width: 100vw;
        height: 100%;
        object-fit: cover;
    }

    /* Responsive improvements */
    @media (max-width: 640px) {
        .carousel-content h1 {
            font-size: 2rem;
        }
        .carousel-content p {
            font-size: 1.125rem;
        }
    }
</style>
@endsection
