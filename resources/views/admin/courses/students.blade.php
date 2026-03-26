@extends('layouts.admin')
@section('title', 'Estudiantes inscritos: ' . $course->title)
@section('content')

<div class="bg-white rounded-lg shadow-sm">
    <div class="border-b border-gray-200 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Estudiantes Inscritos</h1>
            <p class="mt-1 text-sm text-gray-600 font-medium">{{ $course->title }}</p>
            <div class="mt-2 flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-users mr-1"></i> Total: <span id="totalStudentsCounter" class="ml-1 mr-1">{{ $enrollments->total() }}</span> alumnos
                </span>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left mr-2"></i> Volver a Cursos
            </a>
        </div>
    </div>

    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1 max-w-lg">
                <input type="text" id="searchInput" placeholder="Buscar por nombre, email o DNI..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Inscripción</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Progreso</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-200">
                @include('admin.courses.partials.students-table')
            </tbody>
        </table>
    </div>

    <div class="border-t border-gray-200 px-6 py-4">
        <div id="pagination" class="w-full">
            @if($enrollments->hasPages())
                {{ $enrollments->appends(['search' => request('search')])->links() }}
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let currentPage = 1;
    let searchTerm = '';
    let searchTimeout = null;

    function loadStudents() {
        const params = new URLSearchParams({
            page: currentPage,
            search: searchTerm
        });

        axios.get(`{{ route('admin.courses.students', $course->id) }}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            const data = response.data; // ✅ Ahora es JSON, no HTML crudo

            // 1. Inyectar filas directamente como innerHTML (sin parsear <tr> en un <div>)
            document.getElementById('studentsTableBody').innerHTML = data.table;

            // 2. Inyectar paginación directamente
            document.getElementById('pagination').innerHTML = data.pagination;

            // 3. Actualizar contador
            document.getElementById('totalStudentsCounter').textContent = data.total;
        })
        .catch(error => {
            console.error('Error al cargar estudiantes:', error);
        });
    }

    document.getElementById('searchInput').addEventListener('input', function(e) {
        searchTerm = e.target.value;
        currentPage = 1;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadStudents(), 400);
    });

    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#pagination a');
        if (paginationLink) {
            e.preventDefault();
            const url = new URL(paginationLink.href);
            currentPage = url.searchParams.get('page') || 1;
            loadStudents();
        }
    });
</script>
@endsection