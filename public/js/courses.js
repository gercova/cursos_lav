class CoursesPage {
    constructor() {
        this.currentView = 'grid';
        this.filters = {
            search: '',
            category: '',
            level: '',
            sort: 'newest'
        };
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadInitialFilters();
        this.setupViewToggle();
    }

    setupEventListeners() {
        // Filtros
        document.getElementById('search-input').addEventListener('input',
            this.debounce(() => this.applyFilters(), 500));

        document.getElementById('category-filter').addEventListener('change', () => this.applyFilters());
        document.getElementById('level-filter').addEventListener('change', () => this.applyFilters());
        document.getElementById('sort-filter').addEventListener('change', () => this.applyFilters());

        // Limpiar filtros
        document.getElementById('clear-filters').addEventListener('click', () => this.clearFilters());
        document.getElementById('reset-search').addEventListener('click', () => this.clearFilters());

        // Navegación con teclado
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.clearFilters();
            }
        });
    }

    setupViewToggle() {
        const gridViewBtn = document.getElementById('grid-view');
        const listViewBtn = document.getElementById('list-view');
        const gridContainer = document.getElementById('grid-view-container');
        const listContainer = document.getElementById('list-view-container');

        gridViewBtn.addEventListener('click', () => {
            this.currentView = 'grid';
            gridContainer.classList.remove('hidden');
            listContainer.classList.add('hidden');
            gridViewBtn.classList.add('bg-blue-100', 'text-blue-600');
            listViewBtn.classList.remove('bg-blue-100', 'text-blue-600');
            listViewBtn.classList.add('text-gray-500');
        });

        listViewBtn.addEventListener('click', () => {
            this.currentView = 'list';
            gridContainer.classList.add('hidden');
            listContainer.classList.remove('hidden');
            listViewBtn.classList.add('bg-blue-100', 'text-blue-600');
            gridViewBtn.classList.remove('bg-blue-100', 'text-blue-600');
            gridViewBtn.classList.add('text-gray-500');
        });
    }

    loadInitialFilters() {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('category')) {
            this.filters.category = urlParams.get('category');
            document.getElementById('category-filter').value = this.filters.category;
        }

        if (urlParams.get('level')) {
            this.filters.level = urlParams.get('level');
            document.getElementById('level-filter').value = this.filters.level;
        }

        if (urlParams.get('search')) {
            this.filters.search = urlParams.get('search');
            document.getElementById('search-input').value = this.filters.search;
        }

        if (urlParams.get('sort')) {
            this.filters.sort = urlParams.get('sort');
            document.getElementById('sort-filter').value = this.filters.sort;
        }

        this.updateActiveFilters();
    }

    applyFilters() {
        this.filters = {
            search: document.getElementById('search-input').value,
            category: document.getElementById('category-filter').value,
            level: document.getElementById('level-filter').value,
            sort: document.getElementById('sort-filter').value
        };

        this.updateURL();
        this.updateActiveFilters();
        this.filterCourses();
    }

    async filterCourses() {
        try {
            const params = new URLSearchParams();

            if (this.filters.search) params.append('search', this.filters.search);
            if (this.filters.category) params.append('category', this.filters.category);
            if (this.filters.level) params.append('level', this.filters.level);
            if (this.filters.sort) params.append('sort', this.filters.sort);

            // Mostrar loading
            this.showLoading();

            const response = await axios.get(`/courses?${params.toString()}`);

            // Actualizar el contenido
            document.getElementById('courses-container').innerHTML = response.data;

            // Actualizar contador de resultados
            this.updateResultsCount();

        } catch (error) {
            console.error('Error filtering courses:', error);
            this.showError();
        }
    }

    updateResultsCount() {
        const courseCards = document.querySelectorAll('.course-card');
        const resultsCount = document.getElementById('results-count');
        const filterDescription = document.getElementById('filter-description');
        const emptyState = document.getElementById('empty-state');
        const coursesContainer = document.querySelector('.courses-view:not(.hidden)');

        if (courseCards.length === 0) {
            coursesContainer.style.display = 'none';
            emptyState.classList.remove('hidden');
            resultsCount.textContent = '0 cursos encontrados';
        } else {
            coursesContainer.style.display = 'block';
            emptyState.classList.add('hidden');
            resultsCount.textContent = `${courseCards.length} cursos encontrados`;
        }

        // Actualizar descripción de filtros
        let description = 'Mostrando todos los cursos';
        if (this.filters.search) description = `Resultados para "${this.filters.search}"`;
        if (this.filters.category) description += ` en ${document.getElementById('category-filter').selectedOptions[0].text}`;
        if (this.filters.level) description += ` - Nivel ${this.filters.level}`;

        filterDescription.textContent = description;
    }

    updateActiveFilters() {
        const activeFiltersContainer = document.getElementById('active-filters');
        activeFiltersContainer.innerHTML = '';

        let hasActiveFilters = false;

        if (this.filters.search) {
            this.addActiveFilter('search', `Búsqueda: "${this.filters.search}"`, activeFiltersContainer);
            hasActiveFilters = true;
        }

        if (this.filters.category) {
            const categoryName = document.getElementById('category-filter').selectedOptions[0].text;
            this.addActiveFilter('category', `Categoría: ${categoryName}`, activeFiltersContainer);
            hasActiveFilters = true;
        }

        if (this.filters.level) {
            this.addActiveFilter('level', `Nivel: ${this.filters.level}`, activeFiltersContainer);
            hasActiveFilters = true;
        }

        if (hasActiveFilters) {
            activeFiltersContainer.classList.remove('hidden');
        } else {
            activeFiltersContainer.classList.add('hidden');
        }
    }

    addActiveFilter(type, text, container) {
        const filterElement = document.createElement('div');
        filterElement.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium flex items-center gap-2';
        filterElement.innerHTML = `
            ${text}
            <button type="button" onclick="coursesPage.removeFilter('${type}')" class="text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        container.appendChild(filterElement);
    }

    removeFilter(type) {
        switch(type) {
            case 'search':
                this.filters.search = '';
                document.getElementById('search-input').value = '';
                break;
            case 'category':
                this.filters.category = '';
                document.getElementById('category-filter').value = '';
                break;
            case 'level':
                this.filters.level = '';
                document.getElementById('level-filter').value = '';
                break;
        }
        this.applyFilters();
    }

    clearFilters() {
        this.filters = {
            search: '',
            category: '',
            level: '',
            sort: 'newest'
        };

        document.getElementById('search-input').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('level-filter').value = '';
        document.getElementById('sort-filter').value = 'newest';

        this.applyFilters();
    }

    updateURL() {
        const params = new URLSearchParams();

        if (this.filters.search) params.append('search', this.filters.search);
        if (this.filters.category) params.append('category', this.filters.category);
        if (this.filters.level) params.append('level', this.filters.level);
        if (this.filters.sort !== 'newest') params.append('sort', this.filters.sort);

        const newURL = params.toString() ? `/courses?${params.toString()}` : '/courses';
        window.history.replaceState({}, '', newURL);
    }

    showLoading() {
        const container = document.getElementById('courses-container');
        container.innerHTML = `
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        `;
    }

    showError() {
        const container = document.getElementById('courses-container');
        container.innerHTML = `
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Error al cargar los cursos</h3>
                <p class="text-gray-600 mb-4">Intenta recargar la página</p>
                <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Recargar Página
                </button>
            </div>
        `;
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

// Inicializar la página de cursos
document.addEventListener('DOMContentLoaded', function() {
    window.coursesPage = new CoursesPage();
});

// Función global para agregar al carrito
async function addToCart(courseId) {
    try {
        const response = await axios.post(`/cart/add/${courseId}`);

        if (response.data.success) {
            // Mostrar notificación
            showNotification('Curso agregado al carrito', 'success');

            // Actualizar contador del carrito
            updateCartCount();
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
    // Implementar sistema de notificaciones
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
