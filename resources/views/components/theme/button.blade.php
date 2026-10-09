@props(['variant' => 'primary', 'type' => 'button', 'href' => null])

@php
    $variantClass = match ($variant) {
        'secondary' => 'theme-button-secondary',
        'outline' => 'theme-button-outline',
        default => 'theme-button-primary',
    };
@endphp

@if(filled($href))
    <a href="{{ $href }}" {{ $attributes->class(['theme-button', $variantClass]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class(['theme-button', $variantClass]) }}>
        {{ $slot }}
    </button>
@endif
