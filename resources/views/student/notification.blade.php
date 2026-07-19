@extends('layouts.student')
@section('title', 'Mis Notificaciones')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header de la página -->
    <div class="mb-8 animate-fade-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Mis Notificaciones</h1>
                <p class="text-gray-600">Mantente al día con todas tus actividades</p>
            </div>
            <div class="flex items-center space-x-3">
                <button id="mark-all-read-btn" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200 text-sm font-medium">
                    <i class="fas fa-check-circle mr-2"></i>
                    Marcar todas como leídas
                </button>
                <button id="clear-all-btn" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors duration-200 text-sm font-medium">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Limpiar todas
                </button>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-4 animate-slide-in">
        <div class="flex flex-wrap items-center gap-3">
            <button class="filter-btn active px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition-all duration-200" data-filter="all">
                Todas
            </button>
            <button class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium transition-all duration-200" data-filter="unread">
                No leídas <span id="unread-count-badge" class="ml-1 bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">0</span>
            </button>
            <button class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium transition-all duration-200" data-filter="new_course">
                <i class="fas fa-book mr-1 text-blue-500"></i> Nuevos cursos
            </button>
            <button class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium transition-all duration-200" data-filter="payment">
                <i class="fas fa-credit-card mr-1 text-yellow-500"></i> Pagos
            </button>
            <button class="filter-btn px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium transition-all duration-200" data-filter="exam">
                <i class="fas fa-file-alt mr-1 text-red-500"></i> Exámenes
            </button>
        </div>
    </div>

    <!-- Lista de notificaciones -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-slide-in" style="animation-delay: 0.1s">
        <!-- Header de la tabla -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-600">
                <div class="col-span-8 md:col-span-9 lg:col-span-10">Notificación</div>
                <div class="col-span-2 md:col-span-2 lg:col-span-1 text-center">Tipo</div>
                <div class="col-span-2 md:col-span-1 lg:col-span-1 text-center">Acciones</div>
            </div>
        </div>

        <!-- Contenedor de notificaciones -->
        <div id="notifications-container" class="divide-y divide-gray-100">
            <!-- Loading state -->
            <div class="px-6 py-12 text-center">
                <div class="loading-spinner w-10 h-10 mx-auto mb-4"></div>
                <p class="text-gray-500">Cargando notificaciones...</p>
            </div>
        </div>

        <!-- Empty state -->
        <div id="empty-state" class="hidden px-6 py-16 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                <i class="far fa-bell text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No hay notificaciones</h3>
            <p class="text-gray-500 max-w-md mx-auto">Cuando tengas nuevas notificaciones, aparecerán aquí.</p>
        </div>

        <!-- Paginación -->
        <div id="pagination" class="px-6 py-4 bg-gray-50 border-t border-gray-200 hidden">
            <!-- La paginación se cargará dinámicamente -->
        </div>
    </div>
</div>

<!-- Scripts para manejar notificaciones -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentFilter = 'all';
    let notifications = [];

    // Cargar notificaciones
    loadNotifications();

    // Event listeners para filtros
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remover clase active de todos
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('active', 'bg-blue-600', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            });

            // Agregar clase active al botón clickeado
            this.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            this.classList.add('active', 'bg-blue-600', 'text-white');

            currentFilter = this.dataset.filter;
            filterNotifications();
        });
    });

    // Marcar todas como leídas
    document.getElementById('mark-all-read-btn').addEventListener('click', function() {
        if (confirm('¿Estás seguro de marcar todas las notificaciones como leídas?')) {
            markAllAsRead();
        }
    });

    // Limpiar todas las notificaciones
    document.getElementById('clear-all-btn').addEventListener('click', function() {
        if (confirm('¿Estás seguro de eliminar todas las notificaciones? Esta acción no se puede deshacer.')) {
            clearAllNotifications();
        }
    });

    // Funciones
    async function loadNotifications() {
        try {
            const response = await axios.get('/api/student/notifications');
            notifications = response.data.notifications;

            updateUnreadCount(response.data.unreadCount);
            renderNotifications();
        } catch (error) {
            console.error('Error loading notifications:', error);
            showError('Error al cargar notificaciones');
        }
    }

    function renderNotifications() {
        const container = document.getElementById('notifications-container');
        const emptyState = document.getElementById('empty-state');

        const filtered = filterNotificationsList(notifications);

        if (filtered.length === 0) {
            container.innerHTML = '';
            container.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        container.classList.remove('hidden');

        container.innerHTML = filtered.map(notification => `
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200 ${!notification.read_at ? 'bg-blue-50/50' : ''}" data-id="${notification.id}">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <!-- Icono y contenido -->
                    <div class="col-span-8 md:col-span-9 lg:col-span-10">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-${notification.color}-100 flex items-center justify-center">
                                <i class="fas fa-${notification.icon} text-${notification.color}-500"></i>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-gray-900">${notification.title}</h3>
                                    ${!notification.read_at ?
                                        '<span class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full"></span>' :
                                        ''
                                    }
                                </div>
                                <p class="text-gray-600 text-sm mb-2">${notification.message}</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    ${notification.time}
                                    ${notification.link ?
                                        `<a href="${notification.link}" class="ml-4 text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                                            Ver detalles <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                        </a>` :
                                        ''
                                    }
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo -->
                    <div class="col-span-2 md:col-span-2 lg:col-span-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-${notification.color}-100 text-${notification.color}-800">
                            ${getTypeLabel(notification.type)}
                        </span>
                    </div>

                    <!-- Acciones -->
                    <div class="col-span-2 md:col-span-1 lg:col-span-1">
                        <div class="flex justify-center space-x-2">
                            ${!notification.read_at ?
                                `<button class="mark-read-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                         data-id="${notification.id}"
                                         title="Marcar como leída">
                                    <i class="fas fa-check text-sm"></i>
                                </button>` :
                                `<button class="mark-unread-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                         data-id="${notification.id}"
                                         title="Marcar como no leída">
                                    <i class="fas fa-envelope text-sm"></i>
                                </button>`
                            }
                            <button class="delete-btn p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                    data-id="${notification.id}"
                                    title="Eliminar">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        // Agregar event listeners a los botones
        addEventListenersToNotifications();
    }

    function filterNotifications() {
        renderNotifications();
    }

    function filterNotificationsList(list) {
        if (currentFilter === 'all') return list;
        if (currentFilter === 'unread') return list.filter(n => !n.read_at);
        if (currentFilter === 'new_course') return list.filter(n => n.type.includes('course'));
        if (currentFilter === 'payment') return list.filter(n => n.type.includes('payment'));
        if (currentFilter === 'exam') return list.filter(n => n.type.includes('exam'));
        return list;
    }

    function getTypeLabel(type) {
        const labels = {
            'new_course': 'Curso',
            'payment_pending': 'Pago',
            'payment_approved': 'Pago',
            'exam_pending': 'Examen',
            'course_completed': 'Curso',
            'certificate_ready': 'Certificado'
        };
        return labels[type] || 'General';
    }

    function updateUnreadCount(count) {
        const badge = document.getElementById('unread-count-badge');
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('hidden', count === 0);
        }
    }

    function addEventListenersToNotifications() {
        // Marcar como leída
        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.stopPropagation();
                const id = this.dataset.id;
                await markAsRead(id);
            });
        });

        // Marcar como no leída
        document.querySelectorAll('.mark-unread-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.stopPropagation();
                const id = this.dataset.id;
                await markAsUnread(id);
            });
        });

        // Eliminar notificación
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.stopPropagation();
                const id = this.dataset.id;
                if (confirm('¿Estás seguro de eliminar esta notificación?')) {
                    await deleteNotification(id);
                }
            });
        });
    }

    async function markAsRead(id) {
        try {
            await axios.post(`/notifications/${id}/read`);
            await loadNotifications();

            // Actualizar contador en el header
            if (window.studentDashboard && window.studentDashboard.refreshNotifications) {
                window.studentDashboard.refreshNotifications();
            }
        } catch (error) {
            console.error('Error marking as read:', error);
            showError('Error al marcar como leída');
        }
    }

    async function markAllAsRead() {
        try {
            await axios.post('/notifications/read-all');
            await loadNotifications();

            // Actualizar contador en el header
            if (window.studentDashboard && window.studentDashboard.refreshNotifications) {
                window.studentDashboard.refreshNotifications();
            }

            showSuccess('Todas las notificaciones marcadas como leídas');
        } catch (error) {
            console.error('Error marking all as read:', error);
            showError('Error al marcar todas como leídas');
        }
    }

    async function deleteNotification(id) {
        try {
            await axios.delete(`/notifications/${id}`);
            await loadNotifications();

            // Actualizar contador en el header
            if (window.studentDashboard && window.studentDashboard.refreshNotifications) {
                window.studentDashboard.refreshNotifications();
            }

            showSuccess('Notificación eliminada');
        } catch (error) {
            console.error('Error deleting notification:', error);
            showError('Error al eliminar notificación');
        }
    }

    async function clearAllNotifications() {
        try {
            await axios.delete('/notifications');
            await loadNotifications();

            // Actualizar contador en el header
            if (window.studentDashboard && window.studentDashboard.refreshNotifications) {
                window.studentDashboard.refreshNotifications();
            }

            showSuccess('Todas las notificaciones eliminadas');
        } catch (error) {
            console.error('Error clearing notifications:', error);
            showError('Error al eliminar notificaciones');
        }
    }

    async function markAsUnread(id) {
        try {
            // Necesitarías implementar esta ruta si quieres la funcionalidad
            await axios.post(`/notifications/${id}/unread`);
            await loadNotifications();

            if (window.studentDashboard && window.studentDashboard.refreshNotifications) {
                window.studentDashboard.refreshNotifications();
            }
        } catch (error) {
            console.error('Error marking as unread:', error);
            showError('Error al marcar como no leída');
        }
    }

    function showSuccess(message) {
        showToast(message, 'success');
    }

    function showError(message) {
        showToast(message, 'error');
    }

    function showToast(message, type = 'info') {
        const existing = document.querySelectorAll('.custom-notification');
        existing.forEach(n => n.remove());

        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 ${colors[type] || colors.info} text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-slide-in-right flex items-center gap-3 max-w-md`;
        notification.innerHTML = `
            <span class="text-sm font-semibold">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-white/80 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
});
</script>
<style>
    @keyframes slide-in-right {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes fade-out {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    .animate-slide-in-right {
        animation: slide-in-right 0.3s ease-out;
    }
    .animate-fade-out {
        animation: fade-out 0.3s ease-out forwards;
    }
</style>

@endsection
