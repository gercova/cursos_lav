@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Recuperar Contraseña')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full mx-auto">
        <!-- Card Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header con gradiente -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h2 class="mt-4 text-2xl font-bold text-white">
                    Recuperar Contraseña
                </h2>
                <p class="mt-2 text-blue-100 text-sm">
                    Te enviaremos un enlace para restablecer tu contraseña
                </p>
            </div>

            <!-- Form -->
            <form class="px-8 py-6" action="{{ route('password.email') }}" method="POST">
                @csrf

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

                <!-- Instrucciones -->
                <div class="mb-6">
                    <p class="text-sm text-gray-600 text-center">
                        Ingresa tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                    </p>
                </div>

                <!-- Campo Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
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
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                               placeholder="correo@ejemplo.com"
                               value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mb-6">
                    <button type="submit"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar enlace de recuperación
                    </button>
                </div>

                <!-- Botón de regreso -->
                <div class="text-center">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Si no recibes el correo en unos minutos, revisa tu carpeta de spam o
                <a href="{{ route('contacto') }}" class="text-blue-600 hover:text-blue-500 transition-colors duration-200">
                    contáctanos
                </a>
            </p>
        </div>
    </div>
</div>

<style>
    /* Animaciones suaves */
    input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    button:hover {
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }

    /* Efecto de carga para el botón */
    .btn-loading {
        position: relative;
        color: transparent;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    // Efecto de enfoque en campos
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');

        if (emailInput) {
            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200');
            });

            emailInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200');
            });

            // Auto-focus en el campo de email
            emailInput.focus();
        }

        // Manejo del envío del formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.disabled = true;

                    // Revertir después de 10 segundos por si hay error
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
