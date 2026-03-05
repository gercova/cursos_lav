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
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl sm:rounded-2xl p-4 sm:p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-blue-800 mb-1">Espacios disponibles</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-900">{{ $enrolledPackage->seats_max }} usuarios</p>
                    <p class="text-xs sm:text-sm text-gray-600 mt-2">Puedes importar hasta {{ $enrolledPackage->seats_max }} usuarios según tu plan actual</p>
                </div>
                <div class="bg-blue-600 p-3 sm:p-4 rounded-xl w-fit">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Mostrar errores de sesión -->
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-6 rounded-lg text-sm sm:text-base" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Mostrar fallos de validación -->
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

    <!-- Panel principal -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <h2 class="text-base sm:text-lg font-semibold text-gray-800">Subir archivo Excel</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Selecciona un archivo con el formato correcto para importar usuarios</p>
        </div>

        <div class="p-4 sm:p-6">
            <!-- Instrucciones -->
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

            @if($hasAnyPackage)
                <!-- Formulario de importación -->
                <form action="{{ route('company.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
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
                                class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 sm:px-8 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $enrolledPackage->seats_max <= 0 ? 'disabled' : '' }}>
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            Importar usuarios
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-large text-red-800">
                                <i class="fa-solid fa-check mr-2"></i>Para poder registrar a tus colaboradores, al menos debes comprar un paquete.<br>
                                <i class="fa-solid fa-check mr-2"></i>Cada paquete incluye la cantidad de colaboradores que puedes registar como empresa.
                            </p>
                        </div>
                    </div>
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
@endsection

@section('scripts')
<script>
    // Script adicional para manejar el feedback visual del archivo seleccionado
    document.addEventListener('alpine:init', () => {
        Alpine.data('fileUpload', () => ({
            fileName: '',
            
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    // Validar tamaño (5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        this.showNotification('El archivo no debe exceder los 5MB', 'error');
                        event.target.value = '';
                        this.fileName = '';
                        return;
                    }
                    
                    // Validar extensión
                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!['xlsx', 'xls', 'csv'].includes(extension)) {
                        this.showNotification('Solo se permiten archivos Excel o CSV', 'error');
                        event.target.value = '';
                        this.fileName = '';
                        return;
                    }
                    
                    this.fileName = file.name;
                }
            },
            
            showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 sm:top-6 sm:right-6 z-50 px-4 sm:px-6 py-3 sm:py-4 rounded-xl shadow-xl transform transition-all duration-300 text-sm sm:text-base max-w-[90vw] ${
                    type === 'success'
                    ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
                    : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                            }
                        </svg>
                        <span class="font-medium break-words">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.classList.add('translate-y-0', 'opacity-100');
                }, 10);

                setTimeout(() => {
                    notification.classList.remove('translate-y-0', 'opacity-100');
                    notification.classList.add('-translate-y-2', 'opacity-0');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }
        }));
    });

    // Validación adicional del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('file-upload');
        const file = fileInput.files[0];
        
        if (!file) {
            e.preventDefault();
            showNotificationSimple('Por favor, selecciona un archivo para importar', 'error');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            e.preventDefault();
            showNotificationSimple('El archivo no debe exceder los 5MB', 'error');
            return;
        }
        
        const extension = file.name.split('.').pop().toLowerCase();
        if (!['xlsx', 'xls', 'csv'].includes(extension)) {
            e.preventDefault();
            showNotificationSimple('Formato de archivo no válido. Use .xlsx, .xls o .csv', 'error');
            return;
        }
    });

    // Función simple para notificaciones
    function showNotificationSimple(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 sm:top-6 sm:right-6 z-50 px-4 sm:px-6 py-3 sm:py-4 rounded-xl shadow-xl transform transition-all duration-300 text-sm sm:text-base max-w-[90vw] ${
            type === 'success'
            ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
            : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
        }`;

        notification.innerHTML = `
            <div class="flex items-center gap-2 sm:gap-3">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                    }
                </svg>
                <span class="font-medium break-words">${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('translate-y-0', 'opacity-100');
        }, 10);

        setTimeout(() => {
            notification.classList.remove('translate-y-0', 'opacity-100');
            notification.classList.add('-translate-y-2', 'opacity-0');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
</script>
@endsection