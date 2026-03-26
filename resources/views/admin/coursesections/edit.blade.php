@extends('layouts.admin')
@section('title', 'Editar Sección de Curso')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Header del formulario -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Editar Sección</h2>
                    <p class="text-sm text-gray-600 mt-1">Actualiza la información de la sección</p>
                </div>
                <a href="{{ route('admin.courses.sections.index', $course->id) }}"
                   class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al curso
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('admin.courses.sections.update', [$course->id, $section->id]) }}" method="POST" enctype="multipart/form-data" x-data="sectionForm()">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">
                <!-- Información del curso -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Curso: {{ $course->title }}</h3>
                    <div class="flex items-center gap-2 text-sm text-blue-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Editando sección: <strong>{{ $section->title }}</strong></span>
                    </div>
                </div>

                <!-- Campo Título -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título de la Sección <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $section->title) }}"
                           required
                           maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                           placeholder="Ej: Introducción al curso">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                              placeholder="Describe brevemente el contenido de esta sección...">{{ old('description', $section->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campos de Archivo Multimedia y Orden -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Campo Orden -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Orden de la Sección <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               id="order"
                               name="order"
                               value="{{ old('order', $section->order) }}"
                               required
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        <p class="mt-1 text-sm text-gray-500">
                            Las secciones se mostrarán en orden ascendente (1, 2, 3...)
                        </p>
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estado -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input type="checkbox"
                               id="is_active"
                               name="is_active"
                               value="1"
                               {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Sección activa (visible para los estudiantes)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer del formulario -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50 flex justify-between">
                <form action="{{ route('admin.courses.sections.destroy', [$course->id, $section->id]) }}"
                      method="POST"
                      onsubmit="return confirm('¿Estás seguro de eliminar esta sección? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="flex items-center gap-2 px-6 py-3 border border-red-300 text-red-700 hover:bg-red-50 rounded-xl font-medium transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Eliminar Sección
                    </button>
                </form>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.courses.sections.index', $course->id) }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Sección
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function sectionForm() {
        return {
            dragover: false,
            filePreview: false,
            fileName: '',
            fileSize: 0,
            fileType: '',

            init() {
                // Inicializar
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.processFile(file);
                }
            },

            handleDrop(event) {
                this.dragover = false;
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    this.processFile(files[0]);

                    // Actualizar el input file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(files[0]);
                    document.getElementById('mediafile').files = dataTransfer.files;
                }
            },

            processFile(file) {
                // Validar tamaño (50MB máximo)
                const maxSize = 50 * 1024 * 1024; // 50MB
                if (file.size > maxSize) {
                    alert('El archivo es demasiado grande. Máximo 50MB.');
                    return;
                }

                // Validar tipo de archivo
                const allowedTypes = [
                    'video/mp4', 'video/avi', 'video/quicktime', 'video/x-ms-wmv',
                    'video/x-flv', 'video/webm', 'video/x-matroska',
                    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                    'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'text/plain', 'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                ];

                if (!allowedTypes.includes(file.type) && !file.name.match(/\.(mp4|avi|mov|wmv|flv|webm|mkv|jpg|jpeg|png|gif|webp|pdf|doc|docx|txt|ppt|pptx)$/i)) {
                    alert('Tipo de archivo no permitido. Solo se permiten videos, imágenes y documentos.');
                    return;
                }

                this.fileName = file.name;
                this.fileSize = file.size;

                // Determinar tipo de archivo
                if (file.type.startsWith('video/')) {
                    this.fileType = 'video';
                } else if (file.type.startsWith('image/')) {
                    this.fileType = 'image';
                } else if (file.type === 'application/pdf' || file.name.match(/\.(pdf|doc|docx|txt|ppt|pptx)$/i)) {
                    this.fileType = 'document';
                } else {
                    this.fileType = 'other';
                }

                this.filePreview = true;
            },

            clearFile() {
                this.filePreview = false;
                this.fileName = '';
                this.fileSize = 0;
                this.fileType = '';

                // Limpiar input file
                const fileInput = document.getElementById('mediafile');
                fileInput.value = '';
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        };
    }

    // Validación del formulario antes de enviar
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
            const title = document.getElementById('title').value.trim();
            const order = document.getElementById('order').value;

            if (!title) {
                event.preventDefault();
                alert('Por favor, ingresa un título para la sección.');
                return;
            }

            if (!order || order < 1) {
                event.preventDefault();
                alert('Por favor, ingresa un orden válido (mayor o igual a 1).');
                return;
            }
        });
    });
</script>

<style>
    /* Estilos para drag and drop */
    [x-data] .dragover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    /* Transiciones suaves */
    .file-preview-enter {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Estilo para el checkbox */
    input[type="checkbox"]:checked {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    /* Estilo para el checkbox de eliminar */
    input[name="remove_media"]:checked {
        background-color: #dc2626;
        border-color: #dc2626;
    }
</style>
@endsection
