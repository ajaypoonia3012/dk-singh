@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->class(['theme-form-control']) }}>
    {{ $slot }}
</select>
