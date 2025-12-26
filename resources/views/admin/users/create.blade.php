@extends('layouts.admin')
@section('title', 'Crear Nuevo Usuario')
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Crear Nuevo Usuario</h1>
                <p class="text-gray-600 mt-2">Completa el formulario para registrar un nuevo usuario</p>
            </div>

            <div class="flex items-center gap-2 mt-4 lg:mt-0">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Usuarios
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.users.store') }}"
              method="POST"
              id="userForm">
            @csrf

            <div class="p-8">
                <!-- Información personal -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- DNI -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">
                                DNI <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dni" id="dni" value="{{ old('dni') }}" required placeholder="Ej: 12345678" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('dni')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label for="names" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombres Completos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="names" id="names" value="{{ old('names') }}" required placeholder="Ej: Juan Pérez" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('names')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Ej: usuario@ejemplo.com" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Código País
                            </label>
                            <select name="country_code" id="country_code" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">-- Seleccione --</option>
                                <option value="+51">+51 - Perú</option>
                                <option value="+54">+54 - Argentina</option>
                                <option value="+56">+56 - Chile</option>
                                <option value="+591">+591 - Bolivia</option>
                                <option value="+593">+593 - Ecuador</option>
                                <option value="+598">+598 - Uruguay</option>
                            </select>
                            @error('country_code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Ej: +51 987654321" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información adicional -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Información Adicional</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nacionalidad -->
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">
                                Nacionalidad
                            </label>
                            <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" placeholder="Ej: Peruana" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('nationality')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profesión -->
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                                Profesión
                            </label>
                            <input type="text" name="profession" id="profession" value="{{ old('profession') }}" placeholder="Ej: Ingeniero de Sistemas" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('profession')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección
                            </label>
                            <textarea name="address" id="address" rows="2" placeholder="Ej: Av. Principal 123, Lima" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Seguridad y Rol -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Seguridad y Rol</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contraseña -->
                        <!--<div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>-->

                        <!-- Confirmar Contraseña -->
                        <!--<div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                        </div>-->

                        <!-- Rol -->
                        <div class="md:col-span-2">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">Seleccionar rol</option>
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                    <div>
                        <button type="button" onclick="resetForm()" class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reiniciar
                            </div>
                        </button>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit" class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Crear Usuario
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Información de ayuda -->
    <div class="mt-8">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 rounded-xl bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-blue-900">Información Importante</h3>
                    <p class="text-sm text-blue-700 mt-1">Recomendaciones para crear usuarios</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">🔐 Contraseñas Seguras</h4>
                    <p class="text-sm text-gray-600">
                        Usa contraseñas de al menos 8 caracteres que incluyan mayúsculas, minúsculas, números y símbolos.
                    </p>
                </div>

                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">👥 Asignación de Roles</h4>
                    <p class="text-sm text-gray-600">
                        Asigna el rol adecuado según las funciones del usuario: Estudiante, Instructor o Administrador.
                    </p>
                </div>

                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">📧 Verificación de Email</h4>
                    <p class="text-sm text-gray-600">
                        Los usuarios nuevos recibirán un email de verificación para activar su cuenta.
                    </p>
                </div>

                <div class="p-4 bg-white rounded-xl">
                    <h4 class="font-medium text-gray-900 mb-2">📝 Información Completa</h4>
                    <p class="text-sm text-gray-600">
                        Proporciona información completa para un mejor seguimiento y comunicación con los usuarios.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Reiniciar formulario
    function resetForm() {
        if (confirm('¿Estás seguro de reiniciar el formulario? Se perderán todos los datos ingresados.')) {
            document.getElementById('userForm').reset();
        }
    }

    // Mostrar/ocultar contraseña
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }
</script>
@endsection
