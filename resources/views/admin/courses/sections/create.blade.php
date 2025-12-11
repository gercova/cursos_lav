@extends('layouts.admin')

@section('title', 'Nueva Sección: ' . $course->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    @if($course->image_url)
                        <img src="{{ Storage::url($course->image_url) }}"
                             alt="{{ $course->title }}"
                             class="w-12 h-12 rounded-xl object-cover border border-gray-300">
                    @endif
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Nueva Sección</h1>
                        <p class="text-gray-600 mt-1">{{ $course->title }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4 md:mt-0">
                <a href="{{ route('admin.courses.sections.index', $course) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Secciones
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.courses.sections.store', $course) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Título y Orden -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Título de la Sección *
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                               placeholder="Ej: Introducción al curso">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Orden *
                        </label>
                        <input type="number"
                               id="order"
                               name="order"
                               value="{{ old('order', $course->sections()->count() + 1) }}"
                               required min="1"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                              placeholder="Describe brevemente el contenido de esta sección">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Archivo multimedia -->
                <div>
                    <label for="mediafile" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo Multimedia (Opcional)
                    </label>
                    <div class="mt-1 flex items-center space-x-4">
                        <label for="mediafile" class="cursor-pointer">
                            <div class="flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-400 transition duration-200">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-600">Subir archivo</span>
                            </div>
                            <input type="file"
                                   id="mediafile"
                                   name="mediafile"
                                   class="hidden"
                                   accept=".mp4,.avi,.mov,.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx">
                        </label>
                        <span class="text-sm text-gray-500">Máx. 10MB</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Videos (mp4, avi, mov), imágenes (jpg, png, gif) o documentos (pdf, doc)
                    </p>
                    @error('mediafile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="flex items-center">
                    <input type="checkbox"
                           id="is_active"
                           name="is_active"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Sección activa (visible para los estudiantes)
                    </label>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.courses.sections.index', $course) }}"
                       class="px-6 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-medium transition duration-200">
                        Crear Sección
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
