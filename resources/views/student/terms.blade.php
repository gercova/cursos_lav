@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Términos y Condiciones')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl mb-4 sm:mb-6">
                <i class="fas fa-gavel text-white text-2xl sm:text-3xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Términos y Condiciones
            </h1>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:space-x-4 text-sm sm:text-base text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                    <span>Protección de Datos Personales</span>
                </div>
                <div class="hidden sm:block">|</div>
                <div class="flex items-center">
                    <i class="fas fa-balance-scale text-blue-500 mr-2"></i>
                    <span>Ley N° 29733 - Perú</span>
                </div>
            </div>
        </div>

        <nav class="mb-6 sm:mb-8 bg-white rounded-lg shadow-sm p-3 sm:p-4">
            <ol class="flex flex-wrap items-center gap-2 text-xs sm:text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center">
                        <i class="fas fa-home mr-1"></i>
                        Inicio
                    </a>
                </li>
                <li class="text-gray-400">
                    <i class="fas fa-chevron-right text-xs"></i>
                </li>
                <li class="text-gray-700 font-medium break-all">
                    Términos y Condiciones
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-4 sm:px-8 py-4 sm:py-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                            {{ $enterprise->trade_name }}
                        </h2>
                        <p class="text-sm sm:text-base text-gray-600 mt-1">Plataforma de Cursos Online - SSOMA y Medio Ambiente</p>
                    </div>
                    <div class="bg-blue-100 text-blue-800 px-3 sm:px-4 py-2 rounded-lg w-full sm:w-auto text-center sm:text-left">
                        <p class="text-xs sm:text-sm font-semibold">Última actualización: {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="px-4 sm:px-8 py-6 sm:py-8">
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 sm:p-4 mb-6 sm:mb-8 rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-lg sm:text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs sm:text-sm text-yellow-700 font-medium">
                                <strong>Importante:</strong> Al utilizar nuestros servicios, usted acepta cumplir con estos términos y condiciones.
                                Le recomendamos leer detenidamente este documento.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 sm:p-6 mb-8">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <i class="fas fa-list-ul mr-2 text-blue-600"></i>
                        Índice de Contenidos
                    </h3>
                    <ol class="list-decimal pl-4 sm:pl-5 space-y-2 text-sm sm:text-base text-gray-700">
                        <li><a href="#1" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Aceptación de Términos</a></li>
                        <li><a href="#2" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Servicios Ofrecidos</a></li>
                        <li><a href="#3" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Registro de Usuario</a></li>
                        <li><a href="#4" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Protección de Datos Personales</a></li>
                        <li><a href="#5" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Propiedad Intelectual</a></li>
                        <li><a href="#6" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Responsabilidades</a></li>
                        <li><a href="#7" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Modificaciones</a></li>
                        <li><a href="#8" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">Ley Aplicable y Jurisdicción</a></li>
                    </ol>
                </div>

                <section id="1" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4">
                            1
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Aceptación de Términos</h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            Al acceder y utilizar los servicios de {{ $enterprise->trade_name }}, usted acepta estar sujeto a estos Términos y Condiciones,
                            así como a nuestra Política de Privacidad y Política de Cookies. Si no está de acuerdo con alguno de estos términos,
                            deberá abstenerse de utilizar nuestros servicios.
                        </p>
                        <div class="bg-blue-50 p-3 sm:p-4 rounded-lg border border-blue-100">
                            <p class="text-xs sm:text-sm text-blue-800">
                                <strong>Nota Legal:</strong> Estos términos constituyen un contrato legalmente vinculante entre usted y {{ $enterprise->trade_name }}.
                            </p>
                        </div>
                    </div>
                </section>

                <section id="2" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4">
                            2
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Servicios Ofrecidos</h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            {{ $enterprise->trade_name }} ofrece servicios de educación en línea especializados en Seguridad, Salud Ocupacional y Medio Ambiente (SSOMA),
                            incluyendo pero no limitado a:
                        </p>
                        <ul class="list-disc pl-5 space-y-1 sm:space-y-2 text-sm sm:text-base text-gray-700 mb-4">
                            <li>Cursos virtuales certificados</li>
                            <li>Material educativo digital</li>
                            <li>Evaluaciones y exámenes en línea</li>
                            <li>Emisión de certificados digitales</li>
                            <li>Seguimiento de progreso académico</li>
                            <li>Soporte técnico y académico</li>
                        </ul>
                        <p class="text-sm sm:text-base text-gray-700">
                            Nos reservamos el derecho de modificar, suspender o descontinuar cualquier aspecto de nuestros servicios en cualquier momento.
                        </p>
                    </div>
                </section>

                <section id="3" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4">
                            3
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Registro de Usuario</h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">3.1 Requisitos de Registro</h3>
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            Para acceder a ciertos servicios, deberá registrarse proporcionando información veraz, exacta y completa, incluyendo:
                        </p>
                        <ul class="list-disc pl-5 space-y-1 sm:space-y-2 text-sm sm:text-base text-gray-700 mb-6">
                            <li>Nombre completo y datos de identificación</li>
                            <li>Dirección de correo electrónico válida</li>
                            <li>Número de documento de identidad</li>
                            <li>Información de contacto actualizada</li>
                        </ul>

                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">3.2 Responsabilidad de la Cuenta</h3>
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            Usted es responsable de:
                        </p>
                        <ul class="list-disc pl-5 space-y-1 sm:space-y-2 text-sm sm:text-base text-gray-700 mb-4">
                            <li>Mantener la confidencialidad de su contraseña</li>
                            <li>Todas las actividades realizadas bajo su cuenta</li>
                            <li>Notificar inmediatamente cualquier uso no autorizado</li>
                            <li>Proporcionar información actualizada y veraz</li>
                        </ul>
                    </div>
                </section>

                <section id="4" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-start sm:items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-green-100 text-green-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4 mt-1 sm:mt-0">
                            4
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 flex flex-col sm:flex-row sm:items-center">
                            Protección de Datos Personales
                            <span class="text-sm sm:text-lg text-green-600 sm:ml-2 mt-1 sm:mt-0">(Conforme a Ley N° 29733)</span>
                        </h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 sm:p-5 mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-center mb-3">
                                <i class="fas fa-balance-scale text-green-600 text-xl sm:text-2xl mb-2 sm:mb-0 sm:mr-3"></i>
                                <div>
                                    <h4 class="font-bold text-green-800 text-sm sm:text-base">Ley de Protección de Datos Personales - Perú</h4>
                                    <p class="text-green-700 text-xs sm:text-sm">Ley N° 29733 y su Reglamento (D.S. N° 003-2013-JUS)</p>
                                </div>
                            </div>
                            <p class="text-green-700 text-xs sm:text-sm">
                                {{ $enterprise->trade_name }} cumple con la legislación peruana de protección de datos personales,
                                garantizando los derechos ARCO establecidos en la Ley.
                            </p>
                        </div>

                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">4.1 Principios de Protección de Datos</h3>
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            Nos comprometemos a procesar sus datos personales bajo los siguientes principios:
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-6">
                            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Legalidad</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-700">Recolectamos datos solo para fines específicos y legítimos.</p>
                            </div>

                            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-user-shield text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Consentimiento</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-700">Requerimos su consentimiento expreso para el tratamiento.</p>
                            </div>

                            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-bullseye text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Finalidad</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-700">Los datos se utilizan para los fines informados al momento.</p>
                            </div>

                            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Proporcionalidad</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-700">Solo recolectamos datos necesarios para los fines declarados.</p>
                            </div>
                        </div>

                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">4.2 Datos Personales Recopilados</h3>
                        <div class="overflow-x-auto mb-6 rounded-lg border border-gray-200">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Tipo de Dato</th>
                                        <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Finalidad</th>
                                        <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Base Legal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Identificación</td>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Verificación, emisión de certificados</td>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Ejecución de contrato</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Contacto</td>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Comunicación, soporte técnico</td>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Consentimiento</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Académicos</td>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Seguimiento de progreso</td>
                                        <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Ejecución de contrato</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">4.3 Derechos ARCO</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-xl p-3 sm:p-4">
                                <div class="flex items-center mb-2">
                                    <div class="bg-green-100 p-2 rounded-lg mr-2 sm:mr-3">
                                        <i class="fas fa-search text-green-600"></i>
                                    </div>
                                    <h4 class="font-bold text-green-800 text-sm sm:text-base">Acceso</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-green-700">Solicitar información almacenada.</p>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 sm:p-4">
                                <div class="flex items-center mb-2">
                                    <div class="bg-yellow-100 p-2 rounded-lg mr-2 sm:mr-3">
                                        <i class="fas fa-edit text-yellow-600"></i>
                                    </div>
                                    <h4 class="font-bold text-yellow-800 text-sm sm:text-base">Rectificación</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-yellow-700">Corrección de datos inexactos.</p>
                            </div>

                            <div class="bg-red-50 border border-red-200 rounded-xl p-3 sm:p-4">
                                <div class="flex items-center mb-2">
                                    <div class="bg-red-100 p-2 rounded-lg mr-2 sm:mr-3">
                                        <i class="fas fa-trash-alt text-red-600"></i>
                                    </div>
                                    <h4 class="font-bold text-red-800 text-sm sm:text-base">Cancelación</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-red-700">Supresión cuando no sean necesarios.</p>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 sm:p-4">
                                <div class="flex items-center mb-2">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-2 sm:mr-3">
                                        <i class="fas fa-ban text-blue-600"></i>
                                    </div>
                                    <h4 class="font-bold text-blue-800 text-sm sm:text-base">Oposición</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-blue-700">Oponerse al tratamiento.</p>
                            </div>
                        </div>

                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">4.4 Ejercicio de Derechos ARCO</h3>
                        <div class="bg-blue-50 p-4 sm:p-5 rounded-xl">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">
                                        <i class="fas fa-envelope mr-2 text-blue-600"></i> Correo
                                    </h4>
                                    <p class="text-sm text-blue-700 break-all">{{ $enterprise->email }}</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">
                                        <i class="fas fa-file-alt mr-2 text-blue-600"></i> Formulario
                                    </h4>
                                    <a href="#" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                        Descargar formulario ARCO
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="5" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4">
                            5
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Propiedad Intelectual</h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            Todo el contenido disponible en {{ $enterprise->trade_name }}, incluyendo textos, gráficos, logotipos,
                            imágenes, videos, software y cursos, está protegido por derechos de autor.
                        </p>
                        <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg border border-yellow-200">
                            <p class="text-xs sm:text-sm text-yellow-800">
                                <strong>Advertencia:</strong> Queda prohibida la reproducción o modificación del contenido sin autorización.
                            </p>
                        </div>
                    </div>
                </section>

                <section id="6" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4">
                            6
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Responsabilidades</h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-base sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-4">6.1 Del Usuario</h3>
                                <ul class="list-disc pl-5 space-y-1 sm:space-y-2 text-sm sm:text-base text-gray-700">
                                    <li>Uso adecuado y legal</li>
                                    <li>No compartir credenciales</li>
                                    <li>Respetar la propiedad intelectual</li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-4">6.2 De {{ $enterprise->trade_name }}</h3>
                                <ul class="list-disc pl-5 space-y-1 sm:space-y-2 text-sm sm:text-base text-gray-700">
                                    <li>Garantizar seguridad de datos</li>
                                    <li>Proporcionar soporte técnico</li>
                                    <li>Emitir certificados</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="7" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4">
                            7
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Modificaciones</h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            Nos reservamos el derecho de modificar estos Términos en cualquier momento.
                            Las modificaciones entrarán en vigor inmediatamente después de su publicación.
                        </p>
                    </div>
                </section>

                <section id="8" class="mb-8 sm:mb-10 scroll-mt-20">
                    <div class="flex items-center mb-4 sm:mb-6">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center mr-3 sm:mr-4">
                            8
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Ley Aplicable</h2>
                    </div>
                    <div class="pl-0 sm:pl-14">
                        <p class="text-sm sm:text-base text-gray-700 mb-4">
                            Cualquier disputa relacionada con estos términos será sometida a la jurisdicción exclusiva de los tribunales competentes de Lima, Perú.
                        </p>
                    </div>
                </section>

                <div class="mt-8 sm:mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 sm:p-6 border border-blue-200">
                    <div class="flex items-center justify-center sm:justify-start mb-4">
                        <i class="fas fa-handshake text-blue-600 text-xl sm:text-2xl mr-3"></i>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">Aceptación de Términos</h3>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-4 text-center sm:text-left">
                        Al utilizar los servicios de {{ $enterprise->trade_name }}, usted declara que ha leído y aceptado estas disposiciones.
                    </p>
                    <div class="text-center">
                        <p class="text-xs sm:text-sm text-gray-600">
                            Versión: 2.1
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <p class="text-sm sm:text-base text-gray-700">
                        ¿Tienes preguntas sobre nuestros términos?
                    </p>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">
                        Contáctanos: <span class="text-blue-600 break-all">{{ $enterprise->email }}</span>
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-3">
                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-5 py-2 sm:py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <i class="fas fa-user-plus mr-2"></i>
                        Volver al Registro
                    </a>
                    <a href="{{ route('home') }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-5 py-2 sm:py-3 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <i class="fas fa-home mr-2"></i>
                        Ir al Inicio
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-book mr-2 text-blue-600"></i>
                Referencias Legales
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                <div class="bg-white p-3 sm:p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">Ley Peruana</h4>
                    <ul class="text-xs sm:text-sm text-gray-600 space-y-1">
                        <li>• Ley N° 29733</li>
                        <li>• D.S. N° 003-2013-JUS</li>
                        <li>• Ley N° 29571</li>
                    </ul>
                </div>

                <div class="bg-white p-3 sm:p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">Comunidad Andina</h4>
                    <ul class="text-xs sm:text-sm text-gray-600 space-y-1">
                        <li>• Decisión 674</li>
                        <li>• Decisión 486</li>
                    </ul>
                </div>

                <div class="bg-white p-3 sm:p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-2 text-sm sm:text-base">Internacional</h4>
                    <ul class="text-xs sm:text-sm text-gray-600 space-y-1">
                        <li>• GDPR</li>
                        <li>• ISO 27001</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth scrolling for anchor links */
    html { scroll-behavior: smooth; }
    /* Style for active section */
    section { scroll-margin-top: 5rem; }
    /* Print styles */
    @media print {
        .no-print { display: none; }
        section { break-inside: avoid; }
    }
</style>

<script>
    function printTerms() { window.print(); }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                // Ajuste de offset para el menú sticky de app.blade.php
                window.scrollTo({
                    top: targetElement.offsetTop - 80, 
                    behavior: 'smooth'
                });
            }
        });
    });

    const sections = document.querySelectorAll('section');
    const links = document.querySelectorAll('.bg-gray-50 a');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if(scrollY >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });

        links.forEach(link => {
            link.classList.remove('font-bold', 'text-blue-800');
            if(link.getAttribute('href') === `#${current}`) {
                link.classList.add('font-bold', 'text-blue-800');
            }
        });
    });

    document.querySelector('a[href="#"]').addEventListener('click', function(e) {
        if(this.textContent.includes('formulario')) {
            e.preventDefault();
            alert('Formulario ARCO descargado. Complete y envíe a ' + "{{ $enterprise->email }}");
        }
    });
</script>
@endsection
