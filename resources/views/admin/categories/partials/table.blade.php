@if($categories->isEmpty())
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay categorías aún</h3>
        <p class="text-gray-500 mb-6">Comienza organizando tus cursos creando tu primera categoría.</p>
        <button onclick="openModal('createCategoryModal')" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Crear Primera Categoría
        </button>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cursos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">{{ strtoupper(substr($category->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                                <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $category->description ?? 'Sin descripción' }}</div>
                    </td>
                    <!-- Estado -->
                    <td class="px-6 py-5">
                        <div class="inline-flex items-center gap-2">
                            <span :class="{'bg-gradient-to-r from-green-100 to-green-200 text-green-800': {{ $category->is_active }}, 'bg-gradient-to-r from-red-100 to-red-200 text-green-800': !{{ $category->is_active }}}" class="px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                            <button onclick="toggleStatus({{ $category->id }})" class="text-gray-400 hover:text-gray-600 transition-colors" title="{{ $category->is_active ? 'Desactivar' : 'Activar' }}">
                                @if($category->is_active)
                                    <i class="bi bi-toggle-on"></i>
                                @else
                                    <i class="bi bi-toggle-off"></i>
                                @endif
                            </button>
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <i class="fas fa-book mr-1"></i> {{ $category->courses_count ?? 0 }} cursos
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $category->created_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $category->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <!-- Editar -->
                        <x-modal title="Editar Categoría">
                            <x-slot name="trigger">
                                <button class="p-2 text-blue-600 hover:text-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 rounded-lg transition-all duration-200 group/edit"
                                        title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                            </x-slot>
                            @include('admin.categories.partials.form', ['category' => $category])
                        </x-modal>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
