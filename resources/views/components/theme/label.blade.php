@props(['value' => null])

<label {{ $attributes->class(['theme-label']) }}>
    {{ $value ?? $slot }}
</label>
