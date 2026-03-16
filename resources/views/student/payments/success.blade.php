@extends('layouts.app')

@section('title', 'Pago Exitoso - ' . $enterprise->trade_name)

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tarjeta de éxito -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header con animación de éxito -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-12 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="h-24 w-24 bg-white rounded-full flex items-center justify-center animate-bounce">
                        <svg class="h-14 w-14 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">¡Pago Exitoso!</h1>
                <p class="text-green-100 text-lg">Tu compra se ha realizado correctamente</p>
            </div>

            <!-- Contenido principal -->
            <div class="px-6 py-8">
                @if(isset($payment))
                    <!-- Datos de la compra -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles de la compra</h2>
                        
                        <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                            <!-- ID de transacción -->
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">ID de transacción:</span>
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $payment->transaction_id }}</span>
                            </div>
                            
                            <!-- Método de pago -->
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Método de pago:</span>
                                <span class="font-medium text-gray-900 capitalize">{{ $payment->payment_method }}</span>
                            </div>
                            
                            <!-- Fecha -->
                            <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                                <span class="text-gray-600">Fecha:</span>
                                <span class="font-medium text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <!-- Total pagado -->
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-semibold text-gray-900">Total pagado:</span>
                                <span class="font-bold text-green-600">S/ {{ number_format($payment->amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Items comprados -->
                    @if($payment->items && count($payment->items) > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Productos adquiridos</h2>
                        
                        <div class="space-y-3">
                            @foreach($payment->items as $item)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        @if($item->type === 'course')
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $item->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $item->type === 'course' ? 'Curso' : 'Paquete' }}</p>
                                    </div>
                                </div>
                                <span class="font-medium text-gray-900">S/ {{ number_format($item->price, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('student.my-courses') }}" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Ir a Mis Cursos</span>
                    </a>
                    
                    <a href="{{ route('cursos') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        <span>Seguir explorando</span>
                    </a>
                </div>

                <!-- Mensaje de confirmación adicional -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        Hemos enviado los detalles de tu compra a tu correo electrónico.
                        <br>Si tienes alguna duda, contáctanos a <a href="mailto:{{ $enterprise->email }}" class="text-blue-600 hover:underline">{{ $enterprise->email }}</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Sellos de confianza -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <p class="text-xs text-gray-600">Pago 100% seguro</p>
            </div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-xs text-gray-600">Acceso inmediato</p>
            </div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-xs text-gray-600">Certificado incluido</p>
            </div>
            <div class="bg-white rounded-lg p-4 text-center shadow-sm">
                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <p class="text-xs text-gray-600">Soporte 24/7</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Animación de confeti (opcional)
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof confetti !== 'undefined') {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        }
    });
</script>
@endsection