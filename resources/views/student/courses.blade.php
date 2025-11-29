@extends('layouts.app')

@section('title', 'IPF Consultores - Catálogo de Cursos')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">
                Catálogo de <span class="text-yellow-300">Cursos</span>
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Descubre todos nuestros cursos profesionales y encuentra el perfecto para tus objetivos
            </p>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<section class="bg-white border-b border-gray-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
            <!-- Búsqueda -->
            <div class="w-full lg:w-96">
                <div class="relative">
                    <input type="text"
                        id="search-input"
                        placeholder="Buscar cursos..."
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-4 w-full lg:w-auto">
                <!-- Filtro por Categoría -->
                <div class="relative">
                    <select id="category-filter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer min-w-48">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Ordenar por -->
                <div class="relative">
                    <select id="sort-filter" class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 cursor-pointer min-w-48">
                        <option value="newest">Más recientes primero</option>
                        <option value="oldest">Más antiguos primero</option>
                        <option value="popular">Más populares</option>
                        <option value="rating">Mejor valorados</option>
                        <option value="price_low">Precio: menor a mayor</option>
                        <option value="price_high">Precio: mayor a menor</option>
                        <option value="name_asc">Nombre: A-Z</option>
                        <option value="name_desc">Nombre: Z-A</option>
                    </select>
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Botón Limpiar Filtros -->
                <button id="clear-filters" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-lg transition-all duration-200 font-medium">
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Filtros Activos -->
        <div id="active-filters" class="mt-4 flex flex-wrap gap-2 hidden">
            <!-- Los filtros activos se mostrarán aquí -->
        </div>
    </div>
</section>

<!-- Resultados y Grid de Cursos -->
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header de Resultados -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 id="results-count" class="text-2xl font-bold text-gray-900">
                    {{ $courses->total() }} cursos encontrados
                </h2>
                <p id="filter-description" class="text-gray-600 mt-1">
                    Mostrando todos los cursos disponibles
                </p>
            </div>

            <!-- Vista (Grid/List) -->
            <div class="flex items-center space-x-2 bg-white rounded-lg border border-gray-300 p-1">
                <button id="grid-view" class="p-2 rounded-md bg-blue-100 text-blue-600 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button id="list-view" class="p-2 rounded-md text-gray-500 hover:text-blue-600 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Grid/List de Cursos -->
        <div id="courses-container">
            <!-- Vista Grid (Por defecto) -->
            <div id="grid-view-container" class="courses-view">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-gray-100 course-card">
                            <div class="relative">
                                <img src="{{ $course->image_url ?: 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80' }}"
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

            <!-- Vista Lista (Oculta por defecto) -->
            <div id="list-view-container" class="courses-view hidden">
                <div class="space-y-6">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border border-gray-100">
                            <div class="flex flex-col md:flex-row">
                                <div class="md:w-64 md:flex-shrink-0">
                                    <img src="{{ $course->image_url ?: 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80' }}"
                                        alt="{{ $course->title }}"
                                        class="w-full h-48 md:h-full object-cover">
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
                        </div>
                    @endforeach
                </div>
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

        <!-- Estado vacío -->
        <div id="empty-state" class="hidden text-center py-16">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron cursos</h3>
            <p class="text-gray-600 mb-6">Intenta ajustar tus filtros o términos de búsqueda</p>
            <button id="reset-search" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                Mostrar todos los cursos
            </button>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gray-900 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">¿No encuentras lo que buscas?</h2>
        <p class="text-xl text-gray-300 mb-8">Contáctanos y te ayudaremos a encontrar el curso perfecto para ti</p>
        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 inline-block">
            Contactar Asesor
        </a>
    </div>
</section>
@endsection

@section('scripts')
<script>
    class CoursesPage {
        constructor() {
            this.currentView = 'grid';
            this.filters = {
                search: '',
                category: '',
                level: '',
                sort: 'newest'
            };
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.loadInitialFilters();
            this.setupViewToggle();
        }

        setupEventListeners() {
            // Filtros
            document.getElementById('search-input').addEventListener('input',
                this.debounce(() => this.applyFilters(), 500));

            document.getElementById('category-filter').addEventListener('change', () => this.applyFilters());
            document.getElementById('level-filter').addEventListener('change', () => this.applyFilters());
            document.getElementById('sort-filter').addEventListener('change', () => this.applyFilters());

            // Limpiar filtros
            document.getElementById('clear-filters').addEventListener('click', () => this.clearFilters());
            document.getElementById('reset-search').addEventListener('click', () => this.clearFilters());

            // Navegación con teclado
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.clearFilters();
                }
            });
        }

        setupViewToggle() {
            const gridViewBtn = document.getElementById('grid-view');
            const listViewBtn = document.getElementById('list-view');
            const gridContainer = document.getElementById('grid-view-container');
            const listContainer = document.getElementById('list-view-container');

            gridViewBtn.addEventListener('click', () => {
                this.currentView = 'grid';
                gridContainer.classList.remove('hidden');
                listContainer.classList.add('hidden');
                gridViewBtn.classList.add('bg-blue-100', 'text-blue-600');
                listViewBtn.classList.remove('bg-blue-100', 'text-blue-600');
                listViewBtn.classList.add('text-gray-500');
            });

            listViewBtn.addEventListener('click', () => {
                this.currentView = 'list';
                gridContainer.classList.add('hidden');
                listContainer.classList.remove('hidden');
                listViewBtn.classList.add('bg-blue-100', 'text-blue-600');
                gridViewBtn.classList.remove('bg-blue-100', 'text-blue-600');
                gridViewBtn.classList.add('text-gray-500');
            });
        }

        loadInitialFilters() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get('category')) {
                this.filters.category = urlParams.get('category');
                document.getElementById('category-filter').value = this.filters.category;
            }

            if (urlParams.get('level')) {
                this.filters.level = urlParams.get('level');
                document.getElementById('level-filter').value = this.filters.level;
            }

            if (urlParams.get('search')) {
                this.filters.search = urlParams.get('search');
                document.getElementById('search-input').value = this.filters.search;
            }

            if (urlParams.get('sort')) {
                this.filters.sort = urlParams.get('sort');
                document.getElementById('sort-filter').value = this.filters.sort;
            }

            this.updateActiveFilters();
        }

        applyFilters() {
            this.filters = {
                search: document.getElementById('search-input').value,
                category: document.getElementById('category-filter').value,
                level: document.getElementById('level-filter').value,
                sort: document.getElementById('sort-filter').value
            };

            this.updateURL();
            this.updateActiveFilters();
            this.filterCourses();
        }

        async filterCourses() {
            try {
                const params = new URLSearchParams();

                if (this.filters.search) params.append('search', this.filters.search);
                if (this.filters.category) params.append('category', this.filters.category);
                if (this.filters.level) params.append('level', this.filters.level);
                if (this.filters.sort) params.append('sort', this.filters.sort);

                // Mostrar loading
                this.showLoading();

                const response = await axios.get(`/courses?${params.toString()}`);

                // Actualizar el contenido
                document.getElementById('courses-container').innerHTML = response.data;

                // Actualizar contador de resultados
                this.updateResultsCount();

            } catch (error) {
                console.error('Error filtering courses:', error);
                this.showError();
            }
        }

        updateResultsCount() {
            const courseCards = document.querySelectorAll('.course-card');
            const resultsCount = document.getElementById('results-count');
            const filterDescription = document.getElementById('filter-description');
            const emptyState = document.getElementById('empty-state');
            const coursesContainer = document.querySelector('.courses-view:not(.hidden)');

            if (courseCards.length === 0) {
                coursesContainer.style.display = 'none';
                emptyState.classList.remove('hidden');
                resultsCount.textContent = '0 cursos encontrados';
            } else {
                coursesContainer.style.display = 'block';
                emptyState.classList.add('hidden');
                resultsCount.textContent = `${courseCards.length} cursos encontrados`;
            }

            // Actualizar descripción de filtros
            let description = 'Mostrando todos los cursos';
            if (this.filters.search) description = `Resultados para "${this.filters.search}"`;
            if (this.filters.category) description += ` en ${document.getElementById('category-filter').selectedOptions[0].text}`;
            if (this.filters.level) description += ` - Nivel ${this.filters.level}`;

            filterDescription.textContent = description;
        }

        updateActiveFilters() {
            const activeFiltersContainer = document.getElementById('active-filters');
            activeFiltersContainer.innerHTML = '';

            let hasActiveFilters = false;

            if (this.filters.search) {
                this.addActiveFilter('search', `Búsqueda: "${this.filters.search}"`, activeFiltersContainer);
                hasActiveFilters = true;
            }

            if (this.filters.category) {
                const categoryName = document.getElementById('category-filter').selectedOptions[0].text;
                this.addActiveFilter('category', `Categoría: ${categoryName}`, activeFiltersContainer);
                hasActiveFilters = true;
            }

            if (this.filters.level) {
                this.addActiveFilter('level', `Nivel: ${this.filters.level}`, activeFiltersContainer);
                hasActiveFilters = true;
            }

            if (hasActiveFilters) {
                activeFiltersContainer.classList.remove('hidden');
            } else {
                activeFiltersContainer.classList.add('hidden');
            }
        }

        addActiveFilter(type, text, container) {
            const filterElement = document.createElement('div');
            filterElement.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium flex items-center gap-2';
            filterElement.innerHTML = `
                ${text}
                <button type="button" onclick="coursesPage.removeFilter('${type}')" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(filterElement);
        }

        removeFilter(type) {
            switch(type) {
                case 'search':
                    this.filters.search = '';
                    document.getElementById('search-input').value = '';
                    break;
                case 'category':
                    this.filters.category = '';
                    document.getElementById('category-filter').value = '';
                    break;
                case 'level':
                    this.filters.level = '';
                    document.getElementById('level-filter').value = '';
                    break;
            }
            this.applyFilters();
        }

        clearFilters() {
            this.filters = {
                search: '',
                category: '',
                level: '',
                sort: 'newest'
            };

            document.getElementById('search-input').value = '';
            document.getElementById('category-filter').value = '';
            document.getElementById('level-filter').value = '';
            document.getElementById('sort-filter').value = 'newest';

            this.applyFilters();
        }

        updateURL() {
            const params = new URLSearchParams();

            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.category) params.append('category', this.filters.category);
            if (this.filters.level) params.append('level', this.filters.level);
            if (this.filters.sort !== 'newest') params.append('sort', this.filters.sort);

            const newURL = params.toString() ? `/courses?${params.toString()}` : '/courses';
            window.history.replaceState({}, '', newURL);
        }

        showLoading() {
            const container = document.getElementById('courses-container');
            container.innerHTML = `
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>
            `;
        }

        showError() {
            const container = document.getElementById('courses-container');
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Error al cargar los cursos</h3>
                    <p class="text-gray-600 mb-4">Intenta recargar la página</p>
                    <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Recargar Página
                    </button>
                </div>
            `;
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }

    // Inicializar la página de cursos
    document.addEventListener('DOMContentLoaded', function() {
        window.coursesPage = new CoursesPage();
    });

    // Función global para agregar al carrito
    async function addToCart(courseId) {
        try {
            const response = await axios.post(`/cart/add/${courseId}`);

            if (response.data.success) {
                // Mostrar notificación
                showNotification('Curso agregado al carrito', 'success');

                // Actualizar contador del carrito
                updateCartCount();
            }
        } catch (error) {
            if (error.response?.status === 401) {
                showNotification('Debes iniciar sesión para agregar cursos al carrito', 'error');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showNotification('Error al agregar el curso al carrito', 'error');
            }
        }
    }

    function showNotification(message, type = 'info') {
        // Implementar sistema de notificaciones
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    async function updateCartCount() {
        try {
            const response = await axios.get('/api/cart/count');
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = response.data.count;
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }
</script>

<style>
    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .courses-view {
        transition: opacity 0.3s ease;
    }

    /* Estilos para los badges de nivel */
    .level-badge {
        text-transform: capitalize;
    }

    /* Mejoras para los filtros */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
</style>
@endsection
