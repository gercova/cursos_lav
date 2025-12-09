@extends('layouts.student')
@section('title', 'Mis Cursos')
@section('content')
<div x-data="myCoursesApp()" x-init="init()">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Mis Cursos</h1>
        <p class="text-gray-600 mt-2">Gestiona y continúa con tu aprendizaje</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-book text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Cursos Inscritos</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="stats.totalCourses">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-play-circle text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">En Progreso</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="stats.inProgress">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-clock text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Horas Estudiadas</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="stats.hoursStudied">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-trophy text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Completados</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="stats.completed">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                <div class="flex space-x-2">
                    <button @click="filter = 'all'"
                            :class="filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Todos
                    </button>
                    <button @click="filter = 'in_progress'"
                            :class="filter === 'in_progress' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        En Progreso
                    </button>
                    <button @click="filter = 'completed'"
                            :class="filter === 'completed' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Completados
                    </button>
                </div>

                <div class="relative">
                    <input type="text"
                           x-model="search"
                           @input="debounceSearch()"
                           placeholder="Buscar curso..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="p-4">
            <!-- Cursos recientes -->
            <div x-show="recentCourses.length > 0" class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Continuar aprendiendo</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="course in recentCourses" :key="course.id">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow card-hover border border-blue-100">
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full"
                                              :class="course.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                                            <span x-text="course.status === 'completed' ? 'Completado' : course.progress + '%'"></span>
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-800 mt-2" x-text="course.title"></h3>
                                        <p class="text-sm text-gray-600 mt-1" x-text="course.category"></p>
                                    </div>
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                                         :class="course.status === 'completed' ? 'bg-green-100' : 'bg-blue-100'">
                                        <i class="fas" :class="course.status === 'completed' ? 'fa-check text-green-600' : 'fa-book text-blue-600'"></i>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progreso</span>
                                        <span x-text="course.progress + '%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full progress-bar"
                                             :class="course.status === 'completed' ? 'bg-green-600' : 'bg-blue-600'"
                                             :style="'width: ' + course.progress + '%'"></div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-600">
                                        <i class="far fa-clock mr-1"></i>
                                        <span x-text="course.last_accessed"></span>
                                    </div>
                                    <a :href="course.continue_url"
                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                                        <span x-text="course.status === 'completed' ? 'Ver Certificado' : 'Continuar'"></span>
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Todos los cursos -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Todos mis cursos</h2>

                <!-- Estado vacío -->
                <div x-show="filteredCourses.length === 0" class="text-center py-12">
                    <div class="inline-block p-4 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-book-open text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No se encontraron cursos</h3>
                    <p class="text-gray-600 mb-6" x-text="search ? 'No hay resultados para tu búsqueda.' : 'No tienes cursos inscritos.'"></p>
                    <a href="{{ route('cursos') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Explorar cursos
                    </a>
                </div>

                <!-- Lista de cursos -->
                <div x-show="filteredCourses.length > 0" class="space-y-4">
                    <template x-for="course in filteredCourses" :key="course.id">
                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <div class="md:flex">
                                <!-- Imagen del curso -->
                                <div class="md:w-1/4">
                                    <div class="h-48 md:h-full bg-gradient-to-r from-blue-500 to-indigo-600 relative overflow-hidden">
                                        <img x-show="course.image" :src="course.image" :alt="course.title" class="w-full h-full object-cover">
                                        <div x-show="!course.image" class="absolute inset-0 flex items-center justify-center">
                                            <i class="fas fa-book text-white text-5xl opacity-20"></i>
                                        </div>
                                        <div class="absolute top-3 left-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                                  :class="course.status === 'completed' ? 'bg-green-500 text-white' : 'bg-blue-500 text-white'">
                                                <span x-text="course.status === 'completed' ? 'Completado' : 'En progreso'"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenido del curso -->
                                <div class="md:w-3/4 p-6">
                                    <div class="flex flex-col h-full">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-800" x-text="course.title"></h3>
                                                    <div class="flex items-center mt-2 space-x-4">
                                                        <span class="text-sm text-gray-600">
                                                            <i class="fas fa-layer-group mr-1"></i>
                                                            <span x-text="course.modules"></span> módulos
                                                        </span>
                                                        <span class="text-sm text-gray-600">
                                                            <i class="fas fa-video mr-1"></i>
                                                            <span x-text="course.lessons"></span> lecciones
                                                        </span>
                                                        <span class="text-sm text-gray-600">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            <span x-text="course.duration"></span>
                                                        </span>
                                                    </div>
                                                    <p class="text-gray-600 mt-3" x-text="course.description"></p>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <div class="w-16 h-16">
                                                        <div class="relative">
                                                            <svg class="w-16 h-16" viewBox="0 0 36 36">
                                                                <path class="text-gray-200" fill="none" stroke="currentColor" stroke-width="3"
                                                                      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                                <path :class="course.status === 'completed' ? 'text-green-500' : 'text-blue-500'" fill="none" stroke="currentColor" stroke-width="3"
                                                                      :stroke-dasharray="course.progress + ', 100'" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                            </svg>
                                                            <div class="absolute inset-0 flex items-center justify-center">
                                                                <span class="text-lg font-bold"
                                                                      :class="course.status === 'completed' ? 'text-green-600' : 'text-blue-600'"
                                                                      x-text="course.progress + '%'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-500 mt-2">Progreso</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6 pt-6 border-t border-gray-100">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="far fa-calendar mr-2"></i>
                                                        <span>Inscrito: </span>
                                                        <span class="font-medium ml-1" x-text="course.enrolled_date"></span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600 mt-1">
                                                        <i class="far fa-clock mr-2"></i>
                                                        <span>Último acceso: </span>
                                                        <span class="font-medium ml-1" x-text="course.last_accessed || 'No accedido'"></span>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-3">
                                                    <button @click="showCourseDetails(course.id)"
                                                            class="px-4 py-2 border border-gray-300 hover:border-gray-400 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                                                        <i class="fas fa-info-circle mr-2"></i>
                                                        Detalles
                                                    </button>
                                                    <a :href="course.continue_url"
                                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                                                        <i class="fas" :class="course.status === 'completed' ? 'fa-certificate' : 'fa-play'"></i>
                                                        <span class="ml-2" x-text="course.status === 'completed' ? 'Ver Certificado' : 'Continuar'"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Paginación -->
                <div x-show="filteredCourses.length > 0" class="mt-6 flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Mostrando <span x-text="filteredCourses.length"></span> de <span x-text="stats.totalCourses"></span> cursos
                    </div>
                    <div class="flex space-x-2">
                        <button @click="prevPage()" :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <template x-for="page in totalPages" :key="page">
                            <button @click="goToPage(page)"
                                    :class="page === currentPage ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-100'"
                                    class="px-3 py-2 rounded-lg text-sm"
                                    x-text="page"></button>
                        </template>
                        <button @click="nextPage()" :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de detalles del curso -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.away="closeModal" class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" x-text="selectedCourse?.title"></h3>
                        <p class="text-gray-600 mt-2" x-text="selectedCourse?.category"></p>
                    </div>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Progreso del curso</h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Progreso general</span>
                                    <span x-text="selectedCourse?.progress + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full bg-blue-600 progress-bar"
                                         :style="'width: ' + selectedCourse?.progress + '%'"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Lecciones completadas</span>
                                    <span x-text="selectedCourse?.completed_lessons + '/' + selectedCourse?.total_lessons"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full bg-green-600 progress-bar"
                                         :style="'width: ' + (selectedCourse?.completed_lessons / selectedCourse?.total_lessons * 100) + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">Información del curso</h4>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-600">
                                <i class="far fa-calendar mr-3 text-blue-500"></i>
                                <div>
                                    <p class="text-sm">Fecha de inscripción</p>
                                    <p class="font-medium" x-text="selectedCourse?.enrolled_date"></p>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="far fa-clock mr-3 text-green-500"></i>
                                <div>
                                    <p class="text-sm">Último acceso</p>
                                    <p class="font-medium" x-text="selectedCourse?.last_accessed || 'No accedido'"></p>
                                </div>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-trophy mr-3 text-yellow-500"></i>
                                <div>
                                    <p class="text-sm">Estado</p>
                                    <p class="font-medium">
                                        <span x-text="selectedCourse?.status === 'completed' ? 'Completado' : 'En progreso'"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-end space-x-3">
                        <button @click="closeModal"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Cerrar
                        </button>
                        <a :href="selectedCourse?.continue_url"
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <span x-text="selectedCourse?.status === 'completed' ? 'Ver Certificado' : 'Continuar Curso'"></span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function myCoursesApp() {
    return {
        search: '',
        filter: 'all',
        courses: @json($enrollments->map(function($enrollment) {
            $course = $enrollment->course;
            $progress = $enrollment->progress ?: 0;

            return [
                'id' => $enrollment->id,
                'course_id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'category' => $course->category ? $course->category->name : 'Sin categoría',
                'image' => $course->image_url ?: null,
                'progress' => $progress,
                'status' => $progress >= 100 ? 'completed' : 'in_progress',
                'modules' => $course->sections ? $course->sections->count() : 0,
                'lessons' => $course->sections ? $course->sections->sum(function($section) {
                    return $section->lessons ? $section->lessons->count() : 0;
                }) : 0,
                'duration' => $course->duration ?: '0 horas',
                'enrolled_date' => $enrollment->created_at->format('d/m/Y'),
                'last_accessed' => $enrollment->last_accessed_at ? $enrollment->last_accessed_at->format('d/m/Y H:i') : null,
                'completed_lessons' => $enrollment->completed_lessons_count ?: 0,
                'total_lessons' => $course->total_lessons ?: 0,
                'continue_url' => route('course.learn', $course->slug)
            ];
        })),
        stats: {
            totalCourses: 0,
            inProgress: 0,
            completed: 0,
            hoursStudied: 0
        },
        recentCourses: [],
        selectedCourse: null,
        showModal: false,
        searchTimeout: null,
        itemsPerPage: 5,
        currentPage: 1,

        get filteredCourses() {
            let filtered = this.courses;

            // Aplicar filtro
            if (this.filter === 'in_progress') {
                filtered = filtered.filter(course => course.progress < 100);
            } else if (this.filter === 'completed') {
                filtered = filtered.filter(course => course.progress >= 100);
            }

            // Aplicar búsqueda
            if (this.search.trim()) {
                const searchTerm = this.search.toLowerCase();
                filtered = filtered.filter(course =>
                    course.title.toLowerCase().includes(searchTerm) ||
                    course.description.toLowerCase().includes(searchTerm) ||
                    course.category.toLowerCase().includes(searchTerm)
                );
            }

            // Paginación
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;

            return filtered.slice(start, end);
        },

        get totalPages() {
            let filtered = this.courses;

            if (this.filter === 'in_progress') {
                filtered = filtered.filter(course => course.progress < 100);
            } else if (this.filter === 'completed') {
                filtered = filtered.filter(course => course.progress >= 100);
            }

            if (this.search.trim()) {
                const searchTerm = this.search.toLowerCase();
                filtered = filtered.filter(course =>
                    course.title.toLowerCase().includes(searchTerm) ||
                    course.description.toLowerCase().includes(searchTerm) ||
                    course.category.toLowerCase().includes(searchTerm)
                );
            }

            return Math.ceil(filtered.length / this.itemsPerPage);
        },

        init() {
            this.calculateStats();
            this.getRecentCourses();
        },

        calculateStats() {
            this.stats.totalCourses = this.courses.length;
            this.stats.inProgress = this.courses.filter(course => course.progress < 100).length;
            this.stats.completed = this.courses.filter(course => course.progress >= 100).length;
            this.stats.hoursStudied = Math.floor(this.courses.reduce((total, course) =>
                total + (course.lessons * 0.5), 0
            ));
        },

        getRecentCourses() {
            // Tomar los 3 cursos con más reciente acceso o los más recientemente inscritos
            this.recentCourses = [...this.courses]
                .sort((a, b) => {
                    if (a.last_accessed && b.last_accessed) {
                        return new Date(b.last_accessed) - new Date(a.last_accessed);
                    }
                    return b.id - a.id;
                })
                .slice(0, 3);
        },

        debounceSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.currentPage = 1;
            }, 300);
        },

        showCourseDetails(courseId) {
            this.selectedCourse = this.courses.find(course => course.id === courseId);
            this.showModal = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.showModal = false;
            this.selectedCourse = null;
            document.body.style.overflow = 'auto';
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.scrollToTop();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.scrollToTop();
            }
        },

        goToPage(page) {
            this.currentPage = page;
            this.scrollToTop();
        },

        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    };
}
</script>
@endsection
