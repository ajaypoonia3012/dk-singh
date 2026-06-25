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
<loc>{{ url('/blogs') }}</loc>
</url>

<url>
<loc>{{ url('/products') }}</loc>
</url>

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
<loc>{{ route('blogs.show', $blog->slug) }}</loc>
</url>
@endforeach

@foreach($products as $product)
<url>
<loc>{{ route('products.show', $product->slug) }}</loc>
</url>
@endforeach

</urlset>
