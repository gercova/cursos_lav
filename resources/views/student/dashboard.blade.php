@extends('layouts.student')
@section('title', 'Dashboard - Estudiante')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Bienvenida y resumen rápido -->
    <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm mr-3">
                        <i class="fas fa-user-graduate mr-2"></i> Estudiante
                    </span>
                    <span class="text-sm text-gray-600">
                        <i class="far fa-calendar mr-1"></i> {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Bienvenido de nuevo, {{ auth()->user()->names }}! 👋</h1>
                <p class="text-gray-600 mb-6">Continúa tu aprendizaje y alcanza tus metas</p>
                
                <!-- Stats en línea -->
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center border border-blue-100">
                            <i class="fas fa-book-open text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Cursos Activos</p>
                            <p class="text-xl font-bold text-gray-900" id="stats-courses">0</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center border border-green-100">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Progreso Global</p>
                            <p class="text-xl font-bold text-gray-900" id="global-progress-text">0%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 md:mt-0 md:ml-6">
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="fas fa-trophy text-white text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Próximo objetivo</p>
                            <div class="flex items-center mt-2">
                                <div class="w-40 bg-gray-200 rounded-full h-2 mr-3">
                                    <div id="global-progress-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full progress-bar" style="width: 0%"></div>
                                </div>
                                <button class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                    Ver metas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna izquierda - Cursos y Actividad -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Cursos Inscritos -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Mis Cursos Inscritos</h2>
                        <p class="text-sm text-gray-600 mt-1">Continúa tu aprendizaje donde lo dejaste</p>
                    </div>
                    <a href="{{ route('student.my-courses') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-book-open mr-2"></i>
                        Ver todos
                    </a>
                </div>
                <div class="p-6">
                    @if(count($coursesData) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($coursesData->take(4) as $course)
                                <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-200 card-hover">
                                    <div class="p-5">
                                        <!-- Encabezado del curso -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $course['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ $course['status'] === 'completed' ? 'Completado' : 'En progreso' }}
                                                    </span>
                                                    <span class="ml-2 text-xs text-gray-500">
                                                        <i class="far fa-clock mr-1"></i>{{ $course['duration'] }}
                                                    </span>
                                                </div>
                                                <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $course['title'] }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">{{ $course['category'] }}</p>
                                            </div>
                                            <div class="flex-shrink-0 ml-3">
                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center border border-blue-200">
                                                    <i class="fas fa-book text-blue-600"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Progreso -->
                                        <div class="mb-5">
                                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                                <span class="font-medium">Tu progreso</span>
                                                <span class="font-bold text-gray-900">{{ $course['progress'] }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="h-2.5 rounded-full progress-bar {{ $course['status'] === 'completed' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }}" style="width: {{ $course['progress'] }}%"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-500 mt-2">
                                                <span>{{ $course['completed_lessons'] }}/{{ $course['total_lessons'] }} lecciones</span>
                                                <span>{{ $course['modules'] }} módulos</span>
                                            </div>
                                        </div>

                                        <!-- Acciones -->
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <div class="text-sm text-gray-600">
                                                <i class="far fa-calendar mr-1"></i>
                                                Inscrito: {{ $course['enrolled_date'] }}
                                            </div>
                                            <a href="{{ $course['continue_url'] }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow">
                                                <i class="fas fa-play mr-2"></i>
                                                Continuar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @if(count($coursesData) > 4)
                        <div class="mt-6 text-center">
                            <a href="{{ route('student.my-courses') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                <span>Ver {{ count($coursesData) - 4 }} cursos más</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    @endif
                    
                    @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-book-open text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Aún no estás inscrito en ningún curso</h3>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">Descubre nuestra variedad de cursos y comienza tu viaje de aprendizaje hoy mismo.</p>
                            <a href="{{ route('cursos') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                                <i class="fas fa-search mr-3"></i>
                                Explorar cursos disponibles
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-900">Actividad Reciente</h2>
                </div>
                <div class="p-6">
                    <div class="max-h-[400px] overflow-y-auto pr-2 custom-scrollbar space-y-4">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 border border-gray-100">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-10 h-10 bg-gradient-to-br from-{{ $activity->color ?? 'blue' }}-100 to-{{ $activity->color ?? 'blue' }}-50 rounded-xl flex items-center justify-center border border-{{ $activity->color ?? 'blue' }}-200">
                                        <i class="fas fa-{{ $activity->icon ?? 'circle' }} text-{{ $activity->color ?? 'blue' }}-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-xs text-gray-500">
                                            <i class="far fa-clock mr-1"></i>{{ $activity->formatted_date }}
                                        </p>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-{{ $activity->color ?? 'blue' }}-100 text-{{ $activity->color ?? 'blue' }}-800">
                                            {{ __('actividades.' . $activity->type ?? $activity->type) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center border border-gray-200">
                                    <i class="fas fa-history text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-900 font-medium mb-2">No hay actividad reciente</p>
                                <p class="text-sm text-gray-600">Comienza a interactuar con los cursos para ver actividad.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha - Logros, Exámenes y Certificados -->
        <div class="space-y-8">
            <!-- Exámenes Pendientes -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-red-100 to-red-50 rounded-xl flex items-center justify-center border border-red-200">
                            <i class="fas fa-file-alt text-red-600"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Exámenes Pendientes</h2>
                            <p class="text-sm text-gray-600">Próximas evaluaciones</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="max-h-[320px] overflow-y-auto pr-2 custom-scrollbar space-y-4">
                        @forelse($upcomingExams as $exam)
                            <a href="{{ route('student.exams') }}" class="block bg-gradient-to-r from-red-50 to-white hover:from-red-100 hover:to-red-50 border border-red-200 rounded-xl p-4 transition-all duration-200 card-hover">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-100 to-red-50 rounded-lg flex flex-col items-center justify-center border border-red-200">
                                        <i class="fas fa-clipboard-list text-red-600 text-xl"></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $exam->title }}</h4>
                                            <span class="ml-2 px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                                Pendiente
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1">{{ $exam->course->title ?? 'Curso general' }}</p>
                                        <div class="flex items-center mt-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                                <i class="fas fa-clock mr-1 text-xs"></i>
                                                {{ $exam->duration }} min
                                            </span>
                                            @if($exam->passing_score)
                                            <span class="ml-2 inline-flex items-center px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                                <i class="fas fa-bullseye mr-1 text-xs"></i>
                                                Aprobación: {{ $exam->passing_score }}%
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-red-100 to-red-50 rounded-2xl flex items-center justify-center border border-red-200">
                                    <i class="fas fa-file-alt text-2xl text-red-400"></i>
                                </div>
                                <p class="text-gray-900 font-medium mb-2">No hay exámenes pendientes</p>
                                <p class="text-sm text-gray-600">¡Buen trabajo! Estás al día con tus evaluaciones.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Certificados Obtenidos -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-xl flex items-center justify-center border border-yellow-200">
                            <i class="fas fa-certificate text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-900">Certificados Obtenidos</h2>
                            <p class="text-sm text-gray-600">Tus logros certificados</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="max-h-[320px] overflow-y-auto pr-2 custom-scrollbar space-y-4">
                        @forelse($certificates as $cert)
                            <div class="bg-gradient-to-r from-yellow-50 to-white border border-yellow-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-lg flex items-center justify-center border border-yellow-200">
                                        <i class="fas fa-certificate text-yellow-600"></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">Certificado de Finalización</h4>
                                        <p class="text-xs text-gray-600 mt-1">{{ $cert->course->title ?? 'Curso completado' }}</p>
                                        <div class="flex justify-between items-center mt-3">
                                            <span class="text-xs text-yellow-700 font-medium">
                                                <i class="fas fa-award mr-1"></i>
                                                Obtenido: {{ $cert->issue_date->format('d/m/Y') }}
                                            </span>
                                            <a href="{{ $cert->verification_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                Ver <i class="fas fa-external-link-alt ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-2xl flex items-center justify-center border border-yellow-200">
                                    <i class="fas fa-certificate text-2xl text-yellow-400"></i>
                                </div>
                                <p class="text-gray-900 font-medium mb-2">No hay certificados</p>
                                <p class="text-sm text-gray-600">Completa cursos y exámenes para obtener tus primeros certificados.</p>
                            </div>
                        @endforelse
                    </div>
                        
                    @if($certificates->count() >= 5)
                        <div class="text-center pt-4 mt-2 border-t border-yellow-100">
                            <a href="{{ route('student.certificates') }}" class="inline-flex items-center text-yellow-700 hover:text-yellow-800 font-medium text-sm">
                                <span>Ver todos mis certificados</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                    <h2 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('cursos') }}" class="bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 text-blue-700 rounded-xl p-4 flex flex-col items-center justify-center transition-all duration-200 border border-blue-200 card-hover group">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow duration-200">
                                <i class="fas fa-search text-white text-lg"></i>
                            </div>
                            <span class="text-sm font-semibold">Buscar Cursos</span>
                            <span class="text-xs text-blue-600 mt-1">Nuevas oportunidades</span>
                        </a>
                        <a href="{{ route('student.exams') }}" class="bg-gradient-to-br from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 text-red-700 rounded-xl p-4 flex flex-col items-center justify-center transition-all duration-200 border border-red-200 card-hover group">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow duration-200">
                                <i class="fas fa-file-alt text-white text-lg"></i>
                            </div>
                            <span class="text-sm font-semibold">Exámenes</span>
                            <span class="text-xs text-red-600 mt-1">Evaluaciones</span>
                        </a>
                        <a href="{{ route('student.certificates') }}" class="bg-gradient-to-br from-yellow-50 to-yellow-100 hover:from-yellow-100 hover:to-yellow-200 text-yellow-700 rounded-xl p-4 flex flex-col items-center justify-center transition-all duration-200 border border-yellow-200 card-hover group">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow duration-200">
                                <i class="fas fa-certificate text-white text-lg"></i>
                            </div>
                            <span class="text-sm font-semibold">Certificados</span>
                            <span class="text-xs text-yellow-600 mt-1">Tus logros</span>
                        </a>
                        <a href="{{ route('student.profile') }}" class="bg-gradient-to-br from-gray-50 to-gray-100 hover:from-gray-100 hover:to-gray-200 text-gray-700 rounded-xl p-4 flex flex-col items-center justify-center transition-all duration-200 border border-gray-200 card-hover group">
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mb-3 group-hover:shadow-md transition-shadow duration-200">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <span class="text-sm font-semibold">Mi Perfil</span>
                            <span class="text-xs text-gray-600 mt-1">Configuración</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metas del Día -->
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl p-6 border border-purple-200">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-bullseye text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900">Metas del Día</h3>
                        <p class="text-sm text-gray-600">Completa tus objetivos diarios</p>
                    </div>
                </div>
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Lecciones completadas</span>
                            <span id="daily-lessons">0/3</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="daily-lessons-bar" class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                            <span>Minutos de estudio</span>
                            <span id="daily-minutes">0/60 min</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div id="daily-minutes-bar" class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-purple-200">
                        <button onclick="updateDailyGoals()" class="w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white py-2.5 rounded-xl hover:from-purple-600 hover:to-indigo-700 transition-all duration-200 font-medium text-sm shadow-sm hover:shadow">
                            <i class="fas fa-check-circle mr-2"></i>
                            Marcar como completado
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalles del Curso -->
<div id="course-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 id="modal-course-title" class="text-xl font-bold text-gray-900"></h3>
                    <p id="modal-course-category" class="text-sm text-gray-600 mt-1"></p>
                </div>
                <button onclick="closeCourseModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="modal-course-content" class="space-y-4">
                <!-- El contenido se llenará dinámicamente -->
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                <a id="modal-course-link" href="#" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium shadow-sm hover:shadow">
                    <i class="fas fa-play mr-3"></i>
                    Continuar con el curso
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Función para formatear fechas
    function formatDate(date) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('es-ES', options);
    }

    // Actualizar fecha actual
    document.getElementById('current-date').textContent = formatDate(new Date());

    // Funciones para el modal de curso
    function openCourseModal(courseId) {
        // Aquí puedes cargar los detalles específicos del curso si es necesario
        const modal = document.getElementById('course-modal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCourseModal() {
        const modal = document.getElementById('course-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Cargar exámenes pendientes
    async function loadUpcomingExams() {
        try {
            const response  = await axios.get('/api/student/dashboard-exams');
            const exams     = response.data;
            const container = document.getElementById('upcoming-exams');

            if (exams && exams.length > 0) {
                container.innerHTML = exams.map(exam => `
                    <a href="${exam.link || '#'}" class="block bg-gradient-to-r from-red-50 to-white hover:from-red-100 hover:to-red-50 border border-red-200 rounded-xl p-4 transition-all duration-200 card-hover">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-100 to-red-50 rounded-lg flex flex-col items-center justify-center border border-red-200">
                                <span class="text-xs font-bold text-red-700">${exam.day || 'Próximo'}</span>
                                <span class="text-sm font-bold text-red-700">${exam.date || ''}</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-semibold text-gray-900">${exam.title}</h4>
                                    <span class="ml-2 px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                        ${exam.time || 'Próximo'}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">${exam.course || 'Curso'}</p>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                        <i class="fas fa-clock mr-1 text-xs"></i>
                                        ${exam.duration || 'Tiempo pendiente'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                `).join('');
                
                // Actualizar contador de exámenes en stats
                document.getElementById('stats-exams').textContent = exams.length;
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-red-100 to-red-50 rounded-2xl flex items-center justify-center border border-red-200">
                            <i class="fas fa-file-alt text-2xl text-red-400"></i>
                        </div>
                        <p class="text-gray-900 font-medium mb-2">No hay exámenes pendientes</p>
                        <p class="text-sm text-gray-600">¡Buen trabajo! Estás al día con tus evaluaciones.</p>
                    </div>
                `;
                document.getElementById('stats-exams').textContent = '0';
            }
        } catch (error) {
            console.error('Error loading upcoming exams:', error);
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Error cargando exámenes</p>
                </div>
            `;
        }
    }

    // Cargar certificados
    async function loadCertificates() {
        try {
            const response = await axios.get('/api/student/certificates');
            const certificates = response.data;
            const container = document.getElementById('certificates-list');

            if (certificates && certificates.length > 0) {
                container.innerHTML = certificates.slice(0, 3).map(cert => `
                    <div class="bg-gradient-to-r from-yellow-50 to-white border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-lg flex items-center justify-center border border-yellow-200">
                                <i class="fas fa-certificate text-yellow-600"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">${cert.title || 'Certificado'}</h4>
                                <p class="text-xs text-gray-600 mt-1">${cert.course || 'Curso completado'}</p>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="text-xs text-yellow-700 font-medium">
                                        <i class="fas fa-award mr-1"></i>
                                        Obtenido: ${cert.date || 'Fecha'}
                                    </span>
                                    <a href="${cert.link || '#'}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Ver <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join();
                
                // Si hay más de 3 certificados, mostrar enlace
                if (certificates.length > 3) {
                    container.innerHTML += `
                        <div class="text-center pt-4 border-t border-yellow-100">
                            <a href="{{ route('student.certificates') }}" class="inline-flex items-center text-yellow-700 hover:text-yellow-800 font-medium text-sm">
                                <span>Ver ${certificates.length - 3} certificados más</span>
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    `;
                }
                
                // Actualizar contador de certificados en stats
                document.getElementById('stats-certificates').textContent = certificates.length;
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-yellow-100 to-yellow-50 rounded-2xl flex items-center justify-center border border-yellow-200">
                            <i class="fas fa-certificate text-2xl text-yellow-400"></i>
                        </div>
                        <p class="text-gray-900 font-medium mb-2">Aún no tienes certificados</p>
                        <p class="text-sm text-gray-600">Completa cursos y exámenes para obtener certificados.</p>
                    </div>
                `;
                document.getElementById('stats-certificates').textContent = '0';
            }
        } catch (error) {
            console.error('Error loading certificates:', error);
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Error cargando certificados</p>
                </div>
            `;
        }
    }

    // Cargar actividad reciente
    async function loadRecentActivity() {
        try {
            const response = await axios.get('/api/student/recent-activity');
            const activities = response.data;
            const container = document.getElementById('recent-activity');

            if (activities && activities.length > 0) {
                container.innerHTML = activities.map(activity => `
                    <div class="flex items-start p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 border border-gray-100">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 bg-gradient-to-br from-${activity.color || 'blue'}-100 to-${activity.color || 'blue'}-50 rounded-xl flex items-center justify-center border border-${activity.color || 'blue'}-200">
                                <i class="fas fa-${activity.icon || 'circle'} text-${activity.color || 'blue'}-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">${activity.description}</p>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>${activity.time}
                                </p>
                                ${activity.badge ? `
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full ${activity.badge_color || 'bg-blue-100 text-blue-800'}">
                                        ${activity.badge}
                                    </span>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center border border-gray-200">
                            <i class="fas fa-history text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-900 font-medium mb-2">No hay actividad reciente</p>
                        <p class="text-sm text-gray-600">Comienza a interactuar con los cursos para ver actividad.</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading recent activity:', error);
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500">Error cargando actividad</p>
                </div>
            `;
        }
    }

    // Cargar estadísticas del dashboard
    async function loadStats() {
        try {
            const response = await axios.get('/api/student/dashboard-stats');
            const stats = response.data;

            // Actualizar valores en las cards con animación
            animateCounter('stats-courses', stats.activeCourses || 0);
            animateCounter('stats-exams', stats.pendingExams || 0);
            animateCounter('stats-certificates', stats.certificatesCount || 0);
            animateCounter('stats-hours', stats.studyHours || 0);

            // Progreso global
            const progressBar = document.getElementById('global-progress-bar');
            const progressText = document.getElementById('global-progress-text');
            const progress = stats.monthlyProgress || 0;

            setTimeout(() => {
                progressBar.style.width = `${progress}%`;
                animateCounterText('global-progress-text', progress, '%');
            }, 500);

            // Metas diarias (ejemplo)
            updateDailyGoalsStats(stats.dailyGoals || {});

        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    function updateDailyGoalsStats(goals) {
        // Actualizar lecciones diarias
        const lessonsCompleted = goals.lessonsCompleted || 0;
        const totalLessons = goals.totalLessons || 3;
        const lessonsProgress = (lessonsCompleted / totalLessons) * 100;
        
        document.getElementById('daily-lessons').textContent = `${lessonsCompleted}/${totalLessons}`;
        document.getElementById('daily-lessons-bar').style.width = `${lessonsProgress}%`;

        // Actualizar minutos de estudio
        const minutesStudied = goals.minutesStudied || 0;
        const targetMinutes = goals.targetMinutes || 60;
        const minutesProgress = (minutesStudied / targetMinutes) * 100;
        
        document.getElementById('daily-minutes').textContent = `${minutesStudied}/${targetMinutes} min`;
        document.getElementById('daily-minutes-bar').style.width = `${minutesProgress}%`;
    }

    function updateDailyGoals() {
        // Aquí iría la lógica para actualizar las metas diarias
        alert('Funcionalidad para marcar metas como completadas - Por implementar');
    }

    // Animaciones para contadores
    function animateCounter(elementId, targetValue) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const currentValue = parseInt(element.textContent) || 0;
        const duration = 800;
        const startTime = Date.now();

        function updateCounter() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const current = Math.floor(progress * (targetValue - currentValue) + currentValue);

            element.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = targetValue;
            }
        }

        updateCounter();
    }

    function animateCounterText(elementId, targetValue, suffix = '') {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const currentValue = parseFloat(element.textContent.replace(suffix, '')) || 0;
        const duration = 800;
        const startTime = Date.now();

        function updateCounter() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const current = (progress * (targetValue - currentValue) + currentValue).toFixed(1);

            element.textContent = current + suffix;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = targetValue.toFixed(1) + suffix;
            }
        }

        updateCounter();
    }

    // Cargar todo al iniciar
    document.addEventListener('DOMContentLoaded', function() {
        loadRecentActivity();
        loadUpcomingExams();
        loadCertificates();
        loadStats();

        // Actualizar cada 30 segundos
        setInterval(() => {
            loadRecentActivity();
            loadUpcomingExams();
            loadStats();
        }, 30000);
        
        // Cerrar modal al hacer clic fuera
        document.getElementById('course-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCourseModal();
            }
        });
    });

    // Función para actualizar las metas diarias (simulación)
    function simulateDailyProgress() {
        // Simular progreso aleatorio para demostración
        const randomLessons = Math.floor(Math.random() * 4);
        const randomMinutes = Math.floor(Math.random() * 61);
        
        updateDailyGoalsStats({
            lessonsCompleted: randomLessons,
            totalLessons: 3,
            minutesStudied: randomMinutes,
            targetMinutes: 60
        });
    }

    // Ejecutar simulación cada 10 segundos para demostración
    setInterval(simulateDailyProgress, 10000);
</script>

<!-- Estilos adicionales -->
<style>
    /* Estilos para el scrollbar personalizado */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc; /* Color de fondo sutil (slate-50) */
        border-radius: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1; /* Color de la barra (slate-300) */
        border-radius: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; /* Color más oscuro al pasar el ratón (slate-400) */
    }
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .progress-bar {
        transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    #course-modal {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection