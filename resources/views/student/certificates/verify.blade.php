<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path }}">
    <title>Verificar Certificado - IPF CONSULTORES SAC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verificación de Certificado</h1>
            <p class="text-gray-600">Sistema de verificación oficial de IPF CONSULTORES SAC</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Encabezado con estado -->
            <div class="px-8 py-6 {{ $valid ? 'bg-green-500' : 'bg-red-500' }} text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center mr-4">
                            <i class="fas {{ $valid ? 'fa-solid fa-check' : 'fa-solid fa-xmark' }} text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">
                                {{ $valid ? 'CERTIFICADO VÁLIDO' : 'CERTIFICADO NO VÁLIDO' }}
                            </h2>
                            <p class="text-sm opacity-90">{{ $valid ? 'Verificación exitosa' : $message }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm">Fecha de verificación</div>
                        <div class="font-bold">{{ date('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>

            @if($valid && isset($certificate))
            <!-- Información del certificado válido -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Información del Certificado</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Número de Certificado:</label>
                            <p class="font-mono text-lg font-bold text-gray-900">{{ $certificate->getFormattedCertificateNumber() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Código de Verificación:</label>
                            <p class="font-mono bg-gray-100 p-2 rounded">{{ $certificate->certificate_code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Fecha de Emisión:</label>
                            <p class="text-gray-900">{{ $certificate->issue_date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Información del Participante</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nombre Completo:</label>
                            <p class="text-xl font-bold text-gray-900">{{ $certificate->user->names }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Curso:</label>
                            <p class="text-gray-900 font-medium">{{ $certificate->course->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Duración:</label>
                            <p class="text-gray-900">{{ number_format($certificate->total_hours) }} horas lectivas</p>
                        </div>
                    </div>
                </div>

                <!-- Información de la empresa -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Emitido por:</h4>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-xl">IPF</span>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-900">IPF CONSULTORES SAC</h5>
                            <p class="text-sm text-gray-600">Entidad capacitadora autorizada</p>
                            <p class="text-xs text-gray-500 mt-1">RUC: {{ $enterprise->ruc ?? 'No disponible' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="mt-8 flex flex-wrap gap-4 justify-center">
                    <a href="javascript:window.print()"
                       class="px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors duration-200 flex items-center">
                        <i class="fas fa-print mr-2"></i>
                        Imprimir Verificación
                    </a>
                    <a href="{{ route('student.certificates.download-exact', $certificate->id) }}"
                       class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Descargar Certificado PDF
                    </a>
                    <button onclick="copyVerificationLink()"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                        <i class="fas fa-link mr-2"></i>
                        Copiar Enlace de Verificación
                    </button>
                </div>
            </div>
            @else
            <!-- Mensaje de certificado inválido -->
            <div class="p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $message }}</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    El certificado que intentas verificar no existe en nuestros registros o ha expirado.
                </p>
                <div class="space-y-4">
                    <p class="text-sm text-gray-500">Si crees que esto es un error, contacta con:</p>
                    <div class="inline-flex flex-col items-center bg-gray-50 p-4 rounded-lg">
                        <p class="font-medium text-gray-900">IPF CONSULTORES SAC</p>
                        <p class="text-sm text-gray-600">consultas@ipfconsultores.com</p>
                        <p class="text-sm text-gray-600">+51 999 999 999</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pie de página de verificación -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-600 mb-4 md:mb-0">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Sistema seguro de verificación - IPF CONSULTORES SAC
                    </div>
                    <div class="text-xs text-gray-500">
                        Verificación realizada el {{ date('d/m/Y') }} a las {{ date('H:i:s') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                Esta página verifica la autenticidad de certificados emitidos por IPF CONSULTORES SAC.
                Los certificados son registrados en nuestra base de datos oficial al momento de su emisión.
            </p>
        </div>
    </div>

    <script>
    function copyVerificationLink() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            alert('Enlace de verificación copiado al portapapeles');
        });
    }
    </script>
</body>
</html>
