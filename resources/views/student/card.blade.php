@extends('layouts.app')
@section('title', 'Carrito de Compras')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mi Carrito</h1>
            <p class="text-gray-600 mt-2">Revisa los cursos que has seleccionado</p>
        </div>

        @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Lista de cursos -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Cursos seleccionados ({{ $cartItems->count() }})
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($cartItems as $item)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <!-- Imagen del curso -->
                                <div class="flex-shrink-0 w-full sm:w-32 h-40 sm:h-32">
                                    <a href="{{ route('course.show', $item->course->id) }}">
                                        <img src="{{ $item->course->image_url ? Storage::url($item->course->image_url) : 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80' }}"
                                             alt="{{ $item->course->title }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    </a>
                                </div>

                                <!-- Información del curso -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                <a href="{{ route('course.show', $item->course->id) }}" class="hover:text-blue-600 transition-colors duration-200">
                                                    {{ $item->course->title }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-600 mb-2">Por {{ $item->course->instructor->names }}</p>
                                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    4.8
                                                </span>
                                                <span>{{ $item->course->duration }} horas</span>
                                                <span>{{ $item->course->level }}</span>
                                            </div>
                                        </div>

                                        <!-- Precio -->
                                        <div class="text-right ml-4">
                                            @if($item->course->is_on_promotion)
                                                <div class="text-lg font-bold text-gray-900">
                                                    S/ {{ number_format($item->course->promotion_price, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500 line-through">
                                                    S/ {{ number_format($item->course->price, 2) }}
                                                </div>
                                                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">
                                                    Ahorra S/ {{ number_format($item->course->price - $item->course->promotion_price, 2) }}
                                                </span>
                                            @else
                                                <div class="text-lg font-bold text-gray-900">
                                                    S/ {{ number_format($item->course->price, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <button onclick="removeFromCart({{ $item->course_id }})"
                                                    class="flex items-center text-red-600 hover:text-red-800 transition-colors duration-200">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span class="text-sm font-medium">Eliminar</span>
                                            </button>
                                            <button onclick="addToWishlist({{ $item->course_id }})"
                                                    class="flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                </svg>
                                                <span class="text-sm font-medium">Mover a favoritos</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cupón de descuento -->
                <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">¿Tienes un cupón de descuento?</h3>
                    <div class="flex gap-3">
                        <input type="text"
                               id="coupon-code"
                               placeholder="Ingresa código de cupón"
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button id="apply-coupon"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                            Aplicar cupón
                        </button>
                    </div>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-24">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Resumen del pedido</h2>
                    </div>

                    <div class="p-6">
                        <!-- Detalles del precio -->
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal ({{ $cartItems->count() }} cursos)</span>
                                <span>S/ {{ number_format($subtotal, 2) }}</span>
                            </div>

                            @if(isset($discount) && $discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Descuento por cupón</span>
                                <span>- S/ {{ number_format($discount, 2) }}</span>
                            </div>
                            @endif

                            <div class="flex justify-between text-gray-600">
                                <span>Impuestos</span>
                                <span>S/ {{ number_format($tax, 2) }}</span>
                            </div>

                            <div class="pt-3 border-t border-gray-200 flex justify-between text-lg font-bold text-gray-900">
                                <span>Total a pagar</span>
                                <span>S/ {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-6 space-y-3 text-sm text-gray-600">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Garantía de devolución de 30 días</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Acceso de por vida a los cursos</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Certificado de finalización incluido</span>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="mt-8 space-y-3">
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    Proceder al pago
                                </button>
                            </form>

                            <a href="{{ route('cursos') }}"
                               class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Seguir comprando
                            </a>
                        </div>

                        <!-- Métodos de pago -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600 mb-3">Métodos de pago aceptados</p>
                            <div class="flex items-center space-x-2">
                                <div class="w-10 h-6 bg-blue-500 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">VISA</span>
                                </div>
                                <div class="w-10 h-6 bg-red-500 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">MC</span>
                                </div>
                                <div class="w-10 h-6 bg-yellow-500 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">PP</span>
                                </div>
                                <div class="w-10 h-6 bg-green-500 rounded flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">PE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Carrito vacío -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-3">Tu carrito está vacío</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                Agrega algunos cursos para comenzar tu aprendizaje. Explora nuestros cursos más populares y encuentra el perfecto para ti.
            </p>

            <div class="space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('cursos') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Explorar cursos
                </a>

                <a href="{{ route('student.my-courses') }}"
                   class="inline-flex items-center px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Ver mis cursos
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-1.964-1.333-2.732 0L3.082 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">¿Eliminar del carrito?</h3>
                    <p class="text-gray-600 mt-1">El curso se eliminará de tu carrito de compras</p>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <button onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Cancelar
                </button>
                <button id="confirm-delete"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let courseToDelete = null;

// Función para eliminar del carrito
function removeFromCart(courseId) {
    courseToDelete = courseId;
    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    courseToDelete = null;
}

document.getElementById('confirm-delete').addEventListener('click', async function() {
    if (!courseToDelete) return;

    try {
        const response = await axios.delete(`/cart/remove/${courseToDelete}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.data.success) {
            // Mostrar notificación de éxito
            showNotification('Curso eliminado del carrito', 'success');

            // Recargar la página después de un breve delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        showNotification('Error al eliminar el curso', 'error');
    } finally {
        closeDeleteModal();
    }
});

// Función para agregar a favoritos
async function addToWishlist(courseId) {
    try {
        const response = await axios.post('/api/wishlist/add', {
            course_id: courseId
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (response.data.success) {
            showNotification('Curso movido a favoritos', 'success');

            // Actualizar contador de carrito si se elimina del carrito
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    } catch (error) {
        console.error('Error adding to wishlist:', error);

        if (error.response && error.response.status === 401) {
            showNotification('Debes iniciar sesión para usar favoritos', 'warning');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        } else {
            showNotification('Error al agregar a favoritos', 'error');
        }
    }
}

// Aplicar cupón
document.getElementById('apply-coupon').addEventListener('click', async function() {
    const couponCode = document.getElementById('coupon-code').value.trim();

    if (!couponCode) {
        showNotification('Ingresa un código de cupón', 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Aplicando...';

    try {
        const response = await axios.post('/api/cart/apply-coupon', {
            coupon_code: couponCode
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (response.data.success) {
            showNotification('Cupón aplicado correctamente', 'success');
            // Recargar para actualizar precios
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(response.data.message || 'Cupón inválido', 'error');
        }
    } catch (error) {
        console.error('Error applying coupon:', error);
        showNotification('Error al aplicar el cupón', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Aplicar cupón';
    }
});

// Función para mostrar notificaciones
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

// Cerrar modal con Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

<style>
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

/* Estilos para el carrito vacío */
.empty-cart-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Estilo para el botón de eliminar hover */
button:hover .trash-icon {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: rotate(0); }
    25% { transform: rotate(-15deg); }
    75% { transform: rotate(15deg); }
}
</style>
@endsection
