<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $enterprise->favicon_path ?? asset('favicon.ico') }}">
    <title>Verificación de Certificado - {{ $enterprise->company_name ?? 'IPF CONSULTORES SAC' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
    <script src="{{ asset('js/tailwindcss.js') }}"></script>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .container {
                max-width: 100% !important;
                padding: 0 !important;
            }
            .shadow-lg, .shadow-xl {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-gray-50 to-blue-50/40 min-h-screen text-slate-800 antialiased selection:bg-blue-600 selection:text-white pb-12">

    <!-- Top Navigation Bar -->
    <header class="no-print bg-white/80 backdrop-blur-md border-b border-gray-200/80 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <span class="font-bold text-gray-900 tracking-tight text-base sm:text-lg block leading-tight">{{ $enterprise->company_name ?? 'IPF CONSULTORES SAC' }}</span>
                    <span class="text-xs text-gray-500 font-medium tracking-wide uppercase">Portal de Validación Oficial</span>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ url('/') }}" class="text-xs sm:text-sm font-semibold text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-1.5">
                    <i class="bi bi-house-door"></i>
                    <span class="hidden sm:inline">Inicio</span>
                </a>
                @auth
                    <a href="{{ route('student.certificates.index') }}" class="text-xs sm:text-sm font-semibold text-blue-600 hover:text-blue-700 px-3 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors flex items-center gap-1.5">
                        <i class="bi bi-award"></i>
                        <span>Mis Certificados</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 pt-8 sm:pt-10">
        
        <!-- Page Title -->
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100/80 text-blue-800 border border-blue-200 mb-3 shadow-sm">
                <i class="bi bi-patch-check-fill text-blue-600"></i>
                Sistema de Consulta y Autenticidad Curricular
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                Verificación de Certificado
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-2 max-w-xl mx-auto">
                Valida en tiempo real la legitimidad académica, vigencia anual y registro oficial de las certificaciones expedidas.
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/70 border border-gray-200/80 overflow-hidden transition-all duration-300">
            
            @php
                $statusType = $status ?? ($valid ? 'valid' : ($isExpired ?? false ? 'expired' : 'not_found'));
            @endphp

            {{-- ══════════════════════════════════════════════════════════════════════════════ --}}
            {{-- ESTADO 1: CERTIFICADO VÁLIDO Y VIGENTE                                         --}}
            {{-- ══════════════════════════════════════════════════════════════════════════════ --}}
            @if($statusType === 'valid' && isset($certificate))
                <!-- Banner de Estado: VIGENTE -->
                <div class="px-6 sm:px-8 py-6 bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-600 text-white relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center flex-shrink-0 shadow-inner border border-white/30 text-white">
                                <i class="bi bi-patch-check-fill text-3xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-xl sm:text-2xl font-black tracking-tight">CERTIFICADO VÁLIDO Y VIGENTE</h2>
                                </div>
                                <p class="text-xs sm:text-sm text-emerald-100 font-medium mt-0.5">
                                    Documento auténtico y registrado oficialmente • Vigencia anual activa
                                </p>
                            </div>
                        </div>
                        <div class="sm:text-right border-t sm:border-t-0 border-white/20 pt-3 sm:pt-0">
                            <div class="text-[11px] uppercase tracking-wider text-emerald-100 font-semibold">Fecha de Consulta</div>
                            <div class="text-sm font-bold font-mono text-white">{{ $verification_date ?? date('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-8">

                    <!-- Panel de Validación de Vigencia -->
                    <div class="bg-gradient-to-br from-emerald-50/80 via-teal-50/50 to-white rounded-xl p-5 sm:p-6 border border-emerald-200/90 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-emerald-200/70 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                    <i class="bi bi-shield-fill-check"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">Validación de Vigencia Académica</h3>
                                    <p class="text-xs text-gray-600">Este certificado cumple con el periodo estándar de validez anual (1 año)</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-emerald-200 animate-pulse"></span>
                                    VIGENTE
                                </span>
                            </div>
                        </div>

                        <!-- Metadatos de Vigencia -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
                            <div class="bg-white/90 rounded-lg p-3.5 border border-emerald-100 shadow-sm">
                                <span class="text-xs text-gray-500 font-medium block">Fecha de Emisión:</span>
                                <span class="text-sm sm:text-base font-bold text-gray-900 block mt-0.5">
                                    {{ $certificate->issue_date->format('d/m/Y') }}
                                </span>
                                <span class="text-[11px] text-gray-500 capitalize">
                                    {{ $certificate->getFormattedIssueDate() }}
                                </span>
                            </div>

                            <div class="bg-white/90 rounded-lg p-3.5 border border-emerald-100 shadow-sm">
                                <span class="text-xs text-gray-500 font-medium block">Fecha de Vencimiento:</span>
                                <span class="text-sm sm:text-base font-bold text-emerald-800 block mt-0.5">
                                    {{ $certificate->expiry_date ? $certificate->expiry_date->format('d/m/Y') : '1 año desde emisión' }}
                                </span>
                                <span class="text-[11px] text-emerald-700 capitalize">
                                    {{ $certificate->getFormattedExpiryDate() }}
                                </span>
                            </div>

                            <div class="bg-white/90 rounded-lg p-3.5 border border-emerald-100 shadow-sm">
                                <span class="text-xs text-gray-500 font-medium block">Tiempo de Validez:</span>
                                <span class="text-sm sm:text-base font-bold text-gray-900 block mt-0.5">
                                    1 Año de Vigencia
                                </span>
                                @if($certificate->days_remaining !== null)
                                    <span class="text-[11px] {{ $certificate->days_remaining <= 30 ? 'text-amber-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $certificate->days_remaining > 0 ? "Quedan {$certificate->days_remaining} días restantes" : 'Expira hoy' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Certificado y Participante -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna 1: Certificado -->
                        <div class="bg-slate-50/80 rounded-xl p-6 border border-gray-200/80 space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-blue-700 flex items-center gap-2 border-b border-gray-200 pb-2">
                                <i class="bi bi-file-earmark-text text-base"></i>
                                Datos del Certificado
                            </h4>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Número de Certificado:</span>
                                <p class="font-mono text-base sm:text-lg font-extrabold text-gray-900 mt-0.5">
                                    {{ $certificate->getFormattedCertificateNumber() }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Código Único de Verificación:</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="font-mono text-xs sm:text-sm font-bold bg-white px-3 py-1.5 rounded-lg border border-gray-300 text-gray-800 break-all select-all">
                                        {{ $certificate->certificate_code }}
                                    </p>
                                    <button onclick="copyToClipboard('{{ $certificate->certificate_code }}', this)" 
                                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-white rounded-lg border border-transparent hover:border-gray-200 transition-colors" 
                                            title="Copiar código">
                                        <i class="bi bi-clipboard text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Duración:</span>
                                    <p class="text-sm font-bold text-gray-900 mt-0.5">
                                        {{ number_format($certificate->total_hours, 1) }} hrs lectivas
                                    </p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Modalidad:</span>
                                    <p class="text-sm font-bold text-gray-900 mt-0.5">
                                        Virtual / Aprobado
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Columna 2: Participante y Curso -->
                        <div class="bg-slate-50/80 rounded-xl p-6 border border-gray-200/80 space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-blue-700 flex items-center gap-2 border-b border-gray-200 pb-2">
                                <i class="bi bi-person-badge text-base"></i>
                                Participante Acreditado
                            </h4>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Nombre Completo:</span>
                                <p class="text-lg sm:text-xl font-extrabold text-gray-900 mt-0.5 leading-snug">
                                    {{ $certificate->user->names }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Curso Completado y Aprobado:</span>
                                <p class="text-sm sm:text-base font-bold text-blue-900 mt-0.5">
                                    {{ $certificate->course->title }}
                                </p>
                            </div>

                            @if($certificate->course->instructor)
                            <div>
                                <span class="text-xs text-gray-500 font-medium">Instructor Titular:</span>
                                <p class="text-xs sm:text-sm font-semibold text-gray-800 mt-0.5">
                                    {{ $certificate->course->instructor->names }} 
                                    @if($certificate->course->instructor->profession)
                                        <span class="text-gray-500 font-normal">({{ $certificate->course->instructor->profession }})</span>
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Entidad Emisora -->
                    <div class="bg-gradient-to-r from-gray-50 to-slate-100 rounded-xl p-5 border border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-blue-700 text-white flex items-center justify-center font-black text-xl shadow-md flex-shrink-0">
                                IPF
                            </div>
                            <div>
                                <h5 class="text-base font-bold text-gray-900">{{ $enterprise->company_name ?? 'IPF CONSULTORES SAC' }}</h5>
                                <p class="text-xs text-gray-600">Entidad capacitadora autorizada para certificación profesional</p>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mt-1">
                                    <span><strong>RUC:</strong> {{ $enterprise->ruc ?? '20600000000' }}</span>
                                    <span>•</span>
                                    <span><strong>Representante:</strong> {{ $enterprise->legal_representative ?? 'Gerencia General' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="no-print pt-2 flex flex-wrap items-center justify-center gap-3">
                        <button onclick="window.print()" 
                                class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md shadow-gray-900/10 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-printer"></i>
                            <span>Imprimir Verificación</span>
                        </button>

                        <a href="{{ route('student.certificates.download-exact', $certificate->id) }}" 
                           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md shadow-blue-600/20 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-download"></i>
                            <span>Descargar Certificado PDF</span>
                        </a>

                        <button onclick="copyVerificationLink()" 
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-link-45deg text-base"></i>
                            <span>Copiar Enlace de Verificación</span>
                        </button>
                    </div>

                </div>

            {{-- ══════════════════════════════════════════════════════════════════════════════ --}}
            {{-- ESTADO 2: CERTIFICADO AUTÉNTICO PERO CON VIGENCIA EXPIRADA                     --}}
            {{-- ══════════════════════════════════════════════════════════════════════════════ --}}
            @elseif($statusType === 'expired' && isset($certificate))
                <!-- Banner de Estado: EXPIRADO -->
                <div class="px-6 sm:px-8 py-6 bg-gradient-to-r from-amber-600 via-orange-600 to-rose-600 text-white relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center flex-shrink-0 shadow-inner border border-white/30 text-white">
                                <i class="bi bi-clock-history text-3xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-black tracking-tight">CERTIFICADO AUTÉNTICO - VIGENCIA EXPIRADA</h2>
                                <p class="text-xs sm:text-sm text-amber-100 font-medium mt-0.5">
                                    Documento registrado en IPF CONSULTORES SAC • Periodo de validez de 1 año culminado
                                </p>
                            </div>
                        </div>
                        <div class="sm:text-right border-t sm:border-t-0 border-white/20 pt-3 sm:pt-0">
                            <div class="text-[11px] uppercase tracking-wider text-amber-100 font-semibold">Fecha de Consulta</div>
                            <div class="text-sm font-bold font-mono text-white">{{ $verification_date ?? date('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-8">

                    <!-- Alerta de Expiración de Vigencia -->
                    <div class="bg-gradient-to-br from-amber-50 via-orange-50/50 to-white rounded-xl p-5 sm:p-6 border border-amber-300 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-amber-200 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">Estado de la Vigencia Institucional</h3>
                                    <p class="text-xs text-gray-600">Este certificado superó el plazo máximo de validez de un (1) año</p>
                                </div>
                            </div>
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-600 text-white shadow-sm">
                                    <i class="bi bi-clock-history"></i>
                                    VIGENCIA EXPIRADA
                                </span>
                            </div>
                        </div>

                        <!-- Metadatos de Vigencia Expirada -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
                            <div class="bg-white/90 rounded-lg p-3.5 border border-amber-200 shadow-sm">
                                <span class="text-xs text-gray-500 font-medium block">Fecha de Emisión:</span>
                                <span class="text-sm sm:text-base font-bold text-gray-900 block mt-0.5">
                                    {{ $certificate->issue_date->format('d/m/Y') }}
                                </span>
                                <span class="text-[11px] text-gray-500 capitalize">
                                    {{ $certificate->getFormattedIssueDate() }}
                                </span>
                            </div>

                            <div class="bg-white/90 rounded-lg p-3.5 border border-rose-200 shadow-sm">
                                <span class="text-xs text-gray-500 font-medium block">Fecha de Caducidad:</span>
                                <span class="text-sm sm:text-base font-bold text-rose-700 block mt-0.5">
                                    {{ $certificate->expiry_date ? $certificate->expiry_date->format('d/m/Y') : 'N/A' }}
                                </span>
                                <span class="text-[11px] text-rose-600 capitalize">
                                    {{ $certificate->getFormattedExpiryDate() }}
                                </span>
                            </div>

                            <div class="bg-white/90 rounded-lg p-3.5 border border-amber-200 shadow-sm">
                                <span class="text-xs text-gray-500 font-medium block">Condición:</span>
                                <span class="text-sm sm:text-base font-bold text-amber-800 block mt-0.5">
                                    Validez Anual Culminada
                                </span>
                                @if($certificate->days_remaining !== null)
                                    <span class="text-[11px] text-rose-600 font-medium">
                                        Expiró hace {{ abs($certificate->days_remaining) }} días
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Nota institucional sobre expiración -->
                        <div class="mt-4 p-4 rounded-lg bg-amber-100/70 border border-amber-200 text-xs sm:text-sm text-amber-900 space-y-1">
                            <p class="font-bold flex items-center gap-1.5">
                                <i class="bi bi-info-circle-fill text-amber-700"></i>
                                Información para empleadores y auditores:
                            </p>
                            <p class="text-amber-800 leading-relaxed">
                                Este certificado fue otorgado de manera legítima tras la culminación satisfactoria del curso. Sin embargo, en concordancia con los estándares de actualización continua y la normativa laboral vigente, la vigencia curricular es de <strong>1 año calendario</strong>. Para acreditar competencias activas, el participante debe cursar una actualización o solicitar una revalidación oficial.
                            </p>
                        </div>
                    </div>

                    <!-- Datos del Certificado y Participante -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Columna 1 -->
                        <div class="bg-slate-50/80 rounded-xl p-6 border border-gray-200/80 space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2 border-b border-gray-200 pb-2">
                                <i class="bi bi-file-earmark-text text-base"></i>
                                Registro del Certificado
                            </h4>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Número de Certificado:</span>
                                <p class="font-mono text-base sm:text-lg font-bold text-gray-900 mt-0.5">
                                    {{ $certificate->getFormattedCertificateNumber() }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Código Único de Verificación:</span>
                                <p class="font-mono text-xs sm:text-sm font-bold bg-white px-3 py-1.5 rounded-lg border border-gray-300 text-gray-800 break-all mt-1">
                                    {{ $certificate->certificate_code }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Duración Registrada:</span>
                                    <p class="text-sm font-bold text-gray-900 mt-0.5">
                                        {{ number_format($certificate->total_hours, 1) }} hrs
                                    </p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 font-medium">Estado de Emisión:</span>
                                    <p class="text-sm font-bold text-emerald-700 mt-0.5">
                                        Legítimo en BD
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Columna 2 -->
                        <div class="bg-slate-50/80 rounded-xl p-6 border border-gray-200/80 space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2 border-b border-gray-200 pb-2">
                                <i class="bi bi-person-badge text-base"></i>
                                Participante Acreditado
                            </h4>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Nombre Completo:</span>
                                <p class="text-lg sm:text-xl font-bold text-gray-900 mt-0.5">
                                    {{ $certificate->user->names }}
                                </p>
                            </div>

                            <div>
                                <span class="text-xs text-gray-500 font-medium">Curso:</span>
                                <p class="text-sm sm:text-base font-bold text-gray-900 mt-0.5">
                                    {{ $certificate->course->title }}
                                </p>
                            </div>

                            @if($certificate->course->instructor)
                            <div>
                                <span class="text-xs text-gray-500 font-medium">Instructor:</span>
                                <p class="text-xs sm:text-sm font-semibold text-gray-800 mt-0.5">
                                    {{ $certificate->course->instructor->names }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Entidad Emisora -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-700 text-white flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0">
                                IPF
                            </div>
                            <div>
                                <h5 class="text-sm sm:text-base font-bold text-gray-900">{{ $enterprise->company_name ?? 'IPF CONSULTORES SAC' }}</h5>
                                <p class="text-xs text-gray-600">RUC: {{ $enterprise->ruc ?? 'No disponible' }} • Para revalidación contactar a: soporte@ipfconsultores.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="no-print pt-2 flex flex-wrap items-center justify-center gap-3">
                        <button onclick="window.print()" 
                                class="px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md transition-all flex items-center gap-2">
                            <i class="bi bi-printer"></i>
                            <span>Imprimir Constancia</span>
                        </button>

                        <a href="{{ route('student.certificates.download-exact', $certificate->id) }}" 
                           class="px-5 py-2.5 bg-slate-600 hover:bg-slate-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md transition-all flex items-center gap-2">
                            <i class="bi bi-download"></i>
                            <span>Descargar Certificado Original</span>
                        </a>

                        <button onclick="copyVerificationLink()" 
                                class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md transition-all flex items-center gap-2">
                            <i class="bi bi-link-45deg text-base"></i>
                            <span>Copiar Enlace</span>
                        </button>
                    </div>

                </div>

            {{-- ══════════════════════════════════════════════════════════════════════════════ --}}
            {{-- ESTADO 3: CERTIFICADO NO ENCONTRADO O CÓDIGO INVÁLIDO                          --}}
            {{-- ══════════════════════════════════════════════════════════════════════════════ --}}
            @else
                <!-- Banner de Estado: NO VÁLIDO -->
                <div class="px-6 sm:px-8 py-6 bg-gradient-to-r from-rose-600 via-red-600 to-red-700 text-white relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center flex-shrink-0 shadow-inner border border-white/30 text-white">
                                <i class="bi bi-x-octagon-fill text-3xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl sm:text-2xl font-black tracking-tight">CERTIFICADO NO VÁLIDO</h2>
                                <p class="text-xs sm:text-sm text-rose-100 font-medium mt-0.5">
                                    {{ $message ?? 'El código ingresado no corresponde a ningún certificado registrado.' }}
                                </p>
                            </div>
                        </div>
                        <div class="sm:text-right border-t sm:border-t-0 border-white/20 pt-3 sm:pt-0">
                            <div class="text-[11px] uppercase tracking-wider text-rose-100 font-semibold">Fecha de Consulta</div>
                            <div class="text-sm font-bold font-mono text-white">{{ $verification_date ?? date('d/m/Y H:i:s') }}</div>
                        </div>
                    </div>
                </div>

                <div class="p-8 sm:p-12 text-center max-w-2xl mx-auto space-y-6">
                    <div class="w-20 h-20 mx-auto bg-rose-100 text-rose-600 rounded-3xl flex items-center justify-center shadow-inner">
                        <i class="bi bi-shield-x text-4xl"></i>
                    </div>

                    <div>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
                            Código No Encontrado o Inválido
                        </h3>
                        @if(!empty($searchCode))
                            <p class="font-mono text-sm font-bold bg-gray-100 px-4 py-2 rounded-lg inline-block text-gray-700 border border-gray-200 mt-2">
                                "{{ $searchCode }}"
                            </p>
                        @endif
                        <p class="text-sm text-gray-600 mt-3 leading-relaxed">
                            No se encontró ningún registro oficial que coincida con el código suministrado. Verifica que no existan errores de digitación o que el código QR haya sido escaneado correctamente.
                        </p>
                    </div>

                    <!-- Cuadro de Contacto y Asistencia -->
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 text-left space-y-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                            <i class="bi bi-headset text-base text-blue-600"></i>
                            Canales de Atención Oficial
                        </h4>
                        <p class="text-xs sm:text-sm text-gray-600">
                            Si eres el participante o la empresa contratante y consideras que esto se debe a una inconsistencia de registro, ponte en contacto con nuestro equipo de soporte:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-envelope-fill text-blue-600"></i>
                                <span>soporte@ipfconsultores.com</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="bi bi-telephone-fill text-emerald-600"></i>
                                <span>+51 999 999 999</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulario para Consultar Otro Código -->
            <div class="no-print bg-slate-50 border-t border-gray-200/90 px-6 sm:px-8 py-6">
                <div class="max-w-xl mx-auto text-center">
                    <label for="certificate_code_input" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                        ¿Deseas verificar otro certificado?
                    </label>
                    <form action="{{ route('verify.certificate') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="bi bi-upc-scan text-sm"></i>
                            </div>
                            <input type="text" 
                                   id="certificate_code_input" 
                                   name="code" 
                                   placeholder="Ingresa el código (ej. CERT-ABCD12-20260320)" 
                                   value="{{ $searchCode ?? '' }}"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 text-xs sm:text-sm font-mono border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none uppercase bg-white">
                        </div>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-1.5">
                            <i class="bi bi-search"></i>
                            <span>Verificar</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pie del Sistema de Verificación -->
            <div class="px-6 sm:px-8 py-4 bg-gray-100/70 border-t border-gray-200 text-center sm:flex sm:items-center sm:justify-between text-xs text-gray-500">
                <div class="flex items-center justify-center sm:justify-start gap-1.5 mb-1 sm:mb-0">
                    <i class="bi bi-shield-lock-fill text-blue-600"></i>
                    <span>Verificación criptográfica segura • {{ $enterprise->company_name ?? 'IPF CONSULTORES SAC' }}</span>
                </div>
                <div>
                    <span>Registro Oficial en Base de Datos Central</span>
                </div>
            </div>

        </div>

        <!-- Nota Legal al Pie -->
        <footer class="mt-8 text-center text-xs text-gray-400 max-w-2xl mx-auto space-y-2">
            <p>
                Los certificados emitidos por {{ $enterprise->company_name ?? 'IPF CONSULTORES SAC' }} cuentan con una vigencia anual de un (1) año calendario desde su fecha de expedición, conforme a los programas de actualización continua laboral.
            </p>
            <p class="text-[11px] text-gray-400">
                © {{ date('Y') }} {{ $enterprise->company_name ?? 'IPF CONSULTORES SAC' }}. Todos los derechos reservados.
            </p>
        </footer>

    </main>

    <!-- Toast Notification for Copy -->
    <div id="copyToast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
        <div class="bg-gray-900 text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-2.5 text-xs sm:text-sm">
            <i class="bi bi-check-circle-fill text-emerald-400 text-base"></i>
            <span id="toastMessage">Copiado al portapapeles</span>
        </div>
    </div>

    <script>
    function showToast(message) {
        const toast = document.getElementById('copyToast');
        const text = document.getElementById('toastMessage');
        text.textContent = message;
        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 2500);
    }

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Código copiado al portapapeles');
        }).catch(() => {
            alert('Código: ' + text);
        });
    }

    function copyVerificationLink() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            showToast('Enlace de verificación copiado al portapapeles');
        }).catch(() => {
            prompt('Copia este enlace:', link);
        });
    }
    </script>
</body>
</html>
