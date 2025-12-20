@extends('layouts.admin')

@section('title', 'Gestión de Exámenes')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="examManager()" x-init="init()">
    <!-- Header con estadísticas -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Exámenes</h1>
                <p class="text-gray-600 mt-2">Gestiona todos los exámenes de la plataforma</p>
            </div>

            <!-- Botón para crear nuevo examen -->
            <a href="{{ route('admin.exams.create') }}" class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Examen
            </a>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total de exámenes -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Exámenes</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $exams->total() }}</p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Exámenes activos -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Exámenes Activos</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $exams->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="bg-green-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Exámenes con tiempo límite -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">Con Tiempo Límite</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">
                            {{ $exams->where('time_limit', '>', 0)->count() }}
                        </p>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total preguntas -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-800">Total Preguntas</p>
                        <p class="text-2xl font-bold text-orange-900 mt-1">
                            {{ $exams->sum('questions_count') }}
                        </p>
                    </div>
                    <div class="bg-orange-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                    <h2 class="text-lg font-semibold text-gray-800">Todos los Exámenes</h2>
                    <p class="text-sm text-gray-600 mt-1">Configura y administra los exámenes de cada curso</p>
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
                               placeholder="Buscar exámenes..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    </div>

                    <div class="flex gap-2">
                        <select x-model="statusFilter"
                                @change="performSearch()"
                                class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            <option value="">Todos</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                        </select>

                        <select x-model="courseFilter"
                                @change="performSearch()"
                                class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            <option value="">Todos los cursos</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
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

        <!-- Tabla de exámenes -->
        <div class="overflow-x-auto" x-show="!loading">
            @if($exams->isEmpty())
                <!-- Estado vacío -->
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay exámenes aún</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primer examen para evaluar a los estudiantes.</p>
                    <button @click="showCreateModal()"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Primer Examen
                    </button>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Examen
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Curso
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Configuración
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Estadísticas
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
                        @foreach($exams as $exam)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-white transition-all duration-200 group">
                                <!-- Información del examen -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <!-- Icono del examen -->
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Detalles del examen -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <a href="#" class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate">
                                                    {{ $exam->title }}
                                                </a>
                                                @if($exam->is_final)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-red-200 text-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        FINAL
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500 truncate">
                                                {{ $exam->description ? Str::limit($exam->description, 60) : 'Sin descripción' }}
                                            </div>
                                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    ID: {{ $exam->id }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $exam->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Curso -->
                                <td class="px-6 py-5">
                                    @if($exam->course)
                                        <div class="flex items-center gap-3">
                                            @if($exam->course->image_url)
                                                <img src="{{ Storage::url($exam->course->image_url) }}"  alt="{{ $exam->course->title }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($exam->course->title, 30) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $exam->course->category->name ?? 'Sin categoría' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Sin curso asignado</span>
                                    @endif
                                </td>

                                <!-- Configuración -->
                                <td class="px-6 py-5">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Puntaje mínimo:</span>
                                            <span class="text-sm font-semibold text-gray-900">{{ $exam->passing_score }}%</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Tiempo límite:</span>
                                            <span class="text-sm font-semibold text-gray-900">
                                                {{ $exam->time_limit > 0 ? $exam->time_limit . ' min' : 'Ilimitado' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Intentos:</span>
                                            <span class="text-sm font-semibold text-gray-900">
                                                {{ $exam->max_attempts > 0 ? $exam->max_attempts : 'Ilimitados' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Estadísticas -->
                                <td class="px-6 py-5">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Preguntas:</span>
                                            <span class="text-sm font-bold text-blue-600">{{ $exam->questions_count }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Intentos totales:</span>
                                            <span class="text-sm font-bold text-green-600">{{ $exam->attempts_count ?? 0 }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Aprobados:</span>
                                            <span class="text-sm font-bold text-green-600">{{ $exam->passed_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-2">
                                        <span :class="{'bg-gradient-to-r from-green-100 to-green-200 text-green-800': {{ $exam->is_active }}, 'bg-gradient-to-r from-red-100 to-red-200 text-red-800': !{{ $exam->is_active }}}" class="px-3 py-1 rounded-full text-xs font-semibold text-center">
                                            {{ $exam->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        <button onclick="toggleExamStatus({{ $exam->id }})" class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                                            {{ $exam->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Ver resultados -->
                                        <a href="{{ route('admin.exams.results', $exam) }}" class="p-2 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-gray-600 rounded-lg transition-all duration-200 group/results"
                                           title="Ver resultados">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                        </a>

                                        <!-- Editar -->
                                        <button @click="$dispatch('open-edit-modal', { id: {{ $exam->id }} })" class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200 group/edit" title="Editar examen">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <!-- Gestión de preguntas -->
                                        <a href="{{ route('admin.exams.questions', $exam) }}" class="p-2 text-purple-600 hover:text-white hover:bg-gradient-to-r hover:from-purple-500 hover:to-purple-600 rounded-lg transition-all duration-200 group/questions" title="Gestionar preguntas">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </a>

                                        <!-- Vista previa -->
                                        <a href="#" class="p-2 text-green-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-green-600 rounded-lg transition-all duration-200 group/preview" title="Vista previa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        <!-- Eliminar -->
                                        <button onclick="deleteExam({{ $exam->id }})" class="p-2 text-red-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 rounded-lg transition-all duration-200 group/delete" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
            <p class="text-gray-600">Cargando exámenes...</p>
        </div>

        <!-- Paginación -->
        @if($exams->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $exams->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $exams->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $exams->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center space-x-2">
                        {{ $exams->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal para crear/editar examen -->
    <!--<div x-data="examModal()" x-cloak>-->
    <div x-data="examModal()" x-cloak @open-edit-modal.window="showEditModal($event.detail.id)">
        <!-- Modal overlay -->
        <div x-show="showModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
            @click.self="closeModal"
        >
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden"
                >
                    <!-- Header del modal -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900" x-text="isEditing ? 'Editar Examen' : 'Nuevo Examen'"></h3>
                                <p class="text-sm text-gray-600 mt-1" x-text="isEditing ? 'Modifica la configuración del examen' : 'Completa todos los campos para crear un nuevo examen'"></p>
                            </div>
                            <button @click="closeModal" class="p-2 hover:bg-gray-100 rounded-lg transition duration-200">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del modal -->
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        <form @submit.prevent="submitForm">
                            @csrf
                            <div x-show="isEditing">
                                @method('PUT')
                            </div>

                            <div class="space-y-6">
                                <!-- Información básica -->
                                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Título -->
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Título del Examen *
                                            </label>
                                            <input type="text" x-model="formData.title" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                        </div>

                                        <!-- Descripción -->
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Descripción
                                            </label>
                                            <textarea x-model="formData.description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"></textarea>
                                        </div>

                                        <!-- Curso -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Curso *
                                            </label>
                                            <select x-model="formData.course_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                                <option value="">Seleccionar curso</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Tipo de examen -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Tipo de Examen
                                            </label>
                                            <select x-model="formData.is_final" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                                <option value="0">Examen Regular</option>
                                                <option value="1">Examen Final</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Configuración del examen -->
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                                    <h4 class="text-lg font-semibold text-blue-900 mb-4">Configuración del Examen</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Puntaje mínimo -->
                                        <div>
                                            <label class="block text-sm font-medium text-blue-800 mb-1">
                                                Puntaje Mínimo (%) *
                                            </label>
                                            <div class="relative">
                                                <input type="number" x-model="formData.passing_score" min="0" max="100" required class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600">%</span>
                                            </div>
                                        </div>

                                        <!-- Tiempo límite -->
                                        <div>
                                            <label class="block text-sm font-medium text-blue-800 mb-1">
                                                Tiempo Límite (minutos)
                                            </label>
                                            <div class="relative">
                                                <input type="number" x-model="formData.duration" min="0" placeholder="0 = sin límite" class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                                <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600">min</span>
                                            </div>
                                        </div>

                                        <!-- Intentos máximos -->
                                        <div>
                                            <label class="block text-sm font-medium text-blue-800 mb-1">
                                                Intentos Máximos
                                            </label>
                                            <div class="relative">
                                                <input type="number" x-model="formData.max_attempts" min="0" placeholder="0 = ilimitados" class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Opciones adicionales -->
                                    <div class="mt-6 space-y-4">
                                        <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-blue-200">
                                            <div class="flex items-center">
                                                <input type="checkbox" x-model="formData.show_results" id="show_results" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <label for="show_results" class="ml-3 text-sm font-medium text-blue-900">
                                                    Mostrar resultados al finalizar
                                                </label>
                                            </div>
                                            <div class="text-xs text-blue-700">
                                                Los estudiantes verán sus respuestas
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-blue-200">
                                            <div class="flex items-center">
                                                <input type="checkbox" x-model="formData.randomize_questions" id="randomize_questions" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <label for="randomize_questions" class="ml-3 text-sm font-medium text-blue-900">
                                                    Preguntas aleatorias
                                                </label>
                                            </div>
                                            <div class="text-xs text-blue-700">
                                                Mezclar orden de preguntas
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-lg font-semibold text-green-900">Estado del Examen</h4>
                                            <p class="text-sm text-green-700 mt-1">
                                                <span x-text="formData.is_active ? 'Activo - Los estudiantes pueden tomarlo' : 'Inactivo - Solo visible en administración'"></span>
                                            </p>
                                        </div>
                                        <div>
                                            <button type="button"
                                                @click="formData.is_active = !formData.is_active"
                                                :class="formData.is_active ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'"
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                            >
                                                <span :class="formData.is_active ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones del modal -->
                            <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-200">
                                <button type="button" @click="closeModal" class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    :disabled="isSubmitting"
                                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg x-show="isSubmitting" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="isSubmitting ? 'Guardando...' : (isEditing ? 'Actualizar Examen' : 'Crear Examen')"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function examManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            courseFilter: '{{ request('course', '') }}',
            loading: false,

            init() {
                // Inicializar
                this.$watch('showModal', value => {
                    if (value) return;
                    this.resetForm();
                });

                this.$el.addEventListener('open-edit-modal', (e) => {
                    this.showEditModal(e.detail.id);
                });
            },

            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);
                    if (this.courseFilter) params.append('course', this.courseFilter);

                    const url = `{{ route('admin.exams.index') }}?${params.toString()}`;
                    window.location.href = url;
                } catch (error) {
                    console.error('Error en búsqueda:', error);
                    this.loading = false;
                }
            },

            resetFilters() {
                this.searchQuery = '';
                this.statusFilter = '';
                this.courseFilter = '';
                this.performSearch();
            },

            showCreateModal() {
                const modal = document.querySelector('[x-data="examModal()"]');
                if (modal) {
                    modal.__x.$data.showModal = true;
                    modal.__x.$data.isEditing = false;
                    modal.__x.$data.resetForm();
                }
            }
        };
    }

    function examModal() {
        return {
            showModal: false,
            isEditing: false,
            isSubmitting: false,
            formData: {
                title: '',
                description: '',
                course_id: '',
                passing_score: 70,
                duration: 0,
                max_attempts: 0,
                is_final: 0,
                show_results: true,
                randomize_questions: false,
                is_active: true
            },

            init() {
                // Inicializar
            },

            resetForm() {
                this.formData = {
                    title: '',
                    description: '',
                    course_id: '',
                    passing_score: 70,
                    duration: 0,
                    max_attempts: 0,
                    is_final: 0,
                    show_results: true,
                    randomize_questions: false,
                    is_active: true
                };
            },

            async showEditModal(examId) {
                this.showModal = true;
                this.isEditing = true;
                this.isSubmitting = false;
                exam = examId

                try {
                    // Cargar datos del examen
                    const response = await axios.get(`/admin/exams/${examId}/show`);

                    this.formData = response.data;

                    // Asegurar que los valores booleanos sean correctos
                    this.formData.is_final              = this.formData.is_final ? 1 : 0;
                    this.formData.show_results          = Boolean(this.formData.show_results);
                    this.formData.randomize_questions   = Boolean(this.formData.randomize_questions);
                    this.formData.is_active             = Boolean(this.formData.is_active);

                } catch (error) {
                    console.error('Error al cargar examen:', error);
                    showNotification('Error al cargar el examen', 'error');
                    this.closeModal();
                }
            },

            closeModal() {
                this.showModal = false;
                this.isEditing = false;
                this.resetForm();
            },

            async submitForm() {
                this.isSubmitting = true;

                try {
                    const url       = this.isEditing ? `/admin/exams/${this.formData.id}` : '{{ route("admin.exams.store") }}';
                    const method    = this.isEditing ? 'PUT' : 'POST';
                    const response  = await axios({
                        method: method,
                        url: url,
                        data: this.formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.data.success) {
                        this.closeModal();
                        showNotification(response.data.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    }
                } catch (error) {
                    console.error('Error al guardar:', error);

                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        this.showValidationErrors(errors);
                    } else {
                        showNotification('Error al guardar el examen', 'error');
                    }
                } finally {
                    this.isSubmitting = false;
                }
            },

            showValidationErrors(errors) {
                // Aquí podrías implementar la lógica para mostrar errores de validación
                const firstError = Object.values(errors)[0][0];
                showNotification(firstError, 'error');
            }
        };
    }

    // Función para eliminar examen
    async function deleteExam(examId) {
        if (!confirm('¿Estás seguro de eliminar este examen? También se eliminarán todas las preguntas y resultados asociados.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/exams/${examId}`);
            if (response.data.success) {
                showNotification('Examen eliminado exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al eliminar examen:', error);
            showNotification('Error al eliminar el examen', 'error');
        }
    }

    // Función para cambiar estado del examen
    async function toggleExamStatus(examId) {
        try {
            const response = await axios.post(`/admin/exams/${examId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del examen actualizado', 'success');
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

        setTimeout(() => {
            notification.classList.add('translate-y-0', 'opacity-100');
        }, 10);

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

    /* Animaciones para los botones de acción */
    .group\/edit:hover svg {
        animation: bounce 0.3s;
    }

    .group\/delete:hover svg {
        animation: shake 0.3s;
    }

    .group\/questions:hover svg {
        animation: pulse 0.3s;
    }

    .group\/results:hover svg {
        animation: spin 0.3s;
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

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Estilos para la tabla */
    table tbody tr {
        transition: all 0.2s ease;
    }

    table tbody tr:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Estilos para el toggle switch */
    .toggle-bg:after {
        content: '';
        position: absolute;
        top: 0.125rem;
        left: 0.125rem;
        background: white;
        border-radius: 9999px;
        height: 1.25rem;
        width: 1.25rem;
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    input:checked + .toggle-bg:after {
        transform: translateX(100%);
    }
</style>
@endsection
