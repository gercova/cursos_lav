@extends('layouts.student')
@section('title', 'Crear Nuevo Usuario')
@section('content')
<div class="container mx-auto px-4 py-4 sm:py-6">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Crear Nuevo Usuario</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1 sm:mt-2">Completa el formulario para registrar un nuevo usuario</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg sm:rounded-xl font-medium transition duration-200 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.users.store') }}" method="POST" id="userForm" enctype="multipart/form-data">
            @csrf
            <div class="p-4 sm:p-6 lg:p-8">
                <!-- Información personal -->
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <!-- DNI -->
                        <div>
                            <label for="dni" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                DNI <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dni" id="dni" value="{{ old('dni') }}" required placeholder="Ej: 12345678" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('dni')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label for="names" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Nombres Completos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="names" id="names" value="{{ old('names') }}" required placeholder="Ej: Juan Pérez" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('names')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Ej: usuario@ejemplo.com" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('email')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Código País -->
                        <div>
                            <label for="country_code" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Código País
                            </label>
                            <select name="country_code" id="country_code" required class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">-- Seleccione --</option>
                                @foreach($codeCountries as $country)
                                    <option value="{{ $country->code }}" {{ old('country_code') == $country->code ? 'selected' : '' }}>{{ $country->country }}</option>
                                @endforeach
                            </select>
                            @error('country_code')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Ej: +51 987654321" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('phone')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Información Adicional</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Nacionalidad -->
                        <div>
                            <label for="nationality" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Nacionalidad
                            </label>
                            <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" placeholder="Ej: Peruana" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('nationality')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profesión -->
                        <div>
                            <label for="profession" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Profesión
                            </label>
                            <input type="text" name="profession" id="profession" value="{{ old('profession') }}" placeholder="Ej: Ingeniero de Sistemas" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('profession')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                Dirección
                            </label>
                            <textarea name="address" id="address" rows="2" placeholder="Ej: Av. Principal 123, Lima" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                @if($hasAnyPackage)
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 sm:pt-8 border-t border-gray-200">
                        <div class="w-full sm:w-auto order-2 sm:order-1">
                            <button type="button" onclick="resetForm()" class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg sm:rounded-xl font-medium transition duration-200 text-sm sm:text-base">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reiniciar
                                </div>
                            </button>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto order-1 sm:order-2">
                            <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 sm:py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg sm:rounded-xl font-medium transition duration-200 text-sm sm:text-base text-center">
                                Cancelar
                            </a>
                            <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Usuario
                            </button>
                        </div>
                    </div>
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
        </form>
    </div>
</div>

<script>
    // Función para reiniciar formulario
    function resetForm() {
        if (confirm('¿Estás seguro de reiniciar el formulario? Se perderán todos los datos ingresados.')) {
            document.getElementById('userForm').reset();
            
            // Resetear vistas previas
            document.getElementById('preview-container').classList.add('hidden');
            document.getElementById('default-preview').classList.remove('hidden');
            document.getElementById('preview-signature-container').classList.add('hidden');
            document.getElementById('default-signature-preview').classList.remove('hidden');
            
            showNotification('Formulario reiniciado', 'success');
        }
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
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

    // Validación del formulario antes de enviar
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const dni = document.getElementById('dni').value;
        const phone = document.getElementById('phone').value;
        let hasError = false;

        // Validar DNI (solo números, 8 dígitos)
        if (dni && !/^\d{8}$/.test(dni)) {
            showNotification('El DNI debe tener 8 dígitos numéricos', 'error');
            hasError = true;
        }

        // Validar teléfono si se ingresó
        if (phone && !/^[\d\s\+\-]{6,20}$/.test(phone)) {
            showNotification('El teléfono no tiene un formato válido', 'error');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });
</script>
@endsection