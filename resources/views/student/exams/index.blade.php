@extends('layouts.student')
@section('title', 'Mis Exámenes')
@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Encabezado de la página -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Mis Exámenes</h1>
                <p class="text-gray-600 mt-2">Gestiona y realiza tus exámenes de certificación</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Estadísticas rápidas -->
                <div class="hidden sm:flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-sm text-gray-600">Aprobados: <span id="passed-count" class="font-semibold">0</span></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <span class="text-sm text-gray-600">Pendientes: <span id="pending-count" class="font-semibold">0</span></span>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 hover:shadow-sm">
                        <i class="fas fa-filter mr-2 text-gray-500"></i>
                        Filtrar
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-700">Filtrar por estado</h4>
                        </div>
                        <div class="py-1">
                            <button class="filter-btn w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-filter="all">
                                <span>Todos</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: block;"></i>
                            </button>
                            <button class="filter-btn w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-filter="pending">
                                <span>Pendientes</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: none;"></i>
                            </button>
                            <button class="filter-btn w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-filter="passed">
                                <span>Aprobados</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: none;"></i>
                            </button>
                            <button class="filter-btn w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-filter="failed">
                                <span>Reprobados</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: none;"></i>
                            </button>
                            <button class="filter-btn w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center justify-between" data-filter="expired">
                                <span>Expirados</span>
                                <i class="fas fa-check text-blue-600 text-xs" style="display: none;"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Buscar -->
                <div class="relative">
                    <input type="text" id="search-exams" placeholder="Buscar examen..." class="pl-10 pr-4 py-2.5 w-full sm:w-64 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-800">Total Exámenes</p>
                        <h3 class="text-2xl font-bold text-blue-900 mt-1" id="total-exams">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-blue-700 mt-3">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    Todos los exámenes disponibles
                </p>
            </div>

            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-800">Aprobados</p>
                        <h3 class="text-2xl font-bold text-emerald-900 mt-1" id="approved-exams">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-emerald-700 mt-3">
                    <i class="fas fa-trophy mr-1"></i>
                    Exámenes aprobados
                </p>
            </div>

            <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-800">Pendientes</p>
                        <h3 class="text-2xl font-bold text-amber-900 mt-1" id="pending-exams">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-amber-500/20 flex items-center justify-center">
                        <i class="fas fa-clock text-amber-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-amber-700 mt-3">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    Por realizar o completar
                </p>
            </div>

            <div class="bg-gradient-to-r from-rose-50 to-rose-100 border border-rose-200 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-rose-800">Intentos Restantes</p>
                        <h3 class="text-2xl font-bold text-rose-900 mt-1" id="remaining-attempts">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-rose-500/20 flex items-center justify-center">
                        <i class="fas fa-redo text-rose-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-rose-700 mt-3">
                    <i class="fas fa-sync-alt mr-1"></i>
                    Intentos disponibles totales
                </p>
            </div>
        </div>
    </div>

    <!-- Lista de Exámenes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Exámenes Pendientes -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-tasks mr-2 text-blue-600"></i>
                            Exámenes Disponibles
                        </h3>
                        <span class="text-sm font-medium text-blue-700 bg-blue-100 px-3 py-1 rounded-full" id="available-count">0</span>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 min-h-[400px] max-h-[600px] overflow-y-auto custom-scrollbar" id="available-exams-list">
                    <!-- Los exámenes se cargarán aquí via AJAX -->
                    <div class="px-6 py-12 text-center">
                        <div class="loading-spinner w-10 h-10 mx-auto mb-4"></div>
                        <p class="text-gray-600">Cargando exámenes disponibles...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de Intentos -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-emerald-100/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-2 text-emerald-600"></i>
                            Historial Reciente
                        </h3>
                        <a href="javascript:void(0)" onclick="showAllAttempts()" class="text-sm text-emerald-700 hover:text-emerald-800 font-medium">
                            Ver todo
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 min-h-[400px] max-h-[600px] overflow-y-auto custom-scrollbar" id="recent-attempts">
                    <!-- Los intentos se cargarán aquí via AJAX -->
                    <div class="px-6 py-12 text-center">
                        <div class="loading-spinner w-10 h-10 mx-auto mb-4"></div>
                        <p class="text-gray-600">Cargando historial...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exámenes Completados -->
    <div class="mt-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-purple-100/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-check-circle mr-2 text-purple-600"></i>
                    Exámenes Completados
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Curso</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Intento</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Puntaje</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="completed-exams-table">
                        <!-- Los exámenes completados se cargarán aquí via AJAX -->
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="loading-spinner w-10 h-10 mx-auto mb-4"></div>
                                <p class="text-gray-600">Cargando exámenes completados...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para ver todos los intentos -->
    <div id="allAttemptsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-4xl mx-4 p-6 max-h-[80vh] overflow-y-auto transform transition-transform duration-300 scale-95">
            <div class="flex justify-between items-center mb-6 sticky top-0 bg-white py-2">
                <h3 class="text-xl font-semibold text-gray-900">Todos mis intentos</h3>
                <button onclick="closeAllAttemptsModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="allAttemptsContent" class="space-y-4">
                <!-- Contenido se cargará aquí -->
            </div>
        </div>
    </div>

    <!-- Modal para comenzar examen -->
    <div id="startExamModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div class="bg-white rounded-2xl w-full max-w-md mx-4 p-6 transform transition-transform duration-300 scale-95">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2" id="examModalTitle">Comenzar Examen</h3>
                <p class="text-gray-600" id="examModalDescription"></p>
            </div>

            <div class="space-y-4 mb-6">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Duración:</span>
                    <span class="font-semibold text-gray-900" id="examModalDuration"></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Puntaje mínimo:</span>
                    <span class="font-semibold text-gray-900" id="examModalPassingScore"></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Intentos permitidos:</span>
                    <span class="font-semibold text-gray-900" id="examModalMaxAttempts"></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm text-gray-600">Tus intentos usados:</span>
                    <span class="font-semibold text-amber-600" id="examModalUsedAttempts"></span>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <h4 class="text-sm font-semibold text-amber-800 mb-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Instrucciones importantes
                </h4>
                <ul class="text-xs text-amber-700 space-y-1">
                    <li>• El tiempo comenzará cuando inicies el examen</li>
                    <li>• No podrás pausar el examen una vez iniciado</li>
                    <li>• Asegúrate de tener una conexión estable a internet</li>
                    <li>• No refresques la página durante el examen</li>
                </ul>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="closeStartExamModal()" class="px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                    Cancelar
                </button>
                <button id="confirmStartExam" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                    <i class="fas fa-play mr-2"></i>
                    Comenzar Examen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script para funcionalidades de exámenes -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos iniciales
    loadExamData();

    // Inicializar filtros
    initFilters();

    // Inicializar búsqueda
    initSearch();

    // Actualizar datos periódicamente
    setInterval(loadExamData, 30000);
});

// Variables globales
let currentExamId = null;
let examData = {
    available: [],
    recentAttempts: [],
    completed: []
};

// Cargar datos de exámenes
async function loadExamData() {
    try {
        // Cargar datos desde API
        const response = await axios.get('/api/student/exams');
        examData = response.data;

        // Actualizar estadísticas
        updateStats(examData.stats);

        // Actualizar listas
        updateAvailableExams(examData.available);
        updateRecentAttempts(examData.recentAttempts);
        updateCompletedExams(examData.completed);

    } catch (error) {
        console.error('Error loading exam data:', error);
        showToast('Error al cargar los exámenes', 'error');
    }
}

// Actualizar estadísticas
function updateStats(stats) {
    document.getElementById('total-exams').textContent = stats.total || 0;
    document.getElementById('approved-exams').textContent = stats.passed || 0;
    document.getElementById('pending-exams').textContent = stats.pending || 0;
    document.getElementById('remaining-attempts').textContent = stats.remainingAttempts || 0;
    document.getElementById('passed-count').textContent = stats.passed || 0;
    document.getElementById('pending-count').textContent = stats.pending || 0;
    document.getElementById('available-count').textContent = stats.available || 0;
}

// Actualizar exámenes disponibles
function updateAvailableExams(exams) {
    const container = document.getElementById('available-exams-list');

    if (exams.length === 0) {
        container.innerHTML = `
            <div class="px-6 py-12 text-center animate-fade-in">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">No hay exámenes pendientes</h4>
                <p class="text-gray-600 mb-6">Has completado todos los exámenes disponibles.</p>
                <a href="{{ route('student.my-courses') }}"
                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200">
                    <i class="fas fa-book mr-2"></i>
                    Ver mis cursos
                </a>
            </div>
        `;
        return;
    }

    container.innerHTML = exams.map(exam => `
        <div class="exam-item px-6 py-4 hover:bg-gray-50/80 transition-colors duration-200"
             data-id="${exam.id}"
             data-name="${exam.course_title.toLowerCase()}"
             data-status="${exam.status}">
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1 min-w-0">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-${exam.color || 'blue'}-100 to-${exam.color || 'blue'}-50 border border-${exam.color || 'blue'}-200 flex items-center justify-center mr-4">
                        <i class="fas fa-${exam.icon || 'file-alt'} text-${exam.color || 'blue'}-600 text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center">
                            <h4 class="font-semibold text-gray-900 text-sm lg:text-base truncate mr-2">${exam.course_title}</h4>
                            ${exam.has_previous_attempts ?
                                '<span class="flex-shrink-0 bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full">Reintento</span>' :
                                ''
                            }
                        </div>
                        <div class="flex items-center flex-wrap gap-2 mt-2">
                            <span class="inline-flex items-center text-xs text-gray-600">
                                <i class="fas fa-clock mr-1.5 text-gray-400"></i>
                                ${exam.duration} min
                            </span>
                            <span class="inline-flex items-center text-xs text-gray-600">
                                <i class="fas fa-bullseye mr-1.5 text-gray-400"></i>
                                Puntaje mínimo: ${exam.passing_score}/20
                            </span>
                            <span class="inline-flex items-center text-xs text-gray-600">
                                <i class="fas fa-redo mr-1.5 text-gray-400"></i>
                                Intentos: ${exam.attempts_used}/${exam.max_attempts}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="openStartExamModal(${exam.id})"
                            class="px-4 py-2 bg-gradient-to-r from-${exam.can_take ? 'blue' : 'gray'}-600 to-${exam.can_take ? 'blue' : 'gray'}-700 text-white text-sm font-medium rounded-lg hover:from-${exam.can_take ? 'blue' : 'gray'}-700 hover:to-${exam.can_take ? 'blue' : 'gray'}-800 transition-all duration-200 shadow-sm hover:shadow-md flex items-center ${!exam.can_take ? 'opacity-75 cursor-not-allowed' : ''}"
                            ${!exam.can_take ? 'disabled' : ''}>
                        ${exam.can_take ?
                            '<i class="fas fa-play mr-2"></i> Comenzar' :
                            '<i class="fas fa-lock mr-2"></i> No disponible'
                        }
                    </button>
                </div>
            </div>
            ${!exam.can_take && exam.reason ? `
                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-xs text-red-700 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        ${exam.reason}
                    </p>
                </div>
            ` : ''}
        </div>
    `).join('');
}

// Actualizar intentos recientes
function updateRecentAttempts(attempts) {
    const container = document.getElementById('recent-attempts');

    if (attempts.length === 0) {
        container.innerHTML = `
            <div class="px-6 py-12 text-center animate-fade-in">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-600">No hay intentos recientes</p>
            </div>
        `;
        return;
    }

    container.innerHTML = attempts.map(attempt => `
        <div class="attempt-item px-6 py-3 hover:bg-gray-50/80 transition-colors duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 text-sm truncate">${attempt.course_title}</h4>
                    <div class="flex items-center mt-1">
                        <span class="text-xs text-gray-600">Intento ${attempt.attempt_number}</span>
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-xs text-gray-600">${attempt.date_formatted}</span>
                    </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${attempt.passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}">
                        ${attempt.passed ?
                            '<i class="fas fa-check mr-1"></i> Aprobado' :
                            '<i class="fas fa-times mr-1"></i> Reprobado'
                        }
                    </span>
                </div>
            </div>
            <div class="mt-2 flex items-center justify-between">
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="h-full rounded-full ${attempt.passed ? 'bg-emerald-500' : 'bg-rose-500'}" style="width: ${(attempt.score / 20) * 100}%"></div>
                    </div>
                </div>
                <div class="ml-4">
                    <span class="text-sm font-bold ${attempt.passed ? 'text-emerald-700' : 'text-rose-700'}">${attempt.score}/20</span>
                </div>
            </div>
            ${attempt.certificate_id ? `
                <div class="mt-3 flex space-x-2">
                    <a href="/student/certificados/${attempt.certificate_id}"
                       class="flex-1 text-center px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg hover:bg-blue-100 transition-colors duration-200">
                        Ver Certificado
                    </a>
                </div>
            ` : ''}
        </div>
    `).join('');
}

// Actualizar exámenes completados
function updateCompletedExams(exams) {
    const container = document.getElementById('completed-exams-table');

    if (exams.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-600">No hay exámenes completados</p>
                </td>
            </tr>
        `;
        return;
    }

    container.innerHTML = exams.map(exam => `
        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-${exam.color || 'blue'}-100 to-${exam.color || 'blue'}-50 border border-${exam.color || 'blue'}-200 flex items-center justify-center mr-3">
                        <i class="fas fa-${exam.icon || 'file-alt'} text-${exam.color || 'blue'}-600 text-sm"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-900 text-sm">${exam.course_title}</div>
                        <div class="text-xs text-gray-500">Examen: ${exam.exam_title}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">${exam.date_formatted}</div>
                <div class="text-xs text-gray-500">${exam.time_formatted}</div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    Intento ${exam.attempt_number}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-16 bg-gray-200 rounded-full h-2 overflow-hidden mr-3">
                        <div class="h-full rounded-full ${exam.passed ? 'bg-emerald-500' : 'bg-rose-500'}" style="width: ${(exam.score / 20) * 100}%"></div>
                    </div>
                    <span class="text-sm font-bold ${exam.passed ? 'text-emerald-700' : 'text-rose-700'}">${exam.score}/20</span>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${exam.passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}">
                    ${exam.passed ?
                        '<i class="fas fa-check mr-1"></i> Aprobado' :
                        '<i class="fas fa-times mr-1"></i> Reprobado'
                    }
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                    <a href="/examen/resultado/${exam.attempt_id}"
                       class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                       title="Ver resultados">
                        <i class="fas fa-chart-bar text-sm"></i>
                    </a>
                    ${exam.certificate_id ? `
                        <a href="/student/certificados/${exam.certificate_id}"
                           class="p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors duration-200"
                           title="Ver certificado">
                            <i class="fas fa-certificate text-sm"></i>
                        </a>
                    ` : ''}
                    ${exam.can_retake ? `
                        <button onclick="openStartExamModal(${exam.exam_id})"
                                class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors duration-200"
                                title="Reintentar">
                            <i class="fas fa-redo text-sm"></i>
                        </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

// Funciones de filtrado
function initFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    let currentFilter = 'all';

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterValue = this.dataset.filter;

            // Actualizar visualmente
            filterButtons.forEach(btn => {
                btn.querySelector('.fa-check').style.display = 'none';
            });
            this.querySelector('.fa-check').style.display = 'block';

            // Aplicar filtro
            currentFilter = filterValue;
            filterExams(filterValue);
        });
    });
}

function filterExams(filterType) {
    const examItems = document.querySelectorAll('.exam-item');

    examItems.forEach(item => {
        const status = item.dataset.status;

        switch(filterType) {
            case 'all':
                item.style.display = '';
                break;
            case 'pending':
                item.style.display = status === 'pending' ? '' : 'none';
                break;
            case 'passed':
                item.style.display = status === 'passed' ? '' : 'none';
                break;
            case 'failed':
                item.style.display = status === 'failed' ? '' : 'none';
                break;
            case 'expired':
                item.style.display = status === 'expired' ? '' : 'none';
                break;
        }

        if (item.style.display !== 'none') {
            item.classList.add('animate-fade-in');
        }
    });
}

// Funciones de búsqueda
function initSearch() {
    const searchInput = document.getElementById('search-exams');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const items = document.querySelectorAll('.exam-item');

        items.forEach(item => {
            const examName = item.dataset.name;

            if (examName.includes(searchTerm)) {
                item.style.display = '';
                item.classList.add('animate-fade-in');
            } else {
                item.style.display = 'none';
            }
        });
    });
}

// Funciones del modal de inicio
function openStartExamModal(examId) {
    currentExamId = examId;
    const exam = examData.available.find(e => e.id == examId);

    if (!exam) {
        showToast('Examen no encontrado', 'error');
        return;
    }

    // Actualizar modal con datos del examen
    document.getElementById('examModalTitle').textContent = exam.course_title;
    document.getElementById('examModalDescription').textContent = exam.description || 'Completa este examen para obtener tu certificado';
    document.getElementById('examModalDuration').textContent = exam.duration + ' minutos';
    document.getElementById('examModalPassingScore').textContent = exam.passing_score + '/20';
    document.getElementById('examModalMaxAttempts').textContent = exam.max_attempts;
    document.getElementById('examModalUsedAttempts').textContent = exam.attempts_used;

    // Configurar botón de confirmación
    document.getElementById('confirmStartExam').onclick = function() {
        startExam(examId);
    };

    // Mostrar modal
    const modal = document.getElementById('startExamModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function closeStartExamModal() {
    const modal = document.getElementById('startExamModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
        currentExamId = null;
    }, 300);
}

// Iniciar examen
async function startExam(examId) {
    try {
        closeStartExamModal();

        // Mostrar carga
        showToast('Preparando examen...', 'info');

        // Redirigir a la página del examen
        window.location.href = `/examen/comenzar/${examId}`;

    } catch (error) {
        console.error('Error starting exam:', error);
        showToast('Error al iniciar el examen', 'error');
    }
}

// Funciones del modal de intentos
function showAllAttempts() {
    const modal = document.getElementById('allAttemptsModal');
    const content = document.getElementById('allAttemptsContent');

    // Obtener todos los intentos (simulado - en producción sería via API)
    const allAttempts = examData.recentAttempts;

    if (allAttempts.length === 0) {
        content.innerHTML = `
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-600">No hay intentos registrados</p>
            </div>
        `;
    } else {
        content.innerHTML = allAttempts.map(attempt => `
            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-gray-900">${attempt.course_title}</h4>
                        <p class="text-xs text-gray-600 mt-1">Intento ${attempt.attempt_number} • ${attempt.date_formatted}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${attempt.passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}">
                        ${attempt.passed ? 'Aprobado' : 'Reprobado'}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex-1 mr-4">
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="h-full rounded-full ${attempt.passed ? 'bg-emerald-500' : 'bg-rose-500'}" style="width: ${(attempt.score / 20) * 100}%"></div>
                        </div>
                    </div>
                    <div class="text-sm font-bold ${attempt.passed ? 'text-emerald-700' : 'text-rose-700'}">${attempt.score}/20</div>
                </div>
                ${attempt.certificate_id ? `
                    <div class="mt-3 flex justify-end">
                        <a href="/student/certificados/${attempt.certificate_id}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            Ver certificado
                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                ` : ''}
            </div>
        `).join('');
    }

    // Mostrar modal
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.querySelector('.bg-white').classList.remove('scale-95');
        modal.querySelector('.bg-white').classList.add('scale-100');
    }, 10);
}

function closeAllAttemptsModal() {
    const modal = document.getElementById('allAttemptsModal');
    modal.querySelector('.bg-white').classList.remove('scale-100');
    modal.querySelector('.bg-white').classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Funciones auxiliares
function showToast(message, type = 'info') {
    // Crear toast
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white font-medium text-sm z-50 animate-slide-in ${type === 'success' ? 'bg-emerald-600' : type === 'error' ? 'bg-rose-600' : 'bg-blue-600'}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    // Remover después de 3 segundos
    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Exportar funciones globales
window.examDashboard = {
    refresh: loadExamData,
    startExam: openStartExamModal,
    showAllAttempts: showAllAttempts,
    filter: filterExams
};
</script>

<style>
/* Estilos específicos para exámenes */
.exam-item {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.exam-item:hover {
    background-color: rgba(59, 130, 246, 0.03) !important;
    transform: translateX(2px);
}

.attempt-item {
    transition: all 0.2s ease;
}

.attempt-item:hover {
    background-color: rgba(16, 185, 129, 0.03) !important;
}

/* Animaciones */
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

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.exam-item:nth-child(odd) {
    animation: slideInRight 0.5s ease-out;
}

.exam-item:nth-child(even) {
    animation: slideInRight 0.5s ease-out 0.1s backwards;
}

/* Badge para intentos */
.attempt-badge {
    animation: pulse 2s ease-in-out infinite;
}

/* Barra de progreso animada */
.progress-bar-animated {
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive */
@media (max-width: 1024px) {
    .grid-cols-3 {
        grid-template-columns: 1fr;
    }

    .lg\:col-span-2 {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .flex-col {
        flex-direction: column;
        align-items: stretch;
    }

    .exam-item > div {
        flex-direction: column;
        align-items: stretch;
    }

    .exam-item .flex-shrink-0 {
        margin-top: 1rem;
        align-self: flex-end;
    }
}
</style>
@endsection
