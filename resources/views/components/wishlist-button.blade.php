<!-- resources/views/components/wishlist-button.blade.php -->
@auth
<button onclick="toggleWishlist({{ $course->id }})" class="{{ $class ?? 'text-gray-400 hover:text-red-500' }} transition-colors duration-200 relative" id="wishlist-btn-{{ $course->id }}">
    <i class="{{ $inWishlist ? 'fas fa-heart text-red-500' : 'far fa-heart' }} {{ $iconClass ?? 'text-lg' }}"></i>
    @if(isset($withCount) && $withCount)
        <span class="text-xs ml-1" id="wishlist-count-{{ $course->id }}">{{ $wishlistCount ?? 0 }}</span>
    @endif
</button>
@else
<a href="{{ route('login') }}"
   class="{{ $class ?? 'text-gray-400 hover:text-red-500' }} transition-colors duration-200">
    <i class="far fa-heart {{ $iconClass ?? 'text-lg' }}"></i>
</a>
@endauth

@section('scripts')
<script>
async function toggleWishlist(courseId) {
    const button = document.getElementById(`wishlist-btn-${courseId}`);
    const icon = button.querySelector('i');

    try {
        const response = await axios.post('/wishlist/toggle', {
            course_id: courseId
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.data.success) {
            // Cambiar ícono
            if (response.data.action === 'added') {
                icon.classList.remove('far', 'fa-heart');
                icon.classList.add('fas', 'fa-heart', 'text-red-500');

                // Animación
                button.classList.add('heart-beat');
                setTimeout(() => button.classList.remove('heart-beat'), 500);
            } else {
                icon.classList.remove('fas', 'fa-heart', 'text-red-500');
                icon.classList.add('far', 'fa-heart');
            }

            // Actualizar contador si existe
            const countElement = document.getElementById(`wishlist-count-${courseId}`);
            if (countElement) {
                countElement.textContent = response.data.count;
            }

            // Actualizar contador global si estamos en wishlist page
            if (window.wishlistApp) {
                window.wishlistApp.updateWishlistCount();
            }
        }
    } catch (error) {
        console.error('Error toggling wishlist:', error);
    }
}
</script>
@endsection
