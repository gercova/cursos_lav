{{-- resources/views/admin/documents/partials/form.blade.php --}}
@csrf

@if(isset($document) && $document->exists)
    @method('PUT')
@endif

<div class="p-8">
    <!-- Información básica -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Información Básica</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Título -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Título del Documento <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $document->title ?? '') }}"
                    required
                    placeholder="Ej: Guía de Estudio - Capítulo 1"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                >
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Curso -->
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Curso Asociado <span class="text-red-500">*</span>
                </label>
                <select name="course_id" id="course_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <option value="">Seleccionar curso</option>
                    @foreach($courses ?? [] as $course)
                        <option value="{{ $course->id }}"
                            data-category="{{ $course->category->name ?? 'Sin categoría' }}"
                            data-students="{{ $course->students_count ?? 0 }}"
                            {{ old('course_id', $document->course_id ?? '') == $course->id ? 'selected' : '' }}
                        >
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Información del curso seleccionado -->
        <div id="course-info" class="{{ old('course_id', $document->course_id ?? false) ? '' : 'hidden' }} mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-blue-900" id="selected-course-title">
                            {{ $document->course->title ?? '' }}
                        </h4>
                        <div class="flex items-center gap-4 mt-2 text-sm text-blue-700">
                            <span id="selected-course-category">
                                {{ $document->course->category->name ?? 'Sin categoría' }}
                            </span>
                            <span id="selected-course-students">
                                {{ ($document->course->students_count ?? 0) }} estudiantes
                            </span>
                        </div>
                    </div>
                    <a href="{{ isset($document->course) ? route('admin.courses.edit', $document->course) : '#' }}"
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium {{ isset($document->course) ? '' : 'hidden' }}">
                        Ver Curso
                    </a>
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Descripción del Documento
            </label>
            <textarea name="description"
                id="description"
                rows="4"
                placeholder="Describe el contenido, objetivos o instrucciones especiales para los estudiantes..."
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
            >
                {{ old('description', $document->description ?? '') }}
            </textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Archivo -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Archivo del Documento</h3>

        @if(isset($document) && $document->exists)
            <!-- Para edición: Mostrar archivo actual -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Archivo Actual
                </label>
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-lg bg-gradient-to-br
                            @switch($document->file_type)
                                @case('pdf') from-red-100 to-red-200 @break
                                @case('doc') @case('docx') from-blue-100 to-blue-200 @break
                                @case('ppt') @case('pptx') from-orange-100 to-orange-200 @break
                                @case('xls') @case('xlsx') from-green-100 to-green-200 @break
                                @default from-gray-100 to-gray-200
                            @endswitch">
                            <svg class="w-8 h-8
                                @switch($document->file_type)
                                    @case('pdf') text-red-600 @break
                                    @case('doc') @case('docx') text-blue-600 @break
                                    @case('ppt') @case('pptx') text-orange-600 @break
                                    @case('xls') @case('xlsx') text-green-600 @break
                                    @default text-gray-600
                                @endswitch"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $document->title }}.{{ $document->file_type }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ strtoupper($document->file_type) }} •
                                {{ round($document->file_size / 1024 / 1024, 2) }} MB
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ Storage::url($document->file_path) }}"
                           target="_blank"
                           class="px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-sm font-medium transition duration-200">
                            Ver
                        </a>
                        <a href="{{ Storage::url($document->file_path) }}"
                           download
                           class="px-4 py-2 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg text-sm font-medium transition duration-200">
                            Descargar
                        </a>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Para cambiar el archivo, sube uno nuevo a continuación.
                </p>
            </div>
        @endif

        <!-- Subida de archivo -->
        <div class="{{ isset($document) && $document->exists ? 'border-t pt-6 mt-6' : '' }}">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ isset($document) && $document->exists ? 'Subir Nuevo Archivo' : 'Seleccionar Archivo' }}
                <span class="text-red-500">*</span>
            </label>

            <div class="border-2 border-dashed border-gray-300 hover:border-blue-400 rounded-2xl p-8 text-center transition duration-200">
                <input
                    type="file"
                    name="file"
                    id="file"
                    {{ !isset($document) ? 'required' : '' }}
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar,.7z"
                    class="hidden"
                    onchange="previewFile(event)"
                >

                <label for="file" class="cursor-pointer block">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="mt-4 text-lg font-medium text-gray-700">
                        <span class="text-blue-600 hover:text-blue-500">Haz clic para subir</span>
                        o arrastra y suelta
                    </p>
                    <p class="text-sm text-gray-500 mt-2">
                        PDF, DOC, PPT, XLS, TXT, ZIP, RAR, 7Z hasta 50MB
                    </p>
                </label>
            </div>
            @error('file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <!-- Vista previa del archivo -->
            <div id="file-preview" class="mt-4 hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div id="file-icon" class="p-3 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900" id="file-name"></h4>
                                <div class="flex items-center gap-4 mt-1">
                                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md text-xs font-medium" id="file-type"></span>
                                    <span class="text-sm text-gray-600" id="file-size"></span>
                                </div>
                            </div>
                        </div>
                        <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado y Configuración -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Configuración</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Estado -->
            <div class="p-6 bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Estado del Documento</h4>
                        <p class="text-sm text-gray-600">
                            Controla la visibilidad del documento para los estudiantes
                        </p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', isset($document) ? $document->is_active : true) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                <div class="mt-4 text-sm text-gray-500">
                    <span class="font-medium {{ old('is_active', isset($document) ? $document->is_active : true) ? 'text-green-600' : 'text-red-600' }}">
                        {{ old('is_active', isset($document) ? $document->is_active : true) ? 'Activo' : 'Inactivo' }}
                    </span>
                    -
                    {{ old('is_active', isset($document) ? $document->is_active : true)
                        ? 'Visible para estudiantes'
                        : 'Oculto para estudiantes' }}
                </div>
            </div>

            <!-- Metadatos -->
            <div class="p-6 bg-gradient-to-br from-blue-50 to-white border border-blue-200 rounded-xl">
                <h4 class="font-medium text-blue-900 mb-2">Información Técnica</h4>
                <div class="space-y-2 text-sm">
                    @if(isset($document) && $document->exists)
                        <div class="flex justify-between">
                            <span class="text-blue-700">Tipo:</span>
                            <span class="font-medium">{{ strtoupper($document->file_type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">Tamaño:</span>
                            <span class="font-medium">{{ round($document->file_size / 1024 / 1024, 2) }} MB</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-blue-700">Subido:</span>
                            <span class="font-medium">{{ $document->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @else
                        <p class="text-blue-600">La información técnica se generará automáticamente al subir el archivo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="flex items-center justify-between pt-8 border-t border-gray-200">
        <div>
            <button type="button"
                    onclick="resetForm()"
                    class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reiniciar
                </div>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <a href="{{ route('admin.documents.index') }}"
               class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                Cancelar
            </a>
            <button type="submit"
                    class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                @if(isset($document) && $document->exists)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar Documento
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Subir Documento
                @endif
            </button>
        </div>
    </div>
</div>
