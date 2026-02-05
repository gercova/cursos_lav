@extends('layouts.student')

@section('title', 'Certificado - ' . $certificate->course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado con información del certificado -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white mb-2">Certificado de Finalización</h1>
                    <p class="text-blue-100">
                        <span class="font-semibold">Curso:</span> {{ $certificate->course->title }}
                    </p>
                    <p class="text-blue-100">
                        <span class="font-semibold">Emitido:</span> {{ $certificate->getFormattedIssueDate() }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="bg-blue-500 text-white px-4 py-2 rounded-lg text-center">
                        <div class="text-sm">Estado</div>
                        <div class="text-lg font-bold">
                            @if($certificate->expiry_date && $certificate->expiry_date->isPast())
                                <span class="text-yellow-300">Expirado</span>
                            @else
                                <span class="text-green-300">Vigente</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de acciones principales -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Descargar Certificado</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Modelo Exacto (Nuevo) -->
            <div class="border border-gray-200 rounded-lg p-5 hover:border-blue-300 hover:shadow-md transition-all">
                <div class="text-center mb-4">
                    <div class="bg-blue-100 text-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-1">Certificado del curso {{ $certificate->course->title }} </h3>
                    <p class="text-sm text-gray-600">Descarga o visualiza tu certificado</p>
                </div>
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('student.certificates.download-exact', $certificate->id) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar PDF
                    </a>
                    <a href="{{ route('student.certificates.view-exact', $certificate->id) }}" 
                       target="_blank"
                       class="bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-3 rounded-lg flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver en Navegador
                    </a>
                </div>
            </div>

            <!-- Información del Certificado -->
            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-all">
                <div class="text-center mb-4">
                    <div class="bg-purple-100 text-purple-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-1">Información</h3>
                    <p class="text-sm text-gray-600">Detalles de validación</p>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        <span class="text-gray-600">Código:</span>
                        <span class="font-mono text-gray-800 ml-auto">{{ $certificate->certificate_code }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-gray-600">Número:</span>
                        <span class="font-mono text-gray-800 ml-auto">{{ $certificate->getFormattedCertificateNumber() }}</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Duración:</span>
                        <span class="font-semibold text-gray-800 ml-auto">{{ round($certificate->total_hours, 1) }} horas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de verificación -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Verificar Certificado</h2>
                <p class="text-gray-600">Comparte este enlace para validar la autenticidad del certificado</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button onclick="copyVerificationLink()" 
                        class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-3 rounded-lg flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copiar Enlace de Verificación
                </button>
            </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Enlace de Verificación Oficial</h4>
                    <div class="bg-white border border-gray-300 rounded-lg p-4">
                        <a href="{{ url('/verify/' . $certificate->certificate_code) }}" 
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 hover:underline break-all block font-mono text-sm md:text-base">
                           {{ url('/verify/' . $certificate->certificate_code) }}
                        </a>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Este enlace verifica oficialmente la autenticidad del certificado en el sistema IPF Educa.
                    </p>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-5">
                <h4 class="font-bold text-blue-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Instrucciones de Verificación
                </h4>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-blue-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Copia y pega el enlace en cualquier navegador web</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-blue-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>El sistema mostrará todos los detalles del certificado</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-blue-500 mt-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Valida la información con los datos del participante</span>
                    </li>
                </ul>
            </div>

            <div class="bg-green-50 border border-green-100 rounded-lg p-5">
                <h4 class="font-bold text-green-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Estadísticas
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Descargas realizadas:</span>
                        <span class="font-bold text-gray-800">{{ $certificate->download_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Fecha de emisión:</span>
                        <span class="font-semibold text-gray-800">{{ $certificate->getFormattedIssueDate() }}</span>
                    </div>
                    @if($certificate->expiry_date)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Válido hasta:</span>
                        <span class="font-semibold {{ $certificate->expiry_date->isPast() ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $certificate->expiry_date->format('d/m/Y') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de regreso -->
    <div class="mt-8 text-center">
        <a href="{{ route('student.certificates') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a mis certificados
        </a>
    </div>
</div>

<!-- Script para copiar enlace -->
<script>
function copyVerificationLink() {
    const link = "{{ url('/verify/' . $certificate->certificate_code) }}";
    
    navigator.clipboard.writeText(link).then(() => {
        // Crear notificación personalizada
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center animate-fade-in z-50';
        notification.innerHTML = `
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <div>
                <p class="font-bold">✓ Enlace copiado</p>
                <p class="text-sm opacity-90">El enlace de verificación está en el portapapeles</p>
            </div>
        `;
        
        // Agregar animación CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.3s ease-out;
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(notification);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            notification.style.transition = 'all 0.3s ease-out';
            
            setTimeout(() => {
                document.body.removeChild(notification);
                document.head.removeChild(style);
            }, 300);
        }, 3000);
    }).catch(err => {
        console.error('Error al copiar:', err);
        alert('Error al copiar el enlace. Por favor, cópialo manualmente.');
    });
}
</script>

<!-- Estilos adicionales -->
<style>
    .certificate-card {
        transition: all 0.3s ease;
    }
    
    .certificate-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .btn-action {
        transition: all 0.2s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
    }
</style>
@endsection