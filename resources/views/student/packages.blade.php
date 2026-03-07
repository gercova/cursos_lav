@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Inicio')
@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen">
    <!-- Hero Section con diseño llamativo -->
    <div class="relative overflow-hidden bg-indigo-900 py-16">
        <!-- Elementos decorativos de fondo -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500 rounded-full opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-500 rounded-full opacity-20 animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-pink-500 rounded-full opacity-10 animate-ping"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 animate-fade-in-down">
                Paquetes <span class="text-yellow-300">Especiales</span>
            </h1>
            <p class="text-xl text-indigo-200 max-w-3xl mx-auto mb-8 animate-fade-in-up">
                Combina múltiples cursos y ahorra hasta un 40% con nuestros paquetes exclusivos. 
                ¡Desarrolla todas tus habilidades al mejor precio!
            </p>
            
            <!-- Estadísticas llamativas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl mx-auto mt-8">
                <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                    <div class="text-3xl font-bold text-white">{{ $packages->total() }}+</div>
                    <div class="text-indigo-200">Paquetes disponibles</div>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                    <div class="text-3xl font-bold text-white">40%</div>
                    <div class="text-indigo-200">Ahorro máximo</div>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-lg p-4 transform hover:scale-105 transition-all duration-300">
                    <div class="text-3xl font-bold text-white">24/7</div>
                    <div class="text-indigo-200">Acceso ilimitado</div>
                </div>
            </div>
        </div>
        
        <!-- Ola decorativa -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white" fill-opacity="0.1"/>
                <path d="M0 120L60 112.5C120 105 240 90 360 82.5C480 75 600 75 720 82.5C840 90 960 105 1080 112.5C1200 120 1320 120 1380 120L1440 120V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white" fill-opacity="0.3"/>
            </svg>
        </div>
    </div>

    <!-- Barra de filtros y búsqueda -->
    <div class="sticky top-16 z-30 bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Búsqueda -->
                <div class="flex-1">
                    <form id="search-form" method="GET" action="{{ route('paquetes') }}" class="flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Buscar paquetes..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 shadow-md hover:shadow-lg">
                            Buscar
                        </button>
                    </form>
                </div>
                
                <!-- Filtros rápidos -->
                <div class="flex flex-wrap gap-2">
                    <!-- Filtro de categoría -->
                    {{-- <select name="category" id="category-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select> --}}
                    
                    <!-- Filtro de precio mínimo -->
                    <select name="min_price" id="min-price-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">Precio mín.</option>
                        <option value="50" {{ request('min_price') == 50 ? 'selected' : '' }}>S/ 50</option>
                        <option value="100" {{ request('min_price') == 100 ? 'selected' : '' }}>S/ 100</option>
                        <option value="200" {{ request('min_price') == 200 ? 'selected' : '' }}>S/ 200</option>
                        <option value="500" {{ request('min_price') == 500 ? 'selected' : '' }}>S/ 500</option>
                    </select>
                    
                    <!-- Filtro de precio máximo -->
                    <select name="max_price" id="max-price-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">Precio máx.</option>
                        <option value="200" {{ request('max_price') == 200 ? 'selected' : '' }}>S/ 200</option>
                        <option value="500" {{ request('max_price') == 500 ? 'selected' : '' }}>S/ 500</option>
                        <option value="1000" {{ request('max_price') == 1000 ? 'selected' : '' }}>S/ 1000</option>
                        <option value="2000" {{ request('max_price') == 2000 ? 'selected' : '' }}>S/ 2000</option>
                    </select>
                    
                    <!-- Filtro de fecha -->
                    <select name="date_range" id="date-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="">Cualquier fecha</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hoy</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Última semana</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Último mes</option>
                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>Último año</option>
                    </select>
                    
                    <!-- Checkbox de promoción -->
                    <label class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200">
                        <input type="checkbox" name="on_promotion" value="1" {{ request('on_promotion') ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500 mr-2">
                        <span class="text-sm">En promoción</span>
                    </label>
                    
                    <!-- Ordenamiento -->
                    <select name="sort" id="sort-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: menor a mayor</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: mayor a menor</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares</option>
                    </select>
                </div>
            </div>
            
            <!-- Filtros activos (tags) -->
            @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'date_range', 'on_promotion']))
            <div class="flex flex-wrap gap-2 mt-3">
                <span class="text-sm text-gray-600 mr-2">Filtros activos:</span>
                
                @if(request('search'))
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    Búsqueda: "{{ request('search') }}"
                    <button onclick="removeFilter('search')" class="ml-1 text-indigo-600 hover:text-indigo-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </span>
                @endif
                
                @if(request('category'))
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    Categoría: {{ $categories->find(request('category'))?->name }}
                    <button onclick="removeFilter('category')" class="ml-1 text-indigo-600 hover:text-indigo-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </span>
                @endif
                
                @if(request('min_price'))
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    Mín: S/ {{ request('min_price') }}
                    <button onclick="removeFilter('min_price')" class="ml-1 text-indigo-600 hover:text-indigo-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </span>
                @endif
                
                @if(request('max_price'))
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    Máx: S/ {{ request('max_price') }}
                    <button onclick="removeFilter('max_price')" class="ml-1 text-indigo-600 hover:text-indigo-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </span>
                @endif
                
                @if(request('date_range'))
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    Fecha: {{ ['today'=>'Hoy','week'=>'Últ. semana','month'=>'Últ. mes','year'=>'Últ. año'][request('date_range')] }}
                    <button onclick="removeFilter('date_range')" class="ml-1 text-indigo-600 hover:text-indigo-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </span>
                @endif
                
                @if(request('on_promotion'))
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    En promoción
                    <button onclick="removeFilter('on_promotion')" class="ml-1 text-indigo-600 hover:text-indigo-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </span>
                @endif
                
                <button onclick="clearAllFilters()" class="text-sm text-red-600 hover:text-red-800 ml-2">
                    Limpiar todos
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Grid de Paquetes -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Resultados y contador -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ $packages->total() }} paquete(s) disponible(s)
            </h2>
            <div class="text-sm text-gray-600">
                Mostrando {{ $packages->firstItem() ?? 0 }} - {{ $packages->lastItem() ?? 0 }} de {{ $packages->total() }}
            </div>
        </div>

        @if($packages->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay paquetes</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron paquetes con los filtros seleccionados.</p>
                <div class="mt-6">
                    <button onclick="clearAllFilters()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Limpiar filtros
                    </button>
                </div>
            </div>
        @else
            <div id="packages-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                        
                        <!-- Imagen/Icono del paquete (puedes usar una imagen real si tienes) -->
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
                                    {{ $package->seats_min.' - '.$package->seats_max }} cupos
                                </span>
                            </div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6">
                            <a href="{{ route('paquete.detail', $package->slug) }}">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                                    {{ $package->title }}
                                </h3>
                            </a>
                            
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
                            
                            <!-- Cursos destacados incluidos (primeros 3) -->
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
            </div>
            
            <!-- Paginación -->
            <div class="mt-12">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Función para remover un filtro específico
    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        window.location.href = url.toString();
    }
    
    // Función para limpiar todos los filtros
    function clearAllFilters() {
        window.location.href = "{{ route('paquetes') }}";
    }
    
    // Auto-submit al cambiar filtros
    document.querySelectorAll('#category-filter, #min-price-filter, #max-price-filter, #date-filter, #sort-filter, input[name="on_promotion"]').forEach(element => {
        element.addEventListener('change', function() {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('paquetes') }}";
            
            // Preservar search si existe
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && searchInput.value) {
                const searchField = document.createElement('input');
                searchField.type = 'hidden';
                searchField.name = 'search';
                searchField.value = searchInput.value;
                form.appendChild(searchField);
            }
            
            // Agregar el filtro actual
            const field = document.createElement('input');
            field.type = 'hidden';
            field.name = element.name;
            field.value = element.type === 'checkbox' ? (element.checked ? '1' : '') : element.value;
            form.appendChild(field);
            
            document.body.appendChild(form);
            form.submit();
        });
    });
    
    // Función para agregar al carrito
    function addToCart(itemId, itemType) {
        // Aquí puedes implementar la lógica del carrito
        // Por ahora mostraremos una notificación
        alert('Producto agregado al carrito (funcionalidad en desarrollo)');
        
        // Ejemplo de implementación con fetch
        /*
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                item_id: itemId,
                item_type: itemType,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar contador del carrito
                document.getElementById('cart-count').textContent = data.cart_count;
                // Mostrar notificación
                showNotification('¡Producto agregado al carrito!', 'success');
            }
        });
        */
    }
    
    // Búsqueda con debounce
    let searchTimeout;
    document.getElementById('search-input')?.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('search-form').submit();
        }, 500);
    });
</script>

<style>
    @keyframes fade-in-down {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fade-in-up {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-down {
        animation: fade-in-down 0.8s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Estilos para la paginación (puedes personalizarlos) */
    .pagination {
        @apply flex justify-center space-x-1;
    }
    
    .pagination .page-item {
        @apply inline-block;
    }
    
    .pagination .page-link {
        @apply px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors duration-200;
    }
    
    .pagination .active .page-link {
        @apply bg-indigo-600 text-white hover:bg-indigo-700;
    }
    
    .pagination .disabled .page-link {
        @apply text-gray-400 cursor-not-allowed hover:bg-transparent;
    }
</style>

@endsection