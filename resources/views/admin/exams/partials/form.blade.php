<div class="space-y-6">
    @csrf

    <!-- Información Básica -->
    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Título del Examen *
                </label>
                <input type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $exam->title ?? '') }}"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                    placeholder="Ej: Examen Final de Matemáticas">
            </div>

            <!-- Descripción -->
            <div class="col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Descripción
                </label>
                <textarea id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                    placeholder="Describe el contenido y objetivos del examen">{{ old('description', $exam->description ?? '') }}</textarea>
            </div>

            <!-- Curso -->
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Curso *
                </label>
                <select id="course_id"
                    name="course_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <option value="">Seleccionar curso</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-category="{{ $course->category->name ?? 'Sin categoría' }}" {{ (old('course_id', $exam->course_id ?? '') == $course->id) ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Duración -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                    Duración (minutos) *
                </label>
                <div class="relative">
                    <input type="number"
                        id="duration"
                        name="duration"
                        value="{{ old('duration', $exam->duration ?? 60) }}"
                        min="1"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">min</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuración del Examen -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
        <h4 class="text-lg font-semibold text-blue-900 mb-4">Configuración del Examen</h4>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Puntaje mínimo -->
            <div>
                <label for="passing_score" class="block text-sm font-medium text-blue-800 mb-1">
                    Puntaje Mínimo (%) *
                </label>
                <div class="relative">
                    <input type="number"
                        id="passing_score"
                        name="passing_score"
                        value="{{ old('passing_score', $exam->passing_score ?? 70) }}"
                        min="0"
                        max="100"
                        required
                        class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600">%</span>
                    @error('passing_score')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Barra de puntaje -->
                <div class="mt-3">
                    <div class="flex justify-between text-sm text-blue-700 mb-1">
                        <span>Puntaje mínimo:</span>
                        <span id="passing-score-display">70%</span>
                    </div>
                    <div class="w-full bg-blue-200 rounded-full h-2">
                        <div id="passing-score-bar" class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                </div>
            </div>

            <!-- Tiempo límite -->
            <div>
                <label for="duration" class="block text-sm font-medium text-blue-800 mb-1">
                    Tiempo Límite (minutos)
                </label>
                <div class="relative">
                    <input type="number"
                        id="duration"
                        name="duration"
                        value="{{ old('duration', $exam->duration ?? 0) }}"
                        min="0"
                        class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                        placeholder="0 = sin límite">
                    <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600">min</span>
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <p class="text-xs text-blue-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    0 = tiempo ilimitado
                </p>
            </div>

            <!-- Intentos máximos -->
            <div>
                <label for="max_attempts" class="block text-sm font-medium text-blue-800 mb-1">
                    Intentos Máximos
                </label>
                <div class="relative">
                    <input type="number"
                        id="max_attempts"
                        name="max_attempts"
                        value="{{ old('max_attempts', $exam->max_attempts ?? 3) }}"
                        min="0"
                        class="w-full px-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200"
                        placeholder="0 = ilimitados"
                    >
                    @error('max_attempts')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <p class="text-xs text-blue-600 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span id="attempts-display">3</span> intentos permitidos
                    <span id="unlimited-attempts" class="hidden">(Ilimitados)</span>
                </p>
            </div>
        </div>

        <!-- Opciones adicionales -->
        <div class="mt-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Mostrar resultados -->
                <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-blue-200">
                    <div class="flex items-center">
                        <input type="checkbox" id="show_results" name="show_results" value="1" {{ old('show_results', $exam->show_results ?? true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        @error('show_results')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <label for="show_results" class="ml-3 text-sm font-medium text-blue-900">
                            Mostrar resultados al finalizar
                        </label>
                    </div>
                    <div class="text-xs text-blue-700">
                        Los estudiantes verán sus respuestas
                    </div>
                </div>

                <!-- Preguntas aleatorias -->
                <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-blue-200">
                    <div class="flex items-center">
                        <input type="checkbox" id="randomize_questions" name="randomize_questions" value="1" {{ old('randomize_questions', $exam->randomize_questions ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        @error('randomize_questions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <label for="randomize_questions" class="ml-3 text-sm font-medium text-blue-900">
                            Preguntas aleatorias
                        </label>
                    </div>
                    <div class="text-xs text-blue-700">
                        Mezclar orden de preguntas
                    </div>
                </div>

                <!-- Examen final -->
                <div class="flex items-center justify-between p-4 bg-white rounded-xl border border-blue-200">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_final" name="is_final" value="1" {{ old('is_final', $exam->is_final ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        @error('is_final')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <label for="is_final" class="ml-3 text-sm font-medium text-blue-900">
                            Examen Final
                        </label>
                    </div>
                    <div class="text-xs text-blue-700">
                        Requiere aprobación para completar curso
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado del Examen -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-lg font-semibold text-green-900">Estado del Examen</h4>
                <p class="text-sm text-green-700 mt-1" id="status-text">
                    {{ isset($exam) && $exam->is_active ? 'Activo - Los estudiantes pueden tomarlo' : 'Inactivo - Solo visible en administración' }}
                </p>
            </div>
            <div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', isset($exam) ? $exam->is_active : true) ? 'checked' : '' }} class="sr-only peer">
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="relative w-11 h-6 bg-red-500 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    <span class="ml-3 text-sm font-medium text-green-900">
                        {{ isset($exam) && $exam->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </label>
            </div>
        </div>
    </div>

    <!-- Botones del formulario -->
    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
        <a href="{{ route('admin.exams.index') }}"
           class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
            Cancelar
        </a>
        <button type="submit"
                class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
            <i class="fas fa-save"></i>
            {{ isset($exam) ? 'Actualizar Examen' : 'Crear Examen' }}
        </button>
    </div>
</div>
