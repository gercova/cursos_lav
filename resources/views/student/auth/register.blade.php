@extends('layouts.app')

@section('title', 'Registrarse')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto mt-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="mx-auto h-12 w-12 bg-green-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Crear Cuenta
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                ¿Ya tienes una cuenta?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                    Inicia sesión aquí
                </a>
            </p>
        </div>

        <!-- Form -->
        <form class="bg-white shadow-xl rounded-lg overflow-hidden" action="{{ route('register') }}" method="POST" autocomplete="off">
            @method('post')
            @csrf
            <div class="px-6 py-8 sm:p-10">
                <!-- Alertas de error -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                        <strong class="font-bold">Error: </strong>
                        <ul class="list-disc list-inside text-sm mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna 1 -->
                    <div class="space-y-6">
                        <!-- DNI -->
                        <div>
                            <label for="dni" class="block text-sm font-medium text-gray-700 mb-1">
                                DNI *
                            </label>
                            <input id="dni" name="dni" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('dni') border-red-500 @enderror"
                                   placeholder="Ingresa tu DNI" value="{{ old('dni') }}">
                            @error('dni')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombres Completos -->
                        <div>
                            <label for="names" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombres Completos *
                            </label>
                            <input id="names" name="names" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('names') border-red-500 @enderror"
                                   placeholder="Nombres y apellidos" value="{{ old('names') }}">
                            @error('names')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Correo Electrónico *
                            </label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('email') border-red-500 @enderror"
                                   placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Contraseña *
                            </label>
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('password') border-red-500 @enderror"
                                placeholder="Mínimo 8 caracteres">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar Contraseña *
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200"
                                placeholder="Repite tu contraseña">
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div class="space-y-6">
                        <!-- Código País y Celular -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="country_code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Código País *
                                </label>
                                <select id="country_code" name="country_code" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200">
                                    <option value="+51" {{ old('country_code', '+51') == '+51' ? 'selected' : '' }}>+51 Perú</option>
                                    <option value="+1" {{ old('country_code') == '+1' ? 'selected' : '' }}>+1 USA</option>
                                    <option value="+52" {{ old('country_code') == '+52' ? 'selected' : '' }}>+52 México</option>
                                    <option value="+34" {{ old('country_code') == '+34' ? 'selected' : '' }}>+34 España</option>
                                    <option value="+54" {{ old('country_code') == '+54' ? 'selected' : '' }}>+54 Argentina</option>
                                    <option value="+56" {{ old('country_code') == '+56' ? 'selected' : '' }}>+56 Chile</option>
                                    <option value="+57" {{ old('country_code') == '+57' ? 'selected' : '' }}>+57 Colombia</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Celular *
                                </label>
                                <input id="phone" name="phone" type="tel" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('phone') border-red-500 @enderror"
                                    placeholder="987654321" value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nacionalidad -->
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 mb-1">
                                Nacionalidad *
                            </label>
                            <input id="nationality" name="nationality" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('nationality') border-red-500 @enderror"
                                   placeholder="Ej: Peruana" value="{{ old('nationality') }}">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ubigeo -->
                        <div>
                            <label for="ubigeo" class="block text-sm font-medium text-gray-700 mb-1">
                                Ubigeo *
                            </label>
                            <input id="ubigeo" name="ubigeo" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('ubigeo') border-red-500 @enderror"
                                   placeholder="Código de ubigeo" value="{{ old('ubigeo') }}">
                            @error('ubigeo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                Dirección Completa *
                            </label>
                            <textarea id="address" name="address" rows="1" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('address') border-red-500 @enderror"
                                placeholder="Av. Principal 123, Ciudad, Departamento">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profesión -->
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700 mb-1">
                                Profesión *
                            </label>
                            <input id="profession" name="profession" type="text" required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-200 @error('profession') border-red-500 @enderror"
                                   placeholder="Ej: Ingeniero, Estudiante, etc." value="{{ old('profession') }}">
                            @error('profession')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Términos y Condiciones -->
                <div class="mt-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">
                                Acepto los
                                <a href="#" class="text-blue-600 hover:text-blue-500 transition-colors duration-200">términos y condiciones</a>
                                 y la
                                <a href="#" class="text-blue-600 hover:text-blue-500 transition-colors duration-200">política de privacidad</a>
                            </label>
                        </div>
                    </div>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer del Form -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al Inicio
                </a>

                <button type="submit"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Crear Cuenta
                </button>
            </div>
        </form>

        <!-- Información adicional -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Al registrarte, aceptas nuestros
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">Términos de Servicio</a>
                 y
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">Política de Privacidad</a>.
            </p>
        </div>
    </div>
</div>
<script src="{{ asset('js/register.js') }}"></script>
@endsection
