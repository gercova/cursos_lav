@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Registrarse')
@section('content')
<div class="relative min-h-[90vh] flex items-center justify-center bg-slate-50/50 py-16 px-4 overflow-hidden">
    <!-- Floating background blobs for depth/glassmorphism -->
    <div class="absolute top-1/4 left-1/4 w-80 h-80 bg-blue-400/20 rounded-full blur-3xl animate-blob-1 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-400/15 rounded-full blur-3xl animate-blob-2 pointer-events-none"></div>
    <div class="absolute top-10 right-10 w-48 h-48 bg-purple-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-6xl w-full relative z-10">
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl shadow-xl shadow-blue-500/15 mb-4 text-white">
                <i class="fas fa-user-plus text-xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                Crear Cuenta en {{ $enterprise->trade_name }}
            </h1>
            <p class="text-sm text-gray-500 max-w-lg mx-auto mt-2">
                Únete a nuestra plataforma y accede a todos nuestros cursos especializados
            </p>
        </div>

        <!-- Grid Container -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Lateral (Beneficios) -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950/95 rounded-3xl p-8 text-white h-full border border-slate-800 shadow-[0_20px_50px_rgba(8,112,184,0.08)] relative overflow-hidden backdrop-blur-xl">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <h3 class="text-xl font-bold mb-6 tracking-tight flex items-center">
                        <i class="fas fa-award mr-2.5 text-blue-400"></i> Beneficios de registrarte
                    </h3>

                    <div class="space-y-6">
                        <div class="flex items-start group">
                            <div class="flex-shrink-0 bg-white/10 p-3 rounded-xl mr-4 group-hover:bg-blue-500/20 transition-all duration-200">
                                <i class="fas fa-graduation-cap text-base text-blue-300"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm mb-1">Cursos Ilimitados</h4>
                                <p class="text-gray-400 text-xs leading-relaxed">Acceso a más de {{ $totalCourses->count() }} cursos especializados en SST y Calidad</p>
                            </div>
                        </div>

                        <div class="flex items-start group">
                            <div class="flex-shrink-0 bg-white/10 p-3 rounded-xl mr-4 group-hover:bg-blue-500/20 transition-all duration-200">
                                <i class="fas fa-certificate text-base text-blue-300"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm mb-1">Certificados Oficiales</h4>
                                <p class="text-gray-400 text-xs leading-relaxed">Diplomas y acreditaciones verificables al culminar cada programa</p>
                            </div>
                        </div>

                        <div class="flex items-start group">
                            <div class="flex-shrink-0 bg-white/10 p-3 rounded-xl mr-4 group-hover:bg-blue-500/20 transition-all duration-200">
                                <i class="fas fa-chart-line text-base text-blue-300"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm mb-1">Seguimiento de Progreso</h4>
                                <p class="text-gray-400 text-xs leading-relaxed">Monitorea tu aprendizaje en tiempo real desde tu propio dashboard</p>
                            </div>
                        </div>

                        <div class="flex items-start group">
                            <div class="flex-shrink-0 bg-white/10 p-3 rounded-xl mr-4 group-hover:bg-blue-500/20 transition-all duration-200">
                                <i class="fas fa-headset text-base text-blue-300"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm mb-1">Soporte Técnico</h4>
                                <p class="text-gray-400 text-xs leading-relaxed">Asistencia continua para resolver cualquier duda en el uso de la plataforma</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-800 flex items-center">
                        <i class="fas fa-shield-alt text-lg text-emerald-400 mr-3"></i>
                        <div>
                            <p class="font-semibold text-xs">Conexión 100% segura</p>
                            <p class="text-gray-400 text-[11px] mt-0.5">Tus datos personales están protegidos con cifrado SSL</p>
                        </div>
                    </div>

                    <div class="mt-8 text-center bg-white/5 rounded-2xl p-4 border border-white/5">
                        <p class="text-gray-400 text-xs">
                            ¿Ya tienes una cuenta?
                            <a href="{{ route('login') }}" class="font-semibold text-blue-400 hover:text-blue-300 transition-colors duration-200 ml-1">
                                Iniciar sesión
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_rgba(8,112,184,0.08)] border border-white/40 overflow-hidden hover:shadow-[0_20px_50px_rgba(8,112,184,0.12)] transition-all duration-300">
                    <div class="bg-white/20 px-8 py-5 border-b border-gray-150/50 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-edit mr-2.5 text-blue-600 text-base"></i>
                            Información de Registro
                        </h2>
                        <span class="text-xs text-gray-400 font-medium">Campos obligatorios *</span>
                    </div>

                    <form action="{{ route('register') }}" method="POST" autocomplete="off" class="px-8 py-8 space-y-6">
                        @method('post')
                        @csrf

                        <!-- Alertas de errores -->
                        @if($errors->any())
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 flex gap-3 items-start animate-shake">
                                <i class="fas fa-exclamation-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-red-800">Por favor, corrige los siguientes errores:</h3>
                                    <ul class="list-disc pl-5 mt-1.5 space-y-1 text-xs text-red-750">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <!-- Grid de Formulario -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Columna Izquierda -->
                            <div class="space-y-4">
                                <!-- DNI -->
                                <div>
                                    <label for="dni" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        DNI / RUC *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <input id="dni" name="dni" type="text" required 
                                               placeholder="Número de documento"
                                               value="{{ old('dni') }}"
                                               class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                    </div>
                                </div>

                                <!-- Nombres -->
                                <div>
                                    <label for="names" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Nombres Completos / Razón Social *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <input id="names" name="names" type="text" required 
                                               placeholder="Ej: Juan Pérez"
                                               value="{{ old('names') }}"
                                               class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Correo Electrónico *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <input id="email" name="email" type="email" autocomplete="email" required 
                                               placeholder="ejemplo@correo.com"
                                               value="{{ old('email') }}"
                                               class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                    </div>
                                </div>

                                <!-- Profesión -->
                                <div>
                                    <label for="profession" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Profesión u Ocupación *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-briefcase text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <input id="profession" name="profession" type="text" required 
                                               placeholder="Ej: Ingeniero, Consultor"
                                               value="{{ old('profession') }}"
                                               class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="space-y-4">
                                <!-- Celular -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Número de Celular *
                                    </label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="relative group col-span-1">
                                            <select id="country_code" name="country_code" required 
                                                    class="block w-full px-3 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all duration-200 appearance-none cursor-pointer">
                                                <option value="+51" {{ old('country_code', '+51') == '+51' ? 'selected' : '' }}>🇵🇪 +51</option>
                                                <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                                                <option value="+52" {{ old('country_code') == '+52' ? 'selected' : '' }}>🇲🇽 +52</option>
                                                <option value="+34" {{ old('country_code') == '+34' ? 'selected' : '' }}>🇪🇸 +34</option>
                                                <option value="+54" {{ old('country_code') == '+54' ? 'selected' : '' }}>🇦🇷 +54</option>
                                                <option value="+56" {{ old('country_code') == '+56' ? 'selected' : '' }}>🇨🇱 +56</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                                <i class="fas fa-chevron-down text-[10px]"></i>
                                            </div>
                                        </div>
                                        <div class="col-span-2">
                                            <div class="relative group">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <i class="fas fa-mobile-alt text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                                </div>
                                                <input id="phone" name="phone" type="tel" required 
                                                       placeholder="987654321"
                                                       value="{{ old('phone') }}"
                                                       class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nacionalidad -->
                                <div>
                                    <label for="nationality" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Nacionalidad *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-globe text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <input id="nationality" name="nationality" type="text" required 
                                               placeholder="Ej: Peruana"
                                               value="{{ old('nationality') }}"
                                               class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                    </div>
                                </div>

                                <!-- Dirección -->
                                <div>
                                    <label for="address" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Dirección Completa *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-start pt-3.5 pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <textarea id="address" name="address" rows="1" required 
                                                  placeholder="Calle, Número, Ciudad"
                                                  class="pl-11 block w-full px-4 py-3 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 bg-gray-50/50 hover:bg-gray-50 resize-none">{{ old('address') }}</textarea>
                                    </div>
                                </div>

                                <!-- Contraseña -->
                                <div>
                                    <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Contraseña *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                                               placeholder="Mínimo 8 caracteres"
                                               class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                    </div>
                                    <div id="password-strength" class="mt-2.5 hidden">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                <div id="strength-bar" class="h-full rounded-full transition-all duration-300"></div>
                                            </div>
                                            <span id="strength-text" class="text-[10px] font-semibold uppercase tracking-wider"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirmar Contraseña -->
                                <div>
                                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                        Confirmar Contraseña *
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-check-double text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                        </div>
                                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                                               placeholder="Repite la contraseña"
                                               class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                                    </div>
                                    <div id="password-match" class="mt-2 text-xs hidden flex items-center">
                                        <!-- Will be dynamically updated by JS -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Términos y Condiciones -->
                        <div class="bg-blue-50/40 border border-blue-100/50 p-5 rounded-2xl mt-8">
                            <div class="flex items-start">
                                <div class="flex items-center h-5 mt-0.5">
                                    <input id="terms" name="terms" type="checkbox" required class="h-5 w-5 text-blue-600 focus:ring-blue-500/20 border-gray-300 rounded transition-all duration-200 cursor-pointer">
                                </div>
                                <div class="ml-3.5">
                                    <label for="terms" class="text-xs text-gray-650 cursor-pointer select-none">
                                        <span class="font-bold text-gray-900">Acepto los términos y condiciones</span>
                                        <p class="mt-1 leading-relaxed text-gray-500">
                                            Confirmo que he leído y acepto los
                                            <a href="{{ route('terminos-y-condiciones') }}" target="_blank" class="text-blue-600 hover:text-indigo-650 font-semibold hover:underline">términos de servicio</a>,
                                            <a href="{{ route('politicas-de-uso') }}" target="_blank" class="text-blue-600 hover:text-indigo-650 font-semibold hover:underline">política de privacidad</a>
                                            y
                                            <a href="{{ route('politicas-de-cookies') }}" target="_blank" class="text-blue-600 hover:text-indigo-650 font-semibold hover:underline">política de cookies</a>.
                                        </p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-100/50">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-sign-in-alt mr-1.5"></i> ¿Ya tienes una cuenta? Inicia sesión
                            </a>

                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <a href="{{ route('home') }}" class="flex-1 sm:flex-none justify-center inline-flex items-center px-5 py-3 border border-gray-250 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    Cancelar
                                </a>

                                <button type="submit" class="flex-1 sm:flex-none justify-center inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-lg shadow-blue-500/10 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                    <i class="fas fa-user-plus mr-2 text-xs"></i> Crear mi cuenta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Float Animations for Blurred Blobs */
    @keyframes blob-float-1 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(40px, -60px) scale(1.1); }
        66% { transform: translate(-30px, 30px) scale(0.95); }
    }
    @keyframes blob-float-2 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        50% { transform: translate(-50px, 50px) scale(1.05); }
    }
    .animate-blob-1 {
        animation: blob-float-1 20s infinite ease-in-out;
    }
    .animate-blob-2 {
        animation: blob-float-2 25s infinite ease-in-out;
    }

    /* Focus highlight shadows */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08) !important;
    }
    
    /* Shake Keyframe Animation on Errors */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        15%       { transform: translateX(-6px); }
        30%       { transform: translateX(6px); }
        45%       { transform: translateX(-4px); }
        60%       { transform: translateX(4px); }
        75%       { transform: translateX(-2px); }
        90%       { transform: translateX(2px); }
    }
    .animate-shake { animation: shake 0.5s ease-in-out; }

    /* Loading Spinner Styles */
    .btn-loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        top: 50%;
        left: 50%;
        margin-left: -9px;
        margin-top: -9px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DNI input digit restriction
        const dniInput = document.getElementById('dni');
        if (dniInput) {
            dniInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        // Phone input digit restriction
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9\s]/g, '');
            });
        }

        // Password strength and matching checks
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
            return Math.min(strength, 4);
        }

        function updateStrengthDisplay(strength) {
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
            const textColors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-blue-500', 'text-green-500'];
            const texts = ['Muy débil', 'Débil', 'Regular', 'Fuerte', 'Muy fuerte'];

            strengthBar.className = `h-full rounded-full transition-all duration-300 ${colors[strength]}`;
            strengthBar.style.width = `${(strength + 1) * 20}%`;
            
            strengthText.textContent = texts[strength];
            strengthText.className = `text-[10px] font-semibold uppercase tracking-wider ${textColors[strength]}`;

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
                    confirmPassword.classList.add('border-green-400');
                    matchContainer.classList.remove('hidden', 'text-red-500');
                    matchContainer.classList.add('text-green-600');
                    matchContainer.innerHTML = '<i class="fas fa-check-circle mr-1.5 text-sm"></i><span>Las contraseñas coinciden</span>';
                } else {
                    confirmPassword.classList.remove('border-green-400');
                    confirmPassword.classList.add('border-red-300');
                    matchContainer.classList.remove('hidden', 'text-green-600');
                    matchContainer.classList.add('text-red-500');
                    matchContainer.innerHTML = '<i class="fas fa-times-circle mr-1.5 text-sm"></i><span>Las contraseñas no coinciden</span>';
                }
            } else {
                matchContainer.classList.add('hidden');
                confirmPassword.classList.remove('border-red-300', 'border-green-400');
            }
        }

        if (password) {
            password.addEventListener('input', function() {
                const strength = checkPasswordStrength(this.value);
                updateStrengthDisplay(strength);
                checkPasswordMatch();
            });
        }

        if (confirmPassword) {
            confirmPassword.addEventListener('input', checkPasswordMatch);
        }

        // Form submit loading overlay
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.disabled = true;

                    // Revert after 10s if something fails asynchronously
                    setTimeout(() => {
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.disabled = false;
                    }, 10000);
                }
            });
        }
    });
</script>
@endsection
