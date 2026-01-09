@extends('layouts.student')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Certificado de Finalización</h1>
            <div class="flex space-x-2">
                <a href="{{ route('student.certificates.download', $certificate->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Descargar PDF
                </a>
                <button onclick="window.print()"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir
                </button>
            </div>
        </div>

        <!-- Vista previa del certificado -->
        <div class="border-2 border-gray-300 p-8 bg-white">
            @include('student.certificate.pdf-content', ['certificate' => $certificate])
        </div>

        <!-- Información de verificación -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold mb-2">Información de Verificación</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Código del Certificado:</p>
                    <p class="font-mono">{{ $certificate->certificate_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Número de Certificado:</p>
                    <p class="font-mono">{{ $certificate->getFormattedCertificateNumber() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Fecha de Emisión:</p>
                    <p>{{ $certificate->issue_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">URL de Verificación:</p>
                    <a href="{{ $certificate->verification_url }}"
                       class="text-blue-600 hover:underline break-all">
                        {{ $certificate->verification_url }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
