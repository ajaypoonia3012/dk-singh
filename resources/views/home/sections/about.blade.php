@if($theme?->show_about)

<!-- ABOUT -->

<section class="py-24 bg-white">

    <div class="max-w-[1400px] mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <div data-aos="fade-right">

                <img
                    src="{{ !empty($setting?->about_image)
                        ? asset('storage/' . $setting->about_image)
                        : 'https://images.unsplash.com/photo-1534367610401-9f5ed68180aa?q=80&w=1200&auto=format&fit=crop' }}"
                    loading="lazy"
                    decoding="async"
                    class="w-full h-[450px] md:h-[750px] object-cover rounded-[40px] shadow-2xl"
                >

            </div>

            <div data-aos="fade-left">

                <p class="text-yellow-500 font-bold uppercase tracking-[4px]">
                    {{ $setting->about_title }}
                </p>

                <h2 class="text-5xl md:text-6xl font-black text-[#111111] leading-tight mt-6">
                    {{ $setting->about_title ?? 'Fitness Meets Transformation' }}
                </h2>

                <p class="text-gray-600 text-lg leading-relaxed mt-6">
                    {{ $setting->about_description ?? '' }}
                </p>

                <p class="text-gray-600 text-lg leading-relaxed mt-6">
                    {{ $setting->about_description_2 ?? '' }}
                </p>

                @include('home.sections.homepage-cards')

            </div>

        </div>

    </div>

</section>

@endif