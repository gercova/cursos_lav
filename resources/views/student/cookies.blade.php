@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Privacidad y Cookies')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-blue-50 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-24 sm:h-24 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl shadow-xl mb-4 sm:mb-6">
                <i class="fas fa-shield-alt text-white text-2xl sm:text-3xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Privacidad y Política de Cookies
            </h1>
            <div class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-2 sm:gap-4 text-sm sm:text-base text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-user-lock text-blue-500 mr-2"></i>
                    <span>Ley N° 29733 - Perú</span>
                </div>
                <div class="hidden sm:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-cookie text-amber-500 mr-2"></i>
                    <span>GDPR - Europeo</span>
                </div>
                <div class="hidden sm:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-globe-americas text-green-500 mr-2"></i>
                    <span>Decisión 674 - CAN</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap gap-2 mb-6 sm:mb-8">
            <button id="privacy-tab" class="w-full sm:w-auto tab-button active px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-medium text-white bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg transition-all duration-200">
                <i class="fas fa-user-shield mr-2"></i>
                Política de Privacidad
            </button>
            <button id="cookies-tab" class="w-full sm:w-auto tab-button px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-cookie-bite mr-2"></i>
                Política de Cookies
            </button>
            <button id="rights-tab" class="w-full sm:w-auto tab-button px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-gavel mr-2"></i>
                Ejercer Derechos
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-4 sm:px-8 py-4 sm:py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white mb-1 sm:mb-2">{{ $enterprise->trade_name }}</h2>
                        <p class="text-sm sm:text-base text-gray-300">Protección de Datos Personales y Gestión de Cookies</p>
                    </div>
                    <div class="w-full md:w-auto bg-white/10 backdrop-blur-sm px-3 sm:px-4 py-2 rounded-lg text-center md:text-left">
                        <p class="text-xs sm:text-sm text-white font-semibold">Versión 4.1 - Vigente desde {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="p-0">
                <div id="privacy-content" class="tab-content active px-4 sm:px-8 py-6 sm:py-8">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 sm:p-5 rounded-r-lg mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center">
                            <div class="flex-shrink-0 mb-3 sm:mb-0">
                                <i class="fas fa-balance-scale text-blue-600 text-xl sm:text-2xl"></i>
                            </div>
                            <div class="sm:ml-4">
                                <h3 class="text-base sm:text-lg font-semibold text-blue-900">Conforme a Ley Peruana N° 29733</h3>
                                <p class="text-sm sm:text-base text-blue-700 mt-1">
                                    Esta política se rige por la Ley de Protección de Datos Personales del Perú y la Decisión 674 de la Comunidad Andina.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-8 sm:mb-10">
                        <a href="#responsable" class="group bg-gray-50 hover:bg-blue-50 p-3 sm:p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="bg-blue-100 group-hover:bg-blue-200 p-2 sm:p-3 rounded-lg mr-3 transition-colors duration-200">
                                    <i class="fas fa-building text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Responsable</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Quiénes somos</p>
                                </div>
                            </div>
                        </a>

                        <a href="#datos-recogidos" class="group bg-gray-50 hover:bg-blue-50 p-3 sm:p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="bg-blue-100 group-hover:bg-blue-200 p-2 sm:p-3 rounded-lg mr-3 transition-colors duration-200">
                                    <i class="fas fa-database text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Datos Recogidos</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Qué almacenamos</p>
                                </div>
                            </div>
                        </a>

                        <a href="#finalidades" class="group bg-gray-50 hover:bg-blue-50 p-3 sm:p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="bg-blue-100 group-hover:bg-blue-200 p-2 sm:p-3 rounded-lg mr-3 transition-colors duration-200">
                                    <i class="fas fa-bullseye text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Finalidades</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Para qué los usamos</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="space-y-10 sm:space-y-12">
                        <section id="responsable" class="scroll-mt-24">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-3 sm:mr-4">
                                    1
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Responsable del Tratamiento</h2>
                            </div>

                            <div class="pl-0 sm:pl-14">
                                <div class="bg-gray-50 p-4 sm:p-6 rounded-xl mb-4 sm:mb-6">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">{{ $enterprise->trade_name }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-2">Información de Contacto</h4>
                                            <ul class="space-y-2 text-sm sm:text-base text-gray-600">
                                                <li class="flex items-center">
                                                    <i class="fas fa-envelope mr-3 text-blue-500"></i>
                                                    <span class="break-all">{{ $enterprise->email }}</span>
                                                </li>
                                                <li class="flex items-center">
                                                    <i class="fas fa-phone mr-3 text-blue-500"></i>
                                                    <span>+51 123 456 789</span>
                                                </li>
                                                {{-- <li class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-3 text-blue-500"></i>
                                                    <span>Lima, Perú</span>
                                                </li> --}}
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-2">Encargado de Protección</h4>
                                            <p class="text-sm sm:text-base text-gray-600 mb-2">Departamento de Cumplimiento Legal</p>
                                            <a href="mailto:dataprotection@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com"
                                               class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200 text-sm sm:text-base break-all">
                                                <i class="fas fa-user-shield mr-2 flex-shrink-0"></i>
                                                <span class="break-all">dataprotection{{ '@'.strtolower(str_replace(' ', '-', $enterprise->trade_name)) }}.com</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-blue-50 p-4 sm:p-5 rounded-lg border border-blue-200">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3 flex-shrink-0"></i>
                                        <div>
                                            <h4 class="font-semibold text-blue-900 mb-1 sm:mb-2">Principios Rectores</h4>
                                            <p class="text-blue-800 text-xs sm:text-sm">
                                                Nos regimos por los principios establecidos en el <strong>Artículo 6° de la Ley N° 29733</strong>:
                                                Legalidad, Consentimiento, Finalidad, Proporcionalidad, Calidad, Seguridad, Disposición de Recursos y Nivel de Protección Adecuado.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="datos-recogidos" class="scroll-mt-24">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-3 sm:mr-4">
                                    2
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Datos Personales Recogidos</h2>
                            </div>

                            <div class="pl-0 sm:pl-14">
                                <div class="overflow-x-auto mb-6 sm:mb-8 rounded-lg border border-gray-200">
                                    <table class="min-w-full bg-white">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Categoría</th>
                                                <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Datos Específicos</th>
                                                <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Nivel Sensibilidad</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-medium text-gray-900">Identificación</td>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">DNI, nombres, apellidos, nacimiento</td>
                                                <td class="px-3 sm:px-4 py-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Estándar</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-medium text-gray-900">Contacto</td>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Email, teléfono, dirección</td>
                                                <td class="px-3 sm:px-4 py-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Estándar</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-medium text-gray-900">Académicos</td>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Cursos, calificaciones, certificados</td>
                                                <td class="px-3 sm:px-4 py-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Sensible</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-medium text-gray-900">Pagos</td>
                                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Transacciones, método de pago</td>
                                                <td class="px-3 sm:px-4 py-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Sensible</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-5">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3 flex-shrink-0"></i>
                                        <div>
                                            <h4 class="font-semibold text-yellow-900 mb-1 sm:mb-2 text-sm sm:text-base">Datos Sensibles</h4>
                                            <p class="text-yellow-800 text-xs sm:text-sm mb-2">
                                                <strong>Art. 7° Ley N° 29733:</strong> Los datos sensibles solo se procesan con consentimiento explícito o por disposición legal.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="finalidades" class="scroll-mt-24">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-3 sm:mr-4">
                                    3
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Finalidades del Tratamiento</h2>
                            </div>

                            <div class="pl-0 sm:pl-14">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                                    <div class="bg-gradient-to-br from-blue-50 to-white p-4 sm:p-5 rounded-xl border border-blue-100">
                                        <div class="flex items-center mb-3 sm:mb-4">
                                            <div class="bg-blue-100 p-2 sm:p-3 rounded-lg mr-3">
                                                <i class="fas fa-graduation-cap text-blue-600"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Servicios Educativos</h4>
                                        </div>
                                        <ul class="text-xs sm:text-sm text-gray-700 space-y-2">
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Gestión de matrículas</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Seguimiento académico</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Certificados</li>
                                        </ul>
                                    </div>

                                    <div class="bg-gradient-to-br from-green-50 to-white p-4 sm:p-5 rounded-xl border border-green-100">
                                        <div class="flex items-center mb-3 sm:mb-4">
                                            <div class="bg-green-100 p-2 sm:p-3 rounded-lg mr-3">
                                                <i class="fas fa-comments text-green-600"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Comunicación</h4>
                                        </div>
                                        <ul class="text-xs sm:text-sm text-gray-700 space-y-2">
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Soporte técnico</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Notificaciones</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Nuevos cursos</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-purple-50 to-white p-4 sm:p-5 rounded-xl border border-purple-100">
                                        <div class="flex items-center mb-3 sm:mb-4">
                                            <div class="bg-purple-100 p-2 sm:p-3 rounded-lg mr-3">
                                                <i class="fas fa-chart-line text-purple-600"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Mejora Continua</h4>
                                        </div>
                                        <ul class="text-xs sm:text-sm text-gray-700 space-y-2">
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Análisis de plataforma</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Optimización</li>
                                            <li><i class="fas fa-check text-green-500 mr-2"></i>Investigación</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="conservacion" class="scroll-mt-24">
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-3 sm:mr-4">
                                    4
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Conservación de Datos</h2>
                            </div>

                            <div class="pl-0 sm:pl-14">
                                <div class="bg-gradient-to-r from-gray-50 to-white p-4 sm:p-6 rounded-xl border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                                        <div class="text-center bg-white p-4 rounded-lg shadow-sm">
                                            <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-clock text-blue-600 text-xl sm:text-2xl"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-base">Datos de Cuenta</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">5 años tras última actividad</p>
                                        </div>

                                        <div class="text-center bg-white p-4 rounded-lg shadow-sm">
                                            <div class="bg-green-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-graduation-cap text-green-600 text-xl sm:text-2xl"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-base">Datos Académicos</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">Indefinido (certificados)</p>
                                        </div>

                                        <div class="text-center bg-white p-4 rounded-lg shadow-sm">
                                            <div class="bg-purple-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-file-invoice-dollar text-purple-600 text-xl sm:text-2xl"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-base">Datos de Pago</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">10 años (fines fiscales)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div id="cookies-content" class="tab-content hidden px-4 sm:px-8 py-6 sm:py-8">
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 sm:p-5 rounded-r-lg mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center">
                            <div class="flex-shrink-0 mb-3 sm:mb-0">
                                <i class="fas fa-cookie-bite text-amber-600 text-xl sm:text-2xl"></i>
                            </div>
                            <div class="sm:ml-4">
                                <h3 class="text-base sm:text-lg font-semibold text-amber-900">Política de Cookies - GDPR</h3>
                                <p class="text-sm sm:text-base text-amber-700 mt-1">
                                    Utilizamos cookies para mejorar su experiencia. Puede gestionar sus preferencias.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-6 sm:mb-8">
                        <button class="cookie-type-btn active w-full sm:w-auto px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors duration-200" data-type="all">
                            Todas
                        </button>
                        <button class="cookie-type-btn w-full sm:w-auto px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200" data-type="essential">
                            Esenciales
                        </button>
                        <button class="cookie-type-btn w-full sm:w-auto px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200" data-type="analytics">
                            Analíticas
                        </button>
                    </div>

                    <div class="space-y-8">
                        <section>
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="flex-shrink-0 bg-amber-100 text-amber-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-3 sm:mr-4">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">¿Qué son las Cookies?</h2>
                            </div>

                            <div class="pl-0 sm:pl-14">
                                <div class="bg-white p-4 sm:p-6 rounded-xl border border-amber-100 shadow-sm">
                                    <p class="text-sm sm:text-base text-gray-700 mb-4">
                                        Las cookies son pequeños archivos de texto que se almacenan en su dispositivo al visitarnos.
                                    </p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4 sm:mt-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 bg-blue-100 p-2 sm:p-3 rounded-lg mr-3">
                                                <i class="fas fa-bolt text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-1 sm:mb-2 text-sm sm:text-base">Funcionamiento</h4>
                                                <p class="text-xs sm:text-sm text-gray-600">Se descargan y guardan automáticamente.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <div class="flex items-center mb-4 sm:mb-6">
                                <div class="flex-shrink-0 bg-amber-100 text-amber-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-3 sm:mr-4">
                                    <i class="fas fa-list"></i>
                                </div>
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Cookies que Utilizamos</h2>
                            </div>

                            <div class="pl-0 sm:pl-14">
                                <div class="cookie-table essential" id="essential-cookies">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4 flex flex-col sm:flex-row sm:items-center">
                                        <span class="inline-flex items-center self-start sm:self-auto px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium bg-red-100 text-red-800 mb-2 sm:mb-0 sm:mr-3">
                                            Esenciales
                                        </span>
                                        <span class="text-sm sm:text-base">Necesarias para funcionar</span>
                                    </h3>

                                    <div class="overflow-x-auto mb-6 sm:mb-8 rounded-lg border border-gray-200">
                                        <table class="min-w-full bg-white">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Cookie</th>
                                                    <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Finalidad</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-mono text-gray-900 whitespace-nowrap">session_id</td>
                                                    <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Mantener sesión activa</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm font-mono text-gray-900 whitespace-nowrap">XSRF-TOKEN</td>
                                                    <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Seguridad CSRF</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div id="rights-content" class="tab-content hidden px-4 sm:px-8 py-6 sm:py-8">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 sm:p-5 rounded-r-lg mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center">
                            <div class="flex-shrink-0 mb-3 sm:mb-0">
                                <i class="fas fa-hand-paper text-green-600 text-xl sm:text-2xl"></i>
                            </div>
                            <div class="sm:ml-4">
                                <h3 class="text-base sm:text-lg font-semibold text-green-900">Ejercicio de Derechos ARCO</h3>
                                <p class="text-sm sm:text-base text-green-700 mt-1">Conforme al Artículo 22° de la Ley N° 29733</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-10">
                        <div class="bg-gradient-to-br from-blue-50 to-white p-4 sm:p-5 rounded-xl border border-blue-200">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="bg-blue-100 p-2 sm:p-3 rounded-lg mr-3">
                                    <i class="fas fa-search text-blue-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Acceso</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-700">Solicitar información de sus datos.</p>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-50 to-white p-4 sm:p-5 rounded-xl border border-yellow-200">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="bg-yellow-100 p-2 sm:p-3 rounded-lg mr-3">
                                    <i class="fas fa-edit text-yellow-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Rectificación</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-700">Solicitar corrección de datos.</p>
                        </div>
                        <div class="bg-gradient-to-br from-red-50 to-white p-4 sm:p-5 rounded-xl border border-red-200">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="bg-red-100 p-2 sm:p-3 rounded-lg mr-3">
                                    <i class="fas fa-trash-alt text-red-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Cancelación</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-700">Supresión cuando no sean necesarios.</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-white p-4 sm:p-5 rounded-xl border border-purple-200">
                            <div class="flex items-center mb-3 sm:mb-4">
                                <div class="bg-purple-100 p-2 sm:p-3 rounded-lg mr-3">
                                    <i class="fas fa-ban text-purple-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-sm sm:text-base">Oposición</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-700">Oponerse al tratamiento.</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 sm:p-6 mb-8">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 text-center">Ejercer sus Derechos</h3>
                        <div class="max-w-2xl mx-auto">
                            <form id="rights-form" class="space-y-4 sm:space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Nombre Completo *</label>
                                        <input type="text" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm sm:text-base" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Correo Electrónico *</label>
                                        <input type="email" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm sm:text-base" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Tipo de Derecho *</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                        <label class="flex items-center"><input type="checkbox" class="h-4 w-4 text-green-600 rounded"><span class="ml-2 text-xs sm:text-sm text-gray-700">Acceso</span></label>
                                        <label class="flex items-center"><input type="checkbox" class="h-4 w-4 text-green-600 rounded"><span class="ml-2 text-xs sm:text-sm text-gray-700">Rectificación</span></label>
                                        <label class="flex items-center"><input type="checkbox" class="h-4 w-4 text-green-600 rounded"><span class="ml-2 text-xs sm:text-sm text-gray-700">Cancelación</span></label>
                                        <label class="flex items-center"><input type="checkbox" class="h-4 w-4 text-green-600 rounded"><span class="ml-2 text-xs sm:text-sm text-gray-700">Oposición</span></label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Detalle *</label>
                                    <textarea rows="3" class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm sm:text-base" required></textarea>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-lg text-sm sm:text-base font-medium text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 transition-all">
                                        <i class="fas fa-paper-plane mr-2"></i> Enviar Solicitud
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 sm:gap-6">
                <div class="w-full md:w-auto text-center md:text-left">
                    <h4 class="font-semibold text-gray-900 mb-2">Documentación Relacionada</h4>
                    <div class="flex flex-wrap justify-center md:justify-start gap-2">
                        <a href="{{ route('terminos-y-condiciones') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs sm:text-sm text-gray-700 transition-colors">
                            <i class="fas fa-file-contract mr-1"></i> Términos
                        </a>
                        <a href="{{ route('politicas-de-uso') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs sm:text-sm text-gray-700 transition-colors">
                            <i class="fas fa-copyright mr-1"></i> Políticas
                        </a>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row w-full md:w-auto gap-2 sm:gap-3">
                    <button onclick="window.print()" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 transition-all">
                        <i class="fas fa-print mr-2"></i> Imprimir
                    </button>
                    <button onclick="copyPageUrl()" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 transition-all">
                        <i class="fas fa-link mr-2"></i> Copiar
                    </button>
                    <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-700 hover:from-blue-700 transition-all">
                        <i class="fas fa-home mr-2"></i> Inicio
                    </a>
                </div>
            </div>
        </div>

        {{-- Card de estado actual del consentimiento (reemplaza al banner duplicado) --}}
        <div class="bg-gray-900 rounded-2xl shadow-xl p-5 sm:p-6 mt-2" id="cookie-status-card">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5 text-2xl">🍪</div>
                    <div>
                        <h4 class="text-white font-semibold text-sm sm:text-base mb-1">Tu configuración actual de cookies</h4>
                        <p id="cookie-status-text" class="text-gray-400 text-xs sm:text-sm leading-relaxed">
                            Cargando estado...
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 w-full md:w-auto flex-shrink-0">
                    <button onclick="CookieConsent.revokeConsent()"
                            class="flex-1 md:flex-none px-3 py-2 text-xs sm:text-sm font-medium text-gray-300 border border-gray-600 rounded-lg hover:bg-gray-800 transition-colors whitespace-nowrap">
                        <i class="fas fa-undo mr-1"></i> Restablecer
                    </button>
                    <button onclick="CookieConsent.openModal()"
                            class="flex-1 md:flex-none px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-amber-600 hover:bg-amber-500 rounded-lg transition-colors whitespace-nowrap shadow">
                        <i class="fas fa-sliders-h mr-1.5"></i> Gestionar preferencias
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-button.active { box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); }
    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.3s ease-in-out; }
    .cookie-type-btn.active { background-color: #f59e0b; color: white; border-color: #f59e0b; }
    .cookie-table { display: block; }
    .cookie-table.hidden { display: none; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    html { scroll-behavior: smooth; scroll-padding-top: 100px; }
    section { scroll-margin-top: 100px; }
    @media print {
        .no-print, .tab-button, .cookie-type-btn, #cookie-consent-banner, .bg-gradient-to-r { display: none !important; }
        .tab-content { display: block !important; }
        section { break-inside: avoid; }
    }
</style>

<script>
    // Todo el código JavaScript queda exactamente igual, gestionando el cambio de pestañas y botones de forma limpia
    const tabs = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active', 'bg-gradient-to-r', 'from-blue-600', 'to-cyan-600', 'text-white'));
            tabs.forEach(t => t.classList.add('bg-white', 'text-gray-700'));
            
            tab.classList.add('active', 'bg-gradient-to-r', 'from-blue-600', 'to-cyan-600', 'text-white');
            tab.classList.remove('bg-white', 'text-gray-700');

            tabContents.forEach(content => content.classList.remove('active'));
            const tabId = tab.id.replace('-tab', '-content');
            document.getElementById(tabId).classList.add('active');

            const target = tab.id.replace('-tab', '');
            history.pushState(null, null, `#${target}`);
        });
    });

    const cookieTypeButtons = document.querySelectorAll('.cookie-type-btn');
    const cookieTables = document.querySelectorAll('.cookie-table');

    cookieTypeButtons.forEach(button => {
        button.addEventListener('click', () => {
            cookieTypeButtons.forEach(btn => btn.classList.remove('active', 'bg-amber-600', 'text-white'));
            cookieTypeButtons.forEach(btn => btn.classList.add('bg-white', 'text-gray-700'));
            
            button.classList.add('active', 'bg-amber-600', 'text-white');
            button.classList.remove('bg-white', 'text-gray-700');

            cookieTables.forEach(table => table.classList.add('hidden'));
            const type = button.dataset.type;
            if (type === 'all') {
                cookieTables.forEach(table => table.classList.remove('hidden'));
            } else {
                document.getElementById(`${type}-cookies`).classList.remove('hidden');
            }
        });
    });

    // ── Card de estado de consentimiento ────────────────────────────────────
    // Actualiza el texto de estado en el card al cargar y al cambiar el consentimiento
    function updateCookieStatusCard() {
        const statusText = document.getElementById('cookie-status-text');
        if (!statusText) return;

        // Esperamos a que CookieConsent esté disponible (cargado en app.blade.php)
        if (typeof window.CookieConsent === 'undefined') {
            statusText.textContent = 'Cargando estado...';
            return;
        }

        const consent = window.CookieConsent.getConsent();

        if (!consent) {
            statusText.innerHTML =
                '<span class="text-amber-400 font-medium">⚠ Sin consentimiento registrado.</span> ' +
                'Usa el botón "Gestionar preferencias" para configurar tus cookies.';
            return;
        }

        const statusMap = {
            accepted : '<span class="text-green-400 font-medium">✔ Aceptaste todas las cookies.</span>',
            rejected : '<span class="text-red-400 font-medium">✘ Rechazaste las cookies opcionales.</span>',
            custom   : '<span class="text-blue-400 font-medium">⚙ Preferencias personalizadas guardadas.</span>',
        };

        const prefs   = consent.preferences ?? {};
        const details = [
            prefs.functional ? '✔ Funcionales' : '✘ Funcionales',
            prefs.analytics  ? '✔ Analíticas'  : '✘ Analíticas',
            prefs.marketing  ? '✔ Marketing'   : '✘ Marketing',
        ].join(' &nbsp;|&nbsp; ');

        const date = new Date(consent.timestamp).toLocaleDateString('es-PE', {
            day: '2-digit', month: '2-digit', year: 'numeric'
        });

        statusText.innerHTML =
            (statusMap[consent.status] ?? '') +
            `<br><span class="text-gray-400 text-xs mt-1 block">${details} &nbsp;·&nbsp; Guardado el ${date}</span>`;
    }

    // Ejecutar al cargar y cuando el consentimiento cambie
    document.addEventListener('DOMContentLoaded', updateCookieStatusCard);
    window.addEventListener('cookieConsentUpdated', updateCookieStatusCard);

    // Hash control
    window.addEventListener('load', () => {
        const hash = window.location.hash.substring(1);
        if (hash && ['privacy', 'cookies', 'rights'].includes(hash)) {
            document.getElementById(`${hash}-tab`).click();
        }
    });
</script>
@endsection