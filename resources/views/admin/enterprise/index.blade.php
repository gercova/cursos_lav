@extends('layouts.admin')
@section('title', 'Configuración de Empresa')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800">Configuración de Empresa</h2>
            <p class="text-gray-600 mt-1">Actualiza la información de tu empresa para mostrarla en la plataforma.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-6 mt-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.enterprise.update') }}" method="POST" enctype="multipart/form-data" class="px-6 py-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda -->
                <div class="space-y-6">
                    <!-- Información Básica -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">RUC *</label>
                                <input type="text" name="ruc" value="{{ old('ruc', $enterprise->ruc ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required maxlength="11">
                                @error('ruc') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Razón Social *</label>
                                <input type="text" name="company_name" value="{{ old('company_name', $enterprise->company_name ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Comercial *</label>
                                <input type="text" name="trade_name" value="{{ old('trade_name', $enterprise->trade_name ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                @error('trade_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Representante Legal -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Representante Legal</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">DNI *</label>
                                <input type="text" name="legal_representative_dni" value="{{ old('legal_representative_dni', $enterprise->legal_representative_dni ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required maxlength="8">
                                @error('legal_representative_dni') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                                <input type="text" name="legal_representative" value="{{ old('legal_representative', $enterprise->legal_representative ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('legal_representative') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Logos -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Logotipos</h3>
                        <div class="space-y-6">
                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo de la Empresa</label>
                                <div class="flex items-center space-x-4">
                                    @if($enterprise->logo_path && Storage::exists($enterprise->logo_path))
                                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ Storage::url($enterprise->logo_path) }}" alt="Logo" class="w-full h-full object-contain">
                                        </div>
                                        <form action="{{ route('admin.enterprise.delete-logo') }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('¿Eliminar logo?')">
                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <input type="file" name="logo" accept="image/*" class="mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Tamaño recomendado: 200x200px. Formatos: JPG, PNG, GIF, SVG. Máx: 2MB</p>
                                @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Favicon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                                <div class="flex items-center space-x-4">
                                    @if($enterprise->favicon_path && Storage::exists($enterprise->favicon_path))
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ Storage::url($enterprise->favicon_path) }}" alt="Favicon" class="w-full h-full object-contain">
                                        </div>
                                        <form action="{{ route('admin.enterprise.delete-favicon') }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('¿Eliminar favicon?')">
                                                <i class="fas fa-trash mr-1"></i> Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <input type="file" name="favicon" accept=".ico,image/*" class="mt-2 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Formato recomendado: .ico o PNG. Tamaño: 16x16px o 32x32px. Máx: 1MB</p>
                                @error('favicon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div class="space-y-6">
                    <!-- Información de Contacto -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                                <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>{{ old('address', $enterprise->address ?? '') }}</textarea>
                                @error('address')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Código Geográfico</label>
                                    <input type="text" name="geographical_code" value="{{ old('geographical_code', $enterprise->geographical_code ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                                    <input type="text" name="city" value="{{ old('city', $enterprise->city ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono 1 *</label>
                                    <input type="text" name="phone_number_1" value="{{ old('phone_number_1', $enterprise->phone_number_1 ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    @error('phone_number_1')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono 2</label>
                                    <input type="text" name="phone_number_2" value="{{ old('phone_number_2', $enterprise->phone_number_2 ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    @error('phone_number_2')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" value="{{ old('email', $enterprise->email ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sector de Negocio</label>
                                <input type="text" name="business_sector" value="{{ old('business_sector', $enterprise->business_sector ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Redes Sociales</h3>
                        <div class="space-y-4">
                            @foreach([
                                'facebook_link' => ['icon' => 'fab fa-facebook', 'color' => 'text-blue-600', 'placeholder' => 'https://facebook.com/tuempresa'],
                                'linkedin_link' => ['icon' => 'fab fa-linkedin', 'color' => 'text-blue-700', 'placeholder' => 'https://linkedin.com/company/tuempresa'],
                                'twitter_link'  => ['icon' => 'fab fa-twitter', 'color' => 'text-blue-400', 'placeholder' => 'https://twitter.com/tuempresa'],
                                'instagram_link' => ['icon' => 'fab fa-instagram', 'color' => 'text-pink-600', 'placeholder' => 'https://instagram.com/tuempresa'],
                                'whatsapp_link' => ['icon' => 'fab fa-whatsapp', 'color' => 'text-green-500', 'placeholder' => 'https://wa.me/51999999999']
                            ] as $field => $info)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <i class="{{ $info['icon'] }} {{ $info['color'] }} mr-2"></i>
                                        {{ ucfirst(str_replace('_', ' ', str_replace('_link', '', $field))) }}
                                    </label>
                                    <input type="url" name="{{ $field }}" value="{{ old($field, $enterprise->$field ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="{{ $info['placeholder'] }}">
                                    @error($field)
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descripción, Misión y Visión -->
            <div class="mt-6 space-y-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sobre la Empresa</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Frase o Eslogan</label>
                            <input type="text" name="phrase" value="{{ old('phrase', $enterprise->phrase ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Tu frase o eslogan aquí">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $enterprise->description ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Descripción general de la empresa</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Misión</label>
                                <textarea name="mission" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('mission', $enterprise->mission ?? '') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Visión</label>
                                <textarea name="vision" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('vision', $enterprise->vision ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Previsualización de imágenes antes de subir
    document.addEventListener('DOMContentLoaded', function() {
        const logoInput     = document.querySelector('input[name="logo"]');
        const faviconInput  = document.querySelector('input[name="favicon"]');

        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Aquí puedes mostrar una previsualización si lo deseas
                        console.log('Logo cargado:', file.name);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (faviconInput) {
            faviconInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    console.log('Favicon cargado:', file.name);
                }
            });
        }
    });
</script>
@endsection
