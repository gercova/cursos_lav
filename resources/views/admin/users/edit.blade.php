@extends('layouts.admin')
@section('title', 'Editar Usuario: ' . $user->names)
@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($user->names, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Editar Usuario</h1>
                        <p class="text-gray-600 mt-1">{{ $user->names }}</p>
                    </div>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                        @if($user->role === 'admin') bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800
                        @elseif($user->role === 'instructor') bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800
                        @else bg-gradient-to-r from-green-100 to-green-200 text-green-800
                        @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold {{ $user->is_active ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : 'bg-gradient-to-r from-red-100 to-red-200 text-red-800' }}">
                        {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        ID: {{ $user->id }}
                    </span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                        Registro: {{ $user->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 mt-6 lg:mt-0">
                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <i class="bi bi-eye"></i>
                    Ver Detalles
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                    <i class="bi bi-arrow-left"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
            <h3 class="font-bold text-red-800 mb-2">Errores de validación:</h3>
            <ul class="list-disc list-inside text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
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
                            <input type="text"  name="dni" id="dni" value="{{ old('dni', $user->dni) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('dni')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label for="names" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombres Completos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="names" id="names" value="{{ old('names', $user->names) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('names')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- codigo país -->
                        <div>
                            <label for="country_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Código País
                            </label>
                            <select name="country_code" id="country_code" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                <option value="">-- Seleccione --</option>
                                @foreach($codeCountries as $country)
                                    <option value="{{ $country->code }}" {{ $country->code == $user->country_code ? 'selected' : '' }} >{{ $country->country }}</option>
                                @endforeach
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
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
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
                            <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $user->nationality) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('nationality')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profesión -->
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                                Profesión
                            </label>
                            <input type="text" name="profession" id="profession" value="{{ old('profession', $user->profession) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                            @error('profession')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección
                            </label>
                            <textarea name="address" id="address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">{{ old('address', $user->address) }}</textarea>
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
                        <!-- Rol -->
                        <div class="md:col-span-2">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-200">
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
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
                                Reiniciar Cambios
                            </div>
                        </button>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.users.show', $user) }}" class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl font-medium transition duration-200">
                            Cancelar
                        </a>
                        <button type="submit" class="flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Actualizar Usuario
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Panel de acciones rápidas -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Estadísticas -->
        <div class="lg:col-span-2">
            <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Estadísticas del Usuario</h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-900">{{ $user->enrollments_count ?? 0 }}</div>
                            <div class="text-sm text-blue-700 mt-1">Inscripciones</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-900">{{ $user->courses_count ?? 0 }}</div>
                            <div class="text-sm text-green-700 mt-1">Cursos</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-900">{{ $user->certificates_count ?? 0 }}</div>
                            <div class="text-sm text-purple-700 mt-1">Certificados</div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-900">{{ $user->exam_attempts_count ?? 0 }}</div>
                            <div class="text-sm text-orange-700 mt-1">Exámenes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200 shadow-sm">
                <h3 class="text-xl font-bold text-blue-900 mb-6">Acciones Rápidas</h3>

                <div class="space-y-3">
                    <button onclick="toggleUserStatus({{ $user->id }})" class="w-full flex items-center gap-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br {{ $user->is_active ? 'from-red-100 to-red-200' : 'from-green-100 to-green-200' }}">
                            <svg class="w-5 h-5 {{ $user->is_active ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($user->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                @endif
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">
                            {{ $user->is_active ? 'Desactivar' : 'Activar' }} Usuario
                        </span>
                    </button>

                    <!--<button onclick="resetPassword({{ $user->id }})" class="w-full flex items-center gap-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">Restablecer Contraseña</span>
                    </button>-->

                    <!--<button onclick="sendMessage({{ $user->id }})" class="w-full flex items-center gap-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-green-100 to-green-200">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">Enviar Mensaje</span>
                    </button>-->

                    <button onclick="deleteUser({{ $user->id }})" class="w-full flex items-center gap-3 p-3 bg-white hover:bg-red-50 rounded-lg border border-red-200 transition duration-200 group">
                        <div class="p-2 rounded-lg bg-gradient-to-br from-red-100 to-red-200">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-red-700">Eliminar Usuario</span>
                    </button>
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
        if (confirm('¿Estás seguro de reiniciar el formulario? Se perderán todos los cambios no guardados.')) {
            document.getElementById('userForm').reset();
        }
    }

    // Mostrar/ocultar contraseña
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    // Cambiar estado del usuario
    async function toggleUserStatus(userId) {
        if (!confirm('¿Estás seguro de cambiar el estado del usuario?')) {
            return;
        }

        try {
            const response = await axios.patch(`/admin/users/${userId}/toggle-status`);
            if (response.data.success) {
                showNotification('Estado del usuario actualizado', 'success');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error al cambiar estado:', error);
            showNotification('Error al cambiar el estado', 'error');
        }
    }

    // Restablecer contraseña
    /*async function resetPassword(userId) {
        if (!confirm('¿Estás seguro de restablecer la contraseña? Se enviará un email al usuario con una nueva contraseña.')) {
            return;
        }

        try {
            const response = await axios.post(`/admin/users/${userId}/reset-password`);
            if (response.data.success) {
                showNotification('Contraseña restablecida. Se ha enviado un email al usuario.', 'success');
            }
        } catch (error) {
            console.error('Error al restablecer contraseña:', error);
            showNotification('Error al restablecer la contraseña', 'error');
        }
    }*/

    // Enviar mensaje
    /*async function sendMessage(userId) {
        const message = prompt('Escribe el mensaje que deseas enviar:');
        if (!message) return;

        try {
            const response = await axios.post(`/admin/users/${userId}/send-message`, {
                message: message
            });

            if (response.data.success) {
                showNotification('Mensaje enviado exitosamente', 'success');
            }
        } catch (error) {
            console.error('Error al enviar mensaje:', error);
            showNotification('Error al enviar el mensaje', 'error');
        }
    }*/

    // Eliminar usuario
    async function deleteUser(userId) {
        if (!confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
            return;
        }

        try {
            const response = await axios.delete(`/admin/users/${userId}`);
            if (response.data.success) {
                showNotification('Usuario eliminado exitosamente', 'success');
                setTimeout(() => window.location.href = '{{ route('admin.users.index') }}', 1000);
            }
        } catch (error) {
            console.error('Error al eliminar usuario:', error);
            showNotification(error.response?.data?.message || 'Error al eliminar el usuario', 'error');
        }
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-6 right-6 z-50 px-6 py-4 rounded-xl shadow-xl transform transition-all duration-300 ${
            type === 'success'
            ? 'bg-gradient-to-r from-green-500 to-green-600 text-white'
            : 'bg-gradient-to-r from-red-500 to-red-600 text-white'
        }`;

        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                    }
                </svg>
                <span class="font-medium">${message}</span>
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
