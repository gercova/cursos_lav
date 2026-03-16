@extends('layouts.app')

@section('title', 'Pago Fallido - ' . $enterprise->trade_name)

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tarjeta de error -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header con animación de error -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-12 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="h-24 w-24 bg-white rounded-full flex items-center justify-center animate-pulse">
                        <svg class="h-14 w-14 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">¡Pago no completado!</h1>
                <p class="text-red-100 text-lg">Hubo un problema al procesar tu pago</p>
            </div>

            <!-- Contenido principal -->
            <div class="px-6 py-8">
                @if(isset($payment) && $payment)
                    <!-- Datos del intento de compra -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles del intento</h2>
                        
                        <div class="bg-red-50 rounded-xl p-6 space-y-4">
                            <!-- ID de transacción (si existe) -->
                            @if($payment->transaction_id)
                            <div class="flex justify-between items-center pb-4 border-b border-red-200">
                                <span class="text-gray-600">ID de transacción:</span>
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $payment->transaction_id }}</span>
                            </div>
                            @endif
                            
                            <!-- Método de pago -->
                            <div class="flex justify-between items-center pb-4 border-b border-red-200">
                                <span class="text-gray-600">Método de pago:</span>
                                <span class="font-medium text-gray-900 capitalize">{{ $payment->payment_method ?? 'No especificado' }}</span>
                            </div>
                            
                            <!-- Fecha del intento -->
                            <div class="flex justify-between items-center pb-4 border-b border-red-200">
                                <span class="text-gray-600">Fecha del intento:</span>
                                <span class="font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <!-- Monto intentado -->
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-semibold text-gray-900">Monto intentado:</span>
                                <span class="font-bold text-red-600">S/ {{ number_format($payment->amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Posibles causas del error -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Posibles causas del error</h2>
                        
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Fondos insuficientes</h3>
                                    <p class="text-sm text-gray-600">Verifica que tu tarjeta o cuenta tenga saldo disponible</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9V7a4 4 0 118 0v2m-6 0h4m-4 0a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2v-6a2 2 0 00-2-2h-1.5"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Datos incorrectos</h3>
                                    <p class="text-sm text-gray-600">Revisa que los datos de tu tarjeta sean correctos (número, fecha de expiración, CVV)</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Tiempo de espera excedido</h3>
                                    <p class="text-sm text-gray-600">La sesión de pago pudo haber expirado. Intenta nuevamente</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">Problemas de conexión</h3>
                                    <p class="text-sm text-gray-600">Verifica tu conexión a internet y vuelve a intentar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Mensaje genérico si no hay datos del pago -->
                    <div class="mb-8 text-center">
                        <svg class="w-20 h-20 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">No se pudo completar la operación</h2>
                        <p class="text-gray-600">El proceso de pago fue interrumpido o no se pudo completar correctamente.</p>
                    </div>
                @endif

                <!-- Mensaje de soporte -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-blue-800">¿Necesitas ayuda?</h3>
                            <p class="text-sm text-blue-700 mt-1">
                                Si el problema persiste, contáctanos a 
                                <a href="mailto:{{ $enterprise->email }}" class="font-medium underline hover:text-blue-800">
                                    {{ $enterprise->email }}
                                </a> 
                                o al teléfono 
                                <a href="tel:+51{{ $enterprise->phone_number_1 }}" class="font-medium underline hover:text-blue-800">
                                    +51 {{ $enterprise->phone_number_1 }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ url()->previous() }}" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        <span>Reintentar pago</span>
                    </a>
                    
                    <a href="{{ route('cart') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Ver carrito</span>
                    </a>
                    
                    <a href="{{ route('contacto') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>Contactar soporte</span>
                    </a>
                </div>

                <!-- Nota adicional -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        No se ha realizado ningún cargo a tu tarjeta o cuenta.
                        <br>Si ves algún movimiento no reconocido, contáctanos inmediatamente.
                    </p>
                </div>
            </div>
        </div>

        <!-- Métodos de pago alternativos -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-4">Puedes intentar con otros métodos de pago</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M0 4.5v15h24v-15H0zm21.5 12h-19v-9h19v9zM2 7h2v2H2V7zm4 0h2v2H6V7zm4 0h2v2h-2V7zm4 0h2v2h-2V7zm4 0h2v2h-2V7zM2 11h2v2H2v-2zm4 0h2v2H6v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2zM2 15h2v2H2v-2zm4 0h2v2H6v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                    </svg>
                    <p class="text-xs text-gray-600">Tarjeta de crédito</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6-2.28 0-2.56 3.5-4.72 6-4.72s6 2.16 6 4.72c-1.57 1.46-3.97 2.28-6 2.28z"/>
                    </svg>
                    <p class="text-xs text-gray-600">Yape/Plin</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.5 4h-19C1.12 4 0 5.12 0 6.5v11C0 18.88 1.12 20 2.5 20h19c1.38 0 2.5-1.12 2.5-2.5v-11C24 5.12 22.88 4 21.5 4zM8 15H6V9h2v6zm10 0h-2v-3h-2v3h-2V9h2v3h2V9h2v6z"/>
                    </svg>
                    <p class="text-xs text-gray-600">Transferencia bancaria</p>
                </div>
                <div class="bg-white rounded-lg p-4 text-center shadow-sm hover:shadow-md transition-shadow duration-200">
                    <svg class="w-8 h-8 text-blue-600 mx-auto mb-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/>
                    </svg>
                    <p class="text-xs text-gray-600">Efectivo (agentes)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Registrar el evento de pago fallido para analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'purchase_error', {
                'event_category': 'ecommerce',
                'event_label': 'payment_failure',
                'value': {{ $payment->amount ?? 0 }}
            });
        }

        // Mostrar mensaje de ayuda después de 30 segundos si el usuario sigue en la página
        setTimeout(function() {
            const helpMessage = document.createElement('div');
            helpMessage.className = 'fixed bottom-4 right-4 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-bounce cursor-pointer hover:bg-blue-700 transition-colors duration-200';
            helpMessage.innerHTML = '¿Necesitas ayuda? <span class="font-bold">Contáctanos</span>';
            helpMessage.onclick = function() {
                window.location.href = "{{ route('contacto') }}";
            };
            document.body.appendChild(helpMessage);
            
            // Auto-remover después de 10 segundos
            setTimeout(function() {
                helpMessage.remove();
            }, 10000);
        }, 30000);
    });
</script>
@endsection