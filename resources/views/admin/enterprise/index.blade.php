@extends('layouts.admin')
@section('title', 'Configuración de Empresa')
@section('content')

{{-- ══════════════════════════════════════════════════════════════════
     FORMS DE ELIMINACIÓN — FUERA del form principal (HTML válido)
     Los botones dentro del panel los apuntan con form="id"
══════════════════════════════════════════════════════════════════ --}}
<form id="form-delete-logo" action="{{ route('admin.enterprise.delete-logo') }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
<form id="form-delete-favicon" action="{{ route('admin.enterprise.delete-favicon') }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

<div class="admin-enterprise ent-page">
    {{-- Hero --}}
    <div class="ent-hero">
        <div class="ent-hero-icon"><i class="fas fa-building"></i></div>
        <div>
            <h1>Configuración de Empresa</h1>
            <p>Gestiona la identidad, datos y presencia digital de tu organización</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="ent-alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- ══ FORM PRINCIPAL ══════════════════════════════════════════════ --}}
    <form id="form-enterprise" action="{{ route('admin.enterprise.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- Tab nav --}}
        <div class="ent-tabs" role="tablist">
            <button type="button" class="ent-tab active" data-tab="identidad" role="tab">
                <i class="fas fa-id-card"></i> Identidad
            </button>
            <button type="button" class="ent-tab" data-tab="representante" role="tab">
                <i class="fas fa-user-tie"></i> Representante
            </button>
            <button type="button" class="ent-tab" data-tab="contacto" role="tab">
                <i class="fas fa-map-marker-alt"></i> Contacto
            </button>
            <button type="button" class="ent-tab" data-tab="visual" role="tab">
                <i class="fas fa-paint-brush"></i> Identidad Visual
            </button>
            <button type="button" class="ent-tab" data-tab="contenido" role="tab">
                <i class="fas fa-align-left"></i> Contenido
            </button>
            <button type="button" class="ent-tab" data-tab="redes" role="tab">
                <i class="fas fa-share-alt"></i> Redes Sociales
            </button>
            <button type="button" class="ent-tab" data-tab="qr" role="tab">
                <i class="fas fa-qrcode"></i> Compartir QR
            </button>
        </div>

        {{-- ══════════════════════════════════════
            PANEL 1 — IDENTIDAD
        ══════════════════════════════════════ --}}
        <div class="ent-panel active" id="panel-identidad">
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-id-card"></i></div>
                    <h3>Datos Fiscales e Identificación</h3>
                </div>
                <div class="ent-card-body">
                    <div class="grid-2">
                        <div class="field-group">
                            <label class="field-label">
                                RUC <span class="badge-required">REQ</span>
                            </label>
                            <input type="text" name="ruc" class="ent-input" maxlength="11" value="{{ old('ruc', $enterprise->ruc ?? '') }}" placeholder="20123456789" required>
                            @error('ruc')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label">Sector de Negocio</label>
                            <input type="text" name="business_sector" class="ent-input" value="{{ old('business_sector', $enterprise->business_sector ?? '') }}" placeholder="Educación, Tecnología, Salud...">
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            Razón Social <span class="badge-required">REQ</span>
                        </label>
                        <input type="text" name="company_name" class="ent-input"
                            value="{{ old('company_name', $enterprise->company_name ?? '') }}"
                            placeholder="EMPRESA S.A.C." required>
                        @error('company_name')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label">
                            Nombre Comercial <span class="badge-required">REQ</span>
                        </label>
                        <input type="text" name="trade_name" class="ent-input"
                            value="{{ old('trade_name', $enterprise->trade_name ?? '') }}"
                            placeholder="Mi Empresa" required>
                        @error('trade_name')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             PANEL 2 — REPRESENTANTE LEGAL
        ══════════════════════════════════════ --}}
        <div class="ent-panel" id="panel-representante">
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-user-tie"></i></div>
                    <h3>Representante Legal</h3>
                </div>
                <div class="ent-card-body">
                    <div class="grid-2">
                        <div class="field-group">
                            <label class="field-label">
                                DNI <span class="badge-required">REQ</span>
                            </label>
                            <input type="text" name="legal_representative_dni" class="ent-input"
                                maxlength="8"
                                value="{{ old('legal_representative_dni', $enterprise->legal_representative_dni ?? '') }}"
                                placeholder="12345678" required>
                            @error('legal_representative_dni')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label">
                                Nombre Completo <span class="badge-required">REQ</span>
                            </label>
                            <input type="text" name="legal_representative" class="ent-input"
                                value="{{ old('legal_representative', $enterprise->legal_representative ?? '') }}"
                                placeholder="Juan Pérez García" required>
                            @error('legal_representative')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label">Tipo Colegial</label>
                            <input type="text" name="colegial_type" class="ent-input"
                                value="{{ old('colegial_type', $enterprise->colegial_type ?? '') }}"
                                placeholder="CIP, CAP, CMV...">
                            @error('colegial_type')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>

                        <div class="field-group">
                            <label class="field-label">N° Colegial</label>
                            <input type="text" name="colegial" class="ent-input"
                                value="{{ old('colegial', $enterprise->colegial ?? '') }}"
                                placeholder="Número de colegiatura">
                            @error('colegial')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Firma del gerente --}}
                    <div class="field-group" style="margin-top:1.25rem;">
                        <label class="field-label">Firma Digital del Gerente</label>
                        <p class="field-hint" style="margin-bottom:.75rem;">
                            Se guarda como <code>slug(nombre-representante).ext</code> y también
                            como <code>manager_signature.png</code> para documentos y certificados.
                        </p>

                        @php $rawSig = $enterprise->getAttributes()['manager_signature'] ?? null; @endphp
                        @if($rawSig && \Illuminate\Support\Facades\Storage::exists($rawSig))
                            <div class="current-img-row">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($rawSig) }}" alt="Firma actual">
                                <div class="current-img-label">
                                    <p><i class="fas fa-check-circle" style="color:#22c55e;margin-right:.25rem;"></i>Firma actual cargada</p>
                                    <span>Sube una nueva imagen para reemplazarla</span>
                                </div>
                            </div>
                        @endif

                        <div class="img-upload-zone" id="zone-sig">
                            <input type="file" name="signature_photo" id="input-sig"
                                   accept="image/png,image/jpeg,image/gif">
                            <div class="zone-icon"><i class="fas fa-signature"></i></div>
                            <p><strong>Haz clic o arrastra</strong> la imagen de la firma</p>
                            <p style="margin-top:.25rem;">PNG, JPG · Máx 2 MB · Fondo transparente recomendado</p>
                        </div>

                        <div class="js-preview-wrap" id="preview-sig">
                            <p class="js-preview-label"><i class="fas fa-eye" style="margin-right:.25rem;"></i>Vista previa de la nueva firma</p>
                            <img id="img-sig" src="" alt="Vista previa firma" class="sig-preview-box">
                        </div>

                        @error('signature_photo')<p class="field-error" style="margin-top:.5rem;"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             PANEL 3 — CONTACTO
        ══════════════════════════════════════ --}}
        <div class="ent-panel" id="panel-contacto">
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3>Información de Contacto y Ubicación</h3>
                </div>
                <div class="ent-card-body">
                    <div class="field-group">
                        <label class="field-label">Dirección <span class="badge-required">REQ</span></label>
                        <textarea name="address" class="ent-textarea" rows="2" required
                            placeholder="Av. Principal 123, Urb. Centro">{{ old('address', $enterprise->address ?? '') }}</textarea>
                        @error('address')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                    </div>

                    <div class="grid-2">
                        <div class="field-group">
                            <label class="field-label">Código Geográfico</label>
                            <input type="text" name="geographical_code" class="ent-input"
                                value="{{ old('geographical_code', $enterprise->geographical_code ?? '') }}"
                                placeholder="130101">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Ciudad <span class="badge-required">REQ</span></label>
                            <input type="text" name="city" class="ent-input"
                                value="{{ old('city', $enterprise->city ?? '') }}"
                                placeholder="Lima" required>
                            @error('city')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field-group">
                            <label class="field-label">Teléfono Principal <span class="badge-required">REQ</span></label>
                            <input type="text" name="phone_number_1" class="ent-input"
                                value="{{ old('phone_number_1', $enterprise->phone_number_1 ?? '') }}"
                                placeholder="+51 999 999 999" required>
                            @error('phone_number_1')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>
                        <div class="field-group">
                            <label class="field-label">Teléfono Secundario</label>
                            <input type="text" name="phone_number_2" class="ent-input"
                                value="{{ old('phone_number_2', $enterprise->phone_number_2 ?? '') }}"
                                placeholder="+51 888 888 888">
                            @error('phone_number_2')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Email Corporativo <span class="badge-required">REQ</span></label>
                        <input type="email" name="email" class="ent-input"
                            value="{{ old('email', $enterprise->email ?? '') }}"
                            placeholder="contacto@empresa.com" required>
                        @error('email')<p class="field-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             PANEL 4 — IDENTIDAD VISUAL
        ══════════════════════════════════════ --}}
        <div class="ent-panel" id="panel-visual">

            {{-- Logo --}}
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-image"></i></div>
                    <h3>Logo de la Empresa</h3>
                </div>
                <div class="ent-card-body">
                    @php $logoRaw = $enterprise->getAttributes()['logo_path'] ?? null; @endphp
                    @if($logoRaw && \Illuminate\Support\Facades\Storage::exists($logoRaw))
                        <div class="current-img-row">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($logoRaw) }}" alt="Logo actual">
                            <div class="current-img-label">
                                <p><i class="fas fa-check-circle" style="color:#22c55e;margin-right:.25rem;"></i>Logo actual</p>
                                <span>Sube uno nuevo para reemplazarlo</span>
                            </div>
                            {{--
                                form="form-delete-logo" apunta al <form> externo (fuera del form principal).
                                Esto es HTML5 válido y evita el problema de formularios anidados.
                            --}}
                            <button type="submit"
                                    form="form-delete-logo"
                                    class="btn-delete-img"
                                    onclick="return confirm('¿Eliminar el logo actual?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    @endif

                    <div class="img-upload-zone" id="zone-logo">
                        <input type="file" name="logo" id="input-logo" accept="image/*">
                        <div class="zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                        <p><strong>Haz clic o arrastra</strong> el logo aquí</p>
                        <p style="margin-top:.25rem;">PNG, JPG, SVG · Recomendado 200×200 px · Máx 2 MB</p>
                    </div>
                    <div class="js-preview-wrap" id="preview-logo">
                        <p class="js-preview-label"><i class="fas fa-eye" style="margin-right:.25rem;"></i>Vista previa del nuevo logo</p>
                        <img id="img-logo" src="" alt="Vista previa logo" class="img-preview-box">
                    </div>
                    @error('logo')<p class="field-error" style="margin-top:.5rem;"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Favicon --}}
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-star"></i></div>
                    <h3>Favicon del Sitio</h3>
                </div>
                <div class="ent-card-body">
                    @php $faviRaw = $enterprise->getAttributes()['favicon_path'] ?? null; @endphp
                    @if($faviRaw && \Illuminate\Support\Facades\Storage::exists($faviRaw))
                        <div class="current-img-row">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($faviRaw) }}"
                                 alt="Favicon actual" style="width:32px;height:32px;">
                            <div class="current-img-label">
                                <p><i class="fas fa-check-circle" style="color:#22c55e;margin-right:.25rem;"></i>Favicon actual</p>
                                <span>Se muestra en la pestaña del navegador</span>
                            </div>
                            {{-- form="form-delete-favicon" → apunta al <form> externo --}}
                            <button type="submit"
                                    form="form-delete-favicon"
                                    class="btn-delete-img"
                                    onclick="return confirm('¿Eliminar el favicon actual?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    @endif

                    <div class="img-upload-zone" id="zone-fav">
                        <input type="file" name="favicon" id="input-fav" accept=".ico,image/*">
                        <div class="zone-icon"><i class="fas fa-star"></i></div>
                        <p><strong>Haz clic o arrastra</strong> el favicon aquí</p>
                        <p style="margin-top:.25rem;">.ico o PNG · Tamaño ideal 32×32 px · Máx 1 MB</p>
                    </div>
                    <div class="js-preview-wrap" id="preview-fav">
                        <p class="js-preview-label"><i class="fas fa-eye" style="margin-right:.25rem;"></i>Vista previa del nuevo favicon</p>
                        <img id="img-fav" src="" alt="Vista previa favicon" class="img-preview-box" style="max-height:80px;">
                    </div>
                    @error('favicon')<p class="field-error" style="margin-top:.5rem;"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════
            PANEL 5 — CONTENIDO
        ══════════════════════════════════════ --}}
        <div class="ent-panel" id="panel-contenido">
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-align-left"></i></div>
                    <h3>Sobre la Empresa</h3>
                </div>
                <div class="ent-card-body">
                    <div class="field-group">
                        <label class="field-label">Frase o Eslogan</label>
                        <input type="text" name="phrase" class="ent-input"
                            value="{{ old('phrase', $enterprise->phrase ?? '') }}"
                            placeholder="Tu frase inspiradora aquí...">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Descripción General</label>
                        <textarea name="description" class="ent-textarea" rows="4"
                            placeholder="Breve descripción de la empresa...">{{ old('description', $enterprise->description ?? '') }}</textarea>
                        <p class="field-hint">Se muestra en la página principal y materiales de presentación.</p>
                    </div>

                    <div class="grid-2">
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-rocket" style="color:#f59e0b;margin-right:.3rem;"></i>Misión
                            </label>
                            <textarea name="mission" class="ent-textarea" rows="5"
                                placeholder="¿Qué hace tu empresa y para quién?">{{ old('mission', $enterprise->mission ?? '') }}</textarea>
                        </div>
                        <div class="field-group">
                            <label class="field-label">
                                <i class="fas fa-eye" style="color:#8b5cf6;margin-right:.3rem;"></i>Visión
                            </label>
                            <textarea name="vision" class="ent-textarea" rows="5"
                                placeholder="¿A dónde quieres llegar?">{{ old('vision', $enterprise->vision ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
            PANEL 6 — REDES SOCIALES
        ══════════════════════════════════════ --}}
        <div class="ent-panel" id="panel-redes">
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-share-alt"></i></div>
                    <h3>Redes Sociales y Canales Digitales</h3>
                </div>
                <div class="ent-card-body">
                    @php
                        $socials = [
                            'facebook_link'  => ['icon' => 'fab fa-facebook',  'color' => '#1877f2', 'label' => 'Facebook',    'ph' => 'https://facebook.com/tuempresa'],
                            'linkedin_link'  => ['icon' => 'fab fa-linkedin',  'color' => '#0a66c2', 'label' => 'LinkedIn',    'ph' => 'https://linkedin.com/company/tuempresa'],
                            'twitter_link'   => ['icon' => 'fab fa-twitter',   'color' => '#1da1f2', 'label' => 'Twitter / X', 'ph' => 'https://twitter.com/tuempresa'],
                            'instagram_link' => ['icon' => 'fab fa-instagram', 'color' => '#e1306c', 'label' => 'Instagram',   'ph' => 'https://instagram.com/tuempresa'],
                            'whatsapp_link'  => ['icon' => 'fab fa-whatsapp',  'color' => '#25d366', 'label' => 'WhatsApp',    'ph' => 'https://wa.me/51999999999'],
                        ];
                    @endphp
                    <div style="display:grid;gap:1rem;">
                        @foreach($socials as $field => $info)
                            <div class="field-group" style="margin-bottom:0;">
                                <label class="field-label">{{ $info['label'] }}</label>
                                <div class="social-input-wrap">
                                    <span class="social-icon-badge">
                                        <i class="{{ $info['icon'] }}" style="color:{{ $info['color'] }};"></i>
                                    </span>
                                    <input type="url" name="{{ $field }}"
                                        value="{{ old($field, $enterprise->$field ?? '') }}"
                                        placeholder="{{ $info['ph'] }}">
                                </div>
                                @error($field)<p class="field-error" style="margin-top:.3rem;"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             PANEL 7 — COMPARTIR QR
        ══════════════════════════════════════ --}}
        <div class="ent-panel" id="panel-qr">
            <div class="ent-card">
                <div class="ent-card-header">
                    <div class="ent-card-header-icon"><i class="fas fa-qrcode"></i></div>
                    <h3>Código QR Corporativo</h3>
                </div>
                <div class="ent-card-body">
                    <div class="grid-2">
                        <!-- Columna Izquierda: QR y Acciones -->
                        <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                            <div id="qrcode-container" style="background-color: #ffffff; padding: 1.25rem; border-radius: 1rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06); border: 1px solid #cbd5e1; transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                                <div id="qrcode" style="display: flex; justify-content: center; align-items: center; min-width: 200px; min-height: 200px;"></div>
                            </div>
                            
                            <div style="margin-top: 1.5rem; display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: center; width: 100%;">
                                <button type="button" id="btn-download-qr" class="btn-save" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); padding: 0.6rem 1.2rem; font-size: 0.82rem; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);">
                                    <i class="fas fa-download"></i> Descargar Imagen
                                </button>
                                <button type="button" id="btn-share-qr" class="btn-save" style="background: linear-gradient(135deg, #0d9488, #0f766e); padding: 0.6rem 1.2rem; font-size: 0.82rem; box-shadow: 0 4px 10px rgba(13, 148, 136, 0.2);">
                                    <i class="fas fa-share-alt"></i> Compartir QR
                                </button>
                                <button type="button" id="btn-copy-link" class="btn-cancel" style="padding: 0.55rem 1.2rem; font-size: 0.82rem;">
                                    <i class="fas fa-link"></i> Copiar Enlace
                                </button>
                            </div>
                        </div>

                        <!-- Columna Derecha: Configuración de campos -->
                        <div style="display: flex; flex-direction: column; justify-content: space-between; gap: 1.5rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <h4 style="font-weight: 700; color: #334155; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Información a incluir en el QR:</h4>
                                
                                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; bg-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                                    <input type="checkbox" id="chk-trade-name" checked style="width: 1.1rem; height: 1.1rem; accent-color: #1d4ed8;">
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 600; color: #0f172a; margin: 0;">Nombre Comercial</p>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $enterprise->trade_name ?? 'No especificado' }}</p>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; bg-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                                    <input type="checkbox" id="chk-link" checked style="width: 1.1rem; height: 1.1rem; accent-color: #1d4ed8;">
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 600; color: #0f172a; margin: 0;">Enlace de la Plataforma</p>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ url('/') }}</p>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; bg-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                                    <input type="checkbox" id="chk-ruc" style="width: 1.1rem; height: 1.1rem; accent-color: #1d4ed8;">
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 600; color: #0f172a; margin: 0;">RUC</p>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $enterprise->ruc ?? 'No especificado' }}</p>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; bg-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                                    <input type="checkbox" id="chk-email" style="width: 1.1rem; height: 1.1rem; accent-color: #1d4ed8;">
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 600; color: #0f172a; margin: 0;">Correo Electrónico</p>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $enterprise->email ?? 'No especificado' }}</p>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; bg-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                                    <input type="checkbox" id="chk-phone" style="width: 1.1rem; height: 1.1rem; accent-color: #1d4ed8;">
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 600; color: #0f172a; margin: 0;">Teléfono</p>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $enterprise->phone_number_1 ?? 'No especificado' }}</p>
                                    </div>
                                </label>

                                <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; bg-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.15s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                                    <input type="checkbox" id="chk-address" style="width: 1.1rem; height: 1.1rem; accent-color: #1d4ed8;">
                                    <div>
                                        <p style="font-size: 0.85rem; font-weight: 600; color: #0f172a; margin: 0;">Dirección y Ciudad</p>
                                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $enterprise->address ?? '' }}, {{ $enterprise->city ?? '' }}</p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Preview de texto crudo -->
                            <div style="background-color: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem;">
                                <span style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; display: block;">Texto codificado en el QR:</span>
                                <pre id="qr-text-preview" style="margin-top: 0.35rem; font-size: 0.75rem; color: #334155; white-space: pre-wrap; font-family: monospace; line-height: 1.4;"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Action bar ── --}}
        <div class="ent-actions">
            <div style="display:flex;align-items:center;gap:.5rem;">
                <div class="tab-progress">
                    @foreach(['identidad','representante','contacto','visual','contenido','redes','qr'] as $tp)
                        <div class="tab-dot {{ $tp === 'identidad' ? 'active' : '' }}" data-dot="{{ $tp }}"></div>
                    @endforeach
                </div>
                <span style="font-size:.78rem;color:var(--brand-muted);margin-left:.5rem;">Completa todos los campos</span>
            </div>
            <div style="display:flex;gap:.75rem;align-items:center;">
                <a href="{{ route('admin.dashboard') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>

    </form>{{-- /form-enterprise --}}
</div>

@endsection

@section('scripts')
<script>
    (function () {
        // ── Tab switching ──────────────────────────────────────────
        const tabs   = document.querySelectorAll('.ent-tab');
        const panels = document.querySelectorAll('.ent-panel');
        const dots   = document.querySelectorAll('.tab-dot');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.tab;
                tabs.forEach(t => t.classList.remove('active'));
                panels.forEach(p => p.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById('panel-' + target)?.classList.add('active');
                document.querySelector(`.tab-dot[data-dot="${target}"]`)?.classList.add('active');
            });
        });

        // ── Image preview genérico ─────────────────────────────────
        function bindPreview(inputId, imgId, wrapId) {
            const input = document.getElementById(inputId);
            const img   = document.getElementById(imgId);
            const wrap  = document.getElementById(wrapId);
            if (!input || !img || !wrap) return;

            input.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) { wrap.classList.remove('visible'); return; }
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; wrap.classList.add('visible'); };
                reader.readAsDataURL(file);
            });
        }

        bindPreview('input-logo', 'img-logo', 'preview-logo');
        bindPreview('input-fav',  'img-fav',  'preview-fav');
        bindPreview('input-sig',  'img-sig',  'preview-sig');

        // ── Drag-over feedback ─────────────────────────────────────
        ['zone-logo', 'zone-fav', 'zone-sig'].forEach(id => {
            const zone = document.getElementById(id);
            if (!zone) return;
            zone.addEventListener('dragover',  () => zone.style.borderColor = 'var(--brand-blue)');
            zone.addEventListener('dragleave', () => zone.style.borderColor = 'var(--brand-border)');
            zone.addEventListener('drop',      () => zone.style.borderColor = '#86efac');
        });

        // ── Lógica de Código QR Corporativo ──────────────────────
        let qrCodeInstance = null;
        
        function loadQrLibrary(callback) {
            if (window.QRCode) {
                callback();
                return;
            }
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
            script.onload = callback;
            document.head.appendChild(script);
        }

        function updateQR() {
            const container = document.getElementById('qrcode');
            if (!container) return;

            // Obtener estado de los checkboxes
            const includeTradeName = document.getElementById('chk-trade-name')?.checked;
            const includeLink = document.getElementById('chk-link')?.checked;
            const includeRuc = document.getElementById('chk-ruc')?.checked;
            const includeEmail = document.getElementById('chk-email')?.checked;
            const includePhone = document.getElementById('chk-phone')?.checked;
            const includeAddress = document.getElementById('chk-address')?.checked;

            const tradeName = "{{ $enterprise->trade_name ?? '' }}";
            const link = "{{ url('/') }}";
            const ruc = "{{ $enterprise->ruc ?? '' }}";
            const email = "{{ $enterprise->email ?? '' }}";
            const phone = "{{ $enterprise->phone_number_1 ?? '' }}";
            const address = "{{ $enterprise->address ?? '' }}, {{ $enterprise->city ?? '' }}";

            let lines = [];
            if (includeTradeName && tradeName) lines.push(tradeName);
            if (includeLink && link) lines.push(link);
            if (includeRuc && ruc) lines.push(`RUC: ${ruc}`);
            if (includeEmail && email) lines.push(`Email: ${email}`);
            if (includePhone && phone) lines.push(`Tel: ${phone}`);
            if (includeAddress && address.trim() !== ',') lines.push(`Dirección: ${address}`);

            const qrText = lines.join('\n');

            // Actualizar vista previa del texto codificado
            const previewEl = document.getElementById('qr-text-preview');
            if (previewEl) {
                previewEl.textContent = qrText || '(Ningún campo seleccionado)';
            }

            // Limpiar contenedor anterior
            container.innerHTML = '';

            if (!qrText) {
                container.innerHTML = '<span style="color:#94a3b8; font-size:0.85rem; text-align:center;">Selecciona al menos un campo</span>';
                return;
            }

            // Generar nuevo código QR
            qrCodeInstance = new QRCode(container, {
                text: qrText,
                width: 200,
                height: 200,
                colorDark: "#1e293b",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }

        // Inicializar cuando se hace clic en la pestaña de QR o si ya está activa
        document.querySelectorAll('.ent-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                if (tab.dataset.tab === 'qr') {
                    loadQrLibrary(() => {
                        updateQR();
                    });
                }
            });
        });

        // Escuchar cambios en los checkboxes
        ['chk-trade-name', 'chk-link', 'chk-ruc', 'chk-email', 'chk-phone', 'chk-address'].forEach(id => {
            const chk = document.getElementById(id);
            if (chk) {
                chk.addEventListener('change', updateQR);
            }
        });

        // Descargar QR como imagen
        const btnDownload = document.getElementById('btn-download-qr');
        if (btnDownload) {
            btnDownload.addEventListener('click', () => {
                const canvas = document.querySelector('#qrcode canvas');
                const img = document.querySelector('#qrcode img');
                let dataUrl = '';
                
                if (canvas) {
                    dataUrl = canvas.toDataURL('image/png');
                } else if (img) {
                    dataUrl = img.src;
                }

                if (!dataUrl) {
                    alert('Por favor, genera primero el código QR.');
                    return;
                }

                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = `qr-corporativo-${Date.now()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        }

        // Copiar Enlace al portapapeles con feedback visual
        const btnCopy = document.getElementById('btn-copy-link');
        if (btnCopy) {
            btnCopy.addEventListener('click', () => {
                const linkVal = "{{ url('/') }}";
                navigator.clipboard.writeText(linkVal).then(() => {
                    const originalHTML = btnCopy.innerHTML;
                    btnCopy.innerHTML = '<i class="fas fa-check" style="color:#22c55e;"></i> ¡Copiado!';
                    btnCopy.style.borderColor = '#22c55e';
                    setTimeout(() => {
                        btnCopy.innerHTML = originalHTML;
                        btnCopy.style.borderColor = '';
                    }, 2000);
                }).catch(err => {
                    console.error('Error al copiar: ', err);
                });
            });
        }

        // Compartir QR usando Web Share API
        const btnShare = document.getElementById('btn-share-qr');
        if (btnShare) {
            btnShare.addEventListener('click', () => {
                const canvas = document.querySelector('#qrcode canvas');
                const tradeName = "{{ $enterprise->trade_name ?? 'Empresa' }}";
                const link = "{{ url('/') }}";

                if (canvas && navigator.share && navigator.canShare && HTMLCanvasElement.prototype.toBlob) {
                    canvas.toBlob(blob => {
                        const file = new File([blob], 'qr-empresa.png', { type: 'image/png' });
                        if (navigator.canShare({ files: [file] })) {
                            navigator.share({
                                files: [file],
                                title: `Código QR de ${tradeName}`,
                                text: `Código QR corporativo de ${tradeName}`
                            }).catch(err => console.log('Share failed', err));
                        } else {
                            // Fallback compartir texto
                            navigator.share({
                                title: tradeName,
                                text: `Visita ${tradeName} en: ${link}`,
                                url: link
                            }).catch(err => console.log('Share text failed', err));
                        }
                    }, 'image/png');
                } else if (navigator.share) {
                    navigator.share({
                        title: tradeName,
                        text: `Visita ${tradeName} en: ${link}`,
                        url: link
                    }).catch(err => console.log('Share failed', err));
                } else {
                    // Fallback definitivo: Copiar texto del QR al portapapeles
                    const qrText = document.getElementById('qr-text-preview')?.textContent || link;
                    navigator.clipboard.writeText(qrText).then(() => {
                        const originalHTML = btnShare.innerHTML;
                        btnShare.innerHTML = '<i class="fas fa-check" style="color:#22c55e;"></i> ¡Copiado al portapapeles!';
                        setTimeout(() => {
                            btnShare.innerHTML = originalHTML;
                        }, 2000);
                    });
                }
            });
        }
    })();
</script>

<style>
    :root {
        --brand-blue:    #1d4ed8;
        --brand-light:   #eff6ff;
        --brand-surface: #f8fafc;
        --brand-border:  #e2e8f0;
        --brand-text:    #0f172a;
        --brand-muted:   #64748b;
        --tab-active-bg: #1d4ed8;
        --tab-active-fg: #ffffff;
    }

    .admin-enterprise h1,
    .admin-enterprise h2,
    .admin-enterprise h3 { font-family: 'Syne', sans-serif; }

    .ent-page { max-width: 1100px; margin: 0 auto; }

    .ent-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #1d4ed8 100%);
        border-radius: 20px; padding: 2rem 2.5rem;
        display: flex; align-items: center; gap: 1.5rem;
        margin-bottom: 2rem; position: relative; overflow: hidden;
    }
    .ent-hero::before {
        content: ''; position: absolute;
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(99,102,241,.35) 0%, transparent 70%);
        top: -80px; right: -60px; pointer-events: none;
    }
    .ent-hero-icon {
        width: 56px; height: 56px; background: rgba(255,255,255,.12);
        border-radius: 16px; display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(6px); border: 1px solid rgba(255,255,255,.18); flex-shrink: 0;
    }
    .ent-hero-icon i { font-size: 1.5rem; color: #fff; }
    .ent-hero h1 { font-size: 1.6rem; color: #fff; margin: 0; font-weight: 800; }
    .ent-hero p  { color: rgba(255,255,255,.7); margin: .25rem 0 0; font-size: .9rem; }

    .ent-tabs {
        display: flex; gap: .5rem; background: #fff;
        border-radius: 16px; padding: .5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.07);
        margin-bottom: 1.75rem; overflow-x: auto; flex-wrap: nowrap;
    }
    .ent-tab {
        flex: 1; min-width: 140px; padding: .65rem 1rem;
        border-radius: 10px; border: none; background: transparent;
        color: var(--brand-muted); font-family: 'Syne', sans-serif;
        font-size: .82rem; font-weight: 600; cursor: pointer;
        white-space: nowrap; display: flex; align-items: center;
        justify-content: center; gap: .4rem; transition: all .2s;
    }
    .ent-tab:hover { background: var(--brand-light); color: var(--brand-blue); }
    .ent-tab.active {
        background: var(--tab-active-bg); color: var(--tab-active-fg);
        box-shadow: 0 4px 12px rgba(29,78,216,.3);
    }
    .ent-tab i { font-size: .9rem; }

    .ent-panel { display: none; }
    .ent-panel.active { display: block; animation: fadeIn .25s ease; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }

    .ent-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border: 1px solid var(--brand-border); overflow: hidden; margin-bottom: 1.25rem;
    }
    .ent-card-header {
        padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--brand-border);
        display: flex; align-items: center; gap: .75rem; background: var(--brand-surface);
    }
    .ent-card-header-icon {
        width: 34px; height: 34px; border-radius: 9px;
        background: var(--brand-light); display: flex; align-items: center;
        justify-content: center; color: var(--brand-blue); font-size: .9rem;
    }
    .ent-card-header h3 { font-size: 1rem; font-weight: 700; color: var(--brand-text); margin: 0; }
    .ent-card-body { padding: 1.5rem; }

    .field-group { margin-bottom: 1.1rem; }
    .field-group:last-child { margin-bottom: 0; }
    .field-label {
        display: block; font-size: .8rem; font-weight: 600;
        color: var(--brand-text); margin-bottom: .4rem; letter-spacing: .02em;
    }
    .field-label .badge-required {
        display: inline-block; background: #fee2e2; color: #dc2626;
        font-size: .65rem; padding: 1px 5px; border-radius: 4px;
        margin-left: .35rem; font-weight: 700;
    }
    .ent-input, .ent-textarea {
        width: 100%; padding: .6rem .9rem;
        border: 1.5px solid var(--brand-border); border-radius: 10px;
        font-size: .88rem; color: var(--brand-text); background: #fff;
        transition: border-color .15s, box-shadow .15s;
        font-family: 'DM Sans', sans-serif; outline: none; box-sizing: border-box;
    }
    .ent-input:focus, .ent-textarea:focus {
        border-color: var(--brand-blue); box-shadow: 0 0 0 3px rgba(29,78,216,.1);
    }
    .ent-textarea { resize: vertical; min-height: 90px; }
    .field-hint  { font-size: .73rem; color: var(--brand-muted); margin-top: .3rem; }
    .field-error {
        font-size: .77rem; color: #dc2626; margin-top: .3rem;
        display: flex; align-items: center; gap: .25rem;
    }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }

    .img-upload-zone {
        border: 2px dashed var(--brand-border); border-radius: 14px;
        padding: 1.5rem 1rem; text-align: center; cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative; background: var(--brand-surface);
    }
    .img-upload-zone:hover { border-color: var(--brand-blue); background: var(--brand-light); }
    .img-upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .zone-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: var(--brand-light); display: flex; align-items: center;
        justify-content: center; color: var(--brand-blue); font-size: 1.1rem; margin: 0 auto .75rem;
    }
    .img-upload-zone p { font-size: .8rem; color: var(--brand-muted); margin: 0; }
    .img-upload-zone p strong { color: var(--brand-blue); }

    .current-img-row {
        display: flex; align-items: center; gap: 1rem;
        padding: .75rem 1rem; background: var(--brand-light);
        border-radius: 10px; margin-bottom: .75rem; border: 1px solid #bfdbfe;
    }
    .current-img-row img { width: 52px; height: 52px; object-fit: contain; border-radius: 8px; }
    .current-img-label { flex: 1; }
    .current-img-label p    { font-size: .8rem; color: var(--brand-blue); font-weight: 600; margin: 0; }
    .current-img-label span { font-size: .72rem; color: var(--brand-muted); }
    .btn-delete-img {
        background: #fee2e2; color: #dc2626; border: none; border-radius: 8px;
        padding: .4rem .75rem; font-size: .75rem; font-weight: 600; cursor: pointer;
        transition: background .15s; display: flex; align-items: center; gap: .3rem;
    }
    .btn-delete-img:hover { background: #fecaca; }

    .js-preview-wrap { display: none; margin-top: .75rem; text-align: center; }
    .js-preview-wrap.visible { display: block; }
    .js-preview-wrap img {
        max-width: 100%; max-height: 140px; object-fit: contain;
        border-radius: 10px; border: 2px solid #86efac;
    }
    .js-preview-label { font-size: .72rem; color: #16a34a; font-weight: 600; margin-bottom: .35rem; }
    .img-preview-box {
        width: 100%; max-height: 160px; object-fit: contain;
        border-radius: 10px; border: 2px solid var(--brand-border);
        background: var(--brand-surface); display: block;
    }
    .sig-preview-box {
        width: 100%; max-height: 120px; object-fit: contain;
        border-radius: 10px; border: 2px solid var(--brand-border);
        padding: .5rem; background: #f9f9f9;
    }

    .social-input-wrap {
        display: flex; align-items: center;
        border: 1.5px solid var(--brand-border); border-radius: 10px;
        overflow: hidden; transition: border-color .15s, box-shadow .15s;
    }
    .social-input-wrap:focus-within {
        border-color: var(--brand-blue); box-shadow: 0 0 0 3px rgba(29,78,216,.1);
    }
    .social-icon-badge {
        width: 42px; display: flex; align-items: center; justify-content: center;
        background: var(--brand-surface); border-right: 1.5px solid var(--brand-border);
        padding: .6rem 0; font-size: 1rem;
    }
    .social-input-wrap input {
        flex: 1; border: none; padding: .6rem .9rem;
        font-size: .85rem; font-family: 'DM Sans', sans-serif;
        outline: none; color: var(--brand-text); background: #fff;
    }

    .ent-actions {
        background: #fff; border-radius: 16px; padding: 1rem 1.5rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.07); border: 1px solid var(--brand-border);
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; margin-top: 1.5rem;
    }
    .btn-cancel {
        padding: .65rem 1.4rem; border: 1.5px solid var(--brand-border);
        border-radius: 10px; background: #fff; color: var(--brand-muted);
        font-size: .88rem; font-weight: 600; text-decoration: none;
        display: inline-flex; align-items: center; gap: .4rem; transition: all .15s;
    }
    .btn-cancel:hover { background: var(--brand-surface); color: var(--brand-text); }
    .btn-save {
        padding: .7rem 1.75rem;
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        border: none; border-radius: 10px; color: #fff;
        font-size: .9rem; font-weight: 700; cursor: pointer;
        font-family: 'Syne', sans-serif;
        display: inline-flex; align-items: center; gap: .5rem;
        box-shadow: 0 4px 14px rgba(29,78,216,.35);
        transition: box-shadow .2s, transform .15s;
    }
    .btn-save:hover  { box-shadow: 0 6px 20px rgba(29,78,216,.45); transform: translateY(-1px); }
    .btn-save:active { transform: translateY(0); }

    .ent-alert-success {
        display: flex; align-items: center; gap: .75rem;
        background: #f0fdf4; border: 1px solid #86efac;
        border-radius: 12px; padding: .85rem 1.25rem;
        margin-bottom: 1.5rem; color: #166534; font-size: .88rem;
    }
    .ent-alert-success i { color: #22c55e; font-size: 1rem; }

    .tab-progress { display: flex; gap: .35rem; align-items: center; }
    .tab-dot {
        width: 7px; height: 7px; border-radius: 50%; background: #cbd5e1;
        transition: background .2s, transform .2s;
    }
    .tab-dot.active { background: var(--brand-blue); transform: scale(1.3); }
</style>
@endsection