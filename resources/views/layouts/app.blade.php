<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-50">
    <!-- Header Fijo -->
    <header class="header-fixed bg-white shadow-sm w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex-shrink-0">
                        <img class="h-8 w-auto" src="{{ asset('storage/photos/ipf-logo.png') }}" alt="Logo">
                    </a>
                    <nav class="hidden md:ml-6 md:flex space-x-4">
                        <a href="{{ url('/') }}" class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Inicio</a>
                        <a href="{{ route('cursos') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Cursos</a>
                        <a href="{{ url('nosotros') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Nosotros</a>
                        <a href="{{ url('contacto') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Contacto</a>
                    </nav>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('cart') }}" class="text-gray-500 hover:text-blue-600 relative transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center transition-all duration-200">0</span>
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none transition-colors duration-200">
                                {{ auth()->user()->names }}
                                <svg class="ml-1 w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Dashboard</a>
                                <a href="{{ route('my-courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Mis Cursos</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 shadow-sm hover:shadow-md">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <div class="flex content-with-fixed-header">
        <!-- Sidebar Fijo en desktop -->
        <!--<aside class="hidden lg:block w-64 bg-white shadow-sm min-h-screen sidebar-transition sidebar-fixed">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Categorías</h3>
                <ul class="space-y-2" id="categories-list">
                    Las categorías se cargarán via JavaScript
                </ul>
            </div>
        </aside>-->

        <!-- Mobile sidebar toggle -->
        <div class="lg:hidden fixed bottom-4 right-4 z-40">
            <button id="sidebar-toggle" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile sidebar -->
        <div id="mobile-sidebar" class="lg:hidden fixed inset-0 z-30 hidden">
            <div class="fixed inset-0 bg-black bg-opacity-50" id="sidebar-backdrop"></div>
            <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform duration-300 -translate-x-full">
                <div class="p-4 mt-16"> <!-- mt-16 para evitar superposición con header fijo -->
                    <button id="close-sidebar" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
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
        <main class="flex-1 lg:ml-0"> <!-- ml-64 para compensar el sidebar fijo -->
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
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Inicio</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Cursos</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Nosotros</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">Contacto</a></li>
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
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.042-3.441.219-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.017z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
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

            // Efecto de scroll en header
            window.addEventListener('scroll', function() {
                const header = document.querySelector('.header-fixed');
                if (window.scrollY > 10) {
                    header.classList.add('shadow-lg');
                } else {
                    header.classList.remove('shadow-lg');
                }
            });
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
                            <a href="/?category=${category.id}" class="text-gray-600 hover:text-blue-600 block px-2 py-1 rounded hover:bg-gray-100 transition-colors duration-200">
                                ${category.name}
                            </a>
                        </li>
                    `).join('');
                }

                if (mobileCategoriesList) {
                    mobileCategoriesList.innerHTML = categories.map(category => `
                        <li>
                            <a href="/?category=${category.id}" class="text-gray-600 hover:text-blue-600 block px-2 py-1 rounded hover:bg-gray-100 transition-colors duration-200" onclick="closeMobileSidebar()">
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
                    // Efecto de animación cuando cambia el count
                    if (response.data.count > 0) {
                        cartCount.classList.add('animate-pulse');
                        setTimeout(() => {
                            cartCount.classList.remove('animate-pulse');
                        }, 1000);
                    }
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
