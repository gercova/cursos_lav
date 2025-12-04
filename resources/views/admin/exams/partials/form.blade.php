@csrf
@if(isset($exam) && $exam->exists)
    @method('PUT')
@endif
<div class="space-y-8 mb-6 p-4">
    <!-- Información Básica -->
    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-3 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Información Básica</h3>
                <p class="text-sm text-gray-600 mt-1">Configura los datos principales del examen</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="lg:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Título del Examen *
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title', $exam->title ?? '') }}"
                       required
                       placeholder="Ej: Examen Final de Matemáticas Avanzadas"
                       class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 text-lg">
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="lg:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción del Examen
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          placeholder="Describe el objetivo y contenido del examen..."
                          class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">{{ old('description', $exam->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Curso -->
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Curso Asociado *
                </label>
                <select name="course_id"
                        id="course_id"
                        required
                        class="w-full px-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <option value="">Seleccionar curso</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}"
                                {{ old('course_id', $exam->course_id ?? '') == $course->id ? 'selected' : '' }}
                                data-category="{{ $course->category->name ?? '' }}">
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                <div id="course-info" class="mt-2 hidden">
                    <p class="text-sm text-gray-600">
                        Categoría: <span id="course-category" class="font-medium"></span>
                    </p>
                </div>
                @error('course_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duración -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                    Duración (minutos) *
                </label>
                <div class="relative">
                    <input type="number"
                           name="duration"
                           id="duration"
                           value="{{ old('duration', $exam->duration ?? 60) }}"
                           min="1"
                           max="480"
                           required
                           class="w-full pl-4 pr-12 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">min</span>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    <span id="duration-hours">0</span> horas
                    <span id="duration-minutes">0</span> minutos
                </p>
                @error('duration')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Configuración del Examen -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-3 rounded-xl bg-gradient-to-br from-blue-200 to-blue-300">
                <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-blue-900">Configuración del Examen</h3>
                <p class="text-sm text-blue-700 mt-1">Define las reglas y parámetros de evaluación</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Puntaje de Aprobación -->
            <div>
                <label for="passing_score" class="block text-sm font-medium text-blue-800 mb-2">
                    Puntaje Mínimo (%) *
                </label>
                <div class="relative">
                    <input type="number"
                           name="passing_score"
                           id="passing_score"
                           value="{{ old('passing_score', $exam->passing_score ?? 70) }}"
                           min="0"
                           max="100"
                           required
                           class="w-full px-4 py-3.5 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 bg-white">
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-blue-600 font-medium">%</span>
                </div>
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-blue-700 mb-1">
                        <span>0%</span>
                        <span id="passing-score-display">70%</span>
                        <span>100%</span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div id="passing-score-bar" class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300" style="width: 70%"></div>
                    </div>
                </div>
                @error('passing_score')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Intentos Máximos -->
            <div>
                <label for="max_attempts" class="block text-sm font-medium text-blue-800 mb-2">
                    Intentos Máximos
                </label>
                <div class="relative">
                    <input type="number"
                           name="max_attempts"
                           id="max_attempts"
                           value="{{ old('max_attempts', $exam->max_attempts ?? 3) }}"
                           min="0"
                           max="10"
                           class="w-full px-4 py-3.5 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 bg-white">
                </div>
                <p class="mt-2 text-sm text-blue-700">
                    <span id="attempts-display">3</span> intentos permitidos
                    <span id="unlimited-attempts" class="hidden font-medium">(Ilimitados)</span>
                </p>
                @error('max_attempts')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-blue-800 mb-2">
                    Estado del Examen
                </label>
                <div class="relative">
                    <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-blue-300">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   {{ old('is_active', isset($exam) ? $exam->is_active : true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-blue-300 rounded focus:ring-blue-500">
                            <label for="is_active" class="ml-3 text-sm font-medium text-blue-900">
                                Examen Activo
                            </label>
                        </div>
                        <div class="flex items-center">
                            <span class="h-3 w-3 rounded-full {{ (old('is_active', isset($exam) ? $exam->is_active : true)) ? 'bg-green-400' : 'bg-red-400' }} animate-pulse"></span>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-sm text-blue-700">
                    <span id="status-text">Los estudiantes pueden tomar este examen</span>
                </p>
                @error('is_active')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Opciones Adicionales -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-white rounded-xl border border-blue-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-green-50 to-green-100">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-green-900">Preguntas Aleatorias</p>
                        <p class="text-xs text-green-700">Mezclar orden de preguntas</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-600">
                    Esta opción se configura en las preguntas del examen
                </p>
            </div>

            <div class="p-4 bg-white rounded-xl border border-blue-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-purple-900">Mostrar Resultados</p>
                        <p class="text-xs text-purple-700">Ver respuestas al finalizar</p>
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-600">
                    Se muestra después de completar el examen
                </p>
            </div>
        </div>
    </div>

    <!-- Vista Previa y Estadísticas -->
    <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-3 rounded-xl bg-gradient-to-br from-green-100 to-green-200">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Vista Previa</h3>
                <p class="text-sm text-gray-600 mt-1">Resumen de la configuración del examen</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-900" id="preview-duration">60</div>
                    <div class="text-sm text-blue-700 mt-1">minutos</div>
                </div>
            </div>

            <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-900" id="preview-passing-score">70%</div>
                    <div class="text-sm text-green-700 mt-1">para aprobar</div>
                </div>
            </div>

            <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-900" id="preview-attempts">3</div>
                    <div class="text-sm text-purple-700 mt-1">intentos máx.</div>
                </div>
            </div>

            <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-900" id="preview-status">Activo</div>
                    <div class="text-sm text-orange-700 mt-1">estado</div>
                </div>
            </div>
        </div>

        <!-- Información del Curso Seleccionado -->
        <div id="selected-course-info" class="mt-6 p-4 bg-gradient-to-r from-gray-100 to-white rounded-xl border border-gray-300 hidden">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-200 to-blue-300 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900" id="selected-course-title"></h4>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full" id="selected-course-category"></span>
                        <span class="text-xs text-gray-500" id="selected-course-students"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
        <div>
            <a href="{{ route('admin.exams.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200 hover:shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Exámenes
            </a>
        </div>

        <div class="flex items-center gap-4">
            @if(isset($exam))
                <!-- Botón para gestionar preguntas -->
                <a href="{{ route('admin.exams.questions', $exam) }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-xl font-medium transition duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Gestionar Preguntas
                </a>
            @endif

            <div class="flex items-center gap-2">
                <button type="button"
                        onclick="window.location.reload()"
                        class="px-6 py-3.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    Reiniciar
                </button>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    {{ isset($exam) ? 'Actualizar Examen' : 'Crear Examen' }}
                </button>
            </div>
        </div>
    </div>
</div>
