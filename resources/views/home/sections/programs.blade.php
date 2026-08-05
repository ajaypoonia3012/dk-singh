<!-- FEATURED PROGRAMS -->

<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[3px] mb-4">
               {{ $setting->program_label }}
            </p>

            <h2 class="text-5xl font-black text-[#111111] mb-5">
 {{ $setting->programs_heading ?? 'Featured Fitness Programs' }}
            </h2>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $setting->programs_description }}


                            </p>

        </div>
        <!-- PROGRAMS GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

@foreach($programs as $program)

<div data-aos="zoom-in"
     class="group bg-[#f6f3eb] rounded-[32px] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-2 transition duration-500">

    <div class="overflow-hidden">

        <img
            src="{{ $program->image ? asset('storage/' . $program->image) : asset('images/placeholder.jpg') }}"
alt="{{ $program->title }}"
loading="lazy"
    decoding="async"

            class="w-full h-72 object-cover group-hover:scale-110 transition duration-700"
        >

    </div>

    <div class="p-8">

        <div class="flex items-center justify-between mb-4">

            <span class="bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-bold">
                {{ $program->category }}
            </span>

            <span class="text-gray-500 font-semibold">
                {{ $program->duration }}
            </span>

        </div>

        <h3 class="text-3xl font-black text-[#111111] mb-4">
            {{ $program->title }}
        </h3>

        <p class="text-gray-500 leading-relaxed mb-6">
            {{ Str::limit($program->description, 100) }}
        </p>

        <div class="flex items-center justify-between">

            <span class="text-2xl font-black text-yellow-600">
                ₹{{ $program->price }}
            </span>

            <a href="{{ $setting->cta_button_link ?? '/plans' }}"
               class="inline-flex items-center gap-2 text-yellow-600 font-bold hover:text-yellow-500">

                {{ $setting->about_cta_text ?? 'Learn More' }}

            </a>

        </div>

    </div>

</div>

@endforeach

</div>
</div>

</section>

@endif
