@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Catálogo de Cursos Profesionales')
@section('meta')
    <meta name="description" content="Explora nuestro catálogo completo de cursos online en Seguridad y Salud en el Trabajo (SST), normas ISO y Medio Ambiente. Capacítate a tu ritmo y certifícate con {{ $enterprise->trade_name }}.">
    <meta name="keywords" content="catálogo de cursos, cursos online SST, cursos ISO 9001, ISO 14001, ISO 45001, prevención de riesgos, diplomados SST, aula virtual, certificaciones Perú">
    <meta name="author" content="{{ $enterprise->trade_name }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('cursos') }}">

    <!-- Open Graph -->
    <meta property="og:title" content="Catálogo de Cursos Profesionales - {{ $enterprise->trade_name }}">
    <meta property="og:description" content="Potencia tu perfil profesional con nuestros cursos en Prevención de Riesgos, Calidad y Medio Ambiente. ¡Inscríbete hoy!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(isset($enterprise->logo_path))
        <meta property="og:image" content="{{ asset($enterprise->logo_path) }}">
    @endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Catálogo de Cursos Profesionales - {{ $enterprise->trade_name }}">
    <meta name="twitter:description" content="Potencia tu perfil profesional con nuestros cursos en Prevención de Riesgos, Calidad y Medio Ambiente.">

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "ItemList",
          "name": "{{ $enterprise->trade_name }} - Catálogo de Cursos",
          "description": "Explora nuestro catálogo de cursos profesionales de capacitación.",
          "numberOfItems": {{ $courses->total() }},
          "itemListElement": [
            @foreach($courses->take(12) as $index => $c)
            {
              "@type": "ListItem",
              "position": {{ $index + 1 }},
              "item": {
                "@type": "Course",
                "name": "{{ $c->title }}",
                "description": "{{ $c->short_description ?: Str::limit($c->description, 150) }}",
                "provider": {
                  "@type": "Organization",
                  "name": "{{ $enterprise->trade_name }}",
                  "sameAs": "{{ url('/') }}"
                },
                "image": "{{ $c->image_url }}",
                "offers": {
                  "@type": "Offer",
                  "price": "{{ $c->promotion_price ?? $c->price }}",
                  "priceCurrency": "PEN"
                }
              }
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
          ]
        }
      ]
    }
    </script>
@endsection

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-900 to-indigo-900 py-16 sm:py-20 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(59,130,246,0.15),transparent)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <span class="inline-block bg-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-4">Catálogo Completo</span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                Catálogo de <span class="text-blue-400">Cursos</span>
            </h1>
            <p class="text-lg sm:text-xl text-blue-100 max-w-2xl mx-auto leading-relaxed">
                Descubre programas diseñados por expertos de la industria para expandir tus conocimientos y certificar tu futuro.
            </p>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<section class="bg-white border-b border-gray-100 py-6 sticky top-0 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">
            <!-- Búsqueda -->
            <div class="w-full lg:w-96">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           id="search-input" 
                           placeholder="Buscar cursos por título, instructor..." 
                           autocomplete="off"
                           class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 transition-all duration-200">
                    <button type="button" 
                            id="clear-search-btn" 
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition hidden">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap gap-3 items-center">
                <!-- Filtro por Categoría -->
                <div class="relative min-w-[200px]">
                    <select id="category-filter" class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Ordenar por -->
                <div class="relative min-w-[180px]">
                    <select id="sort-filter" class="w-full appearance-none bg-white border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
                        <option value="newest">Más recientes primero</option>
                        <option value="oldest">Más antiguos primero</option>
                        <option value="popular">Más populares</option>
                        <option value="price_low">Precio: menor a mayor</option>
                        <option value="price_high">Precio: mayor a menor</option>
                        <option value="name_asc">Nombre: A-Z</option>
                        <option value="name_desc">Nombre: Z-A</option>
                    </select>
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Botón Limpiar Filtros -->
                <button id="clear-filters" class="border border-gray-200 hover:border-gray-300 text-gray-600 hover:text-gray-800 px-4 py-2.5 rounded-xl text-sm font-medium transition whitespace-nowrap hidden flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Filtros Activos Chips -->
        <div id="active-filters" class="mt-4 flex flex-wrap gap-2 hidden"></div>
    </div>
</section>

<!-- Resultados y Grid de Cursos -->
<section class="py-12 bg-gray-50 min-h-[500px]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header de Resultados -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h2 id="results-count" class="text-2xl font-bold text-gray-900">
                    {{ $courses->total() }} cursos encontrados
                </h2>
                <p id="filter-description" class="text-sm text-gray-500 mt-1">
                    Mostrando todos los cursos disponibles
                </p>
            </div>

            <!-- Vista (Grid/List Toggle) -->
            <div class="flex items-center space-x-1 bg-white rounded-xl border border-gray-200 p-1 shadow-sm">
                <button id="grid-view" class="p-2 rounded-lg bg-blue-50 text-blue-600 transition-all duration-200" title="Vista Cuadrícula">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button id="list-view" class="p-2 rounded-lg text-gray-400 hover:text-blue-600 transition-all duration-200" title="Vista Lista">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Grid/List de Cursos Renderizado Dinámicamente -->
        <div id="courses-container">
            @include('student.partials.courses-grid')
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gray-900 py-16 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_80%,rgba(59,130,246,0.1),transparent)]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl font-extrabold text-white mb-3">¿No encuentras lo que estás buscando?</h2>
        <p class="text-lg text-gray-300 mb-8 max-w-xl mx-auto">Contáctanos directamente y uno de nuestros asesores de capacitación te guiará para encontrar el programa ideal para ti.</p>
        <a href="{{ route('contacto') }}" class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-8 py-4 rounded-xl text-base font-semibold transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform inline-block">
            Contactar Asesor
        </a>
    </div>
</section>
@endsection

@section('scripts')
<script>
    class CoursesPage {
        constructor() {
            this.currentView = localStorage.getItem('coursesView') || 'grid';
            this.filters = {
                search: '',
                category: '',
                sort: 'newest'
            };
            this.isLoading = false;
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.loadInitialFilters();
            this.setupViewToggle();
            this.restoreView();
        }

        setupEventListeners() {
            const searchInput = document.getElementById('search-input');
            const clearSearchBtn = document.getElementById('clear-search-btn');

            if (searchInput) {
                searchInput.addEventListener('input', this.debounce(() => {
                    // Sanitización simple
                    let cleanSearch = searchInput.value.replace(/<[^>]*>/g, '').trim();
                    this.filters.search = cleanSearch;

                    if (cleanSearch.length > 0) {
                        clearSearchBtn?.classList.remove('hidden');
                    } else {
                        clearSearchBtn?.classList.add('hidden');
                    }
                    this.applyFilters();
                }, 400));
            }

            clearSearchBtn?.addEventListener('click', () => {
                if (searchInput) {
                    searchInput.value = '';
                }
                clearSearchBtn.classList.add('hidden');
                this.filters.search = '';
                this.applyFilters();
            });

            // Filtro por categoría
            const categoryFilter = document.getElementById('category-filter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', (e) => {
                    this.filters.category = e.target.value;
                    this.applyFilters();
                });
            }

            // Filtro de ordenamiento
            const sortFilter = document.getElementById('sort-filter');
            if (sortFilter) {
                sortFilter.addEventListener('change', (e) => {
                    this.filters.sort = e.target.value;
                    this.applyFilters();
                });
            }

            // Botón limpiar filtros
            const clearBtn = document.getElementById('clear-filters');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => this.clearFilters());
            }

            // Atajo de teclado: Escape para limpiar búsqueda
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.filters.search) {
                    if (searchInput) searchInput.value = '';
                    clearSearchBtn?.classList.add('hidden');
                    this.filters.search = '';
                    this.applyFilters();
                }
            });
        }

        setupViewToggle() {
            const gridViewBtn = document.getElementById('grid-view');
            const listViewBtn = document.getElementById('list-view');

            if (!gridViewBtn || !listViewBtn) return;

            gridViewBtn.addEventListener('click', () => {
                this.currentView = 'grid';
                localStorage.setItem('coursesView', 'grid');
                this.restoreView();
            });

            listViewBtn.addEventListener('click', () => {
                this.currentView = 'list';
                localStorage.setItem('coursesView', 'list');
                this.restoreView();
            });
        }

        restoreView() {
            const gridViewBtn = document.getElementById('grid-view');
            const listViewBtn = document.getElementById('list-view');
            const gridContainer = document.getElementById('grid-view-container');
            const listContainer = document.getElementById('list-view-container');

            if (this.currentView === 'list') {
                listViewBtn?.classList.add('bg-blue-50', 'text-blue-600');
                listViewBtn?.classList.remove('text-gray-400');
                gridViewBtn?.classList.remove('bg-blue-50', 'text-blue-600');
                gridViewBtn?.classList.add('text-gray-400');

                gridContainer?.classList.add('hidden');
                listContainer?.classList.remove('hidden');
            } else {
                gridViewBtn?.classList.add('bg-blue-50', 'text-blue-600');
                gridViewBtn?.classList.remove('text-gray-400');
                listViewBtn?.classList.remove('bg-blue-50', 'text-blue-600');
                listViewBtn?.classList.add('text-gray-400');

                listContainer?.classList.add('hidden');
                gridContainer?.classList.remove('hidden');
            }
        }

        loadInitialFilters() {
            const urlParams = new URLSearchParams(window.location.search);

            const categoryParam = urlParams.get('category');
            if (categoryParam) {
                this.filters.category = categoryParam;
                const categoryFilter = document.getElementById('category-filter');
                if (categoryFilter) categoryFilter.value = categoryParam;
            }

            const searchParam = urlParams.get('search');
            if (searchParam) {
                this.filters.search = searchParam;
                const searchInput = document.getElementById('search-input');
                if (searchInput) searchInput.value = searchParam;
                document.getElementById('clear-search-btn')?.classList.remove('hidden');
            }

            const sortParam = urlParams.get('sort');
            if (sortParam) {
                this.filters.sort = sortParam;
                const sortFilter = document.getElementById('sort-filter');
                if (sortFilter) sortFilter.value = sortParam;
            }

            this.updateActiveFilters();
        }

        async applyFilters() {
            if (this.isLoading) return;
            this.isLoading = true;
            this.updateURL();
            this.updateActiveFilters();
            await this.filterCourses();
            this.isLoading = false;
        }

        async filterCourses() {
            const coursesContainer = document.getElementById('courses-container');
            if (!coursesContainer) return;

            try {
                const params = new URLSearchParams();
                if (this.filters.search)    params.append('search', this.filters.search);
                if (this.filters.category)  params.append('category', this.filters.category);
                if (this.filters.sort)      params.append('sort', this.filters.sort);

                this.showLoading();

                const baseUrl = '{{ isset($code) ? route("cursos", ["code" => $code]) : route("cursos") }}';
                const response = await fetch(`${baseUrl}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                });

                if (!response.ok) throw new Error('Servidor falló al responder');

                const html = await response.text();
                coursesContainer.innerHTML = html;

                this.updateResultsCount();
                this.restoreView();
            } catch (error) {
                console.error('Error filtering courses:', error);
                this.showError();
            }
        }

        updateResultsCount() {
            const paginationInfo = document.getElementById('pagination-info');
            const total = paginationInfo ? parseInt(paginationInfo.dataset.total) : 0;

            const resultsCount = document.getElementById('results-count');
            if (resultsCount) {
                resultsCount.textContent = `${total} ${total === 1 ? 'curso encontrado' : 'cursos encontrados'}`;
            }

            const filterDescription = document.getElementById('filter-description');
            this.updateFilterDescription(filterDescription);
        }

        updateFilterDescription(el) {
            if (!el) return;

            let parts = [];
            if (this.filters.search) parts.push(`búsqueda "${this.filters.search}"`);

            if (this.filters.category) {
                const catFilter = document.getElementById('category-filter');
                const catName = catFilter?.selectedOptions[0]?.text;
                if (catName && catName !== 'Todas las categorías') parts.push(`categoría "${catName}"`);
            }

            if (parts.length > 0) {
                el.textContent = `Resultados para ${parts.join(' y ')}`;
            } else {
                el.textContent = 'Mostrando todos los cursos disponibles';
            }
        }

        updateActiveFilters() {
            const container = document.getElementById('active-filters');
            const clearFiltersBtn = document.getElementById('clear-filters');
            if (!container) return;

            container.innerHTML = '';
            let hasFilters = false;

            if (this.filters.search) {
                this.addActiveFilterChip('search', `Búsqueda: "${this.filters.search}"`, container);
                hasFilters = true;
            }

            if (this.filters.category) {
                const catFilter = document.getElementById('category-filter');
                const catName = catFilter?.selectedOptions[0]?.text;
                if (catName && catName !== 'Todas las categorías') {
                    this.addActiveFilterChip('category', `Categoría: ${catName}`, container);
                    hasFilters = true;
                }
            }

            container.classList.toggle('hidden', !hasFilters);
            clearFiltersBtn?.classList.toggle('hidden', !hasFilters);
        }

        addActiveFilterChip(type, text, container) {
            const chip = document.createElement('div');
            chip.className = 'inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full';
            chip.innerHTML = `
                <span>${text}</span>
                <button type="button" onclick="coursesPage.removeFilter('${type}')" class="text-blue-500 hover:text-blue-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            container.appendChild(chip);
        }

        removeFilter(type) {
            if (type === 'search') {
                this.filters.search = '';
                const searchInput = document.getElementById('search-input');
                if (searchInput) searchInput.value = '';
                document.getElementById('clear-search-btn')?.classList.add('hidden');
            } else if (type === 'category') {
                this.filters.category = '';
                const categoryFilter = document.getElementById('category-filter');
                if (categoryFilter) categoryFilter.value = '';
            }
            this.applyFilters();
        }

        clearFilters() {
            this.filters.search = '';
            this.filters.category = '';
            this.filters.sort = 'newest';

            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const sortFilter = document.getElementById('sort-filter');

            if (searchInput) searchInput.value = '';
            if (categoryFilter) categoryFilter.value = '';
            if (sortFilter) sortFilter.value = 'newest';

            document.getElementById('clear-search-btn')?.classList.add('hidden');

            this.applyFilters();
        }

        updateURL() {
            const params = new URLSearchParams();
            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.category) params.append('category', this.filters.category);
            if (this.filters.sort && this.filters.sort !== 'newest') params.append('sort', this.filters.sort);

            const baseUrl = '{{ isset($code) ? route("cursos", ["code" => $code]) : route("cursos") }}';
            const newURL = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
            window.history.replaceState({}, '', newURL);
        }

        showLoading() {
            const container = document.getElementById('courses-container');
            if (container) {
                container.innerHTML = `
                    <div class="flex flex-col justify-center items-center py-20">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                        <p class="text-gray-500 text-sm">Cargando cursos...</p>
                    </div>
                `;
            }
        }

        showError() {
            const container = document.getElementById('courses-container');
            if (container) {
                container.innerHTML = `
                    <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm max-w-md mx-auto">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 rounded-full mb-4">
                            <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.082 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Ocurrió un error</h3>
                        <p class="text-gray-500 text-sm mb-6">No se pudo procesar la solicitud. Por favor intenta recargar la página.</p>
                        <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                            Recargar página
                        </button>
                    </div>
                `;
            }
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

    document.addEventListener('DOMContentLoaded', function() {
        window.coursesPage = new CoursesPage();
    });

    // Función global para agregar al carrito
    async function addToCart(courseId, event) {
        const btn = event?.currentTarget ?? event?.target;
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-spin inline-block">⏳</span>';
        }

        try {
            const response = await fetch(`/cart/add/${courseId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification('✓ Curso agregado al carrito', 'success');
                updateCartCount();
            } else if (data.success === false) {
                showNotification('El Curso ya se encuentra agregado en el carrito', 'error');
            } else {
                throw new Error(data.message || 'Error al agregar el curso');
            }
        } catch (error) {
            console.error('Error:', error);
            if (error.message.includes('401') || error.message.includes('Unauthenticated')) {
                showNotification('Debes iniciar sesión para agregar cursos al carrito', 'warning');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showNotification('Error al agregar el curso al carrito', 'error');
            }
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    ${btn.classList.contains('text-xs') ? 'Agregar' : 'Agregar al carrito'}
                `;
            }
        }
    }

    function showNotification(message, type = 'info') {
        const existing = document.querySelectorAll('.custom-notification');
        existing.forEach(n => n.remove());

        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-slide-in-right flex items-center gap-3 max-w-md`;
        notification.innerHTML = `
            <span class="text-sm font-semibold">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-white/80 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('animate-fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    async function updateCartCount() {
        try {
            const response = await fetch('/api/cart/count', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            const cartCount = document.getElementById('cart-count');
            if (cartCount && data.count !== undefined) {
                cartCount.textContent = data.count;
                cartCount.classList.add('animate-bounce');
                setTimeout(() => cartCount.classList.remove('animate-bounce'), 500);
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }
</script>

<style>
    /* Keyframe Animations */
    @keyframes slide-in-right {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fade-out {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out;
    }

    .animate-fade-out {
        animation: fade-out 0.3s ease-out forwards;
    }

    .animate-spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
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
</style>
@endsection
