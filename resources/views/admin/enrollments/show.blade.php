@extends('layouts.admin')
@section('title', 'Detalle de Inscripción')
@section('content')
    <div class="container-fluid py-4">
        <!-- Botón de regreso -->
        <div class="mb-4">
            <a href="{{ route('admin.enrollments.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="bi bi-arrow-left mr-2"></i> Volver a Inscripciones
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Columna izquierda - Información principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tarjeta de Información del Estudiante -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Información del Estudiante</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="bi bi-person text-2xl text-blue-600"></i>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h4 class="text-xl font-bold text-gray-900">{{ $enrollment->user->names }}</h4>
                                <div class="mt-2 grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="font-medium">{{ $enrollment->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Teléfono</p>
                                        <p class="font-medium">{{ $enrollment->user->phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">DNI</p>
                                        <p class="font-medium">{{ $enrollment->user->dni }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Profesión</p>
                                        <p class="font-medium">{{ $enrollment->user->profession }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Información del Curso -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Información del Curso</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="bi bi-book text-2xl text-purple-600"></i>
                                </div>
                            </div>
                            <div class="ml-6 flex-1">
                                <h4 class="text-xl font-bold text-gray-900">{{ $enrollment->course->title }}</h4>
                                <p class="text-gray-600 mt-2">{{ $enrollment->course->description }}</p>

                                <div class="mt-4 grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Categoría</p>
                                        <p class="font-medium">{{ $enrollment->course->category->name ?? 'Sin categoría' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Instructor</p>
                                        <p class="font-medium">{{ $enrollment->course->instructor->names ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Duración</p>
                                        <p class="font-medium">{{ $enrollment->course->duration ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de Pagos -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Historial de Pagos</h3>
                    </div>
                    <div class="p-6">
                        @if ($enrollment->payments && $enrollment->payments->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Monto</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Método</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Estado</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($enrollment->payments as $payment)
                                            <tr>
                                                <td class="px-4 py-3 text-sm">{{ $payment->transaction_id ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm font-semibold">S/
                                                    {{ number_format($payment->amount, 2) }}</td>
                                                <td class="px-4 py-3 text-sm">{{ ucfirst($payment->payment_method) }}</td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full
                                                {{ $payment->status == 'completed'
                                                    ? 'bg-green-100 text-green-800'
                                                    : ($payment->status == 'pending'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : 'bg-red-100 text-red-800') }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    {{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No hay registros de pago.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna derecha - Estadísticas y acciones -->
            <div class="space-y-6">
                <!-- Tarjeta de Estado -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Estado de Inscripción</h3>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="mb-4">
                                <span
                                    class="px-4 py-2 text-lg rounded-full
                                    {{ $enrollment->status == 'active'
                                        ? 'bg-green-100 text-green-800'
                                        : ($enrollment->status == 'completed'
                                            ? 'bg-purple-100 text-purple-800'
                                            : ($enrollment->status == 'cancelled'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </div>

                            <!-- Progreso -->
                            <div class="mb-6">
                                <p class="text-sm text-gray-500 mb-2">Progreso del Curso</p>
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-blue-600 h-4 rounded-full"
                                        style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                </div>
                                <p class="text-lg font-bold mt-2">{{ $enrollment->progress ?? 0 }}%</p>
                            </div>

                            <!-- Cambiar Estado -->
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Cambiar Estado</p>
                                <select id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-3">
                                    <option value="active" {{ $enrollment->status == 'active' ? 'selected' : '' }}>Activo
                                    </option>
                                    <option value="completed" {{ $enrollment->status == 'completed' ? 'selected' : '' }}>
                                        Completado</option>
                                    <option value="cancelled" {{ $enrollment->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelado</option>
                                    <option value="pending" {{ $enrollment->status == 'pending' ? 'selected' : '' }}>
                                        Pendiente</option>
                                </select>
                                <button onclick="updateStatus()"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Actualizar Estado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Fechas -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Fechas Importantes</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Fecha de Inscripción</p>
                                <p class="font-medium">{{ $enrollment->enrolled_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if ($enrollment->completed_at)
                                <div>
                                    <p class="text-sm text-gray-500">Fecha de Finalización</p>
                                    <p class="font-medium">{{ $enrollment->completed_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif

                            <div>
                                <p class="text-sm text-gray-500">Duración Total</p>
                                <p class="font-medium">
                                    @php
                                        $start = $enrollment->enrolled_at;
                                        $end = $enrollment->completed_at ?? now();
                                        $diff = $start->diff($end);
                                    @endphp
                                    {{ $diff->days }} días, {{ $diff->h }} horas
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Acciones Rápidas</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('admin.users.show', $enrollment->user) }}"
                                class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md">
                                <i class="bi bi-person mr-2"></i>Ver Perfil del Estudiante
                            </a>

                            <a href="{{ route('admin.courses.edit', $enrollment->course) }}"
                                class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md">
                                <i class="bi bi-pencil-square mr-2"></i>Editar Curso
                            </a>

                            <button onclick="generateCertificate()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                <i class="bi bi-patch-check mr-2"></i>Generar Certificado
                            </button>

                            <button onclick="sendReminder()"
                                class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md">
                                <i class="bi bi-envelope mr-2"></i>Enviar Recordatorio
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateStatus() {
            const status = document.getElementById('statusSelect').value;
            const url = `/admin/enrollments/{{ $enrollment->id }}/status`;

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
                    alert('Error al actualizar el estado');
                });
        }

        function generateCertificate() {
            if (confirm('¿Generar certificado para esta inscripción?')) {
                // Lógica para generar certificado
                alert('Certificado generado exitosamente');
            }
        }

        function sendReminder() {
            if (confirm('¿Enviar recordatorio al estudiante?')) {
                // Lógica para enviar recordatorio
                alert('Recordatorio enviado exitosamente');
            }
        }
    </script>
@endsection
