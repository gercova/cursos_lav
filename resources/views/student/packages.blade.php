@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Paquetes y Promociones')
@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="relative overflow-hidden bg-blue-900 py-16">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600 rounded-full opacity-30 animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-cyan-500 rounded-full opacity-10 animate-ping"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 animate-fade-in-down">
                Paquetes <span class="text-yellow-400">Especiales</span>
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-8 animate-fade-in-up">
                Combina múltiples cursos y ahorra hasta un 40% con nuestros paquetes exclusivos. 
                ¡Desarrolla todas tus habilidades al mejor precio!
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto mt-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 transform hover:-translate-y-1 transition-all duration-300">
                    <div class="text-4xl font-extrabold text-white mb-1">{{ $packages->total() }}+</div>
                    <div class="text-blue-200 font-medium">Paquetes disponibles</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 transform hover:-translate-y-1 transition-all duration-300">
                    <div class="text-4xl font-extrabold text-white mb-1">40%</div>
                    <div class="text-blue-200 font-medium">Ahorro máximo</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 border border-white/20 transform hover:-translate-y-1 transition-all duration-300">
                    <div class="text-4xl font-extrabold text-white mb-1"><i class="fas fa-infinity text-3xl"></i></div>
                    <div class="text-blue-200 font-medium">Acceso ilimitado</div>
                </div>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0 w-full overflow-hidden leading-none">
            <svg class="relative block w-full h-12 md:h-20" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118,130.84,126,200.7,118.84,242.61,114.62,284.55,102.35,321.39,56.44Z" fill="#F9FAFB"></path>
            </svg>
        </div>
    </div>

    <div class="sticky top-16 z-30 bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1 w-full lg:max-w-md">
                    <form id="search-form" method="GET" action="{{ route('paquetes') }}" class="flex gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Buscar paquetes..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Buscar
                        </button>
                    </form>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <select name="min_price" id="min-price-filter" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-gray-50">
                        <option value="">Precio mín.</option>
                        <option value="50" {{ request('min_price') == 50 ? 'selected' : '' }}>S/ 50</option>
                        <option value="100" {{ request('min_price') == 100 ? 'selected' : '' }}>S/ 100</option>
                        <option value="200" {{ request('min_price') == 200 ? 'selected' : '' }}>S/ 200</option>
                        <option value="500" {{ request('min_price') == 500 ? 'selected' : '' }}>S/ 500</option>
                    </select>
                    
                    <select name="max_price" id="max-price-filter" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-gray-50">
                        <option value="">Precio máx.</option>
                        <option value="200" {{ request('max_price') == 200 ? 'selected' : '' }}>S/ 200</option>
                        <option value="500" {{ request('max_price') == 500 ? 'selected' : '' }}>S/ 500</option>
                        <option value="1000" {{ request('max_price') == 1000 ? 'selected' : '' }}>S/ 1000</option>
                        <option value="2000" {{ request('max_price') == 2000 ? 'selected' : '' }}>S/ 2000</option>
                    </select>
                    
                    <select name="date_range" id="date-filter" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-gray-50">
                        <option value="">Cualquier fecha</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hoy</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Última semana</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Último mes</option>
                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>Último año</option>
                    </select>
                    
                    <select name="sort" id="sort-filter" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-white border-2 border-blue-100 font-medium text-blue-800">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Precio: menor a mayor</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Precio: mayor a menor</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares</option>
                    </select>

                    <label class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg cursor-pointer transition-colors duration-200">
                        <input type="checkbox" name="on_promotion" value="1" {{ request('on_promotion') ? 'checked' : '' }} class="rounded text-red-600 focus:ring-red-500 border-gray-300 w-4 h-4 mr-2">
                        <span class="text-sm font-semibold text-red-700"><i class="fas fa-tag mr-1"></i> Ofertas</span>
                    </label>
                </div>
            </div>
            
            @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'date_range', 'on_promotion']))
            <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-gray-100">
                <span class="text-sm font-medium text-gray-500 mr-1"><i class="fas fa-filter mr-1"></i>Filtros activos:</span>
                
                @if(request('search'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Búsqueda: "{{ request('search') }}"
                    <button onclick="removeFilter('search')" class="ml-1.5 text-blue-600 hover:text-blue-900 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                @endif
                
                @if(request('min_price'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Mín: S/ {{ request('min_price') }}
                    <button onclick="removeFilter('min_price')" class="ml-1.5 text-blue-600 hover:text-blue-900 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                @endif
                
                @if(request('max_price'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Máx: S/ {{ request('max_price') }}
                    <button onclick="removeFilter('max_price')" class="ml-1.5 text-blue-600 hover:text-blue-900 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                @endif
                
                @if(request('date_range'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Fecha: {{ ['today'=>'Hoy','week'=>'Últ. semana','month'=>'Últ. mes','year'=>'Últ. año'][request('date_range')] }}
                    <button onclick="removeFilter('date_range')" class="ml-1.5 text-blue-600 hover:text-blue-900 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                @endif
                
                @if(request('on_promotion'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    En promoción
                    <button onclick="removeFilter('on_promotion')" class="ml-1.5 text-red-600 hover:text-red-900 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                @endif
                
                <button onclick="clearAllFilters()" class="text-sm text-red-600 hover:text-red-800 font-medium ml-2 transition-colors">
                    Limpiar todos
                </button>
            </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Catálogo de Paquetes</h2>
                <p class="mt-1 text-sm text-gray-500">Mostrando {{ $packages->firstItem() ?? 0 }} - {{ $packages->lastItem() ?? 0 }} de {{ $packages->total() }} resultados</p>
            </div>
        </div>

        @if($packages->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-box-open text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No se encontraron paquetes</h3>
                <p class="mt-2 text-gray-500">Intenta ajustar los filtros de búsqueda para encontrar lo que necesitas.</p>
                <div class="mt-6">
                    <button onclick="clearAllFilters()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i> Limpiar filtros
                    </button>
                </div>
            </div>
        @else
            <div id="packages-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($packages as $package)
                    <div class="group flex flex-col bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transform transition-all duration-300 hover:-translate-y-1">
                        
                        <div class="relative h-52 overflow-hidden bg-gray-200">
                            @if($package->has_promotion)
                                <div class="absolute top-4 right-4 z-20">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white shadow-md animate-pulse">
                                        <i class="fas fa-fire mr-1"></i> -{{ $package->discount_percentage }}%
                                    </span>
                                </div>
                            @endif

                            <img src="{{ $package->image_url }}" alt="{{ $package->title }}" class="w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent z-10"></div>
                            
                            <div class="absolute bottom-4 left-4 z-20">
                                <span class="inline-flex items-center space-x-1 text-white text-xs font-medium bg-blue-600/90 backdrop-blur-sm rounded-lg px-2.5 py-1.5 shadow-sm border border-blue-500/30">
                                    <i class="fas fa-layer-group"></i>
                                    <span>{{ $package->total_courses }} cursos incluidos</span>
                                </span>
                            </div>
                            
                            @if($package->seats_max)
                            <div class="absolute bottom-4 right-4 z-20">
                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium bg-white/90 backdrop-blur-sm text-gray-800 shadow-sm">
                                    <i class="fas fa-users text-blue-600 mr-1.5"></i> 
                                    {{ $package->seats_min }} - {{ $package->seats_max }}
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-5 flex flex-col flex-grow">
                            <a href="{{ route('paquete.detail', $package->slug) }}" class="block mb-2 group-hover:text-blue-600 transition-colors">
                                <h3 class="text-lg font-bold text-gray-900 line-clamp-2 leading-tight">
                                    {{ $package->title }}
                                </h3>
                            </a>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">
                                {{ $package->short_description ?? $package->description }}
                            </p>
                            
                            @if($package->categories->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach($package->categories->take(2) as $category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                    @if($package->categories->count() > 2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                            +{{ $package->categories->count() - 2 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <hr class="border-gray-100 mb-4">
                            
                            <div class="mt-auto">
                                <div class="flex items-end justify-between mb-4">
                                    <div>
                                        <span class="block text-xs text-gray-400 font-medium uppercase tracking-wider mb-0.5">Precio del paquete</span>
                                        @if($package->has_promotion)
                                            <div class="flex items-center gap-2">
                                                <span class="text-2xl font-extrabold text-gray-900">
                                                    S/ {{ number_format($package->final_price, 2) }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500 line-through">
                                                S/ {{ number_format($package->price, 2) }}
                                            </div>
                                        @else
                                            <span class="text-2xl font-extrabold text-gray-900">
                                                S/ {{ number_format($package->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($package->has_promotion)
                                        <div class="text-right">
                                            <span class="block text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md">
                                                Ahorras S/ {{ number_format($package->price - $package->final_price, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <button onclick="addToCart({{ $package->id }}, 'package')" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 shadow-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart"></i>
                                    Agregar al carrito
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12 flex justify-center">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    // Función para remover un filtro específico
    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        window.location.href = url.toString();
    }
    
    // Función para limpiar todos los filtros
    function clearAllFilters() {
        window.location.href = "{{ route('paquetes') }}";
    }
    
    // Auto-submit al cambiar filtros
    document.querySelectorAll('#min-price-filter, #max-price-filter, #date-filter, #sort-filter, input[name="on_promotion"]').forEach(element => {
        element.addEventListener('change', function() {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('paquetes') }}";
            
            // Preservar search si existe
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && searchInput.value) {
                const searchField = document.createElement('input');
                searchField.type = 'hidden';
                searchField.name = 'search';
                searchField.value = searchInput.value;
                form.appendChild(searchField);
            }
            
            // Agregar el filtro actual
            const field = document.createElement('input');
            field.type = 'hidden';
            field.name = element.name;
            field.value = element.type === 'checkbox' ? (element.checked ? '1' : '') : element.value;
            form.appendChild(field);
            
            document.body.appendChild(form);
            form.submit();
        });
    });

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
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in-down {
        animation: fade-in-down 0.8s ease-out;
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
@endsection