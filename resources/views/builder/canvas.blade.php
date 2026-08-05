<div class="h-full bg-gray-100 rounded-xl overflow-auto p-8">

    <div
        @class([
            'mx-auto bg-white rounded-xl shadow-xl transition-all duration-300',

            'max-w-5xl'=>$selectedDevice==='desktop',
            'max-w-3xl'=>$selectedDevice==='tablet',
            'max-w-sm'=>$selectedDevice==='mobile',
        ])
    >

        @switch($selectedSection->section)

            @case('hero')
                @include('components.builder.preview.hero')
                @break

            @case('homepage_cards')
                @include('components.builder.preview.homepage-cards')
                @break

            @case('products')
                @include('components.builder.preview.products')
                @break

            @case('programs')
                @include('components.builder.preview.programs')
                @break

            @case('transformations')
                @include('components.builder.preview.transformations')
                @break

            @case('testimonials')
                @include('components.builder.preview.testimonials')
                @break

            @case('blogs')
                @include('components.builder.preview.blogs')
                @break

            @case('contact')
                @include('components.builder.preview.contact')
                @break

        @endswitch

    </div>

</div>