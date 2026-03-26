@extends('layouts.admin')

@section('title', 'Subir Documento - ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-6" x-data="documentForm()" x-init="init()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Subir Documento</h1>
                <p class="text-gray-600 mt-2">
                    Agregar documento al curso: 
                    <span class="font-semibold text-blue-600">{{ $course->title }}</span>
                </p>
                @if($course->category)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                        {{ $course->category->name }}
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-2 mt-4 lg:mt-0">
                <a href="{{ route('admin.documents.view', $course) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Documentos
                </a>
            </div>
        </div>

        <!-- Barra de progreso -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-700">Progreso del formulario</span>
                <span class="text-sm font-bold text-blue-600" x-text="`${formProgress}%`"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500"
                     :style="`width: ${formProgress}%`"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                <span>Información</span>
                <span>Archivo</span>
                <span>Configuración</span>
                <span>Listo</span>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.documents.store') }}"
              method="POST"
              enctype="multipart/form-data"
              id="documentForm"
              @submit.prevent="submitForm">
            @csrf
            
            <!-- Curso seleccionado (oculto) -->
            <input type="hidden" name="course_id" value="{{ $course->id }}">

            <div class="p-6 md:p-8">
                <!-- Información del Curso -->
                <div class="mb-8 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Curso seleccionado</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Este documento será asociado al curso: 
                                <strong class="text-blue-700">{{ $course->title }}</strong>
                            </p>
                            @if($course->description)
                                <p class="text-xs text-gray-500 mt-2">{{ Str::limit($course->description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Título del Documento -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Título del Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title"
                           x-model="formData.title"
                           @input="updateProgress"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                           placeholder="Ej: Guía de Estudio - Capítulo 1">
                    <p class="text-xs text-gray-500 mt-1">Usa un título descriptivo que ayude a identificar el contenido.</p>
                </div>

                <!-- Descripción del Documento -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea name="description" 
                              id="description"
                              x-model="formData.description"
                              @input="updateProgress"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                              placeholder="Describe el contenido del documento, objetivos o instrucciones para los estudiantes..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        <span x-text="formData.description.length"></span>/1000 caracteres
                    </p>
                </div>

                <!-- Archivo -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Archivo <span class="text-red-500">*</span>
                    </label>
                    
                    <!-- Área de dropzone -->
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-colors cursor-pointer"
                         :class="{'border-blue-500 bg-blue-50': isDragging}"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop"
                         @click="triggerFileInput">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Subir un archivo</span>
                                    <input type="file" 
                                           name="file" 
                                           id="file"
                                           @change="handleFileSelect"
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar,.7z"
                                           class="sr-only"
                                           required>
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR, 7Z hasta 50MB
                            </p>
                        </div>
                    </div>

                    <!-- Preview del archivo -->
                    <div id="file-preview" 
                         x-show="selectedFile"
                         x-transition
                         class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200"
                         style="display: none;">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div :class="`p-3 rounded-lg bg-gradient-to-br ${fileIconClass}`">
                                    <svg class="w-8 h-8" :class="fileIconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900" x-text="selectedFileName"></p>
                                    <div class="flex space-x-3 text-xs text-gray-500 mt-1">
                                        <span x-text="selectedFileType"></span>
                                        <span x-text="selectedFileSize"></span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" 
                                    @click="clearFile"
                                    class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Estado Activo/Inactivo -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active"
                               x-model="formData.is_active"
                               @change="updateProgress"
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Activar documento</span>
                            <span class="text-gray-500">- Los estudiantes podrán ver y descargar este documento inmediatamente</span>
                        </span>
                    </label>
                </div>

                <!-- Información adicional -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Consejos para subir documentos</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Usa nombres de archivo sin espacios ni caracteres especiales</li>
                                        <li>Los documentos en PDF son ideales para lectura y visualización</li>
                                        <li>Verifica que el documento no contenga información sensible</li>
                                        <li>Los estudiantes podrán descargar los documentos activos desde el curso</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" 
                        @click="resetForm"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-xl font-medium transition duration-200">
                    Reiniciar
                </button>
                <button type="submit" 
                        :disabled="isSubmitting"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 rounded-xl font-medium transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                    <svg x-show="isSubmitting" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Subiendo...' : 'Subir Documento'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function documentForm() {
        return {
            formData: {
                title: '',
                description: '',
                is_active: true
            },
            selectedFile: null,
            selectedFileName: '',
            selectedFileType: '',
            selectedFileSize: '',
            fileIconClass: 'from-gray-100 to-gray-200',
            fileIconColor: 'text-gray-600',
            isDragging: false,
            isSubmitting: false,
            formProgress: 0,

            init() {
                this.updateProgress();
            },

            updateProgress() {
                let filled = 0;
                let totalFields = 0;

                // Título (requerido)
                totalFields++;
                if (this.formData.title.trim() !== '') filled++;

                // Archivo (requerido)
                totalFields++;
                if (this.selectedFile !== null) filled++;

                // Descripción (opcional)
                totalFields += 0.5;
                if (this.formData.description.trim() !== '') filled += 0.5;

                // Estado activo (opcional)
                totalFields += 0.3;
                filled += 0.3;

                const percentage = Math.min(100, Math.round((filled / totalFields) * 100));
                this.formProgress = percentage;
            },

            triggerFileInput() {
                document.getElementById('file').click();
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.processFile(file);
                }
            },

            handleDrop(event) {
                this.isDragging = false;
                const file = event.dataTransfer.files[0];
                if (file) {
                    this.processFile(file);
                }
            },

            processFile(file) {
                // Validar tamaño máximo (50MB)
                const maxSize = 50 * 1024 * 1024;
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo demasiado grande',
                        text: 'El tamaño máximo permitido es 50MB'
                    });
                    return;
                }

                // Validar extensión
                const allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar', '7z'];
                const extension = file.name.split('.').pop().toLowerCase();
                
                if (!allowedExtensions.includes(extension)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tipo de archivo no permitido',
                        text: 'Formatos aceptados: PDF, DOC, PPT, XLS, TXT, ZIP, RAR, 7Z'
                    });
                    return;
                }

                this.selectedFile = file;
                this.selectedFileName = file.name;
                this.selectedFileType = extension.toUpperCase();
                this.selectedFileSize = this.formatFileSize(file.size);

                // Cambiar icono según tipo de archivo
                switch(extension) {
                    case 'pdf':
                        this.fileIconClass = 'from-red-100 to-red-200';
                        this.fileIconColor = 'text-red-600';
                        break;
                    case 'doc':
                    case 'docx':
                        this.fileIconClass = 'from-blue-100 to-blue-200';
                        this.fileIconColor = 'text-blue-600';
                        break;
                    case 'ppt':
                    case 'pptx':
                        this.fileIconClass = 'from-orange-100 to-orange-200';
                        this.fileIconColor = 'text-orange-600';
                        break;
                    case 'xls':
                    case 'xlsx':
                        this.fileIconClass = 'from-green-100 to-green-200';
                        this.fileIconColor = 'text-green-600';
                        break;
                    case 'zip':
                    case 'rar':
                    case '7z':
                        this.fileIconClass = 'from-purple-100 to-purple-200';
                        this.fileIconColor = 'text-purple-600';
                        break;
                    default:
                        this.fileIconClass = 'from-gray-100 to-gray-200';
                        this.fileIconColor = 'text-gray-600';
                }

                this.updateProgress();
            },

            clearFile() {
                this.selectedFile = null;
                this.selectedFileName = '';
                this.selectedFileType = '';
                this.selectedFileSize = '';
                document.getElementById('file').value = '';
                this.updateProgress();
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },

            resetForm() {
                Swal.fire({
                    title: '¿Reiniciar formulario?',
                    text: 'Se perderán todos los datos ingresados',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, reiniciar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.formData = {
                            title: '',
                            description: '',
                            is_active: true
                        };
                        this.clearFile();
                        this.updateProgress();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Formulario reiniciado',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            },

            submitForm() {
                // Validaciones
                if (!this.formData.title.trim()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Campo requerido',
                        text: 'Por favor ingresa un título para el documento'
                    });
                    document.getElementById('title').focus();
                    return;
                }

                if (!this.selectedFile) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo requerido',
                        text: 'Por favor selecciona un archivo para subir'
                    });
                    return;
                }

                this.isSubmitting = true;

                const formData = new FormData();
                formData.append('course_id', '{{ $course->id }}');
                formData.append('title', this.formData.title);
                formData.append('description', this.formData.description);
                formData.append('is_active', this.formData.is_active ? '1' : '0');
                formData.append('file', this.selectedFile);

                axios.post('{{ route('admin.documents.store') }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Documento subido!',
                            text: response.data.message,
                            confirmButtonColor: '#3b82f6'
                        }).then(() => {
                            window.location.href = '{{ route('admin.documents.view', $course) }}';
                        });
                    }
                })
                .catch(error => {
                    this.isSubmitting = false;
                    
                    let errorMessage = 'Error al subir el documento';
                    if (error.response && error.response.data && error.response.data.message) {
                        errorMessage = error.response.data.message;
                    } else if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        errorMessage = Object.values(errors).flat().join('\n');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                });
            }
        };
    }
</script>
@endsection