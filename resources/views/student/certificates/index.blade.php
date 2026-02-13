@extends('layouts.student')
@section('title', 'Mis Certificados')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Encabezado de la página -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Mis Certificados</h1>
                <p class="text-gray-600 mt-2">Certificados de cursos completados y aprobados</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Filtros -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 hover:shadow-sm">
                        <i class="fas fa-filter mr-2 text-gray-500"></i>
                        Filtrar
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-700">Ordenar por</h4>
                        </div>
                        <div class="py-1">
                            <button class="filter-option w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-sort="newest">
                                <span>Más recientes</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: none;"></i>
                            </button>
                            <button class="filter-option w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-sort="oldest">
                                <span>Más antiguos</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: none;"></i>
                            </button>
                            <button class="filter-option w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-sort="name">
                                <span>Nombre del curso</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: none;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Buscar -->
                <div class="relative">
                    <input type="text" id="search-certificates" placeholder="Buscar certificado..." class="pl-10 pr-4 py-2.5 w-full sm:w-64 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Certificados</p>
                        <h3 class="text-2xl font-bold text-blue-900 mt-1">{{ $certificates->total() }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-certificate text-blue-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-blue-700 mt-3">
                    <i class="fas fa-trend-up mr-1"></i>
                    Todos los certificados obtenidos
                </p>
            </div>

            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-800">Horas Certificadas</p>
                        <h3 class="text-2xl font-bold text-emerald-900 mt-1">
                            {{ number_format($certificates->sum('total_hours'), 0) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                        <i class="fas fa-clock text-emerald-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-emerald-700 mt-3">
                    <i class="fas fa-book-open mr-1"></i>
                    Total de horas de capacitación
                </p>
            </div>

            <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-800">Última Emisión</p>
                        <h3 class="text-lg font-bold text-amber-900 mt-1">
                            @if($certificates->count() > 0)
                                {{ $certificates->first()->issue_date->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-amber-500/20 flex items-center justify-center">
                        <i class="fas fa-calendar-check text-amber-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-amber-700 mt-3">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Fecha del último certificado
                </p>
            </div>
        </div>
    </div>

    <!-- Lista de Certificados -->
    @if($certificates->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Encabezado de la tabla -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                <div class="grid grid-cols-12 gap-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <div class="col-span-5 lg:col-span-6">Curso</div>
                    <div class="col-span-3 lg:col-span-2 text-center">Número</div>
                    <div class="col-span-2 hidden lg:block text-center">Horas</div>
                    <div class="col-span-4 lg:col-span-2 text-center">Acciones</div>
                </div>
            </div>

            <!-- Cuerpo de la tabla -->
            <div class="divide-y divide-gray-100">
                @foreach($certificates as $certificate)
                <div class="certificate-item px-6 py-5 hover:bg-gray-50/80 transition-colors duration-200" data-name="{{ strtolower($certificate->course->title) }}" data-date="{{ $certificate->issue_date->timestamp }}">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <!-- Curso -->
                        <div class="col-span-5 lg:col-span-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 border border-blue-200 flex items-center justify-center mr-4">
                                    <i class="fas fa-certificate text-blue-600 text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm lg:text-base">{{ $certificate->course->title }}</h4>
                                    <div class="flex items-center mt-1 text-xs text-gray-500">
                                        <i class="fas fa-calendar-alt mr-1.5 text-gray-400"></i>
                                        <span>Emitido: {{ $certificate->issue_date->format('d/m/Y') }}</span>
                                        @if($certificate->expiry_date)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-clock mr-1.5 text-gray-400"></i>
                                            <span>Vence: {{ $certificate->expiry_date->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Número de Certificado -->
                        <div class="col-span-3 lg:col-span-2">
                            <div class="text-center">
                                <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 border border-gray-200">
                                    <span class="text-xs font-mono text-gray-700 certificate-code">{{ $certificate->getFormattedCertificateNumber() }}</span>
                                    <button onclick="copyToClipboard('{{ $certificate->getFormattedCertificateNumber() }}', this)" class="ml-2 text-gray-400 hover:text-blue-600 transition-colors duration-200" title="Copiar número">
                                        <i class="far fa-copy text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Horas (solo desktop) -->
                        <div class="col-span-2 hidden lg:block">
                            <div class="text-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                                    <i class="fas fa-clock mr-1.5 text-xs"></i>
                                    {{ number_format($certificate->total_hours, 1) }} hrs
                                </span>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="col-span-4 lg:col-span-2">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Ver -->
                                <a href="{{ route('student.certificates.show', $certificate->id) }}" class="action-btn group" title="Ver certificado">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-200 group-hover:border-blue-300 flex items-center justify-center transition-all duration-200">
                                        <i class="fas fa-eye text-blue-600 text-sm"></i>
                                    </div>
                                </a>

                                <!-- Descargar -->
                                <a href="{{ route('student.certificates.download-exact', $certificate->id) }}" class="action-btn group" title="Descargar PDF">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 group-hover:border-emerald-300 flex items-center justify-center transition-all duration-200">
                                        <i class="fas fa-download text-emerald-600 text-sm"></i>
                                    </div>
                                </a>

                                <!-- Verificar (CAMBIAR de QR a enlace) -->
                                <a href="{{ $certificate->verification_url }}" target="_blank" class="action-btn group" title="Verificar certificado">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 group-hover:border-amber-300 flex items-center justify-center transition-all duration-200">
                                        <i class="fas fa-check-circle text-amber-600 text-sm"></i> <!-- Cambiar ícono -->
                                    </div>
                                </a>

                                <!-- Compartir -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="action-btn group" title="Compartir">
                                        <div class="w-8 h-8 rounded-lg bg-purple-50 hover:bg-purple-100 border border-purple-200 group-hover:border-purple-300 flex items-center justify-center transition-all duration-200">
                                            <i class="fas fa-share-alt text-purple-600 text-sm"></i>
                                        </div>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                                        <a href="javascript:void(0);" onclick="shareCertificate('{{ $certificate->getFormattedCertificateNumber() }}', '{{ $certificate->course->title }}', '{{ $certificate->verification_url }}')" class="share-option flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fab fa-whatsapp text-green-500 mr-3 text-base"></i>
                                            Compartir por WhatsApp
                                        </a>
                                        <a href="mailto:?subject=Certificado: {{ $certificate->course->title }}&body=Verifica mi certificado: {{ $certificate->verification_url }}" class="share-option flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-envelope text-red-500 mr-3 text-base"></i>
                                            Compartir por Email
                                        </a>
                                        <button onclick="copyLink('{{ $certificate->verification_url }}')" class="share-option w-full text-left flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-copy text-blue-500 mr-3 text-base"></i>
                                            Copiar enlace
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pie de tabla -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Mostrando <span class="font-semibold">{{ $certificates->firstItem() }}</span> -
                        <span class="font-semibold">{{ $certificates->lastItem() }}</span> de
                        <span class="font-semibold">{{ $certificates->total() }}</span> certificados
                    </div>
                    <div>
                        {{ $certificates->links() }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Estado vacío -->
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-300 py-16 px-6 text-center animate-fade-in">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
                    <i class="fas fa-certificate text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">No tienes certificados aún</h3>
                <p class="text-gray-600 mb-8">
                    Completa cursos para obtener certificados que acrediten tus conocimientos y habilidades.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('student.my-courses') }}"
                       class="inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-book mr-2"></i>
                        Ver mis cursos
                    </a>
                    <a href="{{ route('cursos') }}" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm hover:shadow-md">
                        <i class="fas fa-search mr-2"></i>
                        Explorar más cursos
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para compartir -->
    <div id="shareModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-md mx-4 p-6 transform transition-transform duration-300 scale-95">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Compartir certificado</h3>
                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="shareContent" class="space-y-4">
                <!-- Contenido dinámico -->
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeShareModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                    Cancelar
                </button>
                <button id="confirmShare" class="px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-all duration-200">
                    Compartir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script para funcionalidades de certificados -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar filtros
        initFilters();

        // Inicializar búsqueda
        initSearch();

        // Inicializar botones de copiar
        initCopyButtons();
    });

    // Funciones de filtrado
    function initFilters() {
        const filterOptions = document.querySelectorAll('.filter-option');
        let currentFilter = 'newest';

        filterOptions.forEach(option => {
            option.addEventListener('click', function() {
                const filterValue = this.dataset.sort;

                // Actualizar visualmente
                filterOptions.forEach(opt => {
                    opt.querySelector('.fa-check').style.display = 'none';
                });
                this.querySelector('.fa-check').style.display = 'block';

                // Aplicar filtro
                currentFilter = filterValue;
                sortCertificates(filterValue);
            });
        });

        // Establecer filtro inicial
        filterOptions[0].querySelector('.fa-check').style.display = 'block';
    }

    function sortCertificates(sortType) {
        const container = document.querySelector('.divide-y');
        const items     = Array.from(document.querySelectorAll('.certificate-item'));

        items.sort((a, b) => {
            switch(sortType) {
                case 'newest':
                    return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                case 'oldest':
                    return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                case 'name':
                    return a.dataset.name.localeCompare(b.dataset.name);
                default:
                    return 0;
            }
        });

        // Reordenar elementos
        container.innerHTML = '';
        items.forEach(item => container.appendChild(item));
    }

    // Funciones de búsqueda
    function initSearch() {
        const searchInput = document.getElementById('search-certificates');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.certificate-item');

            items.forEach(item => {
                const courseName = item.dataset.name;
                const certificateCode = item.querySelector('.certificate-code').textContent.toLowerCase();

                if (courseName.includes(searchTerm) || certificateCode.includes(searchTerm)) {
                    item.style.display = '';
                    item.classList.add('animate-fade-in');
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Funciones de copiado
    function initCopyButtons() {
        // Los botones ya tienen onclick definido en el HTML
    }

    function copyToClipboard(text, button) {
        navigator.clipboard.writeText(text).then(() => {
            // Feedback visual
            const originalIcon = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check text-green-600 text-xs"></i>';
            button.classList.remove('text-gray-400');
            button.classList.add('text-green-600');

            setTimeout(() => {
                button.innerHTML = originalIcon;
                button.classList.remove('text-green-600');
                button.classList.add('text-gray-400');
            }, 2000);

            // Mostrar toast de confirmación
            showToast('Número copiado al portapapeles', 'success');
        }).catch(err => {
            console.error('Error al copiar:', err);
            showToast('Error al copiar', 'error');
        });
    }

    function copyLink(link) {
        copyToClipboard(link, event.target);
    }

    // Funciones para compartir
    function shareCertificate(certNumber, courseTitle, verificationUrl) {
        const modal         = document.getElementById('shareModal');
        const shareContent  = document.getElementById('shareContent');

        // Generar contenido del modal
        shareContent.innerHTML = `
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 border border-blue-200 flex items-center justify-center mr-4">
                        <i class="fas fa-certificate text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">${courseTitle}</h4>
                        <p class="text-sm text-gray-600 mt-1">N°: ${certNumber}</p>
                        <p class="text-xs text-gray-500 mt-2">Verifica en: ${verificationUrl}</p>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mensaje personalizado</label>
                <textarea id="shareMessage" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Añade un mensaje personalizado...">¡Mira mi certificado de ${courseTitle}! Verifícalo en: ${verificationUrl}</textarea>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700 mb-2">Compartir en:</p>
                <div class="flex space-x-3">
                    <button onclick="shareViaWhatsApp()" class="flex-1 flex flex-col items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-green-50 hover:border-green-300 transition-all duration-200">
                        <i class="fab fa-whatsapp text-green-500 text-2xl mb-1"></i>
                        <span class="text-xs text-gray-600">WhatsApp</span>
                    </button>
                    <button onclick="shareViaEmail()" class="flex-1 flex flex-col items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                        <i class="fas fa-envelope text-red-500 text-2xl mb-1"></i>
                        <span class="text-xs text-gray-600">Email</span>
                    </button>
                    <button onclick="shareViaLinkedIn()" class="flex-1 flex flex-col items-center justify-center p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                        <i class="fab fa-linkedin text-blue-500 text-2xl mb-1"></i>
                        <span class="text-xs text-gray-600">LinkedIn</span>
                    </button>
                </div>
            </div>
        `;

        // Actualizar botón de confirmar
        document.getElementById('confirmShare').onclick = function() {
            shareViaWhatsApp();
        };

        // Mostrar modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.bg-white').classList.remove('scale-95');
            modal.querySelector('.bg-white').classList.add('scale-100');
        }, 10);
    }

    function closeShareModal() {
        const modal = document.getElementById('shareModal');
        modal.querySelector('.bg-white').classList.remove('scale-100');
        modal.querySelector('.bg-white').classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function shareViaWhatsApp() {
        const message = document.getElementById('shareMessage').value;
        const encodedMessage = encodeURIComponent(message);
        window.open(`https://wa.me/?text=${encodedMessage}`, '_blank');
        closeShareModal();
        showToast('Compartiendo por WhatsApp...', 'success');
    }

    function shareViaEmail() {
        const message = document.getElementById('shareMessage').value;
        const subject = 'Mi certificado de IPF CONSULTORES SAC';
        const mailtoLink = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(message)}`;
        window.location.href = mailtoLink;
        closeShareModal();
    }

    function shareViaLinkedIn() {
        const message = document.getElementById('shareMessage').value;
        const url = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(message.split('\n').pop());
        window.open(url, '_blank', 'width=600,height=400');
        closeShareModal();
        showToast('Compartiendo en LinkedIn...', 'success');
    }

    // Funciones auxiliares
    function showToast(message, type = 'info') {
        // Crear toast
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white font-medium text-sm z-50 animate-slide-in ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Remover después de 3 segundos
        setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.classList.add('transition-opacity');
            toast.classList.add('duration-300');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Exportar funciones globales si es necesario
    window.certificates = {
        share: shareCertificate,
        copy: copyToClipboard,
        search: function(term) {
            document.getElementById('search-certificates').value = term;
            document.getElementById('search-certificates').dispatchEvent(new Event('input'));
        }
    };
</script>

<style>
    /* Estilos específicos para certificados */
    .certificate-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .certificate-item:hover {
        transform: translateX(4px);
        background-color: rgba(59, 130, 246, 0.03) !important;
    }

    .action-btn {
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .share-option {
        transition: all 0.2s ease;
    }

    .share-option:hover {
        background-color: #f9fafb;
        transform: translateX(2px);
    }

    /* Animación para nuevos certificados */
    @keyframes slideInRight {
        from {
            transform: translateX(20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .certificate-item:nth-child(odd) {
        animation: slideInRight 0.5s ease-out;
    }

    .certificate-item:nth-child(even) {
        animation: slideInRight 0.5s ease-out 0.1s backwards;
    }

    /* Estilo para código de certificado */
    .certificate-code {
        font-family: 'Courier New', monospace;
        letter-spacing: 0.5px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .certificate-item .grid > div {
            margin-bottom: 0.5rem;
        }

        .certificate-item .col-span-4 {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }
    }
</style>
@endsection
