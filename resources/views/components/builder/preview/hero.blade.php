<section class="bg-white rounded-3xl overflow-hidden">

    <div class="grid lg:grid-cols-2 gap-12 items-center p-12">

        {{-- LEFT CONTENT --}}
        <div>

            <p class="text-yellow-500 uppercase tracking-[4px] font-bold mb-5">
                {{ $setting->site_name }}
            </p>

            <h1 class="text-5xl font-black leading-tight text-gray-900">
                {!! nl2br($hero['heading'] ?? $setting->hero_title ?? 'Transform Your Body') !!}
            </h1>

            <p class="mt-6 text-lg leading-8 text-gray-600">
                {{ $hero['subheading'] ?? $setting->hero_subtitle }}
            </p>

            {{-- Buttons --}}
            <div class="mt-8 flex flex-wrap gap-4">

                <a
                    href="{{ $hero['button_link'] ?? $setting->cta_button_link ?? '#' }}"
                    class="px-7 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-bold transition">

                    {{ $hero['button_text'] ?? $setting->cta_button_text }}

                </a>

                <a
                    href="#"
                    class="px-7 py-3 rounded-xl border border-gray-300 hover:border-yellow-500 text-gray-800 font-bold transition">

                    {{ $hero['view_button_text'] ?? $setting->view_transformations_text ?? 'View Transformations' }}

                </a>

            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-8 mt-10">

                <div>

                    <h3 class="text-3xl font-black text-yellow-500">
                        {{ $setting->instagram_followers ?? '3M+' }}
                    </h3>

                    <p class="text-gray-500 mt-2">
                        {{ $hero['followers_label'] ?? 'Followers' }}
                    </p>

                </div>

                <div>

                    <h3 class="text-3xl font-black text-yellow-500">
                        {{ $setting->years_experience ?? '15+' }}
                    </h3>

                    <p class="text-gray-500 mt-2">
                        {{ $hero['years_label'] ?? 'Years' }}
                    </p>

                </div>

                <div>

                    <h3 class="text-3xl font-black text-yellow-500">
                        {{ $setting->transformations ?? '5000+' }}
                    </h3>

                    <p class="text-gray-500 mt-2">
                        {{ $hero['transformations_label'] ?? 'Transformations' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- RIGHT IMAGE --}}
        <div class="flex justify-center">

            <img
                src="{{ $heroBackgroundMediaId && ($selected = collect($heroBackgroundOptions)->firstWhere('id', $heroBackgroundMediaId)) ? asset('storage/'.$selected->path) : asset('images/dk-hero.jpeg') }}"
                alt="{{ $setting->site_name }}"
                class="w-full h-[520px] object-cover rounded-3xl shadow-xl">

        </div>

    </div>

</section>