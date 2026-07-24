@extends('layouts.student')
@section('title', 'Gestión de Empresa')
@section('content')
<div class="min-h-screen bg-gray-50/50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl mx-auto text-center mb-16">
            <div class="relative mx-auto w-20 h-20 mb-6">
                <div class="absolute inset-0 bg-blue-100 rounded-2xl rotate-6 animate-pulse"></div>
                <div class="relative flex items-center justify-center w-20 h-20 bg-white border-2 border-blue-500 rounded-2xl shadow-sm">
                    <i class="bi bi-lock text-3xl text-blue-600"></i>
                </div>
            </div>

            <h1 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">
                Funciones Corporativas Bloqueadas
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                Para gestionar a tus colaboradores en <strong>{{ $enterprise->trade_name }}</strong>, inscribir usuarios de forma masiva y ver sus reportes, necesitas un paquete activo.
            </p>
        </div>

        <div x-data="{ 
            activeSlide: 0,
            @php 
                $packages = \App\Models\Course::where('type', 'package')->where('is_active', true)->get();
            @endphp
            slidesCount: {{ $packages->count() }}
        }" class="relative">
            
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                    Paquetes Recomendados
                </h2>
                <div class="flex gap-2">
                    <button @click="activeSlide = activeSlide > 0 ? activeSlide - 1 : slidesCount - 1" 
                            class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        <i class="bi bi-chevron-left text-gray-600"></i>
                    </button>
                    <button @click="activeSlide = activeSlide < slidesCount - 1 ? activeSlide + 1 : 0" 
                            class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        <i class="bi bi-chevron-right text-gray-600"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-hidden pb-8">
                <div class="flex transition-transform duration-500 ease-in-out -mx-3" 
                     :style="`transform: translateX(-${activeSlide * (100 / (window.innerWidth < 768 ? 1 : (window.innerWidth < 1024 ? 2 : 3)))}%)`">
                    
                    @foreach($packages as $package)
                        <div class="min-w-full md:min-w-[50%] lg:min-w-[33.333%] px-3">
                            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                @if($package->isOnPromotion)
                                    <div class="absolute top-4 left-4 z-10 bg-emerald-500 text-white text-[10px] font-black px-3 py-1 rounded-md uppercase tracking-wider">
                                        Oferta Especial
                                    </div>
                                @endif

                                <div class="relative h-44">
                                    <img src="{{ asset($package->image_url) }}" class="w-full h-full object-cover rounded-t-2xl" alt="{{ $package->title }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <div class="absolute bottom-4 left-4">
                                        <span class="text-white font-bold bg-blue-600/80 backdrop-blur-sm px-3 py-1 rounded-lg text-sm">
                                            {{ $package->seats_max }} Asientos
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $package->title }}</h3>
                                    
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="bi bi-check-circle text-emerald-500 mr-2"></i>
                                            Inscripción masiva habilitada
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="bi bi-check-circle text-emerald-500 mr-2"></i>
                                            {{ $package->course_limit ?: 'Cursos ilimitados' }}
                                        </div>
                                    </div>

                                    <div class="mt-auto border-t border-gray-50 pt-4 flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Inversión</p>
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-2xl font-black text-gray-900">
                                                    S/ {{ number_format($package->promotion_price ?? $package->price, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('paquete.detail', $package->slug) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 shadow-lg shadow-blue-100">
                                            Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-12 p-6 bg-white border border-dashed border-gray-300 rounded-2xl text-center">
            <p class="text-gray-600">
                ¿Buscas una solución a medida para más de 100 empleados? 
                <a href="https://wa.me/923785195" target="_blank" class="text-blue-600 font-bold hover:underline ml-1">
                    Habla con un asesor <i class="fab fa-whatsapp"></i>
                </a>
            </p>
        </div>

    </div>
</div>
@endsection