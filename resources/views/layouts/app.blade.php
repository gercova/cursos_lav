<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Plataforma de Cursos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0">
                        <img class="h-8 w-auto" src="/images/logo.png" alt="Logo">
                    </a>
                    <nav class="hidden md:ml-6 md:flex space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Inicio</a>
                        <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Cursos</a>
                        <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Nosotros</a>
                        <a href="#" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Contacto</a>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('cart') }}" class="text-gray-500 hover:text-blue-600 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">0</span>
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                {{ auth()->user()->names }}
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                <a href="{{ route('my-courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mis Cursos</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="hidden lg:block w-64 bg-white shadow-sm min-h-screen sidebar-transition">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Categorías</h3>
                <ul class="space-y-2" id="categories-list">
                    <!-- Las categorías se cargarán via JavaScript -->
                </ul>
            </div>
        </aside>

        <!-- Mobile sidebar toggle -->
        <div class="lg:hidden fixed bottom-4 right-4 z-40">
            <button id="sidebar-toggle" class="bg-blue-600 text-white p-3 rounded-full shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile sidebar -->
        <div id="mobile-sidebar" class="lg:hidden fixed inset-0 z-30 hidden">
            <div class="fixed inset-0 bg-black bg-opacity-50" id="sidebar-backdrop"></div>
            <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform -translate-x-full">
                <div class="p-4">
                    <button id="close-sidebar" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Categorías</h3>
                    <ul class="space-y-2" id="mobile-categories-list">
                        <!-- Las categorías se cargarán via JavaScript -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Plataforma de Cursos</h3>
                    <p class="text-gray-300">Ofrecemos los mejores cursos online para tu desarrollo profesional.</p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Inicio</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Cursos</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Nosotros</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li>Email: info@plataforma.com</li>
                        <li>Teléfono: +51 123 456 789</li>
                        <li>Dirección: Lima, Perú</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-300">
                <p>&copy; 2024 Plataforma de Cursos. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Cargar categorías
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            updateCartCount();

            // Sidebar móvil
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    mobileSidebar.classList.remove('hidden');
                    setTimeout(() => {
                        mobileSidebar.querySelector('div').classList.remove('-translate-x-full');
                    }, 50);
                });
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', closeMobileSidebar);
            }

            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', closeMobileSidebar);
            }

            function closeMobileSidebar() {
                mobileSidebar.querySelector('div').classList.add('-translate-x-full');
                setTimeout(() => {
                    mobileSidebar.classList.add('hidden');
                }, 300);
            }
        });

        async function loadCategories() {
            try {
                const response = await axios.get('/api/categories');
                const categories = response.data;

                const categoriesList = document.getElementById('categories-list');
                const mobileCategoriesList = document.getElementById('mobile-categories-list');

                if (categoriesList) {
                    categoriesList.innerHTML = categories.map(category => `
                        <li>
                            <a href="/?category=${category.id}" class="text-gray-600 hover:text-blue-600 block px-2 py-1 rounded hover:bg-gray-100">
                                ${category.name}
                            </a>
                        </li>
                    `).join('');
                }

                if (mobileCategoriesList) {
                    mobileCategoriesList.innerHTML = categories.map(category => `
                        <li>
                            <a href="/?category=${category.id}" class="text-gray-600 hover:text-blue-600 block px-2 py-1 rounded hover:bg-gray-100" onclick="closeMobileSidebar()">
                                ${category.name}
                            </a>
                        </li>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        async function updateCartCount() {
            try {
                const response = await axios.get('/api/cart/count');
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = response.data.count;
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
