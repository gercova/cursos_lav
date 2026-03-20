@extends('layouts.student')
@section('title', 'Mi Progreso')
@section('styles')
<style>
    /* ── Base ── */
    .progress-page * { font-family: 'DM Sans', sans-serif; }
    .progress-page .display-font { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Entrance animations ── */
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
    @keyframes progressFill {
        from { stroke-dashoffset: var(--full); }
        to   { stroke-dashoffset: var(--offset); }
    }
    @keyframes barGrow {
        from { height: 0; }
        to   { height: var(--h); }
    }
    @keyframes countUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulseRing {
        0%,100% { opacity: 0.6; transform: scale(1); }
        50%      { opacity: 1;   transform: scale(1.05); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
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
        cursor: default;
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

    /* ── Glassmorphism chart card ── */
    .glass-card {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(12px);
        border-radius: 18px;
        border: 1px solid #e8edf5;
        box-shadow: 0 4px 24px -6px rgba(15,23,42,0.07);
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
        width: 80px; height: 80px;
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
        transform: translateY(-1px);
        color: #fff;
    }
    .btn-secondary {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f8fafc; color: #475569; font-weight: 600; font-size: 0.75rem;
        padding: 7px 14px; border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background: #f1f5f9; color: #334155;
        transform: translateY(-1px);
    }
    .btn-emerald {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff; font-weight: 600; font-size: 0.75rem;
        padding: 7px 14px; border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(16,185,129,0.3);
    }
    .btn-emerald:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        color: #fff;
    }
    .btn-amber {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff; font-weight: 600; font-size: 0.75rem;
        padding: 7px 14px; border-radius: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(245,158,11,0.3);
    }
    .btn-amber:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        transform: translateY(-1px);
        color: #fff;
    }

    /* ── Status badges ── */
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

    /* ── Activity timeline ── */
    .timeline-line {
        position: absolute;
        left: 13px; top: 8px; bottom: 0;
        width: 1px;
        background: linear-gradient(to bottom, #e2e8f0 80%, transparent);
    }
    .timeline-dot {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        position: relative; z-index: 1;
        font-size: 10px;
        flex-shrink: 0;
        ring: 2px solid #fff;
        box-shadow: 0 0 0 2px #fff, 0 1px 4px rgba(0,0,0,0.1);
    }

    /* ── Section headers ── */
    .section-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1rem;
    }
    .section-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1rem; font-weight: 700; color: #0f172a;
        display: flex; align-items: center; gap: 8px;
    }

    /* ── Donut chart wrapper ── */
    .donut-wrap { position: relative; width: 140px; height: 140px; flex-shrink: 0; }
    .donut-center {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        pointer-events: none;
    }

    /* ── Weekly bar mini-chart ── */
    .bar-chart-wrap {
        display: flex; align-items: flex-end;
        gap: 6px; height: 80px;
    }
    .bar-col {
        display: flex; flex-direction: column;
        align-items: center; gap: 4px;
        flex: 1;
    }
    .bar-fill {
        width: 100%; border-radius: 4px 4px 0 0;
        background: linear-gradient(to top, #3b82f6, #60a5fa);
        min-height: 4px;
        animation: barGrow 0.8s cubic-bezier(0.34,1.1,0.64,1) both;
    }
    .bar-label {
        font-size: 9px; color: #94a3b8; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.04em;
    }

    /* ── Streak flame animation ── */
    @keyframes flamePulse {
        0%,100% { transform: scale(1) rotate(-3deg); }
        50%      { transform: scale(1.15) rotate(3deg); }
    }
    .flame { display: inline-block; animation: flamePulse 1.6s ease-in-out infinite; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center; padding: 2.5rem 1rem;
        color: #94a3b8;
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; opacity: 0.35; }

    /* ── Shimmer loader placeholder ── */
    .shimmer {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% auto;
        animation: shimmer 1.4s linear infinite;
        border-radius: 6px;
    }
</style>
@endsection
@section('content')
{{-- Alpine solo maneja la animación del ring SVG del hero --}}
<div class="progress-page" x-data="progressRing()" x-init="initRing()">

    {{-- ═══════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════ --}}
    <div class="hero-banner mb-6 anim-fadeUp delay-1">
        <div class="hero-dots"></div>
        <div class="relative z-10 p-6 sm:p-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center">

                {{-- Greeting + headline --}}
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold text-blue-300 uppercase tracking-widest">Panel de aprendizaje</span>
                    </div>
                    <h1 class="display-font text-2xl sm:text-3xl font-extrabold text-white leading-tight mb-1">
                        ¡Hola, {{ explode(' ', auth()->user()->names)[0] }}! 👋
                    </h1>
                    <p class="text-blue-200 text-sm font-light max-w-lg">
                        Aquí tienes un resumen completo de tu aprendizaje. Sigue así — cada lección te acerca más a tu meta.
                    </p>

                    {{-- Quick stats row --}}
                    <div class="flex flex-wrap gap-4 mt-5">
                        <div class="flex items-center gap-2">
                            <span class="flame text-yellow-400 text-lg"><i class="fas fa-fire"></i></span>
                            <div>
                                <p class="text-white font-extrabold display-font text-xl leading-none">{{ $stats['streak_days'] }}</p>
                                <p class="text-blue-300 text-[11px] font-medium">días de racha</p>
                            </div>
                        </div>
                        <div class="w-px bg-white/10 self-stretch hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 text-lg"><i class="fas fa-check-circle"></i></span>
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

                {{-- Overall progress ring --}}
                <div class="flex flex-col items-center gap-2 lg:pr-4">
                    <div class="donut-wrap">
                        <svg viewBox="0 0 140 140" width="140" height="140" class="progress-ring" style="transform:rotate(-90deg)">
                            <circle class="track" cx="70" cy="70" r="58" stroke-width="10"/>
                            <circle
                                class="fill"
                                cx="70" cy="70" r="58"
                                stroke-width="10"
                                stroke="#3b82f6"
                                stroke-dasharray="364.4"
                                :stroke-dashoffset="ringOffset"
                                style="transition: stroke-dashoffset 1.4s cubic-bezier(0.34,1.1,0.64,1)"
                            />
                        </svg>
                        <div class="donut-center" style="transform: rotate(0deg)">
                            <span class="display-font text-3xl font-extrabold text-white" x-text="displayRate + '%'"></span>
                            <span class="text-blue-300 text-[11px] font-medium mt-0.5">completado</span>
                        </div>
                    </div>
                    <span class="text-blue-200 text-xs font-medium text-center">Tasa de finalización global</span>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         STAT CARDS ROW
    ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        <div class="stat-card blue anim-fadeUp delay-2">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon blue"><i class="fas fa-book-open"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Inscritos</span>
            </div>
            <div class="stat-number" style="animation-delay: 0.2s">{{ $stats['total_courses'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">cursos en total</p>
        </div>

        <div class="stat-card green anim-fadeUp delay-3">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon green"><i class="fas fa-trophy"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Completados</span>
            </div>
            <div class="stat-number" style="animation-delay: 0.27s">{{ $stats['completed_courses'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">de {{ $stats['total_courses'] }} cursos</p>
        </div>

        <div class="stat-card purple anim-fadeUp delay-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon purple"><i class="fas fa-clock"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Estudio</span>
            </div>
            <div class="stat-number" style="animation-delay: 0.34s">{{ $stats['total_study_hours'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">horas acumuladas</p>
        </div>

        <div class="stat-card amber anim-fadeUp delay-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="stat-icon amber"><i class="fas fa-fire"></i></div>
                <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Racha</span>
            </div>
            <div class="stat-number" style="animation-delay: 0.41s">{{ $stats['streak_days'] }}</div>
            <p class="text-slate-400 text-xs mt-1 font-medium">días consecutivos</p>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════
         MAIN GRID
    ═══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ─────────────────────────────────────
             LEFT COLUMN  (xl:col-span-2)
        ───────────────────────────────────── --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- ── Progress Overview Card ── --}}
            @php
                $inProgressCourses = collect($courses)->where('progress', '<', 100)->where('progress', '>', 0)->values();
                $notStarted        = collect($courses)->where('progress', 0)->values();
                $finishedCourses   = collect($courses)->where('progress', 100)->values();
                $allActive         = collect($courses)->where('progress', '<', 100)->values();
            @endphp

            <div class="glass-card p-6 anim-fadeUp delay-5">
                <div class="section-header">
                    <span class="section-title">
                        <i class="fas fa-chart-pie text-blue-500"></i>
                        Resumen de progreso
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-8">
                    {{-- Donut chart (Chart.js) --}}
                    <div style="width:180px;height:180px;flex-shrink:0;position:relative">
                        <canvas id="donutChart" width="180" height="180"></canvas>
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none">
                            <span class="display-font text-2xl font-extrabold text-slate-800">{{ count($courses) }}</span>
                            <span class="text-slate-400 text-xs font-medium">cursos</span>
                        </div>
                    </div>

                    {{-- Legend + mini breakdown --}}
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
                                <div class="pbar-fill pbar-blue" style="width: {{ $stats['total_courses'] > 0 ? round($inProgressCourses->count() / $stats['total_courses'] * 100) : 0 }}%"></div>
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
                                <div class="pbar-fill pbar-green" style="width: {{ $stats['total_courses'] > 0 ? round($finishedCourses->count() / $stats['total_courses'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        {{-- Sin iniciar --}}
                        <div>
                            <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1.5">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-slate-300 inline-block"></span>
                                    Sin iniciar
                                </span>
                                <span>{{ $notStarted->count() }} cursos</span>
                            </div>
                            <div class="pbar-track">
                                <div class="pbar-fill" style="width: {{ $stats['total_courses'] > 0 ? round($notStarted->count() / $stats['total_courses'] * 100) : 0 }}%; background: #cbd5e1;"></div>
                            </div>
                        </div>

                        {{-- Tasa global --}}
                        <div class="mt-2 flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Tasa de finalización global</span>
                            <span class="display-font text-lg font-extrabold text-blue-600">{{ $stats['completion_rate'] }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Cursos En Progreso ── --}}
            <div class="glass-card overflow-hidden anim-fadeUp delay-6">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <span class="section-title">
                        <i class="fas fa-spinner text-blue-500"></i>
                        Cursos en progreso
                        @if($allActive->count() > 0)
                            <span class="text-[11px] font-bold bg-blue-100 text-blue-700 rounded-full px-2 py-0.5 ml-1">{{ $allActive->count() }}</span>
                        @endif
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($allActive as $i => $course)
                        <div class="course-card rounded-none border-0 border-b border-slate-100 last:border-0 p-5 hover:bg-slate-50/60 transition-colors">
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

                                    <p class="text-xs text-slate-400 mb-3 flex items-center gap-1">
                                        <i class="fas fa-chalkboard-teacher text-[10px]"></i>
                                        {{ $course['instructor'] }}
                                    </p>

                                    {{-- Progress bar --}}
                                    <div class="pbar-track mb-1.5">
                                        <div class="pbar-fill {{ $course['progress'] == 0 ? '' : 'pbar-blue' }}"
                                             style="width: {{ $course['progress'] }}%; {{ $course['progress'] == 0 ? 'background:#cbd5e1' : '' }}"></div>
                                    </div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[11px] text-slate-400 font-medium">
                                            {{ $course['completed_lessons'] }} / {{ $course['total_lessons'] }} lecciones
                                        </span>
                                        @if($course['progress'] == 0)
                                            <span class="badge-pill badge-gray">Sin iniciar</span>
                                        @elseif($course['progress'] >= 75)
                                            <span class="badge-pill badge-green">Casi listo</span>
                                        @else
                                            <span class="badge-pill badge-blue">En curso</span>
                                        @endif
                                    </div>

                                    <div class="flex justify-end">
                                        @if($course['progress'] == 0)
                                            <a href="{{ route('student.course.learn', $course['id']) }}" class="btn-primary">
                                                <i class="fas fa-play text-[9px]"></i> Iniciar curso
                                            </a>
                                        @elseif($course['last_lesson_id'])
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
                            <i class="fas fa-graduation-cap"></i>
                            <p class="text-sm font-semibold text-slate-500">¡Todos tus cursos están completados!</p>
                            <p class="text-xs text-slate-400 mt-1">Explora el catálogo para seguir aprendiendo.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ── Cursos Finalizados ── --}}
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
                                {{-- Thumb con badge --}}
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
                                    <p class="text-xs text-slate-400 mb-3 flex items-center gap-1">
                                        <i class="fas fa-chalkboard-teacher text-[10px]"></i>
                                        {{ $course['instructor'] }}
                                    </p>

                                    {{-- Barra verde completa --}}
                                    <div class="pbar-track mb-1.5">
                                        <div class="pbar-fill pbar-green" style="width:100%"></div>
                                    </div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[11px] text-emerald-600 font-semibold">
                                            <i class="fas fa-check-circle mr-0.5 text-[10px]"></i> 100% completado · {{ $course['total_lessons'] }} lecciones
                                        </span>
                                        {{-- Badge de estado --}}
                                        @if($course['certificate_id'])
                                            <span class="badge-pill badge-green"><i class="fas fa-award text-[9px]"></i> Certificado</span>
                                        @elseif($course['has_exam'] && !$course['has_passed_exam'])
                                            <span class="badge-pill badge-amber"><i class="fas fa-exclamation-circle text-[9px]"></i> Examen pendiente</span>
                                        @elseif($course['has_exam'] && $course['has_passed_exam'])
                                            <span class="badge-pill badge-blue"><i class="fas fa-star text-[9px]"></i> Examen aprobado</span>
                                        @else
                                            <span class="badge-pill badge-gray">Sin examen</span>
                                        @endif
                                    </div>

                                    {{-- Botones --}}
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
                                                <i class="fas fa-file-alt text-[9px]"></i> Ir al examen
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

        </div>

        {{-- ─────────────────────────────────────
             RIGHT COLUMN (xl:col-span-1)
        ───────────────────────────────────── --}}
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

            {{-- ── Gráfico de actividad semanal ── --}}
            <div class="glass-card p-5 anim-scale delay-6">
                <div class="section-header mb-4">
                    <span class="section-title" style="font-size:0.9rem">
                        <i class="fas fa-chart-bar text-blue-500"></i>
                        Actividad semanal
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">últimos 7 días</span>
                </div>
                <canvas id="weeklyChart" height="110"></canvas>
                <p class="text-center text-[10px] text-slate-400 mt-2 font-medium">Lecciones completadas por día</p>
            </div>

            {{-- ── Certificates quick-access ── --}}
            @php $certsCount = collect($courses)->filter(fn($c) => $c['certificate_id'])->count(); @endphp
            @if($certsCount > 0)
            <div class="glass-card p-5 anim-scale delay-7">
                <div class="section-header mb-4">
                    <span class="section-title" style="font-size:0.9rem">
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

            {{-- ── Actividad reciente (timeline) ── --}}
            <div class="glass-card overflow-hidden anim-scale delay-8">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <span class="section-title" style="font-size:0.9rem">
                        <i class="fas fa-bolt text-yellow-400"></i>
                        Actividad reciente
                    </span>
                    @if($recentActivity->count() > 0)
                        <span class="text-[10px] text-slate-400 font-semibold">{{ $recentActivity->count() }} registros</span>
                    @endif
                </div>

                {{-- Summary pills --}}
                @if($recentActivity->count() > 0)
                @php
                    $lCount = $recentActivity->where('type', 'lesson_completed')->count();
                    $eCount = $recentActivity->whereIn('type', ['exam_completed','exam_started'])->count();
                    $cCount = $recentActivity->where('type', 'certificate_earned')->count();
                @endphp
                <div class="px-5 py-3 border-b border-slate-100 flex gap-2 bg-white">
                    <div class="flex-1 text-center p-2 rounded-lg bg-blue-50">
                        <p class="display-font text-base font-extrabold text-blue-600">{{ $lCount }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">Lecciones</p>
                    </div>
                    <div class="flex-1 text-center p-2 rounded-lg bg-orange-50">
                        <p class="display-font text-base font-extrabold text-orange-500">{{ $eCount }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">Exámenes</p>
                    </div>
                    <div class="flex-1 text-center p-2 rounded-lg bg-emerald-50">
                        <p class="display-font text-base font-extrabold text-emerald-600">{{ $cCount }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">Certificados</p>
                    </div>
                </div>
                @endif

                <div class="p-5">
                    @if($recentActivity->count() > 0)
                        <div class="relative">
                            <div class="timeline-line"></div>
                            <div class="space-y-5 pl-0">
                                @foreach($recentActivity as $activity)
                                    @php
                                        $data = is_string($activity->data) ? json_decode($activity->data, true) : (array) $activity->data;

                                        $typeMap = [
                                            'lesson_completed'   => ['icon'=>'fas fa-play-circle',  'bg'=>'bg-blue-100',   'text'=>'text-blue-600',   'label'=>'Lección'],
                                            'document_completed' => ['icon'=>'fas fa-file-pdf',     'bg'=>'bg-red-100',    'text'=>'text-red-500',    'label'=>'Documento'],
                                            'exam_completed'     => ['icon'=>'fas fa-file-alt',     'bg'=>'bg-orange-100', 'text'=>'text-orange-500', 'label'=>'Examen'],
                                            'exam_started'       => ['icon'=>'fas fa-pencil-alt',   'bg'=>'bg-yellow-100', 'text'=>'text-yellow-600', 'label'=>'Examen'],
                                            'certificate_earned' => ['icon'=>'fas fa-certificate',  'bg'=>'bg-emerald-100','text'=>'text-emerald-600','label'=>'Certificado'],
                                            'course_enrolled'    => ['icon'=>'fas fa-user-plus',    'bg'=>'bg-purple-100', 'text'=>'text-purple-600', 'label'=>'Inscripción'],
                                            'course_accessed'    => ['icon'=>'fas fa-book-open',    'bg'=>'bg-indigo-100', 'text'=>'text-indigo-600', 'label'=>'Acceso'],
                                        ];

                                        $meta       = $typeMap[$activity->type] ?? ['icon'=>'fas fa-circle','bg'=>'bg-slate-100','text'=>'text-slate-400','label'=>'Actividad'];
                                        $mainTitle  = $data['lesson_title'] ?? $data['exam_title'] ?? $data['course_title'] ?? 'Contenido';
                                        $courseTitle= $data['course_title'] ?? '';
                                        $score      = $data['score'] ?? null;
                                        $passed     = $data['passed'] ?? null;
                                    @endphp

                                    <div class="flex gap-3 relative">
                                        <div class="timeline-dot {{ $meta['bg'] }} {{ $meta['text'] }}">
                                            <i class="{{ $meta['icon'] }}" style="font-size:9px"></i>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-0.5">
                                            <div class="flex items-start justify-between gap-1">
                                                <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">{{ $meta['label'] }}</span>
                                                <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-xs font-semibold text-slate-700 line-clamp-2 mt-0.5 leading-snug">{{ $mainTitle }}</p>
                                            @if($courseTitle && $courseTitle !== $mainTitle)
                                                <p class="text-[11px] text-blue-500 font-medium mt-0.5 truncate">{{ $courseTitle }}</p>
                                            @endif
                                            @if($score !== null)
                                                <span class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold rounded-full px-2 py-0.5 {{ $passed ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                                                    <i class="fas fa-star" style="font-size:8px"></i>
                                                    {{ $score }} pts — {{ $passed ? 'Aprobado' : 'No aprobado' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-history"></i>
                            <p class="text-sm font-semibold text-slate-500">Sin actividad aún</p>
                            <p class="text-xs text-slate-400 mt-1">Empieza una lección para verla aquí.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- end right column --}}
    </div>{{-- end main grid --}}
</div>{{-- end progress-page --}}
@endsection

@section('scripts')
<script>
/* ─────────────────────────────────────────────────────────────
   Alpine: SOLO gestiona la animación del ring SVG en el hero.
   No toca Chart.js — evita el conflicto de timing con defer.
───────────────────────────────────────────────────────────── */
function progressRing() {
    return {
        targetRate: {{ $stats['completion_rate'] ?? 0 }},
        displayRate: 0,
        ringOffset: 364.4, // circunferencia = 2π × 58 ≈ 364.4

        initRing() {
            // Pequeño delay para que la transición CSS del ring sea visible
            setTimeout(() => {
                const rate   = this.targetRate;
                this.ringOffset = 364.4 - (364.4 * rate / 100);

                // Counter animado
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

/* ─────────────────────────────────────────────────────────────
   Chart.js: completamente independiente de Alpine.
   Usamos window 'load' para garantizar que:
     1. El browser terminó de calcular el layout (canvas tiene px reales)
     2. Chart.js (cargado sync en <head>) está disponible
     3. No hay conflicto con Alpine defer
───────────────────────────────────────────────────────────── */
window.addEventListener('load', function () {
    buildDonutChart();
    buildWeeklyChart();
});

/* ── Helpers de destrucción segura ── */
function safeDestroyChart(canvasId) {
    try {
        const existing = Chart.getChart(canvasId);
        if (existing) existing.destroy();
    } catch (e) { /* silencioso */ }
}

/* ── Donut chart: distribución de cursos ── */
function buildDonutChart() {
    safeDestroyChart('donutChart');

    const canvas = document.getElementById('donutChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const inProgress = {{ collect($courses)->where('progress', '<', 100)->where('progress', '>', 0)->count() }};
    const completed  = {{ $finishedCourses->count() }};
    const notStarted = {{ collect($courses)->where('progress', 0)->count() }};

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

/* ── Weekly bar chart: actividad de los últimos 7 días ── */
function buildWeeklyChart() {
    safeDestroyChart('weeklyChart');

    const canvas = document.getElementById('weeklyChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    @php
        $wLabels = [];
        $wDays   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date      = now()->subDays($i);
            $wLabels[] = $date->locale('es')->isoFormat('dd');
            $wDays[]   = $recentActivity
                ->whereIn('type', ['lesson_completed', 'document_completed'])
                ->filter(fn($a) => $a->created_at->isSameDay($date))
                ->count();
        }
    @endphp

    const labels = @json($wLabels);
    const data   = @json($wDays);
    const maxVal = Math.max(...data, 1);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Lecciones',
                data,
                backgroundColor: data.map((v, i) =>
                    i === 6
                        ? 'rgba(59,130,246,0.95)'
                        : (v > 0 ? 'rgba(59,130,246,0.45)' : 'rgba(226,232,240,0.8)')
                ),
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { display: false, beginAtZero: true, max: maxVal + 1 },
                x: {
                    grid:   { display: false },
                    border: { display: false },
                    ticks:  { font: { size: 10, weight: '600', family: 'DM Sans' }, color: '#94a3b8' }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (items) => items[0].dataIndex === 6 ? 'Hoy' : labels[items[0].dataIndex],
                        label: (item)  => ` ${item.raw} lección${item.raw !== 1 ? 'es' : ''}`
                    }
                }
            },
            animation: { duration: 900, easing: 'easeOutQuart' },
        }
    });
}
</script>
@endsection