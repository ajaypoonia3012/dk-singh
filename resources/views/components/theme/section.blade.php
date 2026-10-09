@props(['contained' => true])

<section {{ $attributes->class(['theme-section']) }}>
    @if($contained)
        <div class="theme-section-container">{{ $slot }}</div>
    @else
        {{ $slot }}
    @endif
</section>
