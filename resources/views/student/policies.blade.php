@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Políticas de Uso')
@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl shadow-xl mb-6">
                <i class="fas fa-copyright text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Políticas de Uso y Propiedad Intelectual
            </h1>
            <div class="flex flex-wrap items-center justify-center gap-4 text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-balance-scale text-purple-500 mr-2"></i>
                    <span>Decisión 486 - Comunidad Andina</span>
                </div>
                <div class="hidden md:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-gavel text-purple-500 mr-2"></i>
                    <span>Ley N° 11.426 - Propiedad Intelectual Perú</span>
                </div>
                <div class="hidden md:block">•</div>
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-purple-500 mr-2"></i>
                    <span>Ley N° 29733 - Protección de Datos</span>
                </div>
            </div>
        </div>

        <!-- Alert Banner -->
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-5 mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-purple-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-purple-900">Importante: Uso del Contenido</h3>
                    <p class="text-purple-700 mt-1">
                        Todo el contenido de {{ $enterprise->trade_name }} está protegido por derechos de autor y propiedad intelectual.
                        El acceso a los cursos no implica transferencia de derechos de propiedad.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Navigation -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <a href="#propiedad-intelectual" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md hover:border-purple-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-purple-100 p-3 rounded-lg mb-3 group-hover:bg-purple-200 transition-colors duration-200">
                        <i class="fas fa-copyright text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Propiedad Intelectual</span>
                </div>
            </a>

            <a href="#uso-contenido" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-blue-100 p-3 rounded-lg mb-3 group-hover:bg-blue-200 transition-colors duration-200">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Uso del Contenido</span>
                </div>
            </a>

            <a href="#restricciones" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md hover:border-red-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-red-100 p-3 rounded-lg mb-3 group-hover:bg-red-200 transition-colors duration-200">
                        <i class="fas fa-ban text-red-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Restricciones</span>
                </div>
            </a>

            <a href="#licencias" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md hover:border-green-300 transition-all duration-200 group">
                <div class="flex flex-col items-center text-center">
                    <div class="bg-green-100 p-3 rounded-lg mb-3 group-hover:bg-green-200 transition-colors duration-200">
                        <i class="fas fa-file-contract text-green-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Licencias</span>
                </div>
            </a>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Document Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-2">{{ $enterprise->trade_name }}</h2>
                        <p class="text-purple-200">Políticas de Uso del Contenido y Propiedad Intelectual</p>
                    </div>
                    <div class="mt-4 md:mt-0 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <p class="text-sm text-white font-semibold">Versión 3.2 - Vigente desde {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-8">
                <!-- Legal Framework Banner -->
                <div class="bg-gray-50 border-l-4 border-purple-500 p-5 rounded-r-lg mb-8">
                    <div class="flex items-start">
                        <i class="fas fa-landmark text-purple-600 text-2xl mt-1 mr-4"></i>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-2">Marco Legal Aplicable</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="font-medium text-gray-800">Comunidad Andina:</p>
                                    <ul class="text-gray-600">
                                        <li>• Decisión 486 - Régimen Común de Propiedad Industrial</li>
                                        <li>• Decisión 351 - Régimen Común de Derecho de Autor</li>
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

                <!-- Section 1: Propiedad Intelectual -->
                <section id="propiedad-intelectual" class="mb-12 scroll-mt-24">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-purple-100 text-purple-800 font-bold rounded-lg w-12 h-12 flex items-center justify-center mr-4">
                            <i class="fas fa-copyright"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">1. Propiedad Intelectual</h2>
                            <p class="text-gray-600 mt-1">Conforme a Decisión 486 de la Comunidad Andina</p>
                        </div>
                    </div>

                    <div class="pl-16">
                        <!-- 1.1 Definiciones -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">1.1 Definiciones Legales</h3>
                            <div class="bg-gray-50 p-5 rounded-xl mb-4">
                                <p class="text-gray-700 mb-3">
                                    Según la <strong>Decisión 486 de la Comunidad Andina</strong> y la <strong>Ley N° 11.426 del Perú</strong>,
                                    se entiende por propiedad intelectual:
                                </p>
                                <ul class="list-disc pl-5 space-y-2 text-gray-700">
                                    <li><strong>Obra:</strong> Toda creación intelectual original de carácter artístico, científico o literario</li>
                                    <li><strong>Contenido educativo:</strong> Material didáctico estructurado con fines de enseñanza</li>
                                    <li><strong>Base de datos:</strong> Colección organizada de datos protegida por derechos sui generis</li>
                                    <li><strong>Software educativo:</strong> Programas informáticos diseñados para fines educativos</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 1.2 Titularidad -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">1.2 Titularidad de Derechos</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-purple-50 border border-purple-200 rounded-xl p-5">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-university text-purple-600 text-xl mr-3"></i>
                                        <h4 class="font-bold text-purple-800">Contenido Institucional</h4>
                                    </div>
                                    <ul class="text-sm text-purple-700 space-y-2">
                                        <li>• Logotipos y marcas registradas</li>
                                        <li>• Diseño de plataforma y software</li>
                                        <li>• Metodología educativa propietaria</li>
                                        <li>• Material corporativo y promocional</li>
                                    </ul>
                                </div>

                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                                    <div class="flex items-center mb-3">
                                        <i class="fas fa-chalkboard-teacher text-blue-600 text-xl mr-3"></i>
                                        <h4 class="font-bold text-blue-800">Contenido Académico</h4>
                                    </div>
                                    <ul class="text-sm text-blue-700 space-y-2">
                                        <li>• Cursos y módulos educativos</li>
                                        <li>• Videos, presentaciones y lecturas</li>
                                        <li>• Evaluaciones y exámenes</li>
                                        <li>• Casos prácticos y ejercicios</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-yellow-800 mb-1">Advertencia Legal</p>
                                        <p class="text-yellow-700 text-sm">
                                            La inscripción en cursos otorga una licencia de uso limitada, no constituye transferencia
                                            de propiedad intelectual conforme al Artículo 23 de la Decisión 486.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 1.3 Registros -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">1.3 Registros y Protecciones</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Elemento Protegido</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Tipo de Protección</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Registro</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Vigencia</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Marca "{{ $enterprise->trade_name }}"</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Marca registrada</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">INDECOPI - Perú</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">10 años renovables</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Plataforma de e-learning</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Derecho de autor software</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Registro Nacional</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">70 años p.m.a.</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Contenido de cursos</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Obra educativa</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Registro automático</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">70 años p.m.a.</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Metodología SSOMA</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Secreto empresarial</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Protección interna</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Indefinida</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">
                                <strong>p.m.a.:</strong> post mortem auctoris (70 años después del fallecimiento del autor)
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Section 2: Uso del Contenido -->
                <section id="uso-contenido" class="mb-12 scroll-mt-24">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-blue-100 text-blue-800 font-bold rounded-lg w-12 h-12 flex items-center justify-center mr-4">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">2. Uso del Contenido</h2>
                            <p class="text-gray-600 mt-1">Licencias y derechos de acceso</p>
                        </div>
                    </div>

                    <div class="pl-16">
                        <!-- 2.1 Licencia Educativa -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">2.1 Licencia de Uso Educativo</h3>
                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-6 rounded-xl mb-6">
                                <p class="text-gray-700 mb-4">
                                    Al adquirir un curso en {{ $enterprise->trade_name }}, usted obtiene una <strong>licencia personal,
                                    intransferible y no exclusiva</strong> para:
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-lg border border-blue-100">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                            <h4 class="font-semibold text-gray-800">Usos Permitidos</h4>
                                        </div>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Acceso personal al contenido</li>
                                            <li>• Visualización para fines educativos</li>
                                            <li>• Descarga de materiales permitidos</li>
                                            <li>• Uso de ejercicios prácticos</li>
                                        </ul>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border border-red-100">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                            <h4 class="font-semibold text-gray-800">Usos Prohibidos</h4>
                                        </div>
                                        <ul class="text-sm text-gray-700 space-y-1">
                                            <li>• Distribución a terceros</li>
                                            <li>• Uso comercial del contenido</li>
                                            <li>• Modificación o adaptación</li>
                                            <li>• Ingeniería inversa</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2.2 Material Descargable -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">2.2 Material Descargable</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-green-100 p-2 rounded-lg mr-4">
                                        <i class="fas fa-file-pdf text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-2">Documentos PDF</h4>
                                        <p class="text-gray-700 text-sm">
                                            Los documentos marcados como "descargables" pueden ser almacenados para uso personal,
                                            manteniendo las marcas de agua y referencias a {{ $enterprise->trade_name }}.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-blue-100 p-2 rounded-lg mr-4">
                                        <i class="fas fa-video text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-2">Contenido Multimedia</h4>
                                        <p class="text-gray-700 text-sm">
                                            Videos, presentaciones y contenido interactivo son para visualización en línea exclusivamente.
                                            Queda prohibida su descarga, grabación o distribución.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 bg-purple-100 p-2 rounded-lg mr-4">
                                        <i class="fas fa-code text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-2">Plantillas y Código</h4>
                                        <p class="text-gray-700 text-sm">
                                            Plantillas, formularios y código proporcionado pueden ser utilizados en sus proyectos,
                                            manteniendo la atribución a {{ $enterprise->trade_name }} cuando sea requerido.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2.3 Certificados -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">2.3 Certificados Digitales</h3>
                            <div class="bg-gray-50 p-5 rounded-xl">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-certificate text-yellow-600 text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Uso de Certificados</h4>
                                        <p class="text-gray-600 text-sm">De acuerdo con Artículo 45 de la Decisión 351</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-lg border">
                                        <p class="text-sm text-gray-700 mb-2">
                                            <strong>Uso Personal:</strong> Los certificados pueden ser utilizados en:
                                        </p>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            <li>• Currículum vitae personal</li>
                                            <li>• Perfiles profesionales (LinkedIn)</li>
                                            <li>• Portafolios individuales</li>
                                            <li>• Procesos de certificación laboral</li>
                                        </ul>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border">
                                        <p class="text-sm text-gray-700 mb-2">
                                            <strong>Restricciones:</strong> Queda prohibido:
                                        </p>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            <li>• Modificar el certificado</li>
                                            <li>• Usarlo para falsa representación</li>
                                            <li>• Comercializar el certificado</li>
                                            <li>• Atribuir autoría no correspondiente</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        <strong>Verificación:</strong> Todos los certificados cuentan con código QR único para verificación
                                        de autenticidad en nuestra plataforma.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 3: Restricciones -->
                <section id="restricciones" class="mb-12 scroll-mt-24">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-red-100 text-red-800 font-bold rounded-lg w-12 h-12 flex items-center justify-center mr-4">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">3. Restricciones y Sanciones</h2>
                            <p class="text-gray-600 mt-1">Consecuencias por violación de derechos</p>
                        </div>
                    </div>

                    <div class="pl-16">
                        <!-- 3.1 Conductas Prohibidas -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">3.1 Conductas Prohibidas</h3>
                            <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6">
                                <p class="text-red-800 font-medium mb-4">
                                    Según el Artículo 226 de la Decisión 486, las siguientes conductas constituyen infracción:
                                </p>

                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Reproducción no autorizada</h4>
                                            <p class="text-gray-700 text-sm">
                                                Copiar, duplicar o almacenar contenido fuera de la plataforma sin autorización expresa.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Distribución ilícita</h4>
                                            <p class="text-gray-700 text-sm">
                                                Compartir credenciales, enlaces de acceso o contenido con terceros no autorizados.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Uso comercial</h4>
                                            <p class="text-gray-700 text-sm">
                                                Utilizar el contenido para fines comerciales, incluyendo capacitación a empleados sin licencia corporativa.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <i class="fas fa-times text-red-500 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-gray-800">Ingeniería inversa</h4>
                                            <p class="text-gray-700 text-sm">
                                                Descompilar, desensamblar o intentar extraer el código fuente de la plataforma.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3.2 Sanciones -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">3.2 Sanciones Aplicables</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <thead class="bg-gray-900 text-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Infracción</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Sanciones Civiles</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Sanciones Administrativas</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Acciones Legales</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Uso no autorizado</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Indemnización por daños</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Suspensión de cuenta</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Acción de cesación</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Distribución ilícita</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Lucro cesante + daños</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Cancelación permanente</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Demanda civil</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Falsificación</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Indemnización triplicada</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Lista negra IP</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Denuncia penal</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-700">Uso comercial</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Regalías retroactivas</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Bloqueo empresarial</td>
                                            <td class="px-4 py-3 text-sm text-gray-700">Acción de competencia</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">
                                    <strong>Nota:</strong> Todas las sanciones se aplican conforme a la Ley N° 11.426 del Perú y la Decisión 486
                                    de la Comunidad Andina. Las multas pueden alcanzar hasta 180 UIT (Unidades Impositivas Tributarias).
                                </p>
                            </div>
                        </div>

                        <!-- 3.3 Monitoreo -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">3.3 Monitoreo y Detección</h3>
                            <div class="bg-gray-50 p-5 rounded-xl">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-eye text-purple-600 text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Sistemas de Protección</h4>
                                        <p class="text-gray-600 text-sm">Conforme a Ley N° 29733 de Protección de Datos</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-fingerprint text-blue-500 mr-2"></i>
                                            <h4 class="font-semibold text-gray-800">Huella Digital</h4>
                                        </div>
                                        <p class="text-xs text-gray-600">
                                            Marcas de agua invisibles en todo el contenido multimedia para trazabilidad.
                                        </p>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-user-shield text-green-500 mr-2"></i>
                                            <h4 class="font-semibold text-gray-800">DRM Educativo</h4>
                                        </div>
                                        <p class="text-xs text-gray-600">
                                            Gestión de derechos digitales para contenido descargable y streaming.
                                        </p>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-search text-red-500 mr-2"></i>
                                            <h4 class="font-semibold text-gray-800">Monitoreo Web</h4>
                                        </div>
                                        <p class="text-xs text-gray-600">
                                            Búsqueda activa de contenido pirateado en internet y redes sociales.
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        <strong>Protección de Datos:</strong> El monitoreo se realiza respetando la privacidad del usuario
                                        conforme a la Ley N° 29733, recolectando solo datos necesarios para la protección de derechos.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section 4: Licencias Especiales -->
                <section id="licencias" class="mb-12 scroll-mt-24">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-green-100 text-green-800 font-bold rounded-lg w-12 h-12 flex items-center justify-center mr-4">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">4. Licencias Especiales</h2>
                            <p class="text-gray-600 mt-1">Opciones para uso institucional</p>
                        </div>
                    </div>

                    <div class="pl-16">
                        <!-- 4.1 Licencia Corporativa -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">4.1 Licencia Corporativa</h3>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl mb-6">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-building text-green-600 text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Para Empresas e Instituciones</h4>
                                        <p class="text-gray-600 text-sm">Solución integral para capacitación organizacional</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                    <div class="bg-white p-4 rounded-lg border border-green-200">
                                        <h4 class="font-semibold text-gray-800 mb-3">Características</h4>
                                        <ul class="text-sm text-gray-700 space-y-2">
                                            <li>• Acceso múltiple simultáneo</li>
                                            <li>• Panel de administración</li>
                                            <li>• Reportes de progreso</li>
                                            <li>• Certificados personalizados</li>
                                        </ul>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border border-green-200">
                                        <h4 class="font-semibold text-gray-800 mb-3">Ventajas</h4>
                                        <ul class="text-sm text-gray-700 space-y-2">
                                            <li>• Costos escalables</li>
                                            <li>• Contenido actualizado</li>
                                            <li>• Soporte dedicado</li>
                                            <li>• Cumplimiento normativo</li>
                                        </ul>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border border-green-200">
                                        <h4 class="font-semibold text-gray-800 mb-3">Requisitos</h4>
                                        <ul class="text-sm text-gray-700 space-y-2">
                                            <li>• Contrato de licencia</li>
                                            <li>• NIF/RUC vigente</li>
                                            <li>• Datos de contacto</li>
                                            <li>• Políticas de uso internas</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('contacto') }}"
                                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-700 text-white rounded-lg hover:from-green-700 hover:to-emerald-800 transition-all duration-200">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Solicitar información corporativa
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 4.2 Licencia Educativa -->
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">4.2 Licencia para Instituciones Educativas</h3>
                            <div class="bg-blue-50 p-5 rounded-xl">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-graduation-cap text-blue-600 text-2xl mr-3"></i>
                                    <div>
                                        <h4 class="font-bold text-gray-900">Programas Académicos</h4>
                                        <p class="text-gray-600 text-sm">Convenios con universidades y centros de formación</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Opciones Disponibles</h4>
                                        <ul class="space-y-3">
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Integración con plataformas LMS (Moodle, Blackboard)</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Contenido personalizado para programas específicos</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Acceso para bibliotecas digitales</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check text-green-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Formación para docentes y administrativos</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-800 mb-3">Beneficios</h4>
                                        <ul class="space-y-3">
                                            <li class="flex items-start">
                                                <i class="fas fa-star text-yellow-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Descuentos especiales para estudiantes</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-star text-yellow-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Certificaciones conjuntas</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-star text-yellow-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Actualizaciones gratuitas del contenido</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-star text-yellow-500 mt-1 mr-2"></i>
                                                <span class="text-gray-700">Asesoría técnica especializada</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Acceptance Section -->
                <div class="mt-12 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Declaración de Aceptación</h3>
                            <p class="text-gray-700">
                                Al utilizar nuestros servicios, usted declara conocer y aceptar estas Políticas de Uso.
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Última actualización</p>
                                <p class="font-semibold text-gray-900">{{ date('d/m/Y') }}</p>
                            </div>
                            <div class="bg-purple-600 text-white p-3 rounded-lg">
                                <i class="fas fa-file-signature text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">¿Necesitas más información?</h4>
                    <p class="text-sm text-gray-600">
                        Contacta a nuestro departamento legal:
                        <a href="mailto:legal@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com"
                           class="text-purple-600 hover:text-purple-800 transition-colors duration-200">
                            legal@{{ strtolower(str_replace(' ', '', $enterprise->trade_name)) }}.com
                        </a>
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button onclick="window.print()"
                            class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-print mr-2"></i>
                        Imprimir
                    </button>

                    <a href="{{ route('terminos-y-condiciones') }}"
                       class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-file-contract mr-2"></i>
                        Ver Términos
                    </a>

                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                        <i class="fas fa-home mr-2"></i>
                        Ir al Inicio
                    </a>
                </div>
            </div>
        </div>

        <!-- Legal References -->
        <div class="bg-gray-900 text-white rounded-xl p-6">
            <h4 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-scale-balanced mr-2"></i>
                Referencias Legales Detalladas
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h5 class="font-medium text-purple-300 mb-2">Comunidad Andina</h5>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• Decisión 486 - Régimen Común sobre Propiedad Industrial</li>
                        <li>• Decisión 351 - Régimen Común sobre Derecho de Autor y Derechos Conexos</li>
                        <li>• Decisión 689 - Modificatoria de la Decisión 486</li>
                        <li>• Decisión 674 - Protección de Datos Personales</li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-medium text-purple-300 mb-2">Legislación Peruana</h5>
                    <ul class="text-sm text-gray-300 space-y-1">
                        <li>• Ley N° 11.426 - Ley sobre el Derecho de Autor</li>
                        <li>• Decreto Legislativo N° 822 - Reglamento de Derecho de Autor</li>
                        <li>• Ley N° 29733 - Ley de Protección de Datos Personales</li>
                        <li>• Código Penal - Artículos 216° al 220° sobre delitos contra la propiedad intelectual</li>
                    </ul>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-700">
                <p class="text-xs text-gray-400">
                    <strong>Nota:</strong> Esta documentación ha sido preparada considerando las disposiciones vigentes
                    al {{ date('d/m/Y') }}. Para asesoría legal específica, consulte con un abogado especializado.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for this page */
    html {
        scroll-behavior: smooth;
        scroll-padding-top: 120px;
    }

    section {
        scroll-margin-top: 120px;
    }

    /* Print optimization */
    @media print {
        .no-print {
            display: none;
        }

        section {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .bg-gradient-to-r {
            background: #f3f4f6 !important;
        }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    ::-webkit-scrollbar-thumb {
        background: #8b5cf6;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #7c3aed;
    }
</style>

<script>
    // Smooth scrolling with offset for fixed header
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                const headerOffset = 120;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Update URL without page reload
                history.pushState(null, null, targetId);
            }
        });
    });

    // Highlight current section in navigation
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.grid a[href^="#"]');

    const observerOptions = {
        root: null,
        rootMargin: '-100px 0px -50% 0px',
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
                        switch(id) {
                            case 'propiedad-intelectual':
                                link.classList.add('border-purple-300');
                                break;
                            case 'uso-contenido':
                                link.classList.add('border-blue-300');
                                break;
                            case 'restricciones':
                                link.classList.add('border-red-300');
                                break;
                            case 'licencias':
                                link.classList.add('border-green-300');
                                break;
                        }
                    }
                });
            }
        });
    }, observerOptions);

    sections.forEach(section => {
        observer.observe(section);
    });

    // Back to top button functionality
    const backToTopButton = document.createElement('button');
    backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopButton.className = 'fixed bottom-8 right-8 bg-purple-600 text-white p-3 rounded-full shadow-lg hover:bg-purple-700 transition-all duration-200 opacity-0 invisible z-50';
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

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Copy current URL for sharing
    function copyPageUrl() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('URL copiada al portapapeles');
        });
    }
</script>
@endsection
