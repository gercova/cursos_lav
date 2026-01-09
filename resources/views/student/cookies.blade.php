@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Privacidad y Cookies')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header Principal -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl shadow-xl mb-6">
                <i class="fas fa-shield-alt text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Privacidad y Política de Cookies
            </h1>
            <div class="flex flex-wrap items-center justify-center gap-4 text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-user-lock text-blue-500 mr-2"></i>
                    <span>Ley N° 29733 - Perú</span>
                </div>
                <div class="hidden md:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-cookie text-amber-500 mr-2"></i>
                    <span>GDPR - Reglamento Europeo</span>
                </div>
                <div class="hidden md:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-globe-americas text-green-500 mr-2"></i>
                    <span>Decisión 674 - Comunidad Andina</span>
                </div>
            </div>
        </div>

        <!-- Selector de Pestañas -->
        <div class="flex flex-wrap gap-2 mb-8">
            <button id="privacy-tab" class="tab-button active px-6 py-3 rounded-lg font-medium text-white bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg transition-all duration-200">
                <i class="fas fa-user-shield mr-2"></i>
                Política de Privacidad
            </button>
            <button id="cookies-tab" class="tab-button px-6 py-3 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-cookie-bite mr-2"></i>
                Política de Cookies
            </button>
            <button id="rights-tab" class="tab-button px-6 py-3 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-all duration-200">
                <i class="fas fa-gavel mr-2"></i>
                Ejercer Derechos
            </button>
        </div>

        <!-- Contenedor Principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Header Documento -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $enterprise->trade_name }}</h2>
                        <p class="text-gray-300">Protección de Datos Personales y Gestión de Cookies</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <p class="text-sm text-white font-semibold">Versión 4.1 - Vigente desde {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contenido de Pestañas -->
            <div class="p-0">
                <!-- Pestaña 1: Política de Privacidad -->
                <div id="privacy-content" class="tab-content active px-8 py-8">
                    <!-- Alert Legal -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-r-lg mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-balance-scale text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-blue-900">Conforme a Ley Peruana N° 29733</h3>
                                <p class="text-blue-700 mt-1">
                                    Esta política se rige por la Ley de Protección de Datos Personales del Perú y la Decisión 674 de la Comunidad Andina.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Índice Rápido -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                        <a href="#responsable" class="group bg-gray-50 hover:bg-blue-50 p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="bg-blue-100 group-hover:bg-blue-200 p-3 rounded-lg mr-3 transition-colors duration-200">
                                    <i class="fas fa-building text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Responsable</h4>
                                    <p class="text-sm text-gray-600">Quiénes somos</p>
                                </div>
                            </div>
                        </a>

                        <a href="#datos-recogidos" class="group bg-gray-50 hover:bg-blue-50 p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="bg-blue-100 group-hover:bg-blue-200 p-3 rounded-lg mr-3 transition-colors duration-200">
                                    <i class="fas fa-database text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Datos Recogidos</h4>
                                    <p class="text-sm text-gray-600">Qué información almacenamos</p>
                                </div>
                            </div>
                        </a>

                        <a href="#finalidades" class="group bg-gray-50 hover:bg-blue-50 p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="bg-blue-100 group-hover:bg-blue-200 p-3 rounded-lg mr-3 transition-colors duration-200">
                                    <i class="fas fa-bullseye text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Finalidades</h4>
                                    <p class="text-sm text-gray-600">Para qué usamos sus datos</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Contenido Principal -->
                    <div class="space-y-12">
                        <!-- Sección 1 -->
                        <section id="responsable" class="scroll-mt-24">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    1
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Responsable del Tratamiento</h2>
                            </div>

                            <div class="pl-14">
                                <div class="bg-gray-50 p-6 rounded-xl mb-6">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">{{ $enterprise->trade_name }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-2">Información de Contacto</h4>
                                            <ul class="space-y-2 text-gray-600">
                                                <li class="flex items-center">
                                                    <i class="fas fa-envelope mr-3 text-blue-500"></i>
                                                    <span>{{ $enterprise->email }}</span>
                                                </li>
                                                <li class="flex items-center">
                                                    <i class="fas fa-phone mr-3 text-blue-500"></i>
                                                    <span>+51 123 456 789</span>
                                                </li>
                                                <li class="flex items-center">
                                                    <i class="fas fa-map-marker-alt mr-3 text-blue-500"></i>
                                                    <span>Lima, Perú</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-2">Encargado de Protección de Datos</h4>
                                            <p class="text-gray-600 mb-2">Departamento de Cumplimiento Legal</p>
                                            <a href="mailto:dataprotection@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com"
                                               class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                <i class="fas fa-user-shield mr-2"></i>
                                                dataprotection@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-blue-50 p-5 rounded-lg border border-blue-200">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-blue-900 mb-2">Principios Rectores</h4>
                                            <p class="text-blue-800 text-sm">
                                                Nos regimos por los principios establecidos en el <strong>Artículo 6° de la Ley N° 29733</strong>:
                                                Legalidad, Consentimiento, Finalidad, Proporcionalidad, Calidad, Seguridad, Disposición de Recursos y Nivel de Protección Adecuado.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Sección 2 -->
                        <section id="datos-recogidos" class="scroll-mt-24">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    2
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Datos Personales Recogidos</h2>
                            </div>

                            <div class="pl-14">
                                <!-- Tabla de Datos -->
                                <div class="overflow-x-auto mb-8">
                                    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Categoría</th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Datos Específicos</th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nivel de Sensibilidad</th>
                                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Base Legal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Identificación</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    DNI, nombres, apellidos, fecha de nacimiento, nacionalidad
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Estándar
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">Contrato, legal</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Contacto</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    Email, teléfono, dirección, código postal
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Estándar
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">Consentimiento</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Académicos</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    Historial de cursos, calificaciones, progreso, certificados
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Sensible
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">Contrato</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Técnicos</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    IP, navegador, dispositivo, logs de acceso
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Técnico
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">Interés legítimo</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-medium text-gray-900">Pagos</td>
                                                <td class="px-4 py-3 text-sm text-gray-700">
                                                    Historial de transacciones, método de pago (no datos de tarjeta)
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Sensible
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700">Legal, contrato</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Nota sobre datos sensibles -->
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-yellow-900 mb-2">Datos Sensibles</h4>
                                            <p class="text-yellow-800 text-sm mb-2">
                                                <strong>Artículo 7° Ley N° 29733:</strong> Los datos sensibles (origen racial, convicciones religiosas,
                                                afiliación política, salud, vida sexual, datos biométricos) solo se procesan con consentimiento explícito
                                                o por disposición legal.
                                            </p>
                                            <p class="text-yellow-800 text-sm">
                                                En {{ $enterprise->trade_name }} no recopilamos datos sensibles, excepto información académica que
                                                requiere protección especial conforme a la normativa educativa.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Sección 3 -->
                        <section id="finalidades" class="scroll-mt-24">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    3
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Finalidades del Tratamiento</h2>
                            </div>

                            <div class="pl-14">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                    <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-xl border border-blue-100">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-blue-100 p-3 rounded-lg mr-3">
                                                <i class="fas fa-graduation-cap text-blue-600"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900">Servicios Educativos</h4>
                                        </div>
                                        <ul class="text-sm text-gray-700 space-y-2">
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Gestión de matrículas y cursos</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Seguimiento de progreso académico</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Emisión de certificados</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="bg-gradient-to-br from-green-50 to-white p-5 rounded-xl border border-green-100">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-green-100 p-3 rounded-lg mr-3">
                                                <i class="fas fa-comments text-green-600"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900">Comunicación</h4>
                                        </div>
                                        <ul class="text-sm text-gray-700 space-y-2">
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Soporte técnico y académico</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Notificaciones importantes</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Información de nuevos cursos</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="bg-gradient-to-br from-purple-50 to-white p-5 rounded-xl border border-purple-100">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-purple-100 p-3 rounded-lg mr-3">
                                                <i class="fas fa-chart-line text-purple-600"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900">Mejora Continua</h4>
                                        </div>
                                        <ul class="text-sm text-gray-700 space-y-2">
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Análisis de uso de plataforma</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Optimización de contenidos</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span>Investigación educativa</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Base Legal -->
                                <div class="bg-gray-50 p-5 rounded-xl">
                                    <h4 class="font-semibold text-gray-800 mb-4">Bases Legales del Tratamiento</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-file-contract text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Ejecución de Contrato</h5>
                                                <p class="text-sm text-gray-600">
                                                    Datos necesarios para proveer los servicios educativos contratados.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-check-circle text-green-600"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Consentimiento</h5>
                                                <p class="text-sm text-gray-600">
                                                    Para comunicaciones de marketing y procesamientos adicionales.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-balance-scale text-purple-600"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Interés Legítimo</h5>
                                                <p class="text-sm text-gray-600">
                                                    Para seguridad, prevención de fraudes y mejora de servicios.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Sección 4: Conservación -->
                        <section id="conservacion" class="scroll-mt-24">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    4
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Conservación de Datos</h2>
                            </div>

                            <div class="pl-14">
                                <div class="bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="text-center">
                                            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-clock text-blue-600 text-2xl"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Datos de Cuenta</h4>
                                            <p class="text-sm text-gray-600">Conservados durante 5 años después de la última actividad</p>
                                        </div>

                                        <div class="text-center">
                                            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-graduation-cap text-green-600 text-2xl"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Datos Académicos</h4>
                                            <p class="text-sm text-gray-600">Conservados indefinidamente para verificación de certificados</p>
                                        </div>

                                        <div class="text-center">
                                            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-file-invoice-dollar text-purple-600 text-2xl"></i>
                                            </div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Datos de Pago</h4>
                                            <p class="text-sm text-gray-600">Conservados 10 años por obligaciones fiscales</p>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-6 border-t border-gray-200">
                                        <p class="text-sm text-gray-600">
                                            <strong>Artículo 21° Ley N° 29733:</strong> Los datos personales serán conservados durante el tiempo
                                            necesario para cumplir con las finalidades del tratamiento o por obligaciones legales.
                                            Posteriormente serán anonimizados o eliminados de forma segura.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Sección 5: Transferencias -->
                        <section id="transferencias" class="scroll-mt-24">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    5
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Transferencias Internacionales</h2>
                            </div>

                            <div class="pl-14">
                                <div class="bg-gradient-to-r from-indigo-50 to-white p-6 rounded-xl border border-indigo-100 mb-6">
                                    <div class="flex items-center mb-4">
                                        <i class="fas fa-globe-americas text-indigo-600 text-2xl mr-3"></i>
                                        <div>
                                            <h3 class="font-bold text-gray-900">Conforme a Decisión 674 - Comunidad Andina</h3>
                                            <p class="text-indigo-700 text-sm">Transferencias internacionales de datos personales</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-semibold text-gray-800 mb-3">Países con Nivel Adecuado</h4>
                                            <ul class="space-y-2 text-sm text-gray-700">
                                                <li class="flex items-center">
                                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                                    <span>Países de la Comunidad Andina</span>
                                                </li>
                                                <li class="fas fa-check text-green-500 mr-2"></i>
                                                <span>Países de la Unión Europea</span>
                                                </li>
                                                <li class="flex items-center">
                                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                                    <span>Estados Unidos (con Privacy Shield)</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-gray-800 mb-3">Transferencias Realizadas</h4>
                                            <ul class="space-y-2 text-sm text-gray-700">
                                                <li class="flex items-center">
                                                    <i class="fas fa-cloud text-blue-500 mr-2"></i>
                                                    <span>Amazon Web Services (EE.UU.)</span>
                                                </li>
                                                <li class="flex items-center">
                                                    <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                                    <span>Mailchimp (EE.UU.) para email marketing</span>
                                                </li>
                                                <li class="flex items-center">
                                                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                                                    <span>Google Analytics (EE.UU.) para análisis</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-blue-50 p-5 rounded-lg">
                                    <h4 class="font-semibold text-gray-800 mb-3">Garantías Aplicadas</h4>
                                    <p class="text-sm text-gray-700 mb-3">
                                        Todas las transferencias internacionales cuentan con:
                                    </p>
                                    <ul class="list-disc pl-5 text-sm text-gray-700 space-y-2">
                                        <li>Cláusulas contractuales tipo aprobadas por la Comisión Europea</li>
                                        <li>Certificaciones de privacidad válidas (Privacy Shield)</li>
                                        <li>Compromiso de confidencialidad y seguridad</li>
                                        <li>Notificación al titular en caso de requerimientos legales</li>
                                    </ul>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <!-- Pestaña 2: Política de Cookies -->
                <div id="cookies-content" class="tab-content hidden px-8 py-8">
                    <!-- Banner de Cookies -->
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-5 rounded-r-lg mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-cookie-bite text-amber-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-amber-900">Política de Cookies - GDPR Compliant</h3>
                                <p class="text-amber-700 mt-1">
                                    Utilizamos cookies para mejorar su experiencia. Puede gestionar sus preferencias en cualquier momento.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Selector de Tipos de Cookies -->
                    <div class="flex flex-wrap gap-2 mb-8">
                        <button class="cookie-type-btn active px-4 py-2 rounded-lg font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors duration-200" data-type="all">
                            Todas las Cookies
                        </button>
                        <button class="cookie-type-btn px-4 py-2 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200" data-type="essential">
                            Esenciales
                        </button>
                        <button class="cookie-type-btn px-4 py-2 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200" data-type="preferences">
                            Preferencias
                        </button>
                        <button class="cookie-type-btn px-4 py-2 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200" data-type="analytics">
                            Analíticas
                        </button>
                        <button class="cookie-type-btn px-4 py-2 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors duration-200" data-type="marketing">
                            Marketing
                        </button>
                    </div>

                    <!-- Contenido de Cookies -->
                    <div class="space-y-8">
                        <!-- Qué son las cookies -->
                        <section>
                            <div class="flex items-center mb-6">
                                <div class="bg-amber-100 text-amber-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">¿Qué son las Cookies?</h2>
                            </div>

                            <div class="pl-14">
                                <div class="bg-white p-6 rounded-xl border border-amber-100 shadow-sm">
                                    <p class="text-gray-700 mb-4">
                                        Las cookies son pequeños archivos de texto que se almacenan en su dispositivo cuando visita
                                        nuestro sitio web. Son ampliamente utilizadas para hacer que los sitios web funcionen de manera
                                        más eficiente, así como para proporcionar información a los propietarios del sitio.
                                    </p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                        <div class="flex items-start">
                                            <div class="bg-blue-100 p-3 rounded-lg mr-3">
                                                <i class="fas fa-bolt text-blue-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-2">Funcionamiento</h4>
                                                <p class="text-sm text-gray-600">
                                                    Se descargan automáticamente y se almacenan en el navegador del usuario.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <div class="bg-green-100 p-3 rounded-lg mr-3">
                                                <i class="fas fa-shield-alt text-green-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-2">Seguridad</h4>
                                                <p class="text-sm text-gray-600">
                                                    No son ejecutables, no pueden transmitir virus ni acceder a su disco duro.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Tabla de Cookies -->
                        <section>
                            <div class="flex items-center mb-6">
                                <div class="bg-amber-100 text-amber-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    <i class="fas fa-list"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Cookies que Utilizamos</h2>
                            </div>

                            <div class="pl-14">
                                <!-- Cookies Esenciales -->
                                <div class="cookie-table essential" id="essential-cookies">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 mr-3">
                                            Esenciales
                                        </span>
                                        Necesarias para el funcionamiento básico
                                    </h3>

                                    <div class="overflow-x-auto mb-8">
                                        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Cookie</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Proveedor</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Finalidad</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Duración</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">session_id</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $enterprise->trade_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Mantener la sesión del usuario activa durante la navegación
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Sesión</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">XSRF-TOKEN</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $enterprise->trade_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Protección contra ataques CSRF (Cross-Site Request Forgery)
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Sesión</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">laravel_session</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Laravel</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Identificar la sesión del usuario en la aplicación
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Sesión</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Cookies de Preferencias -->
                                <div class="cookie-table preferences hidden" id="preferences-cookies">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mr-3">
                                            Preferencias
                                        </span>
                                        Personalizan su experiencia
                                    </h3>

                                    <div class="overflow-x-auto mb-8">
                                        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Cookie</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Proveedor</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Finalidad</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Duración</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">theme_preference</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $enterprise->trade_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Recordar preferencia de tema claro/oscuro
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">1 año</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">language</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $enterprise->trade_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Recordar idioma seleccionado por el usuario
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">1 año</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">cookie_consent</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $enterprise->trade_name }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Recordar sus preferencias de cookies
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">1 año</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Cookies Analíticas -->
                                <div class="cookie-table analytics hidden" id="analytics-cookies">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mr-3">
                                            Analíticas
                                        </span>
                                        Nos ayudan a mejorar el sitio
                                    </h3>

                                    <div class="overflow-x-auto mb-8">
                                        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Cookie</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Proveedor</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Finalidad</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Duración</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">_ga</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Google Analytics</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Distinguir usuarios únicos (ID anónimo)
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">2 años</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">_gid</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Google Analytics</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Distinguir usuarios en sesiones
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">24 horas</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">_gat</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Google Analytics</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Limitar el porcentaje de solicitudes
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">1 minuto</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                                        <p class="text-sm text-blue-800">
                                            <strong>Anonimización IP:</strong> Hemos activado la anonimización de IP en Google Analytics,
                                            por lo que los últimos octetos de la dirección IP se enmascaran antes del almacenamiento.
                                        </p>
                                    </div>
                                </div>

                                <!-- Cookies de Marketing -->
                                <div class="cookie-table marketing hidden" id="marketing-cookies">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mr-3">
                                            Marketing
                                        </span>
                                        Mostrar anuncios relevantes
                                    </h3>

                                    <div class="overflow-x-auto mb-8">
                                        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Cookie</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Proveedor</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Finalidad</th>
                                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Duración</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">_fbp</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Facebook</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Mostrar anuncios de Facebook
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">3 meses</td>
                                                </tr>
                                                <tr>
                                                    <td class="px-4 py-3 text-sm font-mono text-gray-900">fr</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">Facebook</td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">
                                                        Segmentación publicitaria
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-gray-700">3 meses</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="bg-yellow-50 p-4 rounded-lg">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Opt-out:</strong> Puede optar por no recibir cookies de marketing mediante
                                            las configuraciones de su navegador o a través de los enlaces de exclusión voluntaria
                                            de las redes sociales.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Gestión de Cookies -->
                        <section>
                            <div class="flex items-center mb-6">
                                <div class="bg-amber-100 text-amber-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                                    <i class="fas fa-sliders-h"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900">Gestión de Cookies</h2>
                            </div>

                            <div class="pl-14">
                                <div class="bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-gray-200">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Configuración del Navegador</h3>
                                            <p class="text-gray-700 mb-4">
                                                Puede gestionar las cookies a través de la configuración de su navegador web:
                                            </p>

                                            <div class="space-y-4">
                                                <div class="flex items-start">
                                                    <i class="fab fa-chrome text-red-500 text-xl mr-3 mt-1"></i>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">Google Chrome</h4>
                                                        <p class="text-sm text-gray-600">
                                                            Configuración → Privacidad y seguridad → Cookies
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-start">
                                                    <i class="fab fa-firefox text-orange-500 text-xl mr-3 mt-1"></i>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">Mozilla Firefox</h4>
                                                        <p class="text-sm text-gray-600">
                                                            Opciones → Privacidad y seguridad → Cookies
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-start">
                                                    <i class="fab fa-safari text-blue-500 text-xl mr-3 mt-1"></i>
                                                    <div>
                                                        <h4 class="font-medium text-gray-900">Safari</h4>
                                                        <p class="text-sm text-gray-600">
                                                            Preferencias → Privacidad → Cookies
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Herramientas de Terceros</h3>
                                            <p class="text-gray-700 mb-4">
                                                Para cookies de análisis y marketing:
                                            </p>

                                            <div class="space-y-4">
                                                <div class="bg-white p-4 rounded-lg border">
                                                    <h4 class="font-medium text-gray-900 mb-2">Google Analytics</h4>
                                                    <a href="https://tools.google.com/dlpage/gaoptout"
                                                       target="_blank"
                                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                                        <i class="fas fa-external-link-alt mr-2"></i>
                                                        Complemento de exclusión voluntaria
                                                    </a>
                                                </div>

                                                <div class="bg-white p-4 rounded-lg border">
                                                    <h4 class="font-medium text-gray-900 mb-2">Facebook</h4>
                                                    <a href="https://www.facebook.com/ads/preferences"
                                                       target="_blank"
                                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm">
                                                        <i class="fas fa-external-link-alt mr-2"></i>
                                                        Preferencias de anuncios
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-6 border-t border-gray-200">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle text-amber-500 text-xl mr-3"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-1">Importante</h4>
                                                <p class="text-sm text-gray-600">
                                                    Deshabilitar las cookies esenciales puede afectar la funcionalidad del sitio web.
                                                    Las cookies de terceros pueden requerir que visite los sitios web de los proveedores para gestionarlas.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <!-- Pestaña 3: Ejercer Derechos -->
                <div id="rights-content" class="tab-content hidden px-8 py-8">
                    <!-- Banner de Derechos -->
                    <div class="bg-green-50 border-l-4 border-green-500 p-5 rounded-r-lg mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-hand-paper text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-green-900">Ejercicio de Derechos ARCO</h3>
                                <p class="text-green-700 mt-1">
                                    Conforme al Artículo 22° de la Ley N° 29733 del Perú
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Cards de Derechos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                        <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-xl border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-100 p-3 rounded-lg mr-3">
                                    <i class="fas fa-search text-blue-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900">Acceso</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4">
                                Solicitar información sobre sus datos personales almacenados.
                            </p>
                            <span class="inline-block text-xs font-medium text-blue-700">
                                Artículo 22.1 Ley 29733
                            </span>
                        </div>

                        <div class="bg-gradient-to-br from-yellow-50 to-white p-5 rounded-xl border border-yellow-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-yellow-100 p-3 rounded-lg mr-3">
                                    <i class="fas fa-edit text-yellow-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900">Rectificación</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4">
                                Solicitar corrección de datos inexactos o incompletos.
                            </p>
                            <span class="inline-block text-xs font-medium text-yellow-700">
                                Artículo 22.2 Ley 29733
                            </span>
                        </div>

                        <div class="bg-gradient-to-br from-red-50 to-white p-5 rounded-xl border border-red-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-red-100 p-3 rounded-lg mr-3">
                                    <i class="fas fa-trash-alt text-red-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900">Cancelación</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4">
                                Solicitar supresión de datos cuando ya no sean necesarios.
                            </p>
                            <span class="inline-block text-xs font-medium text-red-700">
                                Artículo 22.3 Ley 29733
                            </span>
                        </div>

                        <div class="bg-gradient-to-br from-purple-50 to-white p-5 rounded-xl border border-purple-200">
                            <div class="flex items-center mb-4">
                                <div class="bg-purple-100 p-3 rounded-lg mr-3">
                                    <i class="fas fa-ban text-purple-600"></i>
                                </div>
                                <h3 class="font-bold text-gray-900">Oposición</h3>
                            </div>
                            <p class="text-sm text-gray-700 mb-4">
                                Oponerse al tratamiento de datos para fines específicos.
                            </p>
                            <span class="inline-block text-xs font-medium text-purple-700">
                                Artículo 22.4 Ley 29733
                            </span>
                        </div>
                    </div>

                    <!-- Formulario de Ejercicio de Derechos -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Ejercer sus Derechos</h3>

                        <div class="max-w-2xl mx-auto">
                            <form id="rights-form" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nombre Completo *
                                        </label>
                                        <input type="text"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                               placeholder="Ingrese su nombre"
                                               required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Correo Electrónico *
                                        </label>
                                        <input type="email"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                               placeholder="correo@ejemplo.com"
                                               required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        DNI / Documento de Identidad *
                                    </label>
                                    <input type="text"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                           placeholder="Ingrese su número de documento"
                                           required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo de Derecho a Ejercer *
                                    </label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="rights[]" value="access" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">Acceso</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="rights[]" value="rectification" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">Rectificación</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="rights[]" value="cancellation" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">Cancelación</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="rights[]" value="opposition" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-700">Oposición</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Detalle de la Solicitud *
                                    </label>
                                    <textarea rows="4"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200"
                                              placeholder="Describa detalladamente su solicitud..."
                                              required></textarea>
                                </div>

                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                        <div>
                                            <p class="text-sm text-blue-800">
                                                <strong>Plazo de respuesta:</strong> 20 días hábiles según el Artículo 23° de la Ley N° 29733.
                                                <br>
                                                <strong>Costo:</strong> El ejercicio de derechos es gratuito, salvo solicitudes manifiestamente infundadas o excesivas.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit"
                                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Enviar Solicitud
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Métodos Alternativos -->
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Métodos Alternativos de Contacto</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Correo Electrónico</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Envíe su solicitud a nuestro encargado de protección de datos
                                </p>
                                <a href="mailto:dataprotection@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    dataprotection@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com
                                </a>
                            </div>

                            <div class="text-center">
                                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-file-pdf text-green-600 text-2xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Formulario Físico</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Descargue, complete y envíe nuestro formulario oficial
                                </p>
                                <button onclick="downloadForm()"
                                        class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    <i class="fas fa-download mr-1"></i>
                                    Descargar Formulario ARCO
                                </button>
                            </div>

                            <div class="text-center">
                                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-phone-alt text-purple-600 text-2xl"></i>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Teléfono</h4>
                                <p class="text-sm text-gray-600 mb-3">
                                    Llámenos para orientación sobre el proceso
                                </p>
                                <a href="tel:+51123456789"
                                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    <i class="fas fa-phone mr-1"></i>
                                    +51 123 456 789
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer de la Página -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Documentación Relacionada</h4>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('terms') }}"
                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm text-gray-700 transition-colors duration-200">
                            <i class="fas fa-file-contract mr-1"></i>
                            Términos y Condiciones
                        </a>
                        <a href="{{ route('use-policy') }}"
                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm text-gray-700 transition-colors duration-200">
                            <i class="fas fa-copyright mr-1"></i>
                            Políticas de Uso
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <button onclick="window.print()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-print mr-2"></i>
                        Imprimir
                    </button>

                    <button onclick="copyPageUrl()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-link mr-2"></i>
                        Copiar Enlace
                    </button>

                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-cyan-700 hover:from-blue-700 hover:to-cyan-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Ir al Inicio
                    </a>
                </div>
            </div>
        </div>

        <!-- Banner de Consentimiento (simulado) -->
        <div id="cookie-consent-banner" class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 shadow-2xl z-50 transform translate-y-full transition-transform duration-300">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0 md:mr-6">
                        <h4 class="font-semibold text-lg mb-1">Usamos Cookies</h4>
                        <p class="text-gray-300 text-sm">
                            Utilizamos cookies propias y de terceros para mejorar nuestros servicios y mostrarle publicidad relacionada con sus preferencias.
                            Puede gestionar sus preferencias en nuestra <a href="#cookies-tab" class="text-amber-400 hover:text-amber-300 underline">Política de Cookies</a>.
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button id="cookie-reject"
                                class="px-4 py-2 border border-gray-600 rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors duration-200">
                            Rechazar
                        </button>
                        <button id="cookie-accept"
                                class="px-4 py-2 bg-amber-600 hover:bg-amber-700 rounded-lg text-sm font-medium transition-colors duration-200">
                            Aceptar Todas
                        </button>
                        <button id="cookie-settings"
                                class="px-4 py-2 border border-amber-600 text-amber-400 rounded-lg text-sm font-medium hover:bg-amber-900 transition-colors duration-200">
                            Personalizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos personalizados */
    .tab-button.active {
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    .cookie-type-btn.active {
        background-color: #f59e0b;
        color: white;
    }

    .cookie-table {
        display: block;
    }

    .cookie-table.hidden {
        display: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Scroll suave para secciones */
    html {
        scroll-behavior: smooth;
        scroll-padding-top: 140px;
    }

    section {
        scroll-margin-top: 140px;
    }

    /* Estilos para impresión */
    @media print {
        .no-print,
        .tab-button,
        .cookie-type-btn,
        #cookie-consent-banner,
        .bg-gradient-to-r {
            display: none !important;
        }

        .tab-content {
            display: block !important;
        }

        section {
            break-inside: avoid;
        }

        .bg-white {
            background: white !important;
            color: black !important;
        }
    }

    /* Scrollbar personalizado */
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #0ea5e9;
        border-radius: 5px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #0284c7;
    }
</style>

<script>
    // Sistema de pestañas
    const tabs = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remover clase active de todas las pestañas
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Añadir clase active a la pestaña clickeada
            tab.classList.add('active');

            // Mostrar el contenido correspondiente
            const tabId = tab.id.replace('-tab', '-content');
            document.getElementById(tabId).classList.add('active');

            // Actualizar URL sin recargar
            const target = tab.id.replace('-tab', '');
            history.pushState(null, null, `#${target}`);

            // Scroll al inicio del contenido
            document.getElementById(tabId).scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Sistema de filtro de cookies
    const cookieTypeButtons = document.querySelectorAll('.cookie-type-btn');
    const cookieTables = document.querySelectorAll('.cookie-table');

    cookieTypeButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remover clase active de todos los botones
            cookieTypeButtons.forEach(btn => btn.classList.remove('active'));

            // Añadir clase active al botón clickeado
            button.classList.add('active');

            // Ocultar todas las tablas
            cookieTables.forEach(table => table.classList.add('hidden'));

            // Mostrar la tabla correspondiente
            const type = button.dataset.type;
            if (type === 'all') {
                cookieTables.forEach(table => table.classList.remove('hidden'));
            } else {
                document.getElementById(`${type}-cookies`).classList.remove('hidden');
            }
        });
    });

    // Banner de consentimiento de cookies
    const cookieBanner = document.getElementById('cookie-consent-banner');
    const cookieAccept = document.getElementById('cookie-accept');
    const cookieReject = document.getElementById('cookie-reject');
    const cookieSettings = document.getElementById('cookie-settings');

    // Mostrar banner después de 2 segundos (simulación)
    setTimeout(() => {
        if (!localStorage.getItem('cookie-consent')) {
            cookieBanner.classList.remove('translate-y-full');
        }
    }, 2000);

    // Manejar aceptación de cookies
    cookieAccept.addEventListener('click', () => {
        localStorage.setItem('cookie-consent', 'all');
        cookieBanner.classList.add('translate-y-full');
        showToast('Cookies aceptadas correctamente', 'success');
    });

    // Manejar rechazo de cookies
    cookieReject.addEventListener('click', () => {
        localStorage.setItem('cookie-consent', 'rejected');
        cookieBanner.classList.add('translate-y-full');
        showToast('Cookies rechazadas', 'info');
    });

    // Ir a configuración de cookies
    cookieSettings.addEventListener('click', () => {
        // Activar pestaña de cookies
        document.getElementById('cookies-tab').click();
        cookieBanner.classList.add('translate-y-full');
    });

    // Formulario de derechos ARCO
    const rightsForm = document.getElementById('rights-form');
    rightsForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Validación
        const checkedRights = document.querySelectorAll('input[name="rights[]"]:checked');
        if (checkedRights.length === 0) {
            showToast('Seleccione al menos un derecho a ejercer', 'error');
            return;
        }

        // Simular envío
        showToast('Solicitud enviada correctamente. Nos contactaremos en 20 días hábiles.', 'success');
        rightsForm.reset();

        // Scroll al inicio del formulario
        rightsForm.scrollIntoView({ behavior: 'smooth' });
    });

    // Descargar formulario ARCO
    function downloadForm() {
        showToast('Formulario ARCO descargado. Complete y envíe a dataprotection@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com', 'info');

        // Simular descarga
        const link = document.createElement('a');
        link.href = '#';
        link.download = 'formulario-arco.pdf';
        link.click();
    }

    // Copiar URL de la página
    function copyPageUrl() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showToast('URL copiada al portapapeles', 'success');
        });
    }

    // Función para mostrar notificaciones toast
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-50 animate-fade-in-up ${type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-blue-600'}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('animate-fade-out');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Manejar hash en URL al cargar la página
    window.addEventListener('load', () => {
        const hash = window.location.hash.substring(1);
        if (hash && ['privacy', 'cookies', 'rights'].includes(hash)) {
            document.getElementById(`${hash}-tab`).click();
        }
    });

    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#') && href.length > 1) {
                e.preventDefault();
                const targetId = href.substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    // Si es un enlace dentro de una pestaña no activa, activar la pestaña primero
                    const parentTabContent = targetElement.closest('.tab-content');
                    if (parentTabContent && !parentTabContent.classList.contains('active')) {
                        const tabId = parentTabContent.id.replace('-content', '-tab');
                        document.getElementById(tabId).click();

                        // Esperar a que se active la pestaña
                        setTimeout(() => {
                            targetElement.scrollIntoView({ behavior: 'smooth' });
                        }, 300);
                    } else {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            }
        });
    });

    // Añadir estilos CSS para animaciones
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-out {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out;
        }

        .animate-fade-out {
            animation: fade-out 0.3s ease-in forwards;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection
