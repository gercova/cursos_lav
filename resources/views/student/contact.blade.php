@extends('layouts.app')
@section('title', 'PPF Consultores - Contáctanos')
@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-purple-700 py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">
                Contáctanos
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Estamos aquí para ayudarte. ¡Hablemos sobre tu futuro educativo!
            </p>
        </div>
    </div>
</div>

<!-- Contact Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            <!-- Información de Contacto -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Hablemos</h2>
                <p class="text-lg text-gray-600 mb-8">
                    ¿Tienes preguntas sobre nuestros cursos? ¿Necesitas asesoramiento personalizado?
                    Nuestro equipo está listo para ayudarte a alcanzar tus metas educativas.
                </p>

                <!-- Información de Contacto -->
                <div class="space-y-6">
                    <!-- Teléfono -->
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Teléfono</h3>
                            <p class="text-gray-600">+51 987 654 321</p>
                            <p class="text-sm text-gray-500">Lunes a Viernes: 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Email</h3>
                            <p class="text-gray-600">info@eduplatform.com</p>
                            <p class="text-sm text-gray-500">Respondemos en menos de 24 horas</p>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">Oficina Principal</h3>
                            <p class="text-gray-600">Av. Javier Prado 1234</p>
                            <p class="text-gray-600">San Isidro, Lima - Perú</p>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.485"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">WhatsApp</h3>
                            <p class="text-gray-600">+51 987 654 321</p>
                            <p class="text-sm text-gray-500">Chat directo con nuestros asesores</p>
                        </div>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Síguenos en redes</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-gray-100 hover:bg-blue-600 text-gray-600 hover:text-white p-3 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="bg-gray-100 hover:bg-blue-800 text-gray-600 hover:text-white p-3 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="bg-gray-100 hover:bg-blue-700 text-gray-600 hover:text-white p-3 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="bg-gray-100 hover:bg-pink-600 text-gray-600 hover:text-white p-3 rounded-lg transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de Contacto -->
            <div class="bg-gray-50 rounded-2xl p-8 sm:p-10">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Envíanos un mensaje</h2>

                <form id="contact-form" class="space-y-6">
                    @csrf

                    <!-- Alertas -->
                    <div id="form-alert" class="hidden p-4 rounded-lg"></div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo *
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="tel"
                               id="phone"
                               name="phone"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <div id="phone-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Asunto -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Asunto *
                        </label>
                        <select id="subject"
                                name="subject"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <option value="">Selecciona un asunto</option>
                            <option value="info">Información de cursos</option>
                            <option value="support">Soporte técnico</option>
                            <option value="billing">Facturación y pagos</option>
                            <option value="partnership">Alianzas corporativas</option>
                            <option value="other">Otro</option>
                        </select>
                        <div id="subject-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Mensaje -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Mensaje *
                        </label>
                        <textarea id="message"
                                  name="message"
                                  rows="5"
                                  required
                                  placeholder="Cuéntanos en qué podemos ayudarte..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none"></textarea>
                        <div id="message-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Botón de envío -->
                    <div>
                        <button type="submit"
                                id="submit-btn"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span id="submit-text">Enviar Mensaje</span>
                            <div id="submit-loading" class="hidden">
                                <div class="flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                    Enviando...
                                </div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Mapa Section -->
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Visítanos</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Nuestra oficina principal está ubicada en el corazón de San Isidro
            </p>
        </div>

        <!-- Mapa -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="h-96 w-full bg-gray-200 relative">
                <!-- Mapa de Google Maps placeholder -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                    <div class="text-center text-white">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h3 class="text-xl font-bold mb-2">Oficina Principal</h3>
                        <p class="text-blue-100">Av. Javier Prado 1234, San Isidro</p>
                        <p class="text-blue-100">Lima - Perú</p>
                    </div>
                </div>

                <!-- En un entorno real, aquí iría el embed de Google Maps -->
                <!-- <iframe
                    src="https://www.google.com/maps/embed?pb=..."
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe> -->
            </div>

            <div class="p-6 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Horario de Atención</h4>
                        <p class="text-gray-600">Lunes - Viernes</p>
                        <p class="text-gray-600">9:00 AM - 6:00 PM</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Teléfono</h4>
                        <p class="text-gray-600">+51 987 654 321</p>
                        <p class="text-gray-600">+51 1 234 5678</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Email</h4>
                        <p class="text-gray-600">info@eduplatform.com</p>
                        <p class="text-gray-600">asesor@eduplatform.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Preguntas Frecuentes</h2>
            <p class="text-lg text-gray-600">Respuestas a las dudas más comunes</p>
        </div>

        <div class="space-y-6">
            <!-- FAQ Item 1 -->
            <div class="bg-gray-50 rounded-lg p-6">
                <button class="faq-question w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">¿Cómo me inscribo en un curso?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer mt-4 text-gray-600 hidden">
                    <p>Para inscribirte en un curso:</p>
                    <ol class="list-decimal list-inside mt-2 space-y-1">
                        <li>Regístrate en nuestra plataforma</li>
                        <li>Selecciona el curso de tu interés</li>
                        <li>Haz clic en "Agregar al carrito"</li>
                        <li>Completa el proceso de pago</li>
                        <li>¡Listo! Ya tienes acceso al curso</li>
                    </ol>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-gray-50 rounded-lg p-6">
                <button class="faq-question w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">¿Qué métodos de pago aceptan?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer mt-4 text-gray-600 hidden">
                    <p>Aceptamos los siguientes métodos de pago:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Tarjetas de crédito/débito (Visa, MasterCard, American Express)</li>
                        <li>PagoEfectivo</li>
                        <li>Transferencia bancaria</li>
                        <li>Yape y Plin</li>
                        <li>PayPal</li>
                    </ul>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-gray-50 rounded-lg p-6">
                <button class="faq-question w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">¿Puedo acceder al curso desde mi celular?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer mt-4 text-gray-600 hidden">
                    <p>Sí, nuestra plataforma es completamente responsive y puedes acceder desde:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Computadoras de escritorio y laptops</li>
                        <li>Tablets</li>
                        <li>Celulares Android e iPhone</li>
                        <li>Cualquier dispositivo con navegador web</li>
                    </ul>
                    <p class="mt-2">No necesitas descargar ninguna aplicación adicional.</p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-gray-50 rounded-lg p-6">
                <button class="faq-question w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">¿Los certificados son válidos?</h3>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer mt-4 text-gray-600 hidden">
                    <p>Sí, todos nuestros certificados son:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Válidos y verificables</li>
                        <li>Incluyen código QR para verificación</li>
                        <li>Certifican las habilidades adquiridas</li>
                        <li>Son reconocidos por empresas asociadas</li>
                        <li>Pueden ser incluidos en tu CV/LinkedIn</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <p class="text-gray-600 mb-4">¿No encontraste tu respuesta?</p>
            <a href="#contact-form" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200 inline-block">
                Contáctanos Directamente
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-blue-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">¿Listo para comenzar?</h2>
        <p class="text-xl text-blue-100 mb-8">Únete a miles de estudiantes que ya están transformando sus carreras</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg inline-block">
                Crear Cuenta Gratis
            </a>
            <a href="{{ route('cursos') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:bg-white hover:text-blue-600 inline-block">
                Ver Cursos
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo del formulario de contacto
        const contactForm = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitLoading = document.getElementById('submit-loading');
        const formAlert = document.getElementById('form-alert');

        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validación básica
            if (!validateForm()) {
                return;
            }

            // Mostrar loading
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            submitBtn.disabled = true;

            try {
                // Simular envío (en producción, esto sería una petición real)
                await new Promise(resolve => setTimeout(resolve, 2000));

                showAlert('¡Mensaje enviado con éxito! Te contactaremos pronto.', 'success');
                contactForm.reset();

            } catch (error) {
                showAlert('Error al enviar el mensaje. Por favor, intenta nuevamente.', 'error');
            } finally {
                // Ocultar loading
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });

        function validateForm() {
            let isValid = true;
            const fields = ['name', 'email', 'subject', 'message'];

            // Limpiar errores anteriores
            fields.forEach(field => {
                const errorElement = document.getElementById(`${field}-error`);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                }
            });

            // Validar campos requeridos
            fields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    showFieldError(field, 'Este campo es requerido');
                    isValid = false;
                }
            });

            // Validar email
            const email = document.getElementById('email').value;
            if (email && !isValidEmail(email)) {
                showFieldError('email', 'Por favor ingresa un email válido');
                isValid = false;
            }

            return isValid;
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showFieldError(field, message) {
            const errorElement = document.getElementById(`${field}-error`);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');

                // Resaltar campo con error
                const input = document.getElementById(field);
                input.classList.add('border-red-500');

                // Quitar resaltado después de 3 segundos
                setTimeout(() => {
                    input.classList.remove('border-red-500');
                }, 3000);
            }
        }

        function showAlert(message, type) {
            formAlert.textContent = message;
            formAlert.className = `p-4 rounded-lg ${
                type === 'success' ? 'bg-green-100 text-green-700 border border-green-200' :
                'bg-red-100 text-red-700 border border-red-200'
            }`;
            formAlert.classList.remove('hidden');

            // Ocultar alerta después de 5 segundos
            setTimeout(() => {
                formAlert.classList.add('hidden');
            }, 5000);
        }

        // FAQ Accordion
        const faqQuestions = document.querySelectorAll('.faq-question');

        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('svg');

                // Toggle respuesta
                answer.classList.toggle('hidden');

                // Rotar ícono
                icon.classList.toggle('rotate-180');

                // Cerrar otras respuestas
                faqQuestions.forEach(otherQuestion => {
                    if (otherQuestion !== question) {
                        const otherAnswer = otherQuestion.nextElementSibling;
                        const otherIcon = otherQuestion.querySelector('svg');
                        otherAnswer.classList.add('hidden');
                        otherIcon.classList.remove('rotate-180');
                    }
                });
            });
        });

        // Smooth scroll para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Efectos de hover en inputs
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });
    });
</script>

<style>
    .faq-answer {
        transition: all 0.3s ease-in-out;
    }

    input:focus, textarea:focus, select:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    /* Animaciones suaves */
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection
