{{--
    Partial: partials.empty
    Uso: @include('partials.empty', ['icon' => 'fa-book-open', 'message' => 'Sin resultados'])

    Parámetros:
    - icon     : string  → clase del icono FontAwesome (ej: 'fa-book-open'). Default: 'fa-inbox'
    - message  : string  → texto principal.                                   Default: 'Sin resultados'
    - subtitle : string  → texto secundario opcional.                         Default: null
    - color    : string  → color base del icono (blue|green|purple|orange|gray|red). Default: 'gray'
--}}

@php
    $icon     = $icon     ?? 'fa-inbox';
    $message  = $message  ?? 'Sin resultados';
    $subtitle = $subtitle ?? null;
    $color    = $color    ?? 'gray';

    $colorMap = [
        'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-400',   'ring' => 'ring-blue-100'],
        'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-400',  'ring' => 'ring-green-100'],
        'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-400', 'ring' => 'ring-purple-100'],
        'orange' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-400', 'ring' => 'ring-orange-100'],
        'red'    => ['bg' => 'bg-red-50',    'icon' => 'text-red-400',    'ring' => 'ring-red-100'],
        'gray'   => ['bg' => 'bg-gray-50',   'icon' => 'text-gray-400',   'ring' => 'ring-gray-100'],
    ];

    $colors = $colorMap[$color] ?? $colorMap['gray'];
@endphp

<div class="flex flex-col items-center justify-center py-14 text-center">
    {{-- Icono --}}
    <div class="w-16 h-16 rounded-2xl {{ $colors['bg'] }} ring-4 {{ $colors['ring'] }} flex items-center justify-center mb-4">
        <i class="fas {{ $icon }} text-2xl {{ $colors['icon'] }}"></i>
    </div>

    {{-- Mensaje principal --}}
    <p class="text-gray-600 font-semibold text-sm">{{ $message }}</p>

    {{-- Subtítulo opcional --}}
    @if($subtitle)
        <p class="text-gray-400 text-xs mt-1.5 max-w-xs leading-relaxed">{{ $subtitle }}</p>
    @endif
</div>