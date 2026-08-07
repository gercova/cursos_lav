@extends('layouts.student')
@section('title', 'Mi Lista de Deseos')

@section('content')
    <div x-data="wishlistApp()" x-init="init()" class="min-h-screen bg-gray-50/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Encabezado -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-heart-fill text-rose-500"></i> Mi Lista de Deseos
                        </h1>
                        <p class="text-gray-500 mt-1 text-sm">
                            Guarda y administra los cursos y paquetes de capacitación que deseas llevar más adelante.
                        </p>
                    </div>

                    <div class="flex items-center space-x-3">
                        <span id="wishlist-count"
                            class="px-3.5 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-semibold">
                            <span x-text="items.length"></span> ítem(s) guardados
                        </span>

                        <button x-show="items.length > 0" @click="confirmClearAll()"
                            class="px-3.5 py-1.5 text-xs font-semibold text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors border border-red-200 flex items-center gap-1.5">
                            <i class="bi bi-trash"></i> Vaciar lista
                        </button>
                    </div>
                </div>
            </div>

            <template x-if="items.length > 0">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Lista de Cursos y Paquetes -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Filtros por tipo (Todos, Cursos, Paquetes, En Oferta) y Ordenamiento -->
                            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center space-x-2 flex-wrap gap-y-2">
                                        <button @click="filter = 'all'"
                                            :class="filter === 'all' ? 'bg-blue-600 text-white shadow-xs' :
                                                'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                                            Todos (<span x-text="items.length"></span>)
                                        </button>
                                        <button @click="filter = 'courses'"
                                            :class="filter === 'courses' ? 'bg-blue-600 text-white shadow-xs' :
                                                'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1">
                                            <i class="bi bi-journal-bookmark text-sm"></i> Cursos (<span
                                                x-text="coursesCount"></span>)
                                        </button>
                                        <button @click="filter = 'packages'"
                                            :class="filter === 'packages' ? 'bg-purple-600 text-white shadow-xs' :
                                                'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1">
                                            <i class="bi bi-box-seam text-sm"></i> Paquetes (<span
                                                x-text="packagesCount"></span>)
                                        </button>
                                        <button @click="filter = 'on_sale'"
                                            :class="filter === 'on_sale' ? 'bg-rose-600 text-white shadow-xs' :
                                                'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50'"
                                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1">
                                            <i class="bi bi-percent text-sm"></i> En oferta
                                        </button>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500 font-medium">Ordenar:</span>
                                        <select x-model="sortBy"
                                            class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                            <option value="added_date">Fecha agregado</option>
                                            <option value="price_low">Precio: Menor a Mayor</option>
                                            <option value="price_high">Precio: Mayor a Menor</option>
                                            <option value="popular">Más populares</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Ítems Renderizados -->
                            <div class="divide-y divide-gray-100">
                                <template x-for="(item, index) in filteredItems" :key="item.id">
                                    <div class="p-5 hover:bg-gray-50/70 transition-colors group">
                                        <div class="flex flex-col md:flex-row gap-5 md:items-center">
                                            <!-- Imagen -->
                                            <div
                                                class="flex-shrink-0 w-full md:w-44 h-48 md:h-32 relative overflow-hidden rounded-xl bg-gray-100 border border-gray-200">
                                                <a :href="item.is_package ? '/promo-paquetes/' + item.slug : '/curso/' + item.slug"
                                                    class="block h-full">
                                                    <img :src="item.image_url ? Storage::url(item.image_url) : item.image_url"
                                                        :alt="item.title" class="w-full h-full object-cover group-hover:scale-105
                                                        transition-transform duration-300">
                                                </a>
                                                <!-- Discount badge -->
                                                <span x-show="item.is_on_promotion && item.promotion_price"
                                                    class="absolute top-2 left-2 px-2 py-0.5 bg-rose-600 text-white text-[10px] font-bold rounded-md shadow-xs">
                                                    OFF
                                                </span>
                                            </div>

                                            <!-- Información -->
                                            <div class="flex-1 min-w-0">
                                                <div
                                                    class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                                    <div class="flex-1">
                                                        <!-- Badge de Tipo (Paquete / Curso) -->
                                                        <div class="mb-1.5 flex items-center gap-2">
                                                            <template x-if="item.is_package">
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                                    <i class="bi bi-box-seam mr-1 text-purple-600"></i>
                                                                    Paquete de Capacitación
                                                                </span>
                                                            </template>
                                                            <template x-if="!item.is_package">
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                                    <i
                                                                        class="bi bi-journal-bookmark mr-1 text-blue-600"></i>
                                                                    Curso Especializado
                                                                </span>
                                                            </template>

                                                            <span class="text-xs text-gray-500 font-medium"
                                                                x-text="item.category_name"></span>
                                                        </div>

                                                        <h3 class="text-base font-bold text-gray-900 mb-1">
                                                            <a :href="item.is_package ? '/promo-paquete/' + item.slug :
                                                                '/curso/' + item.slug"
                                                                class="hover:text-blue-600 transition-colors"
                                                                x-text="item.title"></a>
                                                        </h3>

                                                        <p class="text-xs text-gray-500 mb-2 flex items-center"
                                                            x-show="!item.is_package">
                                                            <i class="bi bi-person text-gray-400 mr-1"></i>
                                                            <span x-text="item.instructor_name"></span>
                                                        </p>

                                                        <p class="text-gray-600 text-xs line-clamp-2 mb-3"
                                                            x-text="item.short_description || item.description"></p>

                                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                            <span class="flex items-center text-amber-500 font-semibold">
                                                                <i class="bi bi-star-fill mr-1"></i>
                                                                <span x-text="item.rating || '4.8'"></span>
                                                            </span>
                                                            <span class="flex items-center">
                                                                <i class="bi bi-people mr-1"></i>
                                                                <span
                                                                    x-text="(item.students_count || 100) + ' inscritos'"></span>
                                                            </span>
                                                            <span class="flex items-center" x-show="item.duration">
                                                                <i class="bi bi-clock mr-1"></i>
                                                                <span x-text="item.duration + ' hrs'"></span>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Precio & Acciones -->
                                                    <div
                                                        class="flex flex-col items-start md:items-end gap-3 justify-between">
                                                        <div class="text-left md:text-right">
                                                            <template x-if="item.is_on_promotion && item.promotion_price">
                                                                <div>
                                                                    <div class="text-lg font-bold text-gray-900">
                                                                        S/ <span
                                                                            x-text="parseFloat(item.promotion_price).toFixed(2)"></span>
                                                                    </div>
                                                                    <div class="text-xs text-gray-400 line-through">
                                                                        S/ <span
                                                                            x-text="parseFloat(item.price).toFixed(2)"></span>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                            <template x-if="!item.is_on_promotion || !item.promotion_price">
                                                                <div class="text-lg font-bold text-gray-900">
                                                                    S/ <span
                                                                        x-text="parseFloat(item.price).toFixed(2)"></span>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        <div class="flex items-center space-x-2">
                                                            <button @click="addToCart(item.course_id)"
                                                                class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center shadow-xs">
                                                                <i class="bi bi-cart-plus mr-1.5 text-sm"></i> Al Carrito
                                                            </button>

                                                            <button @click="removeFromWishlist(item.course_id)"
                                                                class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors border border-rose-100"
                                                                title="Quitar de favoritos">
                                                                <i class="bi bi-heart-fill text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="filteredItems.length === 0"
                                    class="p-8 text-center text-gray-500 italic text-sm">
                                    No hay ítems en esta categoría.
                                </div>
                            </div>
                        </div>

                        <!-- Cursos recomendados -->
                        <div class="mt-8">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                <h2 class="text-base font-bold text-gray-900 mb-1">Cursos que te pueden interesar</h2>
                                <p class="text-gray-500 text-xs mb-5">Sugerencias basadas en tu lista de deseos</p>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach ($recommendedCourses as $recCourse)
                                        <div
                                            class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow bg-white flex flex-col justify-between">
                                            <div>
                                                <div class="relative h-32 bg-gray-100">
                                                    <img src="{{ $recCourse->image_url ? (str_starts_with($recCourse->image_url, 'http') ? $recCourse->image_url : $recCourse->image_url) : 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                                        alt="{{ $recCourse->title }}" class="w-full h-full object-cover">
                                                </div>

                                                <div class="p-3.5">
                                                    <h3 class="font-bold text-xs text-gray-900 line-clamp-1 mb-1">
                                                        {{ $recCourse->title }}</h3>
                                                    <p class="text-[11px] text-gray-500 mb-2">
                                                        {{ $recCourse->instructor->names ?? 'Instructor' }}</p>
                                                    <div class="text-xs font-bold text-blue-600">
                                                        S/
                                                        {{ number_format($recCourse->promotion_price ?? $recCourse->price, 2) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="p-3 pt-0 border-t border-gray-50 flex justify-between items-center">
                                                <a href="{{ route('course.show', $recCourse->slug) }}"
                                                    class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                                    Ver Detalle <i class="bi bi-arrow-right"></i>
                                                </a>
                                                <button onclick="toggleWishlistDirect({{ $recCourse->id }})"
                                                    class="text-gray-400 hover:text-rose-500 transition-colors p-1">
                                                    <i class="bi bi-heart text-base"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Resumen -->
                    <div class="lg:col-span-1">
                        <div class="space-y-6">
                            <!-- Resumen Financiero -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                                <h3 class="text-base font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Resumen de
                                    Lista</h3>

                                <div class="space-y-3 text-xs">
                                    <div class="flex justify-between items-center text-gray-600">
                                        <span>Cursos guardados:</span>
                                        <span class="font-bold text-gray-800" x-text="coursesCount"></span>
                                    </div>

                                    <div class="flex justify-between items-center text-gray-600">
                                        <span>Paquetes guardados:</span>
                                        <span class="font-bold text-purple-700" x-text="packagesCount"></span>
                                    </div>

                                    <div
                                        class="flex justify-between items-center text-gray-600 pt-2 border-t border-gray-100">
                                        <span class="font-semibold text-gray-800">Valor Total:</span>
                                        <span class="text-base font-bold text-gray-900">
                                            S/ <span x-text="totalPriceFormatted"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Banner Promocional / Accesos Rápidos -->
                            <div class="bg-gradient-to-br from-blue-900 to-indigo-900 text-white rounded-xl p-5 shadow-sm">
                                <div
                                    class="flex items-center space-x-2 text-blue-300 text-xs uppercase font-bold tracking-wider mb-2">
                                    <i class="bi bi-mortarboard-fill"></i> Explora el Catálogo
                                </div>
                                <h4 class="font-bold text-sm mb-2">¿Buscas más capacitaciones?</h4>
                                <p class="text-xs text-blue-100 leading-relaxed mb-4">
                                    Revisa nuestros paquetes especiales e inscribe tus especializaciones.
                                </p>
                                <a href="{{ route('cursos') }}"
                                    class="block text-center w-full py-2 bg-white text-blue-900 font-bold text-xs rounded-lg hover:bg-blue-50 transition-colors shadow-sm">
                                    <i class="bi bi-search mr-1"></i> Ver Catálogo Completo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Estado Vacío -->
            <template x-if="items.length === 0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center max-w-2xl mx-auto my-8">
                    <div
                        class="w-20 h-20 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-5 border border-rose-100">
                        <i class="bi bi-heart text-4xl"></i>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tu lista de deseos está vacía</h3>
                    <p class="text-gray-500 text-xs mb-6 leading-relaxed max-w-md mx-auto">
                        Explora nuestros cursos especializados y paquetes de capacitación y guarda tus favoritos con el
                        icono del corazón para revisarlos o inscribirte después.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center gap-3">
                        <a href="{{ route('cursos') }}"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors inline-flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="bi bi-journal-bookmark"></i> Explorar Cursos
                        </a>
                        <a href="{{ route('paquetes') }}"
                            class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg transition-colors inline-flex items-center justify-center gap-1.5 shadow-sm">
                            <i class="bi bi-box-seam"></i> Ver Paquetes
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function wishlistApp() {
            return {
                items: @json($wishlistItems),
                filter: 'all',
                sortBy: 'added_date',

                init() {},

                get coursesCount() {
                    return this.items.filter(item => !item.is_package).length;
                },

                get packagesCount() {
                    return this.items.filter(item => item.is_package).length;
                },

                get filteredItems() {
                    let list = [...this.items];

                    if (this.filter === 'courses') {
                        list = list.filter(i => !i.is_package);
                    } else if (this.filter === 'packages') {
                        list = list.filter(i => i.is_package);
                    } else if (this.filter === 'on_sale') {
                        list = list.filter(i => i.is_on_promotion);
                    }

                    if (this.sortBy === 'price_low') {
                        list.sort((a, b) => (a.promotion_price || a.price) - (b.promotion_price || b.price));
                    } else if (this.sortBy === 'price_high') {
                        list.sort((a, b) => (b.promotion_price || b.price) - (a.promotion_price || a.price));
                    } else if (this.sortBy === 'popular') {
                        list.sort((a, b) => b.students_count - a.students_count);
                    }

                    return list;
                },

                get totalPriceFormatted() {
                    const total = this.items.reduce((sum, item) => sum + (item.promotion_price || item.price || 0), 0);
                    return parseFloat(total).toFixed(2);
                },

                addToCart(courseId) {
                    axios.post(`/cart/add/${courseId}`, {}, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(res => {
                            alert('¡Ítem agregado al carrito exitosamente!');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('No se pudo agregar al carrito o ya está en tu carrito.');
                        });
                },

                removeFromWishlist(courseId) {
                    if (!confirm('¿Deseas eliminar este ítem de tu lista de deseos?')) return;

                    axios.delete(`/wishlist/remove/${courseId}`, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(res => {
                            this.items = this.items.filter(item => item.course_id != courseId);
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Error al eliminar el ítem de la lista.');
                        });
                },

                confirmClearAll() {
                    if (!confirm('¿Estás seguro de vaciar toda tu lista de deseos?')) return;

                    axios.delete('/wishlist/clear', {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(res => {
                            this.items = [];
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Error al vaciar la lista.');
                        });
                }
            };
        }

        function toggleWishlistDirect(courseId) {
            axios.post('/wishlist/toggle', {
                    course_id: courseId
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => {
                    if (res.data.success) {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al actualizar la lista de deseos.');
                });
        }
    </script>
@endsection
