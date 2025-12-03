@csrf

@if(isset($course) && $course->exists)
    @method('PUT')
@endif

<div class="space-y-6 mb-6 p-4">
    <!-- Información Básica -->
    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Título del Curso *
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title', $course->title ?? '') }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug (se puede generar automáticamente) -->
            <div class="col-span-2">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                    Slug (URL amigable)
                </label>
                <input type="text"
                       name="slug"
                       id="slug"
                       value="{{ old('slug', $course->slug ?? '') }}"
                       placeholder="Se genera automáticamente"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Categoría *
                </label>
                <select name="category_id"
                        id="category_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <option value="">Seleccionar categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ old('category_id', $course->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructor -->
            <div>
                <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Instructor *
                </label>
                <select name="instructor_id"
                        id="instructor_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <option value="">Seleccionar instructor</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}"
                                {{ old('instructor_id', $course->instructor_id ?? '') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->names }}
                        </option>
                    @endforeach
                </select>
                @error('instructor_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nivel -->
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-1">
                    Nivel *
                </label>
                <select name="level" id="level" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    <option value="">Seleccionar nivel</option>
                    <option value="beginner" {{ old('level', $course->level ?? '') == 'beginner' ? 'selected' : '' }}>Principiante</option>
                    <option value="intermediate" {{ old('level', $course->level ?? '') == 'intermediate' ? 'selected' : '' }}>Intermedio</option>
                    <option value="advanced" {{ old('level', $course->level ?? '') == 'advanced' ? 'selected' : '' }}>Avanzado</option>
                    <option value="all" {{ old('level', $course->level ?? '') == 'all' ? 'selected' : '' }}>Todos los niveles</option>
                </select>
                @error('level')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Duración -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                    Duración (horas) *
                </label>
                <input type="number"
                    name="duration"
                    id="duration"
                    value="{{ old('duration', $course->duration ?? '') }}"
                    min="1"
                    step="0.5"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                @error('duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Información de Precios -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">Información de Precios</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Precio Regular -->
            <div>
                <label for="price" class="block text-sm font-medium text-blue-800 mb-1">
                    Precio Regular *
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">S/</span>
                    <input type="number"
                        name="price"
                        id="price"
                        value="{{ old('price', $course->price ?? '0') }}"
                        min="0"
                        step="0.01"
                        required
                        class="w-full pl-10 pr-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Precio en Promoción -->
            <div>
                <label for="promotion_price" class="block text-sm font-medium text-blue-800 mb-1">
                    Precio en Promoción
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">S/</span>
                    <input type="number"
                        name="promotion_price"
                        id="promotion_price"
                        value="{{ old('promotion_price', $course->promotion_price ?? '') }}"
                        min="0"
                        step="0.01"
                        placeholder="Opcional"
                        class="w-full pl-10 pr-4 py-3 border border-blue-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                </div>
                @error('promotion_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Indicador de Promoción -->
            <div class="col-span-2">
                <div class="flex items-center p-3 bg-white rounded-lg border border-blue-200">
                    <div class="flex items-center h-5">
                        <input type="checkbox"
                            id="is_on_promotion"
                            disabled
                            {{ (isset($course) && $course->is_on_promotion) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_on_promotion" class="font-medium text-blue-900">
                            Mostrar como curso en promoción
                        </label>
                        <p class="text-blue-700">
                            Se activa automáticamente cuando el precio en promoción es menor al precio regular.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Descripción y Contenido -->
    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Descripción y Contenido</h3>

        <!-- Descripción -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Descripción del Curso *
            </label>
            <textarea name="description"
                id="description"
                rows="4"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">{{ old('description', $course->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lo que aprenderán (Array) -->
        <div class="mb-6" x-data="arrayField()">
            <label class="block text-sm font-medium text-gray-700 mb-3">
                ¿Qué aprenderán los estudiantes? *
            </label>
            <div class="space-y-3" id="what_you_learn_container">
                @php
                    $learnItems = old('what_you_learn', $course->what_you_learn ?? ['']);
                    $learnItems = is_array($learnItems) ? $learnItems : [];
                @endphp

                @foreach($learnItems as $index => $item)
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <input type="text" name="what_you_learn[]" value="{{ $item }}" placeholder="Ej: Crear aplicaciones web modernas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        </div>
                        @if($index > 0)
                            <button type="button" @click="removeItem($event)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
            <button type="button" @click="addItem('what_you_learn_container')" class="mt-3 flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Agregar otro elemento
            </button>
            @error('what_you_learn')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Requisitos (Array) -->
        <div x-data="arrayField()">
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Requisitos del Curso
            </label>
            <div class="space-y-3" id="requirements_container">
                @php
                    $requirements = old('requirements', $course->requirements ?? ['']);
                    $requirements = is_array($requirements) ? $requirements : [];
                @endphp

                @foreach($requirements as $index => $requirement)
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <input type="text"
                                   name="requirements[]"
                                   value="{{ $requirement }}"
                                   placeholder="Ej: Conocimientos básicos de programación"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        </div>
                        @if($index > 0)
                            <button type="button"
                                    @click="removeItem($event)"
                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
            <button type="button" @click="addItem('requirements_container')" class="mt-3 flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Agregar requisito
            </button>
            @error('requirements')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Imagen y Estado -->
    <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Imagen del Curso -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Imagen del Curso
                </label>

                @if(isset($course) && $course->image_url)
                    <div class="mb-4">
                        <img src="{{ Storage::url($course->image_url) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded-xl border border-gray-300">
                    </div>
                @endif

                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 transition duration-200">
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="hidden"
                           onchange="previewImage(event)">

                    <label for="image" class="cursor-pointer">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-medium text-blue-600 hover:text-blue-500">
                                Sube una imagen
                            </span>
                            o arrastra y suelta
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            PNG, JPG, GIF hasta 5MB
                        </p>
                    </label>
                </div>

                <div id="imagePreview" class="mt-4 hidden">
                    <img id="preview" class="w-full h-48 object-cover rounded-xl border border-gray-300">
                </div>

                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Configuración -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    Configuración del Curso
                </label>

                <!-- Estado -->
                <div class="mb-4">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-300">
                        <div class="flex items-center">
                            <div class="flex items-center h-5">
                                <input type="checkbox"
                                       name="is_active"
                                       id="is_active"
                                       value="1"
                                       {{ old('is_active', isset($course) ? $course->is_active : true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3">
                                <label for="is_active" class="text-sm font-medium text-gray-700">
                                    Curso Activo
                                </label>
                                <p class="text-xs text-gray-500">
                                    El curso será visible para los estudiantes
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="h-3 w-3 rounded-full {{ (old('is_active', isset($course) ? $course->is_active : true)) ? 'bg-green-400' : 'bg-red-400' }}"></span>
                        </div>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Características -->
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                        <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Documentos</p>
                            <p class="text-xs text-blue-700">Podrás agregar documentos después</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200">
                        <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-900">Exámenes</p>
                            <p class="text-xs text-green-700">Configura exámenes después de crear</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
        <div>
            <a href="{{ route('admin.courses.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Cancelar
            </a>
        </div>
        <div class="flex items-center gap-4">
            <button type="button"
                    onclick="window.location.reload()"
                    class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                Reiniciar
            </button>
            <button type="submit"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                {{ isset($course) ? 'Actualizar Curso' : 'Crear Curso' }}
            </button>
        </div>
    </div>
</div>
<script>
    function arrayField() {
        return {
            addItem(containerId) {
                const container = document.getElementById(containerId);
                const newItem = document.createElement('div');
                newItem.className = 'flex items-center gap-3';
                newItem.innerHTML = `
                    <div class="flex-1">
                        <input type="text"
                               name="${containerId.replace('_container', '')}[]"
                               placeholder="${containerId.includes('what_you_learn') ? 'Ej: Crear aplicaciones web modernas' : 'Ej: Conocimientos básicos de programación'}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                    </div>
                    <button type="button"
                            onclick="this.parentElement.remove()"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                container.appendChild(newItem);
            },

            removeItem(event) {
                event.target.closest('.flex.items-center.gap-3').remove();
            }
        };
    }

    // Generar slug automáticamente
    document.getElementById('title')?.addEventListener('input', function(e) {
        const slugInput = document.getElementById('slug');
        if (slugInput && !slugInput.value) {
            const slug = e.target.value
                .toLowerCase()
                .replace(/[^\w\s]/gi, '')
                .replace(/\s+/g, '-');
            slugInput.value = slug;
        }
    });

    // Previsualización de imagen
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Validación de precios
    document.getElementById('promotion_price')?.addEventListener('input', function() {
        const price = parseFloat(document.getElementById('price').value) || 0;
        const promotion = parseFloat(this.value) || 0;
        const checkbox = document.getElementById('is_on_promotion');

        if (promotion > 0 && promotion < price) {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }
    });
</script>
