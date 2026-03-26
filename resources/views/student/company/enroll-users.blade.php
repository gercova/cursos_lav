@extends('layouts.student')
@section('title', 'Inscribir Usuarios')
@section('content')
<div class="container mx-auto px-4 py-4 sm:py-6" x-data="enrollManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
            <a href="{{ route('company.list') }}" 
            class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors text-sm sm:text-base w-fit">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a mis usuarios
            </a>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Inscribir a mis colaboradores</h1>
        </div>

        <!-- Stats Cards - Responsive Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <!-- Total Colaboradores -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-blue-800">Total Colaboradores</p>
                        <p class="text-xl sm:text-2xl font-bold text-blue-900 mt-1">{{ $collaborators->count() }}</p>
                    </div>
                    <div class="bg-blue-600 p-2 sm:p-3 rounded-xl">
                        <i class="w-5 h-5 sm:w-6 sm:h-6 text-white fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Cursos con promoción -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-green-800">Cursos</p>
                        <p class="text-xl sm:text-2xl font-bold text-green-900 mt-1">{{ $totalCourses->where('type', 'course')->count() }}</p>
                    </div>
                    <div class="bg-green-600 p-2 sm:p-3 rounded-xl">
                        <i class="w-5 h-5 sm:w-6 sm:h-6 text-white fa-solid fa-book"></i>
                    </div>
                </div>
            </div>
            <!-- Usuarios con código -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-purple-800">Paquetes en promoción</p>
                        <p class="text-xl sm:text-2xl font-bold text-purple-900 mt-1">{{ $totalCourses->where('id', '<>', $package->course_id)->where('type', 'package')->count() }}</p>
                    </div>
                    <div class="bg-purple-600 p-2 sm:p-3 rounded-xl">
                        <i class="w-5 h-5 sm:w-6 sm:h-6 text-white fa-solid fa-tag"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($package->package->plan_type_id == 1)
            <div class="bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 mb-6 rounded-lg">
                <div class="flex items-start gap-2 sm:gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-red-800">
                            • En este paquete puedes seleccionar tus cursos.<br>
                            • Solo una matrícula por usuario por curso.<br>
                            • No hay cursos seleccionados.
                            <a href="{{ route('student.package.select', $package->course_id) }}" class="underline font-semibold hover:text-red-900 block sm:inline mt-1 sm:mt-0">
                                {{-- Configurar promociones --}}
                                Seleccione sus cursos
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Panel principal -->
    <div class="card overflow-hidden border border-gray-200">
        @if($package->package->plan_type_id == 1)
            @include('student.company.partials.enrollment-options-1')
        @elseif ($package->package->plan_type_id !== 1)
            @include('student.company.partials.enrollment-options-2')
        @endif
    </div>
    
    <!-- Modal de confirmación para Mega Matrícula -->
    <div x-show="showSuperBulkConfirmModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-60 backdrop-blur-sm" @click.self="showSuperBulkConfirmModal = false">
        <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden transform transition-all">
                <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-500 to-orange-600">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-xl font-bold text-white">Confirmar Mega Matrícula</h3>
                        <button @click="showSuperBulkConfirmModal = false" class="text-white/80 hover:text-white">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 mb-4">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-rocket text-amber-600 text-lg sm:text-2xl"></i>
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-base sm:text-lg font-semibold text-gray-900">¿Estás completamente seguro?</p>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                Vas a matricular <span class="font-bold text-amber-600">{{ $collaborators->count() }}</span> usuarios 
                                en <span class="font-bold text-amber-600">{{ $courses->count() }}</span> cursos.
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-amber-50 rounded-xl p-3 sm:p-4 mb-4">
                        <p class="text-xs sm:text-sm text-amber-800">
                            <span class="font-bold">Total de matrículas:</span><br>
                            <span class="text-xl sm:text-2xl font-bold text-amber-600">{{ $collaborators->count() * $courses->count() }}</span> inscripciones
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-3 sm:p-4 mb-4">
                        <p class="text-xs sm:text-sm text-gray-700">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Esta operación podría tomar varios segundos. No cierres la ventana.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 mt-6">
                        <button @click="showSuperBulkConfirmModal = false" class="w-full sm:flex-1 px-4 py-2 sm:py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200 text-sm">
                            Cancelar
                        </button>
                        <button @click="executeSuperBulkEnroll(); showSuperBulkConfirmModal = false" class="w-full sm:flex-1 px-4 py-2 sm:py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold rounded-xl shadow-lg transition-all duration-200 text-sm">
                            Sí, ejecutar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de resultados -->
    <div x-show="showBulkResultsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 backdrop-blur-sm" @click.self="showBulkResultsModal = false">
        <div class="flex items-center justify-center min-h-screen p-3 sm:p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] mx-auto overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-xl font-bold text-gray-900" x-text="bulkResults.title || 'Resultados'"></h3>
                        <button @click="showBulkResultsModal = false" class="p-1.5 sm:p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-4 sm:p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="mb-4">
                        <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-green-50 rounded-lg">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-base sm:text-xl"></i>
                            </div>
                            <div class="text-center sm:text-left">
                                <p class="text-sm sm:text-lg font-semibold text-green-800" x-text="bulkResults.message"></p>
                                <p class="text-xs sm:text-sm text-green-700">
                                    <span x-text="bulkResults.success?.length || 0"></span> exitosos, 
                                    <span x-text="bulkResults.failed?.length || 0"></span> fallidos
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="bulkResults.summary" class="mt-4 mb-4 p-3 sm:p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-2 text-sm">Resumen:</h4>
                        <p class="text-xs sm:text-sm text-blue-800" x-html="bulkResults.summary"></p>
                    </div>
                    
                    <div x-show="bulkResults.failed?.length > 0" class="mt-4">
                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">Errores:</h4>
                        <div class="bg-red-50 rounded-lg p-3 sm:p-4 max-h-48 overflow-y-auto">
                            <template x-for="failed in bulkResults.failed" :key="failed.user + failed.course">
                                <div class="text-xs sm:text-sm text-red-700 mb-2 pb-2 border-b border-red-100 last:border-0">
                                    <span class="font-medium" x-text="failed.user || 'Usuario'"></span>
                                    <span x-show="failed.course"> - <span class="text-xs" x-text="failed.course"></span></span>
                                    <span class="block text-xs mt-1" x-text="failed.reason"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="bulkResults.warning" class="mt-4 p-3 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-yellow-800" x-text="bulkResults.warning"></p>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
                    <button @click="showBulkResultsModal = false" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function enrollManager() {
        return {
            activeTab: 'single',
            loading: false,
            bulkLoading: false,
            superBulkLoading: false,
            showBulkResultsModal: false,
            showSuperBulkConfirmModal: false,
            bulkResults: {
                success: [],
                failed: [],
                message: '',
                title: '',
                summary: '',
                warning: ''
            },
            form: {
                user_id: '',
                course_id: ''
            },
            bulkForm: {
                user_ids: [],
                course_id: ''
            },
            
            init() {
                // Inicializar
            },
            
            selectAllUsers() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"][id^="user_"]');
                this.bulkForm.user_ids = [];
                checkboxes.forEach(checkbox => {
                    if (!checkbox.disabled) {
                        this.bulkForm.user_ids.push(checkbox.value);
                    }
                });
            },
            
            async enrollSingle() {
                if (!this.form.user_id || !this.form.course_id) {
                    this.showNotification('Debes seleccionar un usuario y un curso', 'error');
                    return;
                }
                
                this.loading = true;
                
                try {
                    const response = await axios.post('{{ route("company.enroll.with-code") }}', this.form, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.data.success) {
                        this.showNotification(response.data.message, 'success');
                        this.form.user_id = '';
                        this.form.course_id = '';
                        
                        if (this.activeTab === 'history') {
                            const historyComponent = document.querySelector('[x-data="recentEnrollments()"]')?.__x;
                            if (historyComponent) {
                                historyComponent.loadEnrollments();
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error al matricular:', error);
                    const message = error.response?.data?.message || 'Error al matricular al usuario';
                    this.showNotification(message, 'error');
                } finally {
                    this.loading = false;
                }
            },
            
            async enrollBulk() {
                if (this.bulkForm.user_ids.length === 0) {
                    this.showNotification('Debes seleccionar al menos un usuario', 'error');
                    return;
                }
                
                if (!this.bulkForm.course_id) {
                    this.showNotification('Debes seleccionar un curso', 'error');
                    return;
                }
                
                if (!confirm(`¿Estás seguro de matricular ${this.bulkForm.user_ids.length} usuarios en este curso?`)) {
                    return;
                }
                
                this.bulkLoading = true;
                
                try {
                    const response = await axios.post('{{ route("company.enroll.bulk") }}', this.bulkForm, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.data.success) {
                        this.bulkResults = {
                            ...response.data.data,
                            title: 'Matriculación Masiva'
                        };
                        this.showBulkResultsModal = true;
                        
                        this.bulkForm.user_ids = [];
                        this.bulkForm.course_id = '';
                        
                        this.showNotification(response.data.message, 'success');
                        
                        const historyComponent = document.querySelector('[x-data="recentEnrollments()"]')?.__x;
                        if (historyComponent) {
                            historyComponent.loadEnrollments();
                        }
                    }
                } catch (error) {
                    console.error('Error en matriculación masiva:', error);
                    const message = error.response?.data?.message || 'Error en la matriculación masiva';
                    this.showNotification(message, 'error');
                } finally {
                    this.bulkLoading = false;
                }
            },

            confirmSuperBulkEnroll() {
                const usersCount = {{ $collaborators->count() }};
                const coursesCount = {{ $courses->count() }};
                
                if (usersCount === 0) {
                    this.showNotification('No hay usuarios para matricular', 'error');
                    return;
                }
                
                if (coursesCount === 0) {
                    this.showNotification('No hay cursos con precio de promoción disponibles', 'error');
                    return;
                }
                
                this.showSuperBulkConfirmModal = true;
            },
            
            async executeSuperBulkEnroll() {
                this.superBulkLoading = true;
                
                try {
                    const response = await axios.post('{{ route("company.enroll.super-bulk") }}', {}, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (response.data.success) {
                        this.bulkResults = {
                            ...response.data.data,
                            title: '🚀 Mega Matrícula Express'
                        };
                        this.showBulkResultsModal = true;
                        
                        this.showNotification(response.data.message, 'success');
                        
                        const historyComponent = document.querySelector('[x-data="recentEnrollments()"]')?.__x;
                        if (historyComponent) {
                            historyComponent.loadEnrollments();
                        }
                    }
                } catch (error) {
                    console.error('Error en Mega Matrícula:', error);
                    const message = error.response?.data?.message || 'Error en la Mega Matrícula';
                    this.showNotification(message, 'error');
                } finally {
                    this.superBulkLoading = false;
                }
            },

            showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 sm:top-6 sm:right-6 z-50 px-4 sm:px-6 py-3 sm:py-4 rounded-xl shadow-xl transform transition-all duration-300 text-sm sm:text-base max-w-[90vw] ${
                    type === 'success'
                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                    : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                            }
                        </svg>
                        <span class="font-medium break-words">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.add('translate-y-0', 'opacity-100');
                }, 10);

                setTimeout(() => {
                    notification.classList.remove('translate-y-0', 'opacity-100');
                    notification.classList.add('-translate-y-2', 'opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }
        };
    }

    function recentEnrollments() {
        return {
            enrollments: [],
            loading: false,
            
            async loadEnrollments() {
                this.loading = true;
                
                try {
                    const response = await axios.get('{{ route("company.enroll.recent") }}');
                    
                    if (response.data.success) {
                        this.enrollments = response.data.data;
                    }
                } catch (error) {
                    console.error('Error al cargar matriculaciones:', error);
                } finally {
                    this.loading = false;
                }
            },
            
            formatDate(date) {
                if (!date) return 'N/A';
                return new Date(date).toLocaleDateString('es-PE', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        };
    }
</script>
@endsection