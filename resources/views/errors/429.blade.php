@extends('layouts.app')

@section('title', 'Demasiadas solicitudes - 429')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
    <div class="text-center">
        <!-- Ilustración 429 -->
        <div class="mb-8">
            <svg class="mx-auto h-40 w-40 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        
        <h1 class="text-6xl font-bold text-gray-900 mb-4">429</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">¡Demasiadas solicitudes!</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Has realizado demasiadas solicitudes en poco tiempo. Por favor, espera unos minutos antes de intentar nuevamente.
        </p>
        
        <div class="flex justify-center">
            <button onclick="setTimeout(() => window.location.reload(), 5000)" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Reintentar (5s)
            </button>
        </div>
    </div>
</div>
@endsection