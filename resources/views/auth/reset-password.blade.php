@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Restablecer Contraseña')
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
                    Nueva Contraseña
                </h2>
                <p class="mt-2 text-blue-100 text-sm">
                    Crea una nueva contraseña para tu cuenta
                </p>
            </div>

            <!-- Form -->
            <form class="px-8 py-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Alertas -->
                @if(session('status'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('status') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    @foreach($errors->all() as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-5">
                    <!-- Campo Email (oculto) -->
                    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                    <!-- Email visible para confirmación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-500"></i>
                            Correo electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50"
                                   value="{{ $email ?? old('email') }}"
                                   readonly>
                        </div>
                    </div>

                    <!-- Nueva Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>
                            Nueva Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                   class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('password') border-red-500 @enderror"
                                   placeholder="••••••••"
                                   autocomplete="new-password">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Mínimo 8 caracteres. Incluye mayúsculas, minúsculas y números.
                        </p>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-500"></i>
                            Confirmar Nueva Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   placeholder="••••••••"
                                   autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Indicador de fortaleza de contraseña -->
                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Fortaleza de la contraseña</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="password-strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <div id="password-strength-text" class="text-xs text-gray-500 mt-1"></div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-redo mr-2"></i>
                            Restablecer Contraseña
                        </button>
                    </div>
                </div>
            </form>

            <!-- Footer del formulario -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    ¿Recordaste tu contraseña?
                    <a href="{{ route('login') }}"
                       class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200 ml-1">
                       Iniciar sesión
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    button:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('password-strength-text');

        function checkPasswordStrength(password) {
            let strength = 0;

            // Longitud
            if (password.length >= 8) strength += 25;
            if (password.length >= 12) strength += 10;

            // Caracteres variados
            if (/[a-z]/.test(password)) strength += 10;
            if (/[A-Z]/.test(password)) strength += 15;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;

            // Colores y texto
            let color, text;
            if (strength <= 25) {
                color = '#ef4444'; // Rojo
                text = 'Débil';
            } else if (strength <= 50) {
                color = '#f59e0b'; // Naranja
                text = 'Regular';
            } else if (strength <= 75) {
                color = '#3b82f6'; // Azul
                text = 'Buena';
            } else {
                color = '#10b981'; // Verde
                text = 'Excelente';
            }

            strengthBar.style.width = Math.min(strength, 100) + '%';
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        }

        if (passwordInput && strengthBar && strengthText) {
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });

            // Inicializar con valor vacío
            checkPasswordStrength('');
        }

        // Efecto de enfoque
        const inputs = document.querySelectorAll('input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });
        });
    });
</script>
@endsection
