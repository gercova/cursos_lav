class HomeCarousel {
    constructor() {
        this.currentSlide = 0;
        this.totalSlides = 5;
        this.autoPlayInterval = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.startAutoPlay();
    }

    setupEventListeners() {
        // Navigation buttons
        document.getElementById('next-slide').addEventListener('click', () => this.nextSlide());
        document.getElementById('prev-slide').addEventListener('click', () => this.prevSlide());

        // Indicators
        document.querySelectorAll('.carousel-indicator').forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
            indicator.addEventListener('mouseenter', () => this.pauseAutoPlay());
            indicator.addEventListener('mouseleave', () => this.startAutoPlay());
        });

        // Pause autoplay on hover
        const carousel = document.querySelector('.relative.w-full');
        carousel.addEventListener('mouseenter', () => this.pauseAutoPlay());
        carousel.addEventListener('mouseleave', () => this.startAutoPlay());

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
            if (e.key === ' ') {
                this.pauseAutoPlay();
                setTimeout(() => this.startAutoPlay(), 5000);
            }
        });

        // Touch events for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        carousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            this.handleSwipe();
        });
    }

    handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                this.nextSlide();
            } else {
                this.prevSlide();
            }
        }
    }

    showSlide(index) {
        // Hide all slides
        for (let i = 0; i < this.totalSlides; i++) {
            const slide = document.getElementById(`slide-${i + 1}`);
            const indicator = document.querySelector(`.carousel-indicator[data-slide="${i}"]`);

            if (slide) {
                slide.style.opacity = '0';
                slide.style.zIndex = '0';
            }
            if (indicator) {
                indicator.style.opacity = '0.5';
                indicator.style.transform = 'scale(1)';
            }
        }

        // Show current slide
        const currentSlide = document.getElementById(`slide-${index + 1}`);
        const currentIndicator = document.querySelector(`.carousel-indicator[data-slide="${index}"]`);

        if (currentSlide) {
            currentSlide.style.opacity = '1';
            currentSlide.style.zIndex = '10';

            // Add animation class for entrance
            currentSlide.querySelector('h1')?.classList.add('animate-fade-in');
            currentSlide.querySelector('p')?.classList.add('animate-slide-up');

            // Remove animation after completion
            setTimeout(() => {
                currentSlide.querySelector('h1')?.classList.remove('animate-fade-in');
                currentSlide.querySelector('p')?.classList.remove('animate-slide-up');
            }, 1000);
        }

        if (currentIndicator) {
            currentIndicator.style.opacity = '1';
            currentIndicator.style.transform = 'scale(1.2)';
        }

        this.currentSlide = index;
    }

    nextSlide() {
        this.showSlide((this.currentSlide + 1) % this.totalSlides);
    }

    prevSlide() {
        this.showSlide((this.currentSlide - 1 + this.totalSlides) % this.totalSlides);
    }

    goToSlide(index) {
        this.showSlide(index);
    }

    startAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
        this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
    }

    pauseAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
}

// Inicializar carousel cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.homeCarousel = new HomeCarousel();

    // También inicializar la plataforma de cursos
    if (typeof coursePlatform !== 'undefined') {
        coursePlatform.init();
    }
});

// Smooth scroll para los enlaces internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Función global para agregar al carrito
async function addToCart(courseId) {
    const btn = event?.target;
    if (btn) {
        btn.disabled    = true;
        btn.innerHTML   = '<span class="animate-spin">⏳</span>';
    }

    try {
        const response = await fetch(`/cart/add/${courseId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('✓ Curso agregado al carrito', 'success');
            updateCartCount();
        } else if(data.success == false) {
            showNotification('El Curso ya se encuentra agregado en el carrito', 'error');
        } else {
            throw new Error(data.message || 'Error al agregar el curso');
        }
    } catch (error) {
        console.error('Error:', error);

        if (error.message.includes('401') || error.message.includes('Unauthenticated')) {
            showNotification('Debes iniciar sesión para agregar cursos al carrito', 'warning');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        } else {
            showNotification('Error al agregar el curso al carrito', 'error');
        }
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = 'Agregar';
        }
    }
}

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

async function updateCartCount() {
    try {
        const response = await fetch('/api/cart/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const data = await response.json();

        const cartCount = document.getElementById('cart-count');
        if (cartCount && data.count !== undefined) {
            cartCount.textContent = data.count;

            // Animación del contador
            cartCount.classList.add('animate-bounce');
            setTimeout(() => cartCount.classList.remove('animate-bounce'), 500);
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}
