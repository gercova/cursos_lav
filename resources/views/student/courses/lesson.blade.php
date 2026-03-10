@extends('layouts.student')
@section('title', $lesson->title . ' - ' . $course->title)
@section('content')
@php
    $enrollment         = Auth::user()->enrollments()->where('course_id', $course->id)->first();
    $currentLessonId    = $lesson->id;
    $currentSection     = $lesson->section;

    // Obtener lecciones de la sección actual para Anterior/Siguiente
    $sectionLessons     = $currentSection->lessons;
    $currentLessonIndex = $sectionLessons->search(function($item) use ($currentLessonId) {
        return $item->id == $currentLessonId;
    });

    // Determinar lección anterior y siguiente
    $previousLesson = null;
    $nextLesson = null;

    if ($currentLessonIndex > 0) {
        $previousLesson = $sectionLessons[$currentLessonIndex - 1];
    } else {
        $previousSection = $currentSection->previousSection();
        if ($previousSection && $previousSection->lessons->count() > 0) {
            $previousLesson = $previousSection->lessons->last();
        }
    }

    if ($currentLessonIndex < $sectionLessons->count() - 1) {
        $nextLesson = $sectionLessons[$currentLessonIndex + 1];
    } else {
        $nextSection = $currentSection->nextSection();
        if ($nextSection && $nextSection->lessons->count() > 0) {
            $nextLesson = $nextSection->lessons->first();
        }
    }

    // ELIMINADA la re-declaración de $isCompleted (el controlador ya la envía perfecta)
    // OPTIMIZACIÓN: Sacar solo los IDs de las lecciones completadas para verificaciones rápidas
    $completedLessonIds = $enrollment ? $enrollment->completedLessons->pluck('lesson_id')->toArray() : [];
@endphp

<div x-data="lessonPlayer" x-init="init()">
    <!-- Header de navegación -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center text-sm text-gray-600 mb-2">
                    <a href="{{ route('student.my-courses') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a mis cursos
                    </a>
                    <span class="mx-2">•</span>
                    <a href="{{ route('student.course.learn', $course->slug) }}" class="text-blue-600 hover:text-blue-800">
                        {{ $course->title }}
                    </a>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $lesson->title }}</h1>
                <p class="text-gray-600 mt-2">{{ $currentSection->title }}</p>
            </div>

            <div class="flex items-center space-x-3">
                <div class="hidden md:block">
                    @if($isCompleted)
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>
                        Completada
                    </span>
                    @else
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        <i class="fas fa-play-circle mr-1"></i>
                        En progreso
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contenido principal - Video -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Reproductor de video -->
                <div class="relative bg-black">
                    <div class="aspect-w-16 aspect-h-9" x-ignore>
                        @if($lesson->video?->vimeo_id)
                        
                        <iframe id="vimeo-player"
                            x-ref="videoIframe" 
                            src="{{$lesson->video?->embed_url}}&api=1"
                            class="w-full h-[500px]"
                            frameborder="0"
                            allow="autoplay; fullscreen; picture-in-picture"
                            allowfullscreen
                            referrerpolicy="strict-origin"
                        >
                        </iframe>
                        
                            <iframe id="vimeo-player"
                                x-ref="videoIframe" 
                                src="{{$lesson->video?->embed_url}}&api=1"
                                class="w-full h-[500px]"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen
                                referrerpolicy="strict-origin"
                            >
                            </iframe>

                        @else
                        <div class="w-full h-[500px] flex items-center justify-center bg-gray-900">
                            <div class="text-center">
                                <i class="fas fa-video text-gray-600 text-5xl mb-4"></i>
                                <p class="text-gray-400">Video no disponible</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Overlay de carga -->
                    <div x-show="!isVideoLoaded" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="text-white text-center">
                            <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                            <p>Cargando video...</p>
                        </div>
                    </div>
                </div>

                <!-- Información del video -->
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ $lesson->title }}</h2>
                            <div class="flex items-center mt-2 space-x-4">
                                <span class="text-sm text-gray-600">
                                    <i class="far fa-clock mr-1"></i>
                                    <span x-text="formatTime(duration)"></span>
                                </span>
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-play-circle mr-1"></i>
                                    <span x-text="watchedPercent.toFixed(1) + '% visto'"></span>
                                    @if($watchedPercent > 0)
                                        <span class="text-xs text-gray-500">({{ $watchedPercent }}% visto previamente)</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($isCompleted)
                        <div class="flex items-center text-green-600">
                            <i class="fas fa-check-circle text-xl mr-2"></i>
                            <span class="font-medium">Completado</span>
                        </div>
                        @endif
                    </div>

                    @if($lesson->description)
                        <div class="prose max-w-none">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Descripción</h3>
                            <p class="text-gray-700">{{ $lesson->description }}</p>
                        </div>
                    @endif

                    <!-- Navegación entre lecciones -->
                    {{-- <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                @if($previousLesson)
                                <p class="text-sm text-gray-600 mb-1">Anterior</p>
                                <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    {{ Str::limit($previousLesson->title, 50) }}
                                </a>
                                @endif
                            </div>

                            <div class="flex space-x-3">
                                @if($previousLesson)
                                <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Anterior
                                </a>
                                @endif

                                @if($nextLesson)
                                    <button id="nextLessonBtn"
                                        @click="goToNextLesson()"
                                        :disabled="!isCompleted && watchedPercent < 80"
                                        :class="(!isCompleted && watchedPercent < 80) ?
                                            'opacity-50 cursor-not-allowed bg-blue-400' :
                                            'bg-blue-600 hover:bg-blue-700'"
                                        class="px-4 py-2 text-white rounded-lg transition-colors duration-200"
                                    >
                                        <span>Siguiente</span>
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                @else
                                <a href="{{ route('student.course.learn', $course->slug) }}"
                                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                                    <span>Completar Curso</span>
                                    <i class="fas fa-trophy ml-2"></i>
                                </a>
                                @endif
                            </div>

                            <div class="text-right">
                                @if($nextLesson)
                                <p class="text-sm text-gray-600 mb-1">Siguiente</p>
                                <span class="text-blue-600 font-medium flex items-center justify-end">
                                    {{ Str::limit($nextLesson->title, 50) }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Mensaje de advertencia si no ha visto suficiente -->
                        <div x-show="!isCompleted && watchedPercent < 80 && watchedPercent > 0"
                             class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>
                                <p class="text-sm text-yellow-700">
                                    Debes ver al menos el 80% de esta lección para avanzar a la siguiente.
                                    <span class="font-medium">Has visto el <span x-text="watchedPercent.toFixed(1)"></span>%</span>
                                </p>
                            </div>
                        </div>
                    </div> --}}
                    <!-- Navegación entre lecciones -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                @if($previousLesson)
                                    <p class="text-sm text-gray-600 mb-1">Anterior</p>
                                    <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        {{ Str::limit($previousLesson->title, 50) }}
                                    </a>
                                @endif
                            </div>

                            {{-- <div class="flex space-x-3">
                                @if($previousLesson)
                                    <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Anterior
                                    </a>
                                @endif

                                <!-- Botón para marcar como completada (visible cuando no está completada) -->
                                <button id="completeLessonBtn"
                                    @if(!$isCompleted)
                                    @click="markAsCompleted()"
                                    :disabled="watchedPercent < minWatchPercent"
                                    :class="watchedPercent < minWatchPercent ? 
                                        'opacity-50 cursor-not-allowed bg-green-400' : 
                                        'bg-green-600 hover:bg-green-700'"
                                    @endif
                                    class="px-6 py-2 text-white rounded-lg transition-colors duration-200 font-medium"
                                    @if($isCompleted) disabled style="opacity: 50; cursor: not-allowed;" @endif
                                >
                                    <span x-show="!$el.__x.$data.isCompleted && $el.__x.$data.watchedPercent < $el.__x.$data.minWatchPercent">
                                        <i class="fas fa-lock mr-2"></i>
                                        Ver al menos 80% para completar
                                    </span>
                                    <span x-show="!$el.__x.$data.isCompleted && $el.__x.$data.watchedPercent >= $el.__x.$data.minWatchPercent">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Marcar como completada
                                    </span>
                                    <span x-show="$el.__x.$data.isCompleted">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Completada
                                    </span>
                                </button>

                                <!-- Botón siguiente (ahora siempre disponible si existe) -->
                                @if($nextLesson)
                                    <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                        <span>Siguiente</span>
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                @else
                                    <!-- Si no hay siguiente lección, redirigir al examen -->
                                    <a href="{{ route('student.exams.show', $exam->id) }}" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                        <i class="fas fa-pencil-alt mr-2"></i>Ir al examen final
                                    </a>
                                @endif
                            </div> --}}

                            <div class="flex space-x-3">
                                @if($nextLesson)
                                    <a x-show="isCompleted" href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                        <span>Siguiente</span>
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                    <button x-show="!isCompleted" disabled
                                    class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium opacity-70">
                                        <i class="fas fa-lock mr-2"></i>Siguiente bloqueado
                                    </button>
                                @else
                                    <a x-show="isCompleted" href="{{ route('student.exams.show', $exam->id) }}" 
                                    class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                        <i class="fas fa-pencil-alt mr-2"></i>Ir al examen final
                                    </a>
                                    <button x-show="!isCompleted" disabled 
                                    class="px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium opacity-70">
                                        <i class="fas fa-lock mr-2"></i>Examen bloqueado
                                    </button>
                                @endif
                            </div>

                            <div class="text-right">
                                @if($nextLesson)
                                <p class="text-sm text-gray-600 mb-1">Siguiente</p>
                                <span class="text-blue-600 font-medium flex items-center justify-end">
                                    {{ Str::limit($nextLesson->title, 50) }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </span>
                                @else
                                <p class="text-sm text-gray-600 mb-1">Final del curso</p>
                                <span class="text-purple-600 font-medium flex items-center justify-end">
                                    Examen final
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Mensaje de advertencia si no ha visto suficiente -->
                        <div x-show="!isCompleted && watchedPercent < minWatchPercent && watchedPercent > 0"
                            class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>
                                <p class="text-sm text-yellow-700">
                                    Debes ver al menos el 80% de esta lección para poder marcarla como completada.
                                    <span class="font-medium">Has visto el <span x-text="watchedPercent.toFixed(1)"></span>%</span>
                                </p>
                            </div>
                        </div>

                        <!-- Mensaje de éxito cuando se completa -->
                        <div x-show="isCompleted" 
                            class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <p class="text-sm text-green-700">
                                    ¡Lección completada! Puedes continuar con la siguiente lección.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Fin Navegación entre lecciones -->
                </div>
            </div>
        </div>

        <!-- Panel derecho - Secciones y lecciones -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <!-- Secciones del curso -->
                <div class="bg-white rounded-xl shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-800">Contenido del curso</h3>
                        <p class="text-sm text-gray-600">
                            {{ $course->sections->count() }} {{ $course->sections->count() > 1 ? 'módulos' : 'modulo' }} • {{ $course->lessons->count() ?? 0 }} {{ $course->lessons->count() > 1 ? 'lecciones' : 'lección' }}
                        </p>
                    </div>

                    <div class="overflow-y-auto max-h-[500px]">
                        @foreach($course->sections as $section)
                        <div class="border-b border-gray-100 last:border-b-0">
                            <div class="p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ $section->title }}</h4>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        {{ $section->lessons->count() }}
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    {{-- @foreach($section->lessons as $lessonItem)
                                        @php
                                            $isItemCompleted = $enrollment ? $enrollment->completedLessons->contains($lessonItem->id) : false;
                                        @endphp
                                        <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $lessonItem->id]) }}"
                                        class="flex items-center justify-between p-2 rounded hover:bg-gray-50 transition-colors duration-200 {{ $lessonItem->id == $lesson->id ? 'bg-blue-50 border border-blue-100' : '' }}">
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 flex items-center justify-center mr-2">
                                                    @if($isItemCompleted)
                                                        <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                                    @elseif($lessonItem->id == $lesson->id)
                                                        <i class="fas fa-play-circle text-blue-500 text-sm"></i>
                                                    @else
                                                        <i class="far fa-circle text-gray-300 text-sm"></i>
                                                    @endif
                                                </div>
                                                <span class="text-sm {{ $lessonItem->id == $lesson->id ? 'font-medium text-blue-700' : 'text-gray-700' }}">
                                                    {{ Str::limit($lessonItem->title, 40) }}
                                                </span>
                                            </div>
                                            @if($lessonItem->duration)
                                            <span class="text-xs text-gray-500">{{ $lessonItem->duration }}</span>
                                            @endif
                                        </a>
                                    @endforeach --}}
                                    @php
                                        // Calcular de nuevo qué lecciones puede cliquear en la barra lateral
                                        $accessibleSidebar = [];
                                        $canAccessSide = true;
                                        foreach($course->sections as $sec) {
                                            foreach($sec->lessons as $les) {
                                                if ($canAccessSide) {
                                                    $accessibleSidebar[] = $les->id;
                                                    $isLesComp = $enrollment ? $enrollment->completedLessons->contains($les->id) : false;
                                                    if (!$isLesComp) {
                                                        $canAccessSide = false;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    @foreach($section->lessons as $lessonItem)
                                        @php
                                            $isItemCompleted    = $enrollment ? $enrollment->completedLessons->contains($lessonItem->id) : false;
                                            $isItemAccessible   = in_array($lessonItem->id, $accessibleSidebar);
                                        @endphp
                                        
                                        @if($isItemAccessible)
                                            <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $lessonItem->id]) }}"
                                            class="flex items-center justify-between p-2 rounded hover:bg-gray-50 transition-colors duration-200 {{ $lessonItem->id == $lesson->id ? 'bg-blue-50 border border-blue-100' : '' }}">
                                                <div class="flex items-center">
                                                    <div class="w-6 h-6 flex items-center justify-center mr-2">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </div>
                                                    <span class="text-sm text-gray-700">{{ Str::limit($lessonItem->title, 40) }}</span>
                                                </div>
                                                @if($lessonItem->duration)
                                                    <span class="text-xs text-gray-400">{{ $lessonItem->duration }} min</span>
                                                @endif
                                            </a>
                                        @else
                                            <div class="flex items-center justify-between p-2 rounded bg-gray-50 opacity-60 cursor-not-allowed">
                                                <div class="flex items-center">
                                                    <div class="w-6 h-6 flex items-center justify-center mr-2">
                                                        <i class="fas fa-lock text-gray-400 text-sm"></i>
                                                    </div>
                                                    <span class="text-sm text-gray-500">{{ Str::limit($lessonItem->title, 40) }}</span>
                                                </div>
                                                @if($lessonItem->duration)
                                                    <span class="text-xs text-gray-400">{{ $lessonItem->duration }} min</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recursos adicionales -->
                @if($course->documents->count() > 0 || $lesson->description)
                <div class="bg-white rounded-xl shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-800">Recursos de esta lección</h3>
                    </div>
                    <div class="p-4">
                        @if($lesson->description)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700 mb-2">Descripción</h4>
                                <p class="text-sm text-gray-600">{{ Str::limit($lesson->description, 150) }}</p>
                            </div>
                        @endif

                        @php
                            $lessonDocuments = $course->documents->where('is_active', true)->take(3);
                        @endphp

                        @if($lessonDocuments->count() > 0)
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2">Documentos</h4>
                                <div class="space-y-2">
                                    @foreach($lessonDocuments as $document)
                                        <a href="{{ Storage::url($document->file_path) }}"
                                        target="_blank"
                                        class="flex items-center p-2 rounded border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200">
                                            @php
                                                $icon = match(strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION))) {
                                                    'pdf' => 'fa-file-pdf text-red-500',
                                                    'doc', 'docx' => 'fa-file-word text-blue-500',
                                                    'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                                    default => 'fa-file text-gray-500'
                                                };
                                            @endphp
                                            <i class="fas {{ $icon }} mr-2"></i>
                                            <span class="text-sm text-gray-700 flex-1">{{ Str::limit($document->title, 30) }}</span>
                                            <i class="fas fa-external-link-alt text-gray-400 text-xs"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script src="https://player.vimeo.com/api/player.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        let vimeoInstance = null;
        Alpine.data('lessonPlayer', () => ({
            //videoId: null,
            // player: null,
            isVideoLoaded: false,
            isPlaying: false,
            currentTime: 0,
            duration: 0,
            watchedPercent: 0,
            minWatchPercent: 80, // Porcentaje mínimo que debe ver para marcar como completado
            lessonId: {{ $lesson->id }},
            enrollmentId: {{ $enrollment->id ?? 0 }},
            isCompleted: @json($isCompleted),
            progressInterval: null,

            init() {
                this.$nextTick(() => {
                    this.initializeVideoPlayer();
                    this.setupProgressTracking();
                })
                
            },

            initializeVideoPlayer() {
                @if($lesson->video?->vimeo_id)
                    //this.videoId="{{$lesson->video?->vimeo_id}}";
                    this.setupVimeoPlayer();
                @endif
            },

            setupVimeoPlayer() {
                const iframe = this.$refs.videoIframe || document.querySelector('#vimeo-player');
                if (!iframe) {
                    console.error('Vimeo iframe no encontrado');
                    return;
                }

                try {
                    vimeoInstance = new Vimeo.Player(iframe);
                } catch (e) {
                    console.error('Error creando instancia de Vimeo.Player:', e);
                    return;
                }
                vimeoInstance.ready().then(()=>{
                }).catch(err => {
                    console.error('Vimeo player ready failed after retries:', err);
                });

                vimeoInstance.on('loaded', () => {
                    this.isVideoLoaded = true;
                    vimeoInstance.getDuration().then(duration => {
                        this.duration = duration;
                    });
                });

                /*
                vimeoInstance.on('timeupdate', (data) => {
                    this.currentTime = data.seconds;
                    this.watchedPercent = (data.percent * 100);
                });*/

                vimeoInstance.on('play', () => {
                    this.isPlaying = true;
                    this.startProgressTracking();
                    // Iniciar intervalo de seguimiento si no existe
                    if (!this.progressInterval) {
                        this.progressInterval = setInterval(() => {
                            if (this.isPlaying && vimeoInstance) {
                                vimeoInstance.getCurrentTime().then(time => {
                                    this.currentTime = time;
                                    if (this.duration > 0) {
                                        this.watchedPercent = (time / this.duration) * 100;
                                    }
                                });
                            }
                        }, 1000);
                    }
                });

                vimeoInstance.on('pause', () => {
                    this.isPlaying = false;
                    this.stopProgressTracking();
                    this.saveProgress();
                    // Limpiar intervalo
                    if (this.progressInterval) {
                        clearInterval(this.progressInterval);
                        this.progressInterval = null;
                    }
                });

                vimeoInstance.on('ended', () => {
                    this.isPlaying = false;
                    this.watchedPercent = 100;
                    this.saveProgress();
                    this.markAsCompleted();
                    // Limpiar intervalo
                    if (this.progressInterval) {
                        clearInterval(this.progressInterval);
                        this.progressInterval = null;
                    }
                });
            },

            setupProgressTracking() {
                // Recuperar progreso guardado
                this.loadSavedProgress();

                // Guardar progreso cuando el usuario salga de la página
                window.addEventListener('beforeunload', () => {
                    this.saveProgress();
                });

                // Guardar progreso cada 30 segundos
                setInterval(() => {
                    if (this.isVideoLoaded && this.watchedPercent > 0) {
                        this.saveProgress();
                    }
                }, 30000);
            },

            loadSavedProgress() {
                // Usar el valor inicial del servidor si está disponible
                const serverProgress = {{ $watchedPercent ?? 0 }};
                this.watchedPercent = serverProgress;

                // Intentar cargar del localStorage como respaldo
                const savedProgress = localStorage.getItem(`lesson_${this.lessonId}_progress`);
                if (savedProgress && parseFloat(savedProgress) > serverProgress) {
                    this.watchedPercent = parseFloat(savedProgress);
                }

                // Si hay progreso y el reproductor está listo, buscar ese punto
                if (vimeoInstance && this.duration > 0 && this.watchedPercent > 0) {
                    const timeToSeek = (this.watchedPercent / 100) * this.duration;
                    vimeoInstance.setCurrentTime(timeToSeek);
                }
            },

            saveProgress() {
                if (!this.isVideoLoaded || this.watchedPercent <= 0) return;

                // Guardar en localStorage
                localStorage.setItem(`lesson_${this.lessonId}_progress`, this.watchedPercent.toFixed(2));

                // Guardar en el servidor si tenemos enrollment
                if (this.enrollmentId > 0) {
                    fetch('{{ route("lesson.progress.save") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            enrollment_id: this.enrollmentId,
                            lesson_id: this.lessonId,
                            progress: this.watchedPercent,
                            time_watched: Math.floor(this.currentTime)
                        })
                    });
                }
            },

            startProgressTracking() {
                // Iniciar tracking de progreso
                console.log('Iniciando seguimiento de progreso...');
            },

            stopProgressTracking() {
                // Detener tracking de progreso
                console.log('Deteniendo seguimiento de progreso...');
            },

            async markAsCompleted() {
                if (this.watchedPercent >= this.minWatchPercent && !this.isCompleted) {
                    try {
                        const response = await fetch('{{ route("lesson.complete") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                enrollment_id: this.enrollmentId,
                                lesson_id: this.lessonId,
                                time_spent_minutes: Math.floor(this.currentTime / 60) || 1
                            })
                        });

                        const result = await response.json();
                        if (result.success) {
                            // Actualizar estado local
                            this.isCompleted = true;
                            
                            // Mostrar mensaje de éxito
                            this.showCompletionMessage();
                            
                            // Recargar la página después de 1.5 segundos para actualizar todos los estados
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }
                    } catch (error) {
                        console.error('Error al marcar como completado:', error);
                        
                        // Mostrar mensaje de error
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                        errorDiv.innerHTML = `
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>Error al marcar la lección como completada</span>
                            </div>
                        `;
                        document.body.appendChild(errorDiv);
                        
                        setTimeout(() => {
                            errorDiv.remove();
                        }, 3000);
                    }
                }
            },

            showCompletionMessage() {
                // Mostrar mensaje de éxito
                const messageDiv = document.createElement('div');
                messageDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                messageDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>¡Lección completada! Actualizando...</span>
                    </div>
                `;
                document.body.appendChild(messageDiv);
            }

            goToPreviousLesson() {
                @if($previousLesson)
                    window.location.href = "{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}";
                @endif
            },

            goToNextLesson() {
                @if($nextLesson)
                    window.location.href = "{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}";
                @endif
            },

            formatTime(seconds) {
                if (!seconds) return '00:00';
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }
        }));
    });
</script>

<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    }

    .aspect-w-16 > * {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    .progress-bar {
        transition: width 0.3s ease;
    }
</style>

@endsection
