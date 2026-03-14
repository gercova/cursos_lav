@extends('layouts.app')

@section('title', 'Mantenimiento - 503')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-12">
    <div class="text-center">
        <!-- Ilustración 503 -->
        <div class="mb-8">
            <svg class="mx-auto h-40 w-40 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        
        <h1 class="text-6xl font-bold text-gray-900 mb-4">503</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">¡Sitio en mantenimiento!</h2>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
            Estamos realizando mejoras en la plataforma. Por favor, vuelve a intentarlo en unos momentos.
        </p>
        
        <p class="text-sm text-gray-500">
            Estimamos que estaremos de vuelta en <span class="font-semibold">15 minutos</span>
        </p>
    </div>
</div>
@endsection