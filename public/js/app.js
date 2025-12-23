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

    // Cerrar menú móvil al hacer clic en un enlace
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            Alpine.store('mobileMenuOpen', false);
        });
    });

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
