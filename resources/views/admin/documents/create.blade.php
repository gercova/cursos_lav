@extends('layouts.admin')

@section('title', 'Subir Nuevo Documento')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ formProgress: 0 }" x-init="updateProgress()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Subir Nuevo Documento</h1>
                <p class="text-gray-600 mt-2">Completa el formulario para agregar un nuevo documento</p>
            </div>

            <div class="flex items-center gap-2 mt-4 lg:mt-0">
                <a href="{{ route('admin.documents.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Documentos
                </a>
            </div>
        </div>

        <!-- Barra de progreso -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-gray-700">Progreso del formulario</span>
                <span class="text-sm font-bold text-blue-600" x-text="`${formProgress}%`"></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500"
                     :style="`width: ${formProgress}%`"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                <span>Información</span>
                <span>Archivo</span>
                <span>Revisar</span>
                <span>Completar</span>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.documents.store') }}"
              method="POST"
              enctype="multipart/form-data"
              id="documentForm"
              oninput="updateProgress()">

            @include('admin.documents.partials.form')

        </form>
    </div>

    <!-- Información de ayuda -->
    <div class="mt-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-xl bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-blue-900">Consejos para Subir Documentos</h3>
                    <p class="text-sm text-blue-700 mt-1">Sigue estas recomendaciones para una mejor experiencia</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">💡 Títulos Claros</h4>
                    <p class="text-sm text-gray-600">
                        Usa nombres descriptivos que incluyan el tema, capítulo y tipo de documento (Ej: "Guía_Ejercicios_Cap2.pdf").
                    </p>
                </div>

                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">📁 Formatos Optimizados</h4>
                    <p class="text-sm text-gray-600">
                        Prefiere PDF para lectura, DOC para edición, comprime imágenes y evita archivos innecesariamente grandes.
                    </p>
                </div>

                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">🎯 Descripciones Útiles</h4>
                    <p class="text-sm text-gray-600">
                        Incluye en la descripción el contenido, objetivos y cualquier instrucción especial para los estudiantes.
                    </p>
                </div>

                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">🔄 Organización</h4>
                    <p class="text-sm text-gray-600">
                        Asigna cada documento al curso correcto para facilitar el acceso y mantener la plataforma organizada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Actualizar barra de progreso
    function updateProgress() {
        const form = document.getElementById('documentForm');
        const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
        let filled = 0;

        requiredFields.forEach(field => {
            if (field.type === 'checkbox') {
                if (field.checked) filled++;
            } else if (field.value.trim() !== '' && field.value !== '0') {
                filled++;
            }
        });

        // Verificar si hay archivo seleccionado
        const fileInput = document.getElementById('file');
        if (fileInput && fileInput.files.length > 0) {
            filled++;
        }

        // Agregar puntaje por campos opcionales llenos
        const optionalFields = form.querySelectorAll('input:not([required]), textarea:not([required])');
        optionalFields.forEach(field => {
            if (field.value.trim() !== '') filled += 0.2;
        });

        const percentage = Math.min(100, Math.round((filled / (requiredFields.length + optionalFields.length * 0.2)) * 100));

        // Actualizar Alpine.js data
        const alpineElement = document.querySelector('[x-data]');
        if (alpineElement && alpineElement.__x) {
            alpineElement.__x.$data.formProgress = percentage;
        }
    }

    // Preview del archivo seleccionado
    function previewFile(event) {
        const file = event.target.files[0];
        if (file) {
            const filePreview = document.getElementById('file-preview');
            const fileName = document.getElementById('file-name');
            const fileType = document.getElementById('file-type');
            const fileSize = document.getElementById('file-size');
            const fileIcon = document.getElementById('file-icon');

            // Validar tamaño máximo (50MB)
            const maxSize = 50 * 1024 * 1024; // 50MB en bytes
            if (file.size > maxSize) {
                alert('El archivo es demasiado grande. El tamaño máximo permitido es 50MB.');
                event.target.value = '';
                filePreview.classList.add('hidden');
                return;
            }

            // Validar tipo de archivo
            const allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar', '7z'];
            const extension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(extension)) {
                alert('Tipo de archivo no permitido. Formatos aceptados: PDF, DOC, PPT, XLS, TXT, ZIP, RAR, 7Z');
                event.target.value = '';
                filePreview.classList.add('hidden');
                return;
            }

            // Mostrar información del archivo
            fileName.textContent = file.name;
            fileType.textContent = extension.toUpperCase();
            fileSize.textContent = formatFileSize(file.size);

            // Cambiar icono según tipo de archivo
            if (fileIcon) {
                let iconColor = 'text-gray-600';
                let bgColor = 'from-gray-100 to-gray-200';

                switch(extension) {
                    case 'pdf':
                        iconColor = 'text-red-600';
                        bgColor = 'from-red-100 to-red-200';
                        break;
                    case 'doc':
                    case 'docx':
                        iconColor = 'text-blue-600';
                        bgColor = 'from-blue-100 to-blue-200';
                        break;
                    case 'ppt':
                    case 'pptx':
                        iconColor = 'text-orange-600';
                        bgColor = 'from-orange-100 to-orange-200';
                        break;
                    case 'xls':
                    case 'xlsx':
                        iconColor = 'text-green-600';
                        bgColor = 'from-green-100 to-green-200';
                        break;
                    case 'zip':
                    case 'rar':
                    case '7z':
                        iconColor = 'text-purple-600';
                        bgColor = 'from-purple-100 to-purple-200';
                        break;
                }

                fileIcon.className = `p-3 rounded-lg bg-gradient-to-br ${bgColor}`;
                fileIcon.innerHTML = `
                    <svg class="w-8 h-8 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                `;
            }

            filePreview.classList.remove('hidden');
            updateProgress();
        }
    }

    // Limpiar archivo seleccionado
    function clearFile() {
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('file-preview');

        if (fileInput) fileInput.value = '';
        if (filePreview) filePreview.classList.add('hidden');
        updateProgress();
    }

    // Formatear tamaño de archivo
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Reiniciar formulario
    function resetForm() {
        if (confirm('¿Estás seguro de reiniciar el formulario? Se perderán todos los datos ingresados.')) {
            document.getElementById('documentForm').reset();
            clearFile();
            updateProgress();

            // Ocultar info del curso
            const courseInfo = document.getElementById('course-info');
            if (courseInfo) courseInfo.classList.add('hidden');
        }
    }

    // Actualizar información del curso seleccionado
    document.getElementById('course_id')?.addEventListener('change', function(e) {
        const selectedOption = this.options[this.selectedIndex];
        const courseInfo = document.getElementById('course-info');

        if (selectedOption.value) {
            courseInfo.classList.remove('hidden');

            document.getElementById('selected-course-title').textContent = selectedOption.text;
            document.getElementById('selected-course-category').textContent = selectedOption.dataset.category || 'Sin categoría';

            const students = selectedOption.dataset.students || '0';
            document.getElementById('selected-course-students').textContent = `${students} estudiantes`;
        } else {
            courseInfo.classList.add('hidden');
        }

        updateProgress();
    });

    // Inicializar información del curso si ya hay uno seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const courseSelect = document.getElementById('course_id');
        if (courseSelect && courseSelect.value) {
            courseSelect.dispatchEvent(new Event('change'));
        }

        // Inicializar barra de progreso
        updateProgress();
    });
</script>
@endpush
@endsection
