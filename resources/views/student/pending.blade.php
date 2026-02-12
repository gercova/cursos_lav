@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-lg text-center">
        <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-yellow-100 text-yellow-500 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pago en proceso</h2>
        <p class="text-gray-600 mb-6">
            Estamos esperando la confirmación de tu banco. Esto puede tardar unos minutos o hasta 24 horas si fue por agente.
        </p>

        <p class="text-sm text-gray-500 mb-6">
            Te enviaremos un correo electrónico a <strong>{{ auth()->user()->email ?? 'tu cuenta' }}</strong> en cuanto se confirme el acceso.
        </p>

        <a href="{{ url('/home') }}" class="block w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-3 rounded-xl transition duration-200">
            Volver al inicio
        </a>
    </div>
</div>
@endsection