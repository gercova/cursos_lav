@extends('layouts.admin')
@section('title', 'Crear Nuevo Curso')

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
    .step-dot.done {
        transform: scale(1.1);
    }
    #progressPercentage {
        transition: color 0.4s ease;
    }
    .progress-card {
        background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between mb-6 gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">Crear Nuevo Curso</h1>
                    <p class="text-gray-500 mt-0.5 text-sm">Completa todos los campos para publicar el curso</p>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-1 flex-shrink-0">
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 rounded-xl font-medium transition-all duration-200 text-sm shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Cursos
                </a>
            </div>
        </div>

        <!-- Progress Card -->
        <div class="progress-card rounded-2xl border border-indigo-100 shadow-sm p-5">
            <!-- Top row: steps + percentage -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-2">
                        <span class="step-dot w-3 h-3 rounded-full bg-indigo-500 active inline-block" data-index="0" title="Información Básica"></span>
                        <span class="step-dot w-3 h-3 rounded-full bg-gray-200 inline-block" data-index="1" title="Precios"></span>
                        <span class="step-dot w-3 h-3 rounded-full bg-gray-200 inline-block" data-index="2" title="Descripción y Contenido"></span>
                        <span class="step-dot w-3 h-3 rounded-full bg-gray-200 inline-block" data-index="3" title="Imagen y Estado"></span>
                    </div>
                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full" id="stepLabel">Información Básica</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-gray-400">Completado:</span>
                    <span class="text-sm font-bold text-indigo-600 tabular-nums min-w-[2.5rem] text-right" id="progressPercentage">0%</span>
                </div>
            </div>

            <!-- Bar -->
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 via-blue-500 to-cyan-400 h-3 rounded-full" id="progressBar" style="width: 0%"></div>
            </div>

            <!-- Bottom row -->
            <div class="mt-3 flex items-center justify-between">
                <span class="text-xs text-gray-400" id="fieldsInfo">Completa los campos requeridos</span>
                <span class="text-xs text-emerald-600 font-semibold hidden items-center gap-1" id="readyMsg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Listo para crear
                </span>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" id="courseForm">
            @include('admin.courses.partials.form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const STEPS = [
        { label: 'Información Básica',     color: 'bg-indigo-500' },
        { label: 'Precios',                color: 'bg-blue-500'   },
        { label: 'Descripción y Contenido',color: 'bg-cyan-500'   },
        { label: 'Imagen y Estado',        color: 'bg-teal-500'   },
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

        // Bar
        document.getElementById('progressBar').style.width = percentage + '%';

        // Percentage text + color
        const pctEl = document.getElementById('progressPercentage');
        pctEl.textContent = percentage + '%';
        if (percentage < 30) {
            pctEl.className = 'text-sm font-bold text-rose-500 tabular-nums min-w-[2.5rem] text-right';
        } else if (percentage < 70) {
            pctEl.className = 'text-sm font-bold text-amber-500 tabular-nums min-w-[2.5rem] text-right';
        } else if (percentage < 100) {
            pctEl.className = 'text-sm font-bold text-indigo-600 tabular-nums min-w-[2.5rem] text-right';
        } else {
            pctEl.className = 'text-sm font-bold text-emerald-600 tabular-nums min-w-[2.5rem] text-right';
        }

        // Fields info
        document.getElementById('fieldsInfo').textContent =
            total > 0 ? `${filled} de ${total} campos requeridos completados` : 'Completa los campos requeridos';

        // Step dots
        const dots       = document.querySelectorAll('.step-dot');
        const stepIndex  = Math.min(Math.floor((percentage / 100) * STEPS.length), STEPS.length - 1);

        dots.forEach((dot, i) => {
            // Reset
            dot.className = 'step-dot w-3 h-3 rounded-full inline-block';
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

        // Step label
        document.getElementById('stepLabel').textContent = STEPS[stepIndex].label;

        // Ready message
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
@endpush
