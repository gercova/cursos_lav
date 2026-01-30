@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Términos y Condiciones')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl mb-6">
                <i class="fas fa-gavel text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Términos y Condiciones
            </h1>
            <div class="flex items-center justify-center space-x-4 text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                    <span>Protección de Datos Personales</span>
                </div>
                <div class="hidden md:block">|</div>
                <div class="flex items-center">
                    <i class="fas fa-balance-scale text-blue-500 mr-2"></i>
                    <span>Ley N° 29733 - Perú</span>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav class="mb-8 bg-white rounded-lg shadow-sm p-4">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>
                        Inicio
                    </a>
                </li>
                <li class="text-gray-400">
                    <i class="fas fa-chevron-right"></i>
                </li>
                <li class="text-gray-700 font-medium">
                    Términos y Condiciones
                </li>
            </ol>
        </nav>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Header del documento -->
            <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-8 py-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $enterprise->trade_name }}
                        </h2>
                        <p class="text-gray-600 mt-1">Plataforma de Cursos Online - SSOMA y Medio Ambiente</p>
                    </div>
                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg">
                        <p class="text-sm font-semibold">Última actualización: {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div class="px-8 py-8">
                <!-- Advertencia legal -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-8 rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 font-medium">
                                <strong>Importante:</strong> Al utilizar nuestros servicios, usted acepta cumplir con estos términos y condiciones.
                                Le recomendamos leer detenidamente este documento.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Contenidos -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-list-ul mr-2 text-blue-600"></i>
                        Índice de Contenidos
                    </h3>
                    <ol class="list-decimal pl-5 space-y-2 text-gray-700">
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

                <!-- Sección 1 -->
                <section id="1" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            1
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Aceptación de Términos</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-gray-700 mb-4">
                            Al acceder y utilizar los servicios de {{ $enterprise->trade_name }}, usted acepta estar sujeto a estos Términos y Condiciones,
                            así como a nuestra Política de Privacidad y Política de Cookies. Si no está de acuerdo con alguno de estos términos,
                            deberá abstenerse de utilizar nuestros servicios.
                        </p>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <p class="text-sm text-blue-800">
                                <strong>Nota Legal:</strong> Estos términos constituyen un contrato legalmente vinculante entre usted y {{ $enterprise->trade_name }}.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Sección 2 -->
                <section id="2" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            2
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Servicios Ofrecidos</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-gray-700 mb-4">
                            {{ $enterprise->trade_name }} ofrece servicios de educación en línea especializados en Seguridad, Salud Ocupacional y Medio Ambiente (SSOMA),
                            incluyendo pero no limitado a:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-4">
                            <li>Cursos virtuales certificados</li>
                            <li>Material educativo digital</li>
                            <li>Evaluaciones y exámenes en línea</li>
                            <li>Emisión de certificados digitales</li>
                            <li>Seguimiento de progreso académico</li>
                            <li>Soporte técnico y académico</li>
                        </ul>
                        <p class="text-gray-700">
                            Nos reservamos el derecho de modificar, suspender o descontinuar cualquier aspecto de nuestros servicios en cualquier momento.
                        </p>
                    </div>
                </section>

                <!-- Sección 3 -->
                <section id="3" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            3
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Registro de Usuario</h2>
                    </div>
                    <div class="pl-14">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">3.1 Requisitos de Registro</h3>
                        <p class="text-gray-700 mb-4">
                            Para acceder a ciertos servicios, deberá registrarse proporcionando información veraz, exacta y completa, incluyendo:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-6">
                            <li>Nombre completo y datos de identificación</li>
                            <li>Dirección de correo electrónico válida</li>
                            <li>Número de documento de identidad</li>
                            <li>Información de contacto actualizada</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">3.2 Responsabilidad de la Cuenta</h3>
                        <p class="text-gray-700 mb-4">
                            Usted es responsable de:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-4">
                            <li>Mantener la confidencialidad de su contraseña</li>
                            <li>Todas las actividades realizadas bajo su cuenta</li>
                            <li>Notificar inmediatamente cualquier uso no autorizado</li>
                            <li>Proporcionar información actualizada y veraz</li>
                        </ul>
                    </div>
                </section>

                <!-- Sección 4 - PROTECCIÓN DE DATOS PERSONALES (Ley Peruana) -->
                <section id="4" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 text-green-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            4
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            Protección de Datos Personales
                            <span class="text-lg text-green-600 ml-2">(Conforme a Ley N° 29733)</span>
                        </h2>
                    </div>
                    <div class="pl-14">
                        <!-- Banner de Ley Peruana -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-5 mb-6">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-balance-scale text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <h4 class="font-bold text-green-800">Ley de Protección de Datos Personales - Perú</h4>
                                    <p class="text-green-700 text-sm">Ley N° 29733 y su Reglamento (Decreto Supremo N° 003-2013-JUS)</p>
                                </div>
                            </div>
                            <p class="text-green-700 text-sm">
                                {{ $enterprise->trade_name }} cumple con la legislación peruana de protección de datos personales,
                                garantizando los derechos ARCO (Acceso, Rectificación, Cancelación y Oposición) establecidos en la Ley.
                            </p>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">4.1 Principios de Protección de Datos</h3>
                        <p class="text-gray-700 mb-4">
                            De conformidad con la <strong>Ley N° 29733</strong>, nos comprometemos a procesar sus datos personales bajo los siguientes principios:
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800">Legalidad</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    Recolectamos datos solo para fines específicos, explícitos y legítimos.
                                </p>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-user-shield text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800">Consentimiento</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    Requerimos su consentimiento expreso para el tratamiento de datos personales.
                                </p>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-bullseye text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800">Finalidad</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    Los datos se utilizan únicamente para los fines informados al momento de la recolección.
                                </p>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-gray-800">Proporcionalidad</h4>
                                </div>
                                <p class="text-sm text-gray-700">
                                    Solo recolectamos datos necesarios para los fines declarados.
                                </p>
                            </div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">4.2 Datos Personales Recopilados</h3>
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full bg-gray-50 rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tipo de Dato</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Finalidad</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Base Legal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">Datos de identificación</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Verificación de identidad, emisión de certificados</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Ejecución de contrato, cumplimiento legal</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">Datos de contacto</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Comunicación, soporte técnico</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Consentimiento, interés legítimo</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">Datos académicos</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Seguimiento de progreso, evaluación</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Ejecución de contrato</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-700">Datos de pago</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Procesamiento de transacciones</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Ejecución de contrato, obligación legal</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">4.3 Derechos ARCO (Ley Peruana)</h3>
                        <p class="text-gray-700 mb-4">
                            De acuerdo con la <strong>Ley N° 29733</strong>, usted tiene derecho a:
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                                <div class="flex items-center mb-3">
                                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-search text-green-600"></i>
                                    </div>
                                    <h4 class="font-bold text-green-800">Acceso</h4>
                                </div>
                                <p class="text-sm text-green-700">
                                    Solicitar información sobre sus datos personales almacenados.
                                </p>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                <div class="flex items-center mb-3">
                                    <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-edit text-yellow-600"></i>
                                    </div>
                                    <h4 class="font-bold text-yellow-800">Rectificación</h4>
                                </div>
                                <p class="text-sm text-yellow-700">
                                    Solicitar corrección de datos inexactos o incompletos.
                                </p>
                            </div>

                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <div class="flex items-center mb-3">
                                    <div class="bg-red-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-trash-alt text-red-600"></i>
                                    </div>
                                    <h4 class="font-bold text-red-800">Cancelación</h4>
                                </div>
                                <p class="text-sm text-red-700">
                                    Solicitar supresión de datos cuando ya no sean necesarios.
                                </p>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-center mb-3">
                                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                        <i class="fas fa-ban text-blue-600"></i>
                                    </div>
                                    <h4 class="font-bold text-blue-800">Oposición</h4>
                                </div>
                                <p class="text-sm text-blue-700">
                                    Oponerse al tratamiento de datos para fines específicos.
                                </p>
                            </div>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">4.4 Transferencia Internacional de Datos</h3>
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <p class="text-gray-700 mb-2">
                                <strong>Comunidad Andina:</strong> Aplicamos las disposiciones de la Decisión 674 de la Comunidad Andina sobre protección de datos personales.
                            </p>
                            <p class="text-gray-700">
                                <strong>Transferencias Internacionales:</strong> Solo realizamos transferencias internacionales a países con nivel adecuado de protección,
                                conforme a lo establecido en la legislación peruana.
                            </p>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">4.5 Ejercicio de Derechos ARCO</h3>
                        <p class="text-gray-700 mb-4">
                            Para ejercer sus derechos ARCO, puede contactarnos a través de:
                        </p>
                        <div class="bg-blue-50 p-5 rounded-xl">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                        Correo Electrónico
                                    </h4>
                                    <p class="text-blue-700">{{ $enterprise->email }}</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                                        Formulario ARCO
                                    </h4>
                                    <a href="#" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
                                        Descargar formulario de ejercicio de derechos
                                    </a>
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-600">
                                <strong>Plazo de respuesta:</strong> 20 días hábiles según lo establecido en la Ley N° 29733.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Sección 5 -->
                <section id="5" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            5
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Propiedad Intelectual</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-gray-700 mb-4">
                            Todo el contenido disponible en {{ $enterprise->trade_name }}, incluyendo pero no limitado a textos, gráficos, logotipos,
                            imágenes, videos, software y cursos, está protegido por derechos de autor y otras leyes de propiedad intelectual.
                        </p>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <p class="text-yellow-800">
                                <strong>Advertencia:</strong> Queda prohibida la reproducción, distribución o modificación del contenido sin autorización expresa por escrito.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Sección 6 -->
                <section id="6" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            6
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Responsabilidades</h2>
                    </div>
                    <div class="pl-14">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">6.1 Del Usuario</h3>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-6">
                            <li>Utilizar los servicios de manera adecuada y legal</li>
                            <li>No realizar actividades fraudulentas o ilícitas</li>
                            <li>No compartir credenciales de acceso</li>
                            <li>Respetar los derechos de propiedad intelectual</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 mb-4">6.2 De {{ $enterprise->trade_name }}</h3>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-6">
                            <li>Proporcionar acceso a los servicios contratados</li>
                            <li>Garantizar la seguridad de los datos personales</li>
                            <li>Proporcionar soporte técnico adecuado</li>
                            <li>Emitir certificados al completar cursos satisfactoriamente</li>
                        </ul>
                    </div>
                </section>

                <!-- Sección 7 -->
                <section id="7" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            7
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Modificaciones</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-gray-700 mb-4">
                            Nos reservamos el derecho de modificar estos Términos y Condiciones en cualquier momento.
                            Las modificaciones entrarán en vigor inmediatamente después de su publicación en la plataforma.
                        </p>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <i class="fas fa-bell mr-2"></i>
                                <strong>Notificación de cambios:</strong> Notificaremos cambios significativos a través del correo electrónico registrado o mediante anuncios en la plataforma.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Sección 8 -->
                <section id="8" class="mb-10 scroll-mt-20">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 flex items-center justify-center mr-4">
                            8
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Ley Aplicable y Jurisdicción</h2>
                    </div>
                    <div class="pl-14">
                        <p class="text-gray-700 mb-4">
                            Estos Términos y Condiciones se rigen por las leyes de la República del Perú.
                            Cualquier disputa relacionada con estos términos será sometida a la jurisdicción exclusiva
                            de los tribunales competentes de Lima, Perú.
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-700">
                                <strong>Legislación aplicable:</strong> Código Civil Peruano, Ley de Protección al Consumidor (Ley N° 29571),
                                Ley de Protección de Datos Personales (Ley N° 29733).
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Aceptación final -->
                <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-handshake text-blue-600 text-2xl mr-3"></i>
                        <h3 class="text-xl font-bold text-gray-900">Aceptación de Términos</h3>
                    </div>
                    <p class="text-gray-700 mb-4">
                        Al utilizar los servicios de {{ $enterprise->trade_name }}, usted declara que:
                    </p>
                    <ul class="list-disc pl-5 space-y-2 text-gray-700 mb-6">
                        <li>Ha leído y comprendido estos Términos y Condiciones</li>
                        <li>Acepta cumplir con todas las disposiciones aquí establecidas</li>
                        <li>Reconoce sus derechos y responsabilidades</li>
                        <li>Autoriza el tratamiento de sus datos personales conforme a la Ley N° 29733</li>
                    </ul>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Última revisión: {{ date('d/m/Y') }} | Versión: 2.1
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <p class="text-gray-700">
                        ¿Tienes preguntas sobre nuestros términos?
                    </p>
                    <p class="text-sm text-gray-600">
                        Contáctanos: <span class="text-blue-600">{{ $enterprise->email }}</span>
                    </p>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-5 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Volver al Registro
                    </a>

                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-5 py-3 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Ir al Inicio
                    </a>
                </div>
            </div>
        </div>

        <!-- Información legal adicional -->
        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-book mr-2 text-blue-600"></i>
                Referencias Legales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-2">Ley Peruana</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Ley N° 29733 - Protección de Datos Personales</li>
                        <li>• D.S. N° 003-2013-JUS - Reglamento</li>
                        <li>• Ley N° 29571 - Protección al Consumidor</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-2">Comunidad Andina</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Decisión 674 - Protección de Datos</li>
                        <li>• Decisión 486 - Propiedad Intelectual</li>
                    </ul>
                </div>

                <div class="bg-white p-4 rounded-lg border">
                    <h4 class="font-semibold text-gray-800 mb-2">Normas Internacionales</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• GDPR - Reglamento Europeo</li>
                        <li>• ISO 27001 - Seguridad de la Información</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Smooth scrolling for anchor links */
    html {
        scroll-behavior: smooth;
    }

    /* Style for active section */
    section {
        scroll-margin-top: 2rem;
    }

    /* Custom styling for legal sections */
    .legal-note {
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
        margin: 1.5rem 0;
    }

    /* Print styles */
    @media print {
        .no-print {
            display: none;
        }

        section {
            break-inside: avoid;
        }
    }
</style>

<script>
    // Función para imprimir términos y condiciones
    function printTerms() {
        window.print();
    }

    // Smooth scroll to sections
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Highlight current section in table of contents
    const sections = document.querySelectorAll('section');
    const links = document.querySelectorAll('.bg-gray-50 a');

    window.addEventListener('scroll', () => {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;

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

    // Download ARCO form (simulated)
    document.querySelector('a[href="#"]').addEventListener('click', function(e) {
        if(this.textContent.includes('formulario')) {
            e.preventDefault();
            alert('Formulario ARCO descargado. Complete y envíe a ' + "{{ $enterprise->email }}");
        }
    });
</script>
@endsection
