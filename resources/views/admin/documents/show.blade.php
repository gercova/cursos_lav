@extends('layouts.admin')
@section('title', 'Documento: ' . $document->title)
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-3 rounded-xl bg-gradient-to-br
                        @switch($document->file_type)
                            @case('pdf') from-red-100 to-red-200 @break
                            @case('doc') @case('docx') from-blue-100 to-blue-200 @break
                            @case('ppt') @case('pptx') from-orange-100 to-orange-200 @break
                            @case('xls') @case('xlsx') from-green-100 to-green-200 @break
                            @default from-gray-100 to-gray-200
                        @endswitch">
                        <svg class="w-8 h-8
                            @switch($document->file_type)
                                @case('pdf') text-red-600 @break
                                @case('doc') @case('docx') text-blue-600 @break
                                @case('ppt') @case('pptx') text-orange-600 @break
                                @case('xls') @case('xlsx') text-green-600 @break
                                @default text-gray-600
                            @endswitch"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $document->title }}</h1>
                        <p class="text-gray-600 mt-1">
                            {{ $document->course->title ?? 'Sin curso asignado' }}
                        </p>
                    </div>
                </div>

                <!-- Badges -->
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
                        Subido: {{ $document->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 lg:mt-0">
                <a href="{{ route('admin.documents.edit', $document) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('admin.documents.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal -->
        <div class="lg:col-span-2">
            <!-- Previsualización -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Vista Previa</h3>

                    @if(in_array($document->file_type, ['pdf', 'doc', 'docx', 'txt']))
                        <!-- Vista para documentos de texto -->
                        <div class="border border-gray-200 rounded-xl p-6 bg-gray-50 min-h-[400px]">
                            <div class="text-center py-16">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br
                                    @switch($document->file_type)
                                        @case('pdf') from-red-100 to-red-200 @break
                                        @case('doc') @case('docx') from-blue-100 to-blue-200 @break
                                        @default from-gray-100 to-gray-200
                                    @endswitch mb-6">
                                    <svg class="w-10 h-10
                                        @switch($document->file_type)
                                            @case('pdf') text-red-600 @break
                                            @case('doc') @case('docx') text-blue-600 @break
                                            @default text-gray-600
                                        @endswitch"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $document->title }}</h4>
                                <p class="text-gray-600 mb-4">Para ver el contenido completo del archivo, descárgalo o ábrelo en una nueva pestaña.</p>
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ Storage::url($document->file_path) }}"
                                       target="_blank"
                                       class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg font-medium transition duration-200">
                                        Abrir en nueva pestaña
                                    </a>
                                    <a href="{{ Storage::url($document->file_path) }}"
                                       download
                                       class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition duration-200">
                                        Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Vista para otros tipos de archivo -->
                        <div class="border border-gray-200 rounded-xl p-6 bg-gray-50 min-h-[400px]">
                            <div class="text-center py-16">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Archivo {{ strtoupper($document->file_type) }}</h4>
                                <p class="text-gray-600 mb-4">Este tipo de archivo no puede mostrarse en vista previa. Descárgalo para ver su contenido.</p>
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ Storage::url($document->file_path) }}"
                                       download
                                       class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 rounded-lg font-medium transition duration-200">
                                        Descargar Archivo
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información detallada -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Información del Documento</h3>

                    <div class="space-y-6">
                        <!-- Descripción -->
                        @if($document->description)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Descripción</h4>
                                <p class="text-gray-600 whitespace-pre-line">{{ $document->description }}</p>
                            </div>
                        @endif

                        <!-- Información técnica -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Información Técnica</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                                    <div class="text-center">
                                        <div class="text-sm text-blue-700">Tipo</div>
                                        <div class="text-lg font-bold text-blue-900 mt-1">{{ strtoupper($document->file_type) }}</div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                                    <div class="text-center">
                                        <div class="text-sm text-green-700">Tamaño</div>
                                        <div class="text-lg font-bold text-green-900 mt-1">{{ round($document->file_size / 1024 / 1024, 2) }} MB</div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                                    <div class="text-center">
                                        <div class="text-sm text-purple-700">Subido</div>
                                        <div class="text-lg font-bold text-purple-900 mt-1">{{ $document->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                                    <div class="text-center">
                                        <div class="text-sm text-orange-700">Actualizado</div>
                                        <div class="text-lg font-bold text-orange-900 mt-1">{{ $document->updated_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ruta del archivo -->
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Ubicación del Archivo</h4>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <code class="text-sm text-gray-700 break-all">{{ $document->file_path }}</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div>
            <!-- Información del curso -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm mb-6">
                <h3 class="text-xl font-bold text-blue-900 mb-4">Curso Asociado</h3>

                @if($document->course)
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            @if($document->course->image_url)
                                <img src="{{ Storage::url($document->course->image_url) }}"
                                     alt="{{ $document->course->title }}"
                                     class="w-16 h-16 rounded-xl object-cover border border-blue-200">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-900">{{ $document->course->title }}</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    {{ $document->course->category->name ?? 'Sin categoría' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-white rounded-lg border border-blue-200">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-900">{{ $document->course->students_count ?? 0 }}</div>
                                    <div class="text-xs text-blue-700 mt-1">Estudiantes</div>
                                </div>
                            </div>
                            <div class="p-3 bg-white rounded-lg border border-blue-200">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-900">{{ $document->course->documents_count ?? 0 }}</div>
                                    <div class="text-xs text-blue-700 mt-1">Documentos</div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-blue-200">
                            <a href="{{ route('admin.courses.edit', $document->course) }}"
                               class="block text-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 rounded-lg font-medium transition duration-200">
                                Ver Curso
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-blue-700">Este documento no está asociado a ningún curso.</p>
                @endif
            </div>

            <!-- Acciones rápidas -->
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Acciones Rápidas</h3>

                <div class="space-y-3">
                    <a href="{{ Storage::url($document->file_path) }}"
                       download
                       class="flex items-center gap-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-green-100 to-green-200">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">Descargar Documento</span>
                    </a>

                    <a href="{{ Storage::url($document->file_path) }}"
                       target="_blank"
                       class="flex items-center gap-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">Vista Previa</span>
                    </a>

                    <a href="{{ route('admin.documents.edit', $document) }}"
                       class="flex items-center gap-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">Editar Información</span>
                    </a>

                    <button onclick="toggleDocumentStatus({{ $document->id }})"
                            class="w-full flex items-center gap-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br {{ $document->is_active ? 'from-red-100 to-red-200' : 'from-green-100 to-green-200' }}">
                            <svg class="w-5 h-5 {{ $document->is_active ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($document->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                @endif
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">
                            {{ $document->is_active ? 'Desactivar' : 'Activar' }} Documento
                        </span>
                    </button>

                    <button onclick="deleteDocument({{ $document->id }})"
                            class="w-full flex items-center gap-3 p-3 bg-white hover:bg-red-50 rounded-lg border border-red-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-red-100 to-red-200">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-red-700">Eliminar Documento</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endisset

@section('scripts')
<script>
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

    async function deleteDocument(documentId) {
        if (!confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer.')) {
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
@endsection
