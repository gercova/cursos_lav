@extends('layouts.app')
@section('title', $enterprise->trade_name . ' - Iniciar Sesión')
@section('content')
    <div class="relative min-h-[85vh] flex items-center justify-center bg-slate-50/50 py-16 px-4 overflow-hidden">
        <!-- Floating background blobs for depth/glassmorphism -->
        <div
            class="absolute top-1/4 left-1/3 w-80 h-80 bg-blue-400/25 rounded-full blur-3xl animate-blob-1 pointer-events-none">
        </div>
        <div
            class="absolute bottom-1/4 right-1/3 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl animate-blob-2 pointer-events-none">
        </div>
        <div class="absolute top-10 right-10 w-48 h-48 bg-purple-400/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-md w-full relative z-10">
            <!-- Glassmorphic Login Card -->
            <div
                class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_rgba(8,112,184,0.08)] border border-white/40 overflow-hidden hover:shadow-[0_20px_50px_rgba(8,112,184,0.12)] transition-all duration-300">

                <!-- Card Header -->
                <div class="px-8 pt-8 pb-5 text-center border-b border-gray-100/50 bg-white/20">
                    @if ($enterprise->logo_path)
                        <div class="flex justify-center mb-5">
                            <img class="h-auto w-auto filter drop-shadow-sm hover:scale-105 transition-transform duration-300"
                                src="{{ $enterprise->logo_path }}" alt="{{ $enterprise->trade_name }}" style="width: 150px;">
                        </div>
                    @else
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-blue-500/20 mb-4 text-white">
                            <i class="fas fa-graduation-cap text-2xl"></i>
                        </div>
                    @endif
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                        ¡Bienvenido de nuevo!
                    </h2>
                    <p class="mt-1 text-xs text-gray-500">
                        Ingresa a tu cuenta de {{ $enterprise->trade_name }}
                    </p>
                </div>

                <!-- Form -->
                <form class="px-8 py-6 space-y-5" action="{{ route('login') }}" method="POST" id="login-form"
                    autocomplete="off">
                    @csrf

                    {{-- Alert de error --}}
                    @php
                        $errorMsg = $errors->first();
                        $hasError = $errors->any();
                        $isThrottle =
                            $hasError &&
                            (str_contains($errorMsg, 'intentos') || str_contains($errorMsg ?? '', 'segundo'));
                        $isExpired = $hasError && str_contains($errorMsg, 'caducado');
                        $isInactive = $hasError && str_contains($errorMsg, 'desactivada');
                    @endphp

                    @if ($hasError)
                        <div id="login-alert"
                            class="rounded-2xl border px-4 py-3.5 flex gap-3 items-start animate-shake
                             {{ $isThrottle
                                 ? 'bg-amber-50 border-amber-200 text-amber-800'
                                 : ($isExpired || $isInactive
                                     ? 'bg-orange-50 border-orange-200 text-orange-800'
                                     : 'bg-red-50 border-red-200 text-red-800') }}">

                            {{-- Ícono contextual --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if ($isThrottle)
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
                                    @if ($isThrottle)
                                        Demasiados intentos fallidos
                                    @elseif($isExpired)
                                        Cuenta expirada
                                    @elseif($isInactive)
                                        Cuenta desactivada
                                    @else
                                        Credenciales incorrectas
                                    @endif
                                </p>
                                <p class="text-xs mt-0.5 opacity-80 leading-relaxed">{{ $errorMsg }}</p>
                            </div>

                            {{-- Botón cerrar --}}
                            <button type="button" onclick="document.getElementById('login-alert').remove()"
                                class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity mt-0.5">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Mensaje de éxito --}}
                    @if (session('status'))
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 flex gap-3 items-start">
                            <i class="fas fa-check-circle text-emerald-500 text-lg flex-shrink-0 mt-0.5"></i>
                            <p class="text-sm text-emerald-800 leading-snug font-medium">{{ session('status') }}</p>
                        </div>
                    @endif

                    {{-- Campos --}}
                    <div class="space-y-5">
                        {{-- Email --}}
                        <div>
                            <label for="email"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                                Correo electrónico
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    placeholder="ejemplo@correo.com" value="{{ old('email') }}"
                                    class="pl-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200
                                       focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500
                                       @error('email')
                                           border-red-300 bg-red-50/30 text-red-900 placeholder-red-300
                                           focus:ring-red-500/10 focus:border-red-400
                                       @else
                                           border-gray-200 text-gray-900 placeholder-gray-400
                                       @enderror">
                            </div>
                        </div>

                        {{-- Contraseña --}}
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="password"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Contraseña
                                </label>
                                <a href="{{ route('password.request') }}"
                                    class="text-xs font-medium text-blue-600 hover:text-indigo-600 transition-colors duration-200">
                                    ¿La olvidaste?
                                </a>
                            </div>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-lock text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                    required placeholder="••••••••"
                                    class="pl-11 pr-11 block w-full px-4 py-3.5 bg-gray-50/50 hover:bg-gray-50 border border-gray-250 rounded-xl text-sm transition-all duration-200
                                       focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500
                                       @error('email') border-red-300 bg-red-50/30 @else border-gray-200 @enderror">
                                {{-- Toggle mostrar/ocultar --}}
                                <button type="button" id="toggle-password"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-eye text-sm" id="eye-icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember me -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center cursor-pointer select-none">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="h-4.5 w-4.5 text-blue-600 focus:ring-blue-500/20 border-gray-300 rounded-md transition-all duration-200 cursor-pointer">
                                <span class="ml-2.5 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                    Mantener sesión iniciada
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/10 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                                <i class="fas fa-sign-in-alt mr-2 text-sm"></i>
                                Ingresar a mi cuenta
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Footer del formulario -->
                <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-100/50 text-center">
                    <p class="text-sm text-gray-600">
                        ¿No tienes una cuenta?
                        <a href="{{ route('register') }}"
                            class="font-semibold text-blue-600 hover:text-indigo-600 transition-colors duration-200 ml-1">
                            Regístrate gratis
                        </a>
                    </p>
                </div>
            </div>

            <!-- Información adicional/Políticas -->
            <div class="mt-6 text-center px-4">
                <p class="text-xs text-gray-400 leading-relaxed">
                    Al iniciar sesión, confirmas que aceptas nuestros
                    <a href="{{ route('terminos-y-condiciones') }}"
                        class="text-gray-500 hover:text-blue-600 hover:underline transition-all duration-200">Términos de
                        Servicio</a>
                    y
                    <a href="{{ route('politicas-de-uso') }}"
                        class="text-gray-500 hover:text-blue-600 hover:underline transition-all duration-200">Política de
                        Privacidad</a>.
                </p>
            </div>
        </div>
    </div>

    <style>
        /* Shake Keyframe Animation on Errors */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            15% {
                transform: translateX(-6px);
            }

            30% {
                transform: translateX(6px);
            }

            45% {
                transform: translateX(-4px);
            }

            60% {
                transform: translateX(4px);
            }

            75% {
                transform: translateX(-2px);
            }

            90% {
                transform: translateX(2px);
            }
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Float Animations for Blurred Blobs */
        @keyframes blob-float-1 {

            0%,
            100% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(40px, -60px) scale(1.1);
            }

            66% {
                transform: translate(-30px, 30px) scale(0.95);
            }
        }

        @keyframes blob-float-2 {

            0%,
            100% {
                transform: translate(0px, 0px) scale(1);
            }

            50% {
                transform: translate(-50px, 50px) scale(1.05);
            }
        }

        .animate-blob-1 {
            animation: blob-float-1 20s infinite ease-in-out;
        }

        .animate-blob-2 {
            animation: blob-float-2 25s infinite ease-in-out;
        }

        /* Focus highlight shadows */
        input:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        }

        input.border-red-300:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
        }
    </style>

    <script>
        // Toggle ver/ocultar contraseña
        const toggleBtn = document.getElementById('toggle-password');
        const pwdInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isHidden = pwdInput.type === 'password';
                pwdInput.type = isHidden ? 'text' : 'password';
                eyeIcon.className = isHidden ? 'fas fa-eye-slash text-sm' : 'fas fa-eye text-sm';
            });
        }

        // Auto-dismiss de la alerta de error después de 8 segundos
        const alert = document.getElementById('login-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(() => alert.remove(), 400);
            }, 8000);
        }
    </script>
@endsection
