@extends('layouts.app')

@section('title', 'Cursos Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Banner/Hero Section -->
    <div class="bg-blue-600 rounded-lg shadow-lg p-8 mb-8 text-white">
        <div class="max-w-3xl">
            <h1 class="text-4xl font-bold mb-4">Aprende con los Mejores Cursos Online</h1>
            <p class="text-xl mb-6">Desarrolla tus habilidades con nuestra plataforma educativa de alta calidad.</p>
            <div class="flex space-x-4">
                <input type="text" placeholder="Buscar cursos..." class="flex-1 px-4 py-2 rounded-lg text-gray-900">
                <button class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100">
                    Buscar
                </button>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="flex flex-wrap gap-4 mb-8">
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Todos los niveles</option>
            <option value="beginner">Principiante</option>
            <option value="intermediate">Intermedio</option>
            <option value="advanced">Avanzado</option>
        </select>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Ordenar por</option>
            <option value="popular">Más populares</option>
            <option value="newest">Más recientes</option>
            <option value="price_low">Precio: menor a mayor</option>
            <option value="price_high">Precio: mayor a menor</option>
        </select>
    </div>

    <!-- Grid de Cursos -->
    <div id="courses-container">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($courses as $course)
                <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                    <div class="relative">
                        <img src="{{ $course->image_url ?: '/images/course-placeholder.jpg' }}"
                             alt="{{ $course->title }}"
                             class="w-full h-48 object-cover">
                        @if($course->promotion_price)
                            <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">
                                Oferta
                            </span>
                        @endif
                        <span class="absolute bottom-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-xs">
                            {{ $course->category->name }}
                        </span>
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $course->description }}</p>

                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600 ml-1">{{ $course->rating ?? '4.5' }}</span>
                                </div>
                                <span class="text-gray-400">•</span>
                                <span class="text-sm text-gray-600">{{ $course->students_count ?? 0 }} estudiantes</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                @if($course->promotion_price)
                                    <span class="text-lg font-bold text-gray-900">S/ {{ number_format($course->promotion_price, 2) }}</span>
                                    <span class="text-sm text-gray-500 line-through">S/ {{ number_format($course->price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-900">S/ {{ number_format($course->price, 2) }}</span>
                                @endif
                            </div>
                            <button onclick="addToCart({{ $course->id }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                                Agregar
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($courses->hasPages())
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    async function addToCart(courseId) {
        try {
            const response = await axios.post(`/cart/add/${courseId}`);

            if (response.data.success) {
                await updateCartCount();
                showNotification('Curso agregado al carrito', 'success');
            }
        } catch (error) {
            if (error.response?.status === 401) {
                showNotification('Debes iniciar sesión para agregar cursos al carrito', 'error');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showNotification('Error al agregar el curso al carrito', 'error');
            }
        }
    }

    function showNotification(message, type = 'info') {
        // Crear notificación (puedes implementar tu propio sistema de notificaciones)
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection
