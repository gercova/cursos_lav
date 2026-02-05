@extends('layouts.admin')
@section('title', 'Usuario: ' . $user->names)
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <!-- Avatar -->
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($user->names, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $user->names }}</h1>
                        <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                        @if($user->role === 'admin') bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800
                        @elseif($user->role === 'instructor') bg-gradient-to-br from-purple-100 to-purple-200 text-purple-800
                        @else bg-gradient-to-r from-green-100 to-green-200 text-green-800
                        @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $user->is_active ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800' }}">
                        {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        DNI: {{ $user->dni }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                        Registro: {{ $user->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 lg:mt-0">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="border-b border-gray-200 mb-8">
            <nav class="flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <a href="#info"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Información
                    </div>
                </a>
                @if($user->role === 'student')
                    <a href="#enrollments"
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Inscripciones ({{ $user->enrollments_count ?? 0 }})
                        </div>
                    </a>
                    <a href="#certificates"
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Certificados ({{ $user->certificates_count ?? 0 }})
                        </div>
                    </a>
                    <a href="#exams"
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Exámenes ({{ $user->exam_attempts_count ?? 0 }})
                        </div>
                    </a>
                    <a href="#tracking"
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Seguimiento
                        </div>
                    </a>
                    <a href="#progress"
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Progreso
                        </div>
                    </a>
                @endif
                @if($user->role === 'instructor')
                    <a href="#courses"
                       class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Cursos ({{ $user->courses_count ?? 0 }})
                        </div>
                    </a>
                @endif
                <a href="#activity"
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Actividad
                    </div>
                </a>
            </nav>
        </div>
    </div>

    <!-- Contenido de pestañas -->
    <div class="space-y-8">
        <!-- Información del usuario -->
        <div id="info" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Información del Usuario</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Información personal -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-4">Datos Personales</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">DNI</label>
                                <p class="text-gray-900">{{ $user->dni }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nombres Completos</label>
                                <p class="text-gray-900">{{ $user->names }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Teléfono</label>
                                <p class="text-gray-900">{{ $user->phone ?? 'No registrado' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-4">Información Adicional</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nacionalidad</label>
                                <p class="text-gray-900">{{ $user->nationality ?? 'No registrada' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Profesión</label>
                                <p class="text-gray-900">{{ $user->profession ?? 'No registrada' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Dirección</label>
                                <p class="text-gray-900">{{ $user->address ?? 'No registrada' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Fecha de Registro</label>
                                <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h4 class="font-medium text-gray-900 mb-4">Estadísticas</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-900">{{ $user->enrollments_count ?? 0 }}</div>
                                <div class="text-sm text-blue-700 mt-1">Inscripciones</div>
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-900">{{ $user->courses_count ?? 0 }}</div>
                                <div class="text-sm text-green-700 mt-1">Cursos</div>
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-900">{{ $user->certificates_count ?? 0 }}</div>
                                <div class="text-sm text-purple-700 mt-1">Certificados</div>
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-900">{{ $user->exam_attempts_count ?? 0 }}</div>
                                <div class="text-sm text-orange-700 mt-1">Exámenes</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inscripciones (para estudiantes) -->
        @if($user->role === 'student')
            <div id="enrollments" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Inscripciones a Cursos</h3>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $user->enrollments_count ?? 0 }} cursos
                        </span>
                    </div>

                    @if($user->enrollments->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($user->enrollments as $enrollment)
                                <div class="p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            @if($enrollment->course->image_url ?? false)
                                                <img src="{{ $enrollment->course->image_url }}" alt="{{ $enrollment->course->title ?? 'Curso' }}" class="w-16 h-16 rounded-lg object-cover">
                                            @endif
                                            <div>
                                                <h4 class="font-medium text-gray-900">{{ $enrollment->course->title ?? 'Curso no disponible' }}</h4>
                                                <div class="flex items-center gap-3 mt-2">
                                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                        {{ $enrollment->course->category->name ?? 'Sin categoría' }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        Inscrito: {{ $enrollment->enrolled_at->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                   ($enrollment->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                            <p class="text-sm text-gray-600 mt-2">Progreso: {{ $enrollment->progress }}%</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">El estudiante no tiene inscripciones a cursos</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Certificados (para estudiantes) -->
        @if($user->role === 'student')
            <div id="certificates" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Certificados Obtenidos</h3>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                            {{ $user->certificates_count ?? 0 }} certificados
                        </span>
                    </div>

                    @if($user->certificates->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($user->certificates as $certificate)
                                <div class="border border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="p-3 rounded-lg bg-gradient-to-br from-yellow-100 to-yellow-200">
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $certificate->course->title ?? 'Curso' }}</h4>
                                            <p class="text-sm text-gray-600">Código: {{ $certificate->certificate_code }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">Número de Certificado</label>
                                            <p class="text-sm text-gray-900">{{ $certificate->getFormattedCertificateNumber() }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">Fecha de Emisión</label>
                                            <p class="text-sm text-gray-900">{{ $certificate->issue_date->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs font-medium text-gray-500">Horas Totales</label>
                                            <p class="text-sm text-gray-900">{{ $certificate->total_hours }} horas</p>
                                        </div>
                                        @if($certificate->expiry_date)
                                            <div>
                                                <label class="text-xs font-medium text-gray-500">Válido hasta</label>
                                                <p class="text-sm text-gray-900">{{ $certificate->expiry_date->format('d/m/Y') }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">
                                                Descargas: {{ $certificate->download_count ?? 0 }}
                                            </span>
                                            <a href="{{ $certificate->verification_url ?? '#' }}" 
                                               target="_blank"
                                               class="text-xs text-blue-600 hover:text-blue-800">
                                                Verificar →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">El estudiante no tiene certificados</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Exámenes (para estudiantes) -->
        @if($user->role === 'student')
            <div id="exams" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Exámenes Realizados</h3>
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">
                            {{ $user->exam_attempts_count ?? 0 }} intentos
                        </span>
                    </div>

                    @if($user->examAttempts->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($user->examAttempts->sortByDesc('completed_at') as $attempt)
                                <div class="p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $attempt->exam->title ?? 'Examen' }}</h4>
                                            <div class="flex items-center gap-3 mt-2">
                                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                    Curso: {{ $attempt->exam->course->title ?? 'No disponible' }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    Intento #{{ $attempt->attempt_number }}
                                                </span>
                                                @if($attempt->completed_at)
                                                    <span class="text-xs text-gray-500">
                                                        Completado: {{ $attempt->completed_at->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-bold {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $attempt->score }}/{{ $attempt->total_points }}
                                                </span>
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $attempt->passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $attempt->passed ? 'Aprobado' : 'Reprobado' }}
                                                </span>
                                            </div>
                                            @if($attempt->exam->passing_score)
                                                <p class="text-sm text-gray-600 mt-2">
                                                    Puntuación mínima: {{ $attempt->exam->passing_score }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($attempt->certificate)
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-700">Certificado generado</span>
                                                </div>
                                                <a href="{{ route('admin.certificates.show', $attempt->certificate) }}"
                                                   class="text-sm text-blue-600 hover:text-blue-800">
                                                    Ver certificado →
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">El estudiante no ha realizado exámenes</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Seguimiento del estudiante -->
        @if($user->role === 'student' && isset($trackingData))
            <div id="tracking" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Seguimiento del Estudiante</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">Últimos 30 días</span>
                        </div>
                    </div>

                    <!-- Estadísticas generales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-900">{{ $trackingData['overall_stats']['total_sessions'] ?? 0 }}</div>
                                <div class="text-sm text-blue-700 mt-1">Sesiones</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-900">{{ $trackingData['overall_stats']['active_days'] ?? 0 }}</div>
                                <div class="text-sm text-green-700 mt-1">Días activos</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-900">{{ $trackingData['avg_session_time'] ?? 0 }} min</div>
                                <div class="text-sm text-purple-700 mt-1">Tiempo promedio</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-900">{{ $trackingData['overall_stats']['total_activities'] ?? 0 }}</div>
                                <div class="text-sm text-orange-700 mt-1">Actividades totales</div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Gráfico de sesiones por día -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="font-medium text-gray-900 mb-4">Sesiones por Día</h4>
                            <div class="h-64 relative">
                                <canvas id="sessionsChart"></canvas>
                            </div>
                        </div>

                        <!-- Gráfico de horas activas -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="font-medium text-gray-900 mb-4">Horas Activas del Día</h4>
                            <div class="h-64 relative">
                                <canvas id="activeHoursChart"></canvas>
                            </div>
                        </div>

                        <!-- Gráfico de tipos de actividad -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="font-medium text-gray-900 mb-4">Tipos de Actividad</h4>
                            <div class="h-64 relative">
                                <canvas id="activityTypeChart"></canvas>
                            </div>
                        </div>

                        <!-- Dispositivos utilizados -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="font-medium text-gray-900 mb-4">Dispositivos Utilizados</h4>
                            <div class="h-64 relative">
                                <canvas id="devicesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Hora más activa</h5>
                                    <p class="text-2xl font-bold text-gray-900">{{ $trackingData['overall_stats']['most_active_hour'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 rounded-lg bg-gradient-to-br from-green-100 to-green-200">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Último acceso</h5>
                                    <p class="text-lg font-medium text-gray-900">{{ $trackingData['overall_stats']['last_login'] ?? 'Nunca' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-3 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-900">Sesión promedio</h5>
                                    <p class="text-2xl font-bold text-gray-900">{{ $trackingData['avg_session_time'] ?? 0 }} min</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Progreso de cursos -->
        @if($user->role === 'student' && isset($trackingData))
            <div id="progress" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Progreso de Cursos</h3>
                        <span class="px-3 py-1 bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 rounded-full text-sm font-medium">
                            {{ $trackingData['course_progress']->count() ?? 0 }} cursos
                        </span>
                    </div>

                    @if($trackingData['course_progress']->isNotEmpty())
                        <div class="space-y-6">
                            @foreach($trackingData['course_progress'] as $course)
                                <div class="border border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition duration-200">
                                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 mb-2">{{ $course['course_title'] }}</h4>
                                            <div class="flex items-center gap-4 flex-wrap">
                                                <span class="text-sm text-gray-600">
                                                    Inscrito: {{ $course['enrolled_at'] }}
                                                </span>
                                                @if($course['completed_at'])
                                                    <span class="text-sm text-green-600">
                                                        Completado: {{ $course['completed_at'] }}
                                                    </span>
                                                @endif
                                                <span class="text-sm text-gray-600">
                                                    Duración: {{ $course['duration_days'] }} días
                                                </span>
                                            </div>
                                        </div>

                                        <div class="lg:w-64">
                                            <!-- Barra de progreso -->
                                            <div class="mb-2">
                                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                    <span>Progreso</span>
                                                    <span>{{ $course['progress'] }}%</span>
                                                </div>
                                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-blue-600" 
                                                         style="width: {{ $course['progress'] }}%"></div>
                                                </div>
                                            </div>

                                            <!-- Estado -->
                                            <div class="flex items-center justify-between">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $course['status'] === 'completed' ? 'bg-green-100 text-green-800' :
                                                       ($course['status'] === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($course['status']) }}
                                                </span>
                                                @if($course['progress'] < 100)
                                                    <span class="text-sm text-gray-600">
                                                        {{ 100 - $course['progress'] }}% restante
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">El estudiante no tiene cursos inscritos</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Cursos creados (para instructores) -->
        @if($user->role === 'instructor' && $user->courses->isNotEmpty())
            <div id="courses" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Cursos Creados</h3>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                            {{ $user->courses_count }} cursos
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($user->courses as $course)
                            <div class="p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition duration-200">
                                <div class="flex items-center gap-4">
                                    @if($course->image_url)
                                        <img src="{{ Storage::url($course->image_url) }}"
                                             alt="{{ $course->title }}"
                                             class="w-20 h-20 rounded-lg object-cover">
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $course->title }}</h4>
                                        <div class="flex items-center gap-3 mt-2">
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                {{ $course->category->name ?? 'Sin categoría' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $course->students_count ?? 0 }} estudiantes
                                            </span>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('admin.courses.edit', $course) }}"
                                               class="text-sm text-blue-600 hover:text-blue-800">
                                                Ver curso →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Actividad reciente -->
        <div id="activity" class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <div class="p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Actividad Reciente</h3>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl">
                        <div class="p-3 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900">Usuario registrado</p>
                            <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($user->last_login_at)
                        <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-xl">
                            <div class="p-3 rounded-lg bg-gradient-to-br from-green-100 to-green-200">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-900">Último acceso</p>
                                <p class="text-sm text-gray-500">{{ $user->last_login_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos los gráficos
    initializeCharts();
    
    // Función para inicializar gráficos
    function initializeCharts() {
        // Colores para gráficos
        const chartColors = {
            primary: '#3B82F6',
            secondary: '#10B981',
            accent: '#8B5CF6',
            warning: '#F59E0B',
            danger: '#EF4444',
            gray: '#9CA3AF'
        };

        // 1. Gráfico de sesiones por día
        const sessionsCtx = document.getElementById('sessionsChart')?.getContext('2d');
        if (sessionsCtx) {
            new Chart(sessionsCtx, {
                type: 'bar',
                data: {
                    labels: @json($trackingData['sessions']['labels'] ?? []),
                    datasets: [{
                        label: 'Sesiones',
                        data: @json($trackingData['sessions']['data'] ?? []),
                        backgroundColor: chartColors.primary,
                        borderColor: chartColors.primary,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // 2. Gráfico de horas activas
        const hoursCtx = document.getElementById('activeHoursChart')?.getContext('2d');
        if (hoursCtx) {
            new Chart(hoursCtx, {
                type: 'line',
                data: {
                    labels: @json($trackingData['active_hours']['labels'] ?? []),
                    datasets: [{
                        label: 'Actividad',
                        data: @json($trackingData['active_hours']['data'] ?? []),
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: chartColors.primary,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }

        // 3. Gráfico de tipos de actividad (doughnut)
        const activityCtx = document.getElementById('activityTypeChart')?.getContext('2d');
        if (activityCtx) {
            new Chart(activityCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($trackingData['activity_by_type']['labels'] ?? []),
                    datasets: [{
                        data: @json($trackingData['activity_by_type']['data'] ?? []),
                        backgroundColor: [
                            chartColors.primary,
                            chartColors.secondary,
                            chartColors.accent,
                            chartColors.warning,
                            chartColors.danger
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        // 4. Gráfico de dispositivos (pie)
        const devicesCtx = document.getElementById('devicesChart')?.getContext('2d');
        if (devicesCtx) {
            new Chart(devicesCtx, {
                type: 'pie',
                data: {
                    labels: @json($trackingData['devices_used']['labels'] ?? []),
                    datasets: [{
                        data: @json($trackingData['devices_used']['data'] ?? []),
                        backgroundColor: [
                            chartColors.primary,
                            chartColors.secondary,
                            chartColors.accent,
                            chartColors.gray
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }
    }
    
    // Scroll suave para las pestañas
    document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endsection