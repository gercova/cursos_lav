@extends('layouts.app')
@section('title', $enterprise->trade_name.' - Recuperar Contraseña')
@section('content')
<div class="relative min-h-[85vh] flex items-center justify-center bg-slate-50/50 py-16 px-4 overflow-hidden">
    <!-- Floating background blobs for depth/glassmorphism -->
    <div class="absolute top-1/4 left-1/3 w-80 h-80 bg-blue-400/25 rounded-full blur-3xl animate-blob-1 pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/3 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl animate-blob-2 pointer-events-none"></div>
    <div class="absolute top-10 right-10 w-48 h-48 bg-purple-400/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10">
        <!-- Glassmorphic Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-[0_20px_50px_rgba(8,112,184,0.08)] border border-white/40 overflow-hidden hover:shadow-[0_20px_50px_rgba(8,112,184,0.12)] transition-all duration-300">
            
            <!-- Card Header -->
            <div class="px-8 pt-8 pb-5 text-center border-b border-gray-100/50 bg-white/20">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-blue-500/20 mb-4 text-white hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-key text-xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                    ¿Olvidaste tu contraseña?
                </h2>
                <p class="mt-1 text-xs text-gray-500">
                    Te enviaremos un enlace para restablecer tu cuenta
                </p>
            </div>

            <!-- Form -->
            <form class="px-8 py-6 space-y-5" action="{{ route('password.email') }}" method="POST" autocomplete="off">
                @csrf

                <!-- Alerta de éxito -->
                @if(session('status'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 flex gap-3 items-start animate-fade-in">
                        <i class="fas fa-check-circle text-emerald-500 text-lg flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-emerald-800 leading-snug font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                <!-- Alerta de errores -->
                @if($errors->any())
                    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3.5 flex gap-3 items-start animate-shake">
                        <i class="fas fa-exclamation-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-red-800 leading-snug">Error al solicitar enlace</p>
                            <p class="text-xs text-red-750 mt-0.5 leading-relaxed">
                                @foreach($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Instrucciones -->
                <div class="bg-blue-50/40 border border-blue-100/50 rounded-2xl p-4">
                    <p class="text-xs text-blue-800 leading-relaxed text-center">
                        Ingresa el correo electrónico asociado a tu cuenta. Te enviaremos un enlace seguro para crear una nueva contraseña.
                    </p>
                </div>

                <!-- Campo Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Correo electrónico
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               placeholder="ejemplo@correo.com"
                               value="{{ old('email') }}"
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

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/10 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-paper-plane mr-2 text-xs"></i>
                        Enviar enlace de recuperación
                    </button>
                </div>

                <!-- Regresar al login -->
                <div class="text-center pt-2">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-indigo-600 transition-colors duration-200 group">
                        <i class="fas fa-arrow-left mr-2 text-xs group-hover:-translate-x-1 transition-transform duration-200"></i>
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>

        <!-- Soporte adicional -->
        <div class="mt-8 text-center px-4">
            <p class="text-xs text-gray-400 leading-relaxed">
                ¿Tienes problemas? Revisa tu carpeta de spam o
                <a href="{{ route('contacto') }}" class="text-gray-500 hover:text-blue-600 hover:underline transition-all duration-200 font-medium">contáctanos para ayudarte</a>.
            </p>
        </div>
    </div>
</div>

<style>
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
    input:focus {
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
    }
    input.border-red-300:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
    }

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
        const emailInput = document.getElementById('email');

        if (emailInput) {
            // Auto-focus on load
            emailInput.focus();
        }

        // Add loading effect to submit button on submit
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.disabled = true;

                    // Revert spinner status after 10s if something stalls
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
