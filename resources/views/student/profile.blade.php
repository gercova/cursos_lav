@extends('layouts.student')
@section('title', 'Mi perfil')
@section('content')
<div class="max-w-7xl mx-auto" x-data="{ activeTab: 'info' }">
    <!-- Header del perfil -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Mi Perfil</h1>
        <p class="text-gray-600 mt-2">Administra tu información personal y configuración de cuenta</p>
    </div>

    <!-- Alertas -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between animate-slide-in">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between animate-slide-in">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna izquierda - Información de perfil -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <!-- Foto de perfil -->
                <div class="flex flex-col items-center mb-6">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gradient-to-br from-blue-50 to-gray-100">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" alt="Foto de perfil de {{ $user->names }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                                        {{ strtoupper(substr($user->names, 0, 1)) }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Botón para cambiar foto -->
                        <form id="profile-photo-form" action="{{ route('student.profile.update-photo') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                            @csrf
                            <div class="flex flex-col items-center space-y-3">
                                <input type="file" id="profile-photo-input" name="profile_photo" accept="image/*" class="hidden" onchange="document.getElementById('profile-photo-form').submit()">

                                <button type="button" onclick="document.getElementById('profile-photo-input').click()" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center">
                                    <i class="fas fa-camera mr-2"></i>
                                    Cambiar Foto
                                </button>

                                @if($user->profile_photo)
                                <button type="button" onclick="deleteProfilePhoto()" class="px-3 py-1.5 text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200">
                                    <i class="fas fa-trash mr-1"></i>
                                    Eliminar Foto
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Información básica -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->names }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Información de contacto</h4>
                        <div class="space-y-2">
                            @if($user->phone)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone mr-2 text-blue-500 w-5"></i>
                                <span>{{ $user->country_code }} {{ $user->phone }}</span>
                            </div>
                            @endif

                            @if($user->address)
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500 w-5 mt-0.5"></i>
                                <span class="flex-1">{{ $user->address }}</span>
                            </div>
                            @endif

                            @if($user->profession)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-briefcase mr-2 text-blue-500 w-5"></i>
                                <span>{{ $user->profession }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Estadísticas</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">{{ $user->enrollments()->count() }}</p>
                                <p class="text-xs text-gray-600 mt-1">Cursos</p>
                            </div>
                            <div class="text-center p-3 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg">
                                <p class="text-2xl font-bold text-emerald-600">{{ $user->certificates()->count() }}</p>
                                <p class="text-xs text-gray-600 mt-1">Certificados</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha - Formularios con tabs -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Tabs de navegación -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-1">
                        <button @click="activeTab = 'info'"
                                :class="{
                                    'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border-b-2 border-blue-500': activeTab === 'info',
                                    'text-gray-600 hover:text-gray-900 hover:bg-gray-50': activeTab !== 'info'
                                }"
                                class="px-6 py-4 text-sm font-medium transition-all duration-200 flex items-center">
                            <i class="fas fa-user mr-2"></i>
                            Información Personal
                        </button>
                        <button @click="activeTab = 'password'"
                                :class="{
                                    'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border-b-2 border-blue-500': activeTab === 'password',
                                    'text-gray-600 hover:text-gray-900 hover:bg-gray-50': activeTab !== 'password'
                                }"
                                class="px-6 py-4 text-sm font-medium transition-all duration-200 flex items-center">
                            <i class="fas fa-lock mr-2"></i>
                            Cambiar Contraseña
                        </button>
                        <button @click="activeTab = 'privacy'"
                                :class="{
                                    'bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border-b-2 border-blue-500': activeTab === 'privacy',
                                    'text-gray-600 hover:text-gray-900 hover:bg-gray-50': activeTab !== 'privacy'
                                }"
                                class="px-6 py-4 text-sm font-medium transition-all duration-200 flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Privacidad
                        </button>
                    </nav>
                </div>

                <!-- Contenido de los tabs -->
                <div class="p-6">
                    <!-- Tab 1: Información Personal -->
                    <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-6" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Editar información personal</h3>
                            <!-- Grid de campos -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- DNI -->
                                <div>
                                    <label for="dni" class="block text-sm font-medium text-gray-700 mb-2">
                                        DNI / Documento
                                    </label>
                                    <input type="text"
                                        id="dni"
                                        name="dni"
                                        value="{{ old('dni', $user->dni) }}"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('dni') border-red-300 @enderror"
                                        placeholder="Ingresa tu DNI">
                                    @error('dni')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombres -->
                                <div>
                                    <label for="names" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombres Completos *
                                    </label>
                                    <input type="text"
                                        id="names"
                                        name="names"
                                        value="{{ old('names', $user->names) }}"
                                        required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('names') border-red-300 @enderror"
                                        placeholder="Ingresa tus nombres">
                                    @error('names')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo Electrónico *
                                    </label>
                                    <input type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('email') border-red-300 @enderror"
                                        placeholder="ejemplo@correo.com">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Teléfono
                                    </label>
                                    <div class="flex">
                                        <select name="country_code" class="px-4 py-2.5 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 @error('country_code') border-red-300 @enderror">
                                            <option value="+51" {{ old('country_code', $user->country_code) == '+51' ? 'selected' : '' }}>+51 PE</option>
                                            <option value="+1" {{ old('country_code', $user->country_code) == '+1' ? 'selected' : '' }}>+1 US</option>
                                            <option value="+57" {{ old('country_code', $user->country_code) == '+57' ? 'selected' : '' }}>+57 CO</option>
                                            <option value="+34" {{ old('country_code', $user->country_code) == '+34' ? 'selected' : '' }}>+34 ES</option>
                                            <option value="+54" {{ old('country_code', $user->country_code) == '+54' ? 'selected' : '' }}>+54 AR</option>
                                        </select>
                                        <input type="tel"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', $user->phone) }}"
                                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-300 @enderror"
                                            placeholder="987654321">
                                    </div>
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nacionalidad -->
                                <div>
                                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nacionalidad
                                    </label>
                                    <select id="nationality" name="nationality" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nationality') border-red-300 @enderror">
                                        <option value="">Seleccionar nacionalidad</option>
                                        <option value="Peruana" {{ old('nationality', $user->nationality) == 'Peruana' ? 'selected' : '' }}>Peruana</option>
                                        <option value="Colombiana" {{ old('nationality', $user->nationality) == 'Colombiana' ? 'selected' : '' }}>Colombiana</option>
                                        <option value="Argentina" {{ old('nationality', $user->nationality) == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                                        <option value="Mexicana" {{ old('nationality', $user->nationality) == 'Mexicana' ? 'selected' : '' }}>Mexicana</option>
                                        <option value="Española" {{ old('nationality', $user->nationality) == 'Española' ? 'selected' : '' }}>Española</option>
                                        <option value="Estadounidense" {{ old('nationality', $user->nationality) == 'Estadounidense' ? 'selected' : '' }}>Estadounidense</option>
                                    </select>
                                    @error('nationality')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Profesión -->
                                <div>
                                    <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">
                                        Profesión / Ocupación
                                    </label>
                                    <input type="text"
                                        id="profession"
                                        name="profession"
                                        value="{{ old('profession', $user->profession) }}"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('profession') border-red-300 @enderror"
                                        placeholder="Ingresa tu profesión">
                                    @error('profession')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Dirección (full width) -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dirección
                                </label>
                                <textarea id="address"
                                    name="address"
                                    rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('address') border-red-300 @enderror"
                                    placeholder="Ingresa tu dirección completa">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end pt-6 border-t border-gray-200">
                                <button type="reset" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 mr-3">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 2: Cambiar Contraseña -->
                    <div x-show="activeTab === 'password'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        style="display: none;">
                        <form action="{{ route('student.profile.update-password') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cambiar contraseña</h3>
                            <p class="text-gray-600 mb-6">Asegúrate de usar una contraseña segura que no uses en otros sitios.</p>

                            <!-- Contraseña actual -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraseña Actual *
                                </label>
                                <div class="relative">
                                    <input type="password"
                                        id="current_password"
                                        name="current_password"
                                        required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-10 @error('current_password') border-red-300 @enderror"
                                        placeholder="Ingresa tu contraseña actual">
                                    <button type="button" onclick="togglePasswordVisibility('current_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nueva contraseña -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nueva Contraseña *
                                </label>
                                <div class="relative">
                                    <input type="password"
                                        id="password"
                                        name="password"
                                        required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-10 @error('password') border-red-300 @enderror"
                                        placeholder="Ingresa la nueva contraseña">
                                    <button type="button"
                                            onclick="togglePasswordVisibility('password')"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="mt-2 space-y-1">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <span>La contraseña debe tener al menos 8 caracteres</span>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmar nueva contraseña -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirmar Nueva Contraseña *
                                </label>
                                <div class="relative">
                                    <input type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 pr-10"
                                        placeholder="Confirma la nueva contraseña">
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Indicador de fortaleza de contraseña -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Requisitos de seguridad:</h4>
                                <ul class="space-y-1 text-xs text-gray-600">
                                    <li class="flex items-center" id="length-check">
                                        <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                        <span>Al menos 8 caracteres</span>
                                    </li>
                                    <li class="flex items-center" id="uppercase-check">
                                        <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                        <span>Al menos una letra mayúscula</span>
                                    </li>
                                    <li class="flex items-center" id="lowercase-check">
                                        <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                        <span>Al menos una letra minúscula</span>
                                    </li>
                                    <li class="flex items-center" id="number-check">
                                        <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                        <span>Al menos un número</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end pt-6 border-t border-gray-200">
                                <button type="reset" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 mr-3">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                                    <i class="fas fa-key mr-2"></i>
                                    Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tab 3: Privacidad -->
                    <div x-show="activeTab === 'privacy'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        style="display: none;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Configuración de privacidad</h3>
                        <p class="text-gray-600 mb-6">Controla cómo otros usuarios ven tu información en la plataforma.</p>

                        <div class="space-y-6">
                            <!-- Configuraciones de privacidad -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Perfil público</h4>
                                        <p class="text-sm text-gray-600 mt-1">Permite que otros estudiantes vean tu perfil</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Mostrar certificados</h4>
                                        <p class="text-sm text-gray-600 mt-1">Comparte tus certificados con otros estudiantes</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Notificaciones por email</h4>
                                        <p class="text-sm text-gray-600 mt-1">Recibe notificaciones sobre tus cursos</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>
                            </div>

                            <!-- Botón para guardar configuraciones -->
                            <div class="flex justify-end pt-6 border-t border-gray-200">
                                <button type="button" class="px-6 py-2.5 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-lg hover:from-gray-800 hover:to-gray-900 transition-all duration-200 shadow-sm hover:shadow-md flex items-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Guardar Configuración
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de actividad reciente (opcional) -->
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
                <div class="space-y-3">
                    @if($user->enrollments->count() > 0)
                        @foreach($user->enrollments->take(3) as $enrollment)
                        <div class="flex items-center p-3 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-blue-600"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $enrollment->course->title ?? 'Curso' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Inscrito el {{ $enrollment->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $enrollment->progress ?? 0 }}%
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-book-open text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600">No tienes cursos activos</p>
                            <a href="{{ route('cursos') }}" class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Explorar cursos disponibles
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formulario para eliminar foto de perfil -->
<form id="delete-photo-form" action="{{ route('student.profile.delete-photo') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    // Función para mostrar/ocultar contraseña
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Función para eliminar foto de perfil
    function deleteProfilePhoto() {
        if (confirm('¿Estás seguro de eliminar tu foto de perfil?')) {
            document.getElementById('delete-photo-form').submit();
        }
    }

    // Validación de fortaleza de contraseña en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;

                // Actualizar indicadores
                updateCheck('length-check', password.length >= 8);
                updateCheck('uppercase-check', /[A-Z]/.test(password));
                updateCheck('lowercase-check', /[a-z]/.test(password));
                updateCheck('number-check', /\d/.test(password));
            });
        }
    });

    function updateCheck(elementId, condition) {
        const element = document.getElementById(elementId);
        const icon = element.querySelector('i');

        if (condition) {
            icon.classList.remove('text-gray-300');
            icon.classList.add('text-emerald-500');
        } else {
            icon.classList.remove('text-emerald-500');
            icon.classList.add('text-gray-300');
        }
    }

    // Previsualización de imagen antes de subir
    document.getElementById('profile-photo-input')?.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Aquí podrías mostrar una previsualización
                console.log('Imagen cargada:', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>

<style>
    /* Animaciones adicionales */
    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateY(10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Efecto hover para tarjetas */
    .hover-card {
        transition: all 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Estilos para el switch */
    input:checked ~ .dot {
        transform: translateX(100%);
    }
</style>
@endsection
