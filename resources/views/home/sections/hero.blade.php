@if($theme?->show_hero)
<!-- HERO SECTION -->

<section class="relative bg-[#f8f6f1] overflow-hidden">

    <div class="max-w-7xl mx-auto px-6 py-20">

        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- LEFT CONTENT -->

            <div>

                <p class="text-yellow-500 uppercase tracking-[4px] font-bold mb-6">
                    {{ $setting->site_name }}
                </p>

                <h1 class="text-7xl font-black leading-tight">

    {!! nl2br($hero?->heading ?? $setting->hero_title ?? 'Transform Your Body, Transform Your Life') !!}

</h1>



                <p class="text-xl text-gray-600 leading-relaxed mb-10 max-w-xl">

    {{ $hero?->subheading ?? $setting->hero_subtitle ?? 'Expert fitness coaching and personalized nutrition plans.' }}

</p>

                <!-- BUTTONS -->

                <div class="flex flex-wrap gap-5 mb-14">

                    <a href="{{ $hero?->button_link ?? $setting->cta_button_link ?? '/plans' }}"
   class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-8 py-4 rounded-2xl transition duration-300 shadow-lg hover:scale-105">

    {{ $hero?->button_text ?? $setting->cta_button_text ?? 'Start Your Journey' }}

</a>

                    <a href="/transformations"
                       class="border border-gray-300 hover:border-yellow-500 hover:text-yellow-500 text-black font-bold px-8 py-4 rounded-2xl transition duration-300">

                       {{ $setting->view_transformations_text }}

                    </a>

                </div>

                <!-- STATS -->

                <div class="grid grid-cols-3 gap-10">

                    <div>
                        <h3 class="text-5xl font-black text-yellow-500">
                            {{ $setting->instagram_followers ?? '3M+' }}
                        </h3>
                        <p class="text-gray-600 mt-2">
                            {{ $hero?->followers_label ?? 'Followers' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-5xl font-black text-yellow-500">
                            {{ $setting->years_experience ?? '15+' }}
                        </h3>
                        <p class="text-gray-600 mt-2">
                            {{ $hero?->years_label ?? 'Years' }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-5xl font-black text-yellow-500">
                            {{ $setting->transformations ?? '15000+' }}
                        </h3>
                        <p class="text-gray-600 mt-2">
                            {{ $hero?->transformations_label ?? 'Transformations' }}
                        </p>
                    </div>

                </div>

            </div>

            <!-- RIGHT IMAGE -->

            <div class="relative flex justify-center">

                <!-- GLOW -->

                <div class="absolute inset-0 bg-yellow-200 blur-3xl opacity-20 rounded-full"></div>

                <!-- IMAGE -->

                <img
                    src="{{ $hero?->backgroundMedia ? asset('storage/' . $hero->backgroundMedia->path) : asset('images/dk-hero.jpeg') }}"
                    alt="{{ $setting->site_name }}"
		loading="lazy"
    		decoding="async"

                    class="relative z-10 w-full max-w-[550px] h-[700px] object-cover rounded-[40px] shadow-2xl"
                >

                <!-- FLOATING CARD -->

                <div class="absolute bottom-10 left-0 bg-white rounded-3xl shadow-2xl p-6 z-20">

                    <h4 class="text-3xl font-black text-black mb-2">
                        Transformations
                    </h4>

                    <p class="text-gray-600 max-w-xs">
                        {{ $setting->hero_card_title }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>
@endif