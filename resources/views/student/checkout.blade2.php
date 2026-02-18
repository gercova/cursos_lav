@extends('layouts.app')
@section('title', 'Finalizar Compra')
@section('content')
<div x-data="checkoutApp()" x-init="init()" class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header con logo y pasos -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Finalizar Compra</h1>
                        <p class="text-gray-600 mt-1">Completa tu información para acceder a tus cursos</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 text-sm">
                    <a href="{{ route('cart') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        <span>Volver al carrito</span>
                    </a>
                    <span class="text-gray-400">•</span>
                    <span class="text-gray-500">
                        {{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'curso' : 'cursos' }}
                    </span>
                </div>
            </div>
        </div>

        @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Pasos del checkout -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <div class="relative">
                        <!-- Línea de progreso -->
                        <div class="absolute top-6 left-8 right-8 h-1 bg-gray-200 z-0">
                            <div class="h-1 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full transition-all duration-500" :style="`width: ${progress}%`"></div>
                        </div>

                        <!-- Pasos -->
                        <div class="relative flex justify-between z-10">
                            <div class="flex flex-col items-center">
                                <div :class="currentStep >= 1 ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg transform scale-110' : 'bg-white border-2 border-gray-300 text-gray-400'" class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span :class="currentStep >= 1 ? 'text-blue-600 font-semibold' : 'text-gray-500'" class="text-sm mt-2 transition-colors duration-300">Información</span>
                            </div>

                            <div class="flex flex-col items-center">
                                <div :class="currentStep >= 2 ? 'bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg transform scale-110' : 'bg-white border-2 border-gray-300 text-gray-400'" class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <span :class="currentStep >= 2 ? 'text-purple-600 font-semibold' : 'text-gray-500'" class="text-sm mt-2 transition-colors duration-300">Pago</span>
                            </div>

                            <div class="flex flex-col items-center">
                                <div :class="currentStep >= 3 ? 'bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg transform scale-110' : 'bg-white border-2 border-gray-300 text-gray-400'" class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all duration-300">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span :class="currentStep >= 3 ? 'text-green-600 font-semibold' : 'text-gray-500'" class="text-sm mt-2 transition-colors duration-300">Confirmación</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 1: Información -->
                <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Información personal</h2>
                                <p class="text-gray-600 text-sm">Completa tus datos para el pago y facturación</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="nextStep" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombres -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" x-model="customer.first_name" required @input="saveToLocalStorage" class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Ingresa tus nombres"
                                    >
                                </div>
                            </div>

                            <!-- Apellidos -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" x-model="customer.last_name" required @input="saveToLocalStorage" class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Ingresa tus apellidos">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Correo electrónico <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                    <input type="email" x-model="customer.email" required @input="saveToLocalStorage" class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="ejemplo@correo.com">
                                </div>
                                <p class="text-sm text-gray-500">Te enviaremos la confirmación a este correo</p>
                            </div>

                            <!-- Teléfono -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Teléfono <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="tel" x-model="customer.phone" required @input="saveToLocalStorage" class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="+51 987 654 321">
                                </div>
                            </div>

                            <!-- Tipo de documento -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Tipo de documento
                                </label>
                                <select x-model="customer.document_type" @change="saveToLocalStorage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <option value="">Seleccionar</option>
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CE">Carné de extranjería</option>
                                    <option value="PAS">Pasaporte</option>
                                </select>
                            </div>

                            <!-- Número de documento -->
                            <div class="space-y-2" x-show="customer.document_type">
                                <label class="block text-sm font-medium text-gray-700">
                                    Número de documento <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="customer.document_number" @input="saveToLocalStorage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" :placeholder="'Número de ' + customer.document_type">
                            </div>

                            <!-- Dirección -->
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Dirección completa <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <input type="text" x-model="customer.address" required @input="saveToLocalStorage" class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Ingresa tu dirección completa">
                                </div>
                            </div>

                            <!-- Ciudad y país -->
                            <div class="grid grid-cols-2 gap-4 md:col-span-2">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Ciudad
                                    </label>
                                    <input type="text" x-model="customer.city" @input="saveToLocalStorage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Ej: Lima">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        País
                                    </label>
                                    <select x-model="customer.country" @change="saveToLocalStorage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        <option value="PE">Perú</option>
                                        <option value="CL">Chile</option>
                                        <option value="CO">Colombia</option>
                                        <option value="MX">México</option>
                                        <option value="AR">Argentina</option>
                                        <option value="ES">España</option>
                                        <option value="US">Estados Unidos</option>
                                        <option value="OT">Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de navegación -->
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                                Continuar al pago
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Paso 2: Método de pago -->
                <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-x-4"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-credit-card text-purple-600"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Método de pago</h2>
                                <p class="text-gray-600 text-sm">Elige cómo quieres pagar tus cursos</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="processPayment" class="p-6">
                        <!-- Opciones de pago -->
                        <div class="space-y-4 mb-8">
                            <label @click="paymentMethod = 'card'" :class="paymentMethod === 'card' ? 'border-2 border-blue-500 bg-blue-50' : 'border border-gray-300 hover:border-blue-300 hover:bg-gray-50'" class="block p-5 rounded-xl cursor-pointer transition-all duration-200 transform hover:scale-[1.02]">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div :class="paymentMethod === 'card' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-400'" class="w-12 h-12 rounded-lg flex items-center justify-center transition-colors duration-200">
                                            <i class="fas fa-credit-card text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">Tarjeta de crédito/débito</h3>
                                                <p class="text-sm text-gray-600 mt-1">Paga con Visa, Mastercard, American Express</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <div class="w-10 h-6 bg-blue-500 rounded flex items-center justify-center">
                                                    <span class="text-xs font-bold text-white">VISA</span>
                                                </div>
                                                <div class="w-10 h-6 bg-red-500 rounded flex items-center justify-center">
                                                    <span class="text-xs font-bold text-white">MC</span>
                                                </div>
                                                <div class="w-10 h-6 bg-blue-800 rounded flex items-center justify-center">
                                                    <span class="text-xs font-bold text-white">AX</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label @click="paymentMethod = 'pago_efectivo'" :class="paymentMethod === 'pago_efectivo' ? 'border-2 border-green-500 bg-green-50' : 'border border-gray-300 hover:border-green-300 hover:bg-gray-50'" class="block p-5 rounded-xl cursor-pointer transition-all duration-200 transform hover:scale-[1.02]">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div :class="paymentMethod === 'pago_efectivo' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-400'" class="w-12 h-12 rounded-lg flex items-center justify-center transition-colors duration-200">
                                            <i class="fas fa-money-bill-wave text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="font-semibold text-gray-900">PagoEfectivo</h3>
                                                <p class="text-sm text-gray-600 mt-1">Paga en agentes o banca por internet</p>
                                            </div>
                                            <div class="w-10 h-6 bg-green-500 rounded flex items-center justify-center">
                                                <span class="text-xs font-bold text-white">PE</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Formulario de tarjeta -->
                        <div x-show="paymentMethod === 'card'" x-transition class="space-y-6">
                            <div class="p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                                <h3 class="font-semibold text-blue-800 mb-3">Información de la tarjeta</h3>

                                <!-- Número de tarjeta -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Número de tarjeta <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-credit-card text-gray-400"></i>
                                        </div>
                                        <div id="card-number" class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200"></div>
                                    </div>
                                    <div id="card-number-errors" class="text-red-500 text-sm mt-1"></div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Fecha de expiración -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Expiración <span class="text-red-500">*</span>
                                        </label>
                                        <div id="card-expiry" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200"></div>
                                        <div id="card-expiry-errors" class="text-red-500 text-sm mt-1"></div>
                                    </div>

                                    <!-- CVV -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            CVV <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div id="card-cvv" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all duration-200"></div>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                <button type="button" onclick="showCVVInfo()" class="text-gray-400 hover:text-gray-600">
                                                    <i class="fas fa-question-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="card-cvv-errors" class="text-red-500 text-sm mt-1"></div>
                                    </div>
                                </div>

                                <!-- Nombre en la tarjeta -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Nombre en la tarjeta <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <input type="text" id="card-holder-name" x-model="cardHolderName" required class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Como aparece en la tarjeta">
                                    </div>
                                </div>

                                <!-- Información de seguridad -->
                                <div class="mt-4 p-3 bg-white rounded-lg border border-blue-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                        <span class="text-sm text-gray-700">
                                            Tu información está protegida con encriptación de 256 bits
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de PagoEfectivo -->
                        <div x-show="paymentMethod === 'pago_efectivo'" x-transition class="space-y-6">
                            <div class="p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                <h3 class="font-semibold text-green-800 mb-3">¿Cómo funciona PagoEfectivo?</h3>

                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="font-bold text-green-600">1</span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Generar código CIP</h4>
                                            <p class="text-sm text-gray-600">Al confirmar, generaremos un código único para pagar</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="font-bold text-green-600">2</span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Pagar en agentes</h4>
                                            <p class="text-sm text-gray-600">Lleva el código a cualquier agente autorizado</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="font-bold text-green-600">3</span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Activación automática</h4>
                                            <p class="text-sm text-gray-600">Los cursos se activan al confirmar el pago</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-white rounded-lg border border-green-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-orange-500 mr-2"></i>
                                        <span class="text-sm text-gray-700">
                                            El código CIP tiene validez de 24 horas
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de navegación -->
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                            <button type="button" @click="prevStep" class="px-6 py-3 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg transition-colors duration-200 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver atrás
                            </button>

                            <button type="submit" :disabled="isProcessing" :class="isProcessing ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-xl hover:-translate-y-0.5'" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-200 flex items-center">
                                <template x-if="!isProcessing">
                                    <span>
                                        <i class="fas fa-lock mr-2"></i>
                                        Pagar S/ <span x-text="formatPrice(total)"></span>
                                    </span>
                                </template>
                                <template x-if="isProcessing">
                                    <span>
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                        Procesando...
                                    </span>
                                </template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar con resumen -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- Resumen del pedido -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-900 to-black">
                            <h2 class="text-xl font-bold text-white">Resumen del pedido</h2>
                        </div>

                        <!-- Cursos -->
                        <div class="p-6 max-h-80 overflow-y-auto">
                            <div class="space-y-4">
                                <template x-for="(item, index) in cartItems" :key="item.course.id">
                                    <div class="flex items-start space-x-4 group hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                                        <!-- Imagen del curso -->
                                        <div class="flex-shrink-0 relative">
                                            <img :src="item.course.image_url" :alt="item.course.title" class="w-16 h-12 object-cover rounded-lg">
                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="text-xs text-white font-bold" x-text="index + 1"></span>
                                            </div>
                                        </div>

                                        <!-- Información -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 line-clamp-2" x-text="item.title"></h4>
                                            <p class="text-xs text-gray-500" x-text="item.instructor"></p>

                                            <!-- Precio -->
                                            <div class="mt-1 flex items-center justify-between">
                                                <div>
                                                    <template x-if="item.course.promotion_price">
                                                        <div>
                                                            <span class="text-sm font-bold text-gray-900">
                                                                S/ <span x-text="formatPrice(item.course.promotion_price)"></span>
                                                            </span>
                                                            <span class="text-xs text-gray-500 line-through ml-2">
                                                                S/ <span x-text="formatPrice(item.course.price)"></span>
                                                            </span>
                                                            <span class="ml-2 px-1.5 py-0.5 text-xs font-bold bg-red-100 text-red-800 rounded">
                                                                OFERTA
                                                            </span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!item.promotion_price">
                                                        <span class="text-sm font-bold text-gray-900">
                                                            S/ <span x-text="formatPrice(item.course.price)"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Totales -->
                        <div class="p-6 border-t border-gray-200 bg-gray-50">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium text-gray-900">S/ <span x-text="formatPrice(subtotal)"></span></span>
                                </div>

                                <div x-show="couponDiscount > 0" class="flex justify-between">
                                    <span class="text-green-600">Descuento</span>
                                    <span class="font-medium text-green-600">- S/ <span x-text="formatPrice(couponDiscount)"></span></span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600">IGV (18%)</span>
                                    <span class="font-medium text-gray-900">S/ <span x-text="formatPrice(tax)"></span></span>
                                </div>

                                <div class="pt-3 border-t border-gray-300 flex justify-between text-lg font-bold">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-gray-900">S/ <span x-text="formatPrice(total)"></span></span>
                                </div>
                            </div>

                            <!-- Cupón -->
                            <!--<div class="mt-6">
                                <div x-show="!couponApplied" class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">¿Tienes un cupón?</label>
                                    <div class="flex gap-2">
                                        <input type="text" x-model="couponCode" placeholder="Código de cupón" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <button @click="applyCoupon" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                                            Aplicar
                                        </button>
                                    </div>
                                </div>
                                <div x-show="couponApplied" class="p-3 bg-green-50 rounded-lg border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-green-800 font-medium" x-text="couponCode"></span>
                                            <p class="text-sm text-green-600">Cupón aplicado</p>
                                        </div>
                                        <button @click="removeCoupon"
                                                class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>

                    <!-- Garantías -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-4">Tu compra está protegida</h3>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Pago 100% seguro</h4>
                                    <p class="text-sm text-blue-100 mt-1">Encriptación SSL y certificación PCI DSS</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-undo-alt"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold">30 días de garantía</h4>
                                    <p class="text-sm text-blue-100 mt-1">Reembolso completo si no estás satisfecho</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold">Acceso de por vida</h4>
                                    <p class="text-sm text-blue-100 mt-1">Actualizaciones gratuitas del curso</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Soporte -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">¿Necesitas ayuda?</h3>

                        <div class="space-y-3">
                            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-blue-50 transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors duration-200">
                                    <i class="fas fa-question-circle text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Preguntas frecuentes</p>
                                    <p class="text-sm text-gray-600">Encuentra respuestas rápidas</p>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-3 rounded-lg hover:bg-green-50 transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors duration-200">
                                    <i class="fas fa-headset text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Soporte en vivo</p>
                                    <p class="text-sm text-gray-600">Chat 24/7 con nuestros agentes</p>
                                </div>
                            </a>

                            <a href="tel:+5112345678" class="flex items-center p-3 rounded-lg hover:bg-purple-50 transition-colors duration-200 group">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors duration-200">
                                    <i class="fas fa-phone text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">+51 1 234 5678</p>
                                    <p class="text-sm text-gray-600">Línea directa de soporte</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Estado vacío mejorado -->
        <div class="min-h-[60vh] flex flex-col items-center justify-center">
            <div class="text-center max-w-md">
                <!-- Animación SVG -->
                <div class="relative mx-auto mb-8">
                    <div class="w-48 h-48 mx-auto relative">
                        <!-- Icono de carrito con animación -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-6xl text-gray-300 opacity-20"></i>
                        </div>

                        <!-- Líneas animadas -->
                        <svg class="w-full h-full" viewBox="0 0 200 200">
                            <!-- Círculo de fondo -->
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#f3f4f6" stroke-width="4"/>

                            <!-- Línea animada -->
                            <path id="empty-cart-line" d="M40,100 Q100,40 160,100" fill="none" stroke="#3b82f6" stroke-width="3" stroke-dasharray="200" stroke-dashoffset="200">
                                <animate attributeName="stroke-dashoffset" from="200" to="0" dur="1.5s" fill="freeze"/>
                            </path>
                        </svg>

                        <!-- Icono de alerta -->
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-exclamation text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-4">¡Ups! Carrito vacío</h2>
                <p class="text-gray-600 mb-8">
                    Parece que aún no has agregado cursos a tu carrito.
                    Explora nuestro catálogo y encuentra el curso perfecto para ti.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('cursos') }}" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>
                        Explorar cursos
                    </a>

                    <a href="{{ route('student.dashboard') }}" class="px-8 py-3 border-2 border-gray-300 hover:border-blue-300 hover:bg-blue-50 text-gray-700 font-semibold rounded-lg transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Ir al inicio
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmación de pago -->
<div id="payment-success-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-[9999] hidden p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
        <!-- Encabezado animado -->
        <div class="p-8 text-center relative overflow-hidden">
            <!-- Fondo animado -->
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-emerald-600/10"></div>

            <!-- Check animado -->
            <div class="relative">
                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg animate-scale-up">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-2 animate-fade-in-up">¡Pago exitoso!</h3>
                <p class="text-gray-600 animate-fade-in-up" x-text="paymentMessage"></p>
            </div>
        </div>

        <!-- Detalles del pago -->
        <div class="px-8 pb-8">
            <div class="space-y-4 mb-6">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600">Número de orden</span>
                    <span class="font-mono font-bold text-gray-900" x-text="orderNumber"></span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600">Monto pagado</span>
                    <span class="font-bold text-gray-900">S/ <span x-text="formatPrice(total)"></span></span>
                </div>

                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600">Fecha</span>
                    <span class="font-medium text-gray-900" x-text="paymentDate"></span>
                </div>
            </div>

            <!-- Acciones -->
            <div class="space-y-3">
                <a :href="redirectUrl" class="block w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fas fa-play-circle mr-2"></i>
                    Comenzar a aprender
                </a>

                <button onclick="closeSuccessModal()" class="w-full px-6 py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    Seguir explorando
                </button>
            </div>

            <p class="text-center text-sm text-gray-500 mt-4">
                Te hemos enviado un correo con los detalles de tu compra
            </p>
        </div>
    </div>
</div>

<!-- Modal de información CVV -->
<div id="cvv-info-modal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-[9998] hidden p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-credit-card text-blue-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">¿Dónde encuentro el CVV?</h3>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-sm font-bold text-blue-600">VISA</span>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-700">
                        Es un código de 3 dígitos en el reverso de tu tarjeta
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-sm font-bold text-red-600">AMEX</span>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-700">
                        American Express tiene 4 dígitos en el frente de la tarjeta
                    </p>
                </div>
            </div>
        </div>

        <!-- Imagen de ejemplo -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center">
                <div class="w-16 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded"></div>
                <div class="text-center">
                    <div class="w-12 h-8 bg-gray-200 rounded mb-1 mx-auto"></div>
                    <p class="text-xs text-gray-500">CVV</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="closeCVVModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                Entendido
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Culqi JS -->
<script src="https://checkout.culqi.com/js/v4"></script>

<script>
console.log(@json($cartItems));

function checkoutApp() {
    return {
        cartItems: @json($cartItems),
        subtotal: {{ $subtotal }},
        tax: {{ $tax }},
        total: {{ $total }},
        couponDiscount: {{ $discount ?? 0 }},
        couponCode: '',
        couponApplied: {{ isset($discount) && $discount > 0 ? 'true' : 'false' }},
        currentStep: 1,
        progress: 33,
        paymentMethod: 'card',
        customer: {
            first_name: '{{ auth()->user()->names }}'.split(' ')[0] || '',
            last_name: '{{ auth()->user()->names }}'.split(' ').slice(1).join(' ') || '',
            email: '{{ auth()->user()->email }}',
            phone: '',
            address: '',
            city: '',
            country: 'PE',
            document_type: '',
            document_number: ''
        },
        cardHolderName: '',
        culqi: null,
        isProcessing: false,
        paymentMessage: '',
        redirectUrl: '{{ route("student.my-courses") }}',
        orderNumber: 'ORD-' + Date.now().toString().slice(-8),
        paymentDate: new Date().toLocaleDateString('es-PE', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }),francisco llatas

        init() {
            this.initCulqi();
            this.loadFromLocalStorage();
            this.calculateProgress();

            // Configurar autoguardado
            this.$watch('customer', () => this.saveToLocalStorage(), { deep: true });
        },

        formatPrice(price) {
            return parseFloat(price).toFixed(2);
        },

        calculateProgress() {
            switch(this.currentStep) {
                case 1: this.progress = 33; break;
                case 2: this.progress = 66; break;
                case 3: this.progress = 100; break;
            }
        },

        nextStep() {
            if (this.validateStep1()) {
                this.currentStep = 2;
                this.calculateProgress();
                this.scrollToTop();

                // Inicializar campos de Culqi si se selecciona tarjeta
                if (this.paymentMethod === 'card' && this.culqi) {
                    this.initCulqiFields();
                }
            }
        },

        prevStep() {
            this.currentStep--;
            this.calculateProgress();
            this.scrollToTop();
        },

        validateStep1() {
            const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'address'];
            let isValid = true;

            for (const field of requiredFields) {
                if (!this.customer[field] || this.customer[field].trim() === '') {
                    this.showToast(`Por favor, completa: ${this.getFieldLabel(field)}`, 'error');
                    isValid = false;
                    break;
                }
            }

            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.customer.email && !emailRegex.test(this.customer.email)) {
                this.showToast('Ingresa un correo electrónico válido', 'error');
                isValid = false;
            }

            // Validar documento si se seleccionó tipo
            if (this.customer.document_type && !this.customer.document_number) {
                this.showToast(`Ingresa tu número de ${this.customer.document_type}`, 'error');
                isValid = false;
            }

            return isValid;
        },

        getFieldLabel(field) {
            const labels = {
                first_name: 'Nombres',
                last_name: 'Apellidos',
                email: 'Correo electrónico',
                phone: 'Teléfono',
                address: 'Dirección',
                document_number: 'Número de documento'
            };
            return labels[field] || field;
        },

        initCulqi() {
            Culqi.publicKey = '{{ env("CULQI_PUBLIC_KEY", "pk_test_xxx") }}';

            Culqi.settings({
                title: 'Plataforma de Cursos',
                currency: 'PEN',
                description: `Pago de ${this.cartItems.length} curso(s)`,
                amount: this.total * 100
            });

            Culqi.options({
                onToken: (token) => this.processCulqiToken(token),
                onError: (error) => this.showToast(error.user_message || 'Error en el pago', 'error'),
                onClose: () => this.isProcessing = false
            });

            this.culqi = Culqi;
        },

        initCulqiFields() {
            // Solo inicializar si no están creados
            if (!document.getElementById('card-number').hasChildNodes()) {
                this.culqi.createToken();
            }
        },

        async processPayment() {
            this.isProcessing = true;

            if (this.paymentMethod === 'card') {
                await this.processCardPayment();
            } else {
                await this.processPagoEfectivo();
            }
        },

        async processCardPayment() {
            try {
                await this.culqi.open();
            } catch (error) {
                this.showToast('Error al abrir el formulario de pago', 'error');
                this.isProcessing = false;
            }
        },

        async processCulqiToken(token) {
            try {
                const response = await axios.post('/payment/process-culqi', {
                    token_id: token.id,
                    customer: this.customer,
                    cart_items: this.cartItems,
                    amount: this.total,
                    card_holder_name: this.cardHolderName,
                    order_number: this.orderNumber
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.data.success) {
                    this.showPaymentSuccess(response.data);
                } else {
                    throw new Error(response.data.message);
                }
            } catch (error) {
                this.showToast(error.response?.data?.message || 'Error en el procesamiento del pago', 'error');
            } finally {
                this.isProcessing = false;
            }
        },

        async processPagoEfectivo() {
            try {
                const response = await axios.post('/payment/process-pago-efectivo', {
                    customer: this.customer,
                    cart_items: this.cartItems,
                    amount: this.total,
                    order_number: this.orderNumber
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.data.success) {
                    window.location.href = response.data.redirect_url;
                } else {
                    throw new Error(response.data.message);
                }
            } catch (error) {
                this.showToast(error.response?.data?.message || 'Error al generar el CIP', 'error');
                this.isProcessing = false;
            }
        },

        showPaymentSuccess(data) {
            this.paymentMessage = data.message || '¡Pago procesado exitosamente!';
            this.redirectUrl = data.redirect_url || this.redirectUrl;
            this.currentStep = 3;
            this.calculateProgress();

            // Mostrar modal de éxito
            const modal = document.getElementById('payment-success-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Limpiar localStorage
            localStorage.removeItem('checkout_data');
            localStorage.removeItem('coupon_code');

            // Redirigir automáticamente después de 10 segundos
            setTimeout(() => {
                window.location.href = this.redirectUrl;
            }, 10000);
        },

        showToast(message, type = 'info') {
            const colors = {
                success: 'bg-gradient-to-r from-green-500 to-emerald-600',
                error: 'bg-gradient-to-r from-red-500 to-pink-600',
                warning: 'bg-gradient-to-r from-yellow-500 to-orange-600',
                info: 'bg-gradient-to-r from-blue-500 to-purple-600'
            };

            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 text-white px-6 py-4 rounded-xl shadow-2xl z-[9997] animate-toast-in flex items-center gap-3 min-w-80 max-w-md ${colors[type]}`;
            toast.innerHTML = `
                <div class="flex-shrink-0">
                    <i class="fas ${this.getToastIcon(type)} text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('animate-toast-out');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        },

        getToastIcon(type) {
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            return icons[type] || 'fa-info-circle';
        },

        saveToLocalStorage() {
            const data = {
                customer: this.customer,
                timestamp: Date.now()
            };
            localStorage.setItem('checkout_data', JSON.stringify(data));
        },

        loadFromLocalStorage() {
            try {
                const savedData = localStorage.getItem('checkout_data');
                const savedCoupon = localStorage.getItem('coupon_code');

                if (savedData) {
                    const data = JSON.parse(savedData);

                    // Cargar solo si es reciente (menos de 1 hora)
                    if (Date.now() - data.timestamp < 3600000) {
                        Object.assign(this.customer, data.customer);
                    }
                }

                if (savedCoupon) {
                    this.couponCode = savedCoupon;
                }
            } catch (e) {
                console.error('Error loading saved data:', e);
            }
        },

        async applyCoupon() {
            if (!this.couponCode.trim()) {
                this.showToast('Ingresa un código de cupón', 'warning');
                return;
            }

            try {
                const response = await axios.post('/cart/apply-coupon', {
                    coupon_code: this.couponCode
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.data.success) {
                    this.couponApplied = true;
                    this.couponDiscount = response.data.discount_amount || 0;
                    this.total = this.subtotal - this.couponDiscount + this.tax;

                    localStorage.setItem('coupon_code', this.couponCode);
                    this.showToast('Cupón aplicado exitosamente', 'success');
                } else {
                    this.showToast(response.data.message || 'Cupón inválido', 'error');
                }
            } catch (error) {
                this.showToast('Error al aplicar el cupón', 'error');
            }
        },

        removeCoupon() {
            this.couponApplied = false;
            this.couponCode = '';
            this.couponDiscount = 0;
            this.total = this.subtotal + this.tax;

            localStorage.removeItem('coupon_code');
            this.showToast('Cupón removido', 'info');
        },

        scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    };
}

// Funciones auxiliares globales
function showCVVInfo() {
    document.getElementById('cvv-info-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCVVModal() {
    document.getElementById('cvv-info-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeSuccessModal() {
    document.getElementById('payment-success-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    window.location.href = '{{ route("student.my-courses") }}';
}

// Animaciones CSS
document.addEventListener('DOMContentLoaded', function() {
    const styles = `
        .animate-scale-up {
            animation: scaleUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .animate-toast-in {
            animation: toastIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .animate-toast-out {
            animation: toastOut 0.3s ease-in forwards;
        }

        @keyframes scaleUp {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes toastIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes toastOut {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Scroll personalizado */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Efecto de vidrio esmerilado */
        .backdrop-blur {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    `;

    const styleSheet = document.createElement('style');
    styleSheet.textContent = styles;
    document.head.appendChild(styleSheet);

    // Añadir clase de scroll personalizado a elementos con overflow
    document.querySelectorAll('.overflow-y-auto').forEach(el => {
        el.classList.add('scrollbar-thin');
    });
});
</script>
@endsection
