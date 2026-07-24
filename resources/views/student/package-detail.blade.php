@extends('layouts.app')
@section('title', $package->title . ' - ' . $enterprise->trade_name)
@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white min-h-screen" x-data="packageDetail()" x-init="init({{ $package->id }}, {{ $package->final_price }})">
    <!-- Hero Section del Paquete -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 to-purple-900 py-12">
        <!-- Elementos decorativos -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-500 rounded-full opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-500 rounded-full opacity-20 animate-pulse delay-1000"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Navegación y breadcrumb -->
            <div class="mb-6">
                <a href="{{ route('paquetes') }}" class="text-indigo-200 hover:text-white inline-flex items-center gap-2 transition-colors duration-200">
                    <i class="bi bi-arrow-left text-sm"></i>
                    <span>Volver a Paquetes</span>
                </a>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <!-- Información principal del paquete -->
                <div class="text-white">
                    <!-- Badge de tipo -->
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-500 bg-opacity-50 text-white border border-indigo-300">
                            <i class="bi bi-box mr-1"></i>
                            Paquete de Cursos
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-extrabold mb-4">{{ $package->title }}</h1>
                    
                    <!-- Estadísticas rápidas -->
                    <div class="flex flex-wrap gap-4 mb-6">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-book text-indigo-300"></i>
                            <span class="text-indigo-200">
                                @if($package->plan_type_id == 1 && $package->course_limit !== 0)
                                    {{ $package->course_limit }} cursos incluidos
                                @elseif($package->plan_type_id !== 1 && $package->packageCourses->count() > 0)
                                    {{ $package->packageCourses->count() }} cursos incluidos
                                @elseif($package->plan_type_id !== 1 && $package->packageCourses->count() == 0)
                                    Todos los cursos
                                @endif  
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-people text-indigo-300"></i>
                            <span class="text-indigo-200">Hasta {{ $package->seats_max ?? 0 }} estudiantes</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-clock text-indigo-300"></i>
                            <span class="text-indigo-200">{{ $package->duration ?? 'A tu propio ritmo' }}</span>
                        </div>
                    </div>
                    
                    <!-- Descripción corta -->
                    <p class="text-indigo-200 text-lg mb-8">{{ $package->short_description ?? $package->description }}</p>
                    
                    <!-- Precios y acción -->
                    <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                @if($package->has_promotion)
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-3xl font-bold text-white">
                                            S/ {{ number_format($package->final_price, 2) }}
                                        </span>
                                        <span class="text-lg text-indigo-300 line-through">
                                            S/ {{ number_format($package->price, 2) }}
                                        </span>
                                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            -{{ $package->discount_percentage }}%
                                        </span>
                                    </div>
                                    <p class="text-indigo-200 text-sm">
                                        <i class="bi bi-tag mr-1"></i>
                                        Ahorras S/ {{ number_format($package->price - $package->final_price, 2) }}
                                    </p>
                                @else
                                    <span class="text-3xl font-bold text-white">
                                        S/ {{ number_format($package->price, 2) }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Botón Añadir al Carrito (MODIFICADO) -->
                            <button onclick="addToCart({{ $package->id }})" class="group relative px-8 py-4 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold rounded-xl transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 min-w-[200px]">
                                <template x-if="!loading && !isInCart">
                                    <span class="flex items-center gap-2">
                                        <i class="bi bi-cart-plus text-xl"></i>
                                        <span>Añadir al Carrito</span>
                                    </span>
                                </template>
                                <template x-if="!loading && isInCart">
                                    <span class="flex items-center gap-2">
                                        <i class="bi bi-check-circle text-xl"></i>
                                        <span>Ya está en el carrito</span>
                                    </span>
                                </template>
                                <template x-if="loading">
                                    <span class="flex items-center gap-2">
                                        <i class="bi bi-spinner fa-spin text-xl"></i>
                                        <span>Añadiendo...</span>
                                    </span>
                                </template>
                                
                                <!-- Efecto hover -->
                                <div class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            </button>
                        </div>
                        
                        <!-- Cupos disponibles -->
                        @if($package->seats)
                        <div class="mt-4 flex items-center gap-2 text-indigo-200 text-sm">
                            <i class="bi bi-chair"></i>
                            <span>{{ $package->seats }} cupos disponibles</span>
                            @if($package->seats <= 10)
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">
                                    ¡Últimos cupos!
                                </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Imagen del paquete (si existe) -->
                <div class="hidden lg:block">
                    @if($package->image_url)
                        <img src="{{ $package->image_url }}" alt="{{ $package->title }}" class="rounded-2xl shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-500">
                    @else
                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="aspect-w-16 aspect-h-9 flex items-center justify-center">
                                <i class="bi bi-box-open text-8xl text-white opacity-50"></i>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Ola decorativa inferior -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 80L60 70C120 60 240 40 360 35C480 30 600 40 720 50C840 60 960 70 1080 72.5C1200 75 1320 70 1380 67.5L1440 65V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="white"/>
            </svg>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna principal (2/3) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Pestañas de información -->
                <div x-data="{ activeTab: 'description' }" class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Cabecera de pestañas -->
                    <div class="border-b border-gray-200">
                        <nav class="flex -mb-px">
                            <button @click="activeTab = 'description'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'description', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'description' }" class="py-4 px-6 text-sm font-medium border-b-2 transition-colors duration-200">
                                <i class="bi bi-info-circle mr-2" :class="{ 'text-indigo-500': activeTab === 'description' }"></i>
                                Descripción
                            </button>
                            <button @click="activeTab = 'includes'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'includes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'includes' }" class="py-4 px-6 text-sm font-medium border-b-2 transition-colors duration-200">
                                <i class="bi bi-gift mr-2" :class="{ 'text-indigo-500': activeTab === 'includes' }"></i>
                                ¿Qué incluye?
                            </button>
                        </nav>
                    </div>
                    
                    <!-- Contenido de pestañas -->
                    <div class="p-6">
                        <!-- Descripción -->
                        <div x-show="activeTab === 'description'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            <div class="prose max-w-none">
                                {!! nl2br(e($package->description)) !!}
                            </div>
                        </div>
                        
                        <!-- Qué incluye -->
                        <div x-show="activeTab === 'includes'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                            @if(!empty($package->which_includes) && is_array($package->which_includes))
                                <ul class="space-y-3">
                                    @foreach($package->which_includes as $include)
                                        <li class="flex items-start gap-3">
                                            <i class="bi bi-check-circle text-green-500 mt-1"></i>
                                            <span class="text-gray-700">{{ $include }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-500 text-center py-8">No hay información adicional disponible</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Cursos incluidos en el paquete (basado en create.blade.php) -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-book text-indigo-600"></i>
                        Cursos incluidos en este paquete
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $package->courses->count() }}
                        </span>
                    </h2>
                    
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($package->courses as $index => $course)
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition duration-150">
                                <div class="flex-1 flex items-center gap-3">
                                    <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full text-sm font-bold flex-shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-medium text-gray-900">{{ $course->title }}</h3>
                                        <p class="text-sm text-gray-500">{{ $course->category->name ?? 'Sin categoría' }}</p>
                                    </div>
                                </div>
                                
                                @if($course->pivot && $course->pivot->quantity)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="bi bi-video"></i>
                                        <span>{{ $course->pivot->quantity }} sesiones</span>
                                    </div>
                                @endif
                                
                                <div class="text-right">
                                    <span class="text-sm text-gray-500">Curso valorizado en:</span>
                                    <p class="font-semibold text-indigo-600">S/ {{ number_format($course->price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Resumen de valor total -->
                    @php
                        $coursesSum     = 0;
                        $showSavingsBox = true;

                        if ($package->courses->isNotEmpty()) {
                            // Caso 1: cursos fijos asignados al paquete → suma real de precios
                            $coursesSum = (float) $package->courses->sum('price');

                        } elseif ($package->categories->isNotEmpty()) {
                            // Caso 2: paquete por categorías → suma REAL de todos los cursos activos de esas categorías
                            $categoryIds = $package->categories->pluck('id');
                            $coursesSum  = (float) \App\Models\Course::where('type', 'course')
                                ->where('is_active', true)
                                ->whereIn('category_id', $categoryIds)
                                ->sum('price');

                        } elseif ($package->course_limit > 0) {
                            // Caso 3: plan dinámico/flexible → promedio × límite de cursos
                            $avgPrice   = (float) (\App\Models\Course::where('type', 'course')
                                ->where('is_active', true)
                                ->avg('price') ?? 0);
                            $coursesSum = $avgPrice * (int) $package->course_limit;

                        } else {
                            // Caso 4: sin datos suficientes → ocultar bloque
                            $showSavingsBox = false;
                        }

                        // "Valor por separado" = cada alumno comprando sus cursos individualmente
                        $seats          = max(1, (int) $package->seats_max);
                        $separateTotal  = $coursesSum * $seats;

                        // Precio efectivo del paquete (respeta promoción vigente)
                        $effectivePrice = $package->has_promotion
                            ? (float) $package->final_price
                            : (float) $package->price;

                        $savings        = max(0, $separateTotal - $effectivePrice);
                        $savingsPct     = $separateTotal > 0 ? round(($savings / $separateTotal) * 100) : 0;
                    @endphp

                    @if($showSavingsBox && $separateTotal > 0 && $savings > 0)
                    <div class="mt-6 p-4 bg-indigo-50 rounded-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-sm text-indigo-600 font-medium">Valor total de cursos por separado</p>
                                <p class="text-2xl font-bold text-gray-900">S/ {{ number_format($separateTotal, 2) }}</p>
                                @if($package->courses->isNotEmpty())
                                    <p class="text-xs text-gray-400 mt-1">{{ $package->courses->count() }} curso(s) × {{ $seats }} alumno(s)</p>
                                @elseif($package->categories->isNotEmpty())
                                    <p class="text-xs text-gray-400 mt-1">Cursos de las categorías incluidas × {{ $seats }} alumno(s)</p>
                                @else
                                    <p class="text-xs text-gray-400 mt-1">Precio promedio estimado × {{ $package->course_limit }} curso(s) × {{ $seats }} alumno(s)</p>
                                @endif
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-green-600 font-medium">Ahorro con este paquete</p>
                                <p class="text-2xl font-bold text-green-600">S/ {{ number_format($savings, 2) }}</p>
                                @if($savingsPct > 0)
                                    <p class="text-xs text-green-500 mt-1">{{ $savingsPct }}% menos que comprar por separado</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Categorías del paquete (como en create.blade.php) -->
                @if($package->categories->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="bi bi-folder text-purple-600"></i>
                            Categorías incluidas
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($package->categories as $category)
                                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-100">
                                    <div>
                                        <p class="font-medium text-purple-900">{{ $category->name }}</p>
                                        @if($category->pivot && $category->pivot->max_courses_per_category)
                                            <p class="text-xs text-purple-600">
                                                Máx. {{ $category->pivot->max_courses_per_category }} cursos
                                            </p>
                                        @endif
                                    </div>
                                    <i class="bi bi-check-circle text-purple-400"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar (1/3) - Sticky en desktop -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Card de compra rápida (basada en create.blade.php) -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Compra este paquete</h3>
                        
                        <!-- Precios -->
                        <div class="mb-6">
                            @if($package->has_promotion)
                                <div class="flex items-baseline gap-2 mb-2">
                                    <span class="text-3xl font-bold text-gray-900">
                                        S/ {{ number_format($package->final_price, 2) }}
                                    </span>
                                    <span class="text-lg text-gray-500 line-through">
                                        S/ {{ number_format($package->price, 2) }}
                                    </span>
                                </div>
                                <p class="text-sm text-green-600">
                                    <i class="bi bi-tag mr-1"></i>
                                    Ahorro de S/ {{ number_format($package->price - $package->final_price, 2) }}
                                </p>
                            @else
                                <span class="text-3xl font-bold text-gray-900">
                                    S/ {{ number_format($package->price, 2) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Botón de añadir al carrito (sidebar) -->
                        <button onclick="addToCart({{ $package->id }})" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg hover:shadow-xl flex items-center justify-center gap-3 mb-4">
                            <template x-if="!loading && !isInCart">
                                <span class="flex items-center gap-2">
                                    <i class="bi bi-cart-plus"></i>
                                    <span>Añadir al Carrito</span>
                                </span>
                            </template>
                            <template x-if="!loading && isInCart">
                                <span class="flex items-center gap-2">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Ya está en el carrito</span>
                                </span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-2">
                                    <i class="bi bi-spinner fa-spin"></i>
                                    <span>Añadiendo...</span>
                                </span>
                            </template>
                        </button>
                        
                        <!-- Beneficios (from create.blade.php structure) -->
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-shield-alt text-green-500"></i>
                                <span>Acceso inmediato después de la compra</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-infinity text-green-500"></i>
                                <span>Acceso de por vida a los cursos</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-certificate text-green-500"></i>
                                <span>Certificados incluidos</span>
                            </div>
                            @if($package->seats)
                            <div class="flex items-center gap-2">
                                <i class="bi bi-users text-green-500"></i>
                                <span>{{ $package->seats }} cupos disponibles</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Compartir (extra) -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Compartir este paquete</h3>
                        <div class="flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-lg text-center transition duration-150">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($package->title) }}" target="_blank" class="flex-1 bg-sky-500 hover:bg-sky-600 text-white py-2 px-3 rounded-lg text-center transition duration-150">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($package->title . ' - ' . request()->url()) }}" target="_blank" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-lg text-center transition duration-150">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notificación de éxito (opcional) -->
    <div x-show="notification.show" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full"
         class="fixed top-24 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3">
        <i class="bi bi-check-circle text-xl"></i>
        <div>
            <p class="font-bold" x-text="notification.title"></p>
            <p class="text-sm text-green-100" x-text="notification.message"></p>
        </div>
        <button @click="notification.show = false" class="ml-4 text-green-200 hover:text-white">
            <i class="bi bi-x"></i>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function packageDetail() {
        return {
            // Estado del carrito
            isInCart: false,
            loading: false,
            packageId: null,
            packagePrice: 0,
            
            // Notificaciones
            notification: {
                show: false,
                title: '',
                message: ''
            },
            
            // Inicialización
            init(packageId, packagePrice) {
                this.packageId = packageId;
                this.packagePrice = packagePrice;
                this.checkCartStatus();
            },
            
            // Verificar si el paquete ya está en el carrito
            checkCartStatus() {
                // Aquí implementas la lógica para verificar el carrito
                // Puede ser desde localStorage, sesión, o una petición AJAX
                const cart = JSON.parse(localStorage.getItem('cart') || '[]');
                this.isInCart = cart.some(item => item.id === this.packageId && item.type === 'package');
            },
        }
    }

    // Función global para agregar al carrito
    async function addToCart(courseId) {
        const btn = event?.target;
        if (btn) {
            btn.disabled    = true;
            btn.innerHTML   = '<span class="animate-spin">⏳</span>';
        }

        try {
            const response = await fetch(`/cart/add/${courseId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                showNotification('✓ Curso agregado al carrito', 'success');
                updateCartCount();
            } else if(data.success == false) {
                showNotification('El Curso ya se encuentra agregado en el carrito', 'error');
            } else {
                throw new Error(data.message || 'Error al agregar el curso');
            }
        } catch (error) {
            console.error('Error:', error);

            if (error.message.includes('401') || error.message.includes('Unauthenticated')) {
                showNotification('Debes iniciar sesión para agregar cursos al carrito', 'warning');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showNotification('Error al agregar el curso al carrito', 'error');
            }
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Agregar';
            }
        }
    }

    function showNotification(message, type = 'info') {
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

    async function updateCartCount() {
        try {
            const response = await fetch('/api/cart/count', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            const cartCount = document.getElementById('cart-count');
            if (cartCount && data.count !== undefined) {
                cartCount.textContent = data.count;

                // Animación del contador
                cartCount.classList.add('animate-bounce');
                setTimeout(() => cartCount.classList.remove('animate-bounce'), 500);
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    
    /* Animaciones */
    @keyframes fade-in-down {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fade-in-up {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-down {
        animation: fade-in-down 0.8s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c7d2fe; /* indigo-200 */
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #818cf8; /* indigo-400 */
    }
</style>
@endsection