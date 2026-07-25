@extends('layouts.admin')

@section('title', 'Roles y Permisos')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Roles y Permisos</h2>
            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="bi bi-plus mr-2"></i> Nuevo Rol
            </button>
        </div>

        <!-- Lista de Roles -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4">Roles del Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($roles as $role)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-bold text-lg">{{ ucfirst($role->name) }}</h4>
                        @if(!in_array($role->name, ['admin', 'instructor', 'student']))
                        <div class="flex space-x-2">
                            <button onclick="openEditModal({{ json_encode($role) }})" class="text-blue-600 hover:text-blue-800">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Estás seguro?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <span class="text-sm text-gray-600">Permisos:</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($role->permissions as $permission)
                            <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">
                                {{ str_replace('_', ' ', $permission->name) }}
                            </span>
                            @endforeach
                            @if($role->permissions->isEmpty())
                            <span class="text-gray-500 text-sm">Sin permisos asignados</span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-600">Usuarios: {{ $role->users_count ?? 0 }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Lista de Permisos -->
        <div>
            <h3 class="text-xl font-semibold mb-4">Todos los Permisos</h3>
            @foreach($permissions as $module => $modulePermissions)
            <div class="mb-6">
                <h4 class="font-bold text-lg capitalize mb-2">{{ $module }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                    @foreach($modulePermissions as $permission)
                    <div class="bg-gray-50 p-3 rounded border">
                        <span class="font-medium">{{ str_replace('_', ' ', $permission->name) }}</span>
                        <p class="text-sm text-gray-500 mt-1">{{ $permission->created_at->format('d/m/Y') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Rol -->
<div id="roleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-bold"></h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <form id="roleForm" method="POST">
            @csrf
            <div id="formMethod" class="hidden"></div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nombre del Rol
                </label>
                <input type="text" id="name" name="name" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-red-500 text-xs mt-1 hidden" id="nameError"></p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Permisos
                </label>
                <div class="max-h-60 overflow-y-auto border rounded-lg p-2">
                    @foreach($permissions as $module => $modulePermissions)
                    <div class="mb-3">
                        <h5 class="font-medium capitalize text-gray-700 mb-2">{{ $module }}</h5>
                        <div class="space-y-1 ml-4">
                            @foreach($modulePermissions as $permission)
                            <label class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                       class="form-checkbox h-4 w-4 text-blue-600 permission-checkbox">
                                <span class="ml-2 text-gray-700">{{ str_replace('_', ' ', $permission->name) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentRoleId = null;

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Crear Nuevo Rol';
    document.getElementById('roleForm').action = "{{ route('admin.roles.store') }}";
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('name').value = '';
    
    // Limpiar checkboxes
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    document.getElementById('roleModal').classList.remove('hidden');
}

function openEditModal(role) {
    currentRoleId = role.id;
    document.getElementById('modalTitle').textContent = 'Editar Rol';
    document.getElementById('roleForm').action = `/admin/roles/${role.id}`;
    document.getElementById('formMethod').innerHTML = '@method("PUT")';
    document.getElementById('name').value = role.name;
    
    // Limpiar checkboxes
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Marcar permisos del rol
    if (role.permissions && role.permissions.length > 0) {
        role.permissions.forEach(permission => {
            const checkbox = document.querySelector(`input[name="permissions[]"][value="${permission.id}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
    
    document.getElementById('roleModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('roleModal').classList.add('hidden');
    document.getElementById('nameError').classList.add('hidden');
}

// Manejar envío del formulario
document.getElementById('roleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    const method = document.getElementById('formMethod').innerHTML.includes('PUT') ? 'PUT' : 'POST';
    
    try {
        const response = await axios({
            method: method,
            url: url,
            data: formData,
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        
        if (response.status === 200) {
            window.location.reload();
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            if (errors.name) {
                document.getElementById('nameError').textContent = errors.name[0];
                document.getElementById('nameError').classList.remove('hidden');
            }
        }
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('roleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection