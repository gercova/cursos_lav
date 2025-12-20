@extends('layouts.admin')
@section('title', 'Gestión de Categorías')
@section('content')
<div class="container mx-auto px-4 py-6" x-data="categoryManager()" x-init="init()">
    <!-- Header con estadísticas -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Categorías</h1>
                <p class="text-gray-600 mt-2">Gestiona las categorías de tus cursos</p>
            </div>

            <!-- Botón para abrir modal de creación -->
            <x-modal title="Nueva Categoría">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nueva Categoría
                    </button>
                </x-slot>
                @include('admin.categories.partials.form')
            </x-modal>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1" data-stat="total">
                            {{ $categories->total() }}
                        </p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Activas</p>
                        <p class="text-2xl font-bold text-green-900 mt-1" data-stat="active">
                            {{ $categories->where('is_active', true)->count() }}
                        </p>
                    </div>
                    <div class="bg-green-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-800">Inactivas</p>
                        <p class="text-2xl font-bold text-red-900 mt-1" data-stat="inactive">
                            {{ $categories->where('is_active', false)->count() }}
                        </p>
                    </div>
                    <div class="bg-red-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">Última semana</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1" data-stat="recent">
                            {{ $categories->where('created_at', '>=', now()->subWeek())->count() }}
                        </p>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel principal -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Header del panel -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800">Lista de Categorías</h2>
                    <p class="text-sm text-gray-600 mt-1">Administra todas las categorías del sistema</p>
                </div>

                <!-- Filtros y búsqueda mejorados -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text"
                            name="search"
                            id="searchCategory"
                            x-model="searchQuery"
                            @input.debounce.500ms="performSearch()"
                            placeholder="Buscar categorías..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    </div>

                    <div class="flex gap-2">
                        <select x-model="statusFilter" @change="performSearch()" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            <option value="">Todos</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                        </select>


                        <button @click="resetFilters()"
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="py-16 text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Cargando categorías...</p>
        </div>

        <!-- Tabla Container -->
        <div id="categories-table-container" class="overflow-x-auto" x-show="!loading">
            @if($categories->isEmpty())
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay categorías aún</h3>
                    <p class="text-gray-500 mb-6">Comienza organizando tus cursos creando tu primera categoría.</p>
                    <button onclick="openModal('createCategoryModal')" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i> Crear Primera Categoría
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cursos</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 transition duration-150" data-category-id="{{ $category->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md">
                                                <span class="text-white font-bold text-lg">
                                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $category->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">
                                                {{ $category->slug }}
                                            </div>
                                        </div>
                                        @if($category->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Activa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Inactiva
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">
                                        {{ $category->description ?? 'Sin descripción' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                                        </svg>
                                        <span class="text-sm font-semibold text-blue-900">
                                            {{ $category->courses_count ?? 0 }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $category->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $category->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <!-- Acciones -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div x-data="{ open: false }" class="relative flex items-center justify-end">
                                        <!-- Botón del menú (tres puntos) con estilo más refinado -->
                                        <button @click="open = !open"
                                                class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-300"
                                                title="Más opciones">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>

                                        <!-- Menú desplegable con mejor apariencia -->
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-20 overflow-hidden"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            style="display: none;"
                                        >
                                            <!-- Activar / Desactivar -->
                                            <button @click="toggleStatus({{ $category->id }}); open = false"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium
                                                    {{ $category->is_active
                                                        ? 'text-amber-600 hover:bg-amber-50'
                                                        : 'text-emerald-600 hover:bg-emerald-50' }}
                                                    transition-colors duration-150"
                                            >
                                                @if($category->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                    Desactivar
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Activar
                                                @endif
                                            </button>
                                            <!-- Editar -->
                                            <div class="px-4 py-0.5">
                                                <x-modal title="Editar Categoría">
                                                    <x-slot name="trigger">
                                                        <button @click="open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors duration-150">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            Editar
                                                        </button>
                                                    </x-slot>
                                                    @include('admin.categories.partials.form', ['category' => $category])
                                                </x-modal>
                                            </div>

                                            <!-- Eliminar -->
                                            <button @click="deleteCategory({{ $category->id }}); open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Paginación mejorada -->
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $categories->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $categories->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $categories->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center space-x-2">
                        @if($categories->onFirstPage())
                            <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $categories->previousPageUrl() }}"
                               class="px-3 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        @foreach($categories->getUrlRange(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)) as $page => $url)
                            @if($page == $categories->currentPage())
                                <span class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition duration-200">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if($categories->hasMorePages())
                            <a href="{{ $categories->nextPageUrl() }}"
                               class="px-3 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function categoryManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            loading: false,
            debounceTimer: null,

            // Inicialización
            init() {
                this.setupEventListeners();
                //this.restoreFiltersFromURL();
                console.log('CategoryManager inicializado');
            },

            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);
                    if (this.categoryFilter) params.append('category', this.categoryFilter);

                    const url = `{{ route('admin.categories.index') }}?${params.toString()}`;
                    window.location.href = url;
                } catch (error) {
                    console.error('Error en búsqueda:', error);
                    this.loading = false;
                }
            },

            resetFilters() {
                this.searchQuery = '';
                this.statusFilter = '';
                this.categoryFilter = '';
                this.performSearch();
            },

            // Actualizar estadísticas
            async updateStats() {
                try {
                    const response = await axios.get(`${API_URL}/admin/categories/stats`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (response.data) {
                        this.updateStatsUI(response.data);
                    }
                } catch (error) {
                    console.log('Stats no disponibles');
                }
            },

            // Actualizar UI de estadísticas
            updateStatsUI(stats) {
                const elements = {
                    total: document.querySelector('[data-stat="total"]'),
                    active: document.querySelector('[data-stat="active"]'),
                    inactive: document.querySelector('[data-stat="inactive"]'),
                    recent: document.querySelector('[data-stat="recent"]')
                };

                if (elements.total && stats.total !== undefined) {
                    elements.total.textContent = stats.total;
                }
                if (elements.active && stats.active !== undefined) {
                    elements.active.textContent = stats.active;
                }
                if (elements.inactive && stats.inactive !== undefined) {
                    elements.inactive.textContent = stats.inactive;
                }
                if (elements.recent && stats.recent !== undefined) {
                    elements.recent.textContent = stats.recent;
                }
            },

            // Resetear filtros
            /*resetFilters() {
                this.searchQuery = '';
                this.statusFilter = '';

                // Limpiar inputs del DOM
                const searchInput = document.getElementById('searchCategory');
                if (searchInput) searchInput.value = '';

                const statusSelect = document.querySelector('select[x-model="statusFilter"]');
                if (statusSelect) statusSelect.value = '';

                this.performSearch();
            },*/

            // Configurar listeners de eventos
            setupEventListeners() {
                // Listener para modal submit
                window.addEventListener('modal-submit', (e) => {
                    this.handleModalSubmit(e.detail);
                });

                // Listener para eventos de categorías
                window.addEventListener('category-saved', (e) => {
                    this.handleCategorySaved(e.detail);
                });

                window.addEventListener('category-updated', (e) => {
                    this.handleCategoryUpdated(e.detail);
                });

                window.addEventListener('category-deleted', (e) => {
                    this.handleCategoryDeleted(e.detail);
                });

                // Listener para popstate (botón atrás del navegador)
                window.addEventListener('popstate', () => {
                    this.restoreFiltersFromURL();
                    this.performSearch();
                });
            },

            // Manejar submit del modal
            async handleModalSubmit(detail) {
                const { formData, closeModal } = detail;

                try {
                    const dataObject = this.formDataToObject(formData);
                    const categoryId = dataObject.id;
                    const isEditing = !!categoryId;

                    const response = await axios({
                        method: isEditing ? 'PUT' : 'POST',
                        url: isEditing ? `${API_URL}/admin/categories/${categoryId}` : `${API_URL}/admin/categories/store`,
                        data: dataObject,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
                        }
                    });

                    closeModal();

                    this.showNotification(
                        isEditing ? 'Categoría actualizada exitosamente' : 'Categoría creada exitosamente',
                        'success'
                    );

                    window.dispatchEvent(new CustomEvent(
                        isEditing ? 'category-updated' : 'category-saved',
                        { detail: response.data.category || response.data }
                    ));

                    setTimeout(() => window.location.reload(), 1000);

                } catch (error) {
                    this.handleSubmitError(error);
                }
            },

            // Convertir FormData a objeto
            formDataToObject(formData) {
                const dataObject = {};
                formData.forEach((value, key) => {
                    dataObject[key] = value;
                });
                return dataObject;
            },

            // Manejar errores de submit
            handleSubmitError(error) {
                if (error.response?.status === 422) {
                    this.showValidationErrors(error.response.data.errors);
                    this.showNotification('Por favor corrige los errores en el formulario', 'error');
                } else {
                    console.error('Error completo:', error);
                    this.showNotification('Error al guardar la categoría', 'error');
                }
            },

            // Mostrar errores de validación
            showValidationErrors(errors) {
                // Limpiar errores anteriores
                document.querySelectorAll('.text-red-600').forEach(el => el.remove());
                document.querySelectorAll('.border-red-500').forEach(el => {
                    el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    el.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
                });

                // Mostrar nuevos errores
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        this.displayFieldError(input, messages[0]);
                    }
                }
            },

            // Mostrar error en campo específico
            displayFieldError(input, message) {
                const errorDiv          = document.createElement('p');
                errorDiv.className      = 'mt-1 text-sm text-red-600';
                errorDiv.textContent    = message;
                input.parentNode.appendChild(errorDiv);

                input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                input.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                input.addEventListener('input', function clearError() {
                    this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    this.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
                    const errorMsg = this.parentNode.querySelector('.text-red-600');
                    if (errorMsg) errorMsg.remove();
                    this.removeEventListener('input', clearError);
                });
            },

            // Handlers de eventos
            handleCategorySaved() {
                this.showNotification('Categoría creada exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            },

            handleCategoryUpdated() {
                this.showNotification('Categoría actualizada exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            },

            handleCategoryDeleted(categoryId) {
                const row = document.querySelector(`tr[data-category-id="${categoryId}"]`);
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';

                    setTimeout(() => {
                        row.remove();
                        this.showNotification('Categoría eliminada exitosamente', 'success');
                        this.updateStats();
                    }, 300);
                }
            },

            // Mostrar notificación
            showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                const bgClass = type === 'success'
                    ? 'bg-gradient-to-r from-green-500 to-green-600'
                    : 'bg-gradient-to-r from-red-500 to-red-600';

                notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${bgClass} text-white`;

                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                requestAnimationFrame(() => {
                    notification.style.opacity    = '1';
                    notification.style.transform  = 'translateY(0)';
                });

                setTimeout(() => {
                    notification.style.opacity    = '0';
                    notification.style.transform  = 'translateY(-20px)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        };
    }

    // Función para eliminar categoría
    async function deleteCategory(categoryId) {
        if (!confirm('¿Estás seguro de eliminar esta categoría?\n\nEsta acción no se puede deshacer.')) {
            return;
        }

        try {
            await axios.delete(`${API_URL}/admin/categories/${categoryId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
                }
            });

            window.dispatchEvent(new CustomEvent('category-deleted', {
                detail: categoryId
            }));

        } catch (error) {
            console.error('Error al eliminar:', error);
            alert('Error al eliminar la categoría. Por favor, intenta nuevamente.');
        }
    }

    // Función para cambiar estado
    async function toggleStatus(categoryId) {
        if (!confirm('¿Deseas cambiar el estado de esta categoría?')) {
            return;
        }

        try {
            const response = await axios.post(
                `${API_URL}/admin/categories/${categoryId}/toggle-status`,
                {},
                {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value
                    }
                }
            );

            if (response.data.success) {
                const categoryManager = Alpine.$data(document.querySelector('[x-data*="categoryManager"]'));
                if (categoryManager) {
                    categoryManager.showNotification('Estado actualizado correctamente', 'success');
                }
                setTimeout(() => window.location.reload(), 800);
            }

        } catch (error) {
            console.error('Error al cambiar estado:', error);
            alert('Error al cambiar el estado. Por favor, intenta nuevamente.');
        }
    }
</script>

<style>
    /* Estilos adicionales */
    [x-cloak] { display: none !important; }

    /* Animaciones personalizadas */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    /* Estilos para hover de botones */
    .group\/edit:hover svg {
        animation: bounce 0.3s;
    }

    .group\/delete:hover svg {
        animation: shake 0.3s;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-2px); }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-2px); }
        75% { transform: translateX(2px); }
    }
</style>
@endsection
