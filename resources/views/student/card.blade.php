@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Carrito de Compras</h1>

    @if($cartItems->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Carrito vacío</h3>
            <p class="mt-1 text-sm text-gray-500">Agrega algunos cursos para comenzar.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Explorar Cursos
                </a>
            </div>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($cartItems as $item)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-16 w-16">
                                    <img class="h-16 w-16 object-cover rounded"
                                         src="{{ $item->course->image_url ?: '/images/course-placeholder.jpg' }}"
                                         alt="{{ $item->course->title }}">
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $item->course->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item->course->category->name }}</p>
                                    <div class="mt-1">
                                        @if($item->course->promotion_price)
                                            <span class="text-lg font-bold text-gray-900">S/ {{ number_format($item->course->promotion_price, 2) }}</span>
                                            <span class="text-sm text-gray-500 line-through ml-2">S/ {{ number_format($item->course->price, 2) }}</span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">S/ {{ number_format($item->course->price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button onclick="removeFromCart({{ $item->course_id }})"
                                        class="text-red-600 hover:text-red-900 font-medium">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="px-6 py-4 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-lg font-bold text-gray-900">Total: S/ {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('home') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Seguir Comprando
                        </a>
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Proceder al Pago
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    async function removeFromCart(courseId) {
        try {
            const response = await axios.delete(`/cart/remove/${courseId}`);

            if (response.data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
            showNotification('Error al eliminar el curso del carrito', 'error');
        }
    }

    function showNotification(message, type = 'info') {
        // Implementar notificación (similar a la del archivo principal)
        alert(message); // Placeholder
    }
</script>
@endsection
