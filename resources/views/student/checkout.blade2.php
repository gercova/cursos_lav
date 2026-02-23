@extends('layouts.app') {{-- O tu layout principal --}}

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg text-center">
        <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-500 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-800 mb-2">¡Pago Completado!</h2>
        <p class="text-gray-600 mb-6">
            Bienvenido a **IPF Educa**. El pago se procesó correctamente y ya tienes acceso a tu curso.
        </p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-sm text-left">
            <div class="flex justify-between mb-1">
                <span class="text-gray-500">ID de Transacción:</span>
                <span class="font-medium text-gray-800">{{ request('payment_id') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Estado:</span>
                <span class="text-green-600 font-bold">Aprobado</span>
            </div>
        </div>

        <a href="{{ url('/mis-cursos') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition duration-200">
            Empezar a estudiar ahora
        </a>
    </div>
</div>
@endsection