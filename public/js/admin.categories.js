function categoryManager() {

    return {

        searchQuery: document.getElementById('searchCategory').value,
        statusFilter:document.getElementById('statusFilterSelect').value,
        loading: false,
        currentPage: 1,

        init() {
            // Escuchar eventos de modal
            window.addEventListener('modal-submit', (e) => {
                this.handleModalSubmit(e.detail);
                console.log(e.detail);
            });

            // Escuchar otros eventos
            this.setupEventListeners();
        },

        setupEventListeners() {
            window.addEventListener('category-saved', (e) => {
                this.handleCategorySaved(e.detail);
            });

            window.addEventListener('category-updated', (e) => {
                this.handleCategoryUpdated(e.detail);
            });

            window.addEventListener('category-deleted', (e) => {
                this.handleCategoryDeleted(e.detail);
            });
        },

        async handleModalSubmit(detail) {
            const { formData, form, isSubmittingState, closeModal } = detail;

            // Activar estado de submitting
            if (isSubmittingState) {
                isSubmittingState.isSubmitting = true;
            }

            try {
                // Convertir FormData a objeto plano
                const dataObject = {};
                formData.forEach((value, key) => {
                    // Manejar checkboxes correctamente
                    if (key === 'is_active') {
                        dataObject[key] = value;
                    } else {
                        dataObject[key] = value;
                    }
                });

                console.log('Datos a enviar:', dataObject); // Para debugging

                const response = await axios.post(`${API_URL}/admin/categories/store`, dataObject, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                // Cerrar modal
                closeModal();

                // Resetear estado de submitting
                if (isSubmittingState) {
                    isSubmittingState.isSubmitting = false;
                }

                // Mostrar notificación
                this.showNotification('Categoría creada exitosamente', 'success');

                // Emitir evento
                window.dispatchEvent(new CustomEvent('category-saved', {
                    detail: response.data.category || response.data
                }));

                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } catch (error) {
                // Resetear estado de submitting en caso de error
                if (isSubmittingState) {
                    isSubmittingState.isSubmitting = false;
                }

                if (error.response && error.response.status === 422) {
                    this.showValidationErrors(error.response.data.errors);
                    this.showNotification('Por favor corrige los errores en el formulario', 'error');
                } else {
                    console.error('Error completo:', error);
                    console.error('Response:', error.response?.data);
                    this.showNotification('Error al guardar la categoría', 'error');
                }
            }
        },

        showValidationErrors(errors) {
            // Limpiar errores anteriores
            document.querySelectorAll('.text-red-600').forEach(el => el.remove());

            // Mostrar nuevos errores
            for (const [field, messages] of Object.entries(errors)) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorDiv          = document.createElement('p');
                    errorDiv.className      = 'mt-1 text-sm text-red-600';
                    errorDiv.textContent    = messages[0];
                    input.parentNode.appendChild(errorDiv);

                    input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');

                    input.addEventListener('input', function() {
                        this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                        this.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
                        const errorMsg = this.parentNode.querySelector('.text-red-600');
                        if (errorMsg) errorMsg.remove();
                    }, { once: true });
                }
            }
        },

        async performSearch(page = 1) {
            this.loading = true;
            this.currentPage = page;

            try {
                const params = new URLSearchParams();
                if (this.searchQuery) params.append('search', this.searchQuery);
                if (this.statusFilter) params.append('status', this.statusFilter);
                if (page > 1) params.append('page', page);

                const response = await axios.get(`/admin/categories/search?${params.toString()}`);

                if (response.data.success) {
                    // Actualizar el contenedor de la tabla
                    document.getElementById('categories-table-container').innerHTML = response.data.html;

                    // Scroll suave hacia arriba
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

            } catch (error) {
                console.error('Error en búsqueda:', error);
                alert('Hubo un error al buscar. Por favor intenta de nuevo.');
            } finally {
                this.loading = false;
            }
        },

        resetFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            this.currentPage = 1;
            this.performSearch();
        },

        handleCategorySaved(category) {
            this.showNotification('Categoría creada exitosamente', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        },

        handleCategoryUpdated(category) {
            this.showNotification('Categoría actualizada exitosamente', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        },

        handleCategoryDeleted(categoryId) {
            const row = document.getElementById(`category-row-${categoryId}`);
            if (row) {
                row.classList.add('opacity-0', 'translate-x-4');
                setTimeout(() => {
                    row.remove();
                    this.showNotification('Categoría eliminada exitosamente', 'success');
                    this.updateStats();
                }, 300);
            }
        },

        showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
                type === 'success'
                ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
            }`;

            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success'
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            `;

            document.body.appendChild(notification);
            setTimeout(() => notification.classList.add('translate-y-0', 'opacity-100'), 10);

            setTimeout(() => {
                notification.classList.remove('translate-y-0', 'opacity-100');
                notification.classList.add('-translate-y-2', 'opacity-0');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        },

        updateStats() {
            // Podrías hacer una petición AJAX para actualizar estadísticas
        }
    };
}

// Función para eliminar categoría
async function deleteCategory(categoryId) {
    if (!confirm('¿Estás seguro de eliminar esta categoría? Esta acción no se puede deshacer.')) {
        return;
    }

    try {
        const response = await axios.delete(`{{ route('admin.categories.destroy', '') }}/${categoryId}`);
        window.dispatchEvent(new CustomEvent('category-deleted', {
            detail: categoryId
        }));
    } catch (error) {
        alert('Error al eliminar la categoría');
    }
}

// Función para cambiar estado
async function toggleStatus(categoryId) {
    if (!confirm('¿Cambiar estado de la categoría?')) {
        return;
    }

    try {
        const response = await axios.post(`${API_URL}/admin/categories/toggle-status/${categoryId}`);

        if (response.data.success) {
            // Recargar para ver cambios
            window.location.reload();
        }
    } catch (error) {
        alert('Error al cambiar el estado');
    }
}

// Función global para la paginación
function changePage(page) {
    // Obtener la instancia de Alpine del componente
    const searchComponent = document.querySelector('[x-data="categorySearch()"]').__x.$data;
    searchComponent.performSearch(page);
}
