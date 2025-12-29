@extends('layouts.student')
@section('title', 'Mi Lista de Deseos')
@section('content')
<div x-data="wishlistApp()" x-init="init()" class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mi Lista de Deseos</h1>
                    <p class="text-gray-600 mt-2">Guarda los cursos que te interesan para más tarde</p>
                </div>

                <div class="flex items-center space-x-3">
                    <span id="wishlist-count" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        {{ $wishlistItems->count() }} cursos
                    </span>

                    @if($wishlistItems->count() > 0)
                    <button @click="confirmClearAll()"
                            class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Limpiar lista
                    </button>
                    @endif
                </div>
            </div>
        </div>

        @if($wishlistItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Lista de cursos -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Filtros y ordenamiento -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center space-x-2">
                                <button @click="filter = 'all'"
                                        :class="filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    Todos
                                </button>
                                <button @click="filter = 'on_sale'"
                                        :class="filter === 'on_sale' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    En oferta
                                </button>
                                <button @click="filter = 'newest'"
                                        :class="filter === 'newest' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    Nuevos
                                </button>
                            </div>

                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Ordenar por:</span>
                                <select x-model="sortBy" @change="applyFilters()"
                                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="added_date">Fecha agregado</option>
                                    <option value="price_low">Precio: menor a mayor</option>
                                    <option value="price_high">Precio: mayor a menor</option>
                                    <option value="rating">Mejor valorados</option>
                                    <option value="popular">Más populares</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de cursos -->
                    <div class="divide-y divide-gray-100">
                        <template x-for="(course, index) in filteredCourses" :key="course.id">
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200 group">
                                <div class="flex flex-col md:flex-row gap-4 md:items-center">
                                    <!-- Imagen del curso -->
                                    <div class="flex-shrink-0 w-full md:w-40 h-48 md:h-32">
                                        <a :href="'/curso/' + course.id" class="block relative">
                                            <img :src="course.image_url || 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'"
                                                 :alt="course.title"
                                                 class="w-full h-full object-cover rounded-lg">

                                            <!-- Badges -->
                                            <div class="absolute top-2 left-2 space-y-1">
                                                <span x-show="course.is_on_promotion"
                                                      class="block px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">
                                                    -<span x-text="Math.round(((course.price - course.promotion_price) / course.price) * 100)"></span>%
                                                </span>
                                                <span class="block px-2 py-1 bg-blue-500 text-white text-xs font-bold rounded">
                                                    <span x-text="course.category_name"></span>
                                                </span>
                                            </div>

                                            <!-- Botón de vista rápida -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                <a :href="'/curso/' + course.id"
                                                   class="px-4 py-2 bg-white text-gray-900 rounded-lg font-medium hover:bg-gray-100 transition-colors duration-200">
                                                    Ver detalles
                                                </a>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Información del curso -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                    <a :href="'/curso/' + course.id" class="hover:text-blue-600 transition-colors duration-200"
                                                       x-text="course.title"></a>
                                                </h3>

                                                <p class="text-sm text-gray-600 mb-2" x-text="course.instructor_name"></p>

                                                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                                        <span x-text="course.rating || '4.8'"></span>
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-users mr-1"></i>
                                                        <span x-text="course.students_count + ' estudiantes'"></span>
                                                    </span>
                                                    <span>
                                                        <i class="fas fa-clock mr-1"></i>
                                                        <span x-text="course.duration + ' horas'"></span>
                                                    </span>
                                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded"
                                                          x-text="course.level"></span>
                                                </div>

                                                <p class="text-gray-600 text-sm line-clamp-2" x-text="course.short_description"></p>
                                            </div>

                                            <!-- Acciones y precio -->
                                            <div class="flex flex-col items-end gap-3">
                                                <!-- Precio -->
                                                <div class="text-right">
                                                    <template x-if="course.is_on_promotion">
                                                        <div>
                                                            <div class="text-lg font-bold text-gray-900">
                                                                S/ <span x-text="formatPrice(course.promotion_price)"></span>
                                                            </div>
                                                            <div class="text-sm text-gray-500 line-through">
                                                                S/ <span x-text="formatPrice(course.price)"></span>
                                                            </div>
                                                            <span class="text-xs text-red-600 font-medium">
                                                                Ahorras S/ <span x-text="formatPrice(course.price - course.promotion_price)"></span>
                                                            </span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!course.is_on_promotion">
                                                        <div class="text-lg font-bold text-gray-900">
                                                            S/ <span x-text="formatPrice(course.price)"></span>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Botones de acción -->
                                                <div class="flex items-center space-x-2">
                                                    <button @click="addToCart(course.id, index)"
                                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                                                        <i class="fas fa-shopping-cart mr-2"></i>
                                                        Agregar al carrito
                                                    </button>

                                                    <button @click="removeFromWishlist(course.id, index)"
                                                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                                            title="Eliminar de favoritos">
                                                        <i class="fas fa-heart text-lg"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Cursos recomendados -->
                <div class="mt-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Cursos que te pueden gustar</h2>
                            <p class="text-gray-600 text-sm mt-1">Basado en tu lista de deseos</p>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($recommendedCourses as $course)
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                    <a href="{{ route('course.show', $course->id) }}">
                                        <div class="relative h-40">
                                            <img src="{{ $course->image_url ? Storage::url($course->image_url) : 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                                 alt="{{ $course->title }}"
                                                 class="w-full h-full object-cover">

                                            @if($course->promotion_price)
                                            <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 text-xs font-bold rounded">
                                                -{{ number_format((($course->price - $course->promotion_price) / $course->price) * 100, 0) }}%
                                            </span>
                                            @endif
                                        </div>

                                        <div class="p-4">
                                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">{{ $course->title }}</h3>
                                            <p class="text-sm text-gray-600 mb-2">{{ $course->instructor->names }}</p>

                                            <div class="flex items-center justify-between">
                                                <div>
                                                    @if($course->promotion_price)
                                                    <span class="font-bold text-gray-900">S/ {{ number_format($course->promotion_price, 2) }}</span>
                                                    <span class="text-sm text-gray-500 line-through ml-2">S/ {{ number_format($course->price, 2) }}</span>
                                                    @else
                                                    <span class="font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                                    @endif
                                                </div>

                                                <button onclick="addToWishlist({{ $course->id }})"
                                                        class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar con estadísticas y acciones -->
            <div class="lg:col-span-1">
                <div class="space-y-6">
                    <!-- Resumen -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Resumen</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total cursos</span>
                                <span class="font-semibold text-gray-900" x-text="courses.length"></span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Cursos en oferta</span>
                                <span class="font-semibold text-green-600" x-text="courses.filter(c => c.is_on_promotion).length"></span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Valor total</span>
                                <span class="font-semibold text-gray-900">
                                    S/ <span x-text="formatPrice(courses.reduce((sum, c) => sum + (c.is_on_promotion ? c.promotion_price : c.price), 0))"></span>
                                </span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Posible ahorro</span>
                                <span class="font-semibold text-red-600">
                                    S/ <span x-text="formatPrice(courses.reduce((sum, c) => sum + (c.is_on_promotion ? (c.price - c.promotion_price) : 0), 0))"></span>
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <button @click="addAllToCart()"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Agregar todos al carrito
                            </button>

                            <p class="text-xs text-gray-500 text-center mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Total: S/ <span x-text="formatPrice(courses.reduce((sum, c) => sum + (c.is_on_promotion ? c.promotion_price : c.price), 0))"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Acciones rápidas -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones rápidas</h3>

                        <div class="space-y-3">
                            <a href="{{ route('cursos') }}"
                               class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-search text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Buscar más cursos</p>
                                    <p class="text-sm text-gray-500">Explora nuestro catálogo</p>
                                </div>
                            </a>

                            <a href="{{ route('student.my-courses') }}"
                               class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-play text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Mis cursos</p>
                                    <p class="text-sm text-gray-500">Continúa aprendiendo</p>
                                </div>
                            </a>

                            <a href="{{ route('cart') }}"
                               class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-shopping-bag text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Mi carrito</p>
                                    <p class="text-sm text-gray-500">Ver cursos seleccionados</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Recordatorios -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                        <h3 class="text-lg font-semibold mb-3">Recordatorios</h3>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-start">
                                <i class="fas fa-bell mr-2 mt-0.5"></i>
                                <span>Te notificaremos si un curso baja de precio</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-clock mr-2 mt-0.5"></i>
                                <span>Las ofertas tienen tiempo limitado</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-sync-alt mr-2 mt-0.5"></i>
                                <span>Revisa periódicamente tu lista</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Estado vacío -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gradient-to-r from-pink-100 to-purple-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-heart text-4xl text-pink-500"></i>
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-3">Tu lista de deseos está vacía</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Guarda los cursos que te interesan aquí para revisarlos más tarde.
                No pierdas de vista las ofertas especiales y nuevos lanzamientos.
            </p>

            <div class="space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('cursos') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-search mr-2"></i>
                    Explorar cursos populares
                </a>

                <a href="{{ route('student.my-courses') }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-book-open mr-2"></i>
                    Ver mis cursos
                </a>
            </div>

            <!-- Cursos populares -->
            <div class="mt-12 pt-12 border-t border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-6">Cursos populares que podrían interesarte</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($popularCourses as $course)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <a href="{{ route('course.show', $course->id) }}">
                            <div class="relative h-40">
                                <img src="{{ $course->image_url ? Storage::url($course->image_url) : 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                     alt="{{ $course->title }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 mb-3">{{ $course->instructor->names }}</p>

                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>

                                    <button onclick="addToWishlist({{ $course->id }})"
                                            class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal de confirmación -->
    <div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-${type}-100 rounded-full flex items-center justify-center mr-4">
                        <i :class="icon" class="text-${type}-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" x-text="title"></h3>
                        <p class="text-gray-600 mt-1" x-text="message"></p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button @click="closeModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button @click="confirmAction()"
                            class="px-4 py-2 bg-${type}-600 hover:bg-${type}-700 text-white rounded-lg transition-colors duration-200">
                        <span x-text="confirmText"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function wishlistApp() {
    return {
        courses: @json($wishlistItems),
        filter: 'all',
        sortBy: 'added_date',
        confirmationModal: null,
        modalData: {
            title: '',
            message: '',
            type: 'info',
            icon: '',
            confirmText: 'Confirmar',
            action: null,
            courseId: null,
            courseIndex: null
        },

        init() {
            this.confirmationModal = document.getElementById('confirmation-modal');
            this.setupEventListeners();
        },

        get filteredCourses() {
            let filtered = [...this.courses];

            // Aplicar filtro
            if (this.filter === 'on_sale') {
                filtered = filtered.filter(course => course.is_on_promotion);
            } else if (this.filter === 'newest') {
                // Filtrar por fecha (últimos 30 días)
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                filtered = filtered.filter(course => {
                    const addedDate = new Date(course.added_date);
                    return addedDate > thirtyDaysAgo;
                });
            }

            // Aplicar ordenamiento
            switch(this.sortBy) {
                case 'price_low':
                    filtered.sort((a, b) => {
                        const priceA = a.is_on_promotion ? a.promotion_price : a.price;
                        const priceB = b.is_on_promotion ? b.promotion_price : b.price;
                        return priceA - priceB;
                    });
                    break;
                case 'price_high':
                    filtered.sort((a, b) => {
                        const priceA = a.is_on_promotion ? a.promotion_price : a.price;
                        const priceB = b.is_on_promotion ? b.promotion_price : b.price;
                        return priceB - priceA;
                    });
                    break;
                case 'rating':
                    filtered.sort((a, b) => (b.rating || 4.8) - (a.rating || 4.8));
                    break;
                case 'popular':
                    filtered.sort((a, b) => b.students_count - a.students_count);
                    break;
                case 'added_date':
                default:
                    filtered.sort((a, b) => new Date(b.added_date) - new Date(a.added_date));
                    break;
            }

            return filtered;
        },

        setupEventListeners() {
            // Cerrar modal con Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeModal();
                }
            });

            // Cerrar modal al hacer clic fuera
            this.confirmationModal.addEventListener('click', (e) => {
                if (e.target === this.confirmationModal) {
                    this.closeModal();
                }
            });
        },

        applyFilters() {
            // Reaplicar filtros automáticamente por computed property
        },

        formatPrice(price) {
            return parseFloat(price).toFixed(2);
        },

        showModal(data) {
            this.modalData = { ...this.modalData, ...data };
            this.confirmationModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.confirmationModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            this.resetModalData();
        },

        resetModalData() {
            this.modalData = {
                title: '',
                message: '',
                type: 'info',
                icon: '',
                confirmText: 'Confirmar',
                action: null,
                courseId: null,
                courseIndex: null
            };
        },

        confirmAction() {
            if (this.modalData.action) {
                this.modalData.action();
            }
            this.closeModal();
        },

        async removeFromWishlist(courseId, index) {
            this.showModal({
                title: 'Eliminar de favoritos',
                message: '¿Estás seguro de que quieres eliminar este curso de tu lista de deseos?',
                type: 'error',
                icon: 'fas fa-heart-broken',
                confirmText: 'Sí, eliminar',
                courseId: courseId,
                courseIndex: index,
                action: async () => {
                    try {
                        const response = await axios.delete(`/wishlist/remove/${courseId}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.data.success) {
                            // Remover del array local
                            this.courses.splice(index, 1);

                            // Actualizar contador
                            this.updateWishlistCount();

                            // Mostrar notificación
                            this.showNotification('Curso eliminado de favoritos', 'success');
                        }
                    } catch (error) {
                        console.error('Error removing from wishlist:', error);
                        this.showNotification('Error al eliminar de favoritos', 'error');
                    }
                }
            });
        },

        confirmClearAll() {
            this.showModal({
                title: 'Limpiar lista completa',
                message: '¿Estás seguro de que quieres eliminar todos los cursos de tu lista de deseos? Esta acción no se puede deshacer.',
                type: 'error',
                icon: 'fas fa-trash-alt',
                confirmText: 'Sí, limpiar todo',
                action: async () => {
                    try {
                        const response = await axios.delete('/wishlist/clear-all', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.data.success) {
                            // Limpiar array local
                            this.courses = [];

                            // Actualizar contador
                            this.updateWishlistCount();

                            // Mostrar notificación
                            this.showNotification('Lista de deseos vaciada', 'success');
                        }
                    } catch (error) {
                        console.error('Error clearing wishlist:', error);
                        this.showNotification('Error al limpiar la lista', 'error');
                    }
                }
            });
        },

        async addToCart(courseId, index) {
            try {
                const response = await axios.post(`/cart/add/${courseId}`, {}, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.data.success) {
                    // Actualizar contador del carrito
                    this.updateCartCount();

                    // Opcional: remover de la lista de deseos automáticamente
                    // this.removeFromWishlist(courseId, index);

                    this.showNotification('Curso agregado al carrito', 'success');
                } else {
                    this.showNotification(response.data.message, 'warning');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);

                if (error.response && error.response.status === 401) {
                    this.showNotification('Debes iniciar sesión para agregar al carrito', 'warning');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    this.showNotification('Error al agregar al carrito', 'error');
                }
            }
        },

        async addAllToCart() {
            this.showModal({
                title: 'Agregar todos al carrito',
                message: `¿Estás seguro de que quieres agregar ${this.courses.length} cursos a tu carrito?`,
                type: 'info',
                icon: 'fas fa-shopping-cart',
                confirmText: 'Sí, agregar todos',
                action: async () => {
                    try {
                        let addedCount = 0;
                        let errors = [];

                        // Agregar cursos uno por uno
                        for (const course of this.courses) {
                            try {
                                const response = await axios.post(`/cart/add/${course.id}`, {}, {
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });

                                if (response.data.success) {
                                    addedCount++;
                                } else {
                                    errors.push(course.title);
                                }
                            } catch (error) {
                                errors.push(course.title);
                            }
                        }

                        // Actualizar contador del carrito
                        this.updateCartCount();

                        // Mostrar resultado
                        if (errors.length === 0) {
                            this.showNotification(`Se agregaron ${addedCount} cursos al carrito`, 'success');
                        } else {
                            this.showNotification(`Se agregaron ${addedCount} cursos. ${errors.length} no pudieron agregarse.`, 'warning');
                        }
                    } catch (error) {
                        console.error('Error adding all to cart:', error);
                        this.showNotification('Error al agregar los cursos', 'error');
                    }
                }
            });
        },

        updateWishlistCount() {
            const countElement = document.getElementById('wishlist-count');
            if (countElement) {
                countElement.textContent = `${this.courses.length} cursos`;
            }
        },

        async updateCartCount() {
            try {
                const response = await axios.get('/api/cart/count', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const cartCount = document.getElementById('cart-count');
                if (cartCount && response.data.count !== undefined) {
                    cartCount.textContent = response.data.count;
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        },

        showNotification(message, type = 'info') {
            // Remover notificaciones existentes
            const existing = document.querySelectorAll('.custom-notification');
            existing.forEach(n => n.remove());

            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const notification = document.createElement('div');
            notification.className = `custom-notification fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-2xl z-50 animate-slide-in-right flex items-center gap-3 max-w-md`;
            notification.innerHTML = `
                <span class="text-lg">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('animate-fade-out');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    };
}

// Función global para agregar a favoritos
async function addToWishlist(courseId) {
    try {
        const response = await axios.post('/wishlist/add', {
            course_id: courseId
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.data.success) {
            // Mostrar notificación
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 animate-slide-in-right flex items-center gap-3';
            notification.innerHTML = `
                <i class="fas fa-heart text-lg"></i>
                <span>Curso agregado a favoritos</span>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('animate-fade-out');
                setTimeout(() => notification.remove(), 300);
            }, 3000);

            // Actualizar contador si estamos en la página de wishlist
            if (window.wishlistApp) {
                window.wishlistApp.updateWishlistCount();
            }
        }
    } catch (error) {
        console.error('Error adding to wishlist:', error);

        if (error.response && error.response.status === 401) {
            // Redirigir a login
            window.location.href = '/login';
        }
    }
}
</script>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.animate-slide-in-right {
    animation: slideInRight 0.3s ease-out forwards;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

/* Efecto hover para tarjetas de cursos */
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Animación para el corazón */
@keyframes heartBeat {
    0% { transform: scale(1); }
    25% { transform: scale(1.1); }
    50% { transform: scale(1); }
    75% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.heart-beat {
    animation: heartBeat 0.5s ease-in-out;
}
</style>
@endsection
