@props([
    'context' => 'public',
    'standalone' => false,
    'enabled' => true,
    'theme' => null,
])

@php
    $appearance = app(\App\Support\LoginAppearance::class)->resolve($theme, $context);
    $backgroundImage = $appearance['image']
        ? 'url('.json_encode(
            $appearance['image'],
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES,
        ).')'
        : 'none';
@endphp

<div
    data-login-appearance="{{ $appearance['context'] }}"
    data-background-mode="{{ $appearance['mode'] }}"
    data-login-animation="{{ $appearance['animation'] }}"
    class="login-appearance login-appearance--{{ $appearance['mode'] }} login-appearance--animation-{{ $appearance['animation'] }} {{ $standalone ? 'login-appearance--standalone' : '' }} {{ $enabled ? '' : 'login-appearance--inactive' }}"
    style='--login-background-color: {{ $appearance['color'] }}; --login-background-image: {!! $backgroundImage !!}; --login-background-fit: {{ $appearance['fit'] }}; --login-background-position: {{ $appearance['position'] }}; --login-overlay-color: {{ $appearance['overlay_color'] }}; --login-overlay-opacity: {{ $appearance['overlay_opacity'] / 100 }}; --login-animation-speed: {{ $appearance['speed'] }}s; --login-animation-intensity: {{ $appearance['intensity'] / 100 }};'
>
    <div class="login-appearance__backdrop" aria-hidden="true"></div>
    <div class="login-appearance__overlay" aria-hidden="true"></div>
    <div class="login-appearance__orb login-appearance__orb--one" aria-hidden="true"></div>
    <div class="login-appearance__orb login-appearance__orb--two" aria-hidden="true"></div>

    @if(trim($slot->toHtml()) !== '')
        <div class="login-appearance__content">
            {{ $slot }}
        </div>
    @endif
</div>
