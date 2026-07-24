@extends('layouts.student')
@section('title', 'Gestión de Usuarios')
@section('content')
    <div class="container mx-auto px-4 py-4 sm:py-6" x-data="userManager()" x-init="init()">
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center justify-between mb-6">
                <div class="flex flex-col xs:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                    <!-- Botón para importar usuarios -->
                    <a href="{{ route('company.import.file') }}"
                        class="flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base">
                        <i class="fa-solid fa-file-excel"></i>
                        <span class="hidden xs:inline">Importar usuarios desde excel</span>
                        <span class="xs:hidden">Importar usuarios desde excel</span>
                    </a>
                </div>

                <!-- Botón para crear usuario -->
                <a href="{{ route('company.create.new') }}"
                    class="flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base w-full sm:w-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden xs:inline">Agregar a mis colaboradores</span>
                    <span class="xs:hidden">Agregar a mis colaboradores</span>
                </a>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-1 xs:grid-cols-2 gap-3 sm:gap-4 mb-6">
                <!-- Total de usuarios -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-blue-800">Total Usuarios</p>
                            <p class="text-xl sm:text-2xl font-bold text-blue-900 mt-1">
                                {{ $stats['seats_max'] . ' / ' . $stats['total'] }}</p>
                        </div>
                        <div class="bg-blue-600 p-2 sm:p-3 rounded-xl">
                            <i class="w-5 h-5 sm:w-6 sm:h-6 text-white fa-solid fa-users"></i>
                        </div>
                    </div>
                </div>

                <!-- Estudiantes -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-green-800">Asientos disponibles</p>
                            <p class="text-xl sm:text-2xl font-bold text-green-900 mt-1">
                                {{ $total = (int) $stats['seats_max'] - (int) $stats['total'] }}</p>
                        </div>
                        <div class="bg-green-600 p-2 sm:p-3 rounded-xl">
                            <i class="w-5 h-5 sm:w-6 sm:h-6 text-white fa-regular fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel principal -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <!-- Header del panel -->
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
                    <div class="flex-1">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-800">Todos los Usuarios</h2>
                    </div>

                    <!-- Filtros y búsqueda -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <svg x-show="!searching"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <svg x-show="searching"
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-blue-500 animate-spin"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <input type="text" x-model="searchQuery" @input.debounce.400ms="performSearch()"
                                placeholder="Buscar usuarios..."
                                class="w-full pl-9 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla/Lista de usuarios -->
            <div id="collaborators-table-container" x-show="!loading">
                @if ($users->isEmpty())
                    <!-- Estado vacío -->
                    <div class="text-center py-12 sm:py-16 px-4 sm:px-6">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-4 sm:mb-6">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 0A9 9 0 0121 20v1h-6m0 0v-1a6 6 0 00-6-6m6 6v-1a6 6 0 00-6-6">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">No hay usuarios registrados</h3>
                        <p class="text-sm sm:text-base text-gray-500 mb-4 sm:mb-6 max-w-md mx-auto">Comienza creando tu
                            primer usuario para la plataforma.</p>
                        <a href="{{ route('company.create.new') }}"
                            class="flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base w-full sm:w-auto">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            <span class="hidden xs:inline">Agregar a mis colaboradores</span>
                            <span class="xs:hidden">Agregar a mis colaboradores</span>
                        </a>
                    </div>
                @else
                    <!-- Vista móvil: cards -->
                    <div class="block sm:hidden divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <!-- Header con avatar y nombre -->
                                <div class="flex items-start gap-3 mb-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-base flex-shrink-0">
                                        {{ strtoupper(substr($user->names, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $user->names }}
                                                </p>
                                                <p class="text-xs text-gray-500">DNI: {{ $user->dni }}</p>
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold whitespace-nowrap bg-green-100 text-green-800">
                                                {{ $user->profession }}
                                            </span>
                                        </div>
                                        @if ($user->code)
                                            <span
                                                class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                COD: {{ $user->code }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Información de contacto -->
                                <div class="space-y-1 mb-3 text-xs">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-envelope text-gray-400 w-4"></i>
                                        <span class="text-gray-600 truncate">{{ $user->email }}</span>
                                    </div>
                                    @if ($user->phone)
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-telephone text-gray-400 w-4"></i>
                                            <span class="text-gray-600">{{ $user->phone }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-calendar text-gray-400 w-4"></i>
                                        <span class="text-gray-600">{{ $user->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <!-- Estado y acciones -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold 
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i
                                                class="bi {{ $user->is_active ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} mr-1"></i>
                                            {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        <span class="text-xs text-gray-500">ID: {{ $user->id }}</span>
                                    </div>

                                    <!-- Menú de acciones móvil -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open"
                                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown móvil -->
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-20"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95" style="display: none;">

                                            @if ($user->id !== auth()->user()->id)
                                                <button @click="toggleUserStatus({{ $user->id }}); open = false"
                                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm {{ $user->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }}">
                                                    <i
                                                        class="bi bi-{{ $user->is_active ? 'ban' : 'check-circle' }} w-4"></i>
                                                    {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            @endif
                                            <button
                                                @click="$dispatch('open-password-modal', { userId: {{ $user->id }} }); open = false"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-amber-600 hover:bg-amber-50">
                                                <i class="bi bi-key w-4"></i> Cambiar contraseña
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Vista desktop: tabla -->
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/80 backdrop-blur-sm">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Usuario</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Contacto</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Cargo / Profesión</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Actividad</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider text-right">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-white transition-all duration-200 group">
                                        <!-- Información del usuario -->
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-200">
                                                        {{ strtoupper(substr($user->names, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <a href="{{ route('admin.users.show', $user) }}"
                                                            class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate">
                                                            {{ $user->names }}
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500 truncate">DNI: {{ $user->dni }}
                                                    </div>
                                                    @if ($user->code)
                                                        <div class="mt-1">
                                                            <span
                                                                class="px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800">
                                                                COD: {{ $user->code }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                                                        <div class="flex items-center gap-1">
                                                            <i class="bi bi-person"></i> ID: {{ $user->id }}
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <i class="bi bi-calendar"></i>
                                                            {{ $user->created_at->format('d/m/Y') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($user->is_active)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        <i class="bi bi-check-circle-fill mr-1"></i> Activo
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        <i class="bi bi-x-circle-fill mr-1"></i> Inactivo
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Contacto -->
                                        <td class="px-6 py-5">
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-envelope text-gray-400 w-4"></i>
                                                    <span class="text-sm text-gray-900">{{ $user->email }}</span>
                                                </div>
                                                @if ($user->phone)
                                                    <div class="flex items-center gap-2">
                                                        <i class="bi bi-telephone text-gray-400 w-4"></i>
                                                        <span class="text-sm text-gray-600">{{ $user->phone }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Rol -->
                                        <td class="px-6 py-5">
                                            <div class="flex flex-col gap-2">
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-semibold text-center bg-gradient-to-r from-green-100 to-green-200 text-green-800">
                                                    @if ($user->profession)
                                                        <span
                                                            class="text-xs text-gray-500 truncate">{{ $user->profession }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Actividad -->
                                        <td class="px-6 py-5">
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-600">Inscripciones:</span>
                                                    <span
                                                        class="text-xs font-semibold text-blue-600">{{ $user->enrollments_count }}</span>
                                                </div>
                                                {{-- <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-600">Cursos creados:</span>
                                                <span class="text-xs font-semibold text-purple-600">{{ $user->courses_count }}</span>
                                            </div> --}}
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-600">Certificados:</span>
                                                    <span
                                                        class="text-xs font-semibold text-green-600">{{ $user->certificates_count }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Acciones -->
                                        <td class="px-6 py-5">
                                            <div x-data="{ open: false }" class="relative flex items-center justify-end">
                                                <button @click="open = !open"
                                                    class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 outline-none focus:ring-2 focus:ring-indigo-300">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                                    </svg>
                                                </button>

                                                <!-- Menú desplegable desktop -->
                                                <div x-show="open" @click.away="open = false"
                                                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-20 overflow-hidden"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95" style="display: none;">

                                                    @if ($user->id !== auth()->user()->id)
                                                        <button
                                                            @click="toggleUserStatus({{ $user->id }}); open = false"
                                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium {{ $user->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors duration-150">
                                                            @if ($user->is_active)
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                                    </path>
                                                                </svg>
                                                                Desactivar
                                                            @else
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Activar
                                                            @endif
                                                        </button>
                                                    @endif
                                                    <button
                                                        @click="$dispatch('open-password-modal', { userId: {{ $user->id }} }); open = false"
                                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-amber-600 hover:bg-amber-50 transition-colors duration-150">
                                                        <i class="bi bi-key w-4"></i> Cambiar contraseña
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Loading state -->
            <div x-show="loading" class="py-12 sm:py-16 text-center">
                <div
                    class="inline-block animate-spin rounded-full h-8 w-8 sm:h-12 sm:w-12 border-t-2 border-b-2 border-blue-600 mb-3 sm:mb-4">
                </div>
                <p class="text-sm sm:text-base text-gray-600">Cargando usuarios...</p>
            </div>

            <!-- Paginación -->
            @if ($users->hasPages())
                <div id="collaborators-pagination"
                    class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50/50">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="text-xs sm:text-sm text-gray-700 text-center sm:text-left">
                            Mostrando <span class="font-medium">{{ $users->firstItem() }}</span>
                            a <span class="font-medium">{{ $users->lastItem() }}</span>
                            de <span class="font-medium">{{ $users->total() }}</span> resultados
                        </div>
                        <div class="flex justify-center sm:justify-end">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal para cambiar contraseña -->
        <div x-data="passwordModal()" x-on:open-password-modal.window="handleOpen($event.detail)">
            <div style="display: none;" x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
                @click.self="closeModal">
                <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
                    <div style="display: none;" x-show="showModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-lg mx-auto max-h-[90vh] overflow-hidden">
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base sm:text-xl font-bold text-gray-900">Actualizar contraseña</h3>
                                <button @click="closeModal" class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                            <form @submit.prevent="submitPassword">
                                @csrf
                                <div class="space-y-4 sm:space-y-6">
                                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-200">
                                        <div class="mb-4">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                                Nueva contraseña *
                                            </label>
                                            <div class="relative">
                                                <input :type="showPassword ? 'text' : 'password'"
                                                    x-model="formData.password" required
                                                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 pr-10">
                                                <button type="button" @click="showPassword = !showPassword"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                                                    <i class="fa-solid"
                                                        :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </div>

                                            <div class="mt-2" x-show="formData.password.length > 0" x-transition>
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 h-1.5 rounded-full bg-gray-200 overflow-hidden">
                                                        <div class="h-full transition-all duration-300"
                                                            :class="passwordStrength.color"
                                                            :style="`width: ${passwordStrength.percentage}%`"></div>
                                                    </div>
                                                    <span class="text-xs font-semibold" :class="passwordStrength.textColor"
                                                        x-text="passwordStrength.label"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                                Confirmar contraseña *
                                            </label>
                                            <div class="relative">
                                                <input :type="showPassword ? 'text' : 'password'"
                                                    x-model="formData.password_confirmation" required
                                                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 pr-10">
                                                <button type="button" @click="showPassword = !showPassword"
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                                                    <i class="fa-solid"
                                                        :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                </button>
                                            </div>

                                            <div class="mt-2 text-xs font-medium text-red-500 flex items-center gap-1"
                                                x-show="formData.password_confirmation.length > 0 && formData.password !== formData.password_confirmation"
                                                x-transition>
                                                <i class="fa-solid fa-circle-exclamation"></i> Las contraseñas no coinciden
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 sm:pt-6 mt-4 sm:mt-6 border-t border-gray-200">
                                    <button type="button" @click="closeModal"
                                        class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg sm:rounded-xl font-medium transition duration-200 text-sm">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm">
                                        <i class="bi bi-key"></i>
                                        Guardar cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear código -->
        <div x-data="createCodeModal()" x-on:open-code-user-modal.window="handleOpen($event.detail)">
            <div style="display: none;" x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
                @click.self="closeModal">
                <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
                    <div style="display: none;" x-show="showModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="bg-white rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-lg mx-auto max-h-[90vh] overflow-hidden">
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base sm:text-xl font-bold text-gray-900">Crear código de descuento</h3>
                                <button @click="closeModal" class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                            <form @submit.prevent="submitCode">
                                @csrf
                                <div class="space-y-4 sm:space-y-6">
                                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-200">
                                        <div class="mb-4">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                                Porcentaje de descuento (opcional)
                                            </label>
                                            <input type="number" step="0.01" x-model="formData.discount_percentage"
                                                placeholder="Ej: 20"
                                                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                            <p class="text-xs text-gray-500 mt-1">Si no se especifica, se usará 20% por
                                                defecto</p>
                                        </div>

                                        <div class="flex items-start gap-3">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" x-model="formData.promotion_price_is_active"
                                                    id="promotion_price_is_active"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </div>
                                            <div class="flex-1">
                                                <label for="promotion_price_is_active"
                                                    class="text-xs sm:text-sm font-medium text-gray-700">
                                                    Código activo
                                                </label>
                                                <p class="text-xs text-gray-500">El código estará disponible para su uso
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 sm:pt-6 mt-4 sm:mt-6 border-t border-gray-200">
                                    <button type="button" @click="closeModal"
                                        class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg sm:rounded-xl font-medium transition duration-200 text-sm">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm">
                                        Guardar cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function userManager() {
            return {
                searchQuery: '{{ request('search') }}',
                statusFilter: '{{ request('status', '') }}',
                loading: false,
                searching: false,

                init() {
                    // Inicializar
                },

                async performSearch() {
                    this.searching = true;

                    const params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.statusFilter) params.append('status', this.statusFilter);
                    params.append('ajax', '1');

                    const url = `{{ route('company.list') }}?${params.toString()}`;
                    const displayUrl =
                        `{{ route('company.list') }}?${params.toString().replace('&ajax=1','').replace('ajax=1&','').replace('ajax=1','')}`;
                    window.history.replaceState(null, '', displayUrl || '{{ route('company.list') }}');

                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const html = await res.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newTable = doc.getElementById('collaborators-table-container');
                        if (newTable) document.getElementById('collaborators-table-container').innerHTML = newTable
                            .innerHTML;

                        const newPag = doc.getElementById('collaborators-pagination');
                        const curPag = document.getElementById('collaborators-pagination');
                        if (newPag && curPag) curPag.innerHTML = newPag.innerHTML;
                    } catch (err) {
                        console.error('Error en búsqueda:', err);
                    } finally {
                        this.searching = false;
                    }
                },

                resetFilters() {
                    this.searchQuery = '';
                    this.statusFilter = '';
                    this.performSearch();
                },

                async exportUsers() {
                    try {
                        const params = new URLSearchParams();
                        if (this.searchQuery) params.append('search', this.searchQuery);
                        const url = `{{ route('company.list') }}/export?${params.toString()}`;
                        window.open(url, '_blank');
                    } catch (error) {
                        console.error('Error al exportar:', error);
                        showNotification('Error al exportar usuarios', 'error');
                    }
                }
            };
        }

        function passwordModal() {
            return {
                showModal: false,
                userId: null,
                isSubmitting: false,
                showPassword: false, // Variable para controlar el ojito de la contraseña
                formData: {
                    password: '',
                    password_confirmation: ''
                },

                // Propiedad computada para evaluar la fuerza
                get passwordStrength() {
                    const pass = this.formData.password;
                    let score = 0;

                    if (!pass) return {
                        score: 0,
                        percentage: 0,
                        label: '',
                        color: 'bg-transparent',
                        textColor: ''
                    };

                    if (pass.length >= 8) score += 1;
                    if (/[a-z]/.test(pass)) score += 1;
                    if (/[A-Z]/.test(pass)) score += 1;
                    if (/\d/.test(pass)) score += 1;
                    if (/[^A-Za-z0-9]/.test(pass)) score += 1;

                    if (score <= 2) return {
                        score,
                        percentage: 33,
                        label: 'Débil',
                        color: 'bg-red-500',
                        textColor: 'text-red-500'
                    };
                    if (score === 3 || score === 4) return {
                        score,
                        percentage: 66,
                        label: 'Media',
                        color: 'bg-amber-500',
                        textColor: 'text-amber-500'
                    };
                    return {
                        score,
                        percentage: 100,
                        label: 'Fuerte',
                        color: 'bg-emerald-500',
                        textColor: 'text-emerald-500'
                    };
                },

                handleOpen(detail) {
                    this.userId = detail.userId;
                    this.showModal = true;
                    this.resetForm();
                },

                resetForm() {
                    this.showPassword = false; // Se reinicia al cerrar/abrir
                    this.formData = {
                        password: '',
                        password_confirmation: ''
                    };
                },

                closeModal() {
                    this.showModal = false;
                    this.userId = null;
                    this.resetForm();
                },

                async submitPassword() {
                    // Validar de nuevo por si acaso el usuario no vio el mensaje rojo
                    if (this.formData.password !== this.formData.password_confirmation) {
                        showNotification('Las contraseñas no coinciden', 'error');
                        return;
                    }

                    this.isSubmitting = true;
                    try {
                        // Mantengo tu ruta de este archivo: /mi-colaborador/
                        const response = await axios.put(
                            `/mi-colaborador/${this.userId}/password`,
                            this.formData, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                }
                            }
                        );

                        if (response.data.success) {
                            showNotification(response.data.message, 'success');
                            this.closeModal();
                        }
                    } catch (error) {
                        console.error('Error al actualizar contraseña:', error);
                        const message = error.response?.data?.message || error.response?.data?.errors?.password?.[0] ||
                            'Error al actualizar la contraseña';
                        showNotification(message, 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            };
        }

        function createCodeModal() {
            return {
                showModal: false,
                userId: null,
                isSubmitting: false,
                formData: {
                    discount_percentage: '',
                    promotion_price_is_active: false
                },

                handleOpen(detail) {
                    this.userId = detail.userId;
                    this.showModal = true;
                    this.resetForm();
                },

                resetForm() {
                    this.formData = {
                        discount_percentage: '',
                        promotion_price_is_active: false
                    };
                },

                closeModal() {
                    this.showModal = false;
                    this.userId = null;
                    this.resetForm();
                },

                async submitCode() {
                    this.isSubmitting = true;
                    try {
                        const response = await axios.post(
                            `/admin/users/${this.userId}/create-code`,
                            this.formData, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                }
                            }
                        );

                        if (response.data.success) {
                            showNotification(response.data.message, 'success');
                            this.closeModal();
                            setTimeout(() => window.location.reload(), 1000);
                        }
                    } catch (error) {
                        console.error('Error al crear el código:', error);
                        const message = error.response?.data?.message || 'Error al crear el código';
                        showNotification(message, 'error');
                    } finally {
                        this.isSubmitting = false;
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
                const response = await axios.patch(`/mi-colaborador/${userId}/toggle-status`);
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

        function copyPromoLink(code, button) {
            const promoLink = `${API_URL}/cursos/${code}`;
            const tempInput = document.createElement('input');
            tempInput.value = promoLink;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999);

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    const originalContent = button.innerHTML;
                    button.innerHTML = '<i class="bi bi-check text-green-600"></i> Copiado!';
                    button.classList.add('text-green-600', 'bg-green-100');
                    showNotification('Enlace copiado al portapapeles', 'success');
                    setTimeout(() => {
                        button.innerHTML = originalContent;
                        button.classList.remove('text-green-600', 'bg-green-100');
                    }, 2000);
                }
            } catch (err) {
                console.error('Error al copiar:', err);
                showNotification('Error al copiar el enlace', 'error');
            }
            document.body.removeChild(tempInput);
        }

        // Función para mostrar notificaciones
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 sm:top-6 sm:right-6 z-50 px-4 sm:px-6 py-3 sm:py-4 rounded-xl shadow-xl transform transition-all duration-300 text-sm sm:text-base max-w-[90vw] ${
            type === 'success'
            ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
            : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
        }`;

            notification.innerHTML = `
            <div class="flex items-center gap-2 sm:gap-3">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                    }
                </svg>
                <span class="font-medium break-words">${message}</span>
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
