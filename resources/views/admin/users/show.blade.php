@extends('layouts.admin')
@section('title', 'Usuario: ' . $user->names)
@section('content')
    <div x-data="userProfile()">
        <div class="container mx-auto px-4 py-6">
            {{-- ===== HEADER ===== --}}
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">

                    {{-- Avatar + Info principal --}}
                    <div class="flex items-start gap-5">
                        <div class="relative flex-shrink-0">
                            @if ($user->profile_photo)
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->names }}"
                                    class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white shadow-lg">
                            @else
                                <div
                                    class="w-24 h-24 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-lg
                                @if ($user->role === 'admin') bg-gradient-to-br from-orange-400 to-orange-600
                                @elseif($user->role === 'instructor') bg-gradient-to-br from-purple-500 to-purple-700
                                @else bg-gradient-to-br from-blue-500 to-blue-700 @endif">
                                    {{ strtoupper(substr($user->names, 0, 1)) }}
                                </div>
                            @endif
                            {{-- Indicador de estado --}}
                            <span
                                class="absolute -bottom-1.5 -right-1.5 w-5 h-5 rounded-full border-2 border-white shadow
                            {{ $user->is_active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                        </div>

                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">{{ $user->names }}</h1>
                            <p class="text-gray-500 mt-0.5">{{ $user->email }}</p>

                            <div class="flex flex-wrap gap-2 mt-3">
                                {{-- Rol --}}
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                @if ($user->role === 'admin') bg-orange-100 text-orange-800
                                @elseif($user->role === 'instructor') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800 @endif">
                                    @if ($user->role === 'admin')
                                        <i class="bi bi-shield-fill text-xs"></i>
                                    @elseif($user->role === 'instructor')
                                        <i class="bi bi-person text-xs"></i>
                                    @else
                                        <i class="bi bi-mortarboard text-xs"></i>
                                    @endif
                                    {{ ucfirst($user->role) }}
                                </span>

                                {{-- Estado --}}
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>

                                {{-- DNI --}}
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    <i class="bi bi-person-vcard text-xs"></i>
                                    {{ $user->dni }}
                                </span>

                                {{-- Fecha registro --}}
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    <i class="bi bi-calendar text-xs"></i>
                                    Desde {{ $user->created_at->format('d/m/Y') }}
                                </span>

                                {{-- Expiración --}}
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $user->expires_at ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-gray-100 text-gray-700' }}">
                                    <i class="bi bi-calendar-x text-xs"></i>
                                    Expiración:
                                    {{ $user->expires_at ? $user->expires_at->format('d/m/Y') : 'Acceso Ilimitado' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex flex-wrap items-center gap-2 lg:mt-0">
                        {{-- Inscribir en Curso --}}
                        <button @click="openEnrollModal()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-sm">
                            <i class="bi bi-journal-plus text-xs"></i>
                            Inscribir en Curso
                        </button>

                        {{-- Cambiar Contraseña --}}
                        <button @click="openPasswordModal()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm">
                            <i class="bi bi-key text-gray-500"></i>
                            Contraseña
                        </button>

                        {{-- Toggle Estado --}}
                        <button @click="toggleStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})"
                            :disabled="togglingStatus"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm border
                            {{ $user->is_active
                                ? 'bg-white border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300'
                                : 'bg-white border-green-200 text-green-700 hover:bg-green-50 hover:border-green-300' }}">
                            <i class="bi text-xs"
                                :class="togglingStatus ? 'bi-spinner bi-spin' :
                                    '{{ $user->is_active ? 'bi-ban' : 'bi-check-circle' }}'"></i>
                            <span
                                x-text="togglingStatus ? 'Actualizando...' : '{{ $user->is_active ? 'Desactivar' : 'Activar' }}'"></span>
                        </button>

                        {{-- Editar --}}
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-sm">
                            <i class="bi bi-pencil-square text-xs"></i>
                            Editar
                        </a>

                        {{-- Volver --}}
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm">
                            <i class="bi bi-arrow-left text-xs"></i>
                            Volver
                        </a>
                    </div>
                </div>

                {{-- ===== STATS RÁPIDAS ===== --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                    <div
                        class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 text-center">
                        <div class="text-2xl font-extrabold text-blue-900">{{ $user->enrollments->count() ?? 0 }}</div>
                        <div class="text-xs text-blue-600 font-medium mt-0.5">Inscripciones</div>
                    </div>
                    <div
                        class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 text-center">
                        <div class="text-2xl font-extrabold text-green-900">{{ $user->courses->count() ?? 0 }}</div>
                        <div class="text-xs text-green-600 font-medium mt-0.5">Cursos</div>
                    </div>
                    <div
                        class="p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 text-center">
                        <div class="text-2xl font-extrabold text-yellow-900">{{ $user->certificates->count() ?? 0 }}</div>
                        <div class="text-xs text-yellow-600 font-medium mt-0.5">Certificados</div>
                    </div>
                    <div
                        class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 text-center">
                        <div class="text-2xl font-extrabold text-purple-900">{{ $user->examAttempts->count() ?? 0 }}</div>
                        <div class="text-xs text-purple-600 font-medium mt-0.5">Exámenes</div>
                    </div>
                </div>

                {{-- ===== TABS ===== --}}
                <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <nav class="flex overflow-x-auto">
                        <button @click="activeTab = 'info'"
                            :class="activeTab === 'info' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="bi bi-info-circle"></i> Información
                        </button>

                        @if ($user->role === 'student')
                            <button @click="activeTab = 'enrollments'"
                                :class="activeTab === 'enrollments' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                                <i class="bi bi-book"></i> Inscripciones
                                <span
                                    class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold
                            {{ 'bg-blue-100 text-blue-700' }}">{{ $user->enrollments->count() ?? 0 }}</span>
                            </button>
                            <button @click="activeTab = 'certificates'"
                                :class="activeTab === 'certificates' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                                <i class="bi bi-award"></i> Certificados
                                <span
                                    class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">{{ $user->certificates->count() ?? 0 }}</span>
                            </button>
                            <button @click="activeTab = 'exams'"
                                :class="activeTab === 'exams' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                                <i class="bi bi-clipboard-list"></i> Exámenes
                                <span
                                    class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">{{ $user->examAttempts->count() ?? 0 }}</span>
                            </button>
                            <button @click="activeTab = 'progress'"
                                :class="activeTab === 'progress' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                                <i class="bi bi-chart-line"></i> Progreso
                            </button>
                            <button @click="activeTab = 'tracking'; $nextTick(() => initCharts())"
                                :class="activeTab === 'tracking' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                                <i class="bi bi-chart-bar"></i> Seguimiento
                            </button>
                        @endif

                        @if ($user->role === 'instructor')
                            <button @click="activeTab = 'courses'"
                                :class="activeTab === 'courses' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                                <i class="bi bi-book"></i> Cursos
                                <span
                                    class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">{{ $user->courses_count ?? 0 }}</span>
                            </button>
                        @endif

                        @if ($user->hasPromotionCode())
                            <button @click="activeTab = 'sales'; $nextTick(() => loadSales())"
                                :class="activeTab === 'sales' ? 'border-emerald-600 text-emerald-700 bg-emerald-50/60' :
                                    'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                                <i class="bi bi-receipt"></i> Mis Ventas
                                <span
                                    class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    {{ $user->promotedSales->count() ?? 0 }}
                                </span>
                            </button>
                        @endif

                        <button @click="activeTab = 'activity'; $nextTick(() => loadActivities())"
                            :class="activeTab === 'activity' ? 'border-blue-600 text-blue-700 bg-blue-50/60' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="bi bi-clock-history"></i> Actividad
                        </button>
                    </nav>
                </div>
            </div>

            {{-- ===== CONTENIDO DE TABS ===== --}}

            {{-- TAB: Información --}}
            <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-gray-800 mb-5 flex items-center gap-2">
                            <span class="w-1 h-5 bg-blue-600 rounded-full inline-block"></span>
                            Información del Usuario
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                            {{-- Datos personales --}}
                            <div class="space-y-5">
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Datos Personales
                                </h4>
                                @foreach ([['label' => 'DNI', 'value' => $user->dni, 'icon' => 'fa-id-card'], ['label' => 'Nombres Completos', 'value' => $user->names, 'icon' => 'fa-user'], ['label' => 'Email', 'value' => $user->email, 'icon' => 'fa-envelope'], ['label' => 'Teléfono', 'value' => $user->phone ?? 'No registrado', 'icon' => 'fa-phone']] as $field)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="bi bi-envelope text-gray-500 text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-400 font-medium">{{ $field['label'] }}</div>
                                            <div class="text-gray-900 font-medium text-sm mt-0.5">{{ $field['value'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Información adicional --}}
                            <div class="space-y-5">
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Información
                                    Adicional</h4>
                                @foreach ([['label' => 'Nacionalidad', 'value' => $user->nationality ?? 'No registrada', 'icon' => 'bi-flag'], ['label' => 'Profesión', 'value' => $user->profession ?? 'No registrada', 'icon' => 'bi-person'], ['label' => 'Dirección', 'value' => $user->address ?? 'No registrada', 'icon' => 'bi-geo-alt'], ['label' => 'Fecha de Registro', 'value' => $user->created_at->format('d/m/Y H:i'), 'icon' => 'bi-calendar-check'], ['label' => 'Fecha de Expiración', 'value' => $user->expires_at ? $user->expires_at->format('d/m/Y H:i') : 'Acceso Ilimitado', 'icon' => 'bi-calendar']] as $field)
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="bi {{ $field['icon'] }} text-gray-500 text-xs"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-400 font-medium">{{ $field['label'] }}</div>
                                            <div class="text-gray-900 font-medium text-sm mt-0.5">{{ $field['value'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Roles del sistema --}}
                        @if ($user->getRoleNames()->isNotEmpty())
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Roles del
                                    Sistema</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->getRoleNames() as $roleName)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-xs font-semibold border border-blue-200">
                                            <i class="bi bi-tag text-blue-500 text-xs"></i>
                                            {{ $roleName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TAB: Inscripciones --}}
            @if ($user->role === 'student')
                <div x-show="activeTab === 'enrollments'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                    <span class="w-1 h-5 bg-blue-600 rounded-full inline-block"></span>
                                    Inscripciones a Cursos
                                </h3>
                                <div class="flex items-center gap-2">
                                    <button @click="openEnrollModal()"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition-all duration-200 shadow-sm">
                                        <i class="bi bi-journal-plus text-xs"></i>
                                        Inscribir en Curso
                                    </button>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $user->enrollments_count ?? 0 }} cursos
                                    </span>
                                </div>
                            </div>

                            @if ($user->enrollments->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach ($user->enrollments as $enrollment)
                                        <div
                                            class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-200">
                                            @if ($enrollment->course->image_url ?? false)
                                                <img src="{{ $enrollment->course->image_url }}"
                                                    alt="{{ $enrollment->course->title ?? 'Curso' }}"
                                                    class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                                            @else
                                                <div
                                                    class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0">
                                                    <i class="bi bi-book text-blue-500"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 text-sm truncate">
                                                    {{ $enrollment->course->title ?? 'Curso no disponible' }}</h4>
                                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                    <span
                                                        class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                        {{ $enrollment->course->category->name ?? 'Sin categoría' }}
                                                    </span>
                                                    <span class="text-xs text-gray-400">
                                                        <i
                                                            class="bi bi-calendar-alt mr-1"></i>{{ $enrollment->enrolled_at->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                                {{-- Barra de progreso animada --}}
                                                <div class="mt-2">
                                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                        <span>Progreso</span>
                                                        <span
                                                            class="font-semibold text-blue-600">{{ $enrollment->progress }}%</span>
                                                    </div>
                                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                        <div class="h-full rounded-full transition-all duration-700 ease-out
                                                        {{ $enrollment->progress >= 100 ? 'bg-green-500' : ($enrollment->progress >= 50 ? 'bg-blue-500' : 'bg-orange-400') }}"
                                                            style="width: {{ $enrollment->progress }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 text-right">
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-xs font-semibold
                                                {{ $enrollment->status === 'completed'
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($enrollment->status === 'active'
                                                        ? 'bg-blue-100 text-blue-800'
                                                        : 'bg-gray-100 text-gray-700') }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                @include('admin.users.partials.empty', [
                                    'icon' => 'fa-book-open',
                                    'message' => 'El estudiante no tiene inscripciones a cursos',
                                ])
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB: Certificados --}}
                <div x-show="activeTab === 'certificates'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                    <span class="w-1 h-5 bg-yellow-500 rounded-full inline-block"></span>
                                    Certificados Obtenidos
                                </h3>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                    {{ $user->certificates_count ?? 0 }} certificados
                                </span>
                            </div>

                            @if ($user->certificates->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($user->certificates as $certificate)
                                        <div
                                            class="border border-gray-200 rounded-xl p-5 hover:border-yellow-300 hover:shadow-md transition-all duration-200 group">
                                            <div class="flex items-start gap-3 mb-4">
                                                <div
                                                    class="p-2.5 rounded-xl bg-gradient-to-br from-yellow-100 to-amber-100 group-hover:from-yellow-200 group-hover:to-amber-200 transition-colors">
                                                    <i class="bi bi-award text-yellow-600"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="font-semibold text-gray-900 text-sm leading-tight">
                                                        {{ $certificate->course->title ?? 'Curso' }}</h4>
                                                    <p class="text-xs text-gray-400 mt-0.5 font-mono">
                                                        {{ $certificate->certificate_code }}</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3 text-xs">
                                                <div class="bg-gray-50 rounded-lg p-2">
                                                    <div class="text-gray-400 font-medium">N° Cert.</div>
                                                    <div class="text-gray-800 font-semibold mt-0.5">
                                                        {{ $certificate->getFormattedCertificateNumber() }}</div>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-2">
                                                    <div class="text-gray-400 font-medium">Emisión</div>
                                                    <div class="text-gray-800 font-semibold mt-0.5">
                                                        {{ $certificate->issue_date->format('d/m/Y') }}</div>
                                                </div>
                                                <div class="bg-gray-50 rounded-lg p-2">
                                                    <div class="text-gray-400 font-medium">Horas</div>
                                                    <div class="text-gray-800 font-semibold mt-0.5">
                                                        {{ $certificate->total_hours }}h</div>
                                                </div>
                                                @if ($certificate->expiry_date)
                                                    <div class="bg-gray-50 rounded-lg p-2">
                                                        <div class="text-gray-400 font-medium">Vence</div>
                                                        <div class="text-gray-800 font-semibold mt-0.5">
                                                            {{ $certificate->expiry_date->format('d/m/Y') }}</div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div
                                                class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                                                <span class="text-xs text-gray-400">
                                                    <i
                                                        class="bi bi-download mr-1"></i>{{ $certificate->download_count ?? 0 }}
                                                    descargas
                                                </span>
                                                <a href="{{ route('admin.certificates.show', $certificate) }}"
                                                    target="_blank"
                                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                                    Verificar <i class="bi bi-external-link-alt text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-14">
                                    <div
                                        class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="bi bi-award text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">El estudiante aún no tiene certificados</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB: Exámenes --}}
                <div x-show="activeTab === 'exams'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                    <span class="w-1 h-5 bg-orange-500 rounded-full inline-block"></span>
                                    Exámenes Realizados
                                </h3>
                                <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">
                                    {{ $user->exam_attempts_count ?? 0 }} intentos
                                </span>
                            </div>

                            @if ($user->examAttempts->isNotEmpty())
                                <div class="space-y-3">
                                    @foreach ($user->examAttempts->sortByDesc('completed_at') as $attempt)
                                        <div
                                            class="p-4 border border-gray-100 rounded-xl hover:border-orange-200 hover:bg-orange-50/20 transition-all duration-200">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 text-sm">
                                                        {{ $attempt->exam->title ?? 'Examen' }}</h4>
                                                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                                        <span
                                                            class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                            {{ $attempt->exam->course->title ?? 'No disponible' }}
                                                        </span>
                                                        <span class="text-xs text-gray-400">
                                                            Intento #{{ $attempt->attempt_number }}
                                                        </span>
                                                        @if ($attempt->completed_at)
                                                            <span class="text-xs text-gray-400">
                                                                <i
                                                                    class="bi bi-clock mr-1"></i>{{ $attempt->completed_at->format('d/m/Y H:i') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3 flex-shrink-0">
                                                    <span
                                                        class="text-xl font-extrabold {{ $attempt->passed ? 'text-green-600' : 'text-red-500' }}">
                                                        {{ $attempt->score }}<span
                                                            class="text-sm font-medium text-gray-400">/{{ $attempt->total_points }}</span>
                                                    </span>
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-bold
                                                    {{ $attempt->passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $attempt->passed ? '✓ Aprobado' : '✗ Reprobado' }}
                                                    </span>
                                                </div>
                                            </div>

                                            @if ($attempt->exam->passing_score)
                                                <div class="mt-2">
                                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                        @php $pct = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0; @endphp
                                                        <div class="h-full rounded-full {{ $attempt->passed ? 'bg-green-500' : 'bg-red-400' }}"
                                                            style="width: {{ $pct }}%"></div>
                                                    </div>
                                                    <p class="text-xs text-gray-400 mt-1">Mínimo aprobatorio:
                                                        {{ $attempt->exam->passing_score }}</p>
                                                </div>
                                            @endif

                                            @if ($attempt->certificate)
                                                <div
                                                    class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                                                    <div class="flex items-center gap-2 text-xs text-gray-600">
                                                        <i class="bi bi-patch-check text-yellow-500"></i>
                                                        <span>Certificado generado</span>
                                                    </div>
                                                    <a href="{{ route('admin.certificates.show', $attempt->certificate) }}"
                                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                                                        target="_blank">
                                                        Ver certificado <i
                                                            class="bi bi-external-link-alt text-xs ml-1"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-14">
                                    <div
                                        class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="bi bi-journal-richtext text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">El estudiante no ha realizado exámenes</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB: Progreso --}}
                <div x-show="activeTab === 'progress'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                    <span class="w-1 h-5 bg-blue-600 rounded-full inline-block"></span>
                                    Progreso de Cursos
                                </h3>
                                @if (isset($trackingData['course_progress']))
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $trackingData['course_progress']->count() ?? 0 }} cursos
                                    </span>
                                @endif
                            </div>

                            @if (isset($trackingData['course_progress']) && $trackingData['course_progress']->isNotEmpty())
                                <div class="space-y-4">
                                    @foreach ($trackingData['course_progress'] as $course)
                                        <div
                                            class="border border-gray-100 rounded-xl p-5 hover:border-blue-200 hover:bg-blue-50/20 transition-all duration-200">
                                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900">{{ $course['course_title'] }}
                                                    </h4>
                                                    <div
                                                        class="flex items-center gap-4 mt-1 flex-wrap text-xs text-gray-500">
                                                        <span><i class="bi bi-calendar-alt mr-1"></i>Inscrito:
                                                            {{ $course['enrolled_at'] }}</span>
                                                        @if ($course['completed_at'])
                                                            <span class="text-green-600"><i
                                                                    class="bi bi-check-circle mr-1"></i>Completado:
                                                                {{ $course['completed_at'] }}</span>
                                                        @endif
                                                        <span><i
                                                                class="bi bi-clock mr-1"></i>{{ $course['duration_days'] }}
                                                            días</span>
                                                    </div>
                                                </div>
                                                <div class="lg:w-72">
                                                    <div class="flex justify-between text-sm mb-1.5">
                                                        <span class="text-gray-500 font-medium">Progreso</span>
                                                        <span
                                                            class="font-bold {{ $course['progress'] >= 100 ? 'text-green-600' : 'text-blue-600' }}">{{ $course['progress'] }}%</span>
                                                    </div>
                                                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                                        <div class="h-full rounded-full transition-all duration-700
                                                        {{ $course['progress'] >= 100 ? 'bg-gradient-to-r from-green-400 to-green-500' : 'bg-gradient-to-r from-blue-500 to-blue-600' }}"
                                                            style="width: {{ $course['progress'] }}%"></div>
                                                    </div>
                                                    <div class="flex justify-between mt-1.5">
                                                        <span
                                                            class="text-xs px-2 py-0.5 rounded-full font-semibold
                                                        {{ $course['status'] === 'completed'
                                                            ? 'bg-green-100 text-green-700'
                                                            : ($course['status'] === 'active'
                                                                ? 'bg-blue-100 text-blue-700'
                                                                : 'bg-gray-100 text-gray-600') }}">
                                                            {{ ucfirst($course['status']) }}
                                                        </span>
                                                        @if ($course['progress'] < 100)
                                                            <span
                                                                class="text-xs text-gray-400">{{ 100 - $course['progress'] }}%
                                                                restante</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-14">
                                    <div
                                        class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="bi bi-chart-line text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No hay datos de progreso disponibles</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB: Seguimiento --}}
                <div x-show="activeTab === 'tracking'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                    <span class="w-1 h-5 bg-indigo-600 rounded-full inline-block"></span>
                                    Seguimiento del Estudiante
                                </h3>
                                <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Últimos 30
                                    días</span>
                            </div>

                            {{-- Stats generales --}}
                            @if (isset($trackingData['overall_stats']))
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
                                    @foreach ([
                                        ['label' => 'Sesiones', 'value' => $trackingData['overall_stats']['total_sessions'] ?? 0, 'color' => 'blue', 'icon' => 'bi bi-wifi'],
                                        ['label' => 'Días activos', 'value' => $trackingData['overall_stats']['active_days'] ?? 0, 'color' => 'green', 'icon' => 'bi bi-calendar-check'],
                                        ['label' => 'Sesión promedio', 'value' => ($trackingData['avg_session_time'] ?? 0) . ' min', 'color' => 'purple', 'icon' => 'bi bi-stopwatch'],
                                        ['label' => 'Actividades', 'value' => $trackingData['overall_stats']['total_activities'] ?? 0, 'color' => 'orange', 'icon' => 'bi bi-bolt']
                                    ] as $stat)
                                        <div
                                            class="bg-{{ $stat['color'] }}-50 border border-{{ $stat['color'] }}-200 rounded-xl p-4 text-center">
                                            <i class="bi {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 mb-2"></i>
                                            <div class="text-2xl font-extrabold text-{{ $stat['color'] }}-900">
                                                {{ $stat['value'] }}</div>
                                            <div class="text-xs text-{{ $stat['color'] }}-600 font-medium mt-0.5">
                                                {{ $stat['label'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Gráficos --}}
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="border border-gray-100 rounded-xl p-5">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Sesiones por Día</h4>
                                    <div class="h-56 relative">
                                        <canvas id="sessionsChart"></canvas>
                                    </div>
                                </div>
                                <div class="border border-gray-100 rounded-xl p-5">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Horas más Activas</h4>
                                    <div class="h-56 relative">
                                        <canvas id="activeHoursChart"></canvas>
                                    </div>
                                </div>
                                <div class="border border-gray-100 rounded-xl p-5">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Tipos de Actividad</h4>
                                    <div class="h-56 relative">
                                        <canvas id="activityTypeChart"></canvas>
                                    </div>
                                </div>
                                <div class="border border-gray-100 rounded-xl p-5">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Dispositivos Utilizados</h4>
                                    <div class="h-56 relative">
                                        <canvas id="devicesChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- Info adicional --}}
                            @if (isset($trackingData['overall_stats']))
                                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach ([
                                        ['label' => 'Hora más activa', 'value' => $trackingData['overall_stats']['most_active_hour'] ?? 'N/A', 'icon' => 'bi bi-clock', 'color' => 'blue'], 
                                        ['label' => 'Último acceso', 'value' => $trackingData['overall_stats']['last_login'] ?? 'Nunca', 'icon' => 'bi bi-sign-in-alt', 'color' => 'green'], 
                                        ['label' => 'Tiempo promedio', 'value' => ($trackingData['avg_session_time'] ?? 0) . ' min', 'icon' => 'bi bi-stopwatch', 'color' => 'purple']
                                    ] as $stat)
                                        <div class="border border-gray-100 rounded-xl p-5 flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-xl bg-{{ $stat['color'] }}-100 flex items-center justify-center flex-shrink-0">
                                                <i class="bi {{ $stat['icon'] }} text-{{ $stat['color'] }}-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-400 font-medium">{{ $stat['label'] }}</div>
                                                <div class="text-lg font-extrabold text-gray-900">{{ $stat['value'] }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- TAB: Cursos (Instructor) --}}
            @if ($user->role === 'instructor')
                <div x-show="activeTab === 'courses'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                                    <span class="w-1 h-5 bg-purple-600 rounded-full inline-block"></span>
                                    Cursos Creados
                                </h3>
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                    {{ $user->courses_count }} cursos
                                </span>
                            </div>

                            @if ($user->courses->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($user->courses as $course)
                                        <div
                                            class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:border-purple-200 hover:bg-purple-50/20 transition-all duration-200">
                                            @if ($course->image_url)
                                                <img src="{{ Storage::url($course->image_url) }}"
                                                    alt="{{ $course->title }}"
                                                    class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                            @else
                                                <div
                                                    class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center flex-shrink-0">
                                                    <i class="bi bi-book text-purple-500 text-xl"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 text-sm truncate">
                                                    {{ $course->title }}</h4>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span
                                                        class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                        {{ $course->category->name ?? 'Sin categoría' }}
                                                    </span>
                                                    <span class="text-xs text-gray-400">
                                                        <i
                                                            class="bi bi-people mr-1"></i>{{ $course->students_count ?? 0 }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('admin.courses.edit', $course) }}"
                                                    class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 mt-2 font-medium">
                                                    Ver curso <i class="bi bi-arrow-right text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-14">
                                    <div
                                        class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="bi bi-book text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">Este instructor no tiene cursos creados</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- TAB: Mis Ventas (solo si tiene código de promoción) --}}
            @if ($user->hasPromotionCode())
                <div x-show="activeTab === 'sales'" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

                    {{-- Tarjetas resumen --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                        {{-- Código de afiliado --}}
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-tag text-emerald-600"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-medium">Código</div>
                                <div class="font-extrabold text-gray-900 text-lg font-mono tracking-wider">
                                    {{ $user->code }}</div>
                            </div>
                        </div>
                        {{-- Total ventas --}}
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-shopping-cart text-blue-600"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-medium">Total ventas</div>
                                <div class="font-extrabold text-gray-900 text-2xl"
                                    x-text="salesStats.total ?? {{ $user->courses_sold_count ?? 0 }}"></div>
                            </div>
                        </div>
                        {{-- Comisión ganada --}}
                        <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-4 flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-coins text-emerald-600"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-medium">Comisión ganada</div>
                                <div class="font-extrabold text-emerald-700 text-xl">
                                    S/ <span
                                        x-text="salesStats.total_commission ?? '{{ number_format($user->total_commission ?? 0, 2) }}'"></span>
                                </div>
                            </div>
                        </div>
                        {{-- Pendiente de pago --}}
                        <div class="bg-white rounded-2xl border border-orange-200 shadow-sm p-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <i class="bi bi-clock text-orange-500"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-medium">Pendientes</div>
                                <div class="font-extrabold text-orange-600 text-2xl" x-text="salesStats.pending ?? '—'">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Panel principal --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            {{-- Header + Filtros --}}
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                                <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 flex-shrink-0">
                                    <span class="w-1 h-5 bg-emerald-500 rounded-full inline-block"></span>
                                    Historial de Ventas
                                </h3>
                                <div class="flex flex-wrap gap-2 items-center">
                                    {{-- Filtro status --}}
                                    <select x-model="salesFilters.status" @change="loadSales()"
                                        class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none transition-all">
                                        <option value="">Todos los estados</option>
                                        <option value="completed">Completadas</option>
                                        <option value="pending">Pendientes</option>
                                        <option value="cancelled">Canceladas</option>
                                    </select>
                                    {{-- Filtro fecha desde --}}
                                    <input type="date" x-model="salesFilters.date_from" @change="loadSales()"
                                        class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none transition-all">
                                    {{-- Filtro fecha hasta --}}
                                    <input type="date" x-model="salesFilters.date_to" @change="loadSales()"
                                        class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none transition-all">
                                    {{-- Botón limpiar --}}
                                    <button @click="clearSalesFilters()"
                                        x-show="salesFilters.status || salesFilters.date_from || salesFilters.date_to"
                                        class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-red-500 px-3 py-2 border border-gray-200 rounded-xl hover:border-red-300 transition-all bg-white">
                                        <i class="bi bi-x"></i> Limpiar
                                    </button>
                                </div>
                            </div>

                            {{-- Estado: cargando --}}
                            <div x-show="salesLoading" class="flex items-center justify-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <i class="bi bi-spinner fa-spin text-3xl text-emerald-500"></i>
                                    <span class="text-sm">Cargando ventas...</span>
                                </div>
                            </div>

                            {{-- Estado: error --}}
                            <div x-show="salesError && !salesLoading"
                                class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm mb-4">
                                <i class="bi bi-exclamation-triangle text-red-500 flex-shrink-0"></i>
                                <span x-text="salesError"></span>
                                <button @click="loadSales()" class="ml-auto text-xs underline">Reintentar</button>
                            </div>

                            {{-- Tabla de ventas --}}
                            <div x-show="!salesLoading && !salesError">
                                <div x-show="sales.length === 0" class="text-center py-14">
                                    <div
                                        class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="bi bi-cash-coin text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No se encontraron ventas</p>
                                    <p class="text-gray-400 text-xs mt-1">Prueba cambiando los filtros aplicados</p>
                                </div>

                                <div x-show="sales.length > 0" class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-gray-100">
                                                <th
                                                    class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3 pr-4">
                                                    Curso</th>
                                                <th
                                                    class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3 pr-4">
                                                    Comprador</th>
                                                <th
                                                    class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3 pr-4">
                                                    Venta</th>
                                                <th
                                                    class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3 pr-4">
                                                    Comisión</th>
                                                <th
                                                    class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3 pr-4">
                                                    Fecha</th>
                                                <th
                                                    class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wider pb-3">
                                                    Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            <template x-for="sale in sales" :key="sale.id">
                                                <tr class="hover:bg-gray-50/60 transition-colors">
                                                    {{-- Curso --}}
                                                    <td class="py-3.5 pr-4">
                                                        <div class="flex items-center gap-2.5">
                                                            <div
                                                                class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                                                <i class="bi bi-book text-emerald-600 text-xs"></i>
                                                            </div>
                                                            <span class="font-medium text-gray-900 max-w-[180px] truncate"
                                                                x-text="sale.course_title ?? '—'"></span>
                                                        </div>
                                                    </td>
                                                    {{-- Comprador --}}
                                                    <td class="py-3.5 pr-4">
                                                        <div>
                                                            <div class="font-medium text-gray-800"
                                                                x-text="sale.buyer_name ?? '—'"></div>
                                                            <div class="text-xs text-gray-400"
                                                                x-text="sale.buyer_email ?? ''"></div>
                                                        </div>
                                                    </td>
                                                    {{-- Monto venta --}}
                                                    <td class="py-3.5 pr-4 text-right">
                                                        <span class="font-semibold text-gray-900">
                                                            S/ <span
                                                                x-text="parseFloat(sale.sale_amount).toFixed(2)"></span>
                                                        </span>
                                                    </td>
                                                    {{-- Comisión --}}
                                                    <td class="py-3.5 pr-4 text-right">
                                                        <span class="font-bold text-emerald-700">
                                                            S/ <span
                                                                x-text="parseFloat(sale.commission_amount).toFixed(2)"></span>
                                                        </span>
                                                    </td>
                                                    {{-- Fecha --}}
                                                    <td class="py-3.5 pr-4 text-gray-500 whitespace-nowrap text-xs"
                                                        x-text="sale.sold_at_formatted ?? '—'"></td>
                                                    {{-- Estado --}}
                                                    <td class="py-3.5 text-center">
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold"
                                                            :class="{
                                                                'bg-green-100 text-green-800': sale
                                                                    .status === 'completed',
                                                                'bg-orange-100 text-orange-800': sale
                                                                    .status === 'pending',
                                                                'bg-red-100 text-red-800': sale.status === 'cancelled'
                                                            }">
                                                            <span class="w-1.5 h-1.5 rounded-full"
                                                                :class="{
                                                                    'bg-green-500': sale.status === 'completed',
                                                                    'bg-orange-500': sale.status === 'pending',
                                                                    'bg-red-500': sale.status === 'cancelled'
                                                                }"></span>
                                                            <span
                                                                x-text="sale.status === 'completed' ? 'Completada' : sale.status === 'pending' ? 'Pendiente' : 'Cancelada'"></span>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Paginación --}}
                                <div x-show="salesPagination.last_page > 1"
                                    class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-5 pt-5 border-t border-gray-100">
                                    {{-- Info --}}
                                    <p class="text-xs text-gray-400">
                                        Mostrando <span class="font-semibold text-gray-700"
                                            x-text="salesPagination.from"></span>–<span
                                            class="font-semibold text-gray-700" x-text="salesPagination.to"></span>
                                        de <span class="font-semibold text-gray-700"
                                            x-text="salesPagination.total"></span> ventas
                                    </p>
                                    {{-- Botones --}}
                                    <div class="flex items-center gap-1.5">
                                        <button @click="goSalesPage(salesPagination.current_page - 1)"
                                            :disabled="salesPagination.current_page === 1"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all text-xs">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <template x-for="page in paginationPages()" :key="page">
                                            <button @click="page !== '...' && goSalesPage(page)"
                                                :class="{
                                                    'bg-emerald-600 text-white border-emerald-600': page ===
                                                        salesPagination.current_page,
                                                    'border-gray-200 text-gray-600 hover:bg-gray-50': page !==
                                                        salesPagination.current_page && page !== '...',
                                                    'border-transparent text-gray-400 cursor-default': page === '...'
                                                }"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg border text-xs font-medium transition-all"
                                                x-text="page">
                                            </button>
                                        </template>
                                        <button @click="goSalesPage(salesPagination.current_page + 1)"
                                            :disabled="salesPagination.current_page === salesPagination.last_page"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all text-xs">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- TAB: Actividad --}}
            <div x-show="activeTab === 'activity'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

                {{-- Stats rápidas de actividad --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 text-center">
                        <div class="text-2xl font-extrabold text-gray-900" x-text="activityStats.total ?? '—'"></div>
                        <div class="text-xs text-gray-400 font-medium mt-0.5">Total acciones</div>
                    </div>
                    <div class="bg-white rounded-2xl border border-green-200 shadow-sm p-4 text-center">
                        <div class="text-2xl font-extrabold text-green-700" x-text="activityStats.logins ?? '—'"></div>
                        <div class="text-xs text-gray-400 font-medium mt-0.5">Inicios de sesión</div>
                    </div>
                    <div class="bg-white rounded-2xl border border-blue-200 shadow-sm p-4 text-center">
                        <div class="text-2xl font-extrabold text-blue-700" x-text="activityStats.courses ?? '—'"></div>
                        <div class="text-xs text-gray-400 font-medium mt-0.5">Acciones de cursos</div>
                    </div>
                    <div class="bg-white rounded-2xl border border-purple-200 shadow-sm p-4 text-center">
                        <div class="text-2xl font-extrabold text-purple-700" x-text="activityStats.today ?? '—'"></div>
                        <div class="text-xs text-gray-400 font-medium mt-0.5">Hoy</div>
                    </div>
                </div>

                {{-- Panel principal --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">

                        {{-- Header + Filtros --}}
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                            <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 flex-shrink-0">
                                <span class="w-1 h-5 bg-gray-400 rounded-full inline-block"></span>
                                Historial de Actividad
                            </h3>
                            <div class="flex flex-wrap gap-2 items-center">
                                {{-- Filtro tipo --}}
                                <select x-model="activityFilters.type" @change="loadActivities()"
                                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all">
                                    <option value="">Todos los tipos</option>
                                    <option value="login">Inicio de sesión</option>
                                    <option value="logout">Cierre de sesión</option>
                                    <option value="course_enrolled">Inscripción a curso</option>
                                    <option value="lesson_completed">Lección completada</option>
                                    <option value="exam_started">Examen iniciado</option>
                                    <option value="exam_completed">Examen completado</option>
                                    <option value="certificate_earned">Certificado obtenido</option>
                                    <option value="payment_completed">Pago completado</option>
                                    <option value="profile_updated">Perfil actualizado</option>
                                    <option value="password_changed">Contraseña cambiada</option>
                                    <option value="course_accessed">Acceso a curso</option>
                                    <option value="cart_added">Añadido al carrito</option>
                                </select>
                                {{-- Fecha desde --}}
                                <input type="date" x-model="activityFilters.date_from" @change="loadActivities()"
                                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all">
                                {{-- Fecha hasta --}}
                                <input type="date" x-model="activityFilters.date_to" @change="loadActivities()"
                                    class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition-all">
                                {{-- Limpiar --}}
                                <button @click="clearActivityFilters()"
                                    x-show="activityFilters.type || activityFilters.date_from || activityFilters.date_to"
                                    class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-red-500 px-3 py-2 border border-gray-200 rounded-xl hover:border-red-300 transition-all bg-white">
                                    <i class="bi bi-x"></i> Limpiar
                                </button>
                            </div>
                        </div>

                        {{-- Cargando --}}
                        <div x-show="activityLoading" class="flex items-center justify-center py-16">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i class="bi bi-arrow-clockwise text-3xl text-blue-400"></i>
                                <span class="text-sm">Cargando actividad...</span>
                            </div>
                        </div>

                        {{-- Error --}}
                        <div x-show="activityError && !activityLoading"
                            class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm mb-4">
                            <i class="bi bi-exclamation-triangle text-red-500 flex-shrink-0"></i>
                            <span x-text="activityError"></span>
                            <button @click="loadActivities()" class="ml-auto text-xs underline">Reintentar</button>
                        </div>

                        {{-- Contenido --}}
                        <div x-show="!activityLoading && !activityError">

                            {{-- Vacío --}}
                            <div x-show="activities.length === 0" class="text-center py-14">
                                <div
                                    class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="bi bi-clock-history text-gray-400 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium">No se encontró actividad</p>
                                <p class="text-gray-400 text-xs mt-1">Prueba cambiando los filtros aplicados</p>
                            </div>

                            {{-- Timeline --}}
                            <div x-show="activities.length > 0" class="relative">
                                {{-- Línea vertical del timeline --}}
                                <div class="absolute left-[18px] top-0 bottom-0 w-px bg-gray-100"></div>

                                <div class="space-y-3">
                                    <template x-for="(activity, index) in activities" :key="activity.id">
                                        <div class="flex gap-4 group">
                                            {{-- Ícono del tipo --}}
                                            <div class="flex-shrink-0 relative z-10">
                                                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-sm ring-2 ring-white transition-transform group-hover:scale-105"
                                                    :class="{
                                                        'bg-green-100': activity.color === 'green',
                                                        'bg-gray-100': activity.color === 'gray',
                                                        'bg-blue-100': activity.color === 'blue',
                                                        'bg-yellow-100': activity.color === 'yellow',
                                                        'bg-purple-100': activity.color === 'purple',
                                                        'bg-red-100': activity.color === 'red',
                                                        'bg-pink-100': activity.color === 'pink',
                                                    }">
                                                    <i class="text-sm"
                                                        :class="[
                                                            'bi bi-' + activity.icon,
                                                            {
                                                                'text-green-600': activity.color === 'green',
                                                                'text-gray-500': activity.color === 'gray',
                                                                'text-blue-600': activity.color === 'blue',
                                                                'text-yellow-600': activity.color === 'yellow',
                                                                'text-purple-600': activity.color === 'purple',
                                                                'text-red-600': activity.color === 'red',
                                                                'text-pink-600': activity.color === 'pink',
                                                            }
                                                        ]"></i>
                                                </div>
                                            </div>

                                            {{-- Tarjeta de actividad --}}
                                            <div class="flex-1 min-w-0 pb-3">
                                                <div
                                                    class="bg-gray-50 hover:bg-gray-100/80 border border-gray-100 hover:border-gray-200 rounded-xl p-3.5 transition-all duration-150">
                                                    <div
                                                        class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                                        <div class="flex-1 min-w-0">
                                                            {{-- Acción --}}
                                                            <p class="text-sm font-semibold text-gray-900"
                                                                x-text="activity.action"></p>
                                                            {{-- Descripción --}}
                                                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed"
                                                                x-text="activity.description"></p>

                                                            {{-- Datos extra (curso, ip, device) --}}
                                                            <div class="flex flex-wrap gap-2 mt-2">
                                                                <template x-if="activity.course_title">
                                                                    <span
                                                                        class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                                                        <i class="bi bi-book text-blue-400"
                                                                            style="font-size:10px"></i>
                                                                        <span x-text="activity.course_title"></span>
                                                                    </span>
                                                                </template>
                                                                <template x-if="activity.ip_address">
                                                                    <span
                                                                        class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">
                                                                        <i class="bi bi-network text-gray-400"
                                                                            style="font-size:10px"></i>
                                                                        <span x-text="activity.ip_address"></span>
                                                                    </span>
                                                                </template>
                                                                <template x-if="activity.device">
                                                                    <span
                                                                        class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">
                                                                        <i class="bi bi-laptop text-gray-400"
                                                                            style="font-size:10px"
                                                                            :class="activity.device === 'Mobile' ?
                                                                                'bi bi-mobile' : activity
                                                                                .device === 'Tablet' ?
                                                                                'bi bi-tablet' : 'bi bi-laptop'"></i>
                                                                        <span x-text="activity.device"></span>
                                                                    </span>
                                                                </template>
                                                                <template x-if="activity.browser">
                                                                    <span
                                                                        class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">
                                                                        <i class="bi bi-globe text-gray-400"
                                                                            style="font-size:10px"></i>
                                                                        <span x-text="activity.browser"></span>
                                                                    </span>
                                                                </template>
                                                            </div>
                                                        </div>

                                                        {{-- Fecha --}}
                                                        <div class="flex-shrink-0 text-right">
                                                            <span class="text-xs text-gray-400 whitespace-nowrap"
                                                                x-text="activity.formatted_date"></span>
                                                            <div class="text-xs text-gray-300 mt-0.5 whitespace-nowrap"
                                                                x-text="activity.diff_for_humans"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Paginación --}}
                            <div x-show="activityPagination.last_page > 1"
                                class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-5 pt-5 border-t border-gray-100">
                                <p class="text-xs text-gray-400">
                                    Mostrando
                                    <span class="font-semibold text-gray-700"
                                        x-text="activityPagination.from"></span>–<span class="font-semibold text-gray-700"
                                        x-text="activityPagination.to"></span>
                                    de <span class="font-semibold text-gray-700" x-text="activityPagination.total"></span>
                                    actividades
                                </p>
                                <div class="flex items-center gap-1.5">
                                    <button @click="goActivityPage(activityPagination.current_page - 1)"
                                        :disabled="activityPagination.current_page === 1"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all text-xs">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <template x-for="page in activityPaginationPages()" :key="page">
                                        <button @click="page !== '...' && goActivityPage(page)"
                                            :class="{
                                                'bg-blue-600 text-white border-blue-600': page === activityPagination
                                                    .current_page,
                                                'border-gray-200 text-gray-600 hover:bg-gray-50': page !==
                                                    activityPagination.current_page && page !== '...',
                                                'border-transparent text-gray-400 cursor-default': page === '...'
                                            }"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg border text-xs font-medium transition-all"
                                            x-text="page">
                                        </button>
                                    </template>
                                    <button @click="goActivityPage(activityPagination.current_page + 1)"
                                        :disabled="activityPagination.current_page === activityPagination.last_page"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all text-xs">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ===== MODAL: Cambiar Contraseña ===== --}}
        <div x-show="showPasswordModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none;">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="closePasswordModal()"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                            <i class="bi bi-key text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Cambiar Contraseña</h3>
                            <p class="text-xs text-gray-400">{{ $user->names }}</p>
                        </div>
                    </div>
                    <button @click="closePasswordModal()"
                        class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-4">
                    <div x-show="passwordSuccess"
                        class="flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                        <i class="bi bi-check-circle text-green-500"></i>
                        <span>Contraseña actualizada exitosamente</span>
                    </div>
                    <div x-show="passwordError"
                        class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        <i class="bi bi-exclamation-circle text-red-500"></i>
                        <span x-text="passwordError"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nueva Contraseña</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" x-model="newPassword"
                                placeholder="Mínimo 8 caracteres"
                                class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="bi" :class="showPass ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmar Contraseña</label>
                        <div class="relative">
                            <input :type="showPassConfirm ? 'text' : 'password'" x-model="confirmPassword"
                                placeholder="Repite la contraseña"
                                class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                                :class="confirmPassword && newPassword !== confirmPassword ?
                                    'border-red-400 focus:ring-red-400' : ''">
                            <button type="button" @click="showPassConfirm = !showPassConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="bi "
                                    :class="showPassConfirm ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                        </div>
                        <p x-show="confirmPassword && newPassword !== confirmPassword" class="text-xs text-red-500 mt-1">
                            Las contraseñas no coinciden
                        </p>
                    </div>

                    {{-- Indicador de fortaleza --}}
                    <div x-show="newPassword.length > 0">
                        <div class="flex gap-1 mt-1">
                            <div class="h-1.5 flex-1 rounded-full transition-colors"
                                :class="newPassword.length >= 1 ? 'bg-red-400' : 'bg-gray-200'"></div>
                            <div class="h-1.5 flex-1 rounded-full transition-colors"
                                :class="newPassword.length >= 6 ? 'bg-orange-400' : 'bg-gray-200'"></div>
                            <div class="h-1.5 flex-1 rounded-full transition-colors"
                                :class="newPassword.length >= 8 ? 'bg-yellow-400' : 'bg-gray-200'"></div>
                            <div class="h-1.5 flex-1 rounded-full transition-colors"
                                :class="newPassword.length >= 10 && /[A-Z]/.test(newPassword) ? 'bg-green-500' : 'bg-gray-200'">
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            <span
                                :class="newPassword.length >= 10 && /[A-Z]/.test(newPassword) ? 'text-green-600' :
                                    'text-gray-400'">
                                <span
                                    x-text="newPassword.length < 6 ? 'Muy débil' : newPassword.length < 8 ? 'Débil' : newPassword.length < 10 ? 'Aceptable' : /[A-Z]/.test(newPassword) ? 'Fuerte' : 'Aceptable'"></span>
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button @click="closePasswordModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button @click="updatePassword({{ $user->id }})"
                        :disabled="savingPassword || !newPassword || newPassword !== confirmPassword || newPassword.length < 8"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <i class="bi" :class="savingPassword ? 'bi-spinner bi-spin' : 'bi-save'"></i>
                        <span x-text="savingPassword ? 'Guardando...' : 'Guardar'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== MODAL: Inscribir en Curso ===== --}}
        <div x-show="showEnrollModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-[1040] flex items-center justify-center p-4"
            style="display: none;"
            @keydown.window.escape.prevent="">

            {{-- Backdrop estático (no cierra el modal) --}}
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            {{-- Modal md: max-w-[600px] --}}
            <div class="enroll-modal-dialog relative bg-white rounded-2xl shadow-2xl w-full z-10 overflow-visible"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/50 rounded-t-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <i class="bi bi-journal-plus text-emerald-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Inscribir en Curso</h3>
                            <p class="text-xs text-gray-500">{{ $user->names }}</p>
                        </div>
                    </div>
                    <button @click="closeEnrollModal()"
                        class="w-8 h-8 rounded-lg hover:bg-gray-200/60 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x text-lg"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-6 space-y-4">
                    {{-- Mensaje de Éxito --}}
                    <div x-show="enrollSuccess"
                        class="flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
                        <i class="bi bi-check-circle-fill text-emerald-500 text-base mt-0.5 flex-shrink-0"></i>
                        <span x-html="enrollMessage"></span>
                    </div>

                    {{-- Mensaje de Advertencia (Ya matriculado) --}}
                    <div x-show="enrollWarning"
                        class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-sm">
                        <i class="bi bi-exclamation-triangle-fill text-amber-500 text-base mt-0.5 flex-shrink-0"></i>
                        <span x-html="enrollWarning"></span>
                    </div>

                    {{-- Mensaje de Error --}}
                    <div x-show="enrollError"
                        class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
                        <i class="bi bi-x-circle-fill text-red-500 text-base mt-0.5 flex-shrink-0"></i>
                        <span x-html="enrollError"></span>
                    </div>

                    <div x-show="!enrollSuccess" class="space-y-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Buscar y Seleccionar Curso <span class="text-red-500">*</span>
                        </label>

                        {{-- SearchSelect: el JS instanciará sobre este select oculto --}}
                        <div id="search-select-wrapper">
                            <select id="course-select" name="course_id" style="display:none;"></select>
                        </div>

                        {{-- Badge de curso seleccionado (controlado por Alpine vía callback) --}}
                        <div x-show="selectedCourse"
                            class="text-xs text-emerald-700 font-medium flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 px-3.5 py-2 rounded-xl">
                            <i class="bi bi-check-circle-fill text-emerald-500 text-sm"></i>
                            <span>Curso seleccionado: <strong x-text="selectedCourse ? selectedCourse.title : ''"></strong></span>
                        </div>

                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <i class="bi bi-info-circle"></i> El estudiante tendrá acceso directo e inmediato al curso seleccionado.
                        </p>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 bg-gray-50/30 rounded-b-2xl">
                    <button @click="closeEnrollModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button @click="enrollUserInCourse({{ $user->id }})"
                        :disabled="enrollingCourse || !selectedCourseId"
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <i class="bi" :class="enrollingCourse ? 'bi-spinner bi-spin' : 'bi-check-lg'"></i>
                        <span x-text="enrollingCourse ? 'Inscribiendo...' : 'Confirmar Inscripción'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function userProfile() {
            return {
                activeTab: 'info',
                showPasswordModal: false,
                newPassword: '',
                confirmPassword: '',
                showPass: false,
                showPassConfirm: false,
                savingPassword: false,
                passwordSuccess: false,
                passwordError: '',
                togglingStatus: false,
                chartsInitialized: false,

                // ── Inscribir Curso ─────────────────────────────────
                showEnrollModal: false,
                selectedCourseId: '',
                selectedCourse: null,
                enrollingCourse: false,
                enrollSuccess: false,
                enrollMessage: '',
                enrollError: '',
                enrollWarning: '',

                openEnrollModal() {
                    this.selectedCourseId = '';
                    this.selectedCourse = null;
                    this.enrollSuccess = false;
                    this.enrollMessage = '';
                    this.enrollError = '';
                    this.enrollWarning = '';
                    this.showEnrollModal = true;

                    // Inicializar SearchSelect cuando el modal está visible
                    this.$nextTick(() => {
                        const selectEl = document.getElementById('course-select');
                        if (selectEl) {
                            if (window._courseSearchSelect) {
                                window._courseSearchSelect.reset();
                            } else {
                                const self = this;
                                window._courseSearchSelect = new SearchSelect(
                                    selectEl,
                                    '/admin/users/courses/search',
                                    function(item) {
                                        self.selectedCourseId = item.id;
                                        self.selectedCourse   = item;
                                    }
                                );
                                window._courseSearchSelect.init();
                            }
                        }
                    });
                },

                closeEnrollModal() {
                    if (window._courseSearchSelect) {
                        window._courseSearchSelect.close();
                    }
                    this.showEnrollModal = false;
                },

                async enrollUserInCourse(userId) {
                    if (!this.selectedCourseId) return;
                    this.enrollingCourse = true;
                    this.enrollSuccess = false;
                    this.enrollMessage = '';
                    this.enrollError = '';
                    this.enrollWarning = '';

                    try {
                        const response = await axios.post(`/admin/users/${userId}/enroll`, {
                            course_id: this.selectedCourseId
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.data.success) {
                            this.enrollSuccess = true;
                            this.enrollMessage = response.data.message;
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    } catch (e) {
                        const data = e.response?.data;
                        if (data?.warning) {
                            this.enrollWarning = data.message;
                        } else {
                            this.enrollError = data?.message || 'Error al inscribir en el curso. Intenta de nuevo.';
                        }
                    } finally {
                        this.enrollingCourse = false;
                    }
                },

                // ── Ventas ──────────────────────────────────────────
                sales: [],
                salesLoading: false,
                salesError: '',
                salesStats: {},
                salesFilters: {
                    status: '',
                    date_from: '',
                    date_to: ''
                },
                salesPagination: {
                    current_page: 1,
                    last_page: 1,
                    total: 0,
                    from: 0,
                    to: 0
                },
                salesLoaded: false,

                // ── Actividad ────────────────────────────────────────
                activities: [],
                activityLoading: false,
                activityError: '',
                activityStats: {},
                activityFilters: {
                    type: '',
                    date_from: '',
                    date_to: ''
                },
                activityPagination: {
                    current_page: 1,
                    last_page: 1,
                    total: 0,
                    from: 0,
                    to: 0
                },
                activityLoaded: false,

                async loadActivities(page = 1) {
                    if (this.activityLoading) return;
                    this.activityLoading = true;
                    this.activityError = '';
                    try {
                        const params = new URLSearchParams({
                            page,
                            type: this.activityFilters.type,
                            date_from: this.activityFilters.date_from,
                            date_to: this.activityFilters.date_to,
                        });
                        const response = await axios.get(`/admin/users/{{ $user->id }}/activity?${params}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = response.data;
                        this.activities = data.activities.data ?? [];
                        this.activityStats = data.stats ?? {};
                        this.activityPagination = {
                            current_page: data.activities.current_page,
                            last_page: data.activities.last_page,
                            total: data.activities.total,
                            from: data.activities.from ?? 0,
                            to: data.activities.to ?? 0,
                        };
                        this.activityLoaded = true;
                    } catch (e) {
                        this.activityError = e.response?.data?.message ??
                            'Error al cargar la actividad. Intenta de nuevo.';
                    } finally {
                        this.activityLoading = false;
                    }
                },

                goActivityPage(page) {
                    if (page < 1 || page > this.activityPagination.last_page) return;
                    this.loadActivities(page);
                },

                clearActivityFilters() {
                    this.activityFilters = {
                        type: '',
                        date_from: '',
                        date_to: ''
                    };
                    this.loadActivities();
                },

                activityPaginationPages() {
                    const total = this.activityPagination.last_page;
                    const current = this.activityPagination.current_page;
                    if (total <= 7) return Array.from({
                        length: total
                    }, (_, i) => i + 1);
                    const pages = [];
                    if (current <= 4) {
                        pages.push(1, 2, 3, 4, 5, '...', total);
                    } else if (current >= total - 3) {
                        pages.push(1, '...', total - 4, total - 3, total - 2, total - 1, total);
                    } else {
                        pages.push(1, '...', current - 1, current, current + 1, '...', total);
                    }
                    return pages;
                },
                // ────────────────────────────────────────────────────

                async loadSales(page = 1) {
                    if (this.salesLoading) return;
                    this.salesLoading = true;
                    this.salesError = '';
                    try {
                        const params = new URLSearchParams({
                            page,
                            status: this.salesFilters.status,
                            date_from: this.salesFilters.date_from,
                            date_to: this.salesFilters.date_to,
                        });
                        const response = await axios.get(`/admin/users/{{ $user->id }}/sales?${params}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = response.data;
                        this.sales = data.sales.data ?? [];
                        this.salesStats = data.stats ?? {};
                        this.salesPagination = {
                            current_page: data.sales.current_page,
                            last_page: data.sales.last_page,
                            total: data.sales.total,
                            from: data.sales.from ?? 0,
                            to: data.sales.to ?? 0,
                        };
                        this.salesLoaded = true;
                    } catch (e) {
                        this.salesError = e.response?.data?.message ?? 'Error al cargar las ventas. Intenta de nuevo.';
                    } finally {
                        this.salesLoading = false;
                    }
                },

                goSalesPage(page) {
                    if (page < 1 || page > this.salesPagination.last_page) return;
                    this.loadSales(page);
                },

                clearSalesFilters() {
                    this.salesFilters = {
                        status: '',
                        date_from: '',
                        date_to: ''
                    };
                    this.loadSales();
                },

                paginationPages() {
                    const total = this.salesPagination.last_page;
                    const current = this.salesPagination.current_page;
                    if (total <= 7) return Array.from({
                        length: total
                    }, (_, i) => i + 1);
                    const pages = [];
                    if (current <= 4) {
                        pages.push(1, 2, 3, 4, 5, '...', total);
                    } else if (current >= total - 3) {
                        pages.push(1, '...', total - 4, total - 3, total - 2, total - 1, total);
                    } else {
                        pages.push(1, '...', current - 1, current, current + 1, '...', total);
                    }
                    return pages;
                },
                // ────────────────────────────────────────────────────

                openPasswordModal() {
                    this.passwordSuccess = false;
                    this.passwordError = '';
                    this.newPassword = '';
                    this.confirmPassword = '';
                    this.showPasswordModal = true;
                },

                closePasswordModal() {
                    this.showPasswordModal = false;
                },

                async updatePassword(userId) {
                    if (this.newPassword !== this.confirmPassword || this.newPassword.length < 8) return;
                    this.savingPassword = true;
                    this.passwordSuccess = false;
                    this.passwordError = '';
                    try {
                        const response = await axios.put(`/admin/users/${userId}/password`, {
                            password: this.newPassword,
                            password_confirmation: this.confirmPassword
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        if (response.data.success) {
                            this.passwordSuccess = true;
                            this.newPassword = '';
                            this.confirmPassword = '';
                            setTimeout(() => {
                                this.closePasswordModal();
                            }, 1500);
                        }
                    } catch (e) {
                        this.passwordError = e.response?.data?.message || 'Error al actualizar la contraseña.';
                    } finally {
                        this.savingPassword = false;
                    }
                },

                async toggleStatus(userId, currentStatus) {
                    if (this.togglingStatus) return;
                    this.togglingStatus = true;
                    try {
                        const response = await axios.patch(`/admin/users/${userId}/toggle-status`, {}, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        if (response.data.success) {
                            // Recargar para reflejar el nuevo estado
                            window.location.reload();
                        }
                    } catch (e) {
                        alert('Error al actualizar el estado del usuario.');
                    } finally {
                        this.togglingStatus = false;
                    }
                },

                initCharts() {
                    if (this.chartsInitialized) return;
                    this.chartsInitialized = true;

                    const colors = {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        accent: '#8B5CF6',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                        gray: '#9CA3AF'
                    };

                    const defaultOptions = (yStepSize = 1) => ({
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: yStepSize
                                }
                            }
                        }
                    });

                    // Sesiones por día
                    const sessionsCtx = document.getElementById('sessionsChart')?.getContext('2d');
                    if (sessionsCtx) {
                        new Chart(sessionsCtx, {
                            type: 'bar',
                            data: {
                                labels: @json($trackingData['sessions']['labels'] ?? []),
                                datasets: [{
                                    label: 'Sesiones',
                                    data: @json($trackingData['sessions']['data'] ?? []),
                                    backgroundColor: 'rgba(59,130,246,0.7)',
                                    borderColor: colors.primary,
                                    borderWidth: 1,
                                    borderRadius: 6,
                                }]
                            },
                            options: defaultOptions()
                        });
                    }

                    // Horas activas
                    const hoursCtx = document.getElementById('activeHoursChart')?.getContext('2d');
                    if (hoursCtx) {
                        new Chart(hoursCtx, {
                            type: 'line',
                            data: {
                                labels: @json($trackingData['active_hours']['labels'] ?? []),
                                datasets: [{
                                    label: 'Actividad',
                                    data: @json($trackingData['active_hours']['data'] ?? []),
                                    backgroundColor: 'rgba(59,130,246,0.1)',
                                    borderColor: colors.primary,
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: colors.primary,
                                    pointRadius: 4,
                                }]
                            },
                            options: defaultOptions()
                        });
                    }

                    // Tipos de actividad
                    const activityCtx = document.getElementById('activityTypeChart')?.getContext('2d');
                    if (activityCtx) {
                        new Chart(activityCtx, {
                            type: 'doughnut',
                            data: {
                                labels: @json($trackingData['activity_by_type']['labels'] ?? []),
                                datasets: [{
                                    data: @json($trackingData['activity_by_type']['data'] ?? []),
                                    backgroundColor: [colors.primary, colors.secondary, colors.accent,
                                        colors.warning, colors.danger
                                    ],
                                    borderWidth: 0,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 16
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Dispositivos
                    const devicesCtx = document.getElementById('devicesChart')?.getContext('2d');
                    if (devicesCtx) {
                        new Chart(devicesCtx, {
                            type: 'pie',
                            data: {
                                labels: @json($trackingData['devices_used']['labels'] ?? []),
                                datasets: [{
                                    data: @json($trackingData['devices_used']['data'] ?? []),
                                    backgroundColor: [colors.primary, colors.secondary, colors.accent,
                                        colors.gray
                                    ],
                                    borderWidth: 0,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 16
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            }
        }
    </script>

    {{-- ===== SearchSelect: Vanilla JS ES6 — sin librerías externas ===== --}}
    <style>
        /* Modal md size */
        .enroll-modal-dialog { max-width: 600px; }

        /* SearchSelect wrapper injected by JS */
        .search-select-container {
            position: relative;
            width: 100%;
        }
        .search-select-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-select-input {
            width: 100%;
            padding: 0.72rem 2.6rem 0.72rem 2.5rem;
            border: 1.5px solid #d1d5db;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: #fff;
            color: #111827;
        }
        .search-select-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,.15);
        }
        .search-select-input.has-value {
            border-color: #10b981;
            background: rgba(16,185,129,.04);
            font-weight: 500;
        }
        .search-select-icon-left {
            position: absolute;
            left: 0.75rem;
            color: #9ca3af;
            pointer-events: none;
            display: flex;
            align-items: center;
        }
        .search-select-icon-right {
            position: absolute;
            right: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .search-select-clear-btn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #9ca3af;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            transition: color .15s;
        }
        .search-select-clear-btn:hover { color: #ef4444; }

        /* Dropdown appended to body */
        .search-select-dropdown {
            position: fixed;
            z-index: 9999;
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.875rem;
            box-shadow: 0 10px 40px -8px rgba(0,0,0,.22), 0 4px 16px -4px rgba(0,0,0,.1);
            max-height: 200px;
            overflow-y: auto;
            display: none;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .search-select-dropdown.is-open { display: block; }

        .search-select-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.45rem 0.85rem;
            background: #f9fafb;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #9ca3af;
            border-bottom: 1px solid #f3f4f6;
            border-radius: 0.875rem 0.875rem 0 0;
            position: sticky;
            top: 0;
        }
        .search-select-header-count { color: #10b981; font-weight: 700; }

        .search-select-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.65rem 1rem;
            cursor: pointer;
            font-size: 0.875rem;
            color: #374151;
            border-bottom: 1px solid #f9fafb;
            transition: background .13s;
        }
        .search-select-item:last-child { border-bottom: none; }
        .search-select-item:hover,
        .search-select-item.is-active  { background: rgba(16,185,129,.07); color: #064e3b; }
        .search-select-item.is-selected { background: rgba(16,185,129,.12); font-weight: 600; color: #065f46; }

        .search-select-item-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            background: #d1fae5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.8rem;
        }
        .search-select-item-title { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .search-select-item-check { color: #10b981; font-size: 0.85rem; flex-shrink: 0; }

        .search-select-status {
            padding: 0.9rem 1rem;
            text-align: center;
            font-size: 0.78rem;
            color: #6b7280;
        }
        .search-select-status.is-loading { color: #10b981; }
        .search-select-status.is-error   { color: #ef4444; }

        /* Spinner keyframe (Bootstrap-compat) */
        @keyframes ss-spin { to { transform: rotate(360deg); } }
        .ss-spin { display: inline-block; animation: ss-spin .7s linear infinite; }
    </style>

    <script>
    /* ================================================================
       SearchSelect  —  Vanilla JS ES6+, sin librerías externas
       Autor: generado por Antigravity AI
       URL base: /admin/users/courses/search
         · ?latest=5          → primeros 5 al abrir
         · ?search=term&limit=10 → búsqueda dinámica
    ================================================================ */
    class SearchSelect {
        /**
         * @param {HTMLSelectElement} selectEl   - El <select> original (se oculta)
         * @param {string}           searchBaseUrl - URL base de la API de búsqueda
         * @param {function}         onSelectCallback - fn(item:{id,title}) llamada al elegir
         */
        constructor(selectEl, searchBaseUrl, onSelectCallback) {
            this.select        = selectEl;
            this.baseUrl       = searchBaseUrl;
            this.onSelect      = onSelectCallback;
            this.activeIndex   = -1;
            this.items         = [];
            this.selectedItem  = null;
            this._debounceTimer= null;
            this._abortCtrl    = null;

            // Elementos DOM que se crearán en init()
            this.container  = null;
            this.inputWrap  = null;
            this.input      = null;
            this.iconLeft   = null;
            this.iconRight  = null;
            this.clearBtn   = null;
            this.dropdown   = null;

            // Bind de handlers para poder remover listeners
            this._onDocClick   = this._onDocClick.bind(this);
            this._onKeydown    = this._onKeydown.bind(this);
            this._onScrollRepos= this._onScrollRepos.bind(this);
        }

        /* ── Construcción del DOM ─────────────────────────────────── */
        init() {
            this.select.style.display = 'none';
            const wrapper = this.select.parentElement; // #search-select-wrapper

            // Contenedor principal
            this.container = document.createElement('div');
            this.container.className = 'search-select-container';

            // Input wrap
            this.inputWrap = document.createElement('div');
            this.inputWrap.className = 'search-select-input-wrap';

            // Icono izquierdo (lupa)
            this.iconLeft = document.createElement('span');
            this.iconLeft.className = 'search-select-icon-left';
            this.iconLeft.innerHTML = '<i class="bi bi-search" style="font-size:.9rem;"></i>';

            // Input texto
            this.input = document.createElement('input');
            this.input.type = 'text';
            this.input.className = 'search-select-input';
            this.input.placeholder = 'Escribe el nombre del curso...';
            this.input.autocomplete = 'off';
            this.input.setAttribute('aria-label', 'Buscar curso');
            this.input.setAttribute('role', 'combobox');
            this.input.setAttribute('aria-expanded', 'false');
            this.input.setAttribute('aria-autocomplete', 'list');

            // Iconos derechos (spinner / clear)
            this.iconRight = document.createElement('div');
            this.iconRight.className = 'search-select-icon-right';

            this.spinnerEl = document.createElement('span');
            this.spinnerEl.innerHTML = '<i class="bi bi-arrow-repeat ss-spin" style="font-size:.9rem;color:#10b981;"></i>';
            this.spinnerEl.style.display = 'none';

            this.clearBtn = document.createElement('button');
            this.clearBtn.type = 'button';
            this.clearBtn.className = 'search-select-clear-btn';
            this.clearBtn.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
            this.clearBtn.title = 'Limpiar selección';
            this.clearBtn.style.display = 'none';
            this.clearBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.clearSelection();
            });

            this.iconRight.appendChild(this.spinnerEl);
            this.iconRight.appendChild(this.clearBtn);

            this.inputWrap.appendChild(this.iconLeft);
            this.inputWrap.appendChild(this.input);
            this.inputWrap.appendChild(this.iconRight);
            this.container.appendChild(this.inputWrap);

            // Dropdown appended to body
            this.dropdown = document.createElement('ul');
            this.dropdown.className = 'search-select-dropdown';
            this.dropdown.setAttribute('role', 'listbox');
            document.body.appendChild(this.dropdown);

            // Insertar container antes del select oculto
            wrapper.insertBefore(this.container, this.select);

            // Eventos
            this.input.addEventListener('focus', () => {
                this.fetchInitial();
            });
            this.input.addEventListener('input', () => {
                this._debounce(() => this.fetchResults(this.input.value.trim()), 300);
            });

            document.addEventListener('click',    this._onDocClick);
            document.addEventListener('keydown',  this._onKeydown);
            window.addEventListener('scroll',     this._onScrollRepos, { passive: true });
            window.addEventListener('resize',     this._onScrollRepos, { passive: true });
        }

        /* ── API calls ────────────────────────────────────────────── */
        async fetchInitial() {
            this._openDropdown();
            this._showLoading();
            try {
                const url   = `${this.baseUrl}?latest=5`;
                const data  = await this._apiFetch(url);
                this.items  = data;
                this._renderItems(data, '');
            } catch (err) {
                if (err.name !== 'AbortError') this._showError();
            }
        }

        async fetchResults(query) {
            if (!query) { return this.fetchInitial(); }
            this._openDropdown();
            this._showLoading();
            try {
                const url  = `${this.baseUrl}?search=${encodeURIComponent(query)}&limit=10`;
                const data = await this._apiFetch(url);
                this.items = data;
                this._renderItems(data, query);
            } catch (err) {
                if (err.name !== 'AbortError') this._showError();
            }
        }

        async _apiFetch(url) {
            if (this._abortCtrl) this._abortCtrl.abort();
            this._abortCtrl = new AbortController();
            const resp = await fetch(url, {
                signal: this._abortCtrl.signal,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!resp.ok) throw new Error('HTTP ' + resp.status);
            const json = await resp.json();
            if (!json.success) throw new Error('API error');
            return json.data ?? [];
        }

        /* ── Render ───────────────────────────────────────────────── */
        _renderItems(items, query) {
            this.activeIndex = -1;
            this.dropdown.innerHTML = '';

            // Cabecera sticky
            const hdr = document.createElement('li');
            hdr.className = 'search-select-header';
            const hdrLabel = query
                ? `Resultados (máx 10)`
                : `Últimos 5 cursos agregados`;
            hdr.innerHTML = `<span>${hdrLabel}</span>` +
                (items.length ? `<span class="search-select-header-count">${items.length} curso(s)</span>` : '');
            this.dropdown.appendChild(hdr);

            if (!items.length) {
                const empty = document.createElement('li');
                empty.className = 'search-select-status';
                empty.textContent = query
                    ? `Sin resultados para "${query}"`
                    : 'No hay cursos activos disponibles.';
                this.dropdown.appendChild(empty);
                this._reposition();
                return;
            }

            items.forEach((item, idx) => {
                const li = document.createElement('li');
                li.className = 'search-select-item';
                li.setAttribute('role', 'option');
                li.dataset.idx = idx;

                if (this.selectedItem && this.selectedItem.id === item.id) {
                    li.classList.add('is-selected');
                }

                // Resaltar término en el título
                const title = query
                    ? this._highlight(item.title, query)
                    : this._esc(item.title);

                li.innerHTML =
                    `<span class="search-select-item-icon"><i class="bi bi-journal-text"></i></span>` +
                    `<span class="search-select-item-title">${title}</span>` +
                    (this.selectedItem && this.selectedItem.id === item.id
                        ? `<span class="search-select-item-check"><i class="bi bi-check-circle-fill"></i></span>`
                        : '');

                li.addEventListener('mousedown', (e) => {
                    e.preventDefault(); // No blur el input
                    this._selectItem(item);
                });
                li.addEventListener('mousemove', () => {
                    this.activeIndex = idx;
                    this._updateActiveStyle();
                });

                this.dropdown.appendChild(li);
            });

            this._reposition();
        }

        _showLoading() {
            this.dropdown.innerHTML = '';
            const li = document.createElement('li');
            li.className = 'search-select-status is-loading';
            li.innerHTML = '<i class="bi bi-arrow-repeat ss-spin" style="margin-right:.4rem;font-size:.85rem;"></i> Cargando...';
            this.dropdown.appendChild(li);
            this._reposition();
        }

        _showError() {
            this.dropdown.innerHTML = '';
            const li = document.createElement('li');
            li.className = 'search-select-status is-error';
            li.innerHTML = '<i class="bi bi-exclamation-circle" style="margin-right:.35rem;"></i> Error al cargar cursos. Intente de nuevo.';
            this.dropdown.appendChild(li);
            this._reposition();
        }

        /* ── Selección & limpieza ────────────────────────────────── */
        _selectItem(item) {
            this.selectedItem = item;
            this.input.value  = item.title;
            this.input.classList.add('has-value');
            this.clearBtn.style.display   = 'flex';
            this.spinnerEl.style.display  = 'none';
            this.close();

            // Sincronizar con el <select> oculto (si se usara en form clásico)
            this._syncSelect(item.id, item.title);

            // Notificar Alpine
            if (typeof this.onSelect === 'function') {
                this.onSelect(item);
            }
        }

        clearSelection() {
            this.selectedItem = null;
            this.input.value  = '';
            this.input.classList.remove('has-value');
            this.clearBtn.style.display = 'none';
            this._syncSelect('', '');
            if (typeof this.onSelect === 'function') {
                this.onSelect({ id: '', title: '' });
            }
            this.input.focus();
        }

        reset() {
            this.clearSelection();
            this.activeIndex = -1;
            this.close();
        }

        _syncSelect(id, title) {
            this.select.innerHTML = '';
            if (id) {
                const opt = document.createElement('option');
                opt.value    = id;
                opt.text     = title;
                opt.selected = true;
                this.select.appendChild(opt);
            }
        }

        /* ── Dropdown visibilidad y posición ────────────────────── */
        _openDropdown() {
            this.spinnerEl.style.display = 'flex';
            this.dropdown.classList.add('is-open');
            this.input.setAttribute('aria-expanded', 'true');
            this._reposition();
        }

        close() {
            this.dropdown.classList.remove('is-open');
            this.spinnerEl.style.display = 'none';
            this.input.setAttribute('aria-expanded', 'false');
            this.activeIndex = -1;
        }

        _reposition() {
            const rect = this.input.getBoundingClientRect();
            const dropH = Math.min(this.dropdown.scrollHeight, 200);
            const spaceBelow = window.innerHeight - rect.bottom;
            const openUpward = spaceBelow < dropH + 8 && rect.top > dropH + 8;

            this.dropdown.style.left  = rect.left  + 'px';
            this.dropdown.style.width = rect.width + 'px';

            if (openUpward) {
                this.dropdown.style.top    = '';
                this.dropdown.style.bottom = (window.innerHeight - rect.top + 4) + 'px';
            } else {
                this.dropdown.style.top    = (rect.bottom + 4) + 'px';
                this.dropdown.style.bottom = '';
            }
        }

        /* ── Teclado (ArrowUp/Down, Enter, Escape solo cierra lista) */
        _onKeydown(e) {
            if (!this.dropdown.classList.contains('is-open')) return;

            const items = Array.from(this.dropdown.querySelectorAll('.search-select-item'));
            if (!items.length) return;

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    this.activeIndex = Math.min(this.activeIndex + 1, items.length - 1);
                    this._updateActiveStyle();
                    items[this.activeIndex]?.scrollIntoView({ block: 'nearest' });
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    this.activeIndex = Math.max(this.activeIndex - 1, 0);
                    this._updateActiveStyle();
                    items[this.activeIndex]?.scrollIntoView({ block: 'nearest' });
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (this.activeIndex >= 0 && this.items[this.activeIndex]) {
                        this._selectItem(this.items[this.activeIndex]);
                    }
                    break;
                case 'Escape':
                    // Solo cierra el dropdown, NO el modal
                    e.stopPropagation();
                    this.close();
                    break;
            }
        }

        _updateActiveStyle() {
            const items = Array.from(this.dropdown.querySelectorAll('.search-select-item'));
            items.forEach((el, idx) => {
                el.classList.toggle('is-active', idx === this.activeIndex);
            });
        }

        /* ── Click fuera del componente cierra solo el dropdown ─── */
        _onDocClick(e) {
            if (!this.container.contains(e.target) && !this.dropdown.contains(e.target)) {
                this.close();
            }
        }

        _onScrollRepos() {
            if (this.dropdown.classList.contains('is-open')) {
                this._reposition();
            }
        }

        /* ── Helpers ─────────────────────────────────────────────── */
        _debounce(fn, delay) {
            clearTimeout(this._debounceTimer);
            this._debounceTimer = setTimeout(fn, delay);
        }

        _esc(str) {
            return str.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
        }

        _highlight(text, query) {
            const safe  = this._esc(text);
            const safeQ = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return safe.replace(new RegExp(`(${safeQ})`, 'gi'),
                '<mark style="background:rgba(16,185,129,.25);border-radius:2px;padding:0 2px;">$1</mark>');
        }

        /* ── Destructor ──────────────────────────────────────────── */
        destroy() {
            document.removeEventListener('click',   this._onDocClick);
            document.removeEventListener('keydown', this._onKeydown);
            window.removeEventListener('scroll',    this._onScrollRepos);
            window.removeEventListener('resize',    this._onScrollRepos);
            this.dropdown.remove();
            this.container.remove();
        }
    }
    </script>
@endsection
