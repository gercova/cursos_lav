@extends('layouts.admin')
@section('title', 'Gestión de Permisos')
@section('content')
<div x-data="permissionManager()" class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Permisos</h1>
            <p class="text-gray-600 mt-1">Administra todos los permisos del sistema</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <button @click="openCreatePermissionModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Nuevo Permiso
            </button>
            <a href="{{ route('admin.roles.index') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-user-shield mr-2"></i> Gestionar Roles
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Total Permisos</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ $permissions->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Permisos Activos</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ $permissions->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-cubes text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Módulos</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ count($modules) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Roles Asignados</h3>
                    <p class="text-3xl font-bold text-gray-900">{{ \Spatie\Permission\Models\Role::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" x-model="searchTerm" @input="filterPermissions()" placeholder="Buscar permisos por nombre o descripción..." class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div>
                    <select x-model="selectedModule" @change="filterPermissions()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los módulos</option>
                        @foreach($modules as $key => $module)
                        <option value="{{ $key }}">{{ $module }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select x-model="selectedAction" @change="filterPermissions()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todas las acciones</option>
                        <option value="view">Ver</option>
                        <option value="create">Crear</option>
                        <option value="edit">Editar</option>
                        <option value="delete">Eliminar</option>
                        <option value="manage">Gestionar</option>
                        <option value="assign">Asignar</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Permisos -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Lista de Permisos</h2>
                <p class="text-gray-600 text-sm">Permisos disponibles en el sistema</p>
            </div>
            <div class="text-sm text-gray-600">
                Mostrando <span x-text="filteredPermissions.length"></span> de {{ $permissions->count() }} permisos
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('name')" class="flex items-center hover:text-gray-700">
                                Permiso
                                <i class="fas fa-sort ml-1" :class="sortField === 'name' ? 'text-blue-500' : 'text-gray-400'"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('module')" class="flex items-center hover:text-gray-700">
                                Módulo
                                <i class="fas fa-sort ml-1" :class="sortField === 'module' ? 'text-blue-500' : 'text-gray-400'"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <button @click="sortBy('action')" class="flex items-center hover:text-gray-700">
                                Acción
                                <i class="fas fa-sort ml-1" :class="sortField === 'action' ? 'text-blue-500' : 'text-gray-400'"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="permission in paginatedPermissions" :key="permission.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-key text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="permission.name"></div>
                                        <div class="text-xs text-gray-500" x-text="'ID: ' + permission.id"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                                      x-text="getModuleName(permission.module)">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                      :class="getActionClass(permission.action)">
                                    <span x-text="capitalizeFirst(permission.action)"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" x-text="permission.description || 'Sin descripción'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                      x-text="permission.roles_count + ' roles'">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a :href="'/admin/permissions/' + permission.id" 
                                       class="text-blue-600 hover:text-blue-900" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('edit_permissions')
                                    <button @click="openEditPermissionModal(permission)" 
                                            class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endcan
                                    @can('delete_permissions')
                                    <button @click="deletePermission(permission.id)" 
                                            class="text-red-600 hover:text-red-900" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredPermissions.length === 0">
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-search text-3xl mb-3"></i>
                                <p class="text-lg">No se encontraron permisos</p>
                                <p class="text-sm">Intenta con otros términos de búsqueda</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="px-6 py-4 border-t" x-show="filteredPermissions.length > 0">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-sm text-gray-700 mb-4 md:mb-0">
                    Mostrando <span x-text="(currentPage - 1) * itemsPerPage + 1"></span> a 
                    <span x-text="Math.min(currentPage * itemsPerPage, filteredPermissions.length)"></span> de 
                    <span x-text="filteredPermissions.length"></span> resultados
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="previousPage()" :disabled="currentPage === 1" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <template x-for="page in visiblePages" :key="page">
                        <button @click="goToPage(page)"
                                class="px-3 py-1 rounded border"
                                :class="page === currentPage 
                                    ? 'border-blue-500 bg-blue-50 text-blue-600' 
                                    : 'border-gray-300 hover:bg-gray-50'">
                            <span x-text="page"></span>
                        </button>
                    </template>
                    
                    <button @click="nextPage()" :disabled="currentPage === totalPages" class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribución por Módulo -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Distribución de Permisos por Módulo</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($modules as $key => $module)
            @php
                $modulePermissions = $permissions->filter(function($permission) use ($key) {
                    $permissionModule = explode('_', $permission->name)[1] ?? 'general';
                    return $permissionModule === $key;
                });
                $percentage = $permissions->count() > 0 ? ($modulePermissions->count() / $permissions->count()) * 100 : 0;
            @endphp
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $module }}</h3>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $modulePermissions->count() }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-800">
                        {{ number_format($percentage, 1) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full" 
                         style="width: {{ $percentage }}%"></div>
                </div>
                <div class="mt-3 text-xs text-gray-600">
                    @if($modulePermissions->count() > 0)
                    <div class="truncate">
                        @foreach($modulePermissions->take(2) as $perm)
                        <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-1 mb-1">
                            {{ explode('_', $perm->name)[0] }}
                        </span>
                        @endforeach
                        @if($modulePermissions->count() > 2)
                        <span class="text-gray-500">+{{ $modulePermissions->count() - 2 }} más</span>
                        @endif
                    </div>
                    @else
                    <span class="text-gray-400 italic">Sin permisos</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Permiso -->
<div x-show="showPermissionModal" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto" 
    x-cloak
    style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="showPermissionModal = false"></div>

        <!-- Modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             @click.stop>
            <form @submit.prevent="savePermission">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-key text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="modalTitle"></h3>
                            <div class="mt-4">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
                                        <select x-model="permissionForm.action" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Seleccionar acción</option>
                                            <option value="view">Ver (view)</option>
                                            <option value="create">Crear (create)</option>
                                            <option value="edit">Editar (edit)</option>
                                            <option value="delete">Eliminar (delete)</option>
                                            <option value="manage">Gestionar (manage)</option>
                                            <option value="assign">Asignar (assign)</option>
                                        </select>
                                        <p class="text-red-500 text-xs mt-1" x-show="permissionErrors.action" x-text="permissionErrors.action"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Módulo</label>
                                        <select x-model="permissionForm.module" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Seleccionar módulo</option>
                                            @foreach($modules as $key => $module)
                                            <option value="{{ $key }}">{{ $module }}</option>
                                            @endforeach
                                        </select>
                                        <p class="text-red-500 text-xs mt-1" x-show="permissionErrors.module" x-text="permissionErrors.module"></p>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Permiso</label>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-2 bg-gray-100 rounded-l-md border border-r-0 border-gray-300 text-gray-600"
                                              x-text="(permissionForm.action ? permissionForm.action + '_' : '') + (permissionForm.module || '')"></span>
                                        <input type="text" x-model="permissionForm.customName" placeholder="Nombre personalizado (opcional)" class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Nombre completo: 
                                        <span class="font-medium" x-text="(permissionForm.action ? permissionForm.action + '_' : '') + (permissionForm.module || '') + (permissionForm.customName ? '_' + permissionForm.customName : '')"></span>
                                    </p>
                                    <p class="text-red-500 text-xs mt-1" x-show="permissionErrors.name" x-text="permissionErrors.name"></p>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                    <textarea x-model="permissionForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Describe la funcionalidad de este permiso..."></textarea>
                                    <p class="text-red-500 text-xs mt-1" x-show="permissionErrors.description" x-text="permissionErrors.description"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-save mr-2"></i>
                        <span x-text="isEditingPermission ? 'Actualizar' : 'Crear'"></span>
                    </button>
                    <button type="button" @click="showPermissionModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div x-show="showConfirmModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" x-cloak style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showConfirmModal = false"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmar eliminación</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="confirmMessage"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="confirmAction()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Eliminar
                </button>
                <button @click="showConfirmModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function permissionManager() {
        return {
            // Estados
            showPermissionModal: false,
            showConfirmModal: false,
            isEditingPermission: false,
            modalTitle: '',
            
            // Datos del formulario
            permissionForm: {
                id: null,
                action: '',
                module: '',
                customName: '',
                description: ''
            },
            
            // Errores
            permissionErrors: {},
            
            // Filtros y búsqueda
            searchTerm: '',
            selectedModule: '',
            selectedAction: '',
            
            // Paginación y ordenamiento
            currentPage: 1,
            itemsPerPage: 10,
            sortField: 'name',
            sortDirection: 'asc',
            
            // Datos originales - VERSIÓN SEGURA
            allPermissions: [],
            
            // Datos filtrados
            filteredPermissions: [],
            
            // Inicialización
            init() {
                // Convertir datos PHP a JavaScript de forma segura
                this.allPermissions = this.parsePermissions(@json($permissions));
                this.filteredPermissions = [...this.allPermissions];
                this.sortPermissions();
            },
            
            // Método para parsear permisos de forma segura
            parsePermissions(permissionsData) {
                if (!permissionsData) return [];
                
                return permissionsData.map(permission => {
                    const parts = permission.name ? permission.name.split('_') : [];
                    return {
                        'id': permission.id || 0,
                        'name': permission.name || '',
                        'description': permission.description || '',
                        'module': parts[1] || 'general',
                        'action': parts[0] || 'unknown',
                        'roles_count': permission.roles_count || 0,
                        'created_at': permission.created_at || '',
                        'updated_at': permission.updated_at || ''
                    };
                });
            },
            
            // Métodos de ayuda
            getModuleName(moduleKey) {
                const modules = @json($modules);
                return modules[moduleKey] || moduleKey;
            },
            
            getActionClass(action) {
                switch(action) {
                    case 'view': return 'bg-blue-100 text-blue-800';
                    case 'create': return 'bg-green-100 text-green-800';
                    case 'edit': return 'bg-yellow-100 text-yellow-800';
                    case 'delete': return 'bg-red-100 text-red-800';
                    case 'manage': return 'bg-purple-100 text-purple-800';
                    case 'assign': return 'bg-pink-100 text-pink-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            },
            
            capitalizeFirst(string) {
                return string ? string.charAt(0).toUpperCase() + string.slice(1) : '';
            },
            
            // Filtrado
            filterPermissions() {
                this.currentPage = 1;
                
                this.filteredPermissions = this.allPermissions.filter(permission => {
                    // Búsqueda por término
                    const matchesSearch = !this.searchTerm || 
                        permission.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                        (permission.description && permission.description.toLowerCase().includes(this.searchTerm.toLowerCase()));
                    
                    // Filtro por módulo
                    const matchesModule = !this.selectedModule || permission.module === this.selectedModule;
                    
                    // Filtro por acción
                    const matchesAction = !this.selectedAction || permission.action === this.selectedAction;
                    
                    return matchesSearch && matchesModule && matchesAction;
                });
                
                this.sortPermissions();
            },
            
            // Ordenamiento
            sortBy(field) {
                if (this.sortField === field) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortDirection = 'asc';
                }
                this.sortPermissions();
            },
            
            sortPermissions() {
                this.filteredPermissions.sort((a, b) => {
                    let aValue = a[this.sortField];
                    let bValue = b[this.sortField];
                    
                    if (this.sortField === 'name' || this.sortField === 'description') {
                        aValue = aValue?.toLowerCase() || '';
                        bValue = bValue?.toLowerCase() || '';
                    }
                    
                    if (aValue < bValue) return this.sortDirection === 'asc' ? -1 : 1;
                    if (aValue > bValue) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            },
            
            // Paginación
            get totalPages() {
                return Math.ceil(this.filteredPermissions.length / this.itemsPerPage);
            },
            
            get paginatedPermissions() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredPermissions.slice(start, end);
            },
            
            get visiblePages() {
                const pages = [];
                const maxVisible = 5;
                let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
                let end = Math.min(this.totalPages, start + maxVisible - 1);
                
                if (end - start + 1 < maxVisible) {
                    start = Math.max(1, end - maxVisible + 1);
                }
                
                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
                
                return pages;
            },
            
            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            },
            
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            },
            
            goToPage(page) {
                this.currentPage = page;
            },
            
            // Modal de crear/editar
            openCreatePermissionModal() {
                this.isEditingPermission = false;
                this.modalTitle = 'Crear Nuevo Permiso';
                this.permissionForm = {
                    id: null,
                    action: '',
                    module: '',
                    customName: '',
                    description: ''
                };
                this.permissionErrors = {};
                this.showPermissionModal = true;
            },
            
            openEditPermissionModal(permission) {
                this.isEditingPermission = true;
                this.modalTitle = 'Editar Permiso: ' + permission.name;
                
                // Parsear nombre para extraer acción, módulo y nombre personalizado
                const parts = permission.name.split('_');
                let action = parts[0];
                let module = parts[1];
                let customName = parts.slice(2).join('_') || '';
                
                this.permissionForm = {
                    id: permission.id,
                    action: action,
                    module: module,
                    customName: customName,
                    description: permission.description || ''
                };
                
                this.permissionErrors = {};
                this.showPermissionModal = true;
            },
            
            savePermission() {
                this.permissionErrors = {};
                
                // Construir nombre completo
                const name = this.permissionForm.action + '_' + 
                        this.permissionForm.module + 
                        (this.permissionForm.customName ? '_' + this.permissionForm.customName : '');
                
                const data = {
                    name: name,
                    module: this.permissionForm.module,
                    description: this.permissionForm.description
                };
                
                const url = this.isEditingPermission 
                    ? `/admin/permissions/${this.permissionForm.id}`
                    : '/admin/permissions';
                
                const method = this.isEditingPermission ? 'PUT' : 'POST';
                
                axios({
                    method: method,
                    url: url,
                    data: data,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.data.success) {
                        this.showPermissionModal = false;
                        window.location.reload();
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.permissionErrors = error.response.data.errors;
                    }
                });
            },
            
            // Eliminar permiso
            deletePermission(permissionId) {
                this.confirmMessage = '¿Estás seguro de que deseas eliminar este permiso? Esta acción no se puede deshacer.';
                this.confirmCallback = () => {
                    axios.delete(`/admin/permissions/${permissionId}`)
                        .then(response => {
                            if (response.data.success) {
                                window.location.reload();
                            } else {
                                alert(response.data.message);
                            }
                        })
                        .catch(error => {
                            if (error.response && error.response.data.message) {
                                alert(error.response.data.message);
                            }
                        })
                        .finally(() => {
                            this.showConfirmModal = false;
                        });
                };
                this.showConfirmModal = true;
            },
            
            confirmAction() {
                if (this.confirmCallback) {
                    this.confirmCallback();
                }
            }
        }
    }
</script>
<style>
    [x-cloak] { 
        display: none !important; 
    }
    
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endsection