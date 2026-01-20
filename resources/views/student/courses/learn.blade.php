@extends('layouts.student')
@section('title', 'Aprendiendo: ' . $course->title)
@section('content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $course->title }}</h1>
            <div class="flex items-center mt-2 space-x-4">
                <span class="text-sm text-gray-600">
                    <i class="fas fa-layer-group mr-1"></i>
                    {{ $course->sections->count() }} módulos
                </span>
                <span class="text-sm text-gray-600">
                    <i class="fas fa-chart-bar mr-1"></i>
                    {{ $course->level }}
                </span>
                <span class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-1"></i>
                    {{ $course->duration ?? 'Flexible' }}
                </span>
            </div>
        </div>
        <div class="hidden md:block">
            @php
                $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
                $progress = $enrollment ? $enrollment->progress : 0;
            @endphp
            <div class="flex items-center">
                <div class="mr-4 text-right">
                    <p class="text-sm text-gray-600">Tu progreso</p>
                    <p class="text-lg font-bold text-gray-800">{{ number_format($progress, 1) }}%</p>
                </div>
                <div class="w-16 h-16">
                    <div class="relative">
                        <svg class="w-16 h-16" viewBox="0 0 36 36">
                            <path class="text-gray-200" fill="none" stroke="currentColor" stroke-width="3"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="text-blue-500" fill="none" stroke="currentColor" stroke-width="3"
                                  stroke-dasharray="{{ $progress }}, 100"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">{{ number_format($progress, 0) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Panel izquierdo - Contenido del curso -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow mb-6">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Contenido del curso</h2>

                <!-- Acordeón de secciones -->
                <div x-data="{ openSection: null }" class="space-y-4">
                    @foreach($course->sections as $index => $section)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button
                            @click="openSection = openSection === {{ $index }} ? null : {{ $index }}"
                            class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 flex justify-between items-center text-left"
                        >
                            <div class="flex items-center">
                                <span class="text-blue-600 mr-3">
                                    <i class="fas" :class="openSection === {{ $index }} ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                </span>
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $section->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $section->lessons->count() }} lecciones
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                {{ $index + 1 }}
                            </span>
                        </button>

                        <div x-show="openSection === {{ $index }}" x-collapse class="border-t border-gray-200">
                            <div class="p-4 space-y-3">
                                @foreach($section->lessons as $lessonIndex => $lesson)
                                @php
                                    $isCompleted = false;
                                    if ($enrollment) {
                                        $isCompleted = $enrollment->completedLessons->contains($lesson->id);
                                    }
                                @endphp
                                <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $lesson->id]) }}"
                                   class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 {{ request()->is('student/courses/*/lesson/' . $lesson->id) ? 'bg-blue-50 border border-blue-100' : '' }}">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 flex items-center justify-center mr-3">
                                            @if($isCompleted)
                                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-green-600 text-xs"></i>
                                            </div>
                                            @elseif($lesson->is_free)
                                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-play text-blue-600 text-xs"></i>
                                            </div>
                                            @else
                                            <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-lock text-gray-400 text-xs"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ $lesson->title }}</h4>
                                            @if($lesson->duration)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $lesson->duration }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        @if($lesson->is_free)
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Gratis</span>
                                        @endif
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Documentos del curso -->
        @if($course->documents->count() > 0)
        <div class="bg-white rounded-xl shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Material de apoyo</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($course->documents as $document)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors duration-200">
                        <div class="flex items-start">
                            <div class="mr-4">
                                @php
                                    $fileType = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                                    $icon = match($fileType) {
                                        'pdf' => 'fa-file-pdf',
                                        'doc', 'docx' => 'fa-file-word',
                                        'xls', 'xlsx' => 'fa-file-excel',
                                        'ppt', 'pptx' => 'fa-file-powerpoint',
                                        'zip', 'rar' => 'fa-file-archive',
                                        default => 'fa-file'
                                    };
                                @endphp
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas {{ $icon }} text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $document->title }}</h4>
                                @if($document->description)
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($document->description, 100) }}</p>
                                @endif
                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-file mr-1"></i>
                                        {{ strtoupper($fileType) }}
                                        @if($document->file_size)
                                        • {{ number_format($document->file_size / 1024, 1) }} KB
                                        @endif
                                    </span>
                                    <a href="{{ Storage::url($document->file_path) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-download mr-1"></i>
                                        Descargar
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
    </div>

    <!-- Panel derecho - Información del curso -->
    <div class="lg:col-span-1">
        <div class="sticky top-6 space-y-6">
            <!-- Información del instructor -->
            @if($course->instructor)
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Instructor</h3>
                <div class="flex items-center">
                    <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
                        <img src="{{ $course->instructor->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->instructor->name) . '&color=7F9CF5&background=EBF4FF' }}"
                             alt="{{ $course->instructor->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $course->instructor->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $course->instructor->title ?? 'Instructor' }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-yellow-400 mr-1">
                                <i class="fas fa-star"></i>
                            </span>
                            <span class="text-sm text-gray-700">4.8</span>
                            <span class="text-sm text-gray-500 ml-2">(128 estudiantes)</span>
                        </div>
                    </div>
                </div>
                @if($course->instructor->bio)
                <p class="text-gray-600 text-sm mt-4">{{ Str::limit($course->instructor->bio, 150) }}</p>
                @endif
            </div>
            @endif

            <!-- Lo que aprenderás -->
            @if($course->what_you_learn)
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Lo que aprenderás</h3>
                <ul class="space-y-3">
                    @foreach($course->what_you_learn as $item)
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                        <span class="text-gray-700">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Requisitos -->
            @if($course->requirements)
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Requisitos</h3>
                <ul class="space-y-2">
                    @foreach($course->requirements as $requirement)
                    <li class="flex items-start">
                        <i class="fas fa-circle text-blue-500 text-xs mt-2 mr-3"></i>
                        <span class="text-gray-700">{{ $requirement }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Progreso del estudiante -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tu progreso</h3>
                <div class="space-y-4">
                    @php
                        $totalLessons = $course->sections->sum(function($section) {
                            return $section->lessons->count();
                        });
                        $completedLessons = $enrollment ? $enrollment->completedLessons->count() : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Lecciones completadas</span>
                            <span>{{ $completedLessons }}/{{ $totalLessons }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full bg-green-500"
                                 style="width: {{ $totalLessons > 0 ? ($completedLessons / $totalLessons * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Módulos completados</span>
                            <span>
                                @php
                                    $completedSections = 0;
                                    foreach($course->sections as $section) {
                                        $sectionLessons = $section->lessons->count();
                                        $completedSectionLessons = $enrollment ? $enrollment->completedLessons->whereIn('id', $section->lessons->pluck('id'))->count() : 0;
                                        if ($sectionLessons > 0 && $completedSectionLessons == $sectionLessons) {
                                            $completedSections++;
                                        }
                                    }
                                @endphp
                                {{ $completedSections }}/{{ $course->sections->count() }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full bg-blue-500"
                                 style="width: {{ $course->sections->count() > 0 ? ($completedSections / $course->sections->count() * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Abrir la sección activa automáticamente
    const urlParams = new URLSearchParams(window.location.search);
    const sectionId = urlParams.get('section');
    if (sectionId) {
        // Lógica para abrir la sección específica
    }
});
</script>
@endsection
