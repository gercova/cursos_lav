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