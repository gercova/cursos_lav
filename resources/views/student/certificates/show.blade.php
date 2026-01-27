@extends('layouts.student')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Certificado de Finalización</h1>
            <div class="flex space-x-2">
                <a href="{{ route('student.certificates.download', $certificate->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Descargar PDF
                </a>
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir
                </button>
            </div>
        </div>

        <!-- Vista previa del certificado -->
        <div class="border-2 border-gray-300 p-8 bg-white" style="background: #ffffff; transform: scale(0.8); transform-origin: top center;">
            @include('student.certificates.pdf', ['certificate' => $certificate])
        </div>

        <!-- Información de verificación -->
        <div class="mt-8 p-6 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Información de Verificación</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Código del Certificado:</p>
                        <p class="font-mono bg-white p-2 rounded border">{{ $certificate->certificate_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Número de Certificado:</p>
                        <p class="font-mono bg-white p-2 rounded border">{{ $certificate->getFormattedCertificateNumber() }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Fecha de Emisión:</p>
                        <p class="bg-white p-2 rounded border">{{ $certificate->getFormattedIssueDate() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">URL de Verificación:</p>
                        <a href="{{ url('/verify/' . $certificate->certificate_code) }}"
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 hover:underline break-all block bg-white p-2 rounded border">
                           {{ url('/verify/' . $certificate->certificate_code) }}
                        </a>
                        <p class="text-xs text-gray-500 mt-1">Haz clic para verificar la autenticidad del certificado</p>
                    </div>
                </div>
            </div>

            <!-- Botón para copiar enlace -->
            <div class="mt-6">
                <button onclick="copyToClipboard('{{ url('/verify/' . $certificate->certificate_code) }}')" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copiar enlace de verificación
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Mostrar notificación más elegante
        const alert = document.createElement('div');
        alert.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
        alert.textContent = '✓ Enlace copiado al portapapeles';
        document.body.appendChild(alert);

        setTimeout(() => {
            document.body.removeChild(alert);
        }, 3000);
    }).catch(err => {
        console.error('Error al copiar:', err);
        alert('Error al copiar el enlace');
    });
}
</script>
@endsection
