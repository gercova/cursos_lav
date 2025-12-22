@extends('layouts.admin')
@section('title', 'Gestión de Documentos')
@section('content')
<div class="container mx-auto px-4 py-6" x-data="documentManager()" x-init="init()">
    <!-- Header con estadísticas -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Documentos</h1>
                <p class="text-gray-600 mt-2">Gestiona todos los documentos de tus cursos</p>
            </div>

            <!-- Botón para subir documento -->
            <button
                @click="$dispatch('open-document-modal', { editing: false })"
                class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Subir Documento
            </button>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total de documentos -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Documentos</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $documents->total() }}</p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Documentos activos -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Documentos Activos</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $documents->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="bg-green-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tamaño total -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">Tamaño Total</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">
                            {{ round($documents->sum('file_size') / 1024 / 1024, 2) }} MB
                        </p>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tipos de archivo -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-800">Tipos de Archivo</p>
                        <p class="text-2xl font-bold text-orange-900 mt-1">
                            {{ $documents->pluck('file_type')->unique()->count() }}
                        </p>
                    </div>
                    <div class="bg-orange-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
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
                    <h2 class="text-lg font-semibold text-gray-800">Todos los Documentos</h2>
                    <p class="text-sm text-gray-600 mt-1">Administra los documentos de cada curso</p>
                </div>

                <!-- Filtros y búsqueda -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" x-model="searchQuery" @input.debounce.500ms="performSearch()" placeholder="Buscar documentos..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    </div>

                    <div class="flex gap-2">
                        <select
                            x-model="statusFilter"
                            @change="performSearch()"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                        >
                            <option value="">Todos</option>
                            <option value="active">Activos</option>
                            <option value="inactive">Inactivos</option>
                        </select>

                        <select x-model="courseFilter"
                            @change="performSearch()"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                        >
                            <option value="">Todos los cursos</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>

                        <select
                            x-model="typeFilter"
                            @change="performSearch()"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                        >
                            <option value="">Todos los tipos</option>
                            @foreach($fileTypes as $type)
                                <option value="{{ $type }}">{{ strtoupper($type) }}</option>
                            @endforeach
                        </select>

                        <button
                            @click="resetFilters()"
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200"
                        >
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de documentos -->
        <div class="overflow-x-auto" x-show="!loading">
            @if($documents->isEmpty())
                <!-- Estado vacío -->
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay documentos aún</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza subiendo tu primer documento para compartir con los estudiantes.</p>

                    <button
                        @click="$dispatch('open-document-modal', { editing: false })"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Subir Primer Documento
                    </button>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Documento
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Curso
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Tipo / Tamaño
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Fecha
                            </th>
                            <!--<th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Estado
                            </th>-->
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider text-right">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($documents as $document)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-white transition-all duration-200 group">
                                <!-- Información del documento -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <!-- Icono según tipo de archivo -->
                                        <div class="flex-shrink-0">
                                            @switch($document->file_type)
                                                @case('pdf')
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                    @break
                                                @case('doc')
                                                @case('docx')
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                    @break
                                                @case('ppt')
                                                @case('pptx')
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                    @break
                                                @case('xls')
                                                @case('xlsx')
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                        </svg>
                                                    </div>
                                                    @break
                                                @default
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                            @endswitch
                                        </div>

                                        <!-- Detalles del documento -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <a href="{{ Storage::url($document->file_path) }}"
                                                   target="_blank"
                                                   class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate">
                                                    {{ $document->title }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500 truncate">
                                                {{ $document->description ? Str::limit($document->description, 80) : 'Sin descripción' }}
                                            </div>
                                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    ID: {{ $document->id }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $document->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Curso -->
                                <td class="px-6 py-5">
                                    @if($document->course)
                                        <div class="flex items-center gap-3">
                                            @if($document->course->image_url)
                                                <img src="{{ Storage::url($document->course->image_url) }}" alt="{{ $document->course->title }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($document->course->title, 25) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $document->course->category->name ?? 'Sin categoría' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">Sin curso asignado</span>
                                    @endif
                                </td>

                                <!-- Tipo y tamaño -->
                                <td class="px-6 py-5">
                                    <div class="space-y-2">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-100">
                                            <span class="text-xs font-bold text-gray-700 uppercase">
                                                {{ $document->file_type }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ round($document->file_size / 1024 / 1024, 2) }} MB
                                        </div>
                                    </div>
                                </td>

                                <!-- Fecha -->
                                <td class="px-6 py-5">
                                    <div class="text-sm text-gray-600">
                                        {{ $document->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $document->created_at->diffForHumans() }}
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-5">
                                    <div x-data="{ open: false }" class="relative flex items-center justify-end">
                                        <!-- Botón del menú (tres puntos) -->
                                        <button @click="open = !open" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-300" title="Más opciones">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>

                                        <!-- Menú desplegable -->
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
                                            <button @click="toggleDocumentStatus({{ $document->id }}); open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ $document->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors duration-150">
                                                @if($document->is_active)
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

                                            <!-- Descargar -->
                                            <a href="{{ Storage::url($document->file_path) }}" download class="block w-full px-4 py-2.5 text-sm font-medium text-blue-600 hover:bg-blue-50 flex items-center gap-3 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Descargar
                                            </a>

                                            <!-- Vista previa -->
                                            <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="block w-full px-4 py-2.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 flex items-center gap-3 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Vista previa
                                            </a>

                                            <!-- Editar -->
                                            <a href="{{ route('admin.documents.edit', $document->id) }}" class="block w-full px-4 py-2.5 text-sm font-medium text-purple-600 hover:bg-purple-50 flex items-center gap-3 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>

                                            <!-- Eliminar -->
                                            <button @click="deleteDocument({{ $document->id }}); open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-150">
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
            @endif
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="py-16 text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Cargando documentos...</p>
        </div>

        <!-- Paginación -->
        @if($documents->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $documents->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $documents->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $documents->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center space-x-2">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal para subir/editar documento -->
    <div x-data="documentModal()" x-on:open-document-modal.window="handleOpen($event.detail)" x-cloak>
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
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden"
                >

                    <!-- Header del modal -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900" x-text="isEditing ? 'Editar Documento' : 'Subir Nuevo Documento'"></h3>
                                <p class="text-sm text-gray-600 mt-1" x-text="isEditing ? 'Actualiza la información del documento' : 'Completa el formulario para subir un nuevo documento'"></p>
                            </div>
                            <button
                                @click="closeModal"
                                class="p-2 hover:bg-gray-100 rounded-lg transition duration-200"
                            >
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del modal -->
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        <form @submit.prevent="submitForm" id="documentForm" enctype="multipart/form-data">
                            @csrf
                            <div x-show="isEditing">
                                @method('PUT')
                            </div>
                            @method('POST')

                            <div class="space-y-6">
                                <!-- Información del documento -->
                                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Información del Documento</h4>

                                    <!-- Título -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Título del Documento *
                                        </label>
                                        <input
                                            type="text"
                                            x-model="formData.title"
                                            required
                                            placeholder="Ej: Guía de Estudio - Capítulo 1"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                                        >
                                    </div>

                                    <!-- Descripción -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Descripción
                                        </label>
                                        <textarea x-model="formData.description"
                                            rows="3"
                                            placeholder="Describe el contenido del documento..."
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                                        ></textarea>
                                    </div>

                                    <!-- Curso -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Curso Asociado *
                                        </label>
                                        <select
                                            x-model="formData.course_id"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                                        >
                                            <option value="">Seleccionar curso</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Subida de archivo -->
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                                    <h4 class="text-lg font-semibold text-blue-900 mb-4">Archivo</h4>

                                    <div x-show="!isEditing" class="mb-4">
                                        <label class="block text-sm font-medium text-blue-800 mb-2">
                                            Seleccionar Archivo *
                                        </label>
                                        <div class="border-2 border-dashed border-blue-300 rounded-xl p-6 text-center hover:border-blue-400 transition duration-200">
                                            <input type="file"
                                                   name="file"
                                                   id="file"
                                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip"
                                                   class="hidden"
                                                   @change="handleFileSelect($event)">

                                            <label for="file" class="cursor-pointer">
                                                <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mt-2 text-sm text-blue-700">
                                                    <span class="font-medium text-blue-600 hover:text-blue-500">
                                                        Haz clic para subir
                                                    </span>
                                                    o arrastra y suelta
                                                </p>
                                                <p class="text-xs text-blue-600 mt-1">
                                                    PDF, DOC, PPT, XLS, TXT, ZIP hasta 50MB
                                                </p>
                                            </label>
                                        </div>
                                        <div id="fileInfo" class="mt-4 hidden">
                                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-blue-200">
                                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900" id="fileName"></p>
                                                    <p class="text-xs text-gray-500" id="fileSize"></p>
                                                </div>
                                                <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="isEditing && formData.file_path" class="mb-4">
                                        <label class="block text-sm font-medium text-blue-800 mb-2">
                                            Archivo Actual
                                        </label>
                                        <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-blue-200">
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="formData.title + '.' + formData.file_type"></p>
                                                <p class="text-xs text-gray-500" x-text="formatFileSize(formData.file_size)"></p>
                                            </div>
                                            <a :href="'/storage/' + formData.file_path"
                                               download
                                               class="text-blue-500 hover:text-blue-700"
                                               title="Descargar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        <p class="text-xs text-blue-700 mt-2">
                                            Para cambiar el archivo, sube uno nuevo
                                        </p>
                                    </div>

                                    <!-- Estado -->
                                    <div class="mt-6">
                                        <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-blue-200">
                                            <div class="flex items-center">
                                                <input type="checkbox"
                                                       x-model="formData.is_active"
                                                       id="is_active"
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <label for="is_active" class="ml-3 text-sm font-medium text-blue-900">
                                                    Documento Activo
                                                </label>
                                            </div>
                                            <div class="text-xs text-blue-700">
                                                Visible para los estudiantes
                                            </div>
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
                                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="isSubmitting" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="isSubmitting ? 'Guardando...' : (isEditing ? 'Actualizar Documento' : 'Subir Documento')"></span>
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
    function documentManager() {
        return {
            searchQuery: '{{ request('search') }}',
            statusFilter: '{{ request('status', '') }}',
            courseFilter: '{{ request('course', '') }}',
            typeFilter: '{{ request('type', '') }}',
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
                    if (this.courseFilter) params.append('course', this.courseFilter);
                    if (this.typeFilter) params.append('type', this.typeFilter);

                    const url = `{{ route('admin.documents.index') }}?${params.toString()}`;
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
                this.typeFilter = '';
                this.performSearch();
            },

            handleOpen(detail) {
                this.isEditing = detail.editing === true;
                this.showModal = true;
                if (this.isEditing && detail.id) {
                    this.loadDocument(detail.id);
                } else {
                    this.resetForm();
                }
            },
            async loadDocument(id) {
                try {
                    const response  = await axios.get(`/admin/documents/${id}`);
                    this.formData   = response.data;
                    this.formData.is_active = Boolean(this.formData.is_active);
                } catch (error) {
                    console.error('Error al cargar documento:', error);
                    showNotification('Error al cargar el documento', 'error');
                    this.closeModal();
                }
            },
        };
    }

    function documentModal() {
        return {
            showModal: false,
            isEditing: false,
            isSubmitting: false,
            formData: {
                title: '',
                description: '',
                course_id: '',
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
                    is_active: true
                };

                // Limpiar info de archivo
                const fileInfo = document.getElementById('fileInfo');
                if (fileInfo) fileInfo.classList.add('hidden');
                const fileInput = document.getElementById('file');
                if (fileInput) fileInput.value = '';
            },

            async showEditModal(documentId) {
                this.showModal = true;
                this.isEditing = true;
                this.isSubmitting = false;

                try {
                    // Cargar datos del documento
                    const response = await axios.get(`/api/documents/${documentId}`);
                    this.formData = response.data;
                    this.formData.is_active = Boolean(this.formData.is_active);

                } catch (error) {
                    console.error('Error al cargar documento:', error);
                    showNotification('Error al cargar el documento', 'error');
                    this.closeModal();
                }
            },

            closeModal() {
                this.showModal = false;
                this.isEditing = false;
                this.resetForm();
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    const fileInfo = document.getElementById('fileInfo');
                    const fileName = document.getElementById('fileName');
                    const fileSize = document.getElementById('fileSize');

                    fileName.textContent = file.name;
                    fileSize.textContent = this.formatFileSize(file.size);
                    fileInfo.classList.remove('hidden');
                }
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            async submitForm() {
                this.isSubmitting = true;
                try {
                    const form = document.getElementById('documentForm');
                    const formData = new FormData(form);

                    // Agregar datos del formulario Alpine al FormData
                    for (const [key, value] of Object.entries(this.formData)) {
                        // Excluir campos calculados o no modificables directamente aquí
                        if (key !== 'file_path' && key !== 'file_type' && key !== 'file_size') {
                            formData.append(key, value);
                        }
                    }

                    // --- CORRECCIÓN AQUÍ ---
                    // Definir URL y método según si es edición o creación
                    let url, method;
                    if (this.isEditing) {
                        // Para editar, usa PUT/PATCH y el ID del documento
                        url = `{{ route('admin.documents.update', '') }}/${this.formData.id}`; // Añade el ID al final
                        method = 'POST'; // Laravel usa POST para simular PUT/PATCH desde form HTML
                        formData.append('_method', 'PUT'); // Campo oculto para simular PUT
                    } else {
                        // Para crear, usa POST y la ruta de store
                        url = '{{ route("admin.documents.store") }}'; // Tu ruta de creación
                        method = 'POST';
                    }
                    // --- FIN CORRECCIÓN ---

                    const response = await axios({
                        method: method,
                        url: url,
                        data: formData, // Usar 'data' en lugar de 'formData' aquí
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    // --- CORRECCIÓN AQUÍ ---
                    // Manejar la respuesta del servidor
                    // Asumiendo que tu controlador devuelve una redirección o un JSON con éxito
                    // Si el servidor devuelve una redirección, response.data será la vista HTML, no un objeto JSON.
                    // Si devuelve JSON, podría tener {success: true, message: "..."} o {message: "..."}
                    // Si devuelve una redirección, no debería entrar en el catch por validación, sino que recargaría la página o mostraría la vista de éxito.

                    // Si esperas que el servidor devuelva JSON (lo cual es lo ideal para AJAX):
                    // Asegúrate de que tu controlador retorne JSON en lugar de redirigir para llamadas AJAX.
                    // Por ejemplo, en lugar de `return redirect()->route(...)->with(...)`
                    // Usa `return response()->json(['success' => true, 'message' => '...'])`
                    // o `return response()->json(['message' => '...'])` y verifica el código de estado.

                    // Para manejar ambas posibilidades (redirección o JSON), puedes hacer:
                    if (response.status === 200) {
                        // Asumimos que si es 200 OK, fue exitoso.
                        // Intenta obtener el mensaje del JSON si está disponible
                        const message = response.data?.message || (this.isEditing ? 'Documento actualizado exitosamente.' : 'Documento subido exitosamente.');
                        this.closeModal();
                        showNotification(message, 'success');
                        setTimeout(() => window.location.reload(), 1500); // Recarga la página para ver los cambios
                    } else {
                        // Si el servidor no devuelve 200, puede ser un error no manejado por el catch
                        // o una redirección inesperada.
                        // En la mayoría de los casos, los errores 4xx y 5xx caen en el catch.
                        // Si llega aquí, podría haber un problema inusual.
                        console.warn("Respuesta inesperada del servidor:", response);
                        showNotification("Respuesta inesperada del servidor.", 'error');
                    }
                    // --- FIN CORRECCIÓN ---

                } catch (error) {
                    console.error('Error al guardar:', error);
                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        this.showValidationErrors(errors);
                    } else {
                        // Mensaje de error genérico o del servidor
                        const errorMessage = error.response?.data?.message || 'Error al guardar el documento';
                        showNotification(errorMessage, 'error');
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

    // Función para eliminar documento
    async function deleteDocument(documentId) {
        if (!confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/documents/${documentId}`);
            if (response.data.success) {
                showNotification('Documento eliminado exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al eliminar documento:', error);
            showNotification('Error al eliminar el documento', 'error');
        }
    }

    // Función para cambiar estado del documento
    async function toggleDocumentStatus(documentId) {
        try {
            const response = await axios.post(`/admin/documents/${documentId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del documento actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    // Función para limpiar archivo seleccionado
    function clearFile() {
        const fileInput = document.getElementById('file');
        const fileInfo = document.getElementById('fileInfo');

        if (fileInput) fileInput.value = '';
        if (fileInfo) fileInfo.classList.add('hidden');
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
    .group\/download:hover svg {
        animation: bounce 0.3s;
    }

    .group\/delete:hover svg {
        animation: shake 0.3s;
    }

    .group\/preview:hover svg {
        animation: pulse 0.3s;
    }

    .group\/edit:hover svg {
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

    /* Estilos para los iconos de tipo de archivo */
    .file-icon {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }
</style>
@endsection
