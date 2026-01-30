class GalleryCarousel {
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
        document.getElementById('gallery-next').addEventListener('click', () => this.nextSlide());
        document.getElementById('gallery-prev').addEventListener('click', () => this.prevSlide());

        // Indicators
        document.querySelectorAll('.gallery-indicator').forEach((indicator, index) => {
            indicator.addEventListener('click', () => this.goToSlide(index));
        });

        // Pause autoplay on hover
        const gallery = document.querySelector('.relative.w-full.overflow-hidden');
        gallery.addEventListener('mouseenter', () => this.pauseAutoPlay());
        gallery.addEventListener('mouseleave', () => this.startAutoPlay());

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
        });

        // Touch events for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        gallery.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        gallery.addEventListener('touchend', (e) => {
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
            const slide = document.getElementById(`gallery-slide-${i + 1}`);
            const indicator = document.querySelector(`.gallery-indicator[data-slide="${i}"]`);

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
        const currentSlide = document.getElementById(`gallery-slide-${index + 1}`);
        const currentIndicator = document.querySelector(`.gallery-indicator[data-slide="${index}"]`);

        if (currentSlide) {
            currentSlide.style.opacity = '1';
            currentSlide.style.zIndex = '10';
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
        this.autoPlayInterval = setInterval(() => this.nextSlide(), 4000);
    }

    pauseAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
}

// Inicializar carousel de galería cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.galleryCarousel = new GalleryCarousel();
});
