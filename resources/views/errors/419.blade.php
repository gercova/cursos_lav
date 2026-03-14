@extends('layouts.app')

@section('title', 'Sesión expirada - 419')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
    <div class="text-center">
        <!-- Ilustración 419 -->
        <div class="mb-8">
            <svg class="mx-auto h-40 w-40 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        
        <h1 class="text-6xl font-bold text-gray-900 mb-4">419</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">¡Sesión expirada!</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Tu sesión ha expirado. Por favor, inicia sesión nuevamente para continuar.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Iniciar sesión
            </a>
            
            <a href="{{ url('/') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Ir al inicio
            </a>
        </div>
    </div>
</div>
@endsection