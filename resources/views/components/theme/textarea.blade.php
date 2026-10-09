@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->class(['theme-form-control']) }}>{{ $slot }}</textarea>
