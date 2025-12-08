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
        this.loadCategories();
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

/*async function loadCategories() {
    try {
        const response = await axios.get('/api/categories');
        const categories = response.data;

        const categoriesGrid = document.getElementById('categories-grid');
        if (categoriesGrid) {
            categoriesGrid.innerHTML = categories.map(category => `
                <a href="/?category=${category.id}" class="bg-white rounded-lg shadow-md p-4 sm:p-6 text-center hover:shadow-lg transition-all duration-300 transform hover:scale-105 border border-gray-100 group">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 group-hover:scale-110 transition-all duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base group-hover:text-blue-600 transition-colors duration-300">${category.name}</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">${category.courses_count || 0} cursos</p>
                </a>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}*/

// Llamar a la función cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', loadCategories);

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
