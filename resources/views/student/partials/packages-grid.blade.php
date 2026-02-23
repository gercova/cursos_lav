@foreach($packages as $package)
    <div class="group relative bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
        <!-- Badge de promoción -->
        @if($package->has_promotion)
            <div class="absolute top-4 right-4 z-10">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500 text-white shadow-lg">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812z" clip-rule="evenodd"></path>
                    </svg>
                    -{{ $package->discount_percentage }}%
                </span>
            </div>
        @endif
        
        <!-- Imagen/Icono del paquete -->
        <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-24 h-24 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <!-- Indicador de cursos incluidos -->
            <div class="absolute bottom-4 left-4 flex items-center space-x-1 text-white text-sm font-medium bg-black bg-opacity-50 rounded-full px-3 py-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span>{{ $package->total_courses }} cursos</span>
            </div>
            
            <!-- Indicador de disponibilidad -->
            <div class="absolute bottom-4 right-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $package->seats ?? 'Ilimitado' }} cupos
                </span>
            </div>
        </div>
        
        <!-- Contenido -->
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                {{ $package->name }}
            </h3>
            
            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                {{ $package->description }}
            </p>
            
            <!-- Categorías -->
            @if($package->categories->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($package->categories->take(3) as $category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $category->name }}
                        </span>
                    @endforeach
                    @if($package->categories->count() > 3)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            +{{ $package->categories->count() - 3 }}
                        </span>
                    @endif
                </div>
            @endif
            
            <!-- Precios -->
            <div class="mb-4">
                @if($package->has_promotion)
                    <div class="flex items-center gap-2">
                        <span class="text-3xl font-bold text-gray-900">
                            S/ {{ number_format($package->final_price, 2) }}
                        </span>
                        <span class="text-sm text-gray-500 line-through">
                            S/ {{ number_format($package->price, 2) }}
                        </span>
                    </div>
                    <div class="text-sm text-green-600 font-medium">
                        Ahorras S/ {{ number_format($package->price - $package->final_price, 2) }}
                    </div>
                @else
                    <span class="text-3xl font-bold text-gray-900">
                        S/ {{ number_format($package->price, 2) }}
                    </span>
                @endif
            </div>
            
            <!-- Cursos destacados incluidos -->
            @php
                $featuredCourses = $package->courses->take(3);
            @endphp
            
            @if($featuredCourses->isNotEmpty())
                <div class="mb-4">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Cursos incluidos:
                    </div>
                    <ul class="space-y-1">
                        @foreach($featuredCourses as $course)
                            <li class="flex items-center text-xs text-gray-600">
                                <svg class="w-3 h-3 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $course->title }}
                            </li>
                        @endforeach
                        @if($package->courses->count() > 3)
                            <li class="text-xs text-indigo-600">
                                + {{ $package->courses->count() - 3 }} cursos más
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
            
            <!-- Botón de acción -->
            <button onclick="addToCart({{ $package->id }}, 'package')" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform group-hover:translate-y-0 translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Agregar al carrito
            </button>
        </div>
        
        <!-- Efecto de brillo en hover -->
        <div class="absolute inset-0 border-2 border-transparent group-hover:border-indigo-500 rounded-2xl transition-all duration-300 pointer-events-none"></div>
    </div>
@endforeach