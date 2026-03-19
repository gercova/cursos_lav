@extends('layouts.student')
@section('title', 'Mi Progreso')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Mi Progreso General</h1>
    <p class="text-gray-500 text-sm mt-1">Sigue de cerca tu avance y mantén tu racha de aprendizaje.</p>
</div>

<div class="dashboard-grid">
    <div class="card bg-gradient-to-br from-blue-50 to-white">
        <div class="card-body flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-trophy text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Cursos Completados</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['completed_courses'] }} <span class="text-sm font-normal text-gray-400">/ {{ $stats['total_courses'] }}</span></h3>
            </div>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-emerald-50 to-white">
        <div class="card-body flex items-center">
            <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 mr-4">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Tasa de Finalización</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['completion_rate'] }}%</h3>
            </div>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-purple-50 to-white">
        <div class="card-body flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Horas de Estudio</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_study_hours'] }} <span class="text-sm font-normal text-gray-400">hrs</span></h3>
            </div>
        </div>
    </div>

    <div class="card bg-gradient-to-br from-orange-50 to-white">
        <div class="card-body flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                <i class="fas fa-fire text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Racha Actual</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['streak_days'] }} <span class="text-sm font-normal text-gray-400">días</span></h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-book-open text-blue-500 mr-2"></i> Mis Cursos</h2>
            </div>
            <div class="p-6">
                @forelse($courses as $course)
                    <div class="mb-6 last:mb-0 border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-1/4 flex-shrink-0">
                                <img src="{{ $course['image_url'] }}" alt="{{ $course['title'] }}" class="w-full h-24 object-cover rounded-lg shadow-sm">
                            </div>
                            <div class="w-full sm:w-3/4 flex flex-col justify-center">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-md font-bold text-gray-800 line-clamp-2">{{ $course['title'] }}</h3>
                                </div>
                                <p class="text-xs text-gray-500 mb-3"><i class="fas fa-chalkboard-teacher mr-1"></i> {{ $course['instructor'] }}</p>
                                
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                                    <div class="{{ $course['progress'] == 100 ? 'bg-emerald-500' : 'bg-blue-600' }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $course['progress'] }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 font-medium">
                                    <span>{{ $course['progress'] }}% Completado</span>
                                    <span>{{ $course['completed_lessons'] }} de {{ $course['total_lessons'] }} lecciones</span>
                                </div>
                                
                                <div class="mt-4 flex flex-wrap justify-end gap-2">
                                    @if($course['progress'] == 100)
                                        <a href="{{ route('student.course.learn', $course['id']) }}" class="inline-block bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                            Repasar curso <i class="fas fa-redo ml-1"></i>
                                        </a>

                                        @if($course['certificate_id'])
                                            <a href="{{ url('/certificate/' . $course['certificate_id']) }}" class="inline-block bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                                Ver mi certificado <i class="fas fa-certificate ml-1"></i>
                                            </a>
                                        @elseif($course['has_exam'] && !$course['has_passed_exam'])
                                            <a href="{{ url('/exams/' . $course['exam_id']) }}" class="inline-block bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                                Ir a examen <i class="fas fa-file-alt ml-1"></i>
                                            </a>
                                        @endif
                                    @else
                                        @if($course['last_lesson_id'])
                                            <a href="{{ route('lesson.show', [$course['slug'], $course['last_lesson_id']]) }}" class="inline-block bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                                Continuar aprendiendo <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('student.course.learn', $course['id']) }}" class="inline-block bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors duration-200">
                                                Iniciar Curso <i class="fas fa-play ml-1"></i>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-gray-300 mb-3"><i class="fas fa-graduation-cap text-5xl"></i></div>
                        <p class="text-gray-500 font-medium">¡No tienes cursos en progreso!</p>
                        <p class="text-sm text-gray-400 mt-1">Anímate a explorar el catálogo.</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if($completedCourses->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Completados Recientemente</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($completedCourses->take(4) as $completed)
                    <div class="border border-gray-100 rounded-lg p-3 flex gap-3 hover:shadow-md transition bg-gray-50">
                        <img src="{{ $completed['image_url'] }}" alt="{{ $completed['title'] }}" class="w-16 h-16 object-cover rounded-md">
                        <div class="flex flex-col justify-center">
                            <h4 class="text-sm font-bold text-gray-800 line-clamp-2">{{ $completed['title'] }}</h4>
                            <span class="text-xs text-emerald-600 font-semibold mt-1"><i class="fas fa-check mr-1"></i> 100% Completado</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="xl:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-24">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-history text-gray-500 mr-2"></i> Tu Actividad Reciente</h2>
            </div>
            <div class="p-6">
                @if($recentActivity->count() > 0)
                    <div class="relative border-l-2 border-gray-200 ml-3 space-y-6">
                        @foreach($recentActivity as $activity)
                            @php
                                $details = is_string($activity->data) ? json_decode($activity->data) : (object) $activity->data;
                            @endphp
                            <div class="relative pl-6">
                                <div class="absolute -left-[17px] top-1 bg-white border-2 border-blue-500 rounded-full w-8 h-8 flex items-center justify-center">
                                    @if($activity->type == 'lesson_completed')
                                        <i class="fas fa-play text-blue-500 text-xs"></i>
                                    @elseif($activity->type == 'document_completed')
                                        <i class="fas fa-file-pdf text-red-500 text-xs text-xs"></i>
                                    @else
                                        <i class="fas fa-check text-emerald-500 text-xs text-xs"></i>
                                    @endif
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-400 font-medium mb-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    <h4 class="text-sm font-bold text-gray-800">Lección Completada</h4>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $details->lesson_title ?? 'Contenido' }}</p>
                                    <p class="text-xs text-blue-600 font-medium mt-1">{{ $details->course_title ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-sm text-gray-500">Aún no hay actividad reciente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection