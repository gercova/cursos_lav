@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Iniciar Sesión')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full mx-auto">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header con gradiente -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-white">
                    Iniciar Sesión
                </h2>
                <p class="mt-2 text-blue-100 text-sm">
                    Accede a tu cuenta {{ $enterprise->trade_name }}
                </p>
            </div>

            <!-- Form -->
            <form class="px-8 py-6" action="{{ route('login') }}" method="POST" id="login-form">
                @csrf

                {{-- ── Alerta de error mejorada ──────────────────────────────── --}}
                @php
                    $errorMsg   = $errors->first();
                    $hasError   = $errors->any();
                    $isThrottle = $hasError && str_contains($errorMsg, 'intentos') || str_contains($errorMsg ?? '', 'segundo');
                    $isExpired  = $hasError && str_contains($errorMsg, 'caducado');
                    $isInactive = $hasError && str_contains($errorMsg, 'desactivada');
                @endphp

                @if($hasError)
                    <div id="login-alert"
                         class="mb-5 rounded-xl border px-4 py-3.5 flex gap-3 items-start animate-shake
                             {{ $isThrottle ? 'bg-amber-50 border-amber-300 text-amber-800'
                                 : ($isExpired || $isInactive ? 'bg-orange-50 border-orange-300 text-orange-800'
                                 : 'bg-red-50 border-red-300 text-red-800') }}">

                        {{-- Ícono contextual --}}
                        <div class="flex-shrink-0 mt-0.5">
                            @if($isThrottle)
                                <i class="fas fa-clock text-amber-500 text-lg"></i>
                            @elseif($isExpired)
                                <i class="fas fa-calendar-times text-orange-500 text-lg"></i>
                            @elseif($isInactive)
                                <i class="fas fa-user-slash text-orange-500 text-lg"></i>
                            @else
                                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                            @endif
                        </div>

                        {{-- Texto --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold leading-snug">
                                @if($isThrottle)
                                    Demasiados intentos fallidos
                                @elseif($isExpired)
                                    Cuenta expirada
                                @elseif($isInactive)
                                    Cuenta desactivada
                                @else
                                    Credenciales incorrectas
                                @endif
                            </p>
                            <p class="text-xs mt-0.5 opacity-80">{{ $errorMsg }}</p>
                        </div>

                        {{-- Botón cerrar --}}
                        <button type="button" onclick="document.getElementById('login-alert').remove()"
                                class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity mt-0.5">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                @endif

                {{-- Mensaje de éxito (p. ej. tras restablecer contraseña) --}}
                @if(session('status'))
                    <div class="mb-5 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3.5 flex gap-3 items-start">
                        <i class="fas fa-check-circle text-emerald-500 text-lg flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-emerald-800">{{ session('status') }}</p>
                    </div>
                @endif

                {{-- ── Campos del formulario ────────────────────────────────── --}}
                <div class="space-y-5">

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 {{ $errors->has('email') ? 'text-red-400' : 'text-gray-400' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   placeholder="correo@ejemplo.com"
                                   value="{{ old('email') }}"
                                   class="pl-10 block w-full px-4 py-3 border rounded-lg text-sm transition-colors duration-200
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       @error('email')
                                           border-red-400 bg-red-50 text-red-900 placeholder-red-300
                                           focus:ring-red-400 focus:border-red-400
                                       @else
                                           border-gray-300 bg-white text-gray-900 placeholder-gray-400
                                       @enderror">
                        </div>
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Contraseña
                            </label>
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-blue-600 hover:text-blue-500 transition-colors duration-200 font-medium">
                                ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password"
                                   autocomplete="current-password" required
                                   placeholder="••••••••"
                                   class="pl-10 pr-10 block w-full px-4 py-3 border rounded-lg text-sm transition-colors duration-200
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       @error('email') border-red-400 bg-red-50 @else border-gray-300 bg-white @enderror">
                            {{-- Toggle mostrar/ocultar contraseña --}}
                            <button type="button" id="toggle-password"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-eye text-sm" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-200">
                        <label for="remember_me" class="ml-3 text-sm text-gray-700">
                            Mantener sesión iniciada
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Iniciar Sesión
                        </button>
                    </div>

                    <!-- Separador -->
                    {{-- <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">
                                O continúa con
                            </span>
                        </div>
                    </div> --}}

                    <!-- Social Login (opcional) -->
                    {{-- <div class="grid grid-cols-2 gap-3">
                        <button type="button" class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fab fa-google text-red-500 mr-2"></i>
                            Google
                        </button>
                        <button type="button" class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fab fa-facebook text-blue-600 mr-2"></i>
                            Facebook
                        </button>
                    </div> --}}
                </div>
            </form>

            <!-- Footer del formulario -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}"
                       class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200 ml-1">
                       Regístrate aquí
                    </a>
                </p>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Al iniciar sesión, aceptas nuestros
                <a href="{{ route('terminos-y-condiciones') }}" class="text-blue-600 hover:text-blue-500 transition-colors duration-200">Términos de Servicio</a>
                y
                <a href="{{ route('politicas-de-uso') }}" class="text-blue-600 hover:text-blue-500 transition-colors duration-200">Política de Privacidad</a>
            </p>
        </div>
    </div>
</div>

<style>
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

    /* Ring de error en inputs */
    input.border-red-400:focus {
        box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.2);
    }
    /* Ring normal */
    input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>

<script>
    // Toggle ver/ocultar contraseña
    const toggleBtn = document.getElementById('toggle-password');
    const pwdInput  = document.getElementById('password');
    const eyeIcon   = document.getElementById('eye-icon');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isHidden = pwdInput.type === 'password';
            pwdInput.type  = isHidden ? 'text' : 'password';
            eyeIcon.className = isHidden ? 'fas fa-eye-slash text-sm' : 'fas fa-eye text-sm';
        });
    }

    // Auto-dismiss de la alerta de error después de 8 segundos
    const alert = document.getElementById('login-alert');
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.4s ease';
            alert.style.opacity    = '0';
            setTimeout(() => alert.remove(), 400);
        }, 8000);
    }
</script>
@endsection
