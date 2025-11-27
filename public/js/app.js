// API Base URL
const API_BASE = '/api';

class CoursePlatform {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadInitialData();
    }

    setupEventListeners() {
        // Filtros de cursos
        document.addEventListener('change', this.handleFilters.bind(this));

        // Búsqueda en tiempo real
        const searchInput = document.querySelector('input[type="text"]');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(this.handleSearch.bind(this), 300));
        }
    }

    async loadInitialData() {
        await this.loadCategories();
        await this.updateCartCount();
    }

    async loadCategories() {
        try {
            const response = await axios.get(`${API_BASE}/categories`);
            this.renderCategories(response.data);
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    renderCategories(categories) {
        const containers = [
            document.getElementById('categories-list'),
            document.getElementById('mobile-categories-list')
        ];

        containers.forEach(container => {
            if (container) {
                container.innerHTML = categories.map(category => `
                    <li>
                        <a href="/?category=${category.id}"
                           class="category-link text-gray-600 hover:text-blue-600 block px-2 py-1 rounded hover:bg-gray-100 transition-colors">
                            ${category.name}
                        </a>
                    </li>
                `).join('');
            }
        });
    }

    async updateCartCount() {
        try {
            const response = await axios.get(`${API_BASE}/cart/count`);
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = response.data.count;
                cartCount.style.display = response.data.count > 0 ? 'flex' : 'none';
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    async handleFilters(event) {
        const target = event.target;

        if (target.matches('select')) {
            await this.applyFilters();
        }
    }

    async handleSearch(event) {
        const searchTerm = event.target.value.trim();
        await this.applyFilters({ search: searchTerm });
    }

    async applyFilters(additionalParams = {}) {
        const params = new URLSearchParams();

        // Obtener valores de los filtros
        const categorySelect = document.querySelector('select:nth-of-type(1)');
        const levelSelect = document.querySelector('select:nth-of-type(2)');
        const sortSelect = document.querySelector('select:nth-of-type(3)');

        if (categorySelect?.value) params.append('category', categorySelect.value);
        if (levelSelect?.value) params.append('level', levelSelect.value);
        if (sortSelect?.value) params.append('sort', sortSelect.value);

        // Agregar parámetros adicionales
        Object.entries(additionalParams).forEach(([key, value]) => {
            if (value) params.append(key, value);
        });

        try {
            const response = await axios.get(`${API_BASE}/courses?${params}`);
            this.renderCourses(response.data);
        } catch (error) {
            console.error('Error applying filters:', error);
        }
    }

    renderCourses(coursesData) {
        const container = document.getElementById('courses-container');
        if (!container) return;

        if (coursesData.data && coursesData.data.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron cursos</h3>
                    <p class="mt-1 text-sm text-gray-500">Intenta con otros filtros de búsqueda.</p>
                </div>
            `;
            return;
        }

        const courses = coursesData.data || coursesData;

        container.querySelector('.grid').innerHTML = courses.map(course => `
            <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                <div class="relative">
                    <img src="${course.image_url || '/images/course-placeholder.jpg'}"
                         alt="${course.title}"
                         class="w-full h-48 object-cover">
                    ${course.promotion_price ? `
                        <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">
                            Oferta
                        </span>
                    ` : ''}
                    <span class="absolute bottom-2 left-2 bg-blue-600 text-white px-2 py-1 rounded text-xs">
                        ${course.category?.name || 'General'}
                    </span>
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2 line-clamp-2">${course.title}</h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">${course.description}</p>

                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm text-gray-600 ml-1">${course.rating || '4.5'}</span>
                            </div>
                            <span class="text-gray-400">•</span>
                            <span class="text-sm text-gray-600">${course.students_count || 0} estudiantes</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            ${course.promotion_price ? `
                                <span class="text-lg font-bold text-gray-900">S/ ${this.formatPrice(course.promotion_price)}</span>
                                <span class="text-sm text-gray-500 line-through">S/ ${this.formatPrice(course.price)}</span>
                            ` : `
                                <span class="text-lg font-bold text-gray-900">S/ ${this.formatPrice(course.price)}</span>
                            `}
                        </div>
                        <button onclick="coursePlatform.addToCart(${course.id})"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors">
                            Agregar
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    async addToCart(courseId) {
        try {
            const response = await axios.post(`${API_BASE}/cart/add/${courseId}`);

            if (response.data.success) {
                await this.updateCartCount();
                this.showNotification('Curso agregado al carrito', 'success');
            }
        } catch (error) {
            if (error.response?.status === 401) {
                this.showNotification('Debes iniciar sesión para agregar cursos al carrito', 'error');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                this.showNotification('Error al agregar el curso al carrito', 'error');
            }
        }
    }

    showNotification(message, type = 'info') {
        // Remover notificaciones existentes
        document.querySelectorAll('.custom-notification').forEach(notification => {
            notification.remove();
        });

        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Animación de entrada
        requestAnimationFrame(() => {
            notification.classList.remove('transform');
            notification.classList.remove('translate-x-full');
        });

        setTimeout(() => {
            notification.classList.add('transform');
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    formatPrice(price) {
        return parseFloat(price).toFixed(2);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Inicializar la plataforma cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.coursePlatform = new CoursePlatform();
});

// Sidebar móvil
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const closeSidebar = document.getElementById('close-sidebar');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');

    function openMobileSidebar() {
        if (mobileSidebar) {
            mobileSidebar.classList.remove('hidden');
            setTimeout(() => {
                mobileSidebar.querySelector('div').classList.remove('-translate-x-full');
            }, 50);
        }
    }

    function closeMobileSidebar() {
        if (mobileSidebar) {
            mobileSidebar.querySelector('div').classList.add('-translate-x-full');
            setTimeout(() => {
                mobileSidebar.classList.add('hidden');
            }, 300);
        }
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', openMobileSidebar);
    }

    if (closeSidebar) {
        closeSidebar.addEventListener('click', closeMobileSidebar);
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', closeMobileSidebar);
    }
});
