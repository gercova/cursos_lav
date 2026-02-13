@extends('layouts.admin')
@section('title', 'Inscribir Usuarios')
@section('content')
<div class="container mx-auto px-4 py-6" x-data="enrollManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('company.list') }}" 
               class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a mis usuarios
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Inscribir usuarios con código</h1>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Colaboradores</p>
                        <p class="text-2xl font-bold text-blue-900 mt-1">{{ $collaborators->count() }}</p>
                    </div>
                    <div class="bg-blue-600 p-3 rounded-xl">
                        <i class="w-6 h-6 text-white fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Cursos con promoción</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ $courses->count() }}</p>
                    </div>
                    <div class="bg-green-600 p-3 rounded-xl">
                        <i class="w-6 h-6 text-white fa-solid fa-book"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-800">Usuarios con código</p>
                        <p class="text-2xl font-bold text-purple-900 mt-1">{{ $collaborators->whereNotNull('code')->count() }}</p>
                    </div>
                    <div class="bg-purple-600 p-3 rounded-xl">
                        <i class="w-6 h-6 text-white fa-solid fa-tag"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-800">Total a matricular</p>
                        <p class="text-2xl font-bold text-amber-900 mt-1">{{ $collaborators->whereNotNull('code')->count() * $courses->count() }}</p>
                    </div>
                    <div class="bg-amber-600 p-3 rounded-xl">
                        <i class="w-6 h-6 text-white fa-solid fa-rocket"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerta de usuarios sin código -->
        @php
            $usersWithoutCode = $collaborators->whereNull('code');
        @endphp
        @if($usersWithoutCode->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">
                            <span class="font-bold">{{ $usersWithoutCode->count() }}</span> usuarios no tienen código de promoción asignado.
                            <a href="{{ route('company.list') }}" class="underline font-semibold hover:text-yellow-900">
                                Asignar códigos desde la gestión de usuarios
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Alerta de cursos sin promoción -->
        @php
            $coursesWithoutPromotion = $courses->count();
        @endphp
        @if($coursesWithoutPromotion == 0)
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            No hay cursos con precio de promoción activo.
                            <a href="{{ route('admin.courses.index') }}" class="underline font-semibold hover:text-red-900">
                                Configurar promociones en los cursos
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Panel principal -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Tabs -->
        <div class="border-b border-gray-200 bg-gray-50">
            <nav class="flex -mb-px overflow-x-auto">
                
                <button @click="activeTab = 'single'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'single', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'single' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm focus:outline-none transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-user mr-2"></i>
                    Matrícula Individual
                </button>

                <button @click="activeTab = 'bulk'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'bulk', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'bulk' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm focus:outline-none transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-users mr-2"></i>
                    Matrícula Masiva
                </button>
                
                <button @click="activeTab = 'superBulk'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'superBulk', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'superBulk' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm focus:outline-none transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-rocket mr-2"></i>
                    Matrícula Expres (Todos en Todos)
                </button>
                
                <button @click="activeTab = 'history'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'history', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'history' }" class="py-4 px-6 text-center border-b-2 font-medium text-sm focus:outline-none transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-history mr-2"></i>
                    Historial Reciente
                </button>

            </nav>
        </div>

        <!-- Contenido de los tabs -->
        <div class="p-6">
            <!-- Tab: Matrícula Individual -->
            <div x-show="activeTab === 'single'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                <div class="max-w-3xl mx-auto">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Matricular usuario individualmente</h3>
                    
                    <form @submit.prevent="enrollSingle()" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar Usuario * <small>{{ $collaborators->count() }} total de usuarios</small>
                            </label>
                            <select x-model="form.user_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">-- Selecciona un usuario --</option>
                                @foreach($collaborators as $collaborator)
                                    <option value="{{ $collaborator->id }}">
                                        {{ $collaborator->names }} - {{ $collaborator->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar Curso *
                            </label>
                            <select x-model="form.course_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">-- Selecciona un curso --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">
                                        {{ $course->title }} - 
                                        @if($course->promotion_price)
                                            S/ {{ number_format($course->promotion_price, 2) }}
                                        @else
                                            S/ {{ number_format($course->price, 2) }}
                                        @endif
                                        - Instructor: {{ $course->instructor->names ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Información de la matrícula</p>
                                    <p class="text-xs text-blue-700 mt-1">
                                        • Se utilizará el código de promoción del usuario para aplicar el descuento.<br>
                                        • El usuario recibirá acceso inmediato al curso seleccionado.<br>
                                        • Solo se permite una matrícula por usuario por curso.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    :disabled="loading"
                                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-user-plus" x-show="!loading"></i>
                                <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="loading ? 'Matriculando...' : 'Matricular Usuario'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab: Matrícula Masiva (Múltiples usuarios en un curso) -->
            <div x-show="activeTab === 'bulk'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                <div class="max-w-4xl mx-auto">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Matricular múltiples usuarios en un curso</h3>
                    
                    <form @submit.prevent="enrollBulk()" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar Curso *
                            </label>
                            <select x-model="bulkForm.course_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">-- Selecciona un curso --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">
                                        {{ $course->title }} - S/ {{ number_format($course->promotion_price ?? $course->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar Usuarios *
                            </label>
                            <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                                @forelse($collaborators as $collaborator)
                                    <div class="flex items-center py-2 hover:bg-gray-50 px-2 rounded">
                                        <input type="checkbox" 
                                            x-model="bulkForm.user_ids" 
                                            value="{{ $collaborator->id }}" 
                                            id="user_{{ $collaborator->id }}"
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        >
                                        <label for="user_{{ $collaborator->id }}" class="ml-3 flex-1 text-sm text-gray-700">
                                            <span class="font-medium">{{ $collaborator->names }}</span>
                                        </label>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center py-4">
                                        No hay usuarios con código de promoción disponible.
                                        <a href="{{ route('company.list') }}" class="text-blue-600 underline">Asignar códigos</a>
                                    </p>
                                @endforelse
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-xs text-gray-500">
                                    <span x-text="bulkForm.user_ids.length"></span> usuario(s) seleccionado(s)
                                </p>
                                <button type="button" @click="selectAllUsers()" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    Seleccionar todos
                                </button>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-rocket text-green-600 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-green-800">Matriculación masiva</p>
                                    <p class="text-xs text-green-700 mt-1">
                                        • Se matricularán todos los usuarios seleccionados en el mismo curso.<br>
                                        • Solo se matricularán usuarios que tengan código de promoción activo.<br>
                                        • Los usuarios ya matriculados serán omitidos automáticamente.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    :disabled="bulkLoading || bulkForm.user_ids.length === 0"
                                    class="flex items-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-users" x-show="!bulkLoading"></i>
                                <svg x-show="bulkLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="bulkLoading ? 'Matriculando...' : 'Matricular Seleccionados'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab: MEGA MATRÍCULA (Todos los usuarios en todos los cursos) -->
            <div x-show="activeTab === 'superBulk'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-r from-amber-400 to-amber-600 text-white mb-4">
                            <i class="fas fa-rocket text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Matrícula Express</h3>
                        <p class="text-gray-600">Matricula a <span class="font-bold text-amber-600">{{ $collaborators->count() }}</span> usuarios en <span class="font-bold text-amber-600">{{ $courses->count() }}</span> cursos de una sola vez</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border-2 border-amber-200 mb-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                                    <i class="fas fa-crown text-amber-600 text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-amber-800 text-lg mb-2">Resumen de la operación</h4>
                                <ul class="space-y-2 text-sm text-amber-700">
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        <span><span class="font-bold">{{ $collaborators->count() }}</span> usuarios con código de promoción</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-check-circle"></i>
                                        <span><span class="font-bold">{{ $courses->count() }}</span> cursos con precio de promoción</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class="fas fa-calculator"></i>
                                        <span><span class="font-bold">{{ $collaborators->count() * $courses->count() }}</span> matrículas a realizar</span>
                                    </li>
                                    <li class="flex items-center gap-2 text-amber-800 font-semibold mt-2">
                                        <i class="fas fa-clock"></i>
                                        <span>Tiempo estimado: {{ ceil(($collaborators->count() * $courses->count()) / 10) }} segundos</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 border border-gray-200 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">Vista previa de usuarios a matricular</h4>
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">
                                {{ $collaborators->count() }} usuarios
                            </span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-48 overflow-y-auto p-2">
                            @forelse($collaborators->take(10) as $collaborator)
                                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr($collaborator->names, 0, 1)) }}
                                    </div>
                                    <div class="truncate">
                                        <p class="text-xs font-medium text-gray-700 truncate">{{ $collaborator->names }}</p>
                                        <p class="text-xs text-gray-500">{{ $collaborator->code }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 col-span-3 text-center py-4">No hay usuarios con código</p>
                            @endforelse
                            @if($collaborators->count() > 10)
                                <div class="flex items-center justify-center p-2 bg-gray-100 rounded-lg">
                                    <span class="text-xs font-medium text-gray-600">
                                        y {{ $collaborators->whereNotNull('code')->count() - 10 }} más...
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-6 border border-gray-200 mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-gray-900">Vista previa de cursos</h4>
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">
                                {{ $courses->count() }} cursos
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-2">
                            @forelse($courses as $course)
                                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                        <i class="fas fa-book text-xs"></i>
                                    </div>
                                    <div class="flex-1 truncate">
                                        <p class="text-xs font-medium text-gray-700 truncate">{{ $course->title }}</p>
                                        <p class="text-xs text-gray-500">S/ {{ number_format($course->promotion_price ?? $course->price, 2) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 col-span-2 text-center py-4">No hay cursos disponibles</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <button type="button" @click="confirmSuperBulkEnroll()" :disabled="superBulkLoading || {{ $collaborators->whereNotNull('code')->count() }} === 0 || {{ $courses->count() }} === 0" class="flex items-center gap-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold py-4 px-10 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 text-lg">
                            <i class="fas fa-rocket" x-show="!superBulkLoading"></i>
                            <svg x-show="superBulkLoading" class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="superBulkLoading ? 'Procesando Matrícula Expres...' : 'EJECUTAR MATRÍCULA EXPRES'"></span>
                        </button>
                        
                        <button type="button" @click="simulateSuperBulkEnroll()" class="flex items-center gap-2 bg-white border-2 border-amber-300 text-amber-700 hover:bg-amber-50 font-semibold py-4 px-6 rounded-xl shadow transition-all duration-200">
                            <i class="fas fa-eye"></i>
                            Simular operación
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 text-center mt-4">
                        <i class="fas fa-info-circle"></i> 
                        Esta operación matriculará a TODOS los usuarios con código en TODOS los cursos disponibles.
                        Los usuarios que ya estén matriculados en algún curso serán omitidos automáticamente.
                    </p>
                </div>
            </div>

            <!-- Tab: Historial Reciente -->
            <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
                <div x-data="recentEnrollments()" x-init="loadEnrollments()">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Matriculaciones recientes</h3>
                        <button @click="loadEnrollments()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                    
                    <div x-show="loading" class="py-8 text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600"></div>
                        <p class="text-gray-600 mt-2">Cargando matriculaciones...</p>
                    </div>
                    
                    <div x-show="!loading && enrollments.length === 0" class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                            <i class="fas fa-book-open text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-600">No hay matriculaciones recientes</p>
                    </div>
                    
                    <div x-show="!loading && enrollments.length > 0" class="space-y-3">
                        <template x-for="enrollment in enrollments" :key="enrollment.id">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user-graduate text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900" x-text="enrollment.user?.names || 'N/A'"></p>
                                        <p class="text-sm text-gray-600" x-text="enrollment.course?.title || 'N/A'"></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500" x-text="formatDate(enrollment.created_at)"></p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para Mega Matrícula -->
    <div x-show="showSuperBulkConfirmModal" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm"
        @click.self="showSuperBulkConfirmModal = false"
    >
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
                <div class="px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-600">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar Mega Matrícula</h3>
                        <button @click="showSuperBulkConfirmModal = false" class="text-white/80 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-rocket text-amber-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-900">¿Estás completamente seguro?</p>
                            <p class="text-sm text-gray-600 mt-1">
                                Vas a matricular <span class="font-bold text-amber-600">{{ $collaborators->whereNotNull('code')->count() }}</span> usuarios 
                                en <span class="font-bold text-amber-600">{{ $courses->count() }}</span> cursos.
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-amber-50 rounded-xl p-4 mb-4">
                        <p class="text-sm text-amber-800">
                            <span class="font-bold">Total de matrículas a procesar:</span><br>
                            <span class="text-2xl font-bold text-amber-600">{{ $collaborators->whereNotNull('code')->count() * $courses->count() }}</span> inscripciones
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Esta operación podría tomar varios segundos. No cierres la ventana hasta que termine el proceso.
                        </p>
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button @click="showSuperBulkConfirmModal = false" 
                                class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Cancelar
                        </button>
                        <button @click="executeSuperBulkEnroll(); showSuperBulkConfirmModal = false" 
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-200">
                            Sí, ejecutar Mega Matrícula
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de resultados de matriculación masiva -->
    <div x-show="showBulkResultsModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm"
         @click.self="showBulkResultsModal = false">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900" x-text="bulkResults.title || 'Resultados de Matriculación'"></h3>
                        <button @click="showBulkResultsModal = false" class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="mb-4">
                        <div class="flex items-center gap-4 p-4 bg-green-50 rounded-lg">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-green-800" x-text="bulkResults.message"></p>
                                <p class="text-sm text-green-700">
                                    <span x-text="bulkResults.success?.length || 0"></span> exitosos, 
                                    <span x-text="bulkResults.failed?.length || 0"></span> fallidos
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="bulkResults.summary" class="mt-4 mb-4 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-2">Resumen:</h4>
                        <p class="text-sm text-blue-800" x-html="bulkResults.summary"></p>
                    </div>
                    
                    <div x-show="bulkResults.failed?.length > 0" class="mt-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Errores:</h4>
                        <div class="bg-red-50 rounded-lg p-4 max-h-48 overflow-y-auto">
                            <template x-for="failed in bulkResults.failed" :key="failed.user + failed.course">
                                <div class="text-sm text-red-700 mb-2 pb-2 border-b border-red-100 last:border-0">
                                    <span class="font-medium" x-text="failed.user || 'Usuario'"></span>
                                    <span x-show="failed.course"> - <span class="text-xs" x-text="failed.course"></span></span>
                                    <span class="block text-xs mt-1" x-text="failed.reason"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="bulkResults.warning" class="mt-4 p-3 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-yellow-800" x-text="bulkResults.warning"></p>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <button @click="showBulkResultsModal = false" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function enrollManager() {
        return {
            activeTab: 'single',
            loading: false,
            bulkLoading: false,
            superBulkLoading: false,
            showBulkResultsModal: false,
            showSuperBulkConfirmModal: false,
            bulkResults: {
                success: [],
                failed: [],
                message: '',
                title: '',
                summary: '',
                warning: ''
            },
            form: {
                user_id: '',
                course_id: ''
            },
            bulkForm: {
                user_ids: [],
                course_id: ''
            },
            
            init() {
                // Inicializar
            },
            
            selectAllUsers() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"][id^="user_"]');
                this.bulkForm.user_ids = [];
                checkboxes.forEach(checkbox => {
                    if (!checkbox.disabled) {
                        this.bulkForm.user_ids.push(checkbox.value);
                    }
                });
            },
            
            async enrollSingle() {
                if (!this.form.user_id || !this.form.course_id) {
                    showNotification('Debes seleccionar un usuario y un curso', 'error');
                    return;
                }
                
                this.loading = true;
                
                try {
                    const response = await axios.post('{{ route("company.enroll.with-code") }}', this.form, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.data.success) {
                        showNotification(response.data.message, 'success');
                        this.form.user_id = '';
                        this.form.course_id = '';
                        
                        // Recargar historial si estamos en ese tab
                        if (this.activeTab === 'history') {
                            const historyComponent = document.querySelector('[x-data="recentEnrollments()"]')?.__x;
                            if (historyComponent) {
                                historyComponent.loadEnrollments();
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error al matricular:', error);
                    const message = error.response?.data?.message || 'Error al matricular al usuario';
                    showNotification(message, 'error');
                } finally {
                    this.loading = false;
                }
            },
            
            async enrollBulk() {
                if (this.bulkForm.user_ids.length === 0) {
                    showNotification('Debes seleccionar al menos un usuario', 'error');
                    return;
                }
                
                if (!this.bulkForm.course_id) {
                    showNotification('Debes seleccionar un curso', 'error');
                    return;
                }
                
                if (!confirm(`¿Estás seguro de matricular ${this.bulkForm.user_ids.length} usuarios en este curso?`)) {
                    return;
                }
                
                this.bulkLoading = true;
                
                try {
                    const response = await axios.post('{{ route("company.enroll.bulk") }}', this.bulkForm, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.data.success) {
                        this.bulkResults = {
                            ...response.data.data,
                            title: 'Matriculación Masiva'
                        };
                        this.showBulkResultsModal = true;
                        
                        // Limpiar selección
                        this.bulkForm.user_ids = [];
                        this.bulkForm.course_id = '';
                        
                        showNotification(response.data.message, 'success');
                        
                        // Recargar historial
                        const historyComponent = document.querySelector('[x-data="recentEnrollments()"]')?.__x;
                        if (historyComponent) {
                            historyComponent.loadEnrollments();
                        }
                    }
                } catch (error) {
                    console.error('Error en matriculación masiva:', error);
                    const message = error.response?.data?.message || 'Error en la matriculación masiva';
                    showNotification(message, 'error');
                } finally {
                    this.bulkLoading = false;
                }
            },
            
            confirmSuperBulkEnroll() {
                const usersCount = {{ $collaborators->whereNotNull('code')->count() }};
                const coursesCount = {{ $courses->count() }};
                
                if (usersCount === 0) {
                    showNotification('No hay usuarios con código de promoción para matricular', 'error');
                    return;
                }
                
                if (coursesCount === 0) {
                    showNotification('No hay cursos con precio de promoción disponibles', 'error');
                    return;
                }
                
                this.showSuperBulkConfirmModal = true;
            },
            
            async executeSuperBulkEnroll() {
                this.superBulkLoading = true;
                
                try {
                    const response = await axios.post('{{ route("company.enroll.super-bulk") }}', {}, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.data.success) {
                        this.bulkResults = {
                            ...response.data.data,
                            title: '🚀 Mega Matrícula Express'
                        };
                        this.showBulkResultsModal = true;
                        
                        showNotification(response.data.message, 'success');
                        
                        // Recargar historial
                        const historyComponent = document.querySelector('[x-data="recentEnrollments()"]')?.__x;
                        if (historyComponent) {
                            historyComponent.loadEnrollments();
                        }
                    }
                } catch (error) {
                    console.error('Error en Mega Matrícula:', error);
                    const message = error.response?.data?.message || 'Error en la Mega Matrícula';
                    showNotification(message, 'error');
                } finally {
                    this.superBulkLoading = false;
                }
            },
            
            simulateSuperBulkEnroll() {
                const usersCount = {{ $collaborators->whereNotNull('code')->count() }};
                const coursesCount = {{ $courses->count() }};
                const totalEnrollments = usersCount * coursesCount;
                
                let simulatedSuccess = 0;
                let simulatedFailed = 0;
                const simulatedFailedList = [];
                
                // Simular que algunos ya están matriculados (10%)
                const existingEnrollments = Math.floor(totalEnrollments * 0.1);
                simulatedSuccess = totalEnrollments - existingEnrollments;
                simulatedFailed = existingEnrollments;
                
                for (let i = 0; i < 5; i++) {
                    simulatedFailedList.push({
                        user: 'Usuario ' + (i + 1),
                        course: 'Curso de ejemplo ' + (i + 1),
                        reason: 'Ya estaba matriculado'
                    });
                }
                
                this.bulkResults = {
                    success: new Array(simulatedSuccess),
                    failed: simulatedFailedList,
                    message: `Simulación: ${simulatedSuccess} matrículas exitosas, ${simulatedFailed} omitidas`,
                    title: 'Simulación de Matrícula Expres',
                    summary: `Se procesarían <span class="font-bold">${totalEnrollments}</span> inscripciones.<br>
                        <span class="text-green-600">${simulatedSuccess} nuevas matrículas</span><br>
                        <span class="text-amber-600">${simulatedFailed} usuarios ya matriculados</span>`,
                    warning: 'Esta es solo una simulación. No se realizaron cambios reales.'
                };
                this.showBulkResultsModal = true;
            }
        };
    }

    function recentEnrollments() {
        return {
            enrollments: [],
            loading: false,
            
            async loadEnrollments() {
                this.loading = true;
                
                try {
                    const response = await axios.get('{{ route("company.enroll.recent") }}');
                    
                    if (response.data.success) {
                        this.enrollments = response.data.data;
                    }
                } catch (error) {
                    console.error('Error al cargar matriculaciones:', error);
                } finally {
                    this.loading = false;
                }
            },
            
            formatDate(date) {
                if (!date) return 'N/A';
                return new Date(date).toLocaleDateString('es-PE', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        };
    }

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
        }, 5000);
    }
</script>
@endsection