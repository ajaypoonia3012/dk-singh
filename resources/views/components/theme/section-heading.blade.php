@props(['level' => 2])

@php
    $tag = in_array((int) $level, [1, 2, 3, 4, 5, 6], true) ? 'h'.(int) $level : 'h2';
@endphp

<{{ $tag }} {{ $attributes->class(['theme-section-heading']) }}>{{ $slot }}</{{ $tag }}>
