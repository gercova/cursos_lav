@extends('layouts.admin')
@section('title', 'Editar Curso: ' . $course->title)

@push('styles')
<style>
    #progressBar {
        transition: width 0.65s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    #progressBar::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.35) 50%, transparent 100%);
        animation: shimmer-progress 2s infinite;
    }
    @keyframes shimmer-progress {
        0%   { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
    .step-dot {
        transition: background-color 0.4s ease, transform 0.3s ease, box-shadow 0.3s ease;
        cursor: default;
    }
    .step-dot.active {
        transform: scale(1.25);
        box-shadow: 0 0 0 4px rgba(99,102,241,0.2);
    }
    .step-dot.done { transform: scale(1.1); }
    #progressPercentage { transition: color 0.4s ease; }

    /* Tabs */
    .edit-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid transparent;
        font-size: 0.8125rem;
        font-weight: 500;
        white-space: nowrap;
        transition: color 0.2s ease, border-color 0.2s ease;
        color: #6b7280;
        text-decoration: none;
    }
    .edit-tab:hover { color: #374151; border-bottom-color: #d1d5db; }
    .edit-tab.active { color: #4f46e5; border-bottom-color: #4f46e5; }
    .edit-tab svg { opacity: 0.6; transition: opacity 0.2s; }
    .edit-tab.active svg, .edit-tab:hover svg { opacity: 1; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- ─── Header ─────────────────────────────────────────────────────── --}}
    <div class="mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Gradient stripe --}}
            <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400"></div>

            <div class="p-5">
                <div class="flex flex-col md:flex-row md:items-start gap-5">
                    {{-- Course thumbnail / icon --}}
                    <div class="flex-shrink-0">
                        @if($course->image_url)
                            <img src="{{ $course->image_url }}" alt="{{ $course->title }}"
                                 class="w-16 h-16 rounded-xl object-cover border border-gray-200 shadow-sm">
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Title + badges --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Editar Curso</span>
                        </div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug truncate">{{ $course->title }}</h1>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $course->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $course->is_active ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                                {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                {{ $course->students_count }} {{ $course->students_count === 1 ? 'estudiante' : 'estudiantes' }}
                            </span>
                            @if($course->is_on_promotion)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                    🏷️ En promoción
                                </span>
                            @endif
                            @if($course->is_training)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                    Capacitación
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('admin.courses.index') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 rounded-xl font-medium transition-all duration-200 text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver
                        </a>
                        <a href="{{ route('course.show', $course->slug) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white hover:from-emerald-600 hover:to-green-700 rounded-xl font-medium transition-all duration-200 text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Vista previa
                        </a>
                    </div>
                </div>

                {{-- ─── Progress bar ─────────────────────────────── --}}
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <div class="flex items-center gap-1.5">
                                <span class="step-dot w-2.5 h-2.5 rounded-full bg-indigo-500 active inline-block" data-index="0" title="Información Básica"></span>
                                <span class="step-dot w-2.5 h-2.5 rounded-full bg-gray-200 inline-block" data-index="1" title="Precios"></span>
                                <span class="step-dot w-2.5 h-2.5 rounded-full bg-gray-200 inline-block" data-index="2" title="Descripción"></span>
                                <span class="step-dot w-2.5 h-2.5 rounded-full bg-gray-200 inline-block" data-index="3" title="Imagen y Estado"></span>
                            </div>
                            <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full" id="stepLabel">Información Básica</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-gray-400">Completado:</span>
                            <span class="text-sm font-bold text-indigo-600 tabular-nums" id="progressPercentage">0%</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400 h-2.5 rounded-full" id="progressBar" style="width: 0%"></div>
                    </div>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-xs text-gray-400" id="fieldsInfo">Cargando campos…</span>
                        <span class="text-xs text-emerald-600 font-semibold hidden items-center gap-1" id="readyMsg">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Campos completos
                        </span>
                    </div>
                </div>
            </div>

            {{-- ─── Tabs ─────────────────────────────────────────── --}}
            <div class="border-t border-gray-100 px-4 overflow-x-auto">
                <nav class="flex" aria-label="Secciones del curso">
                    <a href="#" class="edit-tab active">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Información General
                    </a>
                    <a href="{{ route('admin.courses.sections.index', $course) }}" class="edit-tab">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Secciones y Contenido
                    </a>
                    <a href="{{ route('admin.documents.view', $course) }}" class="edit-tab">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Documentos
                    </a>
                    <a href="{{ route('admin.exams.view', $course) }}" class="edit-tab">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Exámenes
                    </a>
                    <a href="{{ route('admin.courses.students', $course) }}" class="edit-tab">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Estudiantes
                    </a>
                </nav>
            </div>
        </div>
    </div>

    {{-- ─── Form ────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
            @include('admin.courses.partials.form')
        </form>
    </div>

    <!-- Sección de estadísticas -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Estadísticas del Curso</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800">Total Estudiantes</span>
                    <span class="text-2xl font-bold text-blue-900">{{ $course->students_count }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800">Total Secciones</span>
                    <span class="text-2xl font-bold text-blue-900">{{ $course->sections_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800">Fecha de Creación</span>
                    <span class="text-sm font-medium text-blue-900">{{ $course->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-green-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.courses.sections.index', $course) }}"
                   class="flex items-center gap-3 p-3 bg-white rounded-lg border border-green-200 hover:border-green-300 transition duration-200">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-900">Agregar Nueva Sección</span>
                </a>
                <a href="#"
                   class="flex items-center gap-3 p-3 bg-white rounded-lg border border-green-200 hover:border-green-300 transition duration-200">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-900">Subir Documentos</span>
                </a>
                <button onclick="toggleCourseStatus({{ $course->id }})"
                        class="w-full flex items-center gap-3 p-3 bg-white rounded-lg border border-green-200 hover:border-green-300 transition duration-200">
                    <svg class="w-5 h-5 {{ $course->is_active ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($course->is_active)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        @endif
                    </svg>
                    <span class="text-sm font-medium {{ $course->is_active ? 'text-red-900' : 'text-green-900' }}">
                        {{ $course->is_active ? 'Desactivar Curso' : 'Activar Curso' }}
                    </span>
                </button>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
            <h3 class="text-lg font-semibold text-purple-900 mb-4">Información de Precios</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-purple-800">Precio Regular</span>
                    <span class="text-xl font-bold text-purple-900">S/ {{ number_format($course->price, 2) }}</span>
                </div>
                @if($course->is_on_promotion)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-purple-800">Precio Promoción</span>
                        <span class="text-xl font-bold text-purple-900">S/ {{ number_format($course->promotion_price, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-purple-800">Descuento</span>
                        <span class="text-xl font-bold text-purple-900">
                            {{ number_format((($course->price - $course->promotion_price) / $course->price) * 100, 0) }}%
                        </span>
                    </div>
                @endif
                <div class="flex items-center justify-between pt-4 border-t border-purple-200">
                    <span class="text-sm font-semibold text-purple-900">Precio Final</span>
                    <span class="text-2xl font-bold text-purple-900">S/ {{ number_format($course->final_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Función para cambiar estado del curso
    async function toggleCourseStatus(courseId) {
        if (!confirm('¿Estás seguro de cambiar el estado del curso?')) {
            return;
        }

        try {
            const response = await axios.post(`/admin/courses/${courseId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del curso actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    // Función para mostrar notificaciones
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
        }, 3000);
    }
</script>
<script>
(function() {
    const STEPS = [
        { label: 'Información Básica',      color: 'bg-indigo-500' },
        { label: 'Precios',                 color: 'bg-blue-500'   },
        { label: 'Descripción y Contenido', color: 'bg-cyan-500'   },
        { label: 'Imagen y Estado',         color: 'bg-teal-500'   },
    ];

    function updateProgress() {
        const form   = document.getElementById('courseForm');
        if (!form) return;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let filled   = 0;

        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                if (input.checked) filled++;
            } else if (input.value && input.value.trim() !== '') {
                filled++;
            }
        });

        const total      = inputs.length;
        const percentage = total > 0 ? Math.min(100, Math.round((filled / total) * 100)) : 0;

        document.getElementById('progressBar').style.width = percentage + '%';

        const pctEl = document.getElementById('progressPercentage');
        pctEl.textContent = percentage + '%';
        if (percentage < 30) {
            pctEl.className = 'text-sm font-bold text-rose-500 tabular-nums';
        } else if (percentage < 70) {
            pctEl.className = 'text-sm font-bold text-amber-500 tabular-nums';
        } else if (percentage < 100) {
            pctEl.className = 'text-sm font-bold text-indigo-600 tabular-nums';
        } else {
            pctEl.className = 'text-sm font-bold text-emerald-600 tabular-nums';
        }

        document.getElementById('fieldsInfo').textContent =
            total > 0 ? `${filled} de ${total} campos requeridos completados` : '';

        const dots      = document.querySelectorAll('.step-dot');
        const stepIndex = Math.min(Math.floor((percentage / 100) * STEPS.length), STEPS.length - 1);

        dots.forEach((dot, i) => {
            dot.className = 'step-dot w-2.5 h-2.5 rounded-full inline-block';
            if (percentage === 100) {
                dot.classList.add(STEPS[i].color, 'done');
            } else if (i < stepIndex) {
                dot.classList.add(STEPS[i].color, 'done');
            } else if (i === stepIndex) {
                dot.classList.add(STEPS[i].color, 'active');
            } else {
                dot.classList.add('bg-gray-200');
            }
        });

        document.getElementById('stepLabel').textContent = STEPS[stepIndex].label;

        const readyMsg = document.getElementById('readyMsg');
        if (percentage === 100) {
            readyMsg.classList.remove('hidden');
            readyMsg.classList.add('inline-flex');
        } else {
            readyMsg.classList.add('hidden');
            readyMsg.classList.remove('inline-flex');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateProgress();
        const form = document.getElementById('courseForm');
        if (form) {
            form.addEventListener('input',  updateProgress);
            form.addEventListener('change', updateProgress);
        }
    });
})();
</script>
@endsection
