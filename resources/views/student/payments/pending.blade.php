@extends('layouts.app')

@section('title', 'Pago Pendiente - ' . $enterprise->trade_name)

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Tarjeta de pago pendiente -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header con animación de espera -->
            <div class="bg-gradient-to-r from-yellow-500 to-amber-600 px-6 py-12 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="h-24 w-24 bg-white rounded-full flex items-center justify-center">
                        <div class="relative">
                            <!-- Icono de reloj con animación -->
                            <svg class="h-14 w-14 text-yellow-500 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <!-- Puntos suspensivos animados -->
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1">
                                <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">¡Pago en proceso!</h1>
                <p class="text-yellow-100 text-lg">Estamos verificando tu pago</p>
            </div>

            <!-- Contenido principal -->
            <div class="px-6 py-8">
                @if(isset($payment) && $payment)
                    <!-- Datos del pago pendiente -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles del pago</h2>
                        
                        <div class="bg-yellow-50 rounded-xl p-6 space-y-4">
                            <!-- Estado del pago -->
                            <div class="flex justify-between items-center pb-4 border-b border-yellow-200">
                                <span class="text-gray-600">Estado:</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Pendiente de confirmación
                                </span>
                            </div>
                            
                            <!-- ID de transacción -->
                            @if($payment->transaction_id)
                            <div class="flex justify-between items-center pb-4 border-b border-yellow-200">
                                <span class="text-gray-600">ID de transacción:</span>
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $payment->transaction_id }}</span>
                            </div>
                            @endif
                            
                            <!-- Método de pago -->
                            <div class="flex justify-between items-center pb-4 border-b border-yellow-200">
                                <span class="text-gray-600">Método de pago:</span>
                                <span class="font-medium text-gray-900 capitalize">{{ $payment->payment_method ?? 'No especificado' }}</span>
                            </div>
                            
                            <!-- Fecha y hora -->
                            <div class="flex justify-between items-center pb-4 border-b border-yellow-200">
                                <span class="text-gray-600">Fecha del pago:</span>
                                <span class="font-medium text-gray-900">{{ $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <!-- Monto -->
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-semibold text-gray-900">Monto:</span>
                                <span class="font-bold text-yellow-600">S/ {{ number_format($payment->amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Items pendientes -->
                    @if($payment->items && count($payment->items) > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Productos pendientes</h2>
                        
                        <div class="space-y-3">
                            @foreach($payment->items as $item)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-yellow-100 p-2 rounded-lg">
                                        @if($item->type === 'course')
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                @else
                    <!-- Mensaje genérico si no hay datos -->
                    <div class="mb-8 text-center">
                        <svg class="w-20 h-20 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Pago en verificación</h2>
                        <p class="text-gray-600">Estamos procesando tu pago. Esto puede tomar algunos minutos.</p>
                    </div>
                @endif

                <!-- Instrucciones según método de pago -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Próximos pasos</h2>
                    
                    <div class="space-y-4">
                        <!-- Para transferencia bancaria -->
                        @if(isset($payment) && $payment->payment_method == 'transferencia')
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-blue-800">Transferencia bancaria detectada</h3>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Hemos recibido tu comprobante de transferencia. 
                                        <strong>La activación puede tomar hasta 24 horas hábiles</strong> mientras verificamos el pago.
                                        Te notificaremos por email cuando esté confirmado.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Para Yape/Plin -->
                        @if(isset($payment) && $payment->payment_method == 'yape')
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a4 4 0 004-4V7a4 4 0 00-4-4H8a4 4 0 00-4 4v10a4 4 0 004 4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-green-800">Pago con Yape/Plin</h3>
                                    <p class="text-sm text-green-700 mt-1">
                                        Tu pago está siendo procesado. Por lo general, la confirmación es inmediata.
                                        Si no ves cambios en unos minutos, verifica que hayas ingresado correctamente el número de operación.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Para tarjeta de crédito/débito -->
                        @if(isset($payment) && $payment->payment_method == 'tarjeta')
                        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-purple-800">Pago con tarjeta</h3>
                                    <p class="text-sm text-purple-700 mt-1">
                                        Tu banco está procesando la transacción. Esto puede tomar hasta 5 minutos.
                                        No cierres esta ventana hasta recibir la confirmación.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Mensaje genérico para cualquier método -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">¿Qué sigue?</h3>
                                    <ul class="text-sm text-gray-600 mt-1 space-y-1 list-disc list-inside">
                                        <li>Recibirás un email de confirmación cuando el pago sea verificado</li>
                                        <li>Los cursos se activarán automáticamente en tu cuenta</li>
                                        <li>Puedes hacer seguimiento de tus compras en "Mis Cursos"</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button id="check-status-btn" 
                            onclick="checkPaymentStatus()"
                            class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-[1.02] shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Verificar estado</span>
                    </button>
                    
                    <a href="{{ route('student.my-courses') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Ir a Mis Cursos</span>
                    </a>
                    
                    <a href="{{ route('contacto') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span>Contactar soporte</span>
                    </a>
                </div>

                <!-- Tiempo estimado -->
                <div class="mt-6 flex items-center justify-center space-x-2 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Tiempo estimado de confirmación:</span>
                    <span class="font-medium text-gray-700">
                        @if(isset($payment) && $payment->payment_method == 'transferencia')
                            Hasta 24 horas
                        @else
                            5-10 minutos
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Preguntas frecuentes -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-4">Preguntas frecuentes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-2">¿Cuánto tarda la confirmación?</h4>
                    <p class="text-sm text-gray-600">Depende del método de pago. Transferencias pueden tomar hasta 24h hábiles, otros métodos suelen ser inmediatos.</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-2">¿Qué hago si no llega la confirmación?</h4>
                    <p class="text-sm text-gray-600">Verifica tu correo (incluyendo spam) y contacta a soporte si pasó el tiempo estimado.</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-2">¿Puedo cancelar el pago?</h4>
                    <p class="text-sm text-gray-600">Una vez procesado, no es posible cancelar. Si tienes problemas, contáctanos.</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <h4 class="font-medium text-gray-900 mb-2">¿Recibiré mi certificado?</h4>
                    <p class="text-sm text-gray-600">Sí, una vez confirmado el pago y completado el curso, podrás descargar tu certificado.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let checkInterval;
    let attempts = 0;
    const maxAttempts = 12; // 1 minuto (12 * 5 segundos)

    function checkPaymentStatus() {
        const button = document.getElementById('check-status-btn');
        const originalText = button.innerHTML;
        
        // Deshabilitar botón
        button.disabled = true;
        button.innerHTML = `
            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>Verificando...</span>
        `;
        
        // Simular verificación (aquí iría la llamada AJAX real)
        setTimeout(() => {
            @if(isset($payment) && $payment->id)
            axios.get('/api/payment/status/{{ $payment->id }}')
                .then(response => {
                    if (response.data.status === 'approved') {
                        window.location.href = "{{ route('payment.success', $payment->id) }}";
                    } else if (response.data.status === 'rejected') {
                        window.location.href = "{{ route('payment.failure', $payment->id) }}";
                    } else {
                        // Mostrar notificación de que sigue pendiente
                        showNotification('El pago aún está pendiente de confirmación', 'info');
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error verificando estado:', error);
                    showNotification('Error al verificar el estado. Intenta nuevamente.', 'error');
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            @else
            // Simulación para demo
            setTimeout(() => {
                showNotification('El pago sigue en proceso de verificación', 'info');
                button.disabled = false;
                button.innerHTML = originalText;
            }, 2000);
            @endif
        }, 500);
    }

    // Auto-verificación periódica
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($payment) && $payment->id)
        checkInterval = setInterval(function() {
            attempts++;
            if (attempts <= maxAttempts) {
                axios.get('/api/payment/status/{{ $payment->id }}')
                    .then(response => {
                        if (response.data.status === 'approved') {
                            clearInterval(checkInterval);
                            window.location.href = "{{ route('payment.success', $payment->id) }}";
                        } else if (response.data.status === 'rejected') {
                            clearInterval(checkInterval);
                            window.location.href = "{{ route('payment.failure', $payment->id) }}";
                        }
                    })
                    .catch(error => console.error('Error auto-verificando:', error));
            } else {
                clearInterval(checkInterval);
            }
        }, 5000); // Cada 5 segundos
        @endif
    });

    // Limpiar intervalo al salir
    window.addEventListener('beforeunload', function() {
        if (checkInterval) {
            clearInterval(checkInterval);
        }
    });

    // Función para mostrar notificaciones
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const bgColor = type === 'info' ? 'bg-blue-600' : 'bg-red-600';
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-500 transform translate-x-full`;
        notification.innerHTML = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }
</script>

<!-- Estilos adicionales -->
<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 3s linear infinite;
    }
</style>
@endsection