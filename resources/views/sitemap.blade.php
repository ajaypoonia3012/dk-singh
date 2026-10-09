<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<url>
<loc>{{ url('/') }}</loc>
</url>

<url>
<loc>{{ url('/about') }}</loc>
</url>

<url>
<loc>{{ url('/contact') }}</loc>
</url>

<url>
<loc>{{ url('/programs') }}</loc>
</url>

<url>
<loc>{{ url('/services') }}</loc>
</url>

<url>
<loc>{{ url('/plans') }}</loc>
</url>

<url>
<loc>{{ url('/fitness-hub') }}</loc>
</url>

<url>
<loc>{{ url('/transformations') }}</loc>
</url>

<url>
<loc>{{ url('/blog') }}</loc>
</url>

<url>
<loc>{{ url('/products') }}</loc>
</url>

{{-- Healthline-Inspired Fitness Content Architecture Hub & Pillars --}}
<url>
<loc>{{ route('fitness.index') }}</loc>
</url>

<url>
<loc>{{ route('fitness.exercise') }}</loc>
</url>

<url>
<loc>{{ route('fitness.cardio') }}</loc>
</url>

<url>
<loc>{{ route('fitness.strength-training') }}</loc>
</url>

<url>
<loc>{{ route('fitness.yoga') }}</loc>
</url>

<url>
<loc>{{ route('fitness.holistic-fitness') }}</loc>
</url>

<url>
<loc>{{ route('fitness.wellness') }}</loc>
</url>

<url>
<loc>{{ route('fitness.exercise-library') }}</loc>
</url>

@foreach($exercises as $exercise)
<url>
<loc>{{ route('fitness.exercise-detail', $exercise->slug) }}</loc>
</url>
@endforeach

@foreach($programs as $program)
<url>
<loc>{{ route('programs.show', $program->slug) }}</loc>
</url>
@endforeach

@foreach($services as $service)
<url>
<loc>{{ route('services.show', $service->slug) }}</loc>
</url>
@endforeach

@foreach($blogs as $blog)
<url>
<loc>{{ route('blog.show', $blog->slug) }}</loc>
</url>
@endforeach

@foreach($products as $product)
<url>
<loc>{{ route('products.show', $product->slug) }}</loc>
</url>
@endforeach

</urlset>
