@extends('layouts.admin')
@section('title', 'Gestión de Paquetes')
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="packagesManager()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Paquetes de Cursos</h1>
        <a href="{{ route('admin.packages.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-150">
            <i class="fas fa-plus"></i>
            <span>Nuevo Paquete</span>
        </a>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           x-model="search"
                           @input.debounce.300ms="filterPackages"
                           placeholder="Buscar paquetes..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div class="flex gap-2">
                <select x-model="statusFilter" 
                        @change="filterPackages"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="all">Todos los estados</option>
                    <option value="active">Activos</option>
                    <option value="inactive">Inactivos</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla de Paquetes -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cupos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cursos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categorías</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($packages as $package)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $package->name }}</div>
                            <div class="text-sm text-gray-500">Creado: {{ $package->created_at->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($package->promotion_price)
                                    <span class="line-through text-gray-400 text-xs">S/ {{ number_format($package->price, 2) }}</span>
                                    <br>
                                    <span class="text-green-600 font-semibold">S/ {{ number_format($package->promotion_price, 2) }}</span>
                                @else
                                    S/ {{ number_format($package->price, 2) }}
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">S/ {{ number_format($package->price_per_person, 2) }} x persona</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $package->seats }} personas
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($package->courses->take(2) as $course)
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                        {{ Str::limit($course->title, 15) }}
                                    </span>
                                @endforeach
                                @if($package->courses->count() > 2)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                        +{{ $package->courses->count() - 2 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($package->categories->take(2) as $category)
                                    <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                                @if($package->categories->count() > 2)
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                        +{{ $package->categories->count() - 2 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button @click="toggleStatus({{ $package->id }})" 
                                    class="relative inline-flex items-center">
                                <span :class="{
                                    'bg-green-500': {{ $package->is_active ? 'true' : 'false' }},
                                    'bg-gray-300': {{ !$package->is_active ? 'true' : 'false' }}
                                }" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white transition duration-150">
                                    {{ $package->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.packages.edit', $package) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded-full transition duration-150"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="confirmDelete({{ $package->id }}, '{{ $package->name }}')" 
                                        class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-full transition duration-150"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-cubes text-4xl mb-3 text-gray-400"></i>
                                <p class="text-lg mb-2">No hay paquetes creados</p>
                                <p class="text-sm mb-4">Comienza creando tu primer paquete de cursos</p>
                                <a href="{{ route('admin.packages.create') }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                                    <i class="fas fa-plus"></i>
                                    <span>Crear Paquete</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $packages->links() }}
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div x-show="showDeleteModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Eliminar Paquete
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    ¿Estás seguro de que deseas eliminar el paquete <span class="font-semibold" x-text="packageToDelete.name"></span>? Esta acción no se puede deshacer.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="deletePackage"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Eliminar
                    </button>
                    <button type="button" 
                            @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function packagesManager() {
    return {
        search: '',
        statusFilter: 'all',
        showDeleteModal: false,
        packageToDelete: { id: null, name: '' },
        
        filterPackages() {
            // Implementar filtrado con AJAX si es necesario
            // Por ahora recargamos la página con parámetros
            let url = new URL(window.location.href);
            url.searchParams.set('search', this.search);
            url.searchParams.set('status', this.statusFilter);
            window.location.href = url.toString();
        },
        
        toggleStatus(packageId) {
            if (!confirm('¿Estás seguro de cambiar el estado del paquete?')) return;
            
            axios.post(`/admin/packages/${packageId}/toggle-status`)
                .then(response => {
                    if (response.data.success) {
                        // Recargar para mostrar el cambio
                        window.location.reload();
                    }
                })
                .catch(error => {
                    alert('Error al cambiar el estado: ' + (error.response?.data?.message || 'Error desconocido'));
                });
        },
        
        confirmDelete(id, name) {
            this.packageToDelete = { id, name };
            this.showDeleteModal = true;
        },
        
        deletePackage() {
            axios.delete(`/admin/packages/${this.packageToDelete.id}`)
                .then(response => {
                    if (response.data.success) {
                        window.location.reload();
                    } else {
                        alert(response.data.message);
                        this.showDeleteModal = false;
                    }
                })
                .catch(error => {
                    alert('Error al eliminar: ' + (error.response?.data?.message || 'Error desconocido'));
                    this.showDeleteModal = false;
                });
        }
    }
}
</script>
@endsection