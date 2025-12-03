@extends('layouts.admin')

@section('title', 'Crear Nuevo Curso')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Crear Nuevo Curso</h1>
                <p class="text-gray-600 mt-2">Completa todos los campos para crear un nuevo curso</p>
            </div>

            <div class="flex items-center gap-2 mt-4 md:mt-0">
                <a href="{{ route('admin.courses.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Cursos
                </a>
            </div>
        </div>

        <!-- Progreso -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progreso del formulario</span>
                <span class="text-sm font-medium text-blue-600" id="progressPercentage">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300" id="progressBar" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.courses.store') }}"
            method="POST"
            enctype="multipart/form-data"
            id="courseForm"
            oninput="updateProgress()">
            @include('admin.courses.partials.form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Actualizar progreso del formulario
    function updateProgress() {
        const form = document.getElementById('courseForm');
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let filled = 0;

        inputs.forEach(input => {
            if (input.type === 'checkbox' && input.checked) {
                filled++;
            } else if (input.value.trim() !== '') {
                filled++;
            }
        });

        const percentage = Math.min(100, Math.round((filled / inputs.length) * 100));
        document.getElementById('progressBar').style.width = percentage + '%';
        document.getElementById('progressPercentage').textContent = percentage + '%';
    }

    // Inicializar progreso
    document.addEventListener('DOMContentLoaded', function() {
        updateProgress();
    });
</script>
@endpush
