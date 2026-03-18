@extends('layouts.student')
@section('title', 'Historial de Ventas - Afiliado')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Historial de Ventas</h1>
            <p class="text-gray-600 mt-2">Seguimiento detallado de todas las transacciones con tu código</p>
        </div>
        <a href="{{ route('student.affiliate.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-md p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Comisión Generada</p>
                    <h3 class="text-3xl font-bold">S/ {{ number_format($totalCommission, 2) }}</h3>
                    <p class="text-xs text-emerald-100 mt-2">* Solo incluye ventas completadas</p>
                </div>
                <div class="bg-white/20 p-3 rounded-lg">
                    <i class="fas fa-hand-holding-usd text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-3 gap-2 text-center text-sm">
                <div>
                    <span class="block font-bold">{{ $stats['completed'] }}</span>
                    <span class="text-emerald-100 text-xs">Exitosas</span>
                </div>
                <div>
                    <span class="block font-bold">{{ $stats['pending'] }}</span>
                    <span class="text-emerald-100 text-xs">Pendientes</span>
                </div>
                <div>
                    <span class="block font-bold">{{ $stats['failed'] }}</span>
                    <span class="text-emerald-100 text-xs">Fallidas</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">
                <i class="fas fa-fire text-orange-500 mr-2"></i> Cursos Más Vendidos
            </h3>
            <div class="space-y-3">
                @forelse($topCourses as $topCourse)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 truncate pr-2 font-medium" title="{{ $topCourse->course->title ?? 'Curso' }}">
                            {{ Str::limit($topCourse->course->title ?? 'Curso no disponible', 25) }}
                        </span>
                        <span class="bg-blue-100 text-blue-800 py-1 px-2 rounded-md font-bold text-xs">
                            {{ $topCourse->sales_count }} ventas
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">Aún no hay cursos destacados.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">
                <i class="fas fa-users text-blue-500 mr-2"></i> Clientes Frecuentes
            </h3>
            <div class="space-y-3">
                @forelse($topBuyers as $topBuyer)
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center mr-2 text-xs font-bold text-gray-600">
                                {{ substr($topBuyer->buyer->names ?? 'C', 0, 1) }}
                            </div>
                            <span class="text-gray-600 font-medium">{{ $topBuyer->buyer->names ?? 'Usuario Anónimo' }}</span>
                        </div>
                        <span class="text-gray-800 font-bold text-xs">
                            {{ $topBuyer->purchases_count }} compras
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic">Aún no hay clientes recurrentes.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800">Todas las Transacciones</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-sm text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium">Fecha</th>
                        <th class="px-6 py-4 font-medium">Curso</th>
                        <th class="px-6 py-4 font-medium">Comprador</th>
                        <th class="px-6 py-4 font-medium text-right">Precio Real</th>
                        <th class="px-6 py-4 font-medium text-right text-emerald-600">Tu Comisión</th>
                        <th class="px-6 py-4 font-medium text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $sale->sold_at ? $sale->sold_at->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $sale->course->title ?? 'Curso Eliminado/Inactivo' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $sale->buyer->names ?? 'Cliente' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-gray-500 line-through">
                            S/ {{ number_format($sale->course->price ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-right text-emerald-600">
                            S/ {{ number_format($sale->commission_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($sale->status == 'completed')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Exitosa
                                </span>
                            @elseif($sale->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pendiente
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Fallida
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-50 flex items-center justify-center">
                                <i class="fas fa-receipt text-gray-300 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aún no hay transacciones registradas.</p>
                            <p class="text-sm text-gray-400 mt-1">Comparte tu código para empezar a generar ventas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sales->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection