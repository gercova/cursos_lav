@if($documents->count() > 0)
    @foreach($documents as $document)
    <tr class="document-row">
        <td class="px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 file-icon {{ $document->file_type }} mr-3">
                    <i class="fas fa-file-{{ 
                        in_array($document->file_type, ['pdf']) ? 'pdf' : 
                        (in_array($document->file_type, ['doc', 'docx']) ? 'word' : 
                        (in_array($document->file_type, ['xls', 'xlsx']) ? 'excel' : 
                        (in_array($document->file_type, ['ppt', 'pptx']) ? 'powerpoint' : 
                        (in_array($document->file_type, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'default'))))
                    }}"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-900">
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="hover:text-blue-600">
                            {{ $document->title }}
                        </a>
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-500 max-w-xs truncate">
                {{ $document->description ?: 'Sin descripción' }}
            </div>
        </td>
        <td class="px-6 py-4">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                {{ strtoupper($document->file_type) }}
            </span>
        </td>
        <td class="px-6 py-4 text-sm text-gray-500">
            {{ number_format($document->file_size / 1024, 2) }} KB
        </td>
        <td class="px-6 py-4">
            <button class="toggle-status-btn px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $document->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                    data-id="{{ $document->id }}">
                {{ $document->is_active ? 'Activo' : 'Inactivo' }}
            </button>
        </td>
        <td class="px-6 py-4 text-sm text-gray-500">
            {{ $document->created_at->format('d/m/Y H:i') }}
        </td>
        <td class="px-6 py-4 text-right text-sm font-medium">
            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.documents.show', $document) }}" 
                   class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.documents.edit', $document) }}" 
                   class="text-yellow-600 hover:text-yellow-900" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <button class="duplicate-document-btn text-green-600 hover:text-green-900" 
                        data-id="{{ $document->id }}" title="Duplicar">
                    <i class="fas fa-copy"></i>
                </button>
                <a href="{{ Storage::url($document->file_path) }}" 
                   target="_blank" class="text-gray-600 hover:text-gray-900" title="Descargar">
                    <i class="fas fa-download"></i>
                </a>
                <button class="delete-document-btn text-red-600 hover:text-red-900" 
                        data-id="{{ $document->id }}" 
                        data-title="{{ $document->title }}" 
                        title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
            <i class="fas fa-folder-open text-4xl mb-3"></i>
            <p class="text-lg">No hay documentos disponibles</p>
            <p class="text-sm mt-1">Sube tu primer documento para este curso</p>
        </td>
    </tr>
@endif

<!-- Paginación -->
<div class="flex justify-between items-center">
    <div class="text-sm text-gray-700">
        Mostrando {{ $documents->firstItem() ?? 0 }} a {{ $documents->lastItem() ?? 0 }} de {{ $documents->total() }} resultados
    </div>
    <div class="flex space-x-2">
        @if($documents->onFirstPage())
            <span class="px-3 py-1 border rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Anterior</span>
        @else
            <button onclick="changePage({{ $documents->currentPage() - 1 }})" 
                    class="px-3 py-1 border rounded-md hover:bg-gray-50">Anterior</button>
        @endif
        
        @php
            $start = max(1, $documents->currentPage() - 2);
            $end = min($documents->lastPage(), $documents->currentPage() + 2);
        @endphp
        
        @if($start > 1)
            <button onclick="changePage(1)" class="px-3 py-1 border rounded-md hover:bg-gray-50">1</button>
            @if($start > 2)
                <span class="px-3 py-1">...</span>
            @endif
        @endif
        
        @for($i = $start; $i <= $end; $i++)
            <button onclick="changePage({{ $i }})" 
                    class="px-3 py-1 border rounded-md {{ $i == $documents->currentPage() ? 'bg-blue-600 text-white' : 'hover:bg-gray-50' }}">
                {{ $i }}
            </button>
        @endfor
        
        @if($end < $documents->lastPage())
            @if($end < $documents->lastPage() - 1)
                <span class="px-3 py-1">...</span>
            @endif
            <button onclick="changePage({{ $documents->lastPage() }})" 
                    class="px-3 py-1 border rounded-md hover:bg-gray-50">{{ $documents->lastPage() }}</button>
        @endif
        
        @if($documents->hasMorePages())
            <button onclick="changePage({{ $documents->currentPage() + 1 }})" 
                    class="px-3 py-1 border rounded-md hover:bg-gray-50">Siguiente</button>
        @else
            <span class="px-3 py-1 border rounded-md bg-gray-100 text-gray-400 cursor-not-allowed">Siguiente</span>
        @endif
    </div>
</div>