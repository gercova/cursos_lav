<!-- Tabs - Scroll horizontal en móvil -->
<div class="border-b border-gray-200 bg-gray-50 overflow-x-auto">
    <nav class="flex whitespace-nowrap min-w-full">
        @if($package->package->plan_type_id == 1)
            <button @click="activeTab = 'superBulk'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'superBulk', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'superBulk' }" class="py-3 sm:py-4 px-4 sm:px-6 text-center border-b-2 font-medium text-xs sm:text-sm focus:outline-none transition-all duration-200">
                <i class="fas fa-rocket mr-1 sm:mr-2"></i>
                <span class="hidden xs:inline">Matrícula</span> Express
            </button>
        @endif
    </nav>
</div>

<!-- Contenido de los tabs -->
<div class="card-body p-4 sm:p-6">
    <!-- Tab: Matrícula Express -->
    <div @if($package->package->plan_type_id == 1) ? x-show="activeTab === 'single'" : '' @endif x-show="activeTab === 'superBulk'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-r from-amber-400 to-amber-600 text-white mb-3 sm:mb-4">
                    <i class="fas fa-rocket text-xl sm:text-3xl"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Matrícula Express</h3>
                <p class="text-sm sm:text-base text-gray-600 px-4">Matricula a <span class="font-bold text-amber-600">{{ $collaborators->count() }}</span> usuarios en <span class="font-bold text-amber-600">{{ $courses->count() }}</span> cursos</p>
            </div>
            
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-4 sm:p-6 border-2 border-amber-200 mb-6">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-amber-100 flex items-center justify-center">
                            <i class="fas fa-crown text-amber-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-amber-800 text-base sm:text-lg mb-2">Resumen de la operación</h4>
                        <ul class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm text-amber-700">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-xs"></i>
                                <span><span class="font-bold">{{ $collaborators->count() }}</span> usuarios con código</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check-circle text-xs"></i>
                                <span><span class="font-bold">{{ $courses->count() }}</span> cursos en promoción</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-calculator text-xs"></i>
                                <span><span class="font-bold">{{ $collaborators->count() * $courses->count() }}</span> matrículas a realizar</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                <!-- Vista previa usuarios -->
                <div class="card p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-900 text-sm">Usuarios</h4>
                        <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">
                            {{ $collaborators->count() }}
                        </span>
                    </div>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @forelse($collaborators->take(5) as $collaborator)
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($collaborator->names, 0, 1)) }}
                                </div>
                                <div class="truncate flex-1">
                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $collaborator->names }}</p>
                                    <p class="text-xs text-gray-500">{{ $collaborator->code }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4 text-sm">No hay usuarios</p>
                        @endforelse
                        @if($collaborators->count() > 5)
                            <div class="text-center p-2 bg-gray-100 rounded-lg">
                                <span class="text-xs font-medium text-gray-600">
                                    y {{ $collaborators->count() - 5 }} más...
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Vista previa cursos -->
                <div class="card p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-gray-900 text-sm">Cursos</h4>
                        <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-semibold">
                            {{ $courses->count() }}
                        </span>
                    </div>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @forelse($courses->take(5) as $course)
                            <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                    <i class="fas fa-book text-xs"></i>
                                </div>
                                <div class="flex-1 truncate">
                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $course->course->title }}</p>
                                    <p class="text-xs text-gray-500">S/ {{ number_format($course->course->promotion_price ?? $course->course->price, 2) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4 text-sm">No hay cursos</p>
                        @endforelse
                        @if($courses->count() > 5)
                            <div class="text-center p-2 bg-gray-100 rounded-lg">
                                <span class="text-xs font-medium text-gray-600">
                                    y {{ $courses->count() - 5 }} más...
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center">
                <button type="button" @click="confirmSuperBulkEnroll()" :disabled="superBulkLoading || {{ $collaborators->count() }} === 0 || {{ $courses->count() }} === 0" class="w-full sm:w-auto flex items-center justify-center gap-2 sm:gap-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold py-3 sm:py-4 px-6 sm:px-10 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
                    <i class="fas fa-rocket" x-show="!superBulkLoading"></i>
                    <svg x-show="superBulkLoading" class="animate-spin h-5 w-5 sm:h-6 sm:w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="superBulkLoading ? 'Procesando...' : 'EJECUTAR MATRÍCULA EXPRES'"></span>
                </button>
                <p class="text-xs text-gray-500 text-center mt-4 max-w-md">
                    <i class="fas fa-info-circle"></i> 
                    Matriculará a TODOS los usuarios en TODOS los cursos. Los ya matriculados serán omitidos.
                </p>
            </div>
        </div>
    </div>
</div>