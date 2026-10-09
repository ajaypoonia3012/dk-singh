@props(['value' => null])

<label {{ $attributes->class(['theme-label', 'block', 'text-sm']) }}>
    {{ $value ?? $slot }}
</label>
