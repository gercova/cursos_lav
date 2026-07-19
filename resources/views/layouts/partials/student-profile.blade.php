<div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
    <div class="px-4 py-2 border-b border-gray-100">
        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->names }}</p>
        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
    </div>
    @if(auth()->user()->role == 'student')
        <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="bi bi-speedometer mr-2"></i> Mi Dashboard
        </a>
        @if (auth()->user()->company_code && auth()->user()->parent_id)
            <hr>
            <a href="{{ route('company.schedule') }}"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-calendar mr-2 text-blue-500"></i> Mis capacitaciones
            </a>
            <hr>
        @endif
        <a href="{{ route('student.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="bi bi-person-fill mr-2"></i> Mi Perfil
        </a>
        <a href="{{ route('student.my-courses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="bi bi-book-fill mr-2"></i> Mis Cursos
        </a>
        <a href="{{ route('student.certificates') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="bi bi-patch-check mr-2"></i> Certificados
        </a>
        <a href="{{ route('student.progress') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="bi bi-graph-up mr-2"></i> Mi Progreso
        </a>
        <div class="border-t border-gray-100"></div>
    @elseif (auth()->user()->role == 'admin' || auth()->user()->role == 'instructor')
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
            <i class="bi bi-speedometer mr-2"></i> Dashboard admin
        </a>

        @role('admin')
            <a href="{{ route('admin.enterprise.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-building-fill mr-2"></i> Empresa
            </a>
        @endrole
        
        @role('admin|instructor')
            <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-folder-fill mr-2"></i> Categorías
            </a>
        @endrole
        
        @role('admin|instructor')
            <a href="{{ route('admin.courses.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-book mr-2"></i> Cursos
            </a>
        @endrole
            
        @role('admin')
            <a href="{{ route('admin.packages.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-box-seam mr-2"></i> Paquetes
            </a>
        @endrole
        
        @role('admin|instructor')
            <a href="{{ route('admin.documents.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-file-earmark-text-fill mr-2"></i> Documentos
            </a>
        @endrole
        
        @role('admin|instructor')
            <a href="{{ route('admin.exams.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-clipboard-check mr-2"></i> Exámenes
            </a>
        @endrole
        
        @role('admin')
            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-people mr-2"></i> Usuarios
            </a>
            
            <!-- Añadir este enlace en el sidebar -->
            <a href="{{ route('admin.roles.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-shield-lock-fill mr-2"></i> Permisos
            </a>
        @endrole
        
        @role('admin|instructor')
            <a href="{{ route('admin.enrollments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-people mr-2"></i> Inscripciones
            </a>
        @endrole
            
        @role('admin')
            <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-cash mr-2"></i> Pagos
            </a>
        @endrole
    @elseif (auth()->user()->role == 'business')
        @role('business')
            <a href="{{ route('company.list') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-speedometer mr-2"></i> Mi panel de empresa
            </a>
            <!-- Enlace corregido para inscribir usuarios -->
            <a href="{{ route('company.enroll.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                <i class="bi bi-people mr-2"></i> Inscribir usuarios
            </a>
            <a href="{{ route('company.profile', [auth()->user()->id]) }}" @click="close()" class="block py-2 px-4 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                <i class="bi bi-building mr-2"></i> Actualizar mi datos
            </a>
        @endrole
    @endif
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors duration-200">
            <i class="bi bi-box-arrow-right mr-2"></i>Cerrar Sesión
        </button>
    </form>
</div>
