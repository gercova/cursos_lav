@extends('layouts.student')
@section('title', 'Mi Progreso')
@section('content')
@php
    /* ── Pre-compute collections once ─────────────────────────────────── */
    $inProgressCourses  = collect($courses)->where('progress', '>', 0)->where('progress', '<', 100)->values();
    $notStartedCourses  = collect($courses)->where('progress', 0)->values();
    $finishedCourses    = $completedCourses; // alias para compatibilidad JS

    // Curso más reciente en progreso → tarjeta "Retomar"
    $resumeCourse = $inProgressCourses
        ->filter(fn($c) => !is_null($c['last_accessed']))
        ->sortByDesc('last_accessed')
        ->first()
        ?? $inProgressCourses->first();
@endphp

<div class="progress-page" x-data="progressRing()" x-init="initRing()">
    {{-- ════════════════════════════════════════════
        HERO BANNER
    ════════════════════════════════════════════ --}}
    <div class="hero-banner mb-6 anim-fadeUp delay-1">
        <div class="hero-dots"></div>
        <div class="relative z-10 p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center">

                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold text-blue-300 uppercase tracking-widest">Panel de aprendizaje</span>
                    </div>
                    <h1 class="display-font text-2xl sm:text-3xl font-extrabold text-white leading-tight mb-1">
                        ¡Hola, {{ auth()->user()->names }}! 👋
                    </h1>
                    <p class="text-blue-200 text-sm font-light max-w-lg">
                        Aquí tienes un resumen completo de tu aprendizaje. Sigue así — cada lección te acerca más a tu meta.
                    </p>

                    <div class="flex flex-wrap gap-4 mt-5">
                        <div class="flex items-center gap-2">
                            <span class="flame text-yellow-400 text-lg">
                                <i class="fas fa-solid fa-fire"></i>
                            </span>
                            <div>
                                <p class="text-white font-extrabold display-font text-xl leading-none">{{ $stats['streak_days'] }}</p>
                                <p class="text-blue-300 text-[11px] font-medium">días de racha</p>
                            </div>
                        </div>
                        <div class="w-px bg-white/10 self-stretch hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 text-lg"><i class="fas fa-circle-check"></i></span>
                            <div>
                                <p class="text-white font-extrabold display-font text-xl leading-none">{{ $stats['completed_courses'] }}</p>
                                <p class="text-blue-300 text-[11px] font-medium">cursos finalizados</p>
                            </div>
                        </div>
                        <div class="w-px bg-white/10 self-stretch hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-purple-400 text-lg"><i class="fas fa-clock"></i></span>
                            <div>
                                <p class="text-white font-extrabold display-font text-xl leading-none">{{ $stats['total_study_hours'] }}</p>
                                <p class="text-blue-300 text-[11px] font-medium">horas de estudio</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Progress ring --}}
                <div class="flex flex-col items-center gap-2 lg:pr-4">
                    <div class="donut-wrap">
                        <svg viewBox="0 0 140 140" width="140" height="140" class="progress-ring" style="transform:rotate(-90deg)">
                            <circle class="track" cx="70" cy="70" r="58" stroke-width="10"/>
                            <circle class="fill" cx="70" cy="70" r="58"
                                stroke-width="10" stroke="#3b82f6"
                                stroke-dasharray="364.4"
                                :stroke-dashoffset="ringOffset"
                                style="transition: stroke-dashoffset 1.4s cubic-bezier(0.34,1.1,0.64,1)"/>
                        </svg>
                        <div class="donut-center">
                            <span class="display-font text-3xl font-extrabold text-white" x-text="displayRate + '%'"></span>
                            <span class="text-blue-300 text-[11px] font-medium mt-0.5">completado</span>
                        </div>
                    </div>
                    <span class="text-blue-200 text-xs font-medium text-center">Tasa de finalización global</span>
                </div>

            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════
         STAT CARDS
    ════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="stat-card blue anim-fadeUp delay-2">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon blue"><i class="fas fa-book-open"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Inscritos</span>
            </div>
            <div class="stat-number" style="animation-delay:.2s">{{ $stats['total_courses'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">cursos en total</p>
        </div>

        <div class="stat-card green anim-fadeUp delay-3">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon green"><i class="fas fa-trophy"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Completados</span>
            </div>
            <div class="stat-number" style="animation-delay:.27s">{{ $stats['completed_courses'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">de {{ $stats['total_courses'] }} cursos</p>
        </div>

        <div class="stat-card purple anim-fadeUp delay-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon purple"><i class="fas fa-clock"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Estudio</span>
            </div>
            <div class="stat-number" style="animation-delay:.34s">{{ $stats['total_study_hours'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">horas acumuladas</p>
        </div>

        <div class="stat-card amber anim-fadeUp delay-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon amber"><i class="fas fa-fire"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Racha</span>
            </div>
            <div class="stat-number" style="animation-delay:.41s">{{ $stats['streak_days'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">días consecutivos</p>
        </div>

    </div>

    {{-- ════════════════════════════════════════════
         MAIN GRID (left 2/3 · right 1/3)
    ════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ──────────────────────────────────────
             LEFT COLUMN (xl:col-span-2)
        ────────────────────────────────────── --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- ── TARJETA "Retomar aprendizaje" ── --}}
            @if($resumeCourse)
            <div class="resume-card p-5 anim-fadeUp delay-5">
                <div class="relative z-10 flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    <img src="{{ $resumeCourse['image_url'] }}" alt="{{ $resumeCourse['title'] }}" class="resume-card-thumb">

                    <div class="flex-1 min-w-0">
                        <p class="text-blue-200 text-[11px] font-bold uppercase tracking-widest mb-1">
                            <i class="fas fa-play-circle mr-1"></i> Retomar aprendizaje
                        </p>
                        <h3 class="display-font text-base font-extrabold text-white leading-snug line-clamp-2 mb-1">
                            {{ $resumeCourse['title'] }}
                        </h3>
                        <p class="text-blue-200 text-xs mb-3 flex items-center gap-1">
                            <i class="fas fa-chalkboard-user text-[10px]"></i>
                            {{ $resumeCourse['instructor'] }}
                            @if($resumeCourse['last_accessed'])
                                <span class="text-white/30 mx-1">·</span>
                                <i class="fas fa-clock text-[10px]"></i>
                                {{ \Carbon\Carbon::parse($resumeCourse['last_accessed'])->diffForHumans() }}
                            @endif
                        </p>

                        {{-- Mini progress bar --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex-1" style="height:5px;background:rgba(255,255,255,0.2);border-radius:999px;overflow:hidden">
                                <div style="height:100%;width:{{ $resumeCourse['progress'] }}%;background:rgba(255,255,255,0.9);border-radius:999px;transition:width 1.2s ease"></div>
                            </div>
                            <span class="text-white text-xs font-bold flex-shrink-0">{{ $resumeCourse['progress'] }}%</span>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            @if($resumeCourse['last_lesson_id'])
                                <a href="{{ route('lesson.show', [$resumeCourse['slug'], $resumeCourse['last_lesson_id']]) }}" class="btn-white">
                                    <i class="fas fa-play text-[9px]"></i> Continuar curso
                                </a>
                            @else
                                <a href="{{ route('student.course.learn', $resumeCourse['id']) }}" class="btn-white">
                                    <i class="fas fa-play text-[9px]"></i> Continuar curso
                                </a>
                            @endif
                            <span class="text-white/60 text-xs">
                                {{ $resumeCourse['completed_lessons'] }} / {{ $resumeCourse['total_lessons'] }} lecciones completadas
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── RESUMEN DE PROGRESO (donut) ── --}}
            <div class="glass-card p-6 anim-fadeUp delay-5">
                <div class="section-header">
                    <span class="section-title">
                        <i class="fas fa-chart-pie text-blue-500"></i>
                        Resumen de progreso
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <div style="width:180px;height:180px;flex-shrink:0;position:relative">
                        <canvas id="donutChart" width="180" height="180"></canvas>
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none">
                            <span class="display-font text-2xl font-extrabold text-slate-800">{{ count($courses) }}</span>
                            <span class="text-slate-400 text-xs font-medium">cursos</span>
                        </div>
                    </div>

                    <div class="flex-1 w-full space-y-4">
                        {{-- En progreso --}}
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
                                    En progreso
                                </span>
                                <span>{{ $inProgressCourses->count() }} cursos</span>
                            </div>
                            <div class="pbar-track">
                                <div class="pbar-fill pbar-blue" style="width:{{ $stats['total_courses'] > 0 ? round($inProgressCourses->count() / $stats['total_courses'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- Completados --}}
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                    Completados
                                </span>
                                <span>{{ $finishedCourses->count() }} cursos</span>
                            </div>
                            <div class="pbar-track">
                                <div class="pbar-fill pbar-green" style="width:{{ $stats['total_courses'] > 0 ? round($finishedCourses->count() / $stats['total_courses'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- Sin iniciar --}}
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-slate-300 inline-block"></span>
                                    Sin iniciar
                                </span>
                                <span>{{ $notStartedCourses->count() }} cursos</span>
                            </div>
                            <div class="pbar-track">
                                <div class="pbar-fill pbar-slate" style="width:{{ $stats['total_courses'] > 0 ? round($notStartedCourses->count() / $stats['total_courses'] * 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Tasa de finalización global</span>
                            <span class="display-font text-lg font-extrabold text-blue-600">{{ $stats['completion_rate'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CURSOS EN PROGRESO ── --}}
            @if($inProgressCourses->count() > 0 || $notStartedCourses->count() > 0)
                <div class="glass-card overflow-hidden anim-fadeUp delay-6" x-data="{ tab: 'inprogress' }">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <span class="section-title">
                            <i class="fas fa-graduation-cap text-blue-500"></i>
                            Mis cursos
                        </span>
                        {{-- Filter tabs --}}
                        <div class="filter-tabs">
                            <button class="filter-tab" :class="{ 'active': tab === 'inprogress' }" @click="tab = 'inprogress'">
                                En progreso
                                @if($inProgressCourses->count() > 0)
                                    <span class="ml-1 text-[10px] font-black">{{ $inProgressCourses->count() }}</span>
                                @endif
                            </button>
                            @if($notStartedCourses->count() > 0)
                            <button class="filter-tab amber" :class="{ 'active amber': tab === 'notstarted' }" @click="tab = 'notstarted'">
                                Sin iniciar
                                <span class="ml-1 text-[10px] font-black">{{ $notStartedCourses->count() }}</span>
                            </button>
                            @endif
                        </div>
                    </div>

                    {{-- Tab: En progreso --}}
                    <div x-show="tab === 'inprogress'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="divide-y divide-slate-100">
                            @forelse($inProgressCourses as $course)
                                <div class="p-5 hover:bg-slate-50/60 transition-colors">
                                    <div class="flex gap-4">
                                        <img src="{{ $course['image_url'] }}" alt="{{ $course['title'] }}" class="course-thumb">

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2 mb-1">
                                                <h3 class="display-font text-sm font-bold text-slate-800 line-clamp-2 leading-snug">
                                                    {{ $course['title'] }}
                                                </h3>
                                                <span class="flex-shrink-0 display-font text-sm font-extrabold
                                                    {{ $course['progress'] >= 75 ? 'text-emerald-600' : ($course['progress'] >= 40 ? 'text-blue-600' : 'text-slate-500') }}">
                                                    {{ $course['progress'] }}%
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-3 mb-2.5 flex-wrap">
                                                <p class="text-xs text-slate-400 flex items-center gap-1">
                                                    <i class="fas fa-chalkboard-user text-[10px]"></i>
                                                    {{ $course['instructor'] }}
                                                </p>
                                                @if($course['last_accessed'])
                                                    <span class="last-accessed-chip">
                                                        <i class="fas fa-clock text-[9px]"></i>
                                                        {{ \Carbon\Carbon::parse($course['last_accessed'])->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="pbar-track mb-1.5">
                                                <div class="pbar-fill pbar-blue" style="width: {{ $course['progress'] }}%"></div>
                                            </div>
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-[11px] text-slate-400 font-medium">
                                                    {{ $course['completed_lessons'] }} / {{ $course['total_lessons'] }} lecciones
                                                </span>
                                                @if($course['progress'] >= 75)
                                                    <span class="badge-pill badge-green">Casi listo</span>
                                                @else
                                                    <span class="badge-pill badge-blue">En curso</span>
                                                @endif
                                            </div>

                                            <div class="flex justify-end">
                                                @if($course['last_lesson_id'])
                                                    <a href="{{ route('lesson.show', [$course['slug'], $course['last_lesson_id']]) }}" class="btn-primary">
                                                        <i class="fas fa-play text-[9px]"></i> Continuar
                                                    </a>
                                                @else
                                                    <a href="{{ route('student.course.learn', $course['id']) }}" class="btn-primary">
                                                        <i class="fas fa-play text-[9px]"></i> Continuar
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fas fa-spinner"></i>
                                    <p class="text-sm font-semibold text-slate-500">No hay cursos en progreso</p>
                                    <p class="text-xs text-slate-400 mt-1">Inicia un curso para verlo aquí.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Tab: Sin iniciar --}}
                    @if($notStartedCourses->count() > 0)
                    <div x-show="tab === 'notstarted'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="divide-y divide-slate-100">
                            @foreach($notStartedCourses as $course)
                                <div class="p-5 hover:bg-slate-50/60 transition-colors">
                                    <div class="flex gap-4">
                                        <div class="relative">
                                            <img src="{{ $course['image_url'] }}" alt="{{ $course['title'] }}" class="course-thumb opacity-75">
                                            <div class="absolute inset-0 rounded-[10px] flex items-center justify-center" style="background:rgba(15,23,42,0.35)">
                                                <i class="fas fa-lock text-white text-sm opacity-80"></i>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2 mb-1">
                                                <h3 class="display-font text-sm font-bold text-slate-700 line-clamp-2 leading-snug">
                                                    {{ $course['title'] }}
                                                </h3>
                                                <span class="badge-pill badge-gray">Sin iniciar</span>
                                            </div>
                                            <p class="text-xs text-slate-400 mb-3 flex items-center gap-1">
                                                <i class="fas fa-chalkboard-user text-[10px]"></i>
                                                {{ $course['instructor'] }}
                                                <span class="text-slate-300 mx-1">·</span>
                                                {{ $course['total_lessons'] }} lecciones
                                            </p>
                                            <div class="flex justify-end">
                                                <a href="{{ route('student.course.learn', $course['id']) }}" class="btn-primary">
                                                    <i class="fas fa-play text-[9px]"></i> Iniciar curso
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            @else
                <div class="glass-card anim-fadeUp delay-6">
                    <div class="empty-state py-12">
                        <i class="fas fa-graduation-cap"></i>
                        <p class="text-sm font-semibold text-slate-500">¡Todos tus cursos están completados!</p>
                        <p class="text-xs text-slate-400 mt-1">Explora el catálogo para seguir aprendiendo.</p>
                    </div>
                </div>
            @endif

            {{-- ── CURSOS FINALIZADOS ── --}}
            @if($finishedCourses->count() > 0)
                <div class="glass-card overflow-hidden anim-fadeUp delay-7">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <span class="section-title">
                            <i class="fas fa-check-double text-emerald-500"></i>
                            Cursos finalizados
                            <span class="text-[11px] font-bold bg-emerald-100 text-emerald-700 rounded-full px-2 py-0.5 ml-1">{{ $finishedCourses->count() }}</span>
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach($finishedCourses as $course)
                            <div class="p-5 hover:bg-slate-50/60 transition-colors">
                                <div class="flex gap-4">
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $course['image_url'] }}" alt="{{ $course['title'] }}" class="course-thumb">
                                        <div class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center shadow-sm">
                                            <i class="fas fa-check text-white" style="font-size:8px"></i>
                                        </div>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h3 class="display-font text-sm font-bold text-slate-800 line-clamp-2 leading-snug mb-1">
                                            {{ $course['title'] }}
                                        </h3>

                                        <div class="flex items-center gap-3 mb-2.5 flex-wrap">
                                            <p class="text-xs text-slate-400 flex items-center gap-1">
                                                <i class="fas fa-chalkboard-user text-[10px]"></i>
                                                {{ $course['instructor'] }}
                                            </p>
                                            @if($course['last_accessed'])
                                                <span class="last-accessed-chip">
                                                    <i class="fas fa-calendar-check text-[9px]"></i>
                                                    Completado {{ \Carbon\Carbon::parse($course['last_accessed'])->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="pbar-track mb-1.5">
                                            <div class="pbar-fill pbar-green" style="width:100%"></div>
                                        </div>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-[11px] text-emerald-600 font-semibold">
                                                <i class="fas fa-circle-check mr-0.5 text-[10px]"></i>
                                                100% · {{ $course['total_lessons'] }} lecciones
                                            </span>
                                            @if($course['certificate_id'])
                                                <span class="badge-pill badge-green"><i class="fas fa-award text-[9px]"></i> Certificado</span>
                                            @elseif($course['has_exam'] && !$course['has_passed_exam'])
                                                <span class="badge-pill badge-amber"><i class="fas fa-circle-exclamation text-[9px]"></i> Examen pendiente</span>
                                            @elseif($course['has_exam'] && $course['has_passed_exam'])
                                                <span class="badge-pill badge-blue"><i class="fas fa-star text-[9px]"></i> Examen aprobado</span>
                                            @else
                                                <span class="badge-pill badge-gray">Sin examen</span>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap gap-2 justify-end">
                                            <a href="{{ route('student.course.learn', $course['id']) }}" class="btn-secondary">
                                                <i class="fas fa-redo text-[9px]"></i> Repasar
                                            </a>
                                            @if($course['certificate_id'])
                                                <a href="{{ url('/certificate/' . $course['certificate_id']) }}" class="btn-emerald">
                                                    <i class="fas fa-certificate text-[9px]"></i> Ver certificado
                                                </a>
                                            @elseif($course['has_exam'] && !$course['has_passed_exam'])
                                                <a href="{{ url('/exams/' . $course['exam_id']) }}" class="btn-amber">
                                                    <i class="fas fa-file-lines text-[9px]"></i> Ir al examen
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>{{-- /left column --}}

        {{-- ──────────────────────────────────────
            RIGHT COLUMN (xl:col-span-1)
        ────────────────────────────────────── --}}
        <div class="xl:col-span-1 space-y-5">

            {{-- ── Streak Card ── --}}
            <div class="anim-scale delay-5" style="background:linear-gradient(135deg,#1e3a5f,#0f172a);border-radius:18px;padding:1.25rem 1.5rem;border:1px solid rgba(255,255,255,0.07);box-shadow:0 8px 28px -8px rgba(15,23,42,0.3)">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-blue-300 text-xs font-bold uppercase tracking-widest">Racha de estudio</span>
                    <span class="flame text-2xl"><i class="fas fa-fire text-amber-400"></i></span>
                </div>
                <div class="flex items-end gap-2 mb-3">
                    <span class="display-font text-5xl font-extrabold text-white">{{ $stats['streak_days'] }}</span>
                    <span class="text-blue-300 text-sm font-medium mb-2">días</span>
                </div>
                <p class="text-blue-200 text-xs">
                    @if($stats['streak_days'] >= 7)
                        🔥 ¡Increíble! Llevas más de una semana sin parar.
                    @elseif($stats['streak_days'] >= 3)
                        💪 Buen ritmo — ¡sigue así!
                    @elseif($stats['streak_days'] >= 1)
                        ⚡ ¡Bien! No pierdas el ritmo hoy.
                    @else
                        🎯 Estudia hoy para arrancar tu racha.
                    @endif
                </p>
            </div>

            {{-- ── Actividad reciente (lista scrollable) ── --}}
            <div class="glass-card overflow-hidden anim-scale delay-6">

                {{-- Header --}}
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                    <span class="section-title" style="font-size:.9rem">
                        <i class="fas fa-bolt text-yellow-400"></i>
                        Actividad reciente
                    </span>
                    @if($recentActivity->count() > 0)
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 rounded-full px-2 py-0.5">
                            {{ $recentActivity->count() }} acciones
                        </span>
                    @endif
                </div>

                @if($recentActivity->count() > 0)

                    {{-- Pills resumen --}}
                    @php
                        $lCount = $recentActivity->where('type', 'lesson_completed')->count();
                        $eCount = $recentActivity->whereIn('type', ['exam_completed','exam_started'])->count();
                        $cCount = $recentActivity->where('type', 'certificate_earned')->count();
                    @endphp
                    <div class="px-4 pt-3 pb-2 flex gap-2 bg-white border-b border-slate-100">
                        <div class="flex-1 text-center p-1.5 rounded-lg bg-blue-50">
                            <p class="display-font text-sm font-extrabold text-blue-600">{{ $lCount }}</p>
                            <p class="text-[9px] text-slate-400 font-semibold">Lecciones</p>
                        </div>
                        <div class="flex-1 text-center p-1.5 rounded-lg bg-orange-50">
                            <p class="display-font text-sm font-extrabold text-orange-500">{{ $eCount }}</p>
                            <p class="text-[9px] text-slate-400 font-semibold">Exámenes</p>
                        </div>
                        <div class="flex-1 text-center p-1.5 rounded-lg bg-emerald-50">
                            <p class="display-font text-sm font-extrabold text-emerald-600">{{ $cCount }}</p>
                            <p class="text-[9px] text-slate-400 font-semibold">Certificados</p>
                        </div>
                    </div>

                    {{-- Lista con scroll --}}
                    <div class="activity-scroll-list overflow-y-auto" style="max-height: 340px;">
                        @foreach($recentActivity->take(10) as $loop_i => $activity)
                            @php
                                $rawData   = $activity->data ?? $activity->details ?? '{}';
                                $data      = is_array($rawData) ? $rawData : (json_decode(is_string($rawData) ? $rawData : '{}', true) ?? []);

                                $typeMap = [
                                    'lesson_completed'   => ['icon' => 'fas fa-circle-play',  'bg' => '#eff6ff', 'color' => '#3b82f6', 'label' => 'Lección'],
                                    'document_completed' => ['icon' => 'fas fa-file-pdf',     'bg' => '#fef2f2', 'color' => '#ef4444', 'label' => 'Documento'],
                                    'exam_completed'     => ['icon' => 'fas fa-file-lines',   'bg' => '#fff7ed', 'color' => '#f97316', 'label' => 'Examen'],
                                    'exam_started'       => ['icon' => 'fas fa-pencil',       'bg' => '#fefce8', 'color' => '#eab308', 'label' => 'Examen'],
                                    'certificate_earned' => ['icon' => 'fas fa-certificate',  'bg' => '#f0fdf4', 'color' => '#10b981', 'label' => 'Certificado'],
                                    'course_enrolled'    => ['icon' => 'fas fa-user-plus',    'bg' => '#faf5ff', 'color' => '#8b5cf6', 'label' => 'Inscripción'],
                                    'course_accessed'    => ['icon' => 'fas fa-book-open',    'bg' => '#eef2ff', 'color' => '#6366f1', 'label' => 'Acceso'],
                                ];
                                $meta       = $typeMap[$activity->type] ?? ['icon' => 'fas fa-circle', 'bg' => '#f8fafc', 'color' => '#94a3b8', 'label' => 'Actividad'];
                                $mainTitle  = $data['lesson_title'] ?? $data['document_title'] ?? $data['exam_title'] ?? $data['course_title'] ?? $activity->description ?? 'Contenido';
                                $courseTitle= $data['course_title'] ?? '';
                                $score      = $data['score'] ?? null;
                                $passed     = $data['passed'] ?? null;
                            @endphp

                            <div class="activity-row flex items-start gap-3 px-4 py-3 border-b border-slate-50 hover:bg-slate-50/70 transition-colors {{ $loop_i === 0 ? 'bg-blue-50/30' : '' }}">

                                {{-- Ícono --}}
                                <div class="activity-icon flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center mt-0.5"
                                     style="background:{{ $meta['bg'] }}; color:{{ $meta['color'] }}">
                                    <i class="{{ $meta['icon'] }}" style="font-size:11px"></i>
                                </div>

                                {{-- Contenido --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <span class="activity-type-label text-[9px] font-extrabold uppercase tracking-widest"
                                              style="color:{{ $meta['color'] }}">
                                            {{ $meta['label'] }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 whitespace-nowrap flex-shrink-0">
                                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                        </span>
                                    </div>

                                    <p class="text-xs font-semibold text-slate-700 leading-snug line-clamp-2">
                                        {{ $mainTitle }}
                                    </p>

                                    @if($courseTitle && $courseTitle !== $mainTitle)
                                        <p class="text-[11px] text-blue-500 font-medium mt-0.5 truncate">
                                            <i class="fas fa-graduation-cap text-[9px] mr-0.5"></i>{{ $courseTitle }}
                                        </p>
                                    @endif

                                    @if($score !== null)
                                        <span class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold rounded-full px-2 py-0.5
                                            {{ $passed ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                            <i class="fas fa-star" style="font-size:8px"></i>
                                            {{ $score }} pts — {{ $passed ? 'Aprobado' : 'No aprobado' }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Número de orden (sutil) --}}
                                <span class="flex-shrink-0 text-[9px] font-bold text-slate-200 mt-1 select-none">
                                    #{{ $loop_i + 1 }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Fade-out bottom indicator --}}
                    <div class="scroll-fade-hint px-4 py-2 text-center border-t border-slate-100 bg-white">
                        <span class="text-[10px] text-slate-400 font-medium flex items-center justify-center gap-1">
                            <i class="fas fa-angles-down text-[8px] text-slate-300"></i>
                            Scroll para ver más
                            <i class="fas fa-angles-down text-[8px] text-slate-300"></i>
                        </span>
                    </div>

                @else
                    <div class="empty-state py-10">
                        <i class="fas fa-clock-rotate-left"></i>
                        <p class="text-sm font-semibold text-slate-500">Sin actividad aún</p>
                        <p class="text-xs text-slate-400 mt-1">Completa una lección para verla aquí.</p>
                    </div>
                @endif
            </div>

            {{-- ── Certificados ── --}}
            @php $certsCount = collect($courses)->filter(fn($c) => $c['certificate_id'])->count(); @endphp
            @if($certsCount > 0)
                <div class="glass-card p-5 anim-scale delay-7">
                    <div class="section-header mb-4">
                        <span class="section-title" style="font-size:.9rem">
                            <i class="fas fa-award text-amber-500"></i>
                            Mis certificados
                        </span>
                        <span class="text-[11px] font-bold bg-amber-100 text-amber-700 rounded-full px-2 py-0.5">{{ $certsCount }}</span>
                    </div>
                    <div class="space-y-2">
                        @foreach(collect($courses)->filter(fn($c) => $c['certificate_id'])->take(3) as $c)
                            <a href="{{ url('/certificate/' . $c['certificate_id']) }}"
                            class="flex items-center gap-3 p-2.5 rounded-xl bg-amber-50 border border-amber-100 hover:border-amber-300 hover:bg-amber-100/60 transition-all group">
                                <div class="w-8 h-8 bg-amber-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-certificate text-white text-xs"></i>
                                </div>
                                <p class="text-xs font-semibold text-slate-700 line-clamp-2 flex-1 leading-snug group-hover:text-amber-700 transition-colors">
                                    {{ $c['title'] }}
                                </p>
                                <i class="fas fa-arrow-right text-amber-400 text-[10px] flex-shrink-0"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif



        </div>{{-- /right column --}}
    </div>{{-- /main grid --}}
</div>{{-- /progress-page --}}

<script>
    function progressRing() {
        return {
            targetRate: {{ $stats['completion_rate'] ?? 0 }},
            displayRate: 0,
            ringOffset: 364.4,

            initRing() {
                setTimeout(() => {
                    const rate = this.targetRate;
                    this.ringOffset = 364.4 - (364.4 * rate / 100);
                    let cur = 0;
                    const step = Math.max(rate / 50, 0.5);
                    const iv = setInterval(() => {
                        cur = Math.min(cur + step, rate);
                        this.displayRate = Math.round(cur);
                        if (cur >= rate) clearInterval(iv);
                    }, 18);
                }, 450);
            }
        };
    }

    window.addEventListener('load', function () {
        buildDonutChart();
    });

    function safeDestroyChart(id) {
        try { const c = Chart.getChart(id); if (c) c.destroy(); } catch(e) {}
    }

    function buildDonutChart() {
        safeDestroyChart('donutChart');
        const canvas = document.getElementById('donutChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');

        // CORRECCIÓN: usamos $completedCourses directamente (mismo valor que $finishedCourses)
        const inProgress = {{ $inProgressCourses->count() }};
        const completed  = {{ $completedCourses->count() }};
        const notStarted = {{ $notStartedCourses->count() }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['En progreso', 'Completados', 'Sin iniciar'],
                datasets: [{
                    data: [inProgress, completed, notStarted],
                    backgroundColor: ['#3b82f6', '#10b981', '#e2e8f0'],
                    borderColor:     ['#fff', '#fff', '#fff'],
                    borderWidth: 3,
                    hoverOffset: 6,
                }]
            },
            options: {
                cutout: '72%',
                responsive: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (item) => ` ${item.label}: ${item.raw} curso${item.raw !== 1 ? 's' : ''}`
                        }
                    }
                },
                animation: { animateRotate: true, duration: 1000, easing: 'easeInOutQuart' },
            }
        });
    }


</script>

<style>
    /* ── Base ── */
    /* .progress-page * { font-family: 'DM Sans', sans-serif; }
    .progress-page .display-font { font-family: 'Plus Jakarta Sans', sans-serif; } */

    /* ── Animations ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.92); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes countUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes flamePulse {
        0%,100% { transform: scale(1) rotate(-3deg); }
        50%      { transform: scale(1.15) rotate(3deg); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }
    @keyframes pulseGlow {
        0%,100% { box-shadow: 0 0 0 0 rgba(59,130,246,0.25); }
        50%      { box-shadow: 0 0 0 8px rgba(59,130,246,0); }
    }

    .anim-fadeUp { animation: fadeUp 0.55s ease both; }
    .anim-fadeIn { animation: fadeIn 0.5s ease both; }
    .anim-scale  { animation: scaleIn 0.5s cubic-bezier(.34,1.56,.64,1) both; }

    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.12s; }
    .delay-3 { animation-delay: 0.19s; }
    .delay-4 { animation-delay: 0.26s; }
    .delay-5 { animation-delay: 0.33s; }
    .delay-6 { animation-delay: 0.40s; }
    .delay-7 { animation-delay: 0.47s; }
    .delay-8 { animation-delay: 0.54s; }

    /* ── Hero banner ── */
    .hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #0f3460 100%);
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 80% 50%, rgba(59,130,246,0.18) 0%, transparent 70%),
            radial-gradient(ellipse 40% 40% at 20% 80%, rgba(245,158,11,0.10) 0%, transparent 60%);
    }
    .hero-dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
        background-size: 28px 28px;
    }

    /* ── Stat cards ── */
    .stat-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8edf5;
        padding: 1.25rem 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px -8px rgba(15,23,42,0.12);
    }
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 0 0 16px 16px;
    }
    .stat-card.blue::after   { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .stat-card.green::after  { background: linear-gradient(90deg, #10b981, #34d399); }
    .stat-card.amber::after  { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .stat-card.purple::after { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .stat-icon.blue   { background: #eff6ff; color: #3b82f6; }
    .stat-icon.green  { background: #ecfdf5; color: #10b981; }
    .stat-icon.amber  { background: #fffbeb; color: #f59e0b; }
    .stat-icon.purple { background: #f5f3ff; color: #8b5cf6; }

    .stat-number {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        color: #0f172a;
        animation: countUp 0.6s ease both;
    }

    /* ── SVG Progress Ring ── */
    .progress-ring circle.track { fill: none; stroke: rgba(255,255,255,0.08); }
    .progress-ring circle.fill  {
        fill: none;
        stroke-linecap: round;
        transition: stroke-dashoffset 1.4s cubic-bezier(0.34, 1.1, 0.64, 1);
    }

    /* ── Glass card ── */
    .glass-card {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(12px);
        border-radius: 18px;
        border: 1px solid #e8edf5;
        box-shadow: 0 4px 24px -6px rgba(15,23,42,0.07);
    }

    /* ── "Retomar" featured card ── */
    .resume-card {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 50%, #3b82f6 100%);
        border-radius: 18px;
        overflow: hidden;
        position: relative;
    }
    .resume-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 70% 60% at 90% 30%, rgba(255,255,255,0.12) 0%, transparent 60%);
    }
    .resume-card-thumb {
        width: 72px; height: 72px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid rgba(255,255,255,0.3);
        flex-shrink: 0;
    }

    /* ── Course cards ── */
    .course-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e8edf5;
        overflow: hidden;
        transition: box-shadow 0.25s ease, transform 0.25s ease;
    }
    .course-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px -8px rgba(15,23,42,0.13);
    }
    .course-thumb {
        width: 76px; height: 76px;
        object-fit: cover;
        border-radius: 10px;
        flex-shrink: 0;
    }

    /* ── Progress bar ── */
    .pbar-track {
        height: 6px;
        background: #f1f5f9;
        border-radius: 999px;
        overflow: hidden;
    }
    .pbar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 1.2s cubic-bezier(0.34,1.1,0.64,1);
    }
    .pbar-blue   { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .pbar-green  { background: linear-gradient(90deg, #10b981, #34d399); }
    .pbar-slate  { background: #cbd5e1; }

    /* ── Buttons ── */
    .btn-primary {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #fff; font-weight: 600; font-size: 0.75rem;
        padding: 7px 14px; border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(59,130,246,0.3);
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 4px 12px rgba(59,130,246,0.4);
        transform: translateY(-1px); color: #fff;
    }
    .btn-white {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        color: #fff; font-weight: 700; font-size: 0.8rem;
        padding: 8px 18px; border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.3);
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-white:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-1px); color: #fff;
    }
    .btn-secondary {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f8fafc; color: #475569; font-weight: 600; font-size: 0.75rem;
        padding: 7px 14px; border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease; text-decoration: none;
    }
    .btn-secondary:hover { background: #f1f5f9; color: #334155; transform: translateY(-1px); }
    .btn-emerald {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff; font-weight: 600; font-size: 0.75rem;
        padding: 7px 14px; border-radius: 8px;
        transition: all 0.2s ease; text-decoration: none;
        box-shadow: 0 2px 8px rgba(16,185,129,0.3);
    }
    .btn-emerald:hover { background: linear-gradient(135deg, #059669, #047857); transform: translateY(-1px); color: #fff; }
    .btn-amber {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff; font-weight: 600; font-size: 0.75rem;
        padding: 7px 14px; border-radius: 8px;
        transition: all 0.2s ease; text-decoration: none;
        box-shadow: 0 2px 8px rgba(245,158,11,0.3);
    }
    .btn-amber:hover { background: linear-gradient(135deg, #d97706, #b45309); transform: translateY(-1px); color: #fff; }

    /* ── Badges ── */
    .badge-pill {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.65rem; font-weight: 700;
        padding: 3px 8px; border-radius: 999px;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .badge-green  { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-amber  { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-blue   { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge-gray   { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
    .badge-purple { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }

    /* ── Section headers ── */
    .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
    .section-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1rem; font-weight: 700; color: #0f172a;
        display: flex; align-items: center; gap: 8px;
    }

    /* ── Activity timeline ── */
    .timeline-line {
        position: absolute; left: 13px; top: 8px; bottom: 0;
        width: 1px;
        background: linear-gradient(to bottom, #e2e8f0 80%, transparent);
    }
    .timeline-dot {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        position: relative; z-index: 1;
        font-size: 10px; flex-shrink: 0;
        box-shadow: 0 0 0 2px #fff, 0 1px 4px rgba(0,0,0,0.1);
    }

    /* ── Donut chart ── */
    .donut-wrap { position: relative; width: 140px; height: 140px; flex-shrink: 0; }
    .donut-center {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        pointer-events: none;
    }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 2.5rem 1rem; color: #94a3b8; }
    .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; opacity: 0.35; }

    /* ── Flame ── */
    /* .flame { display: inline-block; animation: flamePulse 1.6s ease-in-out infinite; } */

    /* ── Filter tabs ── */
    .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .filter-tab {
        padding: 5px 14px; border-radius: 999px;
        font-size: 0.7rem; font-weight: 700;
        border: 1.5px solid #e2e8f0;
        cursor: pointer; transition: all 0.18s ease;
        text-transform: uppercase; letter-spacing: 0.04em;
        background: #fff; color: #64748b;
    }
    .filter-tab:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }
    .filter-tab.active { background: #3b82f6; color: #fff; border-color: #3b82f6; }
    .filter-tab.green.active { background: #10b981; border-color: #10b981; }
    .filter-tab.amber.active { background: #f59e0b; border-color: #f59e0b; }

    /* ── Section divider ── */
    .section-divider {
        display: flex; align-items: center; gap: 10px;
        margin: 0.75rem 0;
    }
    .section-divider span {
        font-size: 0.65rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: #94a3b8; white-space: nowrap;
    }
    .section-divider::before, .section-divider::after {
        content: ''; flex: 1; height: 1px; background: #f1f5f9;
    }

    /* ── Last accessed chip ── */
    .last-accessed-chip {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.65rem; color: #94a3b8; font-weight: 500;
    }

    /* ── Activity scrollable list ── */
    .activity-scroll-list {
        scrollbar-width: thin;
        scrollbar-color: #e2e8f0 transparent;
    }
    .activity-scroll-list::-webkit-scrollbar {
        width: 4px;
    }
    .activity-scroll-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .activity-scroll-list::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 999px;
    }
    .activity-scroll-list::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
    .activity-row:last-child {
        border-bottom: none;
    }
    .activity-icon {
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .scroll-fade-hint {
        background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, #fff 60%);
    }
</style>
@endsection