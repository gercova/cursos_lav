@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Políticas de Uso')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-8 sm:mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl shadow-xl mb-4 sm:mb-6">
                <i class="fas fa-copyright text-white text-2xl sm:text-3xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Políticas de Uso y Propiedad Intelectual
            </h1>
            <div class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-2 sm:gap-4 text-sm sm:text-base text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-balance-scale text-purple-500 mr-2"></i>
                    <span>Decisión 486 - Comunidad Andina</span>
                </div>
                <div class="hidden sm:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-gavel text-purple-500 mr-2"></i>
                    <span>Ley N° 11.426 - Propiedad Intelectual Perú</span>
                </div>
                <div class="hidden sm:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-purple-500 mr-2"></i>
                    <span>Ley N° 29733 - Protección de Datos</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-4 sm:p-5 mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center">
                <div class="flex-shrink-0 mb-3 sm:mb-0">
                    <i class="fas fa-exclamation-circle text-purple-600 text-xl sm:text-2xl"></i>
                </div>
                <div class="sm:ml-4">
                    <h3 class="text-base sm:text-lg font-semibold text-purple-900">Importante: Uso del Contenido</h3>
                    <p class="text-sm sm:text-base text-purple-700 mt-1">
                        Todo el contenido de {{ $enterprise->trade_name }} está protegido por derechos de autor y propiedad intelectual.
                        El acceso a los cursos no implica transferencia de derechos de propiedad.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-8 sm:mb-10">
            <a href="#propiedad-intelectual" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 hover:shadow-md hover:border-purple-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-purple-100 p-2 sm:p-3 rounded-lg mb-2 sm:mb-3 group-hover:bg-purple-200 transition-colors duration-200">
                        <i class="fas fa-copyright text-purple-600 text-lg sm:text-xl"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-gray-900">Propiedad Intelectual</span>
                </div>
            </a>

            <a href="#uso-contenido" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 hover:shadow-md hover:border-blue-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-blue-100 p-2 sm:p-3 rounded-lg mb-2 sm:mb-3 group-hover:bg-blue-200 transition-colors duration-200">
                        <i class="fas fa-book text-blue-600 text-lg sm:text-xl"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-gray-900">Uso del Contenido</span>
                </div>
            </a>

            <a href="#restricciones" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 hover:shadow-md hover:border-red-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-red-100 p-2 sm:p-3 rounded-lg mb-2 sm:mb-3 group-hover:bg-red-200 transition-colors duration-200">
                        <i class="fas fa-ban text-red-600 text-lg sm:text-xl"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-gray-900">Restricciones</span>
                </div>
            </a>

            <a href="#licencias" class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 hover:shadow-md hover:border-green-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-green-100 p-2 sm:p-3 rounded-lg mb-2 sm:mb-3 group-hover:bg-green-200 transition-colors duration-200">
                        <i class="fas fa-file-contract text-green-600 text-lg sm:text-xl"></i>
                    </div>
                    <span class="text-xs sm:text-sm font-medium text-gray-900">Licencias</span>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-4 sm:px-8 py-4 sm:py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white mb-1 sm:mb-2">{{ $enterprise->trade_name }}</h2>
                        <p class="text-sm sm:text-base text-purple-200">Políticas de Uso del Contenido y Propiedad Intelectual</p>
                    </div>
                    <div class="w-full md:w-auto bg-white/10 backdrop-blur-sm px-3 sm:px-4 py-2 rounded-lg text-center md:text-left">
                        <p class="text-xs sm:text-sm text-white font-semibold">Versión 3.2 - Vigente desde {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="px-4 sm:px-8 py-6 sm:py-8">
                <div class="bg-gray-50 border-l-4 border-purple-500 p-4 sm:p-5 rounded-r-lg mb-8">
                    <div class="flex flex-col sm:flex-row items-start">
                        <i class="fas fa-landmark text-purple-600 text-xl sm:text-2xl mb-2 sm:mb-0 sm:mt-1 sm:mr-4"></i>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Marco Legal Aplicable</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="font-medium text-gray-800">Comunidad Andina:</p>
                                    <ul class="text-gray-600">
                                        <li>• Decisión 486 - Propiedad Industrial</li>
                                        <li>• Decisión 351 - Derecho de Autor</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Legislación Peruana:</p>
                                    <ul class="text-gray-600">
                                        <li>• Ley N° 11.426 - Derecho de Autor</li>
                                        <li>• Decreto Legislativo N° 822</li>
                                        <li>• Ley N° 29733 - Protección de Datos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <section id="propiedad-intelectual" class="mb-10 sm:mb-12 scroll-mt-24">
                    <div class="flex items-center mb-4 sm:mb-6 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0 bg-purple-100 text-purple-800 font-bold rounded-lg w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center mr-3 sm:mr-4">
                            <i class="fas fa-copyright"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">1. Propiedad Intelectual</h2>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Conforme a Decisión 486 de la Comunidad Andina</p>
                        </div>
                    </div>

                    <div class="pl-0 sm:pl-16">
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">1.1 Definiciones Legales</h3>
                            <div class="bg-gray-50 p-4 sm:p-5 rounded-xl mb-4">
                                <p class="text-sm sm:text-base text-gray-700 mb-3">
                                    Según la <strong>Decisión 486 de la Comunidad Andina</strong> y la <strong>Ley N° 11.426 del Perú</strong>,
                                    se entiende por propiedad intelectual:
                                </p>
                                <ul class="list-disc pl-5 space-y-1 sm:space-y-2 text-sm sm:text-base text-gray-700">
                                    <li><strong>Obra:</strong> Creación intelectual original artística, científica o literaria</li>
                                    <li><strong>Contenido educativo:</strong> Material didáctico estructurado</li>
                                    <li><strong>Base de datos:</strong> Colección organizada de datos</li>
                                    <li><strong>Software educativo:</strong> Programas para fines educativos</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">1.2 Titularidad de Derechos</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 sm:p-5">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-university text-purple-600 text-lg sm:text-xl mr-3"></i>
                                        <h4 class="font-bold text-purple-800 text-sm sm:text-base">Contenido Institucional</h4>
                                    </div>
                                    <ul class="text-xs sm:text-sm text-purple-700 space-y-2">
                                        <li>• Logotipos y marcas registradas</li>
                                        <li>• Diseño de plataforma y software</li>
                                        <li>• Metodología educativa propietaria</li>
                                        <li>• Material corporativo y promocional</li>
                                    </ul>
                                </div>

                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-5">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-chalkboard-teacher text-blue-600 text-lg sm:text-xl mr-3"></i>
                                        <h4 class="font-bold text-blue-800 text-sm sm:text-base">Contenido Académico</h4>
                                    </div>
                                    <ul class="text-xs sm:text-sm text-blue-700 space-y-2">
                                        <li>• Cursos y módulos educativos</li>
                                        <li>• Videos, presentaciones y lecturas</li>
                                        <li>• Evaluaciones y exámenes</li>
                                        <li>• Casos prácticos y ejercicios</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 p-3 sm:p-4 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-yellow-800 mb-1 text-sm sm:text-base">Advertencia Legal</p>
                                        <p class="text-yellow-700 text-xs sm:text-sm">
                                            La inscripción en cursos otorga una licencia de uso limitada, no constituye transferencia
                                            de propiedad intelectual conforme al Art. 23 de la Decisión 486.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">1.3 Registros y Protecciones</h3>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Elemento Protegido</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Tipo de Protección</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 whitespace-nowrap">Registro</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Marca "{{ $enterprise->trade_name }}"</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Marca registrada</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">INDECOPI</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Plataforma e-learning</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Software</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Registro Nacional</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Contenido cursos</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Obra educativa</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Automático</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="uso-contenido" class="mb-10 sm:mb-12 scroll-mt-24">
                    <div class="flex items-center mb-4 sm:mb-6 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0 bg-blue-100 text-blue-800 font-bold rounded-lg w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center mr-3 sm:mr-4">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">2. Uso del Contenido</h2>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Licencias y derechos de acceso</p>
                        </div>
                    </div>

                    <div class="pl-0 sm:pl-16">
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">2.1 Licencia de Uso Educativo</h3>
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 sm:p-6 rounded-xl mb-6">
                                <p class="text-sm sm:text-base text-gray-700 mb-4">
                                    Al adquirir un curso en {{ $enterprise->trade_name }}, usted obtiene una <strong>licencia personal,
                                    intransferible y no exclusiva</strong> para:
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div class="bg-white p-3 sm:p-4 rounded-lg border border-blue-100">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Usos Permitidos</h4>
                                        </div>
                                        <ul class="text-xs sm:text-sm text-gray-700 space-y-1">
                                            <li>• Acceso personal al contenido</li>
                                            <li>• Visualización educativa</li>
                                            <li>• Descarga permitida</li>
                                            <li>• Ejercicios prácticos</li>
                                        </ul>
                                    </div>

                                    <div class="bg-white p-3 sm:p-4 rounded-lg border border-red-100">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                            <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Usos Prohibidos</h4>
                                        </div>
                                        <ul class="text-xs sm:text-sm text-gray-700 space-y-1">
                                            <li>• Distribución a terceros</li>
                                            <li>• Uso comercial</li>
                                            <li>• Modificación o adaptación</li>
                                            <li>• Ingeniería inversa</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">2.2 Material Descargable</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-green-100 p-2 rounded-lg mr-3 sm:mr-4">
                                        <i class="fas fa-file-pdf text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">Documentos PDF</h4>
                                        <p class="text-xs sm:text-sm text-gray-700">
                                            Los documentos marcados como "descargables" pueden ser almacenados para uso personal,
                                            manteniendo las marcas de agua.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-blue-100 p-2 rounded-lg mr-3 sm:mr-4">
                                        <i class="fas fa-video text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-1 sm:mb-2 text-sm sm:text-base">Contenido Multimedia</h4>
                                        <p class="text-xs sm:text-sm text-gray-700">
                                            Videos y contenido interactivo son para visualización en línea exclusivamente.
                                            Queda prohibida su descarga.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">2.3 Certificados Digitales</h3>
                            <div class="bg-gray-50 p-4 sm:p-5 rounded-xl">
                                <div class="flex flex-col sm:flex-row sm:items-center mb-4">
                                    <i class="fas fa-certificate text-yellow-600 text-xl sm:text-2xl mb-2 sm:mb-0 sm:mr-3"></i>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm sm:text-base">Uso de Certificados</h4>
                                        <p class="text-xs sm:text-sm text-gray-600">De acuerdo con Artículo 45 de la Decisión 351</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div class="bg-white p-3 sm:p-4 rounded-lg border">
                                        <p class="text-xs sm:text-sm text-gray-700 mb-2"><strong>Uso Personal:</strong></p>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            <li>• Currículum vitae</li>
                                            <li>• LinkedIn</li>
                                            <li>• Procesos laborales</li>
                                        </ul>
                                    </div>

                                    <div class="bg-white p-3 sm:p-4 rounded-lg border">
                                        <p class="text-xs sm:text-sm text-gray-700 mb-2"><strong>Restricciones:</strong></p>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            <li>• No modificar el certificado</li>
                                            <li>• No comercializar</li>
                                            <li>• Falsa representación</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="restricciones" class="mb-10 sm:mb-12 scroll-mt-24">
                    <div class="flex items-center mb-4 sm:mb-6 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0 bg-red-100 text-red-800 font-bold rounded-lg w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center mr-3 sm:mr-4">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">3. Restricciones y Sanciones</h2>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Consecuencias por violación de derechos</p>
                        </div>
                    </div>

                    <div class="pl-0 sm:pl-16">
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">3.1 Conductas Prohibidas</h3>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4 sm:p-5 mb-6">
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-times text-red-500 mt-1 mr-2 sm:mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Reproducción no autorizada</h4>
                                            <p class="text-xs sm:text-sm text-gray-700">Copiar o almacenar contenido fuera de la plataforma.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-times text-red-500 mt-1 mr-2 sm:mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 text-sm sm:text-base">Distribución ilícita</h4>
                                            <p class="text-xs sm:text-sm text-gray-700">Compartir credenciales o contenido con terceros.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">3.2 Sanciones Aplicables</h3>
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full bg-white">
                                    <thead class="bg-gray-900 text-white">
                                        <tr>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold whitespace-nowrap">Infracción</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold whitespace-nowrap">Adminstrativa</th>
                                            <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold whitespace-nowrap">Legal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Uso no autorizado</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Suspensión de cuenta</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Acción de cesación</td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Falsificación</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Lista negra IP</td>
                                            <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm text-gray-700">Denuncia penal</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="licencias" class="mb-10 sm:mb-12 scroll-mt-24">
                    <div class="flex items-center mb-4 sm:mb-6 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0 bg-green-100 text-green-800 font-bold rounded-lg w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center mr-3 sm:mr-4">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">4. Licencias Especiales</h2>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">Opciones para uso institucional</p>
                        </div>
                    </div>

                    <div class="pl-0 sm:pl-16">
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">4.1 Licencia Corporativa</h3>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 sm:p-6 rounded-xl mb-6">
                                <div class="flex flex-col sm:flex-row sm:items-center mb-4">
                                    <i class="fas fa-building text-green-600 text-xl sm:text-2xl mb-2 sm:mb-0 sm:mr-3"></i>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm sm:text-base">Para Empresas e Instituciones</h4>
                                        <p class="text-xs sm:text-sm text-gray-600">Solución integral para capacitación organizacional</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-6">
                                    <div class="bg-white p-3 sm:p-4 rounded-lg border border-green-200">
                                        <h4 class="font-semibold text-gray-800 mb-2 sm:mb-3 text-sm sm:text-base">Características</h4>
                                        <ul class="text-xs sm:text-sm text-gray-700 space-y-1 sm:space-y-2">
                                            <li>• Acceso múltiple</li>
                                            <li>• Panel admin</li>
                                            <li>• Reportes</li>
                                        </ul>
                                    </div>
                                    <div class="bg-white p-3 sm:p-4 rounded-lg border border-green-200">
                                        <h4 class="font-semibold text-gray-800 mb-2 sm:mb-3 text-sm sm:text-base">Ventajas</h4>
                                        <ul class="text-xs sm:text-sm text-gray-700 space-y-1 sm:space-y-2">
                                            <li>• Costos escalables</li>
                                            <li>• Soporte dedicado</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="text-center sm:text-left">
                                    <a href="{{ route('contacto') }}"
                                       class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-lg hover:from-green-700 transition-all duration-200 text-sm">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Solicitar información
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="mt-8 sm:mt-12 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4 sm:p-6 border border-purple-200">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-1 sm:mb-2">Declaración de Aceptación</h3>
                            <p class="text-sm sm:text-base text-gray-700">
                                Al utilizar nuestros servicios, usted declara conocer y aceptar estas Políticas de Uso.
                            </p>
                        </div>
                        <div class="flex items-center space-x-3 self-end md:self-auto">
                            <div class="text-right">
                                <p class="text-xs sm:text-sm text-gray-600">Actualización</p>
                                <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ date('d/m/Y') }}</p>
                            </div>
                            <div class="bg-purple-600 text-white p-2 sm:p-3 rounded-lg flex-shrink-0">
                                <i class="fas fa-file-signature text-xl sm:text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left w-full md:w-auto">
                    <h4 class="font-semibold text-gray-900 mb-1">¿Necesitas más información?</h4>
                    <p class="text-xs sm:text-sm text-gray-600 break-all">
                        Contacta a legal:
                        <a href="mailto:legal{{ '@'.strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com"
                           class="text-purple-600 hover:text-purple-800 transition-colors duration-200">
                            legal{{ '@'.strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com
                        </a>
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                    <button onclick="window.print()"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                        <i class="fas fa-print mr-2"></i>
                        Imprimir
                    </button>

                    <a href="{{ route('terminos-y-condiciones') }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                        <i class="fas fa-file-contract mr-2"></i>
                        Términos
                    </a>

                    <a href="{{ route('home') }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all">
                        <i class="fas fa-home mr-2"></i>
                        Inicio
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gray-900 text-white rounded-xl p-4 sm:p-6 mb-8">
            <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4 flex items-center">
                <i class="fas fa-scale-balanced mr-2"></i>
                Referencias Legales
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <h5 class="font-medium text-purple-300 mb-1 sm:mb-2 text-sm sm:text-base">Comunidad Andina</h5>
                    <ul class="text-xs sm:text-sm text-gray-300 space-y-1">
                        <li>• Decisión 486 - Propiedad Industrial</li>
                        <li>• Decisión 351 - Derecho de Autor</li>
                        <li>• Decisión 674 - Protección de Datos</li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-medium text-purple-300 mb-1 sm:mb-2 text-sm sm:text-base">Legislación Peruana</h5>
                    <ul class="text-xs sm:text-sm text-gray-300 space-y-1">
                        <li>• Ley N° 11.426 - Derecho de Autor</li>
                        <li>• Ley N° 29733 - Protección de Datos</li>
                        <li>• Código Penal - Artículos 216° al 220°</li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-700">
                <p class="text-xs text-gray-400">
                    <strong>Nota:</strong> Esta documentación es vigente al {{ date('d/m/Y') }}. Para asesoría, consulte con un abogado.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    html {
        scroll-behavior: smooth;
        scroll-padding-top: 100px;
    }
    section {
        scroll-margin-top: 100px;
    }
    @media print {
        .no-print { display: none; }
        section { break-inside: avoid; page-break-inside: avoid; }
        .bg-gradient-to-r { background: #f3f4f6 !important; }
    }
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #8b5cf6; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #7c3aed; }
</style>

<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                const headerOffset = 80;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.grid a[href^="#"]');

    const observerOptions = {
        root: null,
        rootMargin: '-80px 0px -50% 0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach(link => {
                    link.classList.remove('border-purple-300', 'border-blue-300', 'border-red-300', 'border-green-300', 'shadow-md');
                    if(link.getAttribute('href') === `#${id}`) {
                        link.classList.add('shadow-md');
                        if(id === 'propiedad-intelectual') link.classList.add('border-purple-300');
                        if(id === 'uso-contenido') link.classList.add('border-blue-300');
                        if(id === 'restricciones') link.classList.add('border-red-300');
                        if(id === 'licencias') link.classList.add('border-green-300');
                    }
                });
            }
        });
    }, observerOptions);

    sections.forEach(section => observer.observe(section));

    const backToTopButton = document.createElement('button');
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.className = 'fixed bottom-6 sm:bottom-8 right-6 sm:right-8 bg-purple-600 text-white w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full shadow-lg hover:bg-purple-700 transition-all duration-200 opacity-0 invisible z-50';
    backToTopButton.id = 'back-to-top';
    document.body.appendChild(backToTopButton);

    window.addEventListener('scroll', () => {
        if(window.scrollY > 500) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
            backToTopButton.classList.add('opacity-100', 'visible');
        } else {
            backToTopButton.classList.remove('opacity-100', 'visible');
            backToTopButton.classList.add('opacity-0', 'invisible');
        }
    });

    backToTopButton.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>
@endsection