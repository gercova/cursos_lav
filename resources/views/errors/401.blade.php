@extends('layouts.app')

@section('title', 'No autorizado - 401')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
    <div class="text-center">
        <!-- Ilustración 401 -->
        <div class="mb-8">
            <svg class="mx-auto h-40 w-40 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        
        <h1 class="text-6xl font-bold text-gray-900 mb-4">401</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">¡No autorizado!</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Necesitas iniciar sesión para acceder a esta página.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Iniciar sesión
            </a>
            
            <a href="{{ route('register') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Registrarse
            </a>
        </div>
    </div>
</div>
@endsection