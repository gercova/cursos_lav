@extends('layouts.student')

@section('title', 'Certificado - ' . $certificate->course->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb / Volver -->
    <div class="mb-6">
        <a href="{{ route('student.certificates') }}" 
           class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a mis certificados
        </a>
    </div>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-700 via-blue-800 to-indigo-900 rounded-2xl shadow-xl overflow-hidden mb-8 text-white">
        <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <span class="bg-blue-500/30 text-blue-200 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider">
                    Certificación Oficial
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold mt-3 mb-2 tracking-tight">
                    {{ $certificate->course->title }}
                </h1>
                <p class="text-blue-100/90 text-sm md:text-base flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Emitido el {{ $certificate->getFormattedIssueDate() }}</span>
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/10 text-center min-w-[120px]">
                    <div class="text-xs text-blue-200 uppercase font-semibold">Estado</div>
                    <div class="text-lg font-bold mt-1">
                        @if($certificate->expiry_date && $certificate->expiry_date->isPast())
                            <span class="text-yellow-400">Expirado</span>
                        @else
                            <span class="text-emerald-400">Vigente</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Live Preview (Spans 2 columns on large screens) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Vista Previa del Certificado
                    </h2>
                    <span class="text-xs text-gray-500 font-medium">Formato Digital Oficial</span>
                </div>
                <div class="p-6 bg-gray-100 flex items-center justify-center">
                    <div class="w-full aspect-[1.414] rounded-lg shadow-lg overflow-hidden bg-white relative border border-gray-300">
                        <iframe src="{{ route('student.certificates.view-exact', $certificate->id) }}" 
                                class="absolute inset-0 w-full h-full border-0" 
                                allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            
            <!-- Instructions and details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Instrucciones de Uso
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Puedes descargar tu certificado en formato PDF de alta resolución listo para imprimir.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>El código QR permite a reclutadores y empresas validar la autenticidad al instante.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Puedes adjuntar este enlace en tu perfil de LinkedIn o CV digital.</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Estadísticas de Acceso
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Descargas del PDF:</span>
                            <span class="font-bold text-gray-800">{{ $certificate->download_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Horas de capacitación:</span>
                            <span class="font-bold text-gray-800">{{ round($certificate->total_hours, 1) }} horas lectivas</span>
                        </div>
                        @if($certificate->expiry_date)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Expiración del certificado:</span>
                            <span class="font-bold {{ $certificate->expiry_date->isPast() ? 'text-rose-600' : 'text-gray-800' }}">
                                {{ $certificate->expiry_date->format('d/m/Y') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Actions and Metadata Details -->
        <div class="space-y-6">
            
            <!-- Actions Card -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-5">Acciones del Certificado</h3>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('student.certificates.download-exact', $certificate->id) }}" 
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Descargar PDF Oficial
                    </a>
                    
                    <a href="{{ route('student.certificates.view-exact', $certificate->id) }}" 
                       target="_blank"
                       class="w-full bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-semibold py-3 px-4 rounded-xl flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5 duration-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Ver Pantalla Completa
                    </a>
                </div>
            </div>

            <!-- Metadata Details Card -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Información de Registro</h3>
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Código de Certificado</span>
                        <div class="font-mono text-sm font-bold text-gray-700 mt-1 select-all">
                            {{ $certificate->certificate_code }}
                        </div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Número de Registro</span>
                        <div class="font-mono text-sm font-bold text-gray-700 mt-1">
                            {{ $certificate->getFormattedCertificateNumber() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Card -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Verificación Rápida QR</h3>
                <div class="flex flex-col items-center text-center">
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-2xl mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('verify.certificate', $certificate->certificate_code)) }}" 
                             alt="Código QR de verificación"
                             class="w-36 h-36"
                             loading="lazy">
                    </div>
                    <p class="text-xs text-gray-500 mb-4 px-2">
                        Escanea el código QR con tu móvil para verificar la autenticidad al instante.
                    </p>
                    
                    <button onclick="copyVerificationLink()" 
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors text-sm">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copiar Enlace de Validación
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Script para copiar enlace -->
<script>
function copyVerificationLink() {
    const link = "{{ route('verify.certificate', $certificate->certificate_code) }}";
    
    navigator.clipboard.writeText(link).then(() => {
        // Crear notificación
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center animate-fade-in z-50';
        notification.innerHTML = `
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <div>
                <p class="font-bold text-sm">✓ Enlace copiado</p>
                <p class="text-xs opacity-90">Listo para compartir o incluir en tu CV</p>
            </div>
        `;
        
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
@endsection