@extends('layouts.student')
@section('title', 'Importar Usuarios')
@section('content')
<div class="container mx-auto px-4 py-4 sm:py-6">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-6">
            <a href="{{ route('company.list') }}" 
               class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors text-sm sm:text-base w-fit">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Importar usuarios desde Excel</h1>
        </div>

        <!-- Tarjeta de límite disponible -->
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-6 rounded-lg text-sm sm:text-base" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if(session('import_failures'))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-3 sm:p-4 mb-6 rounded-lg">
                <p class="font-bold text-yellow-800 text-sm sm:text-base mb-2">Errores de validación en el archivo:</p>
                <div class="max-h-48 sm:max-h-64 overflow-y-auto text-xs sm:text-sm">
                    @foreach(session('import_failures') as $failure)
                        <div class="text-yellow-700 mb-1">
                            • Fila {{ $failure->row() }}: 
                            @foreach($failure->errors() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800">Subir archivo Excel</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Selecciona un archivo con el formato correcto para importar usuarios</p>
        </div>

        <div class="p-4 sm:p-6">
            @if($hasAnyPackage)
                @php $slotsReached = $availableSlots <= 0; @endphp

                <div class="bg-gradient-to-r {{ $slotsReached ? 'from-red-50 to-red-100 border-red-300' : 'from-blue-50 to-indigo-50 border-blue-200' }} border rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-xs sm:text-sm font-medium {{ $slotsReached ? 'text-red-800' : 'text-blue-800' }} mb-1">
                                Espacios disponibles
                            </p>
                            <p class="text-2xl sm:text-3xl font-bold {{ $slotsReached ? 'text-red-900' : 'text-blue-900' }}">
                                {{ max(0, $availableSlots) }} / {{ $enrolledPackage?->seats_max ?? 0 }} usuarios
                            </p>
                            <p class="text-xs sm:text-sm text-gray-600 mt-2">
                                @if($slotsReached)
                                    Ha alcanzado el límite de su plan. Actualícelo para agregar más usuarios.
                                @else
                                    Puedes importar hasta {{ $availableSlots }} usuario(s) más según tu plan actual.
                                @endif
                            </p>
                        </div>
                        <div class="{{ $slotsReached ? 'bg-red-500' : 'bg-blue-600' }} p-3 sm:p-4 rounded-xl w-fit">
                            @if($slotsReached)
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg sm:rounded-xl p-4 sm:p-6 mb-6 border border-blue-200">
                    <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-blue-800 text-sm sm:text-base mb-2">Instrucciones para la importación:</h3>
                            <ul class="text-xs sm:text-sm text-blue-700 space-y-1 list-disc list-inside">
                                <li>Descarga la plantilla de ejemplo usando el botón "Descargar plantilla"</li>
                                <li>Completa los datos respetando el formato de cada columna</li>
                                <li>El DNI y CORREO deben ser únicos en el sistema</li>
                                <li>CÓDIGO PAIS: Usar +51 (Perú), +54 (Argentina), +56 (Chile), +591 (Bolivia), +593 (Ecuador), +598 (Uruguay)</li>
                                <li>Si no especificas código de país, se asignará +51 por defecto</li>
                                <li>La contraseña inicial será: <span class="font-mono bg-blue-100 px-2 py-1 rounded text-xs">P4$$w0rd#.</span></li>
                                <li>El archivo no debe exceder los 5MB</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form action="{{ route('company.import.process') }}" method="POST" enctype="multipart/form-data" x-data="importForm({{ $availableSlots }})" @submit.prevent="handleSubmit($event)" class="space-y-4 sm:space-y-6">
                    @csrf
                    
                    <div x-data="{ fileName: '' }" class="border-2 border-dashed border-gray-300 rounded-xl sm:rounded-2xl p-6 sm:p-8 hover:border-blue-500 transition-colors duration-200">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            
                            <div class="mt-3 sm:mt-4 flex flex-col items-center text-xs sm:text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-800 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500">
                                    <span>Selecciona un archivo</span>
                                    <input id="file-upload" name="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv" @change="fileName = $event.target.files[0]?.name || ''">
                                </label>
                                <p class="pl-1">o arrastra y suelta aquí</p>
                                <p class="text-xs text-gray-500 mt-2">Archivos soportados: .xlsx, .xls, .csv (Max 5MB)</p>
                            </div>
                            
                            <div x-show="fileName" class="mt-3 sm:mt-4 p-2 sm:p-3 bg-green-50 rounded-lg inline-flex items-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-xs sm:text-sm font-medium text-green-700" x-text="fileName"></span>
                            </div>
                        </div>
                    </div>

                    @error('file')
                        <p class="text-red-500 text-xs sm:text-sm mt-2">{{ $message }}</p>
                    @enderror

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 sm:gap-4 pt-4">
                        <a href="{{ route('company.import.template') }}" 
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Descargar plantilla
                        </a>
                        
                        <button type="submit"
                                class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 sm:px-8 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Importar usuarios
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6 text-center shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-200 mb-4">
                        <i class="fa-solid fa-lock text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-amber-900 mb-2">Se requiere un paquete corporativo</h3>
                    <p class="text-amber-800 text-sm mb-4">
                        Para poder importar o registrar colaboradores, debes adquirir un plan corporativo.<br>
                        Cada paquete determina la cantidad de asientos (usuarios) que puedes administrar en tu empresa.
                    </p>
                    <a href="/planes" class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                        Ver Planes
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Preview del formato -->
    <div class="mt-6 sm:mt-8 bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800">Vista previa del formato</h2>
        </div>
        <div class="p-4 sm:p-6">
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">DNI</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">NOMBRES</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">CORREO</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">CÓDIGO PAÍS</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">TELÉFONO</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">DIRECCIÓN</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">CARGO / PROFESIÓN</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 whitespace-nowrap">12345678</td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 whitespace-nowrap">JUAN PEREZ</td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 whitespace-nowrap">juan@ejemplo.com</td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 whitespace-nowrap">+51</td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 whitespace-nowrap">987654321</td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 whitespace-nowrap">Av. Principal 123</td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 whitespace-nowrap">DESARROLLADOR</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4 italic">* Los campos DNI, NOMBRES y CORREO son obligatorios</p>
        </div>
    </div>
</div>

<script>
    // ── Componente Alpine principal del formulario ────────────────────────────
    function importForm(availableSlots) {
        return {
            fileName: '',
            availableSlots: availableSlots,

            handleSubmit(event) {
                // 1. Bloquear si no quedan asientos
                if (this.availableSlots <= 0) {
                    this.showToast('Límite de usuarios alcanzado. Actualice su plan para continuar.', 'warning');
                    return;
                }

                // 2. Validar que se haya seleccionado un archivo
                const fileInput = document.getElementById('file-upload');
                const file = fileInput?.files[0];

                if (!file) {
                    this.showToast('Por favor, selecciona un archivo para importar.', 'error');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    this.showToast('El archivo no debe exceder los 5MB.', 'error');
                    return;
                }

                const ext = file.name.split('.').pop().toLowerCase();
                if (!['xlsx', 'xls', 'csv'].includes(ext)) {
                    this.showToast('Formato de archivo no válido. Use .xlsx, .xls o .csv.', 'error');
                    return;
                }

                // 3. Todo OK → enviar el formulario real
                event.target.submit();
            },

            showToast(message, type = 'success') {
                const colors = {
                    success: 'from-green-500 to-green-600',
                    error:   'from-red-500 to-red-600',
                    warning: 'from-amber-500 to-amber-600',
                };

                const icons = {
                    success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                    error:   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
                    warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
                };

                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 sm:top-6 sm:right-6 z-50 px-4 sm:px-6 py-3 sm:py-4 rounded-xl shadow-xl
                    opacity-0 -translate-y-2 transform transition-all duration-300 text-sm sm:text-base max-w-[90vw]
                    bg-gradient-to-r ${colors[type] ?? colors.success} text-white`;

                toast.innerHTML = `
                    <div class="flex items-center gap-2 sm:gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${icons[type] ?? icons.success}
                        </svg>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(toast);

                requestAnimationFrame(() => {
                    toast.classList.remove('opacity-0', '-translate-y-2');
                    toast.classList.add('opacity-100', 'translate-y-0');
                });

                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }, 3500);
            }
        };
    }
</script>
@endsection