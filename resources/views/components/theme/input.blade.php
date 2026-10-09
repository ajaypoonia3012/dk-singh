@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->class(['theme-form-control']) }}>
