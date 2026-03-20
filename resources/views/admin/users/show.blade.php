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
                        @if($user->profile_photo)
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->names }}" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-white shadow-lg">
                        @else
                            <div class="w-24 h-24 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-lg
                                @if($user->role === 'admin') bg-gradient-to-br from-orange-400 to-orange-600
                                @elseif($user->role === 'instructor') bg-gradient-to-br from-purple-500 to-purple-700
                                @else bg-gradient-to-br from-blue-500 to-blue-700 @endif">
                                {{ strtoupper(substr($user->names, 0, 1)) }}
                            </div>
                        @endif
                        {{-- Indicador de estado --}}
                        <span class="absolute -bottom-1.5 -right-1.5 w-5 h-5 rounded-full border-2 border-white shadow
                            {{ $user->is_active ? 'bg-green-400' : 'bg-gray-400' }}"></span>
                    </div>

                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">{{ $user->names }}</h1>
                        <p class="text-gray-500 mt-0.5">{{ $user->email }}</p>

                        <div class="flex flex-wrap gap-2 mt-3">
                            {{-- Rol --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                @if($user->role === 'admin') bg-orange-100 text-orange-800
                                @elseif($user->role === 'instructor') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800 @endif">
                                @if($user->role === 'admin') <i class="fas fa-shield-alt text-xs"></i>
                                @elseif($user->role === 'instructor') <i class="fas fa-chalkboard-teacher text-xs"></i>
                                @else <i class="fas fa-user-graduate text-xs"></i> @endif
                                {{ ucfirst($user->role) }}
                            </span>

                            {{-- Estado --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>

                            {{-- DNI --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                <i class="fas fa-id-card text-xs"></i>
                                {{ $user->dni }}
                            </span>

                            {{-- Fecha registro --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                <i class="fas fa-calendar text-xs"></i>
                                Desde {{ $user->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex flex-wrap items-center gap-2 lg:mt-0">
                    {{-- Cambiar Contraseña --}}
                    <button @click="openPasswordModal()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm">
                        <i class="fas fa-key text-gray-500"></i>
                        Contraseña
                    </button>

                    {{-- Toggle Estado --}}
                    <button
                        @click="toggleStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})"
                        :disabled="togglingStatus"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm border
                            {{ $user->is_active
                                ? 'bg-white border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300'
                                : 'bg-white border-green-200 text-green-700 hover:bg-green-50 hover:border-green-300' }}">
                        <i class="fas text-xs" :class="togglingStatus ? 'fa-spinner fa-spin' : '{{ $user->is_active ? 'fa-ban' : 'fa-check-circle' }}'"></i>
                        <span x-text="togglingStatus ? 'Actualizando...' : '{{ $user->is_active ? 'Desactivar' : 'Activar' }}'"></span>
                    </button>

                    {{-- Editar --}}
                    <a href="{{ route('admin.users.edit', $user) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-sm">
                        <i class="fas fa-pen text-xs"></i>
                        Editar
                    </a>

                    {{-- Volver --}}
                    <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium text-sm transition-all duration-200 shadow-sm">
                        <i class="fas fa-arrow-left text-xs"></i>
                        Volver
                    </a>
                </div>
            </div>

            {{-- ===== STATS RÁPIDAS ===== --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 text-center">
                    <div class="text-2xl font-extrabold text-blue-900">{{ $user->enrollments->count() ?? 0 }}</div>
                    <div class="text-xs text-blue-600 font-medium mt-0.5">Inscripciones</div>
                </div>
                <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 text-center">
                    <div class="text-2xl font-extrabold text-green-900">{{ $user->courses->count() ?? 0 }}</div>
                    <div class="text-xs text-green-600 font-medium mt-0.5">Cursos</div>
                </div>
                <div class="p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 text-center">
                    <div class="text-2xl font-extrabold text-yellow-900">{{ $user->certificates->count() ?? 0 }}</div>
                    <div class="text-xs text-yellow-600 font-medium mt-0.5">Certificados</div>
                </div>
                <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 text-center">
                    <div class="text-2xl font-extrabold text-purple-900">{{ $user->examAttempts->count() ?? 0 }}</div>
                    <div class="text-xs text-purple-600 font-medium mt-0.5">Exámenes</div>
                </div>
            </div>

            {{-- ===== TABS ===== --}}
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <nav class="flex overflow-x-auto">
                    <button @click="activeTab = 'info'"
                        :class="activeTab === 'info' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-user"></i> Información
                    </button>

                    @if($user->role === 'student')
                    <button @click="activeTab = 'enrollments'"
                        :class="activeTab === 'enrollments' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-book-open"></i> Inscripciones
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold
                            {{ 'bg-blue-100 text-blue-700' }}">{{ $user->enrollments->count() ?? 0 }}</span>
                    </button>
                    <button @click="activeTab = 'certificates'"
                        :class="activeTab === 'certificates' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-award"></i> Certificados
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">{{ $user->certificates->count() ?? 0 }}</span>
                    </button>
                    <button @click="activeTab = 'exams'"
                        :class="activeTab === 'exams' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-clipboard-list"></i> Exámenes
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700">{{ $user->examAttempts->count() ?? 0 }}</span>
                    </button>
                    <button @click="activeTab = 'progress'"
                        :class="activeTab === 'progress' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-chart-line"></i> Progreso
                    </button>
                    <button @click="activeTab = 'tracking'; $nextTick(() => initCharts())"
                        :class="activeTab === 'tracking' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-chart-bar"></i> Seguimiento
                    </button>
                    @endif

                    @if($user->role === 'instructor')
                    <button @click="activeTab = 'courses'"
                        :class="activeTab === 'courses' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-book"></i> Cursos
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">{{ $user->courses_count ?? 0 }}</span>
                    </button>
                    @endif

                    <button @click="activeTab = 'activity'"
                        :class="activeTab === 'activity' ? 'border-blue-600 text-blue-700 bg-blue-50/60' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="fas fa-history"></i> Actividad
                    </button>
                </nav>
            </div>
        </div>

        {{-- ===== CONTENIDO DE TABS ===== --}}

        {{-- TAB: Información --}}
        <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        <span class="w-1 h-5 bg-blue-600 rounded-full inline-block"></span>
                        Información del Usuario
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        {{-- Datos personales --}}
                        <div class="space-y-5">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Datos Personales</h4>
                            @foreach([
                                ['label' => 'DNI', 'value' => $user->dni, 'icon' => 'fa-id-card'],
                                ['label' => 'Nombres Completos', 'value' => $user->names, 'icon' => 'fa-user'],
                                ['label' => 'Email', 'value' => $user->email, 'icon' => 'fa-envelope'],
                                ['label' => 'Teléfono', 'value' => $user->phone ?? 'No registrado', 'icon' => 'fa-phone'],
                            ] as $field)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas {{ $field['icon'] }} text-gray-500 text-xs"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">{{ $field['label'] }}</div>
                                    <div class="text-gray-900 font-medium text-sm mt-0.5">{{ $field['value'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Información adicional --}}
                        <div class="space-y-5">
                            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Información Adicional</h4>
                            @foreach([
                                ['label' => 'Nacionalidad', 'value' => $user->nationality ?? 'No registrada', 'icon' => 'fa-flag'],
                                ['label' => 'Profesión', 'value' => $user->profession ?? 'No registrada', 'icon' => 'fa-briefcase'],
                                ['label' => 'Dirección', 'value' => $user->address ?? 'No registrada', 'icon' => 'fa-map-marker-alt'],
                                ['label' => 'Fecha de Registro', 'value' => $user->created_at->format('d/m/Y H:i'), 'icon' => 'fa-calendar-check'],
                            ] as $field)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas {{ $field['icon'] }} text-gray-500 text-xs"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 font-medium">{{ $field['label'] }}</div>
                                    <div class="text-gray-900 font-medium text-sm mt-0.5">{{ $field['value'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Roles del sistema --}}
                    @if($user->getRoleNames()->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Roles del Sistema</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->getRoleNames() as $roleName)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-full text-xs font-semibold border border-blue-200">
                                    <i class="fas fa-user-tag text-blue-500 text-xs"></i>
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
        @if($user->role === 'student')
        <div x-show="activeTab === 'enrollments'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-1 h-5 bg-blue-600 rounded-full inline-block"></span>
                            Inscripciones a Cursos
                        </h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                            {{ $user->enrollments_count ?? 0 }} cursos
                        </span>
                    </div>

                    @if($user->enrollments->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($user->enrollments as $enrollment)
                                <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-200">
                                    @if($enrollment->course->image_url ?? false)
                                        <img src="{{ $enrollment->course->image_url }}" alt="{{ $enrollment->course->title ?? 'Curso' }}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-book text-blue-500"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $enrollment->course->title ?? 'Curso no disponible' }}</h4>
                                        <div class="flex items-center gap-2 mt-1 flex-wrap">
                                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                {{ $enrollment->course->category->name ?? 'Sin categoría' }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-calendar-alt mr-1"></i>{{ $enrollment->enrolled_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        {{-- Barra de progreso animada --}}
                                        <div class="mt-2">
                                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                <span>Progreso</span>
                                                <span class="font-semibold text-blue-600">{{ $enrollment->progress }}%</span>
                                            </div>
                                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-700 ease-out
                                                    {{ $enrollment->progress >= 100 ? 'bg-green-500' : ($enrollment->progress >= 50 ? 'bg-blue-500' : 'bg-orange-400') }}"
                                                    style="width: {{ $enrollment->progress }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                            {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                            ($enrollment->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        @include('partials.empty', ['icon' => 'fa-book-open', 'message' => 'El estudiante no tiene inscripciones a cursos'])
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB: Certificados --}}
        <div x-show="activeTab === 'certificates'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
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

                    @if($user->certificates->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($user->certificates as $certificate)
                                <div class="border border-gray-200 rounded-xl p-5 hover:border-yellow-300 hover:shadow-md transition-all duration-200 group">
                                    <div class="flex items-start gap-3 mb-4">
                                        <div class="p-2.5 rounded-xl bg-gradient-to-br from-yellow-100 to-amber-100 group-hover:from-yellow-200 group-hover:to-amber-200 transition-colors">
                                            <i class="fas fa-award text-yellow-600"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-semibold text-gray-900 text-sm leading-tight">{{ $certificate->course->title ?? 'Curso' }}</h4>
                                            <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $certificate->certificate_code }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        <div class="bg-gray-50 rounded-lg p-2">
                                            <div class="text-gray-400 font-medium">N° Cert.</div>
                                            <div class="text-gray-800 font-semibold mt-0.5">{{ $certificate->getFormattedCertificateNumber() }}</div>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2">
                                            <div class="text-gray-400 font-medium">Emisión</div>
                                            <div class="text-gray-800 font-semibold mt-0.5">{{ $certificate->issue_date->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-2">
                                            <div class="text-gray-400 font-medium">Horas</div>
                                            <div class="text-gray-800 font-semibold mt-0.5">{{ $certificate->total_hours }}h</div>
                                        </div>
                                        @if($certificate->expiry_date)
                                        <div class="bg-gray-50 rounded-lg p-2">
                                            <div class="text-gray-400 font-medium">Vence</div>
                                            <div class="text-gray-800 font-semibold mt-0.5">{{ $certificate->expiry_date->format('d/m/Y') }}</div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                                        <span class="text-xs text-gray-400">
                                            <i class="fas fa-download mr-1"></i>{{ $certificate->download_count ?? 0 }} descargas
                                        </span>
                                        <a href="{{ route('admin.certificates.show', $certificate) }}" target="_blank"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                            Verificar <i class="fas fa-external-link-alt text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-14">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <i class="fas fa-award text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">El estudiante aún no tiene certificados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB: Exámenes --}}
        <div x-show="activeTab === 'exams'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
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

                    @if($user->examAttempts->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($user->examAttempts->sortByDesc('completed_at') as $attempt)
                                <div class="p-4 border border-gray-100 rounded-xl hover:border-orange-200 hover:bg-orange-50/20 transition-all duration-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $attempt->exam->title ?? 'Examen' }}</h4>
                                            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                    {{ $attempt->exam->course->title ?? 'No disponible' }}
                                                </span>
                                                <span class="text-xs text-gray-400">
                                                    Intento #{{ $attempt->attempt_number }}
                                                </span>
                                                @if($attempt->completed_at)
                                                    <span class="text-xs text-gray-400">
                                                        <i class="fas fa-clock mr-1"></i>{{ $attempt->completed_at->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 flex-shrink-0">
                                            <span class="text-xl font-extrabold {{ $attempt->passed ? 'text-green-600' : 'text-red-500' }}">
                                                {{ $attempt->score }}<span class="text-sm font-medium text-gray-400">/{{ $attempt->total_points }}</span>
                                            </span>
                                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                                {{ $attempt->passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $attempt->passed ? '✓ Aprobado' : '✗ Reprobado' }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($attempt->exam->passing_score)
                                    <div class="mt-2">
                                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            @php $pct = $attempt->total_points > 0 ? round(($attempt->score / $attempt->total_points) * 100) : 0; @endphp
                                            <div class="h-full rounded-full {{ $attempt->passed ? 'bg-green-500' : 'bg-red-400' }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">Mínimo aprobatorio: {{ $attempt->exam->passing_score }}</p>
                                    </div>
                                    @endif

                                    @if($attempt->certificate)
                                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                                        <div class="flex items-center gap-2 text-xs text-gray-600">
                                            <i class="fas fa-certificate text-yellow-500"></i>
                                            <span>Certificado generado</span>
                                        </div>
                                        <a href="{{ route('admin.certificates.show', $attempt->certificate) }}"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium" target="_blank">
                                            Ver certificado <i class="fas fa-external-link-alt text-xs ml-1"></i>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-14">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">El estudiante no ha realizado exámenes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB: Progreso --}}
        <div x-show="activeTab === 'progress'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-1 h-5 bg-blue-600 rounded-full inline-block"></span>
                            Progreso de Cursos
                        </h3>
                        @if(isset($trackingData['course_progress']))
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                            {{ $trackingData['course_progress']->count() ?? 0 }} cursos
                        </span>
                        @endif
                    </div>

                    @if(isset($trackingData['course_progress']) && $trackingData['course_progress']->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($trackingData['course_progress'] as $course)
                                <div class="border border-gray-100 rounded-xl p-5 hover:border-blue-200 hover:bg-blue-50/20 transition-all duration-200">
                                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $course['course_title'] }}</h4>
                                            <div class="flex items-center gap-4 mt-1 flex-wrap text-xs text-gray-500">
                                                <span><i class="fas fa-calendar-alt mr-1"></i>Inscrito: {{ $course['enrolled_at'] }}</span>
                                                @if($course['completed_at'])
                                                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Completado: {{ $course['completed_at'] }}</span>
                                                @endif
                                                <span><i class="fas fa-clock mr-1"></i>{{ $course['duration_days'] }} días</span>
                                            </div>
                                        </div>
                                        <div class="lg:w-72">
                                            <div class="flex justify-between text-sm mb-1.5">
                                                <span class="text-gray-500 font-medium">Progreso</span>
                                                <span class="font-bold {{ $course['progress'] >= 100 ? 'text-green-600' : 'text-blue-600' }}">{{ $course['progress'] }}%</span>
                                            </div>
                                            <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-700
                                                    {{ $course['progress'] >= 100 ? 'bg-gradient-to-r from-green-400 to-green-500' : 'bg-gradient-to-r from-blue-500 to-blue-600' }}"
                                                    style="width: {{ $course['progress'] }}%"></div>
                                            </div>
                                            <div class="flex justify-between mt-1.5">
                                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                                                    {{ $course['status'] === 'completed' ? 'bg-green-100 text-green-700' :
                                                    ($course['status'] === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                                    {{ ucfirst($course['status']) }}
                                                </span>
                                                @if($course['progress'] < 100)
                                                    <span class="text-xs text-gray-400">{{ 100 - $course['progress'] }}% restante</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-14">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No hay datos de progreso disponibles</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB: Seguimiento --}}
        <div x-show="activeTab === 'tracking'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-1 h-5 bg-indigo-600 rounded-full inline-block"></span>
                            Seguimiento del Estudiante
                        </h3>
                        <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Últimos 30 días</span>
                    </div>

                    {{-- Stats generales --}}
                    @if(isset($trackingData['overall_stats']))
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
                        @foreach([
                            ['label' => 'Sesiones', 'value' => $trackingData['overall_stats']['total_sessions'] ?? 0, 'color' => 'blue', 'icon' => 'fa-wifi'],
                            ['label' => 'Días activos', 'value' => $trackingData['overall_stats']['active_days'] ?? 0, 'color' => 'green', 'icon' => 'fa-calendar-check'],
                            ['label' => 'Sesión promedio', 'value' => ($trackingData['avg_session_time'] ?? 0) . ' min', 'color' => 'purple', 'icon' => 'fa-stopwatch'],
                            ['label' => 'Actividades', 'value' => $trackingData['overall_stats']['total_activities'] ?? 0, 'color' => 'orange', 'icon' => 'fa-bolt'],
                        ] as $stat)
                        <div class="bg-{{ $stat['color'] }}-50 border border-{{ $stat['color'] }}-200 rounded-xl p-4 text-center">
                            <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-500 mb-2"></i>
                            <div class="text-2xl font-extrabold text-{{ $stat['color'] }}-900">{{ $stat['value'] }}</div>
                            <div class="text-xs text-{{ $stat['color'] }}-600 font-medium mt-0.5">{{ $stat['label'] }}</div>
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
                    @if(isset($trackingData['overall_stats']))
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach([
                            ['label' => 'Hora más activa', 'value' => $trackingData['overall_stats']['most_active_hour'] ?? 'N/A', 'icon' => 'fa-clock', 'color' => 'blue'],
                            ['label' => 'Último acceso', 'value' => $trackingData['overall_stats']['last_login'] ?? 'Nunca', 'icon' => 'fa-sign-in-alt', 'color' => 'green'],
                            ['label' => 'Tiempo promedio', 'value' => ($trackingData['avg_session_time'] ?? 0) . ' min', 'icon' => 'fa-stopwatch', 'color' => 'purple'],
                        ] as $stat)
                        <div class="border border-gray-100 rounded-xl p-5 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-{{ $stat['color'] }}-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $stat['icon'] }} text-{{ $stat['color'] }}-600"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400 font-medium">{{ $stat['label'] }}</div>
                                <div class="text-lg font-extrabold text-gray-900">{{ $stat['value'] }}</div>
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
        @if($user->role === 'instructor')
        <div x-show="activeTab === 'courses'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
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

                    @if($user->courses->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($user->courses as $course)
                                <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:border-purple-200 hover:bg-purple-50/20 transition-all duration-200">
                                    @if($course->image_url)
                                        <img src="{{ Storage::url($course->image_url) }}" alt="{{ $course->title }}"
                                            class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-book text-purple-500 text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $course->title }}</h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full">
                                                {{ $course->category->name ?? 'Sin categoría' }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                <i class="fas fa-users mr-1"></i>{{ $course->students_count ?? 0 }}
                                            </span>
                                        </div>
                                        <a href="{{ route('admin.courses.edit', $course) }}"
                                        class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 mt-2 font-medium">
                                            Ver curso <i class="fas fa-arrow-right text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-14">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                                <i class="fas fa-book text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Este instructor no tiene cursos creados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- TAB: Actividad --}}
        <div x-show="activeTab === 'activity'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2 mb-5">
                        <span class="w-1 h-5 bg-gray-400 rounded-full inline-block"></span>
                        Actividad Reciente
                    </h3>
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-5 top-0 bottom-0 w-px bg-gray-200"></div>
                        <div class="space-y-4 pl-14">
                            {{-- Registro --}}
                            <div class="relative">
                                <div class="absolute -left-9 w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="font-medium text-gray-900 text-sm">Usuario registrado</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $user->created_at->format('d/m/Y H:i') }} · {{ $user->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            @if($user->last_login_at)
                            <div class="relative">
                                <div class="absolute -left-9 w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt text-green-600 text-sm"></i>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="font-medium text-gray-900 text-sm">Último acceso</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $user->last_login_at->format('d/m/Y H:i') }} · {{ $user->last_login_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            @if($user->enrollments->isNotEmpty())
                            <div class="relative">
                                <div class="absolute -left-9 w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-book-open text-purple-600 text-sm"></i>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="font-medium text-gray-900 text-sm">Última inscripción</p>
                                    <p class="text-xs text-gray-700 mt-0.5">{{ $user->enrollments->sortByDesc('enrolled_at')->first()->course->title ?? 'Curso' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->enrollments->sortByDesc('enrolled_at')->first()->enrolled_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endif

                            @if($user->certificates->isNotEmpty())
                            <div class="relative">
                                <div class="absolute -left-9 w-9 h-9 rounded-xl bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-award text-yellow-600 text-sm"></i>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="font-medium text-gray-900 text-sm">Último certificado</p>
                                    <p class="text-xs text-gray-700 mt-0.5">{{ $user->certificates->sortByDesc('issue_date')->first()->course->title ?? 'Curso' }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $user->certificates->sortByDesc('issue_date')->first()->issue_date->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: Cambiar Contraseña ===== --}}
    <div x-show="showPasswordModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
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
                        <i class="fas fa-key text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Cambiar Contraseña</h3>
                        <p class="text-xs text-gray-400">{{ $user->names }}</p>
                    </div>
                </div>
                <button @click="closePasswordModal()" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-4">
                <div x-show="passwordSuccess" class="flex items-center gap-2 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>Contraseña actualizada exitosamente</span>
                </div>
                <div x-show="passwordError" class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
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
                            <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmar Contraseña</label>
                    <div class="relative">
                        <input :type="showPassConfirm ? 'text' : 'password'" x-model="confirmPassword"
                            placeholder="Repite la contraseña"
                            class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"
                            :class="confirmPassword && newPassword !== confirmPassword ? 'border-red-400 focus:ring-red-400' : ''">
                        <button type="button" @click="showPassConfirm = !showPassConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas" :class="showPassConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p x-show="confirmPassword && newPassword !== confirmPassword" class="text-xs text-red-500 mt-1">
                        Las contraseñas no coinciden
                    </p>
                </div>

                {{-- Indicador de fortaleza --}}
                <div x-show="newPassword.length > 0">
                    <div class="flex gap-1 mt-1">
                        <div class="h-1.5 flex-1 rounded-full transition-colors" :class="newPassword.length >= 1 ? 'bg-red-400' : 'bg-gray-200'"></div>
                        <div class="h-1.5 flex-1 rounded-full transition-colors" :class="newPassword.length >= 6 ? 'bg-orange-400' : 'bg-gray-200'"></div>
                        <div class="h-1.5 flex-1 rounded-full transition-colors" :class="newPassword.length >= 8 ? 'bg-yellow-400' : 'bg-gray-200'"></div>
                        <div class="h-1.5 flex-1 rounded-full transition-colors" :class="newPassword.length >= 10 && /[A-Z]/.test(newPassword) ? 'bg-green-500' : 'bg-gray-200'"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        <span :class="newPassword.length >= 10 && /[A-Z]/.test(newPassword) ? 'text-green-600' : 'text-gray-400'">
                            <span x-text="newPassword.length < 6 ? 'Muy débil' : newPassword.length < 8 ? 'Débil' : newPassword.length < 10 ? 'Aceptable' : /[A-Z]/.test(newPassword) ? 'Fuerte' : 'Aceptable'"></span>
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
                    <i class="fas" :class="savingPassword ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                    <span x-text="savingPassword ? 'Guardando...' : 'Guardar'"></span>
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
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (response.data.success) {
                    this.passwordSuccess = true;
                    this.newPassword = '';
                    this.confirmPassword = '';
                    setTimeout(() => { this.closePasswordModal(); }, 1500);
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
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
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
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: yStepSize } } }
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
                            backgroundColor: [colors.primary, colors.secondary, colors.accent, colors.warning, colors.danger],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: { legend: { position: 'right', labels: { boxWidth: 12, padding: 16 } } }
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
                            backgroundColor: [colors.primary, colors.secondary, colors.accent, colors.gray],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'right', labels: { boxWidth: 12, padding: 16 } } }
                    }
                });
            }
        }
    }
}
</script>
@endsection