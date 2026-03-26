@extends('layouts.admin')
@section('title', 'Gestión de Inscripciones')
@section('content')
<div class="container-fluid py-4">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Inscripciones</p>
                    <h3 class="text-2xl font-bold">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-play-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Activas</p>
                    <h3 class="text-2xl font-bold">{{ $stats['active'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Completadas</p>
                    <h3 class="text-2xl font-bold">{{ $stats['completed'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Canceladas</p>
                    <h3 class="text-2xl font-bold">{{ $stats['cancelled'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Filtrar Inscripciones</h2>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.enrollments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Usuario o Curso" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                    <select name="course" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los cursos</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-filter mr-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Inscripciones -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Lista de Inscripciones</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progreso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inscripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($enrollments as $enrollment)
                        @php
                            $user = $enrollment->user;
                            $course = $enrollment->course;
                            $hasUser = !is_null($user);
                            $hasCourse = !is_null($course);
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#{{ $enrollment->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $hasUser ? $user->names : 'Usuario eliminado' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $hasUser ? $user->email : 'Email no disponible' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $hasCourse ? $course->title : 'Curso eliminado' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $hasCourse && $course->category ? $course->category->name : 'Sin categoría' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-3">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $enrollment->progress ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $enrollment->status == 'active' ? 'bg-green-100 text-green-800' :
                                    ($enrollment->status == 'completed' ? 'bg-purple-100 text-purple-800' :
                                    ($enrollment->status == 'cancelled' ? 'bg-red-100 text-red-800' :
                                    'bg-yellow-100 text-yellow-800')) }}">
                                    @if($enrollment->status == 'active')
                                        <i class="fas fa-play-circle mr-1"></i>
                                    @elseif($enrollment->status == 'completed')
                                        <i class="fas fa-graduation-cap mr-1"></i>
                                    @elseif($enrollment->status == 'cancelled')
                                        <i class="fas fa-times-circle mr-1"></i>
                                    @else
                                        <i class="fas fa-clock mr-1"></i>
                                    @endif
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('d/m/Y H:i') : 'Fecha no disponible' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($hasUser && $hasCourse)
                                        <a href="{{ route('admin.enrollments.show', $enrollment) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="changeEnrollmentStatus({{ $enrollment->id }}, '{{ $enrollment->status }}')"
                                                class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        {{-- <a href="#" class="text-purple-600 hover:text-purple-900">
                                            <i class="fas fa-chart-line"></i>
                                        </a> --}}
                                    @else
                                        <span class="text-gray-400" title="Inscripción incompleta">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No se encontraron inscripciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($enrollments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Mostrando
                        <span class="font-medium">{{ $enrollments->firstItem() }}</span>
                        a
                        <span class="font-medium">{{ $enrollments->lastItem() }}</span>
                        de
                        <span class="font-medium">{{ $enrollments->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center space-x-2">
                        {{ $enrollments->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal para cambiar estado -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar Estado de Inscripción</h3>

            <form id="statusForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="statusSelect" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active">Activo</option>
                        <option value="completed">Completado</option>
                        <option value="cancelled">Cancelado</option>
                        <option value="pending">Pendiente</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Cancelar
                    </button>
                    <button type="button" onclick="updateEnrollmentStatus()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentEnrollmentId = null;

function changeEnrollmentStatus(enrollmentId, currentStatus) {
    currentEnrollmentId = enrollmentId;

    // Set current status in select
    const statusSelect = document.getElementById('statusSelect');
    statusSelect.value = currentStatus;

    // Show modal
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
    currentEnrollmentId = null;
}

function updateEnrollmentStatus() {
    if (!currentEnrollmentId) return;

    const status = document.getElementById('statusSelect').value;
    const url = `/admin/enrollments/${currentEnrollmentId}/status`;

    axios.patch(url, {
        status: status,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    })
    .then(response => {
        if (response.data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el estado de la inscripción');
    });
}

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
