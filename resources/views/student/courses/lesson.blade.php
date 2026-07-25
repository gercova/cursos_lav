@extends('layouts.student')
@section('title', $lesson->title . ' - ' . $course->title)
@section('content')
    @php
        $enrollment = Auth::user()->enrollments()->where('course_id', $course->id)->first();
        $currentLessonId = $lesson->id;
        $currentSection = $lesson->section;

        // Obtener lecciones de la sección actual para Anterior/Siguiente
        $sectionLessons = $currentSection->lessons;
        $currentLessonIndex = $sectionLessons->search(function ($item) use ($currentLessonId) {
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
                            <i class="bi bi-arrow-left mr-2"></i>
                            Volver a mis cursos
                        </a>
                        <span class="mx-2">•</span>
                        <a href="{{ route('student.course.learn', $course->slug) }}"
                            class="text-blue-600 hover:text-blue-800">
                            {{ $course->title }}
                        </a>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $lesson->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $currentSection->title }}</p>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="hidden md:block">
                        @if ($isCompleted)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                <i class="bi bi-check-circle mr-1"></i>
                                Completada
                            </span>
                        @else
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                <i class="bi bi-play-circle mr-1"></i>
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
                            @if ($lesson->video?->vimeo_id)
                                <iframe id="vimeo-player" x-ref="videoIframe" src="{{ $lesson->video?->embed_url }}&api=1"
                                    class="w-full h-[500px]" frameborder="0"
                                    allow="autoplay; fullscreen; picture-in-picture" allowfullscreen
                                    referrerpolicy="strict-origin">
                                </iframe>
                            @else
                                <div class="w-full h-[500px] flex items-center justify-center bg-gray-900">
                                    <div class="text-center">
                                        <i class="bi bi-video text-gray-600 text-5xl mb-4"></i>
                                        <p class="text-gray-400">Video no disponible</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Overlay de carga -->
                        <div x-show="!isVideoLoaded"
                            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="text-white text-center">
                                <i class="bi bi-spinner text-3xl mb-2"></i>
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
                                        <i class="bi bi-clock mr-1"></i>
                                        <span x-text="formatTime(duration)"></span>
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        <i class="bi bi-play-circle mr-1"></i>
                                        <span x-text="watchedPercent.toFixed(1) + '% visto'"></span>
                                        @if ($watchedPercent > 0)
                                            <span class="text-xs text-gray-500">({{ $watchedPercent }}% visto
                                                previamente)</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            @if ($isCompleted)
                                <div class="flex items-center text-green-600">
                                    <i class="bi bi-check-circle text-xl mr-2"></i>
                                    <span class="font-medium">Completado</span>
                                </div>
                            @endif
                        </div>

                        @if ($lesson->description)
                            <div class="prose max-w-none">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Descripción</h3>
                                <p class="text-gray-700">{{ $lesson->description }}</p>
                            </div>
                        @endif

                        <!-- Navegación entre lecciones -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    @if ($previousLesson)
                                        <p class="text-sm text-gray-600 mb-1">Anterior</p>
                                        <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $previousLesson->id]) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                            <i class="bi bi-arrow-left mr-2"></i>
                                            {{ Str::limit($previousLesson->title, 50) }}
                                        </a>
                                    @endif
                                </div>

                                <div class="flex space-x-3">
                                    @if ($nextLesson)
                                        <a x-show="isCompleted"
                                            href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                            <span>Siguiente</span>
                                            <i class="bi bi-arrow-right ml-2"></i>
                                        </a>
                                        <button x-show="!isCompleted" disabled
                                            class="px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium opacity-70">
                                            <i class="bi bi-lock mr-2"></i>Siguiente bloqueado
                                        </button>
                                    @else
                                        <a x-show="isCompleted" href="{{ route('student.exams.show', $exam->id) }}"
                                            class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200 font-medium">
                                            <i class="bi bi-pencil-alt mr-2"></i>Ir al examen final
                                        </a>
                                        <button x-show="!isCompleted" disabled
                                            class="px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium opacity-70">
                                            <i class="bi bi-lock mr-2"></i>Examen bloqueado
                                        </button>
                                    @endif
                                </div>

                                <div class="text-right">
                                    @if ($nextLesson)
                                        <p class="text-sm text-gray-600 mb-1">Siguiente</p>
                                        <span class="text-blue-600 font-medium flex items-center justify-end">
                                            {{ Str::limit($nextLesson->title, 50) }}
                                            <i class="bi bi-arrow-right ml-2"></i>
                                        </span>
                                    @else
                                        <p class="text-sm text-gray-600 mb-1">Final del curso</p>
                                        <span class="text-purple-600 font-medium flex items-center justify-end">
                                            Examen final
                                            <i class="bi bi-arrow-right ml-2"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Mensaje de advertencia si no ha visto suficiente -->
                            <div x-show="!isCompleted && watchedPercent < minWatchPercent && watchedPercent > 0"
                                class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="bi bi-exclamation-circle text-yellow-500 mr-2"></i>
                                    <p class="text-sm text-yellow-700">
                                        Debes ver al menos el 99% de esta lección para poder marcarla como completada.
                                        <span class="font-medium">Has visto el <span
                                                x-text="watchedPercent.toFixed(1)"></span>%</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Mensaje de éxito cuando se completa -->
                            <div x-show="isCompleted" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="bi bi-check-circle text-green-500 mr-2"></i>
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
                                {{ $course->sections->count() }}
                                {{ $course->sections->count() > 1 ? 'módulos' : 'modulo' }} •
                                {{ $course->lessons->count() ?? 0 }}
                                {{ $course->lessons->count() > 1 ? 'lecciones' : 'lección' }}
                            </p>
                        </div>

                        <div class="overflow-y-auto max-h-[500px]">
                            @foreach ($course->sections as $section)
                                <div class="border-b border-gray-100 last:border-b-0">
                                    <div class="p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="font-semibold text-gray-800">{{ $section->title }}</h4>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                {{ $section->lessons->count() }}
                                            </span>
                                        </div>

                                        <div class="space-y-2">
                                            @php
                                                // Calcular de nuevo qué lecciones puede cliquear en la barra lateral
                                                $accessibleSidebar = [];
                                                $canAccessSide = true;
                                                foreach ($course->sections as $sec) {
                                                    foreach ($sec->lessons as $les) {
                                                        if ($canAccessSide) {
                                                            $accessibleSidebar[] = $les->id;
                                                            $isLesComp = $enrollment
                                                                ? $enrollment->completedLessons->contains($les->id)
                                                                : false;
                                                            if (!$isLesComp) {
                                                                $canAccessSide = false;
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @foreach ($section->lessons as $lessonItem)
                                                @php
                                                    $isItemCompleted = $enrollment
                                                        ? $enrollment->completedLessons->contains($lessonItem->id)
                                                        : false;
                                                    $isItemAccessible = in_array($lessonItem->id, $accessibleSidebar);
                                                @endphp

                                                @if ($isItemAccessible)
                                                    <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $lessonItem->id]) }}"
                                                        class="flex items-center justify-between p-2 rounded hover:bg-gray-50 transition-colors duration-200 {{ $lessonItem->id == $lesson->id ? 'bg-blue-50 border border-blue-100' : '' }}">
                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 flex items-center justify-center mr-2">
                                                                <i class="bi bi-eye"></i>
                                                            </div>
                                                            <span
                                                                class="text-sm text-gray-700">{{ Str::limit($lessonItem->title, 40) }}</span>
                                                        </div>
                                                        @if ($lessonItem->duration)
                                                            <span
                                                                class="text-xs text-gray-400">{{ $lessonItem->duration }}
                                                                min</span>
                                                        @endif
                                                    </a>
                                                @else
                                                    <div
                                                        class="flex items-center justify-between p-2 rounded bg-gray-50 opacity-60 cursor-not-allowed">
                                                        <div class="flex items-center">
                                                            <div class="w-6 h-6 flex items-center justify-center mr-2">
                                                                <i class="bi bi-lock text-gray-400 text-sm"></i>
                                                            </div>
                                                            <span
                                                                class="text-sm text-gray-500">{{ Str::limit($lessonItem->title, 40) }}</span>
                                                        </div>
                                                        @if ($lessonItem->duration)
                                                            <span
                                                                class="text-xs text-gray-400">{{ $lessonItem->duration }}
                                                                min</span>
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
                    @if ($course->documents->count() > 0 || $lesson->description)
                        <div class="bg-white rounded-xl shadow">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-bold text-gray-800">Recursos de esta lección</h3>
                            </div>
                            <div class="p-4">
                                @if ($lesson->description)
                                    <div class="mb-4">
                                        <h4 class="font-medium text-gray-700 mb-2">Descripción</h4>
                                        <p class="text-sm text-gray-600">{{ Str::limit($lesson->description, 150) }}</p>
                                    </div>
                                @endif

                                @php
                                    $lessonDocuments = $course->documents->where('is_active', true)->take(3);
                                @endphp

                                @if ($lessonDocuments->count() > 0)
                                    <div>
                                        <h4 class="font-medium text-gray-700 mb-2">Documentos</h4>
                                        <div class="space-y-2">
                                            @foreach ($lessonDocuments as $document)
                                                <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                                    class="flex items-center p-2 rounded border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors duration-200">
                                                    @php
                                                        $icon = match (
                                                            strtolower(
                                                                pathinfo($document->file_path, PATHINFO_EXTENSION),
                                                            )
                                                        ) {
                                                            'pdf' => 'bi bi-file-pdf text-red-500',
                                                            'doc', 'docx' => 'bi bi-file-word text-blue-500',
                                                            'xls', 'xlsx' => 'bi bi-file-excel text-green-500',
                                                            default => 'bi bi-file text-gray-500',
                                                        };
                                                    @endphp
                                                    <i class="bi {{ $icon }} mr-2"></i>
                                                    <span
                                                        class="text-sm text-gray-700 flex-1">{{ Str::limit($document->title, 30) }}</span>
                                                    <i class="bi bi-box-arrow-up-right text-gray-400 text-xs"></i>
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
                isVideoLoaded: false,
                isPlaying: false,
                currentTime: 0,
                duration: 0,
                watchedPercent: {{ $watchedPercent ?? 0 }},
                minWatchPercent: 99,
                lessonId: {{ $lesson->id }},
                enrollmentId: {{ $enrollment->id ?? 0 }},
                isCompleted: @json((bool) $isCompleted),
                isProcessing: false, // EL SEMÁFORO QUE EVITARÁ LOS ERRORES 500
                hasError: false,

                init() {
                    this.$nextTick(() => {
                        this.initializeVideoPlayer();
                        this.setupProgressTracking();
                    });
                },

                initializeVideoPlayer() {
                    @if ($lesson->video?->vimeo_id)
                        this.setupVimeoPlayer();
                    @endif
                },

                setupVimeoPlayer() {
                    const iframe = document.getElementById('vimeo-player');

                    if (!iframe) {
                        console.error('Vimeo iframe no encontrado.');
                        this.isVideoLoaded = true;
                        return;
                    }

                    try {
                        vimeoInstance = new Vimeo.Player(iframe);
                    } catch (e) {
                        console.error('Error creando instancia:', e);
                        this.isVideoLoaded = true;
                        return;
                    }

                    vimeoInstance.ready().then(() => {
                        this.isVideoLoaded = true;
                        vimeoInstance.getDuration().then(duration => {
                            this.duration = duration;
                        });
                    });

                    vimeoInstance.on('timeupdate', (data) => {
                        if (this.duration > 0) {
                            this.currentTime = data.seconds;

                            // Calculamos el porcentaje y aseguramos que NUNCA pase de 100
                            let percent = (data.seconds / this.duration) * 100;
                            this.watchedPercent = Math.min(100, parseFloat(percent.toFixed(2)));

                            // Condición: Si pasa el 80% + NO está completado + NO está procesando
                            if (this.watchedPercent >= this.minWatchPercent && !this
                                .isCompleted && !this.isProcessing) {
                                this.markAsCompleted();
                            }
                        }
                    });

                    vimeoInstance.on('play', () => this.isPlaying = true);

                    vimeoInstance.on('pause', () => {
                        this.isPlaying = false;
                        this.saveProgress();
                    });

                    vimeoInstance.on('ended', () => {
                        this.isPlaying = false;
                        this.watchedPercent = 100;

                        // Si NO está completada, markAsCompleted ya se encarga de guardar el 100% en BD
                        if (!this.isCompleted && !this.isProcessing) {
                            this.markAsCompleted();
                        }
                        // Si YA estaba completada (por ejemplo, se completó al 80%), solo actualizamos el progreso al 100%
                        else if (this.isCompleted && !this.isProcessing) {
                            this.saveProgress();
                        }
                    });
                },

                setupProgressTracking() {
                    this.loadSavedProgress();
                    window.addEventListener('beforeunload', () => this.saveProgress());

                    setInterval(() => {
                        if (this.isVideoLoaded && this.watchedPercent > 0 && this.isPlaying) {
                            this.saveProgress();
                        }
                    }, 30000);
                },

                loadSavedProgress() {
                    const serverProgress = {{ $watchedPercent ?? 0 }};
                    this.watchedPercent = serverProgress;

                    const savedProgress = localStorage.getItem(`lesson_${this.lessonId}_progress`);
                    if (savedProgress && parseFloat(savedProgress) > serverProgress) {
                        this.watchedPercent = parseFloat(savedProgress);
                    }

                    if (vimeoInstance && this.duration > 0 && this.watchedPercent > 0) {
                        const timeToSeek = (this.watchedPercent / 100) * this.duration;
                        vimeoInstance.setCurrentTime(timeToSeek);
                    }
                },

                async saveProgress() {
                    if (!this.isVideoLoaded || this.watchedPercent <= 0 || this.isProcessing || this
                        .hasError) return;

                    localStorage.setItem(`lesson_${this.lessonId}_progress`, this.watchedPercent
                        .toString());

                    if (this.enrollmentId > 0) {
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]')
                                ?.content || '';
                            let cleanProgress = Math.min(100, Math.round(this.watchedPercent));

                            const response = await fetch('{{ route('lesson.progress.save') }}', {
                                method: 'POST',
                                credentials: 'same-origin', // <--- LA MAGIA PARA PRODUCCIÓN (Envía la cookie)
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest' // <--- PARA QUE LARAVEL SEPA QUE ES AJAX
                                },
                                body: JSON.stringify({
                                    enrollment_id: this.enrollmentId,
                                    lesson_id: this.lessonId,
                                    progress: cleanProgress,
                                    time_watched: Math.floor(this.currentTime)
                                })
                            });

                            const result = await response.json();
                            if (!result.success) {
                                // console.error('ERROR BACKEND (Progreso):', result.message || result.error || JSON.stringify(result));
                                console.error('ERROR BACKEND (Progreso):', result.message || result
                                    .error);
                                this.hasError = true;
                            }
                        } catch (error) {
                            console.error("Error de red guardando progreso:", error);
                            // Ojo: Aquí NO ponemos this.hasError = true; 
                            // Para evitar que un micro-corte de internet le bloquee el progreso al usuario.
                        }
                    }
                },

                async markAsCompleted() {
                    if (this.isProcessing || this.isCompleted || this.hasError) return;

                    this.isProcessing = true;

                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.content ||
                            '';

                        const response = await fetch('{{ route('lesson.complete') }}', {
                            method: 'POST',
                            credentials: 'same-origin', // <--- LA MAGIA PARA PRODUCCIÓN
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest' // <--- PARA QUE LARAVEL SEPA QUE ES AJAX
                            },
                            body: JSON.stringify({
                                enrollment_id: this.enrollmentId,
                                lesson_id: this.lessonId,
                                time_spent_minutes: Math.floor(this.currentTime /
                                    60) || 1
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.isCompleted = true;
                            this.showCompletionMessage();
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            // console.error('ERROR BACKEND (Completar):', result.message);
                            console.error('ERROR BACKEND (Completar):', result.message || result
                                .error);
                            this.hasError = true;
                        }
                    } catch (error) {
                        console.error('Error de red al completar:', error);
                        this.hasError = true;
                    } finally {
                        if (!this.hasError) {
                            this.isProcessing = false;
                        }
                    }
                },

                showCompletionMessage() {
                    const messageDiv = document.createElement('div');
                    messageDiv.className =
                        'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity duration-500';
                    messageDiv.innerHTML =
                        `<div class="flex items-center"><i class="bi bi-check-circle mr-2"></i><span>¡Lección completada! Guardado con éxito.</span></div>`;
                    document.body.appendChild(messageDiv);

                    setTimeout(() => {
                        messageDiv.style.opacity = '0';
                        setTimeout(() => messageDiv.remove(), 500);
                    }, 3000);
                },

                formatTime(seconds) {
                    if (!seconds || isNaN(seconds)) return '00:00';
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
            padding-bottom: 56.25%;
            /* 16:9 Aspect Ratio */
        }

        .aspect-w-16>* {
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
