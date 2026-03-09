@extends('layouts.admin')
@section('title', 'Editar Paquete: ' . $package->title)
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="packageForm({{ 
    json_encode([
        'package' => [
            'id'                => $package->id,
            'name'              => $package->title,
            'slug'              => $package->slug,
            'plan_type_id'      => $package->plan_type_id,
            'description'       => $package->description,
            'meta_description'  => $package->meta_description,
            'meta_keywords'     => $package->meta_keywords,
            'which_includes'    => $package->which_includes ?? [],
            'price'             => $package->price,
            'promotion_price'   => $package->promotion_price,
            'seats_min'         => $package->seats_min ?? 1,
            'seats_max'         => $package->seats_max ?? 1,
            'course_limit'      => $package->course_limit ?? 0,
            'is_active'         => $package->is_active,
            'image_url'         => $package->image_url,
            'courses'           => $package->courses->map(function($course) {
                return [
                    'id'        => $course->id,
                    'quantity'  => $course->pivot->quantity ?? 1,
                ];
            })->values(),
            'categories' => $package->categories->map(function($category) {
                return [
                    'id'                        => $category->id,
                    'max_courses_per_category'  => $category->pivot->max_courses_per_category
                ];
            })->values()
        ]
    ]) 
}})" x-init="init()" @keydown.escape="showCourseModal = false">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.packages.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Volver a la lista</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Editar Paquete: {{ $package->title }}</h1>
    </div>

    <!-- Formulario -->
    <form @submit.prevent="submitForm" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Columna Izquierda -->
            <div class="space-y-6">
                <!-- Nombre del Paquete -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Paquete <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" x-model="form.name" @input="generateSlug" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Ej: Paquete Desarrollo Web Completo" required>
                    <p class="text-xs text-gray-500 mt-1" x-show="form.slug">
                        Slug: <span x-text="form.slug" class="font-mono"></span>
                    </p>
                </div>

                <!-- Tipo Plan -->
                <div>
                    <label for="plan_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Plan
                    </label>
                    <select name="plan_type_id" id="plan_type_id" x-model="form.plan_type_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">--Seleccione--</option>
                        @foreach ($planType as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="description" x-model="form.description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe el contenido del paquete..."></textarea>
                </div>

                <!-- Meta Descripción (SEO) -->
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Descripción (SEO)
                    </label>
                    <textarea id="meta_description" x-model="form.meta_description" rows="2" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Breve descripción para motores de búsqueda..."></textarea>
                    <p class="text-xs text-gray-500 mt-1" x-text="(form.meta_description?.length || 0) + '/500 caracteres'"></p>
                </div>

                <!-- Meta Keywords -->
                <div>
                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Keywords (SEO)
                    </label>
                    <input type="text" id="meta_keywords" x-model="form.meta_keywords" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="ej: cursos, desarrollo, programación">
                    <p class="text-xs text-gray-500 mt-1">Separar con comas</p>
                </div>

                <!-- ¿Qué incluye este paquete? (NUEVO: Copiado de create.blade.php) -->
                <div class="mb-6" x-data="arrayField()">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        ¿Qué incluye este paquete? *
                    </label>
                    <div class="space-y-3" id="which_includes_container">
                        <template x-for="(item, index) in form.which_includes" :key="index">
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <input type="text" 
                                           name="which_includes[]" 
                                           x-model="form.which_includes[index]"
                                           placeholder="Ej: Crear aplicaciones web modernas" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                </div>
                                <button type="button" 
                                        x-show="index > 0" 
                                        @click="removeWhichInclude(index)" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        
                        <!-- Template para cuando no hay items -->
                        <div x-show="form.which_includes.length === 0" class="text-center py-4 text-gray-500">
                            No hay elementos agregados
                        </div>
                    </div>
                    <button type="button" @click="addWhichInclude" class="mt-3 flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Agregar otro elemento
                    </button>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="space-y-6">
                <!-- Precio y Cupos -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="seats_min" class="block text-sm font-medium text-gray-700 mb-2">
                            Cupos mínimos <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="seats_min" x-model="form.seats_min" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="1" required>
                    </div>
                    <div>
                        <label for="seats_max" class="block text-sm font-medium text-gray-700 mb-2">
                            Cupos máximos <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="seats_max" x-model="form.seats_max" @input="calculatePrices" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="10" required>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Precio (S/) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">S/</span>
                            <input type="number" id="price" x-model="form.price" @input="calculatePrices" step="0.01" min="0" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00" required>
                        </div>
                    </div>
                    <div>
                        <label for="course_limit" class="block text-sm font-medium text-gray-700 mb-2">
                            Límite de cursos para este paquete <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="course_limit" x-model="form.course_limit" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                    </div>
                </div>

                <!-- Precio por Persona (solo lectura) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Precio por Persona
                    </label>
                    <p class="text-2xl font-bold text-blue-600" x-text="formatPrice(pricePerPerson)">S/ 0.00</p>
                </div>

                <!-- Precio Promocional -->
                <div>
                    <label for="promotion_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio Promocional (S/)
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">S/</span>
                        <input type="number" id="promotion_price" x-model="form.promotion_price" @input="validatePromotionPrice" step="0.01" min="0" :max="form.price" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                    </div>
                    <p x-show="form.promotion_price && parseFloat(form.promotion_price) >= parseFloat(form.price)" 
                       class="text-xs text-red-500 mt-1">
                        El precio promocional debe ser menor al precio regular
                    </p>
                </div>

                <!-- Imagen de Portada -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Imagen de Portada
                    </label>
                    <div class="mt-1 flex flex-col sm:flex-row items-start gap-4">
                        <!-- Previsualización de imagen actual o nueva -->
                        <div class="flex-shrink-0 h-32 w-48 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="h-full w-full object-cover">
                            </template>
                            <template x-if="!imagePreview && form.image_url">
                                <img :src="form.image_url" class="h-full w-full object-cover">
                            </template>
                            <template x-if="!imagePreview && !form.image_url">
                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image text-4xl"></i>
                                </div>
                            </template>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="image" name="image" @change="previewImage" accept="image/jpeg,image/png,image/jpg,image/gif" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB</p>
                            <p x-show="form.image_url && !imagePreview" class="text-xs text-blue-500 mt-1">
                                <i class="fas fa-info-circle"></i> Deja en blanco para mantener la imagen actual
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Estado Activo -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" x-model="form.is_active" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Paquete activo
                    </label>
                </div>
            </div>
        </div>

        <!-- Sección de Cursos EspecíficOS -->
        <div class="mt-8 border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-book text-blue-600"></i>
                    Cursos Específicos Incluidos
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full" x-show="form.courses.length > 0" x-text="form.courses.length"></span>
                </h2>
                <button type="button" @click="openCourseModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition duration-150">
                    <i class="fas fa-plus-circle"></i>
                    <span>Agregar Cursos</span>
                </button>
            </div>
            
            <!-- Lista de cursos seleccionados CON SCROLL -->
            <div class="space-y-3" x-show="form.courses.length > 0">
                <!-- Cabecera de la lista (visible en desktop) -->
                <div class="hidden sm:flex gap-3 px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex-1">Curso</div>
                    <div class="w-10"></div>
                </div>
                
                <!-- Contenedor con scroll -->
                <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
                    x-ref="coursesContainer">
                    <div class="space-y-3 p-1">
                        <template x-for="(course, index) in form.courses" :key="index">
                            <div class="flex flex-col sm:flex-row gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition duration-150">
                                <div class="flex-1 flex items-center gap-3">
                                    <i class="fas fa-grip-vertical text-gray-400 cursor-move"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate" x-text="getCourseName(course.id)" :title="getCourseName(course.id)"></p>
                                        <p class="text-xs text-gray-500">ID: <span x-text="course.id"></span></p>
                                    </div>
                                </div>
                                <button type="button" @click="removeCourse(index)" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition duration-150 self-end sm:self-center">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Resumen de cursos seleccionados -->
                <div class="flex justify-between items-center mt-3 px-4 py-2 bg-gray-100 rounded-lg text-sm">
                    <span class="text-gray-600">Total de cursos:</span>
                    <span class="font-semibold text-blue-600" x-text="form.courses.length"></span>
                </div>
            </div>
            
            <!-- Estado vacío -->
            <div x-show="form.courses.length === 0" 
                class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <i class="fas fa-book-open text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500 mb-2">No hay cursos seleccionados</p>
                <button type="button" @click="openCourseModal" class="text-blue-600 hover:text-blue-800 font-medium">
                    + Agregar cursos al paquete
                </button>
            </div>
        </div>

        <!-- Sección de Categorías -->
        <div class="mt-8 border-t pt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-folder text-purple-600"></i>
                    Categorías Incluidas
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full" x-show="form.categories.length > 0" x-text="form.categories.length"></span>
                </h2>
                <button type="button" @click="addCategory" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition duration-150">
                    <i class="fas fa-plus-circle"></i>
                    <span>Agregar Categoría</span>
                </button>
            </div>
            
            <!-- Contenedor con scroll para categorías -->
            <div class="space-y-3" x-show="form.categories.length > 0">
                <!-- Cabecera -->
                <div class="hidden sm:flex gap-3 px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <div class="flex-1">Categoría</div>
                    <div class="sm:w-48">Máx. Cursos</div>
                    <div class="w-10"></div>
                </div>
                
                <!-- Scroll container -->
                <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-lg scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <div class="space-y-3 p-1">
                        <template x-for="(category, index) in form.categories" :key="index">
                            <div class="flex flex-col sm:flex-row gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition duration-150">
                                <div class="flex-1">
                                    <select x-model="category.id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">
                                            {{ $cat->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:w-48">
                                    <input type="number" x-model="category.max_courses_per_category" placeholder="Máx. cursos" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                </div>
                                <button type="button" @click="removeCategory(index)" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition duration-150 self-end sm:self-center">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Resumen -->
                <div class="flex justify-between items-center mt-3 px-4 py-2 bg-gray-100 rounded-lg text-sm">
                    <span class="text-gray-600">Total de categorías:</span>
                    <span class="font-semibold text-purple-600" x-text="form.categories.length"></span>
                </div>
            </div>
            
            <!-- Estado vacío categorías -->
            <div x-show="form.categories.length === 0" 
                class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <p class="text-gray-500">No hay categorías agregadas</p>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-end">
            <a href="{{ route('admin.packages.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center">
                Cancelar
            </a>
            <button type="submit" :disabled="loading" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <i x-show="!loading" class="fas fa-save"></i>
                <i x-show="loading" class="fas fa-spinner fa-spin"></i>
                <span x-text="loading ? 'Actualizando...' : 'Actualizar Paquete'"></span>
            </button>
        </div>
    </form>

    <!-- MODAL PARA SELECCIONAR CURSOS -->
    <div x-show="showCourseModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCourseModal = false"></div>
        
        <!-- Modal Panel -->
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full" @click.stop>
                <!-- Header del Modal -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-book text-blue-600"></i>
                            Seleccionar Cursos
                        </h3>
                        <button @click="showCourseModal = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Filtros -->
                <div class="p-6 border-b border-gray-200 bg-white">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" x-model="courseSearch" placeholder="Buscar cursos..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="sm:w-64">
                            <select x-model="categoryFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Barra de herramientas -->
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="selectAllFiltered" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                            <i class="fas fa-solid fa-check"></i>
                            <span>Seleccionar todos</span>
                        </button>
                        <span class="text-gray-300">|</span>
                        <button type="button" @click="deselectAll" class="text-sm text-gray-600 hover:text-gray-800 font-medium flex items-center gap-1">
                            <i class="fas fa-times"></i>
                            <span>Deseleccionar todos</span>
                        </button>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span x-text="selectedCourses.length"></span> de <span x-text="filteredCourses.length"></span> cursos seleccionados
                    </div>
                </div>
                
                <!-- Lista de Cursos -->
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="course in filteredCourses" :key="course.id">
                            <div class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" :value="course.id" x-model="selectedCourses" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 flex-1">
                                    <label class="font-medium text-gray-900 cursor-pointer" x-text="course.title"></label>
                                    <p class="text-sm text-gray-500" x-text="course.category ? course.category.name : 'Sin categoría'"></p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <span class="inline-flex items-center gap-1">
                                            <i class="fas fa-users"></i>
                                            <span x-text="course.students_count || 0"></span>
                                        </span>
                                        <span class="mx-2">•</span>
                                        <span>S/ <span x-text="course.price ? course.price.toFixed(2) : '0.00'"></span></span>
                                    </p>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Mensaje sin resultados -->
                        <div x-show="filteredCourses.length === 0" class="col-span-2 text-center py-12">
                            <i class="fas fa-search text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No se encontraron cursos</p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer del Modal -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold" x-text="selectedCourses.length"></span> cursos seleccionados
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="showCourseModal = false" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-150">
                                Cancelar
                            </button>
                            <button type="button" @click="addSelectedCourses" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 flex items-center gap-2">
                                <i class="fas fa-check"></i>
                                <span>Agregar (<span x-text="selectedCourses.length"></span>)</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <div x-show="alert.show" x-cloak x-transition :class="alert.type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'" class="fixed top-4 right-4 border px-4 py-3 rounded-lg shadow-lg z-50">
        <div class="flex items-center gap-2">
            <i :class="alert.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
            <span x-text="alert.message"></span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function packageForm(initialData) {
    return {
        form: {
            id: initialData?.package?.id || null,
            name: initialData?.package?.name || '',
            slug: initialData?.package?.slug || '',
            plan_type_id: initialData?.package?.plan_type_id || '',
            description: initialData?.package?.description || '',
            meta_description: initialData?.package?.meta_description || '',
            meta_keywords: initialData?.package?.meta_keywords || '',
            which_includes: initialData?.package?.which_includes || [''],
            price: initialData?.package?.price || '',
            promotion_price: initialData?.package?.promotion_price || '',
            seats_min: initialData?.package?.seats_min || '',
            seats_max: initialData?.package?.seats_max || '',
            course_limit: initialData?.package?.course_limit || '',
            is_active: initialData?.package?.is_active ?? true,
            image_url: initialData?.package?.image_url || null,
            courses: initialData?.package?.courses || [],
            categories: initialData?.package?.categories || []
        },
        
        // Control del modal
        showCourseModal: false,
        courseSearch: '',
        categoryFilter: '',
        selectedCourses: [],
        
        // Imagen
        imagePreview: null,
        imageFile: null,
        
        availableCourses: @json($allCourses),
        
        loading: false,
        pricePerPerson: 0,
        alert: {
            show: false,
            type: 'success',
            message: ''
        },

        // Computed: cursos filtrados
        get filteredCourses() {
            return this.availableCourses.filter(course => {
                const matchesSearch = course.title.toLowerCase().includes(this.courseSearch.toLowerCase());
                const matchesCategory = !this.categoryFilter || course.category?.id == this.categoryFilter;
                return matchesSearch && matchesCategory;
            });
        },

        init() {
            // Asegurar que which_includes tenga al menos un elemento vacío si está vacío
            if (!this.form.which_includes || this.form.which_includes.length === 0) {
                this.form.which_includes = [''];
            }
            this.calculatePrices();
        },

        generateSlug() {
            this.form.slug = this.form.name
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        },

        calculatePrices() {
            if (this.form.price && this.form.seats_max) {
                this.pricePerPerson = this.form.price / this.form.seats_max;
            }
        },

        validatePromotionPrice() {
            if (this.form.promotion_price && this.form.price) {
                if (parseFloat(this.form.promotion_price) >= parseFloat(this.form.price)) {
                    this.form.promotion_price = (parseFloat(this.form.price) - 0.01).toFixed(2);
                }
            }
        },

        // NUEVO: Agregar elemento a which_includes
        addWhichInclude() {
            this.form.which_includes.push('');
        },

        // NUEVO: Eliminar elemento de which_includes
        removeWhichInclude(index) {
            this.form.which_includes.splice(index, 1);
        },

        // Previsualizar imagen
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.imagePreview = null;
                this.imageFile = null;
            }
        },

        getCourseName(courseId) {
            const course = this.availableCourses.find(c => c.id == courseId);
            return course ? course.title : 'Curso no encontrado';
        },

        openCourseModal() {
            this.courseSearch = '';
            this.categoryFilter = '';
            this.selectedCourses = this.form.courses.map(c => c.id);
            this.showCourseModal = true;
        },

        selectAllFiltered() {
            this.selectedCourses = this.filteredCourses.map(course => course.id);
        },

        deselectAll() {
            this.selectedCourses = [];
        },

        addSelectedCourses() {
            this.selectedCourses.forEach(courseId => {
                const exists = this.form.courses.some(c => c.id == courseId);
                if (!exists) {
                    this.form.courses.push({
                        id: courseId,
                        quantity: 1
                    });
                }
            });
            
            this.showCourseModal = false;
            this.selectedCourses = [];
        },

        removeCourse(index) {
            this.form.courses.splice(index, 1);
        },

        addCategory() {
            this.form.categories.push({
                id: '',
                max_courses_per_category: null
            });
        },

        removeCategory(index) {
            this.form.categories.splice(index, 1);
        },

        formatPrice(value) {
            return 'S/ ' + parseFloat(value || 0).toFixed(2);
        },

        showAlert(type, message) {
            this.alert = {
                show: true,
                type: type,
                message: message
            };
            
            setTimeout(() => {
                this.alert.show = false;
            }, 3000);
        },

        submitForm() {
            this.loading = true;

            // Filtrar which_includes vacíos
            const filteredWhichIncludes = this.form.which_includes.filter(item => item && item.trim() !== '');
            
            if (filteredWhichIncludes.length === 0) {
                this.showAlert('error', 'Debes agregar al menos un elemento en "¿Qué incluye este paquete?"');
                this.loading = false;
                return;
            }

            const formData = new FormData();
            
            // Agregar campos básicos con _method para PUT
            formData.append('_method', 'PUT');
            formData.append('name', this.form.name);
            formData.append('slug', this.form.slug);
            formData.append('plan_type_id', this.form.plan_type_id);
            formData.append('description', this.form.description || '');
            formData.append('meta_description', this.form.meta_description || '');
            formData.append('meta_keywords', this.form.meta_keywords || '');
            formData.append('which_includes', JSON.stringify(filteredWhichIncludes));
            formData.append('price', this.form.price);
            formData.append('course_limit', this.form.course_limit || 0);
            formData.append('seats_min', this.form.seats_min);
            formData.append('seats_max', this.form.seats_max);
            formData.append('is_active', this.form.is_active ? '1' : '0');
            
            if (this.form.promotion_price) {
                formData.append('promotion_price', this.form.promotion_price);
            }
            
            // Agregar imagen si se seleccionó una nueva
            if (this.imageFile) {
                formData.append('image', this.imageFile);
            }
            
            // Preparar cursos
            const validCourses = this.form.courses
                .filter(c => c.id)
                .map(c => ({
                    id: c.id,
                    quantity: c.quantity || 1
                }));
            formData.append('courses', JSON.stringify(validCourses));
            
            // Preparar categorías
            const validCategories = this.form.categories
                .filter(c => c.id)
                .map(c => ({
                    id: c.id,
                    max_courses_per_category: c.max_courses_per_category || null
                }));
            formData.append('categories', JSON.stringify(validCategories));

            // CORREGIDO: URL sin /update al final
            axios.post(`/admin/packages/${this.form.id}/update`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if (response.data.success) {
                    this.showAlert('success', response.data.message);
                    setTimeout(() => {
                        window.location.href = response.data.redirect;
                    }, 1000);
                }
            })
            .catch(error => {
                this.loading = false;
                
                if (error.response?.data?.errors) {
                    const errors = Object.values(error.response.data.errors).flat();
                    this.showAlert('error', errors[0]);
                    console.error('Errores de validación:', error.response.data.errors);
                } else {
                    this.showAlert('error', error.response?.data?.message || 'Error al actualizar el paquete');
                }
            });
        }

        // submitForm() {
        //     this.loading = true;

        //     // Filtrar which_includes vacíos
        //     const filteredWhichIncludes = this.form.which_includes.filter(item => item && item.trim() !== '');
            
        //     if (filteredWhichIncludes.length === 0) {
        //         this.showAlert('error', 'Debes agregar al menos un elemento en "¿Qué incluye este paquete?"');
        //         this.loading = false;
        //         return;
        //     }

        //     const formData = new FormData();
            
        //     // Agregar campos básicos con _method para PUT
        //     formData.append('_method', 'PUT');
        //     formData.append('name', this.form.name);
        //     formData.append('slug', this.form.slug);
        //     formData.append('description', this.form.description || '');
        //     formData.append('meta_description', this.form.meta_description || '');
        //     formData.append('meta_keywords', this.form.meta_keywords || '');
        //     formData.append('which_includes', JSON.stringify(filteredWhichIncludes));
        //     formData.append('price', this.form.price);
        //     formData.append('course_limit', this.form.course_limit || 0);
        //     formData.append('seats_min', this.form.seats_min);
        //     formData.append('seats_max', this.form.seats_max);
        //     formData.append('is_active', this.form.is_active ? '1' : '0');
            
        //     if (this.form.promotion_price) {
        //         formData.append('promotion_price', this.form.promotion_price);
        //     }
            
        //     // Agregar imagen si se seleccionó una nueva
        //     if (this.imageFile) {
        //         formData.append('image', this.imageFile);
        //     }
            
        //     // Preparar cursos
        //     const validCourses = this.form.courses
        //         .filter(c => c.id)
        //         .map(c => ({
        //             id: c.id,
        //             quantity: c.quantity || 1
        //         }));
        //     formData.append('courses', JSON.stringify(validCourses));
            
        //     // Preparar categorías
        //     const validCategories = this.form.categories
        //         .filter(c => c.id)
        //         .map(c => ({
        //             id: c.id,
        //             max_courses_per_category: c.max_courses_per_category || null
        //         }));
        //     formData.append('categories', JSON.stringify(validCategories));

        //     axios.post(`/admin/packages/${this.form.id}/update`, formData, {
        //         headers: {
        //             'Content-Type': 'multipart/form-data'
        //         }
        //     })
        //     .then(response => {
        //         if (response.data.success) {
        //             this.showAlert('success', response.data.message);
        //             setTimeout(() => {
        //                 window.location.href = response.data.redirect;
        //             }, 1000);
        //         }
        //     })
        //     .catch(error => {
        //         this.loading = false;
                
        //         if (error.response?.data?.errors) {
        //             const errors = Object.values(error.response.data.errors).flat();
        //             this.showAlert('error', errors[0]);
        //             console.error('Errores de validación:', error.response.data.errors);
        //         } else {
        //             this.showAlert('error', error.response?.data?.message || 'Error al actualizar el paquete');
        //         }
        //     });
        // }
    }
}

// Helper para manejar arrays dinámicos
function arrayField() {
    return {
        // Mantenemos la función para compatibilidad, pero usamos los métodos del componente principal
    };
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    .scrollbar-thin {
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 #f1f1f1;
    }
</style>
@endsection