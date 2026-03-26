@extends('layouts.admin')
@section('title', 'Documentos del curso: ' . $course->title)
@section('content')

<div class="bg-white rounded-lg shadow-sm">
    <!-- Header del curso -->
    <div class="border-b border-gray-200 p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Documentos del curso</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $course->title }}</p>
                @if($course->category)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                        {{ $course->category->name }}
                    </span>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.documents.create', $course) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i> Subir Documento
                </a>
                <a href="{{ route('admin.courses.edit', $course) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Curso
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros y estadísticas -->
    <div class="border-b border-gray-200 p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap gap-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Buscar documentos..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                
                <select id="typeFilter" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los tipos</option>
                    <option value="pdf">PDF</option>
                    <option value="doc">DOC</option>
                    <option value="docx">DOCX</option>
                    <option value="xls">XLS</option>
                    <option value="xlsx">XLSX</option>
                    <option value="ppt">PPT</option>
                    <option value="pptx">PPTX</option>
                    <option value="jpg">JPG</option>
                    <option value="png">PNG</option>
                </select>
                
                <select id="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>
            </div>
            
            <div class="text-sm text-gray-600">
                Total: <span id="totalDocuments">0</span> documentos
            </div>
        </div>
    </div>

    <!-- Tabla de documentos -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody id="documentsTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Los documentos se cargarán vía AJAX -->
                @foreach($documents as $document)
                    <tr class="document-row">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="file-icon {{ $document->file_type }} mr-3">
                                    @php
                                        $iconClass = match($document->file_type) {
                                            'pdf' => 'fa-file-pdf',
                                            'doc', 'docx' => 'fa-file-word',
                                            'xls', 'xlsx' => 'fa-file-excel',
                                            'ppt', 'pptx' => 'fa-file-powerpoint',
                                            'jpg', 'jpeg', 'png', 'gif' => 'fa-file-image',
                                            default => 'fa-file'
                                        };
                                    @endphp
                                    <i class="fas {{ $iconClass }}"></i>
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $document->title }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500 max-w-xs truncate">
                                {{ $document->description ?? 'Sin descripción' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 uppercase">
                                {{ $document->file_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $bytes = $document->file_size;
                                $formattedSize = '0 B';
                                if ($bytes > 0) {
                                    $i = floor(log($bytes) / log(1024));
                                    $formattedSize = round($bytes / pow(1024, $i), 2) . ' ' . ['B', 'KB', 'MB', 'GB'][$i];
                                }
                            @endphp
                            {{ $formattedSize }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button" 
                                    data-id="{{ $document->id }}" 
                                    class="toggle-status-btn inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $document->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $document->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900" title="Ver archivo">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" data-id="{{ $document->id }}" class="duplicate-document-btn text-purple-600 hover:text-purple-900" title="Duplicar">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <a href="{{ route('admin.documents.edit', $document) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        data-id="{{ $document->id }}" 
                                        data-title="{{ $document->title }}" 
                                        class="delete-document-btn text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach

                @if($documents->isEmpty())
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            No se encontraron documentos para este curso.
                        </td>
                    </tr>
                @endif

                <div id="totalDocuments" style="display:none">{{ $documents->total() }}</div>

                <div id="pagination" style="display:none">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($documents->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white"> Anterior </span>
                        @else
                            <button onclick="changePage({{ $documents->currentPage() - 1 }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Anterior </button>
                        @endif

                        @if ($documents->hasMorePages())
                            <button onclick="changePage({{ $documents->currentPage() + 1 }})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Siguiente </button>
                        @else
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white"> Siguiente </span>
                        @endif
                    </div>
                    
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between w-full">
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando <span class="font-medium">{{ $documents->firstItem() ?? 0 }}</span> a <span class="font-medium">{{ $documents->lastItem() ?? 0 }}</span> de <span class="font-medium">{{ $documents->total() }}</span> resultados
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @for ($i = 1; $i <= $documents->lastPage(); $i++)
                                    <button onclick="changePage({{ $i }})" 
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium {{ $i == $documents->currentPage() ? 'text-blue-600 bg-blue-50 border-blue-500 z-10' : 'text-gray-500 hover:bg-gray-50' }}">
                                        {{ $i }}
                                    </button>
                                @endfor
                            </nav>
                        </div>
                    </div>
                </div>
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="border-t border-gray-200 px-6 py-4">
        <div id="pagination" class="flex justify-between items-center">
            <!-- La paginación se cargará vía AJAX -->
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let currentPage = 1;
    let searchTerm = '';
    let typeFilter = '';
    let statusFilter = '';
    
    // Función para cargar documentos
    // Función para cargar documentos
    function loadDocuments() {
        const params = new URLSearchParams({
            page: currentPage,
            search: searchTerm,
            type: typeFilter,
            status: statusFilter
        });

        // ¡AQUÍ ESTÁ LA MAGIA PAPU! Le decimos explícitamente a Laravel que es AJAX
        axios.get(`{{ route('admin.documents.view', $course->id) }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const tempDiv = document.createElement('div');
            
            // Envolvemos el HTML recibido en una tabla virtual
            tempDiv.innerHTML = `<table><tbody>${response.data}</tbody></table>`;
            
            // 1. Extraer e insertar SOLO las filas de la tabla (<tr>)
            const tbodyElements = tempDiv.querySelector('tbody');
            if (tbodyElements) {
                const rows = Array.from(tbodyElements.querySelectorAll('tr'));
                const targetBody = document.getElementById('documentsTableBody');
                targetBody.innerHTML = ''; // Limpiamos la tabla actual
                
                // Si hay filas, las inyectamos
                if (rows.length > 0) {
                    rows.forEach(row => targetBody.appendChild(row));
                }
            }
            
            // 2. Extraer e insertar la paginación
            // El navegador sacará los divs fuera de la tabla automáticamente, así que los buscamos en tempDiv
            const tempPagination = tempDiv.querySelector('#pagination');
            const targetPagination = document.querySelector('.border-t #pagination');
            if (tempPagination && targetPagination) {
                targetPagination.innerHTML = tempPagination.innerHTML;
            }
            
            // 3. Extraer e insertar el contador total
            const tempTotal = tempDiv.querySelector('#totalDocuments');
            if (tempTotal) {
                document.getElementById('totalDocuments').textContent = tempTotal.textContent;
            }
            
            // Volver a "despertar" los botones de los nuevos elementos
            attachDocumentEvents();
        })
        .catch(error => {
            console.error('Error al cargar documentos:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los documentos'
            });
        });
    }

    // Función para adjuntar eventos a los documentos
    function attachDocumentEvents() {
        // Botones de toggle status
        document.querySelectorAll('.toggle-status-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const documentId = this.dataset.id;
                toggleDocumentStatus(documentId);
            });
        });
        
        // Botones de eliminar
        document.querySelectorAll('.delete-document-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const documentId = this.dataset.id;
                const documentTitle = this.dataset.title;
                deleteDocument(documentId, documentTitle);
            });
        });
        
        // Botones de duplicar
        document.querySelectorAll('.duplicate-document-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const documentId = this.dataset.id;
                duplicateDocument(documentId);
            });
        });
    }

    // Función para cambiar estado del documento
    function toggleDocumentStatus(documentId) {
        axios.post(`/admin/documents/${documentId}/toggle-status`)
            .then(response => {
                if (response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadDocuments(); // Recargar la tabla
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cambiar el estado del documento'
                });
            });
    }

    // Función para eliminar documento
    function deleteDocument(documentId, documentTitle) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas eliminar el documento "${documentTitle}"? Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/admin/documents/${documentId}`)
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: response.data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadDocuments(); // Recargar la tabla
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el documento'
                        });
                    });
            }
        });
    }

    // Función para duplicar documento
    function duplicateDocument(documentId) {
        axios.post(`/admin/documents/${documentId}/duplicate`)
            .then(response => {
                if (response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Duplicado',
                        text: response.data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadDocuments(); // Recargar la tabla
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo duplicar el documento'
                });
            });
    }

    // Eventos de filtros
    document.getElementById('searchInput').addEventListener('input', function(e) {
        searchTerm = e.target.value;
        currentPage = 1;
        loadDocuments();
    });
    
    document.getElementById('typeFilter').addEventListener('change', function(e) {
        typeFilter = e.target.value;
        currentPage = 1;
        loadDocuments();
    });
    
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        statusFilter = e.target.value;
        currentPage = 1;
        loadDocuments();
    });
    
    // Función para cambiar de página (se llamará desde los enlaces de paginación)
    window.changePage = function(page) {
        currentPage = page;
        loadDocuments();
    };
    
    // Cargar documentos al inicio
    document.addEventListener('DOMContentLoaded', function() {
        loadDocuments();
    });
</script>

<!-- SweetAlert2 para mejor experiencia -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .document-row:hover {
        background-color: #f9fafb;
        transition: background-color 0.2s;
    }
    
    .file-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background-color: #f3f4f6;
    }
    
    .file-icon.pdf { background-color: #fee2e2; color: #dc2626; }
    .file-icon.word { background-color: #e0e7ff; color: #3b82f6; }
    .file-icon.excel { background-color: #dcfce7; color: #10b981; }
    .file-icon.image { background-color: #fef3c7; color: #f59e0b; }
    .file-icon.powerpoint { background-color: #fed7aa; color: #ea580c; }
    .file-icon.default { background-color: #e5e7eb; color: #6b7280; }
</style>
@endsection