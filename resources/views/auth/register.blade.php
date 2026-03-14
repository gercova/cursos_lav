@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Registrarse')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl mb-6">
                <i class="fas fa-user-plus text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-3">
                Crear Cuenta en {{ $enterprise->trade_name }}
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Completa tus datos para acceder a todos nuestros cursos especializados en Seguridad y Salud en el Trabajo, Medio Ambiente y Calidad
            </p>
        </div>

        <!-- Card Container -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Lateral -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 text-white h-full">
                    <h3 class="text-2xl font-bold mb-6">Beneficios de Registrarse</h3>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-white/20 p-3 rounded-xl mr-4">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">Cursos Ilimitados</h4>
                                <p class="text-blue-100 text-sm">Acceso a más de {{ $totalCourses->count() }} cursos especializados</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-white/20 p-3 rounded-xl mr-4">
                                <i class="fas fa-certificate text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">Certificados Oficiales</h4>
                                <p class="text-blue-100 text-sm">Diplomas verificables al completar cursos</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-white/20 p-3 rounded-xl mr-4">
                                <i class="fas fa-chart-line text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">Seguimiento de Progreso</h4>
                                <p class="text-blue-100 text-sm">Monitoriza tu avance en tiempo real</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-white/20 p-3 rounded-xl mr-4">
                                <i class="fas fa-headset text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg mb-1">Soporte Personalizado</h4>
                                <p class="text-blue-100 text-sm">Asistencia técnica 24/7</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-blue-500/30">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-2xl mr-3"></i>
                            <div>
                                <p class="font-semibold">Datos Seguros</p>
                                <p class="text-blue-100 text-sm">Encriptación SSL de 256-bit</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-blue-100 text-sm">
                            ¿Ya tienes una cuenta?
                            <a href="{{ route('login') }}" class="font-semibold text-white hover:text-blue-200 transition-colors duration-200 underline">
                                Inicia sesión aquí
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-gray-50 to-white px-8 py-6 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-edit mr-3 text-blue-600"></i>
                            Información Personal
                        </h2>
                        <p class="text-gray-600 mt-2">Todos los campos marcados con * son obligatorios</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST" autocomplete="off">
                        @method('post')
                        @csrf
                        <div class="px-8 py-8">
                            <!-- Alertas de error -->
                            @if($errors->any())
                                <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-5">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-red-800">
                                                ¡Corrige los siguientes errores!
                                            </h3>
                                            <div class="mt-2">
                                                <ul class="list-disc pl-5 space-y-1 text-red-700">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Grid de formulario -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Columna Izquierda -->
                                <div class="space-y-5">
                                    <!-- DNI -->
                                    <div>
                                        <label for="dni" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                                DNI / RUC *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-fingerprint text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <input id="dni" name="dni" type="text" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="Ingresa tu número de DNI" value="{{ old('dni') }}">
                                        </div>
                                    </div>

                                    <!-- Nombres Completos -->
                                    <div>
                                        <label for="names" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                                                Nombres Completos / Razón Social *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <input id="names" name="names" type="text" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="Nombres y apellidos completos" value="{{ old('names') }}">
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                                Correo Electrónico *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-at text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <input id="email" name="email" type="email" autocomplete="email" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                                        </div>
                                    </div>

                                    <!-- Profesión -->
                                    <div>
                                        <label for="profession" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                                                Profesión u Ocupación *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user-tie text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <input id="profession" name="profession" type="text" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="Ej: Ingeniero, Estudiante, etc." value="{{ old('profession') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha -->
                                <div class="space-y-5">
                                    <!-- Celular -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-mobile-alt mr-2 text-blue-500"></i>
                                                Número de Celular *
                                            </span>
                                        </label>
                                        <div class="grid grid-cols-3 gap-3">
                                            <div class="relative group">
                                                <select id="country_code" name="country_code" required class="block w-full px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200 appearance-none bg-white">
                                                    <option value="+51" {{ old('country_code', '+51') == '+51' ? 'selected' : '' }}>🇵🇪 +51</option>
                                                    <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                                    <option value="+52" {{ old('country_code') == '+52' ? 'selected' : '' }}>🇲🇽 +52</option>
                                                    <option value="+34" {{ old('country_code') == '+34' ? 'selected' : '' }}>🇪🇸 +34</option>
                                                    <option value="+54" {{ old('country_code') == '+54' ? 'selected' : '' }}>🇦🇷 +54</option>
                                                    <option value="+56" {{ old('country_code') == '+56' ? 'selected' : '' }}>🇨🇱 +56</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                                </div>
                                            </div>
                                            <div class="col-span-2">
                                                <div class="relative group">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <i class="fas fa-phone text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                                    </div>
                                                    <input id="phone" name="phone" type="tel" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="987 654 321" value="{{ old('phone') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nacionalidad -->
                                    <div>
                                        <label for="nationality" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-globe-americas mr-2 text-blue-500"></i>
                                                Nacionalidad *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-flag text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <input id="nationality" name="nationality" type="text" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="Ej: Peruana" value="{{ old('nationality') }}">
                                        </div>
                                    </div>

                                    <!-- Dirección -->
                                    <div>
                                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                                Dirección Completa *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 pt-3 pointer-events-none">
                                                <i class="fas fa-home text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <textarea id="address" name="address" rows="1" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="Av. Principal 123, Ciudad">{{ old('address') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Contraseña -->
                                    <div>
                                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-key mr-2 text-blue-500"></i>
                                                Contraseña *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <input id="password" name="password" type="password" autocomplete="new-password" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="Mínimo 8 caracteres">
                                        </div>
                                        <div id="password-strength" class="mt-3 hidden">
                                            <div class="flex items-center space-x-2">
                                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div id="strength-bar" class="h-full rounded-full transition-all duration-300"></div>
                                                </div>
                                                <span id="strength-text" class="text-xs font-medium"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Confirmar Contraseña -->
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                            <span class="flex items-center">
                                                <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                                                Confirmar Contraseña *
                                            </span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                            </div>
                                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 hover:border-blue-400 transition-all duration-200" placeholder="Repite tu contraseña">
                                        </div>
                                        <div id="password-match" class="mt-2 text-sm hidden">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            <span>Las contraseñas coinciden</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Términos y Condiciones -->
                            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-200">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5 mt-0.5">
                                        <input id="terms" name="terms" type="checkbox" required class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-200">
                                    </div>
                                    <div class="ml-3">
                                        <label for="terms" class="text-gray-700 cursor-pointer">
                                            <span class="font-semibold text-gray-900">Acepto los términos y condiciones</span>
                                            <p class="text-gray-600 mt-1 text-sm">
                                                Confirmo que he leído y acepto los
                                                <a href="{{ route('terminos-y-condiciones') }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">términos de servicio</a>,
                                                <a href="{{ route('politicas-de-uso') }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">política de privacidad</a>
                                                y
                                                <a href="{{ route('politicas-de-cookies') }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">política de cookies</a>
                                                de {{ $enterprise->trade_name }}.
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="mt-8 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                                <a href="{{ route('login') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    ¿Ya tienes cuenta? Inicia sesión
                                </a>

                                <div class="flex space-x-3">
                                    <a href="{{ route('home') }}" class="inline-flex items-center px-5 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <i class="fas fa-times mr-2"></i>
                                        Cancelar
                                    </a>

                                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-xl">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        Crear mi cuenta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Información de seguridad -->
                <div class="mt-6 bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-lock text-green-500 mr-2"></i>
                            <span>Conexión segura SSL</span>
                        </div>
                        <div class="hidden md:flex items-center">
                            <i class="fas fa-user-shield text-blue-500 mr-2"></i>
                            <span>Protección de datos</span>
                        </div>
                        <div class="hidden md:flex items-center">
                            <i class="fas fa-ban text-red-500 mr-2"></i>
                            <span>Sin spam</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animaciones y efectos mejorados */
    input, select, textarea {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .group:hover .group-hover\:text-blue-500 {
        color: #3b82f6;
    }

    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .hover\:border-blue-400:hover {
        border-color: #60a5fa;
    }
</style>

<script>
    // Validación en tiempo real para DNI
    document.getElementById('dni').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Validación en tiempo real para teléfono
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9\s]/g, '');
    });

    // Verificación de contraseña y coincidencia
    const password          = document.getElementById('password');
    const confirmPassword   = document.getElementById('password_confirmation');
    const strengthBar       = document.getElementById('strength-bar');
    const strengthText      = document.getElementById('strength-text');
    const strengthContainer = document.getElementById('password-strength');
    const matchContainer    = document.getElementById('password-match');

    function checkPasswordStrength(pw) {
        let strength = 0;

        if (pw.length >= 8) strength += 1;
        if (pw.match(/[a-z]+/)) strength += 1;
        if (pw.match(/[A-Z]+/)) strength += 1;
        if (pw.match(/[0-9]+/)) strength += 1;
        if (pw.match(/[$@#&!]+/)) strength += 1;

        return strength;
    }

    function updateStrengthDisplay(strength) {
        const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
        const texts = ['Muy débil', 'Débil', 'Regular', 'Fuerte', 'Muy fuerte'];

        strengthBar.className = `h-full rounded-full transition-all duration-300 ${colors[strength]}`;
        strengthBar.style.width = `${(strength + 1) * 20}%`;
        strengthText.textContent = texts[strength];
        strengthText.className = `text-xs font-medium ${colors[strength].replace('bg-', 'text-')}`;

        if (password.value.length > 0) {
            strengthContainer.classList.remove('hidden');
        } else {
            strengthContainer.classList.add('hidden');
        }
    }

    function checkPasswordMatch() {
        if (password.value && confirmPassword.value) {
            if (password.value === confirmPassword.value) {
                confirmPassword.classList.remove('border-red-300', 'hover:border-red-400');
                confirmPassword.classList.add('border-green-300', 'hover:border-green-400');
                matchContainer.classList.remove('hidden');
                matchContainer.classList.add('text-green-600');
                matchContainer.innerHTML = '<i class="fas fa-check-circle mr-1"></i><span>Las contraseñas coinciden</span>';
            } else {
                confirmPassword.classList.remove('border-green-300', 'hover:border-green-400');
                confirmPassword.classList.add('border-red-300', 'hover:border-red-400');
                matchContainer.classList.remove('hidden');
                matchContainer.classList.add('text-red-600');
                matchContainer.innerHTML = '<i class="fas fa-times-circle mr-1"></i><span>Las contraseñas no coinciden</span>';
            }
        } else {
            matchContainer.classList.add('hidden');
            confirmPassword.classList.remove('border-red-300', 'border-green-300', 'hover:border-red-400', 'hover:border-green-400');
        }
    }

    // Event listeners
    password.addEventListener('input', function() {
        const strength = checkPasswordStrength(this.value);
        updateStrengthDisplay(strength);
        checkPasswordMatch();
    });

    confirmPassword.addEventListener('input', checkPasswordMatch);

    // Efecto de enfoque para campos
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-xl');
        });

        element.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-blue-200');
        });
    });
</script>
@endsection
