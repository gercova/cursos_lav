@extends('layouts.admin')
@section('title', 'Preguntas del Examen: ' . $exam->title)
@section('content')
<div class="container mx-auto px-4 py-6" x-data="questionManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Preguntas del Examen</h1>
                        <p class="text-gray-600 mt-1">{{ $exam->title }}</p>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        {{ $exam->questions_count }} preguntas
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        {{ $exam->questions()->where('type', 'multiple_choice')->count() }} opción múltiple
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                        {{ $exam->questions()->where('type', 'true_false')->count() }} verdadero/falso
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                        Total puntos: {{ $exam->questions()->sum('points') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 lg:mt-0">
                <a href="{{ route('admin.exams.edit', $exam) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Configuración
                </a>
                <button @click="showCreateModal()"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Pregunta
                </button>
            </div>
        </div>

        <!-- Pestañas de navegación -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <a href="{{ route('admin.exams.edit', $exam) }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Información General
                    </div>
                </a>
                <a href="#"
                   class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Preguntas ({{ $exam->questions_count }})
                    </div>
                </a>
                <a href="{{ route('admin.exams.results', $exam) }}"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Resultados
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <!-- Contenedor principal -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Lista de preguntas -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <!-- Header de preguntas -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Todas las Preguntas</h2>
                            <p class="text-sm text-gray-600 mt-1">Organiza y gestiona las preguntas del examen</p>
                        </div>

                        <!-- Controles -->
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text"
                                       x-model="searchQuery"
                                       @input.debounce.500ms="searchQuestions()"
                                       placeholder="Buscar preguntas..."
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            </div>

                            <select x-model="typeFilter"
                                    @change="searchQuestions()"
                                    class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">Todos los tipos</option>
                                <option value="multiple_choice">Opción múltiple</option>
                                <option value="true_false">Verdadero/Falso</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Lista de preguntas -->
                <div class="divide-y divide-gray-100">
                    @forelse($questions as $question)
                        <div class="p-6 hover:bg-gray-50 transition duration-200 question-item"
                             data-question-id="{{ $question->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Header de la pregunta -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-800 font-semibold text-sm">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900">{{ $question->question }}</h3>
                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $question->type === 'multiple_choice' ? 'Opción Múltiple' : 'Verdadero/Falso' }}
                                                </span>
                                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    {{ $question->points }} puntos
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Opciones de respuesta -->
                                    @if($question->type === 'multiple_choice' && $question->options)
                                        <div class="ml-11 space-y-2 mt-4">
                                            @foreach(json_decode($question->options) as $index => $option)
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0 w-6 h-6 rounded-full border-2
                                                        {{ $question->correct_answer == $index ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                                        @if($question->correct_answer == $index)
                                                            <div class="w-full h-full rounded-full bg-green-500 flex items-center justify-center">
                                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 p-3 rounded-lg
                                                        {{ $question->correct_answer == $index ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                                                        <span class="text-gray-700">{{ $option }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'true_false')
                                        <div class="ml-11 space-y-2 mt-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 rounded-full border-2
                                                    {{ $question->correct_answer == 'true' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                                    @if($question->correct_answer == 'true')
                                                        <div class="w-full h-full rounded-full bg-green-500 flex items-center justify-center">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 p-3 rounded-lg
                                                    {{ $question->correct_answer == 'true' ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                                                    <span class="text-gray-700">Verdadero</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 rounded-full border-2
                                                    {{ $question->correct_answer == 'false' ? 'border-green-500 bg-green-50' : 'border-gray-300' }}">
                                                    @if($question->correct_answer == 'false')
                                                        <div class="w-full h-full rounded-full bg-green-500 flex items-center justify-center">
                                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 p-3 rounded-lg
                                                    {{ $question->correct_answer == 'false' ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                                                    <span class="text-gray-700">Falso</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Acciones -->
                                <div class="flex items-center gap-2 ml-4">
                                    <button @click="editQuestion({{ $question->id }})"
                                            class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200"
                                            title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button @click="deleteQuestion({{ $question->id }})"
                                            class="p-2 text-red-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 rounded-lg transition-all duration-200"
                                            title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    <button @click="moveQuestion({{ $question->id }}, 'up')"
                                            {{ $loop->first ? 'disabled' : '' }}
                                            class="p-2 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-gray-600 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Mover arriba">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    </button>
                                    <button @click="moveQuestion({{ $question->id }}, 'down')"
                                            {{ $loop->last ? 'disabled' : '' }}
                                            class="p-2 text-gray-600 hover:text-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-gray-600 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Mover abajo">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Estado vacío -->
                        <div class="text-center py-16 px-6">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay preguntas aún</h3>
                            <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primera pregunta para este examen.</p>
                            <button @click="showCreateModal()"
                                    class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Primera Pregunta
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Panel lateral - Estadísticas y acciones -->
        <div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm sticky top-6">
                <h3 class="text-xl font-bold text-blue-900 mb-6">Resumen del Examen</h3>

                <!-- Estadísticas -->
                <div class="space-y-4 mb-6">
                    <div class="p-4 bg-white rounded-xl border border-blue-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-900">{{ $exam->questions_count }}</div>
                            <div class="text-sm text-blue-700 mt-1">Total Preguntas</div>
                        </div>
                    </div>

                    <div class="p-4 bg-white rounded-xl border border-blue-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-900">
                                {{ $exam->questions()->sum('points') }}
                            </div>
                            <div class="text-sm text-green-700 mt-1">Puntos Totales</div>
                        </div>
                    </div>

                    <div class="p-4 bg-white rounded-xl border border-blue-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-900">
                                {{ $exam->questions()->where('type', 'multiple_choice')->count() }}
                            </div>
                            <div class="text-sm text-purple-700 mt-1">Opción Múltiple</div>
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="space-y-3">
                    <button @click="showCreateModal()"
                            class="w-full flex items-center gap-3 p-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium transition-all duration-200 group">
                        <div class="p-2 rounded-lg bg-white/20 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <span>Nueva Pregunta</span>
                    </button>

                    <button @click="importQuestions()"
                            class="w-full flex items-center gap-3 p-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-medium transition-all duration-200 group">
                        <div class="p-2 rounded-lg bg-white/20 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                        </div>
                        <span>Importar Preguntas</span>
                    </button>

                    <button @click="reorderQuestions()"
                            class="w-full flex items-center gap-3 p-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl font-medium transition-all duration-200 group">
                        <div class="p-2 rounded-lg bg-white/20 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <span>Reordenar Todas</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar pregunta -->
    <div x-data="questionModal()" x-cloak>
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
             @click.self="closeModal">

            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">

                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900" x-text="isEditing ? 'Editar Pregunta' : 'Nueva Pregunta'"></h3>
                                <p class="text-sm text-gray-600 mt-1" x-text="isEditing ? 'Modifica la pregunta y sus opciones' : 'Crea una nueva pregunta para el examen'"></p>
                            </div>
                            <button @click="closeModal"
                                    class="p-2 hover:bg-gray-100 rounded-lg transition duration-200">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="submitForm" class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        @csrf
                        <div x-show="isEditing">
                            <input type="hidden" name="_method" value="PUT">
                        </div>

                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                        <div class="space-y-6">
                            <!-- Tipo de pregunta -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Tipo de Pregunta *
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <button type="button"
                                            @click="formData.type = 'multiple_choice'"
                                            :class="formData.type === 'multiple_choice'
                                                ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white border-blue-600'
                                                : 'bg-white text-gray-700 border-gray-300 hover:border-blue-500'"
                                            class="p-4 border rounded-xl transition-all duration-200">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">Opción Múltiple</span>
                                        </div>
                                    </button>
                                    <button type="button"
                                            @click="formData.type = 'true_false'"
                                            :class="formData.type === 'true_false'
                                                ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600'
                                                : 'bg-white text-gray-700 border-gray-300 hover:border-green-500'"
                                            class="p-4 border rounded-xl transition-all duration-200">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">Verdadero/Falso</span>
                                        </div>
                                    </button>
                                </div>
                                <input type="hidden" x-model="formData.type" name="type" required>
                            </div>

                            <!-- Enunciado de la pregunta -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Enunciado de la Pregunta *
                                </label>
                                <textarea x-model="formData.question"
                                          name="question"
                                          rows="3"
                                          required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                                          placeholder="Escribe la pregunta aquí..."></textarea>
                            </div>

                            <!-- Puntos -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Puntos *
                                </label>
                                <div class="relative">
                                    <input type="number"
                                           x-model="formData.points"
                                           name="points"
                                           min="1"
                                           max="100"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                                           placeholder="Ej: 10">
                                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">puntos</span>
                                </div>
                            </div>

                            <!-- Opciones (solo para múltiple choice) -->
                            <div x-show="formData.type === 'multiple_choice'" x-cloak>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Opciones de Respuesta *
                                        </label>
                                        <button type="button"
                                                @click="addOption()"
                                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            + Agregar opción
                                        </button>
                                    </div>

                                    <div class="space-y-3" x-data="{ options: formData.options || ['', '', '', ''] }">
                                        <template x-for="(option, index) in options" :key="index">
                                            <div class="flex items-center gap-3">
                                                <button type="button"
                                                        @click="setCorrectAnswer(index)"
                                                        :class="formData.correct_answer == index
                                                            ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                                                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                                        class="flex-shrink-0 w-8 h-8 rounded-full font-medium transition duration-200">
                                                    <span x-text="String.fromCharCode(65 + index)"></span>
                                                </button>
                                                <input type="text"
                                                       x-model="options[index]"
                                                       @input="formData.options = options"
                                                       :name="'options[' + index + ']'"
                                                       :placeholder="'Opción ' + String.fromCharCode(65 + index)"
                                                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                                <button type="button"
                                                        x-show="options.length > 2"
                                                        @click="removeOption(index)"
                                                        class="flex-shrink-0 p-1 text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <input type="hidden" x-model="formData.correct_answer" name="correct_answer">
                                </div>
                            </div>

                            <!-- Opciones para verdadero/falso -->
                            <div x-show="formData.type === 'true_false'" x-cloak>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Respuesta Correcta *
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <button type="button"
                                                @click="formData.correct_answer = 'true'"
                                                :class="formData.correct_answer === 'true'
                                                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600'
                                                    : 'bg-white text-gray-700 border-gray-300 hover:border-green-500'"
                                                class="p-4 border rounded-xl transition-all duration-200">
                                            <div class="flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="font-medium">Verdadero</span>
                                            </div>
                                        </button>
                                        <button type="button"
                                                @click="formData.correct_answer = 'false'"
                                                :class="formData.correct_answer === 'false'
                                                    ? 'bg-gradient-to-r from-red-500 to-red-600 text-white border-red-600'
                                                    : 'bg-white text-gray-700 border-gray-300 hover:border-red-500'"
                                                class="p-4 border rounded-xl transition-all duration-200">
                                            <div class="flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span class="font-medium">Falso</span>
                                            </div>
                                        </button>
                                    </div>
                                    <input type="hidden" x-model="formData.correct_answer" name="correct_answer">
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-200">
                            <button type="button"
                                    @click="closeModal"
                                    class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                                Cancelar
                            </button>
                            <button type="submit"
                                    :disabled="isSubmitting"
                                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="isSubmitting" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isSubmitting ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Crear Pregunta')"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Manager para las preguntas
    function questionManager() {
        return {
            searchQuery: '',
            typeFilter: '',
            loading: false,

            init() {
                // Inicialización
            },

            async searchQuestions() {
                this.loading = true;
                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.typeFilter) params.append('type', this.typeFilter);

                    const url = `{{ route('admin.exams.questions', $exam) }}?${params.toString()}`;
                    window.location.href = url;
                } catch (error) {
                    console.error('Error en búsqueda:', error);
                    this.loading = false;
                }
            },

            showCreateModal() {
                const modal = document.querySelector('[x-data="questionModal()"]');
                if (modal) {
                    modal.__x.$data.showModal = true;
                    modal.__x.$data.isEditing = false;
                    modal.__x.$data.resetForm();
                }
            },

            async editQuestion(questionId) {
                const modal = document.querySelector('[x-data="questionModal()"]');
                if (modal) {
                    modal.__x.$data.showModal = true;
                    modal.__x.$data.isEditing = true;

                    try {
                        const response = await axios.get(`/admin/exams/questions/${questionId}`);
                        modal.__x.$data.formData = response.data;

                        // Asegurar que las opciones sean un array
                        if (modal.__x.$data.formData.options && typeof modal.__x.$data.formData.options === 'string') {
                            modal.__x.$data.formData.options = JSON.parse(modal.__x.$data.formData.options);
                        }
                    } catch (error) {
                        console.error('Error al cargar pregunta:', error);
                        showNotification('Error al cargar la pregunta', 'error');
                    }
                }
            },

            async deleteQuestion(questionId) {
                if (!confirm('¿Estás seguro de eliminar esta pregunta?')) {
                    return;
                }

                try {
                    const response = await axios.delete(`/admin/exams/questions/${questionId}`);
                    if (response.data.success) {
                        showNotification('Pregunta eliminada exitosamente', 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    }
                } catch (error) {
                    console.error('Error al eliminar pregunta:', error);
                    showNotification('Error al eliminar la pregunta', 'error');
                }
            },

            async moveQuestion(questionId, direction) {
                try {
                    const response = await axios.post(`/admin/exams/questions/${questionId}/move`, {
                        direction: direction
                    });

                    if (response.data.success) {
                        showNotification('Pregunta movida exitosamente', 'success');
                        setTimeout(() => window.location.reload(), 500);
                    }
                } catch (error) {
                    console.error('Error al mover pregunta:', error);
                    showNotification('Error al mover la pregunta', 'error');
                }
            },

            importQuestions() {
                // Implementar lógica de importación
                showNotification('Funcionalidad de importación en desarrollo', 'info');
            },

            reorderQuestions() {
                // Implementar lógica de reordenamiento
                showNotification('Funcionalidad de reordenamiento en desarrollo', 'info');
            }
        };
    }

    // Modal para crear/editar preguntas
    function questionModal() {
        return {
            showModal: false,
            isEditing: false,
            isSubmitting: false,
            formData: {
                id: null,
                question: '',
                type: 'multiple_choice',
                points: 10,
                options: ['', '', '', ''],
                correct_answer: 0
            },

            init() {
                // Inicialización
            },

            resetForm() {
                this.formData = {
                    id: null,
                    question: '',
                    type: 'multiple_choice',
                    points: 10,
                    options: ['', '', '', ''],
                    correct_answer: 0
                };
            },

            addOption() {
                if (this.formData.options.length < 6) {
                    this.formData.options.push('');
                }
            },

            removeOption(index) {
                if (this.formData.options.length > 2) {
                    this.formData.options.splice(index, 1);

                    // Ajustar respuesta correcta si es necesario
                    if (this.formData.correct_answer >= index) {
                        this.formData.correct_answer = Math.max(0, this.formData.correct_answer - 1);
                    }
                }
            },

            setCorrectAnswer(index) {
                this.formData.correct_answer = index;
            },

            closeModal() {
                this.showModal = false;
                this.isEditing = false;
                this.isSubmitting = false;
                this.resetForm();
            },

            async submitForm() {
                this.isSubmitting = true;

                try {
                    const url = this.isEditing
                        ? `/admin/exams/questions/${this.formData.id}`
                        : '{{ route("admin.exams.questions.store", $exam) }}';

                    const method = this.isEditing ? 'PUT' : 'POST';

                    // Preparar datos
                    const formData = new FormData();
                    formData.append('exam_id', '{{ $exam->id }}');
                    formData.append('question', this.formData.question);
                    formData.append('type', this.formData.type);
                    formData.append('points', this.formData.points);
                    formData.append('correct_answer', this.formData.correct_answer);

                    if (this.formData.type === 'multiple_choice') {
                        formData.append('options', JSON.stringify(this.formData.options));
                    }

                    const response = await axios({
                        method: method,
                        url: url,
                        data: formData,
                        headers: {
                            'Content-Type': 'multipart/form-data',
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
                        showNotification('Error al guardar la pregunta', 'error');
                    }
                } finally {
                    this.isSubmitting = false;
                }
            },

            showValidationErrors(errors) {
                const firstError = Object.values(errors)[0][0];
                showNotification(firstError, 'error');
            }
        };
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
            type === 'success'
            ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
            : type === 'error'
            ? 'bg-gradient-to-r from-red-500 to-red-600 text-white'
            : 'bg-gradient-to-r from-blue-500 to-blue-600 text-white'
        }`;

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        : type === 'error'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
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

    /* Animaciones */
    .question-item {
        transition: all 0.2s ease;
    }

    .question-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Estilos para las opciones */
    input[type="text"]:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Scroll personalizado */
    .overflow-y-auto {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f7fafc;
    }

    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f7fafc;
        border-radius: 3px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }
</style>
@endsection
