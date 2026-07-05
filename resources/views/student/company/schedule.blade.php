@extends('layouts.student')
@section('title', 'Cronograma Anual de Capacitaciones')
@section('content')
{{-- Contenido inyectado en el layout student --}}
<div class="space-y-6">

    {{-- ── CABECERA ──────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Programa Anual de Capacitaciones
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Cursos programados para tu empresa — Solo se muestran los cursos del
                <span class="font-semibold text-blue-600">mes actual y meses anteriores</span>.
                Los cursos de próximos meses se mostrarán al llegar su fecha.
            </p>
        </div>
        {{-- Selector de año: solo se puede navegar hacia atrás --}}
        @php $currentYear = now()->year; @endphp
        <div class="flex items-center gap-2">
            <a href="{{ request()->fullUrlWithQuery(['year' => $year - 1]) }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600 transition-colors">
                <i class="fas fa-chevron-left text-xs"></i>
            </a>
            <span class="text-lg font-bold text-blue-700 px-2">{{ $year }}</span>
            {{-- Solo permitir avanzar al año siguiente si es un año pasado --}}
            @if($year < $currentYear)
                <a href="{{ request()->fullUrlWithQuery(['year' => $year + 1]) }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100 text-gray-600 transition-colors">
                <i class="fas fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-100 text-gray-300 cursor-not-allowed" title="No hay cursos programados en años futuros">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>
    </div>

    {{-- ── TARJETAS RESUMEN ──────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-check text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total programado</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalItems }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-emerald-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Ya disponibles</p>
                <p class="text-xl font-bold text-gray-800">{{ $releasedCount }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Próximos</p>
                <p class="text-xl font-bold text-gray-800">{{ $upcomingCount }}</p>
            </div>
        </div>
    </div>

    {{-- ── GRILLA POR MES ────────────────────────────────────────────────── --}}
    <div class="space-y-3">
        @php $months = \App\Models\CompanySchedule::$months; @endphp
        @foreach($months as $num => $monthName)
            @php
                $now = now();
                $isPast = ($year < $now->year) || ($year == $now->year && $num < $now->month);
                $isCurrent = ($year == $now->year && $num == $now->month);
                $isFuture = !$isPast && !$isCurrent;
                $items = $byMonth->get($num, collect());
            @endphp

            {{-- Ocultar meses futuros del año actual y años futuros --}}
            @if($isFuture)
                @continue
            @endif

            <div class="bg-white rounded-xl border {{ $isCurrent ? 'border-blue-300 shadow-md' : ($isPast ? 'border-gray-200' : 'border-gray-100') }} overflow-hidden">
                {{-- Cabecera del mes --}}
                <div class="px-5 py-3 flex items-center gap-3 {{ $isCurrent ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white' : ($isPast ? 'bg-gray-50' : 'bg-white') }}">
                    {{-- Indicador --}}
                    @if($isCurrent)
                        <span class="w-2.5 h-2.5 rounded-full bg-white flex-shrink-0"></span>
                    @elseif($isPast)
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 flex-shrink-0"></span>
                    @else
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-300 flex-shrink-0"></span>
                    @endif

                    <h3 class="font-bold text-sm {{ $isCurrent ? 'text-white' : 'text-gray-800' }} uppercase tracking-wide">
                        {{ $monthName }} {{ $year }}
                    </h3>

                    @if($isCurrent)
                        <span class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full">Mes actual</span>
                    @elseif($isPast)
                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Completado</span>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Próximo</span>
                    @endif

                    <span class="ml-auto text-xs {{ $isCurrent ? 'text-white/80' : 'text-gray-400' }}">
                        {{ $items->count() }} {{ $items->count() == 1 ? 'curso' : 'cursos' }}
                    </span>
                </div>

                {{-- Cursos del mes --}}
                @if($items->count() > 0)
                    <div class="divide-y divide-gray-50">
                        @foreach($items as $item)
                        <div class="px-5 py-3 flex items-center gap-4 hover:bg-gray-50/50 transition-colors duration-150">
                            {{-- Imagen del curso --}}
                            <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                                @if($item->course)
                                <img src="{{ $item->course->image_url }}"
                                    alt="{{ $item->course->title }}"
                                    class="w-full h-full object-cover {{ $isFuture ? 'grayscale opacity-60' : '' }}">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-book"></i>
                                </div>
                                @endif
                            </div>

                            {{-- Info del curso --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $item->course?->title ?? 'Curso no disponible' }}
                                </p>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @if($item->modality)
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-laptop-house mr-1"></i>{{ $item->modality }}
                                    </span>
                                    @endif
                                    @if($item->responsible_area)
                                    <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-user-tie mr-1"></i>{{ $item->responsible_area }}
                                    </span>
                                    @endif
                                    @if($item->scope)
                                    <span class="text-xs bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full">
                                        <i class="fas fa-users mr-1"></i>{{ $item->scope }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Estado / Acción --}}
                            <div class="flex-shrink-0 text-right">
                                @if($item->is_released && $item->course)
                                    <a href="{{ route('student.course.learn', $item->course->id) }}" class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="fas fa-play-circle"></i> Iniciar
                                    </a>
                                @elseif($isFuture)
                                    {{-- Nunca se renderiza (los meses futuros se omiten arriba) --}}
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-400 bg-gray-100 px-3 py-1.5 rounded-lg">
                                        <i class="fas fa-lock"></i>
                                        Próximamente
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg font-medium">
                                        <i class="fas fa-check-circle"></i> Liberado
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-4 text-sm text-gray-400 italic">
                        <i class="fas fa-calendar-times mr-2"></i>Sin capacitaciones programadas para este mes.
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection