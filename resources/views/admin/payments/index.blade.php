@extends('layouts.admin')
@section('title', 'Gestión de Pagos')
@section('content')
<div class="container-fluid py-4">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Total Recaudado</p>
                    <h3 class="text-2xl font-bold">S/ {{ number_format($stats['total'], 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Pendientes</p>
                    <h3 class="text-2xl font-bold">S/ {{ number_format($stats['pending'], 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Completados</p>
                    <h3 class="text-2xl font-bold">S/ {{ number_format($stats['completed'], 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-500 text-sm">Fallidos</p>
                    <h3 class="text-2xl font-bold">S/ {{ number_format($stats['failed'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Filtrar Pagos</h2>
        </div>
        <div class="p-4">
            <form id="filterForm" method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="ID, Usuario o Curso"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Método</label>
                    <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transfer" {{ request('method') == 'transfer' ? 'selected' : '' }}>Transferencia</option>
                        <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Efectivo</option>
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

    <!-- Tabla de Pagos -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Lista de Pagos</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $payment->transaction_id ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $payment->user->names ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        {{-- <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->order && $payment->order->items->first())
                                <div class="text-sm text-gray-900">
                                    {{ $payment->order->items->first()->course_title ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $payment->order->items->count() }} curso(s)
                                </div>
                            @else
                                <span class="text-sm text-gray-500">N/A</span>
                            @endif
                        </td> --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->order && $payment->order->items->first())
                                <div class="text-sm text-gray-900">
                                    {{ $payment->order->items->first()->course_title ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $payment->order->items->count() }} curso(s)
                                </div>
                            @else
                                <span class="text-sm text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">S/ {{ number_format($payment->amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $payment->payment_method == 'card' ? 'bg-purple-100 text-purple-800' :
                                   ($payment->payment_method == 'transfer' ? 'bg-blue-100 text-blue-800' :
                                   'bg-green-100 text-green-800') }}">
                                {{ ucfirst($payment->payment_method ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $payment->status == 'completed' ? 'bg-green-100 text-green-800' :
                                   ($payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($payment->status == 'failed' ? 'bg-red-100 text-red-800' :
                                   'bg-gray-100 text-gray-800')) }}">
                                @if($payment->status == 'completed')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($payment->status == 'pending')
                                    <i class="fas fa-clock mr-1"></i>
                                @elseif($payment->status == 'failed')
                                    <i class="fas fa-times-circle mr-1"></i>
                                @else
                                    <i class="fas fa-exchange-alt mr-1"></i>
                                @endif
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="changePaymentStatus({{ $payment->id }}, '{{ $payment->status }}')"
                                        class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="#" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron pagos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payments->links() }}
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar Estado del Pago</h3>

            <form id="statusForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="statusSelect" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">Pendiente</option>
                        <option value="completed">Completado</option>
                        <option value="failed">Fallido</option>
                        <option value="refunded">Reembolsado</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Cancelar
                    </button>
                    <button type="button" onclick="updatePaymentStatus()"
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
let currentPaymentId = null;

function changePaymentStatus(paymentId, currentStatus) {
    currentPaymentId = paymentId;

    // Set current status in select
    const statusSelect = document.getElementById('statusSelect');
    statusSelect.value = currentStatus;

    // Show modal
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
    currentPaymentId = null;
}

function updatePaymentStatus() {
    if (!currentPaymentId) return;

    const status = document.getElementById('statusSelect').value;
    const url = `/admin/payments/${currentPaymentId}/status`;

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
        alert('Error al actualizar el estado del pago');
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
