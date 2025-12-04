@extends('layouts.admin')

@section('title', 'Editar Documento: ' . $document->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200">
                        @switch($document->file_type)
                            @case('pdf')
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                @break
                            @case('doc')
                            @case('docx')
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                @break
                            @default
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                        @endswitch
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Editar Documento</h1>
                        <p class="text-gray-600 mt-1">{{ $document->title }}</p>
                    </div>
                </div>

                <!-- Badges de estado -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $document->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $document->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        {{ strtoupper($document->file_type) }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                        {{ round($document->file_size / 1024 / 1024, 2) }} MB
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                        {{ $document->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 lg:mt-0">
                <a href="{{ route('admin.documents.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Documentos
                </a>
                <button onclick="toggleDocumentStatus({{ $document->id }})"
                        class="inline-flex items-center gap-2 px-4 py-2.5 {{ $document->is_active ? 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800' : 'bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800' }} text-white rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($document->is_active)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        @endif
                    </svg>
                    {{ $document->is_active ? 'Desactivar' : 'Activar' }}
                </button>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <a href="#"
                   class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Información General
                    </div>
                </a>
                <a href="{{ Storage::url($document->file_path) }}"
                   target="_blank"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Vista Previa
                    </div>
                </a>
                <a href="{{ Storage::url($document->file_path) }}"
                   download
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-8">
        <form action="{{ route('admin.documents.update', $document) }}"
              method="POST"
              enctype="multipart/form-data"
              id="documentForm">

            @include('admin.documents.partials.form')

        </form>
    </div>

    <!-- Estadísticas y Acciones Rápidas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Estadísticas del Documento -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Información del Documento</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-900">{{ strtoupper($document->file_type) }}</div>
                            <div class="text-sm text-blue-700 mt-1">Tipo de Archivo</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-900">{{ round($document->file_size / 1024 / 1024, 2) }}</div>
                            <div class="text-sm text-green-700 mt-1">Tamaño (MB)</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-900">{{ $document->created_at->format('d/m/Y') }}</div>
                            <div class="text-sm text-purple-700 mt-1">Fecha de Subida</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-900">{{ $document->updated_at->format('d/m/Y') }}</div>
                            <div class="text-sm text-orange-700 mt-1">Última Actualización</div>
                        </div>
                    </div>
                </div>

                <!-- Información del curso -->
                <div class="mt-6 p-4 bg-white rounded-xl border border-gray-200">
                    <h4 class="font-medium text-gray-900 mb-4">Información del Curso Asociado</h4>
                    @if($document->course)
                        <div class="flex items-center gap-4">
                            @if($document->course->image_url)
                                <img src="{{ Storage::url($document->course->image_url) }}"
                                     alt="{{ $document->course->title }}"
                                     class="w-16 h-16 rounded-xl object-cover border border-gray-200">
                            @endif
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-900">{{ $document->course->title }}</h5>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                        {{ $document->course->category->name ?? 'Sin categoría' }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $document->course->students_count ?? 0 }} estudiantes
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $document->course->documents_count ?? 0 }} documentos
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('admin.courses.edit', $document->course) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver Curso
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Este documento no está asociado a ningún curso.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm sticky top-6">
                <h3 class="text-xl font-bold text-blue-900 mb-6">Acciones Rápidas</h3>

                <div class="space-y-4">
                    <!-- Descargar Documento -->
                    <a href="{{ Storage::url($document->file_path) }}"
                       download
                       class="flex items-center gap-3 p-4 bg-white rounded-xl border border-blue-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-blue-900">Descargar Documento</h4>
                            <p class="text-sm text-blue-700 mt-1">{{ strtoupper($document->file_type) }} • {{ round($document->file_size / 1024 / 1024, 2) }} MB</p>
                        </div>
                        <svg class="w-5 h-5 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- Vista Previa -->
                    <a href="{{ Storage::url($document->file_path) }}"
                       target="_blank"
                       class="flex items-center gap-3 p-4 bg-white rounded-xl border border-green-200 hover:border-green-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-green-100 to-green-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-green-900">Vista Previa</h4>
                            <p class="text-sm text-green-700 mt-1">Abrir en nueva pestaña</p>
                        </div>
                        <svg class="w-5 h-5 text-green-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- Ver en Lista -->
                    <a href="{{ route('admin.documents.index') }}?course={{ $document->course_id }}"
                       class="flex items-center gap-3 p-4 bg-white rounded-xl border border-purple-200 hover:border-purple-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-purple-900">Ver en Lista</h4>
                            <p class="text-sm text-purple-700 mt-1">Todos los documentos del curso</p>
                        </div>
                        <svg class="w-5 h-5 text-purple-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <!-- Eliminar Documento -->
                    <button onclick="deleteDocument({{ $document->id }})"
                            class="w-full flex items-center gap-3 p-4 bg-white rounded-xl border border-red-200 hover:border-red-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-red-100 to-red-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-red-900">Eliminar Documento</h4>
                            <p class="text-sm text-red-700 mt-1">Eliminar permanentemente</p>
                        </div>
                    </button>

                    <!-- Duplicar Documento -->
                    <button onclick="duplicateDocument({{ $document->id }})"
                            class="w-full flex items-center gap-3 p-4 bg-white rounded-xl border border-orange-200 hover:border-orange-300 hover:shadow-md transition-all duration-200 group">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-orange-900">Duplicar Documento</h4>
                            <p class="text-sm text-orange-700 mt-1">Crear copia con nueva referencia</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Función para cambiar estado del documento
    async function toggleDocumentStatus(documentId) {
        if (!confirm('¿Estás seguro de cambiar el estado del documento?')) {
            return;
        }

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

    // Función para eliminar documento
    async function deleteDocument(documentId) {
        if (!confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer y el archivo será eliminado permanentemente.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/documents/${documentId}`);
            if (response.data.success) {
                showNotification('Documento eliminado exitosamente', 'success');
                setTimeout(() => window.location.href = '{{ route('admin.documents.index') }}', 1000);
            }
        } catch (error) {
            console.error('Error al eliminar documento:', error);
            showNotification('Error al eliminar el documento', 'error');
        }
    }

    // Función para duplicar documento
    async function duplicateDocument(documentId) {
        if (!confirm('¿Deseas crear una copia de este documento?')) {
            return;
        }

        try {
            const response = await axios.post(`/admin/documents/${documentId}/duplicate`);
            if (response.data.success) {
                showNotification('Documento duplicado exitosamente', 'success');
                setTimeout(() => window.location.href = `/admin/documents/${response.data.new_document_id}/edit`, 1500);
            }
        } catch (error) {
            console.error('Error al duplicar documento:', error);
            showNotification('Error al duplicar el documento', 'error');
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

    // Funciones para manejo de archivos (las mismas que en create.blade.php)
    function previewFile(event) {
        const file = event.target.files[0];
        if (file) {
            const filePreview = document.getElementById('file-preview');
            const fileName = document.getElementById('file-name');
            const fileType = document.getElementById('file-type');
            const fileSize = document.getElementById('file-size');
            const fileIcon = document.getElementById('file-icon');

            // Validar tamaño máximo (50MB)
            const maxSize = 50 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('El archivo es demasiado grande. El tamaño máximo permitido es 50MB.');
                event.target.value = '';
                filePreview.classList.add('hidden');
                return;
            }

            // Validar tipo de archivo
            const allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar', '7z'];
            const extension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(extension)) {
                alert('Tipo de archivo no permitido. Formatos aceptados: PDF, DOC, PPT, XLS, TXT, ZIP, RAR, 7Z');
                event.target.value = '';
                filePreview.classList.add('hidden');
                return;
            }

            // Mostrar información del archivo
            fileName.textContent = file.name;
            fileType.textContent = extension.toUpperCase();
            fileSize.textContent = formatFileSize(file.size);

            // Cambiar icono según tipo de archivo
            if (fileIcon) {
                let iconColor = 'text-gray-600';
                let bgColor = 'from-gray-100 to-gray-200';

                switch(extension) {
                    case 'pdf':
                        iconColor = 'text-red-600';
                        bgColor = 'from-red-100 to-red-200';
                        break;
                    case 'doc':
                    case 'docx':
                        iconColor = 'text-blue-600';
                        bgColor = 'from-blue-100 to-blue-200';
                        break;
                    case 'ppt':
                    case 'pptx':
                        iconColor = 'text-orange-600';
                        bgColor = 'from-orange-100 to-orange-200';
                        break;
                    case 'xls':
                    case 'xlsx':
                        iconColor = 'text-green-600';
                        bgColor = 'from-green-100 to-green-200';
                        break;
                    case 'zip':
                    case 'rar':
                    case '7z':
                        iconColor = 'text-purple-600';
                        bgColor = 'from-purple-100 to-purple-200';
                        break;
                }

                fileIcon.className = `p-3 rounded-lg bg-gradient-to-br ${bgColor}`;
                fileIcon.innerHTML = `
                    <svg class="w-8 h-8 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                `;
            }

            filePreview.classList.remove('hidden');
        }
    }

    function clearFile() {
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('file-preview');

        if (fileInput) fileInput.value = '';
        if (filePreview) filePreview.classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function resetForm() {
        if (confirm('¿Estás seguro de reiniciar el formulario? Se perderán todos los cambios no guardados.')) {
            document.getElementById('documentForm').reset();
            clearFile();

            // Restaurar valores originales del documento
            const courseSelect = document.getElementById('course_id');
            if (courseSelect) {
                const originalCourseId = {{ $document->course_id ?? 'null' }};
                if (originalCourseId) {
                    courseSelect.value = originalCourseId;
                    courseSelect.dispatchEvent(new Event('change'));
                }
            }
        }
    }

    // Actualizar información del curso seleccionado
    document.getElementById('course_id')?.addEventListener('change', function(e) {
        const selectedOption = this.options[this.selectedIndex];
        const courseInfo = document.getElementById('course-info');

        if (selectedOption.value) {
            courseInfo.classList.remove('hidden');

            document.getElementById('selected-course-title').textContent = selectedOption.text;
            document.getElementById('selected-course-category').textContent = selectedOption.dataset.category || 'Sin categoría';

            const students = selectedOption.dataset.students || '0';
            document.getElementById('selected-course-students').textContent = `${students} estudiantes`;
        } else {
            courseInfo.classList.add('hidden');
        }
    });

    // Inicializar información del curso
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_id');
        if (courseSelect && courseSelect.value) {
            courseSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
