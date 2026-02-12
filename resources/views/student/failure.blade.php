@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg text-center">
        <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-red-100 text-red-500 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pago fallido</h2>
        <p class="text-gray-600 mb-6">
            Lo sentimos, no pudimos procesar tu pago. Por favor, intenta nuevamente con otro medio de pago.
        </p>

        <div class="space-y-3">
            <a href="{{ url('/checkout') }}" class="block w-full bg-gray-800 hover:bg-black text-white font-bold py-3 rounded-xl transition duration-200">
                Reintentar pago
            </a>
            <a href="{{ url('/contacto') }}" class="block text-sm text-blue-600 hover:underline">
                Necesito ayuda técnica
            </a>
        </div>
    </div>
</div>
@endsection