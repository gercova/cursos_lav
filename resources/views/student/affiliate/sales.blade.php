@extends('layouts.student')
@section('title', 'Historial de Ventas')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Historial de Ventas</h1>
        <p class="text-gray-600 mt-2">Revisa el detalle de todas las conversiones generadas con tu código de afiliado.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Todas las transacciones</h2>
            <span class="text-sm text-gray-500">Mostrando página {{ $sales->currentPage() }} de {{ $sales->lastPage() }}</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-sm text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">Curso / Cliente</th>
                        <th class="px-6 py-4 font-medium text-center">Fecha</th>
                        <th class="px-6 py-4 font-medium text-center">Estado</th>
                        <th class="px-6 py-4 font-medium text-right">Precio Curso</th>
                        <th class="px-6 py-4 font-medium text-right">Monto Venta</th>
                        <th class="px-6 py-4 font-medium text-right text-emerald-600">Tu Comisión</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 line-clamp-1" title="{{ $sale->course->title ?? 'Curso no disponible' }}">
                                {{ $sale->course->title ?? 'Curso no disponible' }}
                            </p>
                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                <i class="fas fa-user text-xs mr-1 text-gray-400"></i>
                                {{ $sale->buyer->names ?? 'Cliente anónimo' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                            {{ $sale->sold_at->format('d/m/Y') }}
                            <div class="text-xs text-gray-400">{{ $sale->sold_at->format('H:i') }}</div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClasses = [
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'pending'   => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'failed'    => 'bg-red-100 text-red-800 border-red-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $statusLabels = [
                                    'completed' => 'Completada',
                                    'pending'   => 'Pendiente',
                                    'failed'    => 'Fallida',
                                    'cancelled' => 'Cancelada',
                                ];
                                $currentClass = $statusClasses[$sale->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                $currentLabel = $statusLabels[$sale->status] ?? ucfirst($sale->status);
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $currentClass }}">
                                {{ $currentLabel }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right text-sm text-gray-500">
                            S/ {{ number_format($sale->course->price ?? 0, 2) }}
                        </td>

                        <td class="px-6 py-4 text-right text-sm font-medium text-gray-700">
                            S/ {{ number_format($sale->sale_amount, 2) }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-emerald-600">
                                S/ {{ number_format($sale->commission_amount, 2) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-50 flex items-center justify-center">
                                <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600 font-medium">Aún no tienes ventas registradas</p>
                            <p class="text-sm text-gray-500 mt-1">Cuando los usuarios compren con tu código, aparecerán aquí.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection