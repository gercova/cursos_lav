@if($courses->count() > 0)
    <!-- Vista Grid -->
    <div id="grid-view-container" class="courses-view">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-gray-100 course-card">
                    <div class="relative">
                        <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                        @if($course->promotion_price)
                            <span class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">
                                -{{ number_format((($course->price - $course->promotion_price) / $course->price) * 100, 0) }}%
                            </span>
                        @endif
                        <span class="absolute bottom-3 left-3 bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium shadow-lg">
                            {{ $course->category->name }}
                        </span>
                        @if($course->level)
                            <span class="absolute top-3 left-3 bg-green-600 text-white px-2 py-1 rounded text-xs font-medium shadow-lg level-badge">
                                {{ ucfirst($course->level) }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-gray-900 line-clamp-2 hover:text-blue-600 transition-colors duration-200">
                            <a href="{{ route('course.show', $course->id) }}">{{ $course->title }}</a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->short_description ?: Str::limit($course->description, 120) }}</p>

                        <!-- Instructor -->
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                <span class="text-xs font-bold text-gray-600">
                                    {{ strtoupper(substr($course->instructor->names, 0, 1)) }}
                                </span>
                            </div>
                            <span class="text-sm text-gray-600">{{ $course->instructor->names }}</span>
                        </div>

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
                            <button onclick="addToCart({{ $course->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg add-to-cart-btn">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Vista Lista -->
    <div id="list-view-container" class="courses-view hidden">
        <div class="space-y-6">
            @foreach($courses as $course)
                {{-- <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-gray-100">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-64 md:flex-shrink-0">
                            <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-48 md:h-full object-cover">
                        </div>
                        <div class="p-6 flex-1">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
                                            {{ $course->category->name }}
                                        </span>
                                        @if($course->level)
                                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                {{ ucfirst($course->level) }}
                                            </span>
                                        @endif
                                        @if($course->promotion_price)
                                            <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                                                Oferta Especial
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-blue-600 transition-colors duration-200">
                                        <a href="{{ route('course.show', $course->id) }}">{{ $course->title }}</a>
                                    </h3>

                                    <p class="text-gray-600 mb-4">{{ $course->short_description ?: $course->description }}</p>

                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span>4.8 (250 reviews)</span>
                                        </div>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ $course->students_count ?? 125 }} estudiantes</span>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ $course->duration ?? 10 }} horas</span>
                                    </div>

                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-bold text-gray-600">
                                                {{ strtoupper(substr($course->instructor->names, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="text-sm text-gray-600">Por {{ $course->instructor->names }}</span>
                                    </div>
                                </div>

                                <div class="lg:text-right">
                                    <div class="mb-4">
                                        @if($course->promotion_price)
                                            <span class="text-2xl font-bold text-gray-900">S/ {{ number_format($course->promotion_price, 2) }}</span>
                                            <span class="text-lg text-gray-500 line-through block">S/ {{ number_format($course->price, 2) }}</span>
                                        @else
                                            <span class="text-2xl font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <button onclick="addToCart({{ $course->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg w-full lg:w-auto">
                                        Agregar al Carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="md:flex">
                                <!-- Imagen del curso -->
                                <div class="md:w-1/4">
                                    <div class="h-48 md:h-full bg-gradient-to-r from-blue-500 to-indigo-600 relative overflow-hidden">
                                        <img x-show="course.image" :src="course.image" :alt="course.title" class="w-full h-full object-cover" src="/storage/courses/6h9LQEKHT1ar2tUHlX8Hpj24wwWoKkCFfJrESIOe.jpg" alt="MANEJO DEFENSIVO">
                                        <div x-show="!course.image" class="absolute inset-0 flex items-center justify-center" style="display: none;">
                                            <i class="fas fa-book text-white text-5xl opacity-20"></i>
                                        </div>
                                        <div class="absolute top-3 left-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-500 text-white" :class="course.status === 'completed' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'">
                                                <span x-text="course.status === 'completed' ? 'Completado' : 'En progreso'">En progreso</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenido del curso -->
                                <div class="md:w-3/4 p-6">
                                    <div class="flex flex-col h-full">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-800" x-text="course.title">MANEJO DEFENSIVO</h3>
                                                    <div class="flex items-center mt-2 space-x-4">
                                                        <span class="text-sm text-gray-600">
                                                            <i class="fas fa-layer-group mr-1"></i>
                                                            <span x-text="course.modules">1</span> módulos
                                                        </span>
                                                        <span class="text-sm text-gray-600">
                                                            <i class="fas fa-video mr-1"></i>
                                                            <span x-text="course.lessons">1</span> lecciones
                                                        </span>
                                                        <span class="text-sm text-gray-600">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            <span x-text="course.duration">1</span>
                                                        </span>
                                                    </div>
                                                    <p class="text-gray-600 mt-3" x-text="course.description">¿Sabías que el 90% de los accidentes viales se pueden evitar con la técnica correcta? No se trata solo de conducir, se trata de sobrevivir y proteger. Nuestro curso de Manejo Defensivo te transforma en un conductor de élite, capaz de anticipar peligros antes de que ocurran. Domina las normativas vigentes en Perú y adquiere las habilidades tácticas para reducir riesgos, ahorrar combustible y, lo más importante, salvar vidas en cada kilómetro. ¡No manejes por inercia, conduce con estrategia!</p>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <div class="w-16 h-16">
                                                        <div class="relative">
                                                            <svg class="w-16 h-16" viewBox="0 0 36 36">
                                                                <path class="text-gray-200" fill="none" stroke="currentColor" stroke-width="3" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                                                <path :class="course.status === 'completed' ? 'text-green-500' : 'text-blue-500'" fill="none" stroke="currentColor" stroke-width="3" :stroke-dasharray="course.progress + ', 100'" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" class="text-blue-500" stroke-dasharray="0.00, 100"></path>
                                                            </svg>
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <span class="text-lg font-bold text-blue-600" :class="course.status === 'completed' ? 'text-green-600' : 'text-blue-600'" x-text="course.progress + '%'">0.00%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-500 mt-2">Progreso</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 pt-6 border-t border-gray-100">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="far fa-calendar mr-2"></i>
                                                        <span>Inscrito: </span>
                                                        <span class="font-medium ml-1" x-text="course.enrolled_date">22/02/2026</span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600 mt-1">
                                                        <i class="far fa-clock mr-2"></i>
                                                        <span>Último acceso: </span>
                                                        <span class="font-medium ml-1" x-text="course.last_accessed || 'No accedido'">No accedido</span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-3">
                                                    <button class="px-4 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Detalles
                                                    </button>
                                                    <a :href="course.continue_url" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors duration-200" href="https://ipf-educa.com/courses/7/learn">
                                                        <i class="fas fa-play" :class="course.status === 'completed' ? 'fa-certificate' : 'fa-play'"></i>
                                                        <span class="ml-2" x-text="course.status === 'completed' ? 'Ver Certificado' : 'Continuar'">Continuar</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            @endforeach
        </div>
    </div>

    <!-- Paginación -->
    @if($courses->hasPages())
        <div class="mt-12 flex justify-center">
            <div class="bg-white px-6 py-4 rounded-lg shadow-lg">
                {{ $courses->links() }}
            </div>
        </div>
    @endif
@else
    <div id="empty-state" class="text-center py-16">
        <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron cursos</h3>
        <p class="text-gray-600 mb-6">Intenta ajustar tus filtros o términos de búsqueda</p>
        <button onclick="coursesPage.clearFilters()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
            Mostrar todos los cursos
        </button>
    </div>
@endif
