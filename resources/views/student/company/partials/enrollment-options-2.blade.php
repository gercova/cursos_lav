{{--
    CORRECCIÓN FINAL: $courses ahora es siempre Collection<Course> (normalizado
    en el controller con ->pluck('course')->filter()).
    Por eso se accede DIRECTAMENTE: $course->id, $course->title, $course->price.
    Ya NO se usa $course->course->* (eso era para colecciones pivot).
--}}

<!-- Tabs -->
<div class="border-b border-gray-200 bg-gray-50 overflow-x-auto">
    <nav class="flex whitespace-nowrap min-w-full">

        <button @click="activeTab = 'single'"
            :class="{
                'border-blue-500 text-blue-600': activeTab === 'single',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'single'
            }"
            class="py-3 sm:py-4 px-4 sm:px-6 text-center border-b-2 font-medium text-xs sm:text-sm focus:outline-none transition-all duration-200">
            <i class="bi bi-person mr-1 sm:mr-2"></i>
            <span class="hidden xs:inline">Matrícula</span> Individual
        </button>

        <button @click="activeTab = 'bulk'"
            :class="{
                'border-blue-500 text-blue-600': activeTab === 'bulk',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'bulk'
            }"
            class="py-3 sm:py-4 px-4 sm:px-6 text-center border-b-2 font-medium text-xs sm:text-sm focus:outline-none transition-all duration-200">
            <i class="bi bi-people mr-1 sm:mr-2"></i>
            <span class="hidden xs:inline">Matrícula</span> Masiva
        </button>

        <button @click="activeTab = 'superBulk'"
            :class="{
                'border-blue-500 text-blue-600': activeTab === 'superBulk',
                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'superBulk'
            }"
            class="py-3 sm:py-4 px-4 sm:px-6 text-center border-b-2 font-medium text-xs sm:text-sm focus:outline-none transition-all duration-200">
            <i class="bi bi-rocket mr-1 sm:mr-2"></i>
            <span class="hidden xs:inline">Matrícula</span> Express
        </button>

    </nav>
</div>

<!-- Contenido de los tabs -->
<div class="card-body p-4 sm:p-6">

    <!-- ─── Tab: Matrícula Individual ─── -->
    <div x-show="activeTab === 'single'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100">

        <div class="max-w-3xl mx-auto">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Matricular usuario individualmente</h3>

            <form @submit.prevent="enrollSingle()" class="space-y-4 sm:space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Usuario *
                        <span class="text-xs text-gray-500 ml-1">({{ $collaborators->count() }} total)</span>
                    </label>
                    <select x-model="form.user_id" required
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 text-sm">
                        <option value="">-- Selecciona un usuario --</option>
                        @foreach ($collaborators as $collaborator)
                            <option value="{{ $collaborator->id }}">
                                {{ $collaborator->names }} - {{ $collaborator->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Curso *
                    </label>
                    <select x-model="form.course_id" required
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 text-sm">
                        <option value="">-- Selecciona un curso --</option>
                        @foreach ($courses as $course)
                            {{-- $course es un Course directamente → usar $course->id --}}
                            <option value="{{ $course->id }}">
                                {{ Str::limit($course->title, 40) }} -
                                S/ {{ number_format($course->promotion_price ?? $course->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-blue-50 rounded-xl p-3 sm:p-4 border border-blue-200">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <i class="bi bi-info-circle text-blue-600 mt-1 text-sm sm:text-base"></i>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-blue-800">Información de la matrícula</p>
                            <p class="text-xs text-blue-700 mt-1">
                                • El usuario recibirá acceso inmediato.<br>
                                • Solo una matrícula por usuario por curso.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                    <button type="submit" :disabled="loading"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
                        <i class="bi bi-person-plus" x-show="!loading"></i>
                        <svg x-show="loading" class="animate-spin h-4 w-4 sm:h-5 sm:w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="loading ? 'Matriculando...' : 'Matricular Usuario'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ─── Tab: Matrícula Masiva ─── -->
    <div x-show="activeTab === 'bulk'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        style="display: none;">

        <div class="max-w-4xl mx-auto">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Matricular múltiples usuarios en un curso
            </h3>

            <form @submit.prevent="enrollBulk()" class="space-y-4 sm:space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Curso *
                    </label>
                    <select x-model="bulkForm.course_id" required
                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200 text-sm">
                        <option value="">-- Selecciona un curso --</option>
                        @foreach ($courses as $course)
                            {{-- $course es un Course directamente → usar $course->id --}}
                            <option value="{{ $course->id }}">
                                {{ Str::limit($course->title, 50) }} -
                                S/ {{ number_format($course->promotion_price ?? $course->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Usuarios *
                    </label>
                    <div class="border border-gray-300 rounded-lg p-3 sm:p-4 max-h-48 sm:max-h-64 overflow-y-auto">
                        @forelse($collaborators as $collaborator)
                            <div class="flex items-center py-1.5 sm:py-2 hover:bg-gray-50 px-2 rounded">
                                <input type="checkbox" x-model="bulkForm.user_ids" value="{{ $collaborator->id }}"
                                    id="user_{{ $collaborator->id }}"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 flex-shrink-0">
                                <label for="user_{{ $collaborator->id }}"
                                    class="ml-2 sm:ml-3 flex-1 text-xs sm:text-sm text-gray-700 truncate">
                                    <span class="font-medium">{{ $collaborator->names }}</span>
                                    <span
                                        class="text-gray-500 text-xs ml-1 hidden sm:inline">{{ $collaborator->email }}</span>
                                </label>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4 text-sm">
                                No hay usuarios disponibles.
                                <a href="{{ route('company.list') }}"
                                    class="text-blue-600 underline block sm:inline">Ver lista</a>
                            </p>
                        @endforelse
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mt-2">
                        <p class="text-xs text-gray-500">
                            <span x-text="bulkForm.user_ids.length"></span> usuario(s) seleccionado(s)
                        </p>
                        <button type="button" @click="selectAllUsers()"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            Seleccionar todos
                        </button>
                    </div>
                </div>

                <div class="bg-green-50 rounded-xl p-3 sm:p-4 border border-green-200">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <i class="bi bi-rocket text-green-600 mt-1 text-sm sm:text-base"></i>
                        <div>
                            <p class="text-xs sm:text-sm font-medium text-green-800">Matriculación masiva</p>
                            <p class="text-xs text-green-700 mt-1">
                                • Se matricularán todos los usuarios seleccionados.<br>
                                • Los ya matriculados serán omitidos.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                    <button type="submit" :disabled="bulkLoading || bulkForm.user_ids.length === 0"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
                        <i class="bi bi-people" x-show="!bulkLoading"></i>
                        <svg x-show="bulkLoading" class="animate-spin h-4 w-4 sm:h-5 sm:w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="bulkLoading ? 'Matriculando...' : 'Matricular Seleccionados'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ─── Tab: Matrícula Express ─── -->
    <div x-show="activeTab === 'superBulk'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">

        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-6 sm:mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-r from-amber-400 to-amber-600 text-white mb-3 sm:mb-4">
                    <i class="bi bi-rocket text-xl sm:text-3xl"></i>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Matrícula Express</h3>
                <p class="text-sm sm:text-base text-gray-600 px-4">
                    Matricula a <span class="font-bold text-amber-600">{{ $collaborators->count() }}</span> usuarios
                    en <span class="font-bold text-amber-600">{{ $courses->count() }}</span> cursos
                </p>
            </div>

            <div
                class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-4 sm:p-6 border-2 border-amber-200 mb-6">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-amber-100 flex items-center justify-center">
                            <i class="bi bi-stars text-amber-600 text-lg sm:text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-amber-800 text-base sm:text-lg mb-2">Resumen de la operación</h4>
                        <ul class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm text-amber-700">
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check-circle text-xs"></i>
                                <span><span class="font-bold">{{ $collaborators->count() }}</span> usuarios</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check-circle text-xs"></i>
                                <span><span class="font-bold">{{ $courses->count() }}</span> cursos</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-calculator text-xs"></i>
                                <span><span class="font-bold">{{ $collaborators->count() * $courses->count() }}</span>
                                    matrículas a realizar</span>
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
                                <div
                                    class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-amber-500 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($collaborator->names, 0, 1)) }}
                                </div>
                                <div class="truncate flex-1">
                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $collaborator->names }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $collaborator->email }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4 text-sm">No hay usuarios</p>
                        @endforelse
                        @if ($collaborators->count() > 5)
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
                                <div
                                    class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                    <i class="bi bi-book text-xs"></i>
                                </div>
                                <div class="flex-1 truncate">
                                    {{-- $course es un Course directamente --}}
                                    <p class="text-xs font-medium text-gray-700 truncate">{{ $course->title }}</p>
                                    <p class="text-xs text-gray-500">
                                        S/ {{ number_format($course->promotion_price ?? $course->price, 2) }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4 text-sm">No hay cursos</p>
                        @endforelse
                        @if ($courses->count() > 5)
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
                <button type="button" @click="confirmSuperBulkEnroll()"
                    :disabled="superBulkLoading || {{ $collaborators->count() }} === 0 || {{ $courses->count() }} === 0"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 sm:gap-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold py-3 sm:py-4 px-6 sm:px-10 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base">
                    <i class="bi bi-rocket" x-show="!superBulkLoading"></i>
                    <svg x-show="superBulkLoading" class="animate-spin h-5 w-5 sm:h-6 sm:w-6 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span x-text="superBulkLoading ? 'Procesando...' : 'EJECUTAR MATRÍCULA EXPRESS'"></span>
                </button>
                <p class="text-xs text-gray-500 text-center mt-4 max-w-md">
                    <i class="bi bi-info-circle"></i>
                    Matriculará a TODOS los usuarios en TODOS los cursos. Los ya matriculados serán omitidos.
                </p>
            </div>
        </div>
    </div>

</div>
