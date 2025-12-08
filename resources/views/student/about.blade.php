@extends('layouts.app')
@section('title', 'IPF Consultores - Nosotros')
@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-blue-900 to-purple-900 py-20 sm:py-24 lg:py-32">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 animate-fade-in">
            Sobre Nosotros
        </h1>
        <p class="text-xl sm:text-2xl text-blue-100 max-w-3xl mx-auto animate-slide-up">
            Transformando la educación online con pasión, innovación y compromiso
        </p>
    </div>
</div>

<!-- Descripción de la Empresa -->
<section class="py-16 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    Nuestra <span class="text-blue-600">Historia</span>
                </h2>
                <div class="space-y-4 text-lg text-gray-600">
                    <p>
                        Fundada en 2020, <span class="font-semibold text-gray-900">EduPlatform</span> nació con la visión de democratizar la educación de calidad y hacerla accesible para todos. Lo que comenzó como un pequeño proyecto educativo se ha convertido en una de las plataformas de aprendizaje online más reconocidas de Latinoamérica.
                    </p>
                    <p>
                        Nuestro equipo está compuesto por <span class="font-semibold text-gray-900">educadores apasionados, desarrolladores innovadores y profesionales de la industria</span> que comparten un objetivo común: crear experiencias de aprendizaje transformadoras que empoderen a nuestros estudiantes.
                    </p>
                    <p>
                        Hoy, contamos con una comunidad de más de <span class="font-semibold text-gray-900">10,000 estudiantes activos</span> y hemos entregado más de <span class="font-semibold text-gray-900">5,000 certificados</span> que han ayudado a nuestros graduados a avanzar en sus carreras profesionales.
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl sm:text-3xl font-bold text-blue-600 mb-2">3+</div>
                        <div class="text-sm text-gray-600">Años de Experiencia</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl sm:text-3xl font-bold text-green-600 mb-2">50+</div>
                        <div class="text-sm text-gray-600">Instructores Expertos</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl sm:text-3xl font-bold text-purple-600 mb-2">500+</div>
                        <div class="text-sm text-gray-600">Cursos Creados</div>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <div class="text-2xl sm:text-3xl font-bold text-orange-600 mb-2">10K+</div>
                        <div class="text-sm text-gray-600">Estudiantes</div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="bg-blue-600 rounded-2xl p-8 text-white transform rotate-2 hover:rotate-0 transition-transform duration-500">
                    <div class="bg-white rounded-xl p-6 transform -rotate-2">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Nuestros Valores</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Calidad</h4>
                                    <p class="text-gray-600 text-sm">Nos comprometemos con la excelencia en cada curso que creamos.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-green-100 p-2 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Comunidad</h4>
                                    <p class="text-gray-600 text-sm">Fomentamos un ambiente de colaboración y apoyo mutuo.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-purple-100 p-2 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Innovación</h4>
                                    <p class="text-gray-600 text-sm">Siempre buscamos nuevas formas de mejorar el aprendizaje.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Misión y Visión -->
<section class="py-16 sm:py-20 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            <!-- Misión -->
            <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 transform hover:scale-105 transition-transform duration-300">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestra Misión</h2>
                </div>
                <div class="space-y-4 text-lg text-gray-600">
                    <p>
                        <span class="font-semibold text-gray-900">Democratizar el acceso a educación de calidad</span> mediante plataformas tecnológicas innovadoras que rompan las barreras geográficas, económicas y temporales.
                    </p>
                    <p>
                        Buscamos empoderar a individuos y organizaciones a través del aprendizaje continuo, proporcionando herramientas y conocimientos que sean <span class="font-semibold text-gray-900">prácticos, relevantes y aplicables</span> en el mundo real.
                    </p>
                    <p>
                        Nos esforzamos por crear un impacto positivo en la sociedad formando profesionales capacitados, emprendedores exitosos y ciudadanos conscientes que contribuyan al desarrollo sostenible de sus comunidades.
                    </p>
                </div>
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-600">
                    <p class="text-blue-800 font-semibold italic">
                        "Creemos que la educación es el motor más poderoso para transformar vidas y construir un futuro mejor para todos."
                    </p>
                </div>
            </div>

            <!-- Visión -->
            <div class="bg-white rounded-2xl shadow-xl p-8 sm:p-10 transform hover:scale-105 transition-transform duration-300">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Nuestra Visión</h2>
                </div>
                <div class="space-y-4 text-lg text-gray-600">
                    <p>
                        Aspiramos a ser <span class="font-semibold text-gray-900">la plataforma de educación online líder en Latinoamérica</span>, reconocida por nuestra excelencia académica, innovación tecnológica y impacto social positivo.
                    </p>
                    <p>
                        Visualizamos un futuro donde cualquier persona, sin importar su ubicación o recursos, pueda acceder a educación de clase mundial y desarrollar las habilidades necesarias para <span class="font-semibold text-gray-900">triunfar en la economía digital global</span>.
                    </p>
                    <p>
                        Nos proyectamos como el socio estratégico preferido para instituciones educativas, empresas y gobiernos que buscan transformar sus programas de capacitación y desarrollo profesional mediante soluciones de aprendizaje digital efectivas y escalables.
                    </p>
                </div>
                <div class="mt-8 p-4 bg-green-50 rounded-lg border-l-4 border-green-600">
                    <p class="text-green-800 font-semibold italic">
                        "Soñamos con un mundo donde el aprendizaje continuo sea accesible para todos y cada persona pueda alcanzar su máximo potencial."
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Equipo de Liderazgo -->
<section class="py-16 sm:py-20 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                Nuestro <span class="text-blue-600">Equipo</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Conoce a las mentes brillantes detrás de nuestra plataforma
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Miembro 1 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 group">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                         alt="CEO"
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-blue-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Carlos Rodríguez</h3>
                    <p class="text-blue-600 font-semibold mb-3">CEO & Fundador</p>
                    <p class="text-gray-600 text-sm">Más de 15 años de experiencia en educación y tecnología.</p>
                </div>
            </div>

            <!-- Miembro 2 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 group">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                         alt="Directora Académica"
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-green-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Ana Martínez</h3>
                    <p class="text-green-600 font-semibold mb-3">Directora Académica</p>
                    <p class="text-gray-600 text-sm">PhD en Educación con especialización en e-learning.</p>
                </div>
            </div>

            <!-- Miembro 3 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 group">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                         alt="CTO"
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-purple-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Miguel Torres</h3>
                    <p class="text-purple-600 font-semibold mb-3">CTO & Co-Fundador</p>
                    <p class="text-gray-600 text-sm">Experto en desarrollo de plataformas educativas escalables.</p>
                </div>
            </div>

            <!-- Miembro 4 -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 group">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1551836026-d5c88ac5c73d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                         alt="Directora de Marketing"
                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-orange-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Laura González</h3>
                    <p class="text-orange-600 font-semibold mb-3">Directora de Marketing</p>
                    <p class="text-gray-600 text-sm">Especialista en crecimiento y comunidades digitales.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Carousel de Fotos - Galería -->
<section class="py-16 sm:py-20 lg:py-24 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 sm:mb-16">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                Nuestra <span class="text-blue-400">Galería</span>
            </h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                Momentos especiales que capturan la esencia de nuestra comunidad educativa
            </p>
        </div>

        <!-- Carousel Container -->
        <div class="relative w-full overflow-hidden rounded-2xl bg-gray-800">
            <div class="relative h-64 sm:h-80 lg:h-96 w-full">
                <!-- Slide 1 -->
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100" id="gallery-slide-1">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80"
                         alt="Comunidad de estudiantes"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black opacity-40"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold">Comunidad Activa</h3>
                        <p class="text-gray-200">Nuestros estudiantes colaborando en proyectos</p>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="gallery-slide-2">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                         alt="Sesiones de aprendizaje"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black opacity-40"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold">Aprendizaje Colaborativo</h3>
                        <p class="text-gray-200">Sesiones interactivas en vivo</p>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="gallery-slide-3">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                         alt="Ceremonia de graduación"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black opacity-40"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold">Logros Compartidos</h3>
                        <p class="text-gray-200">Celebrando el éxito de nuestros graduados</p>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="gallery-slide-4">
                    <img src="https://images.unsplash.com/photo-1535223289827-42f1e9919769?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                         alt="Innovación tecnológica"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black opacity-40"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold">Tecnología Educativa</h3>
                        <p class="text-gray-200">Plataformas de última generación</p>
                    </div>
                </div>

                <!-- Slide 5 -->
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0" id="gallery-slide-5">
                    <img src="https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                         alt="Eventos educativos"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black opacity-40"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold">Eventos Especiales</h3>
                        <p class="text-gray-200">Conferencias y workshops exclusivos</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <button id="gallery-prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white p-3 rounded-full transition-all duration-300 backdrop-blur-sm z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button id="gallery-next" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/30 hover:bg-white/50 text-white p-3 rounded-full transition-all duration-300 backdrop-blur-sm z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
                <button class="gallery-indicator w-3 h-3 bg-white rounded-full transition-all duration-300 opacity-100" data-slide="0"></button>
                <button class="gallery-indicator w-3 h-3 bg-white rounded-full transition-all duration-300 opacity-50" data-slide="1"></button>
                <button class="gallery-indicator w-3 h-3 bg-white rounded-full transition-all duration-300 opacity-50" data-slide="2"></button>
                <button class="gallery-indicator w-3 h-3 bg-white rounded-full transition-all duration-300 opacity-50" data-slide="3"></button>
                <button class="gallery-indicator w-3 h-3 bg-white rounded-full transition-all duration-300 opacity-50" data-slide="4"></button>
            </div>
        </div>

        <!-- Mini Gallery Grid -->
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="relative group overflow-hidden rounded-lg cursor-pointer" onclick="galleryCarousel.goToSlide(0)">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                     alt="Miniatura 1"
                     class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
            </div>
            <div class="relative group overflow-hidden rounded-lg cursor-pointer" onclick="galleryCarousel.goToSlide(1)">
                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                     alt="Miniatura 2"
                     class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
            </div>
            <div class="relative group overflow-hidden rounded-lg cursor-pointer" onclick="galleryCarousel.goToSlide(2)">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                     alt="Miniatura 3"
                     class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
            </div>
            <div class="relative group overflow-hidden rounded-lg cursor-pointer" onclick="galleryCarousel.goToSlide(3)">
                <img src="https://images.unsplash.com/photo-1535223289827-42f1e9919769?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80"
                     alt="Miniatura 4"
                     class="w-full h-32 object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-blue-600 to-purple-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
            ¿Listo para unirte a nuestra comunidad?
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
            Descubre cómo podemos ayudarte a alcanzar tus metas educativas y profesionales
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg inline-block">
                Comenzar Ahora
            </a>
            <a href="{{ route('home') }}#cursos" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:bg-white hover:text-blue-600 inline-block">
                Ver Cursos
            </a>
        </div>
    </div>
</section>
<script src="{{ asset('js/aboutus.js') }}"></script>
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

    .gallery-indicator {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .gallery-indicator:hover {
        opacity: 0.8 !important;
        transform: scale(1.1);
    }
</style>
@endsection
