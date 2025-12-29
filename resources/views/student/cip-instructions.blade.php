@extends('layouts.app')
@section('title', 'Instrucciones de Pago - PagoEfectivo')
@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-qrcode text-green-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Instrucciones de Pago</h1>
            <p class="text-gray-600 mt-2">Realiza el pago utilizando PagoEfectivo</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Información del CIP -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Detalles del Pago</h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm text-blue-700">Código CIP</p>
                            <p class="text-2xl font-bold text-blue-800 font-mono" id="cip-code">
                                {{ $cipData['cip_code'] ?? '---' }}
                            </p>
                        </div>
                        <button onclick="copyCIPCode()"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-copy mr-2"></i>
                            Copiar
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Monto a pagar:</span>
                            <span class="font-bold text-gray-900">S/ {{ number_format($payment->amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Fecha límite:</span>
                            <span class="font-bold text-red-600">
                                {{ \Carbon\Carbon::parse($cipData['expires_at'])->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Estado:</span>
                            <span class="font-bold" id="cip-status">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">
                                    Pendiente
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- QR Code (opcional) -->
                <div class="mt-6 text-center">
                    <div class="inline-block p-4 bg-white border border-gray-300 rounded-lg">
                        <div id="qrcode" class="w-48 h-48 mx-auto"></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Escanea el código para pagar</p>
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="space-y-6">
                <!-- Pasos para pagar -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">¿Cómo pagar?</h2>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="font-bold text-blue-600">1</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Agentes autorizados</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Acércate a cualquier agente autorizado de PagoEfectivo (Agentes BCP, Banco de la Nación, etc.)
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="font-bold text-blue-600">2</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Entrega el código CIP</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Proporciona el código CIP <strong class="font-mono">{{ $cipData['cip_code'] ?? '---' }}</strong> al agente
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="font-bold text-blue-600">3</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Realiza el pago</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Paga el monto exacto de <strong>S/ {{ number_format($payment->amount, 2) }}</strong> en efectivo
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="font-bold text-blue-600">4</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Espera confirmación</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Los cursos se activarán automáticamente en tu cuenta (puede demorar hasta 15 minutos)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bancos y agentes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Puedes pagar en:</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="font-bold text-red-600">BCP</span>
                            </div>
                            <span class="text-sm font-medium">Agentes BCP</span>
                        </div>

                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="font-bold text-blue-600">BN</span>
                            </div>
                            <span class="text-sm font-medium">Banco Nación</span>
                        </div>

                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="font-bold text-green-600">BBVA</span>
                            </div>
                            <span class="text-sm font-medium">Agentes BBVA</span>
                        </div>

                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="font-bold text-purple-600">INT</span>
                            </div>
                            <span class="text-sm font-medium">Banca por Internet</span>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="space-y-3">
                        <a href="{{ $cipData['cip_url'] ?? '#' }}" target="_blank"
                           class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Pagar en línea
                        </a>

                        <button onclick="checkPaymentStatus()"
                                class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Verificar estado del pago
                        </button>

                        <a href="{{ route('student.dashboard') }}"
                           class="w-full border border-blue-300 hover:bg-blue-50 text-blue-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i>
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de estado -->
<div id="status-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div id="status-content">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
// Generar QR Code
document.addEventListener('DOMContentLoaded', function() {
    const cipCode = '{{ $cipData["cip_code"] }}';
    const amount = '{{ $payment->amount }}';

    // Texto para el QR (formato para PagoEfectivo)
    const qrText = `PAGOEFECTIVO|${cipCode}|${amount}|PEN`;

    // Generar QR
    QRCode.toCanvas(document.getElementById('qrcode'), qrText, {
        width: 192,
        height: 192,
        margin: 1,
        color: {
            dark: '#000000',
            light: '#ffffff'
        }
    });
});

// Copiar código CIP
function copyCIPCode() {
    const cipCode = document.getElementById('cip-code').textContent.trim();

    navigator.clipboard.writeText(cipCode).then(() => {
        // Mostrar notificación
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slide-in-right';
        notification.textContent = '✓ Código copiado al portapapeles';

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('animate-fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    });
}

// Verificar estado del pago
async function checkPaymentStatus() {
    const modal = document.getElementById('status-modal');
    const content = document.getElementById('status-content');

    // Mostrar loading
    content.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Verificando estado del pago...</p>
        </div>
    `;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    try {
        const response = await axios.get('/payment/cip-status/{{ $payment->id }}');
        const data = response.data;

        let statusHtml = '';
        let statusColor = '';

        if (data.status === 'paid' || data.payment_status === 'completed') {
            statusHtml = `
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">¡Pago confirmado!</h3>
                    <p class="text-gray-600 mb-6">Tu pago ha sido procesado exitosamente.</p>
                    <a href="{{ route('student.my-courses') }}"
                       class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                        Ir a mis cursos
                    </a>
                </div>
            `;
        } else if (data.status === 'expired') {
            statusHtml = `
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">CIP Expirado</h3>
                    <p class="text-gray-600 mb-6">El código CIP ha expirado. Debes generar un nuevo pago.</p>
                    <a href="{{ route('cart.checkout') }}"
                       class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                        Generar nuevo pago
                    </a>
                </div>
            `;
        } else {
            statusHtml = `
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pago Pendiente</h3>
                    <p class="text-gray-600 mb-6">Aún no se ha registrado el pago. Por favor, realiza el pago en los agentes autorizados.</p>
                    <button onclick="closeModal()"
                            class="inline-block px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-semibold transition-colors duration-200">
                        Cerrar
                    </button>
                </div>
            `;
        }

        content.innerHTML = statusHtml;

    } catch (error) {
        console.error('Error checking payment status:', error);
        content.innerHTML = `
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Error</h3>
                <p class="text-gray-600 mb-6">No se pudo verificar el estado del pago. Por favor, intenta nuevamente.</p>
                <button onclick="closeModal()"
                        class="inline-block px-6 py-3 border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-semibold transition-colors duration-200">
                    Cerrar
                </button>
            </div>
        `;
    }
}

function closeModal() {
    const modal = document.getElementById('status-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Verificar automáticamente cada 30 segundos
setInterval(() => {
    checkPaymentStatus();
}, 30000);

// Animaciones
const animations = `
    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out forwards;
    }

    .animate-fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = animations;
document.head.appendChild(styleSheet);
</script>
@endsection
