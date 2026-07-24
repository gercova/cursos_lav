@extends('layouts.student')
@section('title', 'Enlaces de Afiliado')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Enlaces de Afiliado por Curso</h1>
        <p class="text-gray-600 mt-2">Enlaces personalizados para cada curso con tu código</p>
    </div>

    <!-- Enlace General -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-sm border border-blue-100 p-6 mb-8">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    <i class="bi bi-link-45deg mr-2 text-blue-600"></i>
                    Enlace General de Afiliado
                </h3>
                <p class="text-gray-600 mb-4">Este enlace funciona para todos los cursos con descuento</p>
                <div class="flex items-center space-x-4">
                    <div class="bg-white rounded-lg border border-gray-200 px-4 py-3 flex-1">
                        <code id="general-affiliate-url" class="text-sm text-gray-800 break-all">
                            {{ $user->affiliate_url }}
                        </code>
                    </div>
                    <button onclick="copyGeneralLink()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200">
                        <i class="bi bi-copy mr-2"></i>
                        Copiar
                    </button>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="bi bi-share-fill mr-1"></i>
                        Comparte en redes sociales
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="bi bi-qr-code mr-1"></i>
                        Genera código QR
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces por Curso -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Enlaces por Curso</h2>
        <p class="text-gray-600">Cada curso tiene su propio enlace con descuento específico</p>
    </div>

    @if(count($affiliateLinks) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($affiliateLinks as $link)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                        <i class="bi bi-book text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $link['course']->title }}</h3>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                <i class="bi bi-tag mr-1"></i>
                                {{ $link['discount_percentage'] }}% de descuento
                            </span>
                            <span class="text-sm text-gray-600">
                                Código: <code class="font-mono">{{ $link['promo_code'] }}</code>
                            </span>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enlace del curso:</label>
                            <div class="flex items-center space-x-2">
                                <input type="text" readonly value="{{ $link['link'] }}" 
                                    class="flex-1 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    id="link-{{ $loop->index }}">
                                <button onclick="copyCourseLink({{ $loop->index }})" 
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="bi bi-copy"></i>
                                </button>
                                <a href="{{ $link['link'] }}" target="_blank" 
                                    class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Botones para compartir -->
                        <div class="mt-4 flex space-x-2">
                            <button onclick="shareOnFacebook('{{ $link['link'] }}', '{{ $link['course']->title }}')"
                                class="flex-1 bg-[#1877F2] hover:bg-[#166FE5] text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fab fa-facebook-f mr-2"></i> Facebook
                            </button>
                            <button onclick="shareOnWhatsApp('{{ $link['link'] }}', '{{ $link['course']->title }}')"
                                class="flex-1 bg-[#25D366] hover:bg-[#1DA851] text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">
                        Precio: <span class="font-semibold text-gray-900">S/ {{ number_format($link['course']->final_price, 2) }}</span>
                    </span>
                    <span class="text-emerald-600 font-semibold">
                        Tu comisión: S/ {{ number_format($link['course']->final_price * 0.1, 2) }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
            <i class="bi bi-link text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">No tienes cursos promocionales activos</h3>
        <p class="text-gray-600 mb-6">Contacta con administración para activar códigos promocionales en cursos</p>
        <a href="{{ route('cursos') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <i class="bi bi-box-arrow-up-right mr-2"></i>
            Explorar cursos disponibles
        </a>
    </div>
    @endif

    <!-- Widgets para compartir -->
    <div class="mt-12 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Herramientas de Marketing</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                        <i class="bi bi-qr-code text-blue-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Código QR</h4>
                </div>
                <p class="text-sm text-gray-600 mb-3">Genera un código QR para tu enlace de afiliado</p>
                <button onclick="generateQRCode()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium">
                    Generar QR
                </button>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                        <i class="bi bi-bar-chart text-green-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Widget de Seguimiento</h4>
                </div>
                <p class="text-sm text-gray-600 mb-3">Código HTML para insertar en tu sitio web</p>
                <button onclick="showTrackingWidget()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium">
                    Obtener Widget
                </button>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                        <i class="bi bi-file-earmark-text text-purple-600"></i>
                    </div>
                    <h4 class="font-medium text-gray-900">Material Promocional</h4>
                </div>
                <p class="text-sm text-gray-600 mb-3">Banners e imágenes para promocionar</p>
                <button onclick="downloadPromoMaterial()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium">
                    Descargar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function copyGeneralLink() {
        const url = document.getElementById('general-affiliate-url').textContent;
        navigator.clipboard.writeText(url);
        showToast('Enlace copiado al portapapeles');
    }

    function copyCourseLink(index) {
        const input = document.getElementById('link-' + index);
        input.select();
        document.execCommand('copy');
        showToast('Enlace del curso copiado');
    }

    function shareOnFacebook(url, title) {
        const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(title)}`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }

    function shareOnWhatsApp(url, title) {
        const text = `¡Mira este curso! ${title} - ${url}`;
        const shareUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
        window.open(shareUrl, '_blank');
    }

    function generateQRCode() {
        alert('Funcionalidad de código QR en desarrollo');
    }

    function showTrackingWidget() {
        const widgetCode = `<div class="affiliate-widget">
    <!-- Widget de seguimiento -->
    </div>`;
        
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Código del Widget</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <textarea class="w-full h-40 border-gray-300 rounded-lg p-3 text-sm font-mono" readonly>${widgetCode}</textarea>
                <div class="mt-4 flex justify-end">
                    <button onclick="copyWidgetCode()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Copiar Código
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function downloadPromoMaterial() {
        alert('Funcionalidad de material promocional en desarrollo');
    }

    function showToast(message) {
        // Implementar toast de notificación
        alert(message);
    }
</script>
@endsection