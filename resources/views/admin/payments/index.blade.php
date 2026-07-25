@extends('layouts.admin')
@section('title', 'Gestión de Pagos')
@section('content')
    <div class="container-fluid py-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="bi bi-cash-stack text-2xl"></i>
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
                        <i class="bi bi-clock text-2xl"></i>
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
                        <i class="bi bi-check-circle text-2xl"></i>
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
                        <i class="bi bi-x-octagon text-2xl"></i>
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
                <form id="filterForm" method="GET" action="{{ route('admin.payments.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ID, Usuario o Curso"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado
                            </option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Reembolsado
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Método</label>
                        <select name="method"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Tarjeta</option>
                            <option value="transfer" {{ request('method') == 'transfer' ? 'selected' : '' }}>Transferencia
                            </option>
                            <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Efectivo</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            <i class="bi bi-filter mr-2"></i>Filtrar
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $payment->payment_id ?? $payment->id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $payment->user->names ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($payment->order && $payment->order->items->first())
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
                                    <span class="text-sm font-semibold text-gray-900">S/
                                        {{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                {{ $payment->payment_method == 'card'
                                    ? 'bg-purple-100 text-purple-800'
                                    : ($payment->payment_method == 'transfer'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($payment->payment_method ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full
                                {{ $payment->status == 'completed'
                                    ? 'bg-green-100 text-green-800'
                                    : ($payment->status == 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($payment->status == 'failed'
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-gray-100 text-gray-800')) }}">
                                        @if ($payment->status == 'completed')
                                            <i class="bi bi-check-circle mr-1"></i>
                                        @elseif($payment->status == 'pending')
                                            <i class="bi bi-clock mr-1"></i>
                                        @elseif($payment->status == 'failed')
                                            <i class="bi bi-times-circle mr-1"></i>
                                        @else
                                            <i class="bi bi-exchange-alt mr-1"></i>
                                        @endif
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        onclick="changePaymentStatus({{ $payment->id }}, '{{ $payment->status }}')"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-xs font-semibold rounded-lg transition-colors"
                                        title="Ver detalles del pago y actualizar estado">
                                        <i class="bi bi-eye mr-1.5 text-sm"></i> Detalles y Estado
                                    </button>
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
            @if ($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="text-sm text-gray-700">
                            Mostrando
                            <span class="font-medium">{{ $payments->firstItem() }}</span>
                            a
                            <span class="font-medium">{{ $payments->lastItem() }}</span>
                            de
                            <span class="font-medium">{{ $payments->total() }}</span>
                            resultados
                        </div>

                        <div class="flex items-center space-x-2">
                            {{ $payments->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para ver detalles y cambiar estado -->
    <div id="statusModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-10 mx-auto p-6 border-0 w-full max-w-2xl shadow-2xl rounded-2xl bg-white mb-10">
            <!-- Header Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                <div class="flex items-center space-x-3">
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="bi bi-receipt-cutoff text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Detalles de la Transacción</h3>
                        <p class="text-xs text-gray-500" id="modalPaymentIdDisplay">ID: #---</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Body Modal: Transaction Details -->
            <div class="space-y-6">
                <!-- Cliente & Resumen Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Información del Cliente -->
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <i class="bi bi-person mr-1.5 text-blue-500"></i> Información del Cliente
                        </div>
                        <p class="text-sm font-semibold text-gray-800" id="modalUserName">---</p>
                        <p class="text-xs text-gray-500" id="modalUserEmail">---</p>
                    </div>

                    <!-- Resumen del Pago -->
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            <i class="bi bi-credit-card mr-1.5 text-green-500"></i> Resumen de Pago
                        </div>
                        <div class="flex justify-between items-baseline">
                            <span class="text-xs text-gray-500">Monto:</span>
                            <span class="text-base font-bold text-gray-900" id="modalAmount">S/ 0.00</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-gray-500">Método:</span>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md bg-blue-100 text-blue-800" id="modalPaymentMethod">---</span>
                        </div>
                    </div>
                </div>

                <!-- Fecha & ID Transacción Gateway Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                        <span class="text-xs text-gray-500 flex items-center"><i class="bi bi-hash text-gray-400 mr-1"></i> ID Pasarela:</span>
                        <span class="text-xs font-mono font-semibold text-gray-700" id="modalGatewayId">---</span>
                    </div>
                    <div class="p-3 bg-white border border-gray-200 rounded-xl flex items-center justify-between">
                        <span class="text-xs text-gray-500 flex items-center"><i class="bi bi-calendar-event text-gray-400 mr-1"></i> Fecha:</span>
                        <span class="text-xs text-gray-700 font-medium" id="modalCreatedAt">---</span>
                    </div>
                </div>

                <!-- Orden e Ítems Comprados -->
                <div class="p-4 bg-white border border-gray-200 rounded-xl">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider flex items-center">
                            <i class="bi bi-cart-check mr-1.5 text-indigo-500"></i> Orden de Compra
                        </span>
                        <span class="text-xs font-mono bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded" id="modalOrderNumber">Orden #---</span>
                    </div>
                    <div id="modalOrderItems" class="space-y-2 max-h-36 overflow-y-auto pr-1">
                        <!-- Items rendered dynamically -->
                    </div>
                </div>

                <!-- Mensaje de Error si aplica -->
                <div id="modalErrorContainer" class="hidden p-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs flex items-start space-x-2">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 mt-0.5"></i>
                    <div>
                        <span class="font-semibold block">Detalle del Error:</span>
                        <span id="modalErrorMessage">---</span>
                    </div>
                </div>

                <!-- Detalle técnico / Payment details si existen -->
                <div id="modalDetailsContainer" class="hidden p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs">
                    <span class="font-semibold text-gray-600 block mb-1 flex items-center">
                        <i class="bi bi-info-circle text-blue-500 mr-1"></i> Detalles Técnicos del Pago:
                    </span>
                    <pre id="modalDetailsJson" class="bg-gray-900 text-gray-100 p-2.5 rounded-lg text-[11px] overflow-x-auto font-mono max-h-28"></pre>
                </div>

                <!-- Actualización de Estado Form -->
                <div class="pt-4 border-t border-gray-200">
                    <form id="statusForm">
                        @csrf
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-blue-50/60 p-4 rounded-xl border border-blue-100">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1 flex items-center">
                                    <i class="bi bi-pencil-square text-blue-600 mr-1.5"></i> Cambiar Estado del Pago
                                </label>
                                <p class="text-xs text-gray-500">Selecciona el nuevo estado para actualizar la transacción.</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <select id="statusSelect" name="status"
                                    class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm font-medium">
                                    <option value="pending"> Pendiente</option>
                                    <option value="completed"> Completado</option>
                                    <option value="failed"> Fallido</option>
                                    <option value="refunded"> Reembolsado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex justify-end space-x-3 mt-5">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center">
                                <i class="bi bi-x-circle mr-1.5"></i> Cerrar
                            </button>
                            <button type="button" onclick="updatePaymentStatus()"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center">
                                <i class="bi bi-check2-circle mr-1.5"></i> Actualizar Estado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const paymentsData = @json($payments->keyBy('id'));
        let currentPaymentId = null;

        function changePaymentStatus(paymentId, currentStatus) {
            currentPaymentId = paymentId;
            const payment = paymentsData[paymentId];

            if (!payment) return;

            // Header ID
            document.getElementById('modalPaymentIdDisplay').innerText = `ID Interno: #${payment.id}` + (payment.payment_id ? ` | Pasarela: ${payment.payment_id}` : '');

            // User details
            document.getElementById('modalUserName').innerText = payment.user ? payment.user.names : 'N/A';
            document.getElementById('modalUserEmail').innerText = payment.user ? payment.user.email : 'Sin email';

            // Financial details
            const formattedAmount = parseFloat(payment.amount || 0).toFixed(2);
            document.getElementById('modalAmount').innerText = `${payment.currency || 'S/'} ${formattedAmount}`;
            document.getElementById('modalPaymentMethod').innerText = (payment.payment_method || 'N/A').toUpperCase();

            // Gateway & Dates
            document.getElementById('modalGatewayId').innerText = payment.payment_id || 'N/A';
            const createdAtDate = payment.created_at ? new Date(payment.created_at).toLocaleString('es-PE') : 'N/A';
            document.getElementById('modalCreatedAt').innerText = createdAtDate;

            // Order items
            const orderItemsContainer = document.getElementById('modalOrderItems');
            orderItemsContainer.innerHTML = '';
            
            if (payment.order) {
                document.getElementById('modalOrderNumber').innerText = `Orden #${payment.order.order_number || payment.order.id}`;
                if (payment.order.items && payment.order.items.length > 0) {
                    payment.order.items.forEach(item => {
                        const itemEl = document.createElement('div');
                        itemEl.className = 'flex justify-between items-center text-xs p-2 bg-gray-50 rounded-lg border border-gray-100';
                        itemEl.innerHTML = `
                            <span class="font-medium text-gray-800 flex items-center">
                                <i class="bi bi-journal-bookmark mr-1.5 text-blue-500"></i> ${item.course_title || 'Curso'}
                            </span>
                            <span class="font-bold text-gray-700">S/ ${parseFloat(item.price || 0).toFixed(2)}</span>
                        `;
                        orderItemsContainer.appendChild(itemEl);
                    });
                } else {
                    orderItemsContainer.innerHTML = '<p class="text-xs text-gray-500 italic">No hay ítems registrados en la orden.</p>';
                }
            } else {
                document.getElementById('modalOrderNumber').innerText = 'Sin orden asociada';
                orderItemsContainer.innerHTML = '<p class="text-xs text-gray-500 italic">Sin información de orden.</p>';
            }

            // Error message if present
            const errorContainer = document.getElementById('modalErrorContainer');
            if (payment.error_message) {
                document.getElementById('modalErrorMessage').innerText = payment.error_message;
                errorContainer.classList.remove('hidden');
            } else {
                errorContainer.classList.add('hidden');
            }

            // Details JSON if present
            const detailsContainer = document.getElementById('modalDetailsContainer');
            if (payment.payment_details && Object.keys(payment.payment_details).length > 0) {
                document.getElementById('modalDetailsJson').innerText = JSON.stringify(payment.payment_details, null, 2);
                detailsContainer.classList.remove('hidden');
            } else {
                detailsContainer.classList.add('hidden');
            }

            // Set select value
            const statusSelect = document.getElementById('statusSelect');
            statusSelect.value = payment.status || currentStatus;

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
