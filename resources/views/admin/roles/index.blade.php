@extends('layouts.admin')

@section('title', 'Gestión de Roles y Permisos')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-shield-lock text-blue-600"></i> Gestión de Roles y Permisos
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Administra los usuarios del sistema, sus roles asignados y consulta su matriz de permisos por módulos.
                </p>
            </div>
            <div>
                <button onclick="openCreateModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold inline-flex items-center gap-2 transition-colors shadow-sm">
                    <i class="bi bi-plus-circle"></i> Nuevo Rol
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center">
                <div class="p-3.5 rounded-xl bg-blue-50 text-blue-600 mr-4">
                    <i class="bi bi-people text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Usuarios</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center">
                <div class="p-3.5 rounded-xl bg-purple-50 text-purple-600 mr-4">
                    <i class="bi bi-shield-check text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Administradores</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['admins'] }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center">
                <div class="p-3.5 rounded-xl bg-indigo-50 text-indigo-600 mr-4">
                    <i class="bi bi-person-badge text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Instructores</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['instructors'] }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center">
                <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-600 mr-4">
                    <i class="bi bi-mortarboard text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiantes</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['students'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Filtros y Tabla de Usuarios -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Usuarios del Sistema y Permisos</h2>
                    <p class="text-xs text-gray-500">Selecciona un usuario para consultar sus roles y matriz de accesos por módulo.</p>
                </div>
                <form method="GET" action="{{ route('admin.roles.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por Nombre, Email o DNI..."
                            class="pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                    </div>

                    <select name="role"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Todos los Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>Instructor</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Estudiante</option>
                    </select>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="bi bi-filter"></i> Filtrar
                    </button>
                </form>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">DNI</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol Asignado</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            @php
                                $roleName = $user->role ?? ($user->roles->first()->name ?? 'student');
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-700 font-bold rounded-full flex items-center justify-center text-sm border border-blue-200">
                                            {{ strtoupper(substr($user->names ?? 'U', 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->names }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                    {{ $user->dni ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($roleName === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                            <i class="bi bi-shield-check mr-1 text-purple-600"></i> Administrador
                                        </span>
                                    @elseif($roleName === 'instructor')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                            <i class="bi bi-person-badge mr-1 text-blue-600"></i> Instructor
                                        </span>
                                    @elseif($roleName === 'student')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="bi bi-mortarboard mr-1 text-emerald-600"></i> Estudiante
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                            <i class="bi bi-person mr-1 text-gray-600"></i> {{ ucfirst($roleName) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button onclick='openUserPermissionsModal(@json($user))'
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-xs font-semibold rounded-lg transition-colors"
                                        title="Ver permisos y accesos del usuario">
                                        <i class="bi bi-shield-lock mr-1.5 text-sm"></i> Ver Permisos y Módulos
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">
                                    No se encontraron usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Sección de Roles Configurados del Sistema -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Roles Configurados del Sistema</h3>
                    <p class="text-xs text-gray-500">Lista de roles globales y sus permisos asociados.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($roles as $role)
                    <div class="border border-gray-200 rounded-xl p-5 bg-white shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-bold text-base text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-shield text-blue-600"></i> {{ ucfirst($role->name) }}
                                </h4>
                                @if (!in_array($role->name, ['admin', 'instructor', 'student']))
                                    <div class="flex space-x-2">
                                        <button onclick="openEditModal({{ json_encode($role) }})"
                                            class="text-blue-600 hover:text-blue-800 p-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-1"
                                                onclick="return confirm('¿Estás seguro de eliminar este rol?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">Permisos Asignados:</span>
                                <div class="flex flex-wrap gap-1.5 max-h-36 overflow-y-auto pr-1">
                                    @forelse($role->permissions as $permission)
                                        <span class="bg-gray-100 text-gray-700 text-[11px] font-medium px-2 py-0.5 rounded border border-gray-200">
                                            {{ str_replace('_', ' ', $permission->name) }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs italic">Sin permisos específicos</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                            <span>Usuarios asignados:</span>
                            <span class="font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded-full">{{ $role->users_count ?? 0 }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- MODAL 1: Permisos y Módulos del Usuario (Editar Rol y Asignar/Revocar Permisos) -->
    <div id="userPermissionsModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-10 mx-auto p-6 border-0 w-full max-w-4xl shadow-2xl rounded-2xl bg-white mb-10">
            <!-- Header Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="bi bi-person-lock text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Editar Rol y Permisos de Usuario</h3>
                        <p class="text-xs text-gray-500">Modifica el rol del usuario y asigna o revoca permisos por módulos del sistema</p>
                    </div>
                </div>
                <button type="button" onclick="closeUserPermissionsModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Header Info del Usuario & Selector de Rol -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 mb-6 gap-4">
                <div>
                    <h4 class="text-base font-bold text-gray-900" id="userModalName">---</h4>
                    <p class="text-xs text-gray-500" id="userModalEmail">---</p>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Cambiar Rol:</label>
                    <select id="userModalRoleSelect" onchange="onModalRoleChange(this.value)"
                        class="px-3 py-1.5 text-xs font-semibold border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-xs">
                        <option value="student">Estudiante</option>
                        <option value="instructor">Instructor</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
            </div>

            <!-- CONTENIDO MODAL: Restricción para Estudiantes vs Matriz de Edición para Admin/Instructor -->
            <!-- Alerta para Estudiantes -->
            <div id="studentWarningContainer" class="hidden p-6 bg-amber-50 border border-amber-200 rounded-xl text-amber-800 text-sm">
                <div class="flex items-start space-x-3">
                    <i class="bi bi-exclamation-triangle-fill text-amber-500 text-2xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="font-bold text-amber-900 text-base mb-1">Permisos de Módulo no disponibles</h4>
                        <p class="text-xs leading-relaxed text-amber-800">
                            La asignación y revocación de permisos por módulo (Acceso, Lectura, Edición y Eliminación) no se encuentra habilitada para los usuarios con el rol de <strong>Estudiante</strong>. 
                        </p>
                        <p class="text-xs leading-relaxed text-amber-700 mt-2">
                            Los estudiantes cuentan únicamente con acceso general al Aula Virtual. Para otorgar permisos específicos de módulos, cambia el rol del usuario a <strong>Instructor</strong> o <strong>Administrador</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Matriz Interactiva de Permisos por Módulo (Solo para Admin e Instructor) -->
            <div id="permissionsMatrixContainer" class="hidden space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                        <i class="bi bi-sliders text-blue-500 mr-1.5"></i> Asignar / Revocar Permisos por Módulo
                    </span>
                    <span class="text-xs text-gray-500 italic">Marca o desmarca casillas para conceder o denegar accesos</span>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-xs">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Módulo del Sistema</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Acceso</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Lectura</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Edición / Actualizar</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Eliminación</th>
                            </tr>
                        </thead>
                        <tbody id="permissionsMatrixBody" class="bg-white divide-y divide-gray-200 text-sm">
                            <!-- Filas dinámicas con checkboxes -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeUserPermissionsModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center">
                    <i class="bi bi-x-circle mr-1.5"></i> Cancelar
                </button>
                <button type="button" id="savePermissionsBtn" onclick="saveUserRoleAndPermissions()"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all flex items-center">
                    <i class="bi bi-check2-circle mr-1.5 text-base"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL 2: Crear / Editar Rol del Sistema -->
    <div id="roleModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-16 mx-auto p-6 border-0 w-full max-w-lg shadow-2xl rounded-2xl bg-white mb-10">
            <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-4">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-900"></h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="roleForm" method="POST">
                @csrf
                <div id="formMethod" class="hidden"></div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" for="name">
                        Nombre del Rol
                    </label>
                    <input type="text" id="name" name="name" placeholder="Ej. coordinador, supervisor"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <p class="text-red-500 text-xs mt-1 hidden" id="nameError"></p>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Permisos Globales
                    </label>
                    <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50/50 space-y-3">
                        @foreach ($permissions as $module => $modulePermissions)
                            <div>
                                <h5 class="font-bold text-xs capitalize text-blue-700 mb-1.5 pb-1 border-b border-gray-200">
                                    Módulo: {{ $module }}
                                </h5>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 ml-2">
                                    @foreach ($modulePermissions as $permission)
                                        <label class="flex items-center text-xs text-gray-700 cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="form-checkbox h-3.5 w-3.5 text-blue-600 rounded permission-checkbox">
                                            <span class="ml-2">{{ str_replace('_', ' ', $permission->name) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                        Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentUserId = null;
        let currentUserPerms = [];

        // Módulos y sus claves de permisos correspondientes para la matriz
        const systemModules = [
            { name: 'Dashboard / Tablero', icon: 'bi-speedometer2', access: 'view_dashboard', read: 'view_dashboard', edit: null, delete: null },
            { name: 'Configuración Empresa', icon: 'bi-building', access: 'view_enterprise', read: 'view_enterprise', edit: 'edit_enterprise', delete: null },
            { name: 'Categorías', icon: 'bi-tags', access: 'view_categories', read: 'view_categories', edit: 'edit_categories', delete: 'delete_categories' },
            { name: 'Cursos y Clases', icon: 'bi-journal-bookmark', access: 'view_courses', read: 'view_courses', edit: 'edit_courses', delete: 'delete_courses' },
            { name: 'Documentos y Recursos', icon: 'bi-file-earmark-pdf', access: 'view_documents', read: 'view_documents', edit: 'edit_documents', delete: 'delete_documents' },
            { name: 'Exámenes y Evaluaciones', icon: 'bi-pencil-square', access: 'view_exams', read: 'view_exams', edit: 'edit_exams', delete: 'delete_exams' },
            { name: 'Gestión de Usuarios', icon: 'bi-people', access: 'view_users', read: 'view_users', edit: 'edit_users', delete: 'delete_users' },
            { name: 'Inscripciones', icon: 'bi-mortarboard', access: 'view_enrollments', read: 'view_enrollments', edit: 'edit_enrollments', delete: 'delete_enrollments' },
            { name: 'Pagos y Finanzas', icon: 'bi-cash-stack', access: 'view_payments', read: 'view_payments', edit: 'edit_payments', delete: 'delete_payments' },
            { name: 'Roles y Permisos', icon: 'bi-shield-lock', access: 'view_roles', read: 'view_roles', edit: 'edit_roles', delete: 'delete_roles' }
        ];

        function openUserPermissionsModal(user) {
            currentUserId = user.id;
            currentUserPerms = user.effective_permissions || [];

            document.getElementById('userModalName').innerText = user.names || 'Usuario sin nombre';
            document.getElementById('userModalEmail').innerText = (user.email || '') + (user.dni ? ` | DNI: ${user.dni}` : '');

            const roleName = user.role || (user.roles && user.roles.length > 0 ? user.roles[0].name : 'student');
            const roleSelect = document.getElementById('userModalRoleSelect');
            roleSelect.value = roleName;

            onModalRoleChange(roleName);

            document.getElementById('userPermissionsModal').classList.remove('hidden');
        }

        function onModalRoleChange(selectedRole) {
            const studentWarning = document.getElementById('studentWarningContainer');
            const matrixContainer = document.getElementById('permissionsMatrixContainer');

            if (selectedRole === 'student') {
                studentWarning.classList.remove('hidden');
                matrixContainer.classList.add('hidden');
            } else {
                studentWarning.classList.add('hidden');
                matrixContainer.classList.remove('hidden');
                renderPermissionsMatrix(selectedRole);
            }
        }

        function renderPermissionsMatrix(selectedRole) {
            const matrixBody = document.getElementById('permissionsMatrixBody');
            matrixBody.innerHTML = '';

            const isAdmin = selectedRole === 'admin';

            systemModules.forEach(mod => {
                const accessChecked = isAdmin || (mod.access && currentUserPerms.includes(mod.access));
                const readChecked   = isAdmin || (mod.read && currentUserPerms.includes(mod.read));
                const editChecked   = isAdmin || (mod.edit && currentUserPerms.includes(mod.edit));
                const deleteChecked = isAdmin || (mod.delete && currentUserPerms.includes(mod.delete));

                const renderCheckbox = (permKey, isChecked) => {
                    if (!permKey) return '<span class="text-xs text-gray-400 italic">N/A</span>';
                    return `
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" value="${permKey}" ${isChecked ? 'checked' : ''} 
                                class="user-perm-checkbox form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                        </label>
                    `;
                };

                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50/70 transition-colors';
                row.innerHTML = `
                    <td class="px-4 py-3 font-semibold text-gray-800 flex items-center gap-2">
                        <i class="bi ${mod.icon} text-blue-600 text-base"></i> ${mod.name}
                    </td>
                    <td class="px-4 py-3 text-center">${renderCheckbox(mod.access, accessChecked)}</td>
                    <td class="px-4 py-3 text-center">${renderCheckbox(mod.read, readChecked)}</td>
                    <td class="px-4 py-3 text-center">${renderCheckbox(mod.edit, editChecked)}</td>
                    <td class="px-4 py-3 text-center">${renderCheckbox(mod.delete, deleteChecked)}</td>
                `;
                matrixBody.appendChild(row);
            });
        }

        function saveUserRoleAndPermissions() {
            if (!currentUserId) return;

            const selectedRole = document.getElementById('userModalRoleSelect').value;
            const checkedBoxes = document.querySelectorAll('.user-perm-checkbox:checked');
            const selectedPermissions = Array.from(checkedBoxes).map(cb => cb.value);

            const saveBtn = document.getElementById('savePermissionsBtn');
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="bi bi-arrow-repeat spin mr-1.5"></i> Guardando...';

            axios.put(`/users/${currentUserId}/permissions`, {
                role: selectedRole,
                permissions: selectedPermissions,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            })
            .then(response => {
                if (response.data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error al guardar permisos:', error);
                alert('Ocurrió un error al actualizar el rol y permisos del usuario.');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-check2-circle mr-1.5 text-base"></i> Guardar Cambios';
            });
        }

        function closeUserPermissionsModal() {
            document.getElementById('userPermissionsModal').classList.add('hidden');
            currentUserId = null;
        }

        // Modal Crear/Editar Rol Global
        let currentRoleId = null;

        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Crear Nuevo Rol';
            document.getElementById('roleForm').action = "{{ route('admin.roles.store') }}";
            document.getElementById('formMethod').innerHTML = '';
            document.getElementById('name').value = '';

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

            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });

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

        // Manejar envío del formulario de roles
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

                if (response.status === 200 || response.data.success) {
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

        // Cerrar modales al hacer clic fuera
        document.getElementById('userPermissionsModal').addEventListener('click', function(e) {
            if (e.target === this) closeUserPermissionsModal();
        });

        document.getElementById('roleModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>
@endsection