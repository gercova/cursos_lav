@if($courses->count() > 0)
    <!-- Vista Grid -->
    <div id="grid-view-container" class="courses-view">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-gray-100 course-card">
                    <a href="{{ route('course.show', $course->slug) }}">
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
                                <a href="{{ route('course.show', $course->slug) }}">{{ $course->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->short_description ?: Str::limit($course->description, 120) }}</p>
                            <div class="flex items-center mb-6">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ $course->instructor->profile_photo ? Storage::url($course->instructor->profile_photo) : asset('storage/instructors/instructor-default.png') }}" alt="{{ $course->instructor->names }}">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $course->instructor->names }}</p>
                                        <p class="text-sm text-gray-600">{{ $course->instructor->profession ?? 'Instructor' }}</p>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600 students-count">{{ $course->students_count ?? 125 }} estudiantes</span>
                                </div>
                            </div> --}}

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 px-4 py-3">
                                    @if($course->promotion_price)
                                        <span class="text-xl font-bold text-gray-900">S/ {{ number_format($course->promotion_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through">S/ {{ number_format($course->price, 2) }}</span>
                                    @else
                                        <span class="text-xl font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <button onclick="addToCart({{ $course->id }})" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg add-to-cart-btn">
                                    Agregar al carrito
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Vista Lista -->
    <div id="list-view-container" class="courses-view hidden">
        <div class="space-y-6">
            @foreach($courses as $course)
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="md:flex">
                        <!-- Imagen del curso -->
                        
                        <div class="md:w-1/4">
                            <a href="{{ route('course.show', $course->slug) }}">
                                <div class="h-48 md:h-full bg-gradient-to-r from-blue-500 to-indigo-600 relative overflow-hidden">
                                    <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    <div x-show="{{ $course->image_url }}" class="absolute inset-0 flex items-center justify-center" style="display: none;">
                                        <i class="fas fa-book text-white text-5xl opacity-20"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Contenido del curso -->
                        <div class="md:w-3/4 p-6">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <a href="{{ route('course.show', $course->slug) }}">
                                                <h3 class="text-xl font-bold text-gray-800">{{ $course->title }}</h3>
                                            </a>
                                            <div class="flex items-center mt-2 space-x-4">
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-layer-group mr-1"></i>
                                                    <span x-text="course.modules">{{ $course->sections->count() }}</span> Módulos
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    <i class="fas fa-video mr-1"></i>
                                                    <span x-text="course.lessons">{{ $course->lessons->count() }}</span> Lecciones
                                                </span>
                                            </div>
                                            <p class="text-gray-600 mt-3" x-text="course.description">{{ $course->description }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="lg:text-right">
                                                <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium">
                                                    {{ $course->category->name }}
                                                </span>
                                                &nbsp;
                                                @if($course->promotion_price)
                                                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                                                        Oferta Especial
                                                    </span>
                                                @endif
                                            </div>
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
                                        <button onclick="addToCart({{ $course->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg w-full lg:w-auto">
                                            Agregar al Carrito
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <div class="flex items-center mb-6">
                                        <div class="flex items-center">
                                            <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ $course->instructor->profile_photo ? Storage::url($course->instructor->profile_photo) : asset('storage/instructors/instructor-default.png') }}" alt="{{ $course->instructor->names }}">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Instructor: {{ $course->instructor->names }}</p>
                                                <p class="text-sm text-gray-600">{{ $course->instructor->profession ?? 'Instructor' }}</p>
                                            </div>
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
