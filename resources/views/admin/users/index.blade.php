@extends('layouts.admin')
@section('title', 'Gestión de Usuarios')
@section('content')
<div class="container mx-auto px-4 py-6" x-data="userManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Usuarios</h1>
                <p class="text-gray-600 mt-2">Gestiona todos los usuarios de la plataforma</p>
            </div>

            <!-- Botón para crear usuario -->
            <a href="{{ route('admin.users.create') }}"
               class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Usuario
            </a>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total de usuarios -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Usuarios</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <i class="w-6 h-6 text-white fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Estudiantes -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Estudiantes</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $stats['students'] }}</p>
                    </div>
                    <div class="bg-green-600 p-3 rounded-xl">
                        <i class="w-6 h-6 text-white fa-regular fa-user"></i>
                    </div>
                </div>
            </div>

            <!-- Instructores -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">Instructores</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">{{ $stats['instructors'] }}</p>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Administradores -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-800">Administradores</p>
                        <p class="text-2xl font-bold text-orange-900 mt-1">{{ $stats['admins'] }}</p>
                    </div>
                    <div class="bg-orange-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel principal -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Header del panel -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800">Todos los Usuarios</h2>
                    <p class="text-sm text-gray-600 mt-1">Administra estudiantes, instructores y administradores</p>
                </div>

                <!-- Filtros y búsqueda -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text"
                            x-model="searchQuery"
                            @input.debounce.500ms="performSearch()"
                            placeholder="Buscar por nombre, email o DNI..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                        >
                    </div>

                    <div class="flex gap-2">
                        <select x-model="roleFilter"
                            @change="performSearch()"
                            class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                        >
                            <option value="">Todos los roles</option>
                            <option value="student">Estudiante</option>
                            <option value="instructor">Instructor</option>
                            <option value="admin">Administrador</option>
                        </select>

                        <button @click="resetFilters()" class="px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Limpiar
                        </button>

                        <button @click="exportUsers()" class="px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl font-medium transition duration-200">
                            Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="overflow-x-auto" x-show="!loading">
            @if($users->isEmpty())
                <!-- Estado vacío -->
                <div class="text-center py-16 px-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 0A9 9 0 0121 20v1h-6m0 0v-1a6 6 0 00-6-6m6 6v-1a6 6 0 00-6-6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay usuarios registrados</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">Comienza creando tu primer usuario para la plataforma.</p>
                    <a href="{{ route('admin.users.create') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Primer Usuario
                    </a>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Contacto
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Rol
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actividad
                            </th>
                            <!--<th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Estado
                            </th>-->
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider text-right">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-white transition-all duration-200 group">
                                <!-- Información del usuario -->
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <!-- Avatar -->
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-200">
                                                {{ strtoupper(substr($user->names, 0, 1)) }}
                                            </div>
                                        </div>

                                        <!-- Detalles del usuario -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <a href="{{ route('admin.users.show', $user) }}"
                                                   class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate">
                                                    {{ $user->names }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500 truncate">
                                                DNI: {{ $user->dni }}
                                            </div>
                                            <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                                <div class="flex items-center gap-1">
                                                    <i class="bi bi-person"></i>
                                                    ID: {{ $user->id }}
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <i class="bi bi-calendar"></i>
                                                    {{ $user->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="bi bi-check-circle-fill"></i>&nbsp;
                                                Activa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <i class="bi bi-x-circle-fill"></i>&nbsp;
                                                Inactiva
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Contacto -->
                                <td class="px-6 py-5">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-envelope"></i>
                                            <span class="text-sm text-gray-900">{{ $user->email }}</span>
                                        </div>
                                        @if($user->phone)
                                            <div class="flex items-center gap-2">
                                                <i class="bi bi-telephone"></i>
                                                <span class="text-sm text-gray-600">{{ $user->phone }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Rol -->
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold text-center
                                            @if($user->role === 'admin') bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800
                                            @elseif($user->role === 'instructor') bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800
                                            @else bg-gradient-to-r from-green-100 to-green-200 text-green-800
                                            @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                        @if($user->profession)
                                            <span class="text-xs text-gray-500 truncate">{{ $user->profession }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actividad -->
                                <td class="px-6 py-5">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-600">Inscripciones:</span>
                                            <span class="text-xs font-semibold text-blue-600">{{ $user->enrollments_count }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-600">Cursos creados:</span>
                                            <span class="text-xs font-semibold text-purple-600">{{ $user->courses_count }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-600">Certificados:</span>
                                            <span class="text-xs font-semibold text-green-600">{{ $user->certificates_count }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-600">Exámenes:</span>
                                            <span class="text-xs font-semibold text-orange-600">{{ $user->exam_attempts_count }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-5">
                                    <div x-data="{ open: false }" class="relative flex items-center justify-end">
                                        <!-- Botón del menú (tres puntos) -->
                                        <button @click="open = !open" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-300" title="Más opciones">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                            </svg>
                                        </button>

                                        <!-- Menú desplegable -->
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-20 overflow-hidden"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            style="display: none;"
                                        >
                                            <!-- Activar / Desactivar -->
                                            <button @click="toggleUserStatus({{ $user->id }}); open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ $user->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors duration-150">
                                                @if($user->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                    Desactivar
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Activar
                                                @endif
                                            </button>

                                            <!-- Ver detalles -->
                                            <a href="{{ route('admin.users.show', $user) }}" class="block w-full px-4 py-2.5 text-sm font-medium text-blue-600 hover:bg-blue-50 flex items-center gap-3 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver detalles
                                            </a>

                                            <!-- Editar -->
                                            <a href="{{ route('admin.users.edit', $user) }}" class="block w-full px-4 py-2.5 text-sm font-medium text-purple-600 hover:bg-purple-50 flex items-center gap-3 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>

                                            <!-- Enviar mensaje -->
                                            <button @click="sendMessage({{ $user->id }}); open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                </svg>
                                                Enviar mensaje
                                            </button>

                                            <!-- Eliminar -->
                                            <button @click="deleteUser({{ $user->id }}); open = false" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="py-16 text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Cargando usuarios...</p>
        </div>

        <!-- Paginación -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $users->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $users->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $users->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center space-x-2">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function userManager() {
        return {
            searchQuery: '{{ request('search') }}',
            roleFilter: '{{ request('role', '') }}',
            statusFilter: '{{ request('status', '') }}',
            loading: false,

            init() {
                // Inicializar
            },

            async performSearch() {
                this.loading = true;

                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.roleFilter) params.append('role', this.roleFilter);
                    if (this.statusFilter) params.append('status', this.statusFilter);

                    const url = `{{ route('admin.users.index') }}?${params.toString()}`;
                    window.location.href = url;
                } catch (error) {
                    console.error('Error en búsqueda:', error);
                    this.loading = false;
                }
            },

            resetFilters() {
                this.searchQuery = '';
                this.roleFilter = '';
                this.statusFilter = '';
                this.performSearch();
            },

            async exportUsers() {
                try {
                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.roleFilter) params.append('role', this.roleFilter);

                    const url = `{{ route('admin.users.index') }}/export?${params.toString()}`;
                    window.open(url, '_blank');
                } catch (error) {
                    console.error('Error al exportar:', error);
                    showNotification('Error al exportar usuarios', 'error');
                }
            }
        };
    }

    // Función para cambiar estado del usuario
    async function toggleUserStatus(userId) {
        if (!confirm('¿Estás seguro de cambiar el estado del usuario?')) {
            return;
        }

        try {
            const response = await axios.patch(`/admin/users/${userId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del usuario actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    // Función para eliminar usuario
    async function deleteUser(userId) {
        if (!confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/users/${userId}`);
            if (response.data.success) {
                showNotification('Usuario eliminado exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al eliminar usuario:', error);
            showNotification(error.response?.data?.message || 'Error al eliminar el usuario', 'error');
        }
    }

    // Función para enviar mensaje
    async function sendMessage(userId) {
        const message = prompt('Escribe el mensaje que deseas enviar:');
        if (!message) return;

        try {
            const response = await axios.post(`/admin/users/${userId}/send-message`, {
                message: message
            });

            if (response.data.success) {
                showNotification('Mensaje enviado exitosamente', 'success');
            }
        } catch (error) {
            console.error('Error al enviar mensaje:', error);
            showNotification('Error al enviar el mensaje', 'error');
        }
    }

    // Función para mostrar notificaciones
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
