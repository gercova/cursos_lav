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
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $categories->total() }}</p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Activas</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $categories->where('is_active', true)->count() }}</p>
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
                        <p class="text-2xl font-bold text-red-900 mt-1">{{ $categories->where('is_active', false)->count() }}</p>
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
                        <p class="text-2xl font-bold text-purple-900 mt-1">{{ $categories->where('created_at', '>=', now()->subWeek())->count() }}</p>
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
                               x-model="searchQuery"
                               @input.debounce.500ms="performSearch()"
                               placeholder="Buscar categorías..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    </div>

                    <div class="flex gap-2">
                        <select x-model="statusFilter"
                                @change="performSearch()"
                                class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            <option value="">Todos</option>
                            <option value="1">Activas</option>
                            <option value="0">Inactivas</option>
                        </select>

                        <button @click="resetFilters()"
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla mejorada -->
        <div class="overflow-x-auto" x-show="!loading">
            @if($categories->isEmpty())
                <!-- Estado vacío mejorado -->
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay categorías aún</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza organizando tus cursos creando tu primera categoría.</p>
                    <x-modal title="Crear Primera Categoría">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Primera Categoría
                            </button>
                        </x-slot>
                        @include('admin.categories.partials.form')
                    </x-modal>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span>Nombre</span>
                                    <button @click="sortBy('name')" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Cursos
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider text-right">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-white transition-all duration-200 group"
                                id="category-row-{{ $category->id }}">
                                <!-- Nombre -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                <span class="text-blue-700 font-bold text-lg">
                                                    {{ strtoupper(substr($category->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $category->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $category->slug }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Descripción -->
                                <td class="px-6 py-5">
                                    <div class="text-sm text-gray-600 max-w-xs truncate">
                                        {{ $category->description ?? 'Sin descripción' }}
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-5">
                                    <div class="inline-flex items-center gap-2">
                                        <span :class="{'bg-gradient-to-r from-green-100 to-green-200 text-green-800': {{ $category->is_active }}, 'bg-gradient-to-r from-red-100 to-red-200 text-red-800': !{{ $category->is_active }}}"
                                              class="px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                        <button onclick="toggleStatus({{ $category->id }})"
                                                class="text-gray-400 hover:text-gray-600 transition-colors"
                                                title="{{ $category->is_active ? 'Desactivar' : 'Activar' }}">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                @if($category->is_active)
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-10 10a1 1 0 01-1.414-1.414l10-10a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                                <!-- Cursos (puedes agregar esta relación si existe) -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $category->courses_count ?? 0 }}
                                        </span>
                                        <span class="text-xs text-gray-500">cursos</span>
                                    </div>
                                </td>

                                <!-- Fecha -->
                                <td class="px-6 py-5">
                                    <div class="text-sm text-gray-600">
                                        {{ $category->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $category->created_at->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Editar -->
                                        <x-modal title="Editar Categoría">
                                            <x-slot name="trigger">
                                                <button class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200 group/edit"
                                                        title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            @include('admin.categories.partials.form', ['category' => $category])
                                        </x-modal>

                                        <!-- Eliminar -->
                                        <button onclick="deleteCategory({{ $category->id }})"
                                                class="p-2 text-red-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 rounded-lg transition-all duration-200 group/delete"
                                                title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <!-- Ver detalles -->
                                        <button onclick="viewCategory({{ $category->id }})"
                                                class="p-2 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-gray-600 rounded-lg transition-all duration-200 group/view"
                                                title="Ver detalles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="py-16 text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Cargando categorías...</p>
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
    /*function categoryManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            loading: false,

            init() {
                // Escuchar eventos
                this.setupEventListeners();
            },

            setupEventListeners() {
                window.addEventListener('category-saved', (e) => {
                    this.handleCategorySaved(e.detail);
                });

                window.addEventListener('category-updated', (e) => {
                    this.handleCategoryUpdated(e.detail);
                });

                window.addEventListener('category-deleted', (e) => {
                    this.handleCategoryDeleted(e.detail);
                });
            },

            // Función modificada para recibir parámetros adicionales
            async submitForm(event, modalComponent = null, isSubmittingState = null) {
                event.preventDefault();

                const form = event.target;

                // Si se pasó el estado de submitting, actualizarlo
                if (isSubmittingState) {
                    isSubmittingState.isSubmitting = true;
                }

                try {
                    const formData = new FormData(form);

                    // Determinar URL y método
                    let url = form.action;
                    let method = form.method;

                    // Si no tiene action, usar la ruta store
                    if (!url) {
                        url = '{{ route("admin.categories.store") }}';
                        method = 'POST';
                    }

                    // Si es un formulario de edición pero no tiene method="PUT", agregarlo
                    if (form.querySelector('input[name="_method"]')) {
                        const methodInput = form.querySelector('input[name="_method"]');
                        method = methodInput.value;
                    }

                    const response = await axios({
                        method: method,
                        url: url,
                        data: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    // Cerrar modal si existe
                    if (modalComponent) {
                        const modalData = modalComponent.__x.$data;
                        if (modalData && typeof modalData.open !== 'undefined') {
                            modalData.open = false;
                        }
                    }

                    // Resetear estado de submitting
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    // Determinar si es creación o actualización
                    const isCreation = method === 'POST';

                    // Mostrar notificación
                    this.showNotification(
                        isCreation ? 'Categoría creada exitosamente' : 'Categoría actualizada exitosamente',
                        'success'
                    );

                    // Emitir evento correspondiente
                    const eventName = isCreation ? 'category-saved' : 'category-updated';
                    window.dispatchEvent(new CustomEvent(eventName, {
                        detail: response.data.category
                    }));

                    // Recargar la página después de un breve delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);

                } catch (error) {
                    // Resetear estado de submitting en caso de error
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    if (error.response && error.response.status === 422) {
                        // Manejar errores de validación
                        this.showValidationErrors(error.response.data.errors);
                        this.showNotification('Por favor corrige los errores en el formulario', 'error');
                    } else {
                        console.error('Error:', error);
                        this.showNotification('Error al guardar la categoría', 'error');
                    }
                }
            },

            showValidationErrors(errors) {
                // Limpiar errores anteriores
                document.querySelectorAll('.text-red-600').forEach(el => el.remove());

                // Mostrar nuevos errores
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        // Crear elemento de error
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600';
                        errorDiv.textContent = messages[0];

                        // Insertar después del input
                        input.parentNode.appendChild(errorDiv);

                        // Resaltar el input con error
                        input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        input.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                        // Remover clases de error cuando el usuario empiece a escribir
                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            this.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                            // Remover mensaje de error
                            const errorMsg = this.parentNode.querySelector('.text-red-600');
                            if (errorMsg) {
                                errorMsg.remove();
                            }
                        }, { once: true });
                    }
                }
            },

            // ... resto de las funciones se mantienen igual ...
            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);

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
                this.performSearch();
            },

            handleCategorySaved(category) {
                this.showNotification('Categoría creada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryUpdated(category) {
                this.showNotification('Categoría actualizada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryDeleted(categoryId) {
                const row = document.getElementById(`category-row-${categoryId}`);
                if (row) {
                    row.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => {
                        row.remove();
                        this.showNotification('Categoría eliminada exitosamente', 'success');
                        this.updateStats();
                    }, 300);
                }
            },

            showNotification(message, type = 'success') {
                // Crear notificación flotante
                const notification = document.createElement('div');
                notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
                    type === 'success'
                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                    : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Animar entrada
                setTimeout(() => {
                    notification.classList.add('translate-y-0', 'opacity-100');
                }, 10);

                // Remover después de 3 segundos
                setTimeout(() => {
                    notification.classList.remove('translate-y-0', 'opacity-100');
                    notification.classList.add('-translate-y-2', 'opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            },

            updateStats() {
                // Podrías hacer una petición AJAX para actualizar estadísticas
            }
        };
    }*/

    /*function categoryManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            loading: false,

            init() {
                // Escuchar eventos
                this.setupEventListeners();
            },

            setupEventListeners() {
                window.addEventListener('category-saved', (e) => {
                    this.handleCategorySaved(e.detail);
                });

                window.addEventListener('category-updated', (e) => {
                    this.handleCategoryUpdated(e.detail);
                });

                window.addEventListener('category-deleted', (e) => {
                    this.handleCategoryDeleted(e.detail);
                });
            },

            // Función para manejar el submit del formulario
            async submitForm(event, modalComponent = null, isSubmittingState = null) {
                event.preventDefault();

                const form = event.target;

                // Si se pasó el estado de submitting, actualizarlo
                if (isSubmittingState) {
                    isSubmittingState.isSubmitting = true;
                }

                try {
                    const formData = new FormData(form);

                    // Determinar URL y método
                    let url = form.getAttribute('action');
                    let method = form.getAttribute('method') || 'POST';

                    // Si no tiene action, usar la ruta store
                    if (!url) {
                        url = '{{ route("admin.categories.store") }}';
                        method = 'POST';
                    }

                    const response = await axios({
                        method: method,
                        url: url,
                        data: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });

                    // Cerrar modal si existe
                    if (modalComponent && modalComponent.open !== undefined) {
                        modalComponent.open = false;
                    }

                    // Resetear estado de submitting
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    // Determinar si es creación o actualización
                    const isCreation = method === 'POST' || method.toLowerCase() === 'post';

                    // Mostrar notificación
                    this.showNotification(
                        isCreation ? 'Categoría creada exitosamente' : 'Categoría actualizada exitosamente',
                        'success'
                    );

                    // Emitir evento correspondiente
                    const eventName = isCreation ? 'category-saved' : 'category-updated';
                    window.dispatchEvent(new CustomEvent(eventName, {
                        detail: response.data.category || response.data
                    }));

                    // Recargar la página después de un breve delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);

                } catch (error) {
                    // Resetear estado de submitting en caso de error
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    if (error.response && error.response.status === 422) {
                        // Manejar errores de validación
                        this.showValidationErrors(error.response.data.errors);
                        this.showNotification('Por favor corrige los errores en el formulario', 'error');
                    } else {
                        console.error('Error:', error);
                        this.showNotification('Error al guardar la categoría', 'error');
                    }
                }
            },

            // ... resto de tus funciones siguen igual ...
            showValidationErrors(errors) {
                // Limpiar errores anteriores
                document.querySelectorAll('.text-red-600').forEach(el => el.remove());

                // Mostrar nuevos errores
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        // Crear elemento de error
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600';
                        errorDiv.textContent = messages[0];

                        // Insertar después del input
                        input.parentNode.appendChild(errorDiv);

                        // Resaltar el input con error
                        input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        input.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                        // Remover clases de error cuando el usuario empiece a escribir
                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            this.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                            // Remover mensaje de error
                            const errorMsg = this.parentNode.querySelector('.text-red-600');
                            if (errorMsg) {
                                errorMsg.remove();
                            }
                        }, { once: true });
                    }
                }
            },

            // ... resto de tus funciones ...
            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);

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
                this.performSearch();
            },

            handleCategorySaved(category) {
                this.showNotification('Categoría creada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryUpdated(category) {
                this.showNotification('Categoría actualizada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryDeleted(categoryId) {
                const row = document.getElementById(`category-row-${categoryId}`);
                if (row) {
                    row.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => {
                        row.remove();
                        this.showNotification('Categoría eliminada exitosamente', 'success');
                        this.updateStats();
                    }, 300);
                }
            },

            showNotification(message, type = 'success') {
                // Crear notificación flotante
                const notification = document.createElement('div');
                notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
                    type === 'success'
                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                    : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Animar entrada
                setTimeout(() => {
                    notification.classList.add('translate-y-0', 'opacity-100');
                }, 10);

                // Remover después de 3 segundos
                setTimeout(() => {
                    notification.classList.remove('translate-y-0', 'opacity-100');
                    notification.classList.add('-translate-y-2', 'opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            },

            updateStats() {
                // Podrías hacer una petición AJAX para actualizar estadísticas
            }
        };
    }*/

    /*function categoryManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            loading: false,

            init() {
                // Escuchar eventos
                this.setupEventListeners();
            },

            setupEventListeners() {
                window.addEventListener('category-saved', (e) => {
                    this.handleCategorySaved(e.detail);
                });

                window.addEventListener('category-updated', (e) => {
                    this.handleCategoryUpdated(e.detail);
                });

                window.addEventListener('category-deleted', (e) => {
                    this.handleCategoryDeleted(e.detail);
                });
            },

            // Función para manejar el submit del formulario
            async submitForm(event, modalComponent = null, isSubmittingState = null) {
                event.preventDefault();

                const form = event.target;

                // Si se pasó el estado de submitting, actualizarlo
                if (isSubmittingState) {
                    isSubmittingState.isSubmitting = true;
                }

                try {
                    const formData = new FormData(form);

                    // Obtener la URL y método del formulario
                    const action = form.getAttribute('action');
                    const method = form.getAttribute('method') || 'POST';

                    const response = await axios({
                        method: method,
                        url: action,
                        data: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });

                    // Cerrar modal si existe
                    if (modalComponent && typeof modalComponent.close === 'function') {
                        modalComponent.open = false;
                    } else {
                        // Si no se puede acceder directamente al modal, buscarlo
                        const modal = document.querySelector('[x-data*="modal()"]');
                        if (modal && modal.__x && modal.__x.$data) {
                            modal.__x.$data.open = false;
                        }
                    }

                    // Resetear estado de submitting
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    // Determinar si es creación o actualización
                    const isCreation = method.toUpperCase() === 'POST';

                    // Mostrar notificación
                    this.showNotification(
                        isCreation ? 'Categoría creada exitosamente' : 'Categoría actualizada exitosamente',
                        'success'
                    );

                    // Emitir evento correspondiente
                    const eventName = isCreation ? 'category-saved' : 'category-updated';
                    window.dispatchEvent(new CustomEvent(eventName, {
                        detail: response.data.category || response.data
                    }));

                    // Recargar la página después de un breve delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);

                } catch (error) {
                    // Resetear estado de submitting en caso de error
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    if (error.response && error.response.status === 422) {
                        // Manejar errores de validación
                        this.showValidationErrors(error.response.data.errors);
                        this.showNotification('Por favor corrige los errores en el formulario', 'error');
                    } else {
                        console.error('Error:', error);
                        this.showNotification('Error al guardar la categoría', 'error');
                    }
                }
            },

            // ... resto de tus funciones siguen igual ...
            showValidationErrors(errors) {
                // Limpiar errores anteriores
                document.querySelectorAll('.text-red-600').forEach(el => el.remove());

                // Mostrar nuevos errores
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        // Crear elemento de error
                        const errorDiv = document.createElement('p');
                        errorDiv.className = 'mt-1 text-sm text-red-600';
                        errorDiv.textContent = messages[0];

                        // Insertar después del input
                        input.parentNode.appendChild(errorDiv);

                        // Resaltar el input con error
                        input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        input.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                        // Remover clases de error cuando el usuario empiece a escribir
                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            this.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                            // Remover mensaje de error
                            const errorMsg = this.parentNode.querySelector('.text-red-600');
                            if (errorMsg) {
                                errorMsg.remove();
                            }
                        }, { once: true });
                    }
                }
            },

            // ... resto de tus funciones ...
            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);

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
                this.performSearch();
            },

            handleCategorySaved(category) {
                this.showNotification('Categoría creada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryUpdated(category) {
                this.showNotification('Categoría actualizada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryDeleted(categoryId) {
                const row = document.getElementById(`category-row-${categoryId}`);
                if (row) {
                    row.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => {
                        row.remove();
                        this.showNotification('Categoría eliminada exitosamente', 'success');
                        this.updateStats();
                    }, 300);
                }
            },

            showNotification(message, type = 'success') {
                // Crear notificación flotante
                const notification = document.createElement('div');
                notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
                    type === 'success'
                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                    : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Animar entrada
                setTimeout(() => {
                    notification.classList.add('translate-y-0', 'opacity-100');
                }, 10);

                // Remover después de 3 segundos
                setTimeout(() => {
                    notification.classList.remove('translate-y-0', 'opacity-100');
                    notification.classList.add('-translate-y-2', 'opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            },

            updateStats() {
                // Podrías hacer una petición AJAX para actualizar estadísticas
            }
        };
    }*/

    function categoryManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            loading: false,

            init() {
                // Escuchar eventos de modal
                window.addEventListener('modal-submit', (e) => {
                    this.handleModalSubmit(e.detail);
                });

                // Escuchar otros eventos
                this.setupEventListeners();
            },

            setupEventListeners() {
                window.addEventListener('category-saved', (e) => {
                    this.handleCategorySaved(e.detail);
                });

                window.addEventListener('category-updated', (e) => {
                    this.handleCategoryUpdated(e.detail);
                });

                window.addEventListener('category-deleted', (e) => {
                    this.handleCategoryDeleted(e.detail);
                });
            },

            async handleModalSubmit(detail) {
                const { formData, form, isSubmittingState, closeModal } = detail;

                // Activar estado de submitting
                if (isSubmittingState) {
                    isSubmittingState.isSubmitting = true;
                }

                try {
                    const action = form.getAttribute('action');
                    const method = form.getAttribute('method') || 'POST';

                    const response = await axios({
                        method: method,
                        url: action,
                        data: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });

                    // Cerrar modal
                    closeModal();

                    // Resetear estado de submitting
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    // Determinar si es creación o actualización
                    const isCreation = method.toUpperCase() === 'POST';

                    // Mostrar notificación
                    this.showNotification(
                        isCreation ? 'Categoría creada exitosamente' : 'Categoría actualizada exitosamente',
                        'success'
                    );

                    // Emitir evento correspondiente
                    const eventName = isCreation ? 'category-saved' : 'category-updated';
                    window.dispatchEvent(new CustomEvent(eventName, {
                        detail: response.data.category || response.data
                    }));

                    // Recargar la página después de un breve delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);

                } catch (error) {
                    // Resetear estado de submitting en caso de error
                    if (isSubmittingState) {
                        isSubmittingState.isSubmitting = false;
                    }

                    if (error.response && error.response.status === 422) {
                        this.showValidationErrors(error.response.data.errors);
                        this.showNotification('Por favor corrige los errores en el formulario', 'error');
                    } else {
                        console.error('Error:', error);
                        this.showNotification('Error al guardar la categoría', 'error');
                    }
                }
            },

            showValidationErrors(errors) {
                // Limpiar errores anteriores
                document.querySelectorAll('.text-red-600').forEach(el => el.remove());

                // Mostrar nuevos errores
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        const errorDiv          = document.createElement('p');
                        errorDiv.className      = 'mt-1 text-sm text-red-600';
                        errorDiv.textContent    = messages[0];
                        input.parentNode.appendChild(errorDiv);

                        input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        input.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            this.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
                            const errorMsg = this.parentNode.querySelector('.text-red-600');
                            if (errorMsg) errorMsg.remove();
                        }, { once: true });
                    }
                }
            },

            // ... resto de tus funciones ...
            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);

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
                this.performSearch();
            },

            handleCategorySaved(category) {
                this.showNotification('Categoría creada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryUpdated(category) {
                this.showNotification('Categoría actualizada exitosamente', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },

            handleCategoryDeleted(categoryId) {
                const row = document.getElementById(`category-row-${categoryId}`);
                if (row) {
                    row.classList.add('opacity-0', 'translate-x-4');
                    setTimeout(() => {
                        row.remove();
                        this.showNotification('Categoría eliminada exitosamente', 'success');
                        this.updateStats();
                    }, 300);
                }
            },

            showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
                    type === 'success'
                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                    : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                            }
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);
                setTimeout(() => notification.classList.add('translate-y-0', 'opacity-100'), 10);

                setTimeout(() => {
                    notification.classList.remove('translate-y-0', 'opacity-100');
                    notification.classList.add('-translate-y-2', 'opacity-0');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            },

            updateStats() {
                // Podrías hacer una petición AJAX para actualizar estadísticas
            }
        };
    }

    // Función para eliminar categoría
    async function deleteCategory(categoryId) {
        if (!confirm('¿Estás seguro de eliminar esta categoría? Esta acción no se puede deshacer.')) {
            return;
        }

        try {
            const response = await axios.delete(`{{ route('admin.categories.destroy', '') }}/${categoryId}`);
            window.dispatchEvent(new CustomEvent('category-deleted', {
                detail: categoryId
            }));
        } catch (error) {
            alert('Error al eliminar la categoría');
        }
    }

    // Función para cambiar estado
    async function toggleStatus(categoryId) {
        if (!confirm('¿Cambiar estado de la categoría?')) {
            return;
        }

        try {
            // /admin/categories/{category}/toggle-status
            const response = await axios.post(`{{ route(['admin.categories.toggleStatus', '']) }}/${categoryId}`);
            if (response.data.success) {
                // Recargar para ver cambios
                window.location.reload();
            }
        } catch (error) {
            alert('Error al cambiar el estado');
        }
    }

    // Función para ver detalles
    function viewCategory(categoryId) {
        console.log('Ver categoría:', categoryId);
        // Implementar vista de detalles si la necesitas
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
