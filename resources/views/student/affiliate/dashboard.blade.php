@extends('layouts.student')
@section('title', 'Dashboard de Afiliado')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard de Afiliados</h1>
        <p class="text-gray-600 mt-2">Gana comisiones promocionando nuestros cursos</p>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Ventas</p>
                    <p class="text-2xl font-bold text-gray-800" id="total-sales">{{ $stats['total_sales'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-emerald-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Comisiones Totales</p>
                    <p class="text-2xl font-bold text-gray-800" id="total-commission">S/ {{ number_format($stats['total_commission'], 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ventas Pendientes</p>
                    <p class="text-2xl font-bold text-gray-800" id="pending-sales">{{ $stats['pending_sales'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ventas Completadas</p>
                    <p class="text-2xl font-bold text-gray-800" id="completed-sales">{{ $stats['completed_sales'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Ventas Recientes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Ventas Recientes</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentSales as $sale)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $sale->course->title ?? 'Curso no disponible' }}</p>
                            <div class="flex items-center mt-1 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-user mr-1 text-xs"></i>
                                    {{ $sale->buyer->names ?? 'Cliente' }}
                                </span>
                                <span class="mx-2">•</span>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1 text-xs"></i>
                                    {{ $sale->sold_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">S/ {{ number_format($sale->sale_amount, 2) }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($sale->status == 'completed') bg-green-100 text-green-800
                                @elseif($sale->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-chart-line text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-600">Aún no tienes ventas registradas</p>
                    <p class="text-sm text-gray-500 mt-1">Comparte tu enlace de afiliado para comenzar</p>
                </div>
                @endforelse
            </div>
            @if($recentSales->count() > 0)
            <div class="px-6 py-3 border-t border-gray-100">
                <a href="{{ route('student.affiliate.sales') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    Ver todas las ventas
                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            @endif
        </div>

        <!-- Cursos Más Vendidos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Cursos Más Vendidos</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topCourses as $course)
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                            <i class="fas fa-book text-blue-600"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="font-medium text-gray-900 truncate">{{ $course->course->title ?? 'Curso no disponible' }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-shopping-cart mr-1"></i>
                                    {{ $course->sales_count }} ventas
                                </span>
                                <span class="text-sm font-semibold text-emerald-600">
                                    S/ {{ number_format($course->total_revenue, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-trophy text-gray-400 text-xl"></i>
                    </div>
                    <p class="text-gray-600">No hay datos de ventas por curso</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Enlace de Afiliado -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Tu Enlace de Afiliado</h3>
                <p class="text-gray-600 mt-1">Comparte este enlace para promocionar todos los cursos</p>
                <div class="mt-4 flex items-center space-x-4">
                    <div class="bg-white rounded-lg border border-gray-200 px-4 py-3 flex-1">
                        <code id="affiliate-url" class="text-sm text-gray-800 break-all">
                            {{ $user->affiliate_url }}
                        </code>
                    </div>
                    <button onclick="copyAffiliateLink()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-copy mr-2"></i>
                        Copiar
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Recibirás una comisión por cada venta realizada a través de tu enlace
                </p>
            </div>
            <div class="hidden lg:block">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                    <i class="fas fa-link text-blue-600 text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyAffiliateLink() {
        const url = document.getElementById('affiliate-url').textContent;
        navigator.clipboard.writeText(url).then(() => {
            // Mostrar mensaje de éxito
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i>Copiado!';
            button.classList.remove('bg-blue-600');
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600');
                button.classList.add('bg-blue-600');
            }, 2000);
        });
    }

    // Actualizar estadísticas cada 30 segundos
    setInterval(() => {
        fetch('/api/student/affiliate/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-sales').textContent = data.total_sales;
                document.getElementById('total-commission').textContent = 'S/ ' + data.total_commission;
                document.getElementById('pending-sales').textContent = data.pending_sales;
            });
    }, 30000);
</script>
@endsection