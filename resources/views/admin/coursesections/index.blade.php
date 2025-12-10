@extends('layouts.admin')
@section('title', 'Secciones del Curso')
@section('content')
<div class="container mx-auto px-4 py-6" x-data="sectionManager()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.courses.index') }}"
                       class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cursos
                    </a>
                    <span class="text-gray-400">/</span>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Secciones del Curso</h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Información del curso -->
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl">
                        @if($course->image_url)
                            <img src="{{ Storage::url($course->image_url) }}"
                                 alt="{{ $course->title }}"
                                 class="w-16 h-16 rounded-xl object-cover border border-blue-300">
                        @else
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-lg font-semibold text-blue-900">{{ $course->title }}</h2>
                            <div class="flex items-center gap-4 text-sm text-blue-800 mt-1">
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
                                    {{ $sections->count() }} secciones
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex gap-3 mt-4 md:mt-0">
                <a href="{{ route('admin.courses.edit', $course) }}"
                   class="flex items-center gap-2 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Curso
                </a>

                <a href="{{ route('admin.courses.sections.create', $course) }}"
                   class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Sección
                </a>
            </div>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total de secciones -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Secciones</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $sections->count() }}</p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Secciones activas -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Secciones Activas</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $sections->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="bg-green-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total de lecciones -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">Total Lecciones</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">{{ $totalLessons }}</p>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
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
                    <h2 class="text-lg font-semibold text-gray-800">Todas las Secciones</h2>
                    <p class="text-sm text-gray-600 mt-1">Administra las secciones y su contenido</p>
                </div>

                <!-- Filtros -->
                <div class="flex gap-2">
                    <select x-model="statusFilter"
                            @change="filterSections()"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        <option value="">Todas</option>
                        <option value="active">Activas</option>
                        <option value="inactive">Inactivas</option>
                    </select>

                    <button @click="resetFilters()"
                            class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                        Limpiar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de secciones -->
        <div class="overflow-x-auto">
            @if($sections->isEmpty())
                <!-- Estado vacío -->
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay secciones aún</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando la primera sección para este curso.</p>
                    <a href="{{ route('admin.courses.sections.create', $course) }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Primera Sección
                    </a>
                </div>
            @else
                <div x-ref="sectionsContainer" class="min-w-full">
                    <template x-for="section in filteredSections" :key="section.id">
                        <div class="border-b border-gray-100 last:border-b-0 hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-white transition-all duration-200">
                            <div class="px-6 py-5">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <!-- Información principal -->
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <!-- Número de orden -->
                                            <div class="flex-shrink-0">
                                                <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 font-bold text-lg"
                                                      x-text="section.order"></span>
                                            </div>

                                            <!-- Contenido -->
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-900" x-text="section.title"></h3>
                                                    <span x-show="section.is_active"
                                                          class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-green-200 text-green-800">
                                                        Activo
                                                    </span>
                                                    <span x-show="!section.is_active"
                                                          class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-100 to-red-200 text-red-800">
                                                        Inactivo
                                                    </span>
                                                </div>

                                                <!-- Descripción -->
                                                <p class="text-gray-600 mb-3" x-text="section.description || 'Sin descripción'"></p>

                                                <!-- Información adicional -->
                                                <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                                    <!-- Archivo multimedia -->
                                                    <div class="flex items-center gap-2" x-show="section.media_url">
                                                        <template x-if="section.media_type === 'video'">
                                                            <div class="flex items-center gap-1">
                                                                <i class="fas fa-video text-purple-500"></i>
                                                                <span>Video incluido</span>
                                                            </div>
                                                        </template>
                                                        <template x-if="section.media_type === 'image'">
                                                            <div class="flex items-center gap-1">
                                                                <i class="fas fa-image text-green-500"></i>
                                                                <span>Imagen incluida</span>
                                                            </div>
                                                        </template>
                                                        <template x-if="section.media_type === 'document'">
                                                            <div class="flex items-center gap-1">
                                                                <i class="fas fa-file-alt text-orange-500"></i>
                                                                <span>Documento incluido</span>
                                                            </div>
                                                        </template>
                                                        <a :href="section.media_url"
                                                           target="_blank"
                                                           class="text-blue-600 hover:text-blue-800 hover:underline text-xs">
                                                            Ver archivo
                                                        </a>
                                                    </div>

                                                    <!-- Número de lecciones -->
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-list-ol text-indigo-500"></i>
                                                        <span x-text="`${section.lessons_count || 0} lecciones`"></span>
                                                    </div>

                                                    <!-- Fecha de creación -->
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-calendar text-gray-500"></i>
                                                        <span x-text="formatDate(section.created_at)"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex items-center gap-2">
                                        <!-- Ver/editar lecciones -->
                                        <a :href="`/admin/courses/${section.course_id}/sections/${section.id}/lessons`"
                                           class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-50 to-indigo-100 text-indigo-700 hover:from-indigo-100 hover:to-indigo-200 rounded-lg transition duration-200"
                                           title="Gestionar lecciones">
                                            <i class="fas fa-book-open text-sm"></i>
                                            <span class="text-sm font-medium">Lecciones</span>
                                        </a>

                                        <!-- Editar -->
                                        <a :href="`/admin/courses/${section.course_id}/sections/${section.id}/edit`"
                                           class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200"
                                           title="Editar sección">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <!-- Cambiar estado -->
                                        <button @click="toggleSectionStatus(section)"
                                                class="p-2"
                                                :class="section.is_active
                                                    ? 'text-yellow-600 hover:text-white hover:bg-gradient-to-r hover:from-yellow-500 hover:to-yellow-600'
                                                    : 'text-green-600 hover:text-white hover:bg-gradient-to-r hover:from-green-500 hover:to-green-600'"
                                                :title="section.is_active ? 'Desactivar' : 'Activar'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <template x-if="section.is_active">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                </template>
                                                <template x-if="!section.is_active">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </template>
                                            </svg>
                                        </button>

                                        <!-- Eliminar -->
                                        <button @click="deleteSection(section)"
                                                class="p-2 text-red-600 hover:text-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 rounded-lg transition-all duration-200"
                                                title="Eliminar sección">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div x-data="{ showModal: false, sectionToDelete: null }" x-cloak>
    <!-- Overlay -->
    <div x-show="showModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
         @click.self="showModal = false">

        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-2xl shadow-2xl w-full max-w-md">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Confirmar Eliminación</h3>
                        <button @click="showModal = false"
                                class="p-1 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.698-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">¿Estás seguro de eliminar esta sección?</h4>
                            <p class="text-sm text-gray-600 mt-1" x-text="sectionToDelete ? 'Se eliminará: ' + sectionToDelete.title : ''"></p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        Esta acción eliminará la sección y todas sus lecciones asociadas. Esta acción no se puede deshacer.
                    </p>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                    <button @click="showModal = false"
                            class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg font-medium transition duration-200">
                        Cancelar
                    </button>
                    <button @click="confirmDelete()"
                            class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg font-medium transition duration-200">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function sectionManager() {
        return {
            sections: @json($sections),
            statusFilter: '',
            filteredSections: [],

            init() {
                // Inicializar con todas las secciones
                this.filteredSections = this.sections;
            },

            filterSections() {
                if (!this.statusFilter) {
                    this.filteredSections = this.sections;
                    return;
                }

                this.filteredSections = this.sections.filter(section => {
                    if (this.statusFilter === 'active') {
                        return section.is_active;
                    } else if (this.statusFilter === 'inactive') {
                        return !section.is_active;
                    }
                    return true;
                });
            },

            resetFilters() {
                this.statusFilter = '';
                this.filteredSections = this.sections;
            },

            async toggleSectionStatus(section) {
                try {
                    const response = await axios.post(`/admin/courses/${section.course_id}/sections/${section.id}/toggle-status`, {
                        _token: '{{ csrf_token() }}'
                    });

                    if (response.data.success) {
                        // Actualizar estado localmente
                        const index = this.sections.findIndex(s => s.id === section.id);
                        if (index !== -1) {
                            this.sections[index].is_active = !this.sections[index].is_active;
                        }

                        // Refiltrar si hay filtro activo
                        this.filterSections();

                        showNotification('Estado actualizado correctamente', 'success');
                    }
                } catch (error) {
                    console.error('Error al cambiar estado:', error);
                    showNotification('Error al cambiar el estado', 'error');
                }
            },

            deleteSection(section) {
                const modalData = document.querySelector('[x-data]').__x.$data;
                modalData.sectionToDelete = section;
                modalData.showModal = true;
            },

            async confirmDelete() {
                const modalData = document.querySelector('[x-data]').__x.$data;
                const section = modalData.sectionToDelete;

                try {
                    const response = await axios.delete(`/admin/courses/${section.course_id}/sections/${section.id}`, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.data.success) {
                        // Remover de la lista local
                        this.sections = this.sections.filter(s => s.id !== section.id);
                        this.filteredSections = this.filteredSections.filter(s => s.id !== section.id);

                        modalData.showModal = false;
                        showNotification('Sección eliminada correctamente', 'success');

                        // Si no quedan secciones, recargar la página
                        if (this.sections.length === 0) {
                            setTimeout(() => window.location.reload(), 1000);
                        }
                    }
                } catch (error) {
                    console.error('Error al eliminar:', error);
                    showNotification('Error al eliminar la sección', 'error');
                    modalData.showModal = false;
                }
            },

            formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }
        };
    }

    // Función para mostrar notificaciones (reutilizable)
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

    /* Animaciones */
    .section-enter {
        animation: slideIn 0.3s ease-out;
    }

    .section-exit {
        animation: slideOut 0.3s ease-in;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    /* Estilos para el drag and drop (futura implementación) */
    .draggable-section {
        cursor: move;
        transition: all 0.2s ease;
    }

    .draggable-section:hover {
        background-color: #f8fafc;
    }

    .dragging {
        opacity: 0.5;
        background-color: #e0f2fe;
    }

    /* Estilos para los botones de acción */
    .group\/sections-view:hover svg {
        animation: bounce 0.3s;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-2px); }
    }
</style>
@endsection
