@extends('layouts.student') 
@section('title', 'Arma tu Paquete')
@section('content')
<div x-data="courseSelector({{ $package->id }}, {{ $package->course_limit }})" class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Arma tu paquete: {{ $package->title }}</h1>
            <p class="text-gray-500 mt-1">Selecciona los cursos que deseas incluir. Tienes un límite establecido.</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center min-w-[150px]">
            <span class="block text-sm text-blue-600 font-semibold mb-1">Cursos seleccionados</span>
            <span class="text-3xl font-bold" :class="selected.length === limit ? 'text-green-600' : 'text-blue-700'">
                <span x-text="selected.length"></span> / <span x-text="limit"></span>
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row gap-4">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" x-model.debounce.500ms="search" placeholder="Buscar cursos por nombre..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
        </div>
        <div class="sm:w-64">
            <select x-model="categoryId" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="relative min-h-[400px]">
        <div x-show="loading" class="absolute inset-0 bg-white/80 z-10 flex items-center justify-center rounded-xl">
            <div class="loading-spinner"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <template x-for="course in courses" :key="course.id">
                <div class="card cursor-pointer relative overflow-hidden group" @click="toggleCourse(course.id)" :class="selected.includes(course.id) ? 'ring-2 ring-blue-500 shadow-md' : ''">
                    
                    <div x-show="selected.includes(course.id)" x-transition class="absolute top-2 right-2 bg-blue-500 text-white w-8 h-8 flex items-center justify-center rounded-full z-10 shadow-lg">
                        <i class="fas fa-check"></i>
                    </div>

                    <div class="h-48 w-full bg-gray-200 overflow-hidden">
                        <img :src="course.image_url" :alt="course.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    
                    <div class="card-body p-4">
                        <h3 class="font-semibold text-gray-800 line-clamp-2 mb-2" x-text="course.title"></h3>
                        <p class="text-sm text-gray-500 line-clamp-2 mb-4" x-text="course.short_description"></p>
                        
                        <div class="flex items-center gap-2 mt-auto">
                            <input type="checkbox" :value="course.id" x-model="selected" :disabled="selected.length >= limit && !selected.includes(course.id)" class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500 cursor-pointer pointer-events-none">
                            <span class="text-sm font-medium" :class="selected.includes(course.id) ? 'text-blue-600' : 'text-gray-600'" x-text="selected.includes(course.id) ? 'Seleccionado' : 'Seleccionar'"></span>
                        </div>
                    </div>

                    <div x-show="selected.length >= limit && !selected.includes(course.id)" class="absolute inset-0 bg-gray-100/50 cursor-not-allowed"></div>
                </div>
            </template>
        </div>

        <div x-show="!loading && courses.length === 0" class="text-center py-12">
            <i class="fas fa-folder-open text-gray-300 text-5xl mb-3"></i>
            <p class="text-gray-500">No se encontraron cursos con esos filtros.</p>
        </div>
    </div>

    <div x-show="pagination.last_page > 1" class="flex justify-center gap-2 pb-6">
        <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            Anterior
        </button>
        <span class="px-4 py-2 text-gray-600 font-medium">
            Página <span x-text="pagination.current_page"></span> de <span x-text="pagination.last_page"></span>
        </span>
        <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition">
            Siguiente
        </button>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 z-40 md:left-[260px] shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4">
            <p class="text-gray-600 hidden sm:block">
                <span x-show="selected.length < limit">Te faltan <b x-text="limit - selected.length"></b> cursos por elegir.</span>
                <span x-show="selected.length === limit" class="text-green-600 font-medium"><i class="fas fa-check-circle mr-1"></i>¡Paquete completo!</span>
            </p>
            <button @click="saveSelection()" :disabled="selected.length === 0 || saving" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-8 rounded-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 ml-auto">
                <span x-show="!saving"><i class="fas fa-save"></i> Guardar mi paquete</span>
                <span x-show="saving"><i class="fas fa-circle-notch fa-spin"></i> Guardando...</span>
            </button>
        </div>
    </div>
    
    <div class="h-24"></div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('courseSelector', (packageId, limit) => ({
            packageId: packageId,
            limit: limit,
            courses: [],
            selected: [],
            search: '',
            categoryId: '',
            loading: false,
            saving: false,
            pagination: {
                current_page: 1,
                last_page: 1
            },

            init() {
                this.fetchCourses();
                
                // Watchers para reiniciar paginación y buscar al escribir o cambiar categoría
                this.$watch('search', () => { 
                    this.pagination.current_page = 1; 
                    this.fetchCourses(); 
                });
                this.$watch('categoryId', () => { 
                    this.pagination.current_page = 1; 
                    this.fetchCourses(); 
                });
            },

            async fetchCourses() {
                this.loading = true;
                try {
                    const response = await axios.get('/api/student/package/courses', {
                        params: {
                            search: this.search,
                            category_id: this.categoryId,
                            page: this.pagination.current_page
                        }
                    });
                    
                    this.courses = response.data.data;
                    this.pagination = {
                        current_page: response.data.current_page,
                        last_page: response.data.last_page
                    };
                } catch (error) {
                    console.error("Error cargando cursos:", error);
                    alert("Ocurrió un error al cargar los cursos.");
                } finally {
                    this.loading = false;
                }
            },

            changePage(page) {
                if (page >= 1 && page <= this.pagination.last_page) {
                    this.pagination.current_page = page;
                    this.fetchCourses();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            toggleCourse(courseId) {
                if (this.selected.includes(courseId)) {
                    // Si ya está, lo quitamos
                    this.selected = this.selected.filter(id => id !== courseId);
                } else {
                    // Si no está y no hemos llegado al límite, lo agregamos
                    if (this.selected.length < this.limit) {
                        this.selected.push(courseId);
                    } else {
                        // Feedback visual (opcional)
                        alert(`Ya has alcanzado el límite de ${this.limit} cursos para este paquete.`);
                    }
                }
            },

            async saveSelection() {
                if (this.selected.length === 0) return;
                
                this.saving = true;
                try {
                    const response = await axios.post(`/package/${this.packageId}/save-courses`, {
                        selected_courses: this.selected
                    });
                    
                    if (response.data.success) {
                        // Puedes usar SweetAlert aquí si lo prefieres
                        alert(response.data.message);
                        window.location.href = response.data.redirect;
                    }
                } catch (error) {
                    console.error("Error guardando selección:", error);
                    const msg = error.response?.data?.message || "Ocurrió un error al guardar tu selección.";
                    alert(msg);
                } finally {
                    this.saving = false;
                }
            }
        }));
    });
</script>
@endsection