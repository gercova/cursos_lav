@csrf

@if(isset($course) && $course->exists)
    @method('PUT')
@endif

<div class="space-y-6 mb-6 p-4">
    <!-- Información Básica -->
    <input type="hidden" name="id" id="id" value="{{ $course->id ?? '' }}">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Información Básica</h3>
                <p class="text-xs text-gray-500 mt-0.5">Datos principales del curso</p>
            </div>
        </div>
        <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título del Curso *</label>
                <input type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $course->title ?? '') }}"
                    required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug (se puede generar automáticamente) -->
            <div class="col-span-2">
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                    Slug (URL amigable)
                    <span class="ml-1 text-xs font-normal text-gray-400">— se genera automáticamente</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm select-none">/</span>
                    <input type="text"
                        name="slug"
                        id="slug"
                        value="{{ old('slug', $course->slug ?? '') }}"
                        placeholder="mi-curso-ejemplo"
                        class="w-full pl-7 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900 font-mono text-sm">
                </div>
                @error('slug')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Meta-description (SEO) -->
            <div class="col-span-2">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                    Meta-description
                    <span class="ml-1 text-xs font-normal text-gray-400">SEO</span>
                </label>
                <input type="text" name="meta_description" id="meta_description"
                       value="{{ old('meta_description', $course->meta_description ?? '') }}"
                       placeholder="Breve descripción para motores de búsqueda (150-160 caracteres)"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900" required>
                @error('meta_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keywords (SEO) -->
            <div class="col-span-2">
                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                    Palabras clave
                    <span class="ml-1 text-xs font-normal text-gray-400">SEO keywords</span>
                </label>
                <textarea name="meta_keywords" id="meta_keywords" rows="2"
                          placeholder="palabra1, palabra2, palabra3"
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900 resize-none" required>{{ old('meta_keywords', $course->meta_keywords ?? '') }}</textarea>
                @error('meta_keywords')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                <select name="category_id" id="category_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white text-gray-900">
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
                <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-1">Instructor *</label>
                <select name="instructor_id"
                    id="instructor_id"
                    required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white text-gray-900">
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

            <!-- Duración -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duración (horas) *</label>
                <div class="relative">
                    <input type="number" name="duration" id="duration" value="{{ old('duration', $course->duration ?? '') }}" min="1" step="0.5" required
                           class="w-full px-4 py-3 pr-14 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white text-gray-900">
                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">hrs</span>
                </div>
                @error('duration')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        </div><!-- /p-6 -->
    </div>

    <!-- Información de Precios -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Información de Precios</h3>
                <p class="text-xs text-gray-500 mt-0.5">Precio regular y promociones</p>
            </div>
        </div>
        <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Precio Regular -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                    Precio Regular *
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">S/</span>
                    <input type="number" name="price" id="price" value="{{ old('price', $course->price ?? '0') }}" min="0" step="0.01" required
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white text-gray-900">
                </div>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Precio en Promoción -->
            <div>
                <label for="promotion_price" class="block text-sm font-medium text-gray-700 mb-1">
                    Precio en Promoción
                    <span class="ml-1 text-xs font-normal text-gray-400">opcional</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 font-medium text-sm">S/</span>
                    <input type="number" name="promotion_price" id="promotion_price" value="{{ old('promotion_price', $course->promotion_price ?? '') }}" min="0" step="0.01" placeholder="0.00"
                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white text-gray-900">
                </div>
                @error('promotion_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Indicador de Promoción -->
            <div class="col-span-2">
                <div class="flex items-center p-3 bg-white rounded-lg border border-blue-200">
                    <div class="flex items-center h-5">
                        <input type="checkbox" id="is_on_promotion" disabled {{ (isset($course) && $course->is_on_promotion) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
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
        </div><!-- /p-6 -->
    </div>

    <!-- Descripción y Contenido -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-violet-50 to-white">
            <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Descripción y Contenido</h3>
                <p class="text-xs text-gray-500 mt-0.5">Qué aprenderán y requisitos del curso</p>
            </div>
        </div>
        <div class="p-6">
        <!-- Descripción -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Descripción del Curso *
            </label>
            <textarea name="description" id="description" rows="4" required
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900 resize-y">{{ old('description', $course->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Resultados del Aprendizaje -->
        <div class="mb-6">
            <label for="learning_outcomes" class="block text-sm font-medium text-gray-700 mb-1">
                Resultados del Aprendizaje *
            </label>
            <textarea name="learning_outcomes" id="learning_outcomes" rows="4" required
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900 resize-y">{{ old('learning_outcomes', $course->learning_outcomes ?? '') }}</textarea>
            @error('learning_outcomes')
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
                            <input type="text" name="what_you_learn[]" value="{{ $item }}" placeholder="Ej: Crear aplicaciones web modernas" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900">
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
            <button type="button" @click="addItem('what_you_learn_container')" class="mt-3 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium text-sm">
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
                            <input type="text" name="requirements[]" value="{{ $requirement }}" placeholder="Ej: Conocimientos básicos de programación" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900">
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
            <button type="button" @click="addItem('requirements_container')" class="mt-3 inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Agregar requisito
            </button>
            @error('requirements')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        </div><!-- /p-6 -->
    </div>

    <!-- Imagen y Estado -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Imagen y Configuración</h3>
                <p class="text-xs text-gray-500 mt-0.5">Portada del curso y opciones de visibilidad</p>
            </div>
        </div>
        <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Imagen del Curso -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Imagen del Curso
                </label>

                @if(isset($course) && $course->image_url)
                    <div class="mb-4">
                        <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded-xl border border-gray-300">
                    </div>
                @endif

                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-200 group">
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <label for="image" class="cursor-pointer block">
                        <div class="w-12 h-12 bg-gray-100 group-hover:bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-3 transition-colors duration-200">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium text-indigo-600 group-hover:text-indigo-700">Selecciona una imagen</span>
                            <span class="text-gray-400"> o arrastra y suelta</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF — máx. 5MB</p>
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
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', isset($course) ? $course->is_active : true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
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

                <!-- Tipo de Curso: Normal vs. Capacitación -->
                <div class="mb-4"
                    x-data="{ isTraining: {{ old('is_training', isset($course) ? ($course->is_training ? 'true' : 'false') : 'false') }} }">
                    <div class="p-4 rounded-xl border transition-all duration-300"
                        :class="isTraining
                            ? 'bg-gradient-to-r from-amber-50 to-orange-50 border-amber-300'
                            : 'bg-gradient-to-r from-gray-50 to-white border-gray-300'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Ícono dinámico -->
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300"
                                    :class="isTraining ? 'bg-amber-100' : 'bg-gray-100'">
                                    <svg class="w-5 h-5 transition-colors duration-300"
                                        :class="isTraining ? 'text-amber-600' : 'text-gray-400'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium transition-colors duration-300"
                                        :class="isTraining ? 'text-amber-800' : 'text-gray-700'"
                                        x-text="isTraining ? 'Curso de Capacitación' : 'Curso Normal'">
                                    </p>
                                    <p class="text-xs transition-colors duration-300"
                                        :class="isTraining ? 'text-amber-600' : 'text-gray-500'"
                                        x-text="isTraining
                                            ? 'Orientado a formación corporativa y entrenamiento'
                                            : 'Curso estándar de aprendizaje individual'">
                                    </p>
                                </div>
                            </div>

                            <!-- Toggle switch -->
                            <button type="button" @click="isTraining = !isTraining"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="isTraining ? 'bg-amber-500 focus:ring-amber-400' : 'bg-gray-300 focus:ring-gray-400'"
                                role="switch" :aria-checked="isTraining.toString()">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-md ring-0 transition-transform duration-300"
                                    :class="isTraining ? 'translate-x-6' : 'translate-x-1'"></span>
                            </button>
                        </div>

                        <!-- Badge indicador -->
                        <div x-show="isTraining"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="mt-3 flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-100 w-fit px-2.5 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Capacitación activa
                        </div>
                    </div>

                    <!-- Input oculto que envía el valor real al servidor -->
                    <input type="hidden" name="is_training" :value="isTraining ? '1' : '0'">

                    @error('is_training')
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
        </div><!-- /p-6 -->
    </div>

    <!-- Botones de acción -->
    <div class="flex items-center justify-between pt-6 border-t border-gray-100 mt-2">
        <div>
            <a href="{{ route('admin.courses.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 rounded-xl font-medium transition-all duration-200 text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Cancelar
            </a>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.location.reload()"
                    class="px-5 py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 rounded-xl font-medium transition-all duration-200 text-sm shadow-sm">
                Reiniciar
            </button>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-semibold py-2.5 px-7 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(isset($course))
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    @endif
                </svg>
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
                        <input type="text" name="${containerId.replace('_container', '')}[]" placeholder="${containerId.includes('what_you_learn') ? 'Ej: Crear aplicaciones web modernas' : 'Ej: Conocimientos básicos de programación'}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition-all duration-200 bg-gray-50 focus:bg-white placeholder-gray-400 text-gray-900">
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

    const input     = document.getElementById('title');
    const output    = document.getElementById('slug');

    input.addEventListener('input', () => {
        output.value = slugify(input.value);
    });


    function slugify(text) {
        return text
            .normalize('NFD')                     // Descompone letras con acentos (ej. á → a + ◌́)
            .replace(/[\u0300-\u036f]/g, '')     // Elimina los diacríticos (acentos, virgulillas, etc.)
            .toLowerCase()                        // Convierte a minúsculas
            .replace(/[^a-z0-9\s-]/g, '')         // Elimina todo excepto letras, números, espacios y guiones
            .replace(/[\s-]+/g, '-')              // Reemplaza espacios y múltiples guiones por un solo guion
            .replace(/^-+|-+$/g, '');             // Elimina guiones al inicio o al final
    }

    // Previsualización de imagen
    function previewImage(event) {
        const input             = event.target;
        const preview           = document.getElementById('preview');
        const previewContainer  = document.getElementById('imagePreview');

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
