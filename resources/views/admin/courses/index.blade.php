@extends('layouts.admin')
@section('title', 'Gestión de Cursos')
@section('content')
<div class="container mx-auto px-4 py-6" x-data="courseManager()" x-init="init()">
    <!-- Header con estadísticas -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Cursos</h1>
                <p class="text-gray-600 mt-2">Gestiona todos los cursos de tu plataforma</p>
            </div>
            <!-- Botón para crear nuevo curso -->
            <a href="{{ route('admin.courses.create') }}"
               class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Curso
            </a>
        </div>
        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total de cursos -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Cursos</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $courses->total() }}</p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cursos activos -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Cursos Activos</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $courses->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="bg-green-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cursos en promoción -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">En Promoción</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">
                            {{ $courses->filter(fn($course) => $course->is_on_promotion)->count() }}
                        </p>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total estudiantes -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-800">Total Estudiantes</p>
                        <p class="text-2xl font-bold text-orange-900 mt-1">
                            {{ $courses->sum('students_count') }}
                        </p>
                    </div>
                    <div class="bg-orange-600 p-3 rounded-xl">
                        <i class="fa-solid fa-users"></i>
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
                    <h2 class="text-lg font-semibold text-gray-800">Todos los Cursos</h2>
                    <p class="text-sm text-gray-600 mt-1">Administra el contenido y configuración de cada curso</p>
                </div>

                <!-- Filtros y búsqueda -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.500ms="performSearch()"
                               placeholder="Buscar cursos..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    </div>

                    <div class="flex gap-2">
                        <select x-model="statusFilter"
                                @change="performSearch()"
                                class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            <option value="">Todos</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                            <option value="promotion">En promoción</option>
                        </select>

                        <select x-model="categoryFilter"
                                @change="performSearch()"
                                class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <button @click="resetFilters()"
                                class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de cursos -->
        <div class="overflow-x-auto" x-show="!loading">
            @if($courses->isEmpty())
                <!-- Estado vacío -->
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay cursos aún</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primer curso para la plataforma.</p>
                    <a href="{{ route('admin.courses.create') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Primer Curso
                    </a>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Curso
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Categoría
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Precio
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Estudiantes
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider text-right">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($courses as $course)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-white transition-all duration-200 group">
                                <!-- Información del curso -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <!-- Imagen del curso -->
                                        <div class="flex-shrink-0">
                                            @if($course->image_url)
                                                <img src="{{ Storage::url($course->image_url) }}"
                                                     alt="{{ $course->title }}"
                                                     class="w-16 h-16 rounded-xl object-cover border border-gray-200 shadow-sm">
                                            @else
                                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Detalles del curso -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <a href="#" class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate">
                                                    {{ $course->title }}
                                                </a>
                                                @if($course->is_on_promotion)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.2 6.5 10.266a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        PROMO
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-4 text-sm text-gray-500">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $course->duration }} horas
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                    {{ ucfirst($course->level) }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                    {{ $course->sections_count ?? 0 }} secciones
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Categoría -->
                                <td class="px-6 py-5">
                                    @if($course->category)
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100">
                                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $course->category->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Sin categoría</span>
                                    @endif
                                </td>

                                <!-- Precio -->
                                <td class="px-6 py-5">
                                    <div class="space-y-1">
                                        @if($course->is_on_promotion)
                                            <div class="text-sm font-bold text-gray-900">
                                                S/ {{ number_format($course->promotion_price, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-400 line-through">
                                                S/ {{ number_format($course->price, 2) }}
                                            </div>
                                        @else
                                            <div class="text-sm font-bold text-gray-900">
                                                S/ {{ number_format($course->price, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Estudiantes -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-gray-900">
                                                {{ $course->students_count }}
                                            </div>
                                            <div class="text-xs text-gray-500">estudiantes</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-2">
                                        <span :class="{'bg-gradient-to-r from-green-100 to-green-200 text-green-800': {{ $course->is_active }}, 'bg-gradient-to-r from-red-100 to-red-200 text-red-800': !{{ $course->is_active }}}"
                                              class="px-3 py-1 rounded-full text-xs font-semibold text-center">
                                            {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        <button onclick="toggleCourseStatus({{ $course->id }})"
                                                class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                                            {{ $course->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Ver detalles -->
                                        <!--<a href="#"
                                           class="p-2 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-gray-600 rounded-lg transition-all duration-200 group/view"
                                           title="Ver detalles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>-->

                                        <!-- Editar -->
                                        <a href="{{ route('admin.courses.edit', $course) }}"
                                           class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200 group/edit"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <!-- Gestión de secciones (modal) -->
                                        <!--<button onclick="showSectionsModal({{ $course->id }})"-->
                                        <a href="#"
                                            class="p-2 text-purple-600 hover:text-white hover:bg-gradient-to-r hover:from-purple-500 hover:to-purple-600 rounded-lg transition-all duration-200 group/sections"
                                            title="Gestionar secciones">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                            </svg>
                                        </a>
                                        <!--</button>-->

                                        <!-- Documentos -->
                                        <a href="#"
                                           class="p-2 text-green-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-green-600 rounded-lg transition-all duration-200 group/documents"
                                           title="Documentos">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>

                                        <!-- Eliminar -->
                                        <!--<button onclick="deleteCourse({{ $course->id }})"
                                                class="p-2 text-red-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 rounded-lg transition-all duration-200 group/delete"
                                                title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>-->
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
            <p class="text-gray-600">Cargando cursos...</p>
        </div>

        <!-- Paginación -->
        @if($courses->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $courses->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $courses->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $courses->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center space-x-2">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal para gestionar secciones -->
    <div x-data="{ showSections: false, courseId: null, sections: [], loadingSections: false }" x-cloak>
        <!-- Modal overlay -->
        <div x-show="showSections"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
            @click.self="showSections = false">

            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showSections"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">

                    <!-- Header del modal -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Gestionar Secciones</h3>
                                <p class="text-sm text-gray-600 mt-1" x-text="'Curso ID: ' + courseId"></p>
                            </div>
                            <button @click="showSections = false"
                                    class="p-2 hover:bg-gray-100 rounded-lg transition duration-200">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del modal -->
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        <!-- Formulario para agregar sección -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                            <h4 class="text-lg font-semibold text-blue-900 mb-3">Agregar Nueva Sección</h4>
                            <form @submit.prevent="addSection" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                                        <input type="text"
                                               x-model="newSectionTitle"
                                               required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                                        <input type="number"
                                               x-model="newSectionOrder"
                                               min="1"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <textarea x-model="newSectionDescription"
                                              rows="2"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-2 px-6 rounded-lg transition duration-200">
                                        Agregar Sección
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Lista de secciones -->
                        <div x-show="!loadingSections && sections.length > 0">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Secciones del Curso</h4>
                            <div class="space-y-4">
                                <template x-for="(section, index) in sections" :key="section.id">
                                    <div class="p-4 border border-gray-200 rounded-xl hover:border-blue-300 transition duration-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-3">
                                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 font-bold"
                                                      x-text="section.order"></span>
                                                <h5 class="font-medium text-gray-900" x-text="section.title"></h5>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button @click="editSection(section)"
                                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button @click="deleteSection(section.id)"
                                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 ml-11" x-text="section.description || 'Sin descripción'"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Sin secciones -->
                        <div x-show="!loadingSections && sections.length === 0" class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">No hay secciones</h4>
                            <p class="text-gray-500">Comienza agregando tu primera sección al curso</p>
                        </div>

                        <!-- Loading secciones -->
                        <div x-show="loadingSections" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600 mb-4"></div>
                            <p class="text-gray-600">Cargando secciones...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function courseManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            categoryFilter: '{{ request('category', '') }}',
            loading: false,

            init() {
                // Inicializar
            },

            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);
                    if (this.categoryFilter) params.append('category', this.categoryFilter);

                    const url = `{{ route('admin.courses.index') }}?${params.toString()}`;
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
            }
        };
    }

    // Función para mostrar modal de secciones
    async function showSectionsModal(courseId) {
        const modal                 = document.querySelector('[x-data]');
        const modalData             = modal.__x.$data;
        modalData.courseId          = courseId;
        modalData.showSections      = true;
        modalData.loadingSections   = true;

        try {
            // Cargar secciones del curso
            const response = await axios.get(`/api/courses/${courseId}/sections`);
            modalData.sections = response.data;
        } catch (error) {
            console.error('Error al cargar secciones:', error);
            showNotification('Error al cargar las secciones', 'error');
        } finally {
            modalData.loadingSections = false;
        }
    }

    // Función para agregar sección
    async function addSection() {
        const modal = document.querySelector('[x-data]');
        const modalData = modal.__x.$data;

        try {
            const response = await axios.post(`{{ route('admin.courses.sections.add', '') }}/${modalData.courseId}`, {
                title: modalData.newSectionTitle,
                description: modalData.newSectionDescription,
                order: modalData.newSectionOrder,
                _token: '{{ csrf_token() }}'
            });

            // Agregar la nueva sección a la lista
            modalData.sections.push(response.data.section);

            // Limpiar formulario
            modalData.newSectionTitle = '';
            modalData.newSectionDescription = '';
            modalData.newSectionOrder = '';

            showNotification('Sección agregada exitosamente', 'success');
        } catch (error) {
            console.error('Error al agregar sección:', error);
            showNotification('Error al agregar la sección', 'error');
        }
    }

    // Función para eliminar curso
    async function deleteCourse(courseId) {
        if (!confirm('¿Estás seguro de eliminar este curso? Esta acción no se puede deshacer.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/courses/${courseId}`);
            if (response.data.success) {
                showNotification('Curso eliminado exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al eliminar curso:', error);
            showNotification('Error al eliminar el curso', 'error');
        }
    }

    // Función para cambiar estado del curso
    async function toggleCourseStatus(courseId) {
        try {
            const response = await axios.post(`/admin/courses/${courseId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del curso actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
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
    }
</script>

<style>
    [x-cloak] { display: none !important; }

    /* Estilos para el modal de secciones */
    .section-drag-handle {
        cursor: move;
    }

    .section-drag-handle:hover {
        background-color: #f3f4f6;
    }

    /* Estilos para las tablas */
    table tbody tr {
        transition: all 0.2s ease;
    }

    table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Animación para los botones de acción */
    .group\/edit:hover svg {
        animation: bounce 0.3s;
    }

    .group\/delete:hover svg {
        animation: shake 0.3s;
    }

    .group\/sections:hover svg {
        animation: pulse 0.3s;
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

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
</style>
@endsection
