<div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
    <div class="px-4 py-2 border-b border-gray-100">
        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->names }}</p>
        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
    </div>
    <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
        <i class="fas fa-user mr-2"></i>Mi Perfil
    </a>
    <a href="{{ route('student.my-courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
        <i class="fas fa-book mr-2"></i>Mis Cursos
    </a>
    <a href="{{ route('student.certificates') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
        <i class="fas fa-certificate mr-2"></i>Certificados
    </a>
    <a href="{{ route('student.progress') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
        <i class="fas fa-chart-line mr-2"></i>Mi Progreso
    </a>
    <div class="border-t border-gray-100"></div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-200">
            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
        </button>
    </form>
</div>
