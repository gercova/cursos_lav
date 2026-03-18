@extends('layouts.student')
@section('title', 'Reportes - Afiliado')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Reportes y Estadísticas</h1>
        <p class="text-gray-600 mt-2">Analiza el rendimiento de tus ventas</p>
    </div>

    <!-- Filtro de fechas -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rango de Fechas</label>
                <div class="flex items-center space-x-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                        class="flex-1 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <span class="text-gray-500">a</span>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="flex-1 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Generar Reporte
                </button>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="exportReport()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-file-export mr-2"></i>
                    Exportar Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Resumen del período -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ventas Totales</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $periodStats['total_sales'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Ingresos Totales</p>
                    <p class="text-2xl font-bold text-gray-800">S/ {{ number_format($periodStats['total_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-emerald-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Comisiones</p>
                    <p class="text-2xl font-bold text-gray-800">S/ {{ number_format($periodStats['total_commission'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hand-holding-usd text-amber-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tasa de Conversión</p>
                    <p class="text-2xl font-bold text-gray-800">
                        @if($periodStats['total_sales'] > 0)
                            {{ round(($periodStats['completed_sales'] / $periodStats['total_sales']) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de ventas por día -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Ventas por Día</h2>
        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Tabla detallada -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">Ventas Diarias</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ventas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ingresos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Promedio por Venta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día de la Semana</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salesByDay as $day)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $day->count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            S/ {{ number_format($day->revenue, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            S/ {{ number_format($day->count > 0 ? $day->revenue / $day->count : 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($day->date)->translatedFormat('l') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No hay datos para el período seleccionado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function exportReport() {
        // Implementar exportación a Excel
        alert('Funcionalidad de exportación en desarrollo');
    }

    // Gráfico de ventas por día
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = @json($salesByDay);
        
        const labels = salesData.map(item => {
            const date = new Date(item.date);
            return date.getDate() + '/' + (date.getMonth() + 1);
        });
        
        const data = salesData.map(item => item.count);
        const revenue = salesData.map(item => item.revenue);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas',
                    data: data,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' ventas';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection