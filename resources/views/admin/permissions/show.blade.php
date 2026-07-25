@extends('layouts.admin')
@section('title', 'Detalles del Permiso: ' . $permission->name)
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detalles del Permiso</h1>
            <p class="text-gray-600 mt-1">Información completa y asignaciones del permiso</p>
        </div>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <a href="{{ route('admin.permissions.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Volver a Permisos
            </a>
            @can('edit_permissions')
            <button onclick="openEditPermissionModal({{ $permission->id }})" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="bi bi-pencil-square mr-2"></i> Editar
            </button>
            @endcan
            @can('delete_permissions')
            <button onclick="confirmDeletePermission({{ $permission->id }})" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="bi bi-trash mr-2"></i> Eliminar
            </button>
            @endcan
        </div>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="bi bi-key text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Información del Permiso</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">Nombre:</p>
                        <p class="text-lg font-bold text-gray-900">{{ $permission->name }}</p>
                        <p class="text-sm text-gray-600 mt-1">Descripción:</p>
                        <p class="text-gray-900">{{ $permission->description ?? 'Sin descripción' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="bi bi-shield text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Roles con este Permiso</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalRoles }}</p>
                    <p class="text-sm text-gray-600">roles asignados</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="bi bi-people text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800">Usuarios con este Permiso</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                    <p class="text-sm text-gray-600">usuarios afectados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Detallada -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Detalles del Permiso -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">Información Detallada</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Permiso:</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $permission->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Acción:</label>
                            <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($action == 'view') bg-blue-100 text-blue-800
                                @elseif($action == 'create') bg-green-100 text-green-800
                                @elseif($action == 'edit') bg-yellow-100 text-yellow-800
                                @elseif($action == 'delete') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($action) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Módulo:</label>
                            <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                {{ $moduleName }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Guard:</label>
                            <p class="mt-1 text-gray-900">{{ $permission->guard_name }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción:</label>
                        <p class="mt-1 text-gray-900">{{ $permission->description ?? 'No hay descripción disponible.' }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Creado el:</label>
                            <p class="mt-1 text-gray-900">{{ $permission->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Última actualización:</label>
                            <p class="mt-1 text-gray-900">{{ $permission->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Asignados -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Roles con este Permiso</h2>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $totalRoles }} roles
                    </span>
                </div>
            </div>
            <div class="p-6">
                @if($rolesWithPermission->count() > 0)
                <div class="space-y-4">
                    @foreach($rolesWithPermission as $role)
                    <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full flex items-center justify-center 
                                @if($role->name == 'admin') bg-red-100 text-red-600
                                @elseif($role->name == 'instructor') bg-blue-100 text-blue-600
                                @elseif($role->name == 'student') bg-green-100 text-green-600
                                @else bg-gray-100 text-gray-600 @endif">
                                <i class="bi bi-shield"></i>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-medium text-gray-900 capitalize">{{ $role->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $role->users_count ?? 0 }} usuarios</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.roles.index') }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm">
                            Ver rol
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="bi bi-shield text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay roles asignados</h3>
                    <p class="text-gray-600">Este permiso no está asignado a ningún rol.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Usuarios con Permiso Directo -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Usuarios con Permiso Directo</h2>
                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $usersWithPermission->count() }} usuarios
                </span>
            </div>
            <p class="text-gray-600 text-sm mt-1">Usuarios que tienen este permiso asignado directamente (no a través de roles)</p>
        </div>
        <div class="p-6">
            @if($usersWithPermission->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($usersWithPermission as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full overflow-hidden">
                                        @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                             alt="{{ $user->name }}" 
                                             class="h-10 w-10 rounded-full">
                                        @else
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="bi bi-person text-blue-600"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->role == 'admin') bg-red-100 text-red-800
                                    @elseif($user->role == 'instructor') bg-blue-100 text-blue-800
                                    @elseif($user->role == 'student') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="bi bi-people text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay usuarios directos</h3>
                <p class="text-gray-600">Ningún usuario tiene este permiso asignado directamente.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Editar Permiso -->
<div id="editPermissionModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             onclick="closeEditPermissionModal()"></div>

        <!-- Modal -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="editPermissionForm">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bi bi-pencil-square text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Editar Permiso</h3>
                            <div class="mt-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Permiso</label>
                                    <input type="text" id="permissionName" name="name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <p class="text-red-500 text-xs mt-1" id="nameError"></p>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                                    <textarea id="permissionDescription" name="description" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    <p class="text-red-500 text-xs mt-1" id="descriptionError"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="bi bi-save mr-2"></i> Actualizar
                    </button>
                    <button type="button" 
                            onclick="closeEditPermissionModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div id="confirmDeleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             onclick="closeDeleteModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="bi bi-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmar eliminación</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="deleteMessage">¿Estás seguro de que deseas eliminar este permiso?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="deletePermission()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Eliminar
                </button>
                <button onclick="closeDeleteModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentPermissionId = null;
    
    function openEditPermissionModal(permissionId) {
        currentPermissionId = permissionId;
        
        // Limpiar errores anteriores
        document.getElementById('nameError').textContent = '';
        document.getElementById('descriptionError').textContent = '';
        
        // Obtener datos del permiso
        axios.get(`/admin/permissions/${permissionId}/edit`)
            .then(response => {
                if (response.data.success) {
                    const permission = response.data.permission;
                    document.getElementById('permissionName').value = permission.name;
                    document.getElementById('permissionDescription').value = permission.description || '';
                    
                    // Mostrar modal
                    document.getElementById('editPermissionModal').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error al cargar permiso:', error);
                alert('Error al cargar los datos del permiso');
            });
    }
    
    function closeEditPermissionModal() {
        document.getElementById('editPermissionModal').classList.add('hidden');
        currentPermissionId = null;
    }
    
    // Formulario de edición
    document.getElementById('editPermissionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: document.getElementById('permissionName').value,
            description: document.getElementById('permissionDescription').value,
            module: 'permissions' // Default module for edit
        };
        
        // Limpiar errores
        document.getElementById('nameError').textContent = '';
        document.getElementById('descriptionError').textContent = '';
        
        axios.put(`/admin/permissions/${currentPermissionId}`, formData)
            .then(response => {
                if (response.data.success) {
                    alert('Permiso actualizado exitosamente');
                    window.location.reload();
                }
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    if (errors.name) {
                        document.getElementById('nameError').textContent = errors.name[0];
                    }
                    if (errors.description) {
                        document.getElementById('descriptionError').textContent = errors.description[0];
                    }
                } else {
                    alert('Error al actualizar el permiso');
                }
            });
    });
    
    // Funciones para eliminar permiso
    function confirmDeletePermission(permissionId) {
        currentPermissionId = permissionId;
        document.getElementById('confirmDeleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('confirmDeleteModal').classList.add('hidden');
        currentPermissionId = null;
    }
    
    function deletePermission() {
        axios.delete(`/admin/permissions/${currentPermissionId}`)
            .then(response => {
                if (response.data.success) {
                    alert('Permiso eliminado exitosamente');
                    window.location.href = '{{ route("admin.permissions.index") }}';
                } else {
                    alert(response.data.message || 'Error al eliminar el permiso');
                }
            })
            .catch(error => {
                if (error.response && error.response.data.message) {
                    alert(error.response.data.message);
                } else {
                    alert('Error al eliminar el permiso');
                }
            })
            .finally(() => {
                closeDeleteModal();
            });
    }
</script>
@endsection