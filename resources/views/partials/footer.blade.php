<footer class="bg-black text-white pt-20 pb-10 mt-20">

    <div class="max-w-7xl mx-auto px-6">

        <!-- TOP GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-14 pb-16 border-b border-white/10">

            <!-- BRAND -->

            <div>

                <div class="flex items-center gap-3 mb-6">

                    @if(!empty($setting?->logo))

                        <img
                            src="{{ asset('storage/' . $setting->logo) }}"
                            alt="{{ $setting->site_name }}"
                            class="w-14 h-14 object-contain"
                        >

                    @endif

                    <div>

                        <h2 class="text-2xl font-black text-yellow-500">
                            {{ $setting->site_name ?? 'DK Singh Fitness' }}
                        </h2>

                        <p class="text-xs uppercase tracking-[3px] text-gray-400">
                            {{ $setting->site_tagline ?? 'Fitness & Nutrition' }}
                        </p>

                    </div>

                </div>

                <p class="text-gray-400 leading-relaxed mb-6">
                    {{ $setting->footer_text ?? 'Transform your body and mindset with elite coaching and premium fitness programs.' }}
                </p>

                <!-- SOCIALS -->

                <div class="flex items-center gap-4">

                    @if(!empty($setting?->instagram))

                        <a href="{{ $setting->instagram }}"
                           target="_blank"
                           class="w-10 h-10 rounded-full bg-white/10 hover:bg-yellow-500 hover:text-black transition flex items-center justify-center">

                            IG

                        </a>

                    @endif

                    @if(!empty($setting?->youtube))

                        <a href="{{ $setting->youtube }}"
                           target="_blank"
                           class="w-10 h-10 rounded-full bg-white/10 hover:bg-yellow-500 hover:text-black transition flex items-center justify-center">

                            YT

                        </a>

                    @endif

                    @if(!empty($setting?->facebook))

                        <a href="{{ $setting->facebook }}"
                           target="_blank"
                           class="w-10 h-10 rounded-full bg-white/10 hover:bg-yellow-500 hover:text-black transition flex items-center justify-center">

                            FB

                        </a>

                    @endif

                </div>

            </div>

            <!-- QUICK LINKS -->

            <div>

                <h3 class="text-xl font-bold mb-6">
          {{ $setting->footer_links_heading ?? 'Quick Links' }}
                </h3>

                <div class="space-y-4 text-gray-400">

                    <a href="/" class="block hover:text-yellow-500 transition">
                        {{ $setting->home_label ?? 'Home' }}
                    </a>

                    <a href="/programs" class="block hover:text-yellow-500 transition">
                  {{ $setting->program_label ?? 'Programs' }}
                    </a>

                    <a href="/services" class="block hover:text-yellow-500 transition">
         {{ $setting->footer_services_heading ?? 'Services' }}
                    </a>

                    <a href="/transformations" class="block hover:text-yellow-500 transition">
       {{ $setting->transformation_label ?? 'Transformations' }}
                    </a>

                    <a href="/blog" class="block hover:text-yellow-500 transition">
                        {{ $setting->blog_label ?? 'Blog' }}
                    </a>


<a href="/fitness-hub" class="block hover:text-yellow-500 transition">
    Fitness Hub
</a>


                    <a href="/contact" class="block hover:text-yellow-500 transition">
                   {{ $setting->contact_label ?? 'Contact' }}
                    </a>

                </div>

            </div>

            <!-- SERVICES -->

            <div>

                <h3 class="text-xl font-bold mb-6">
                    {{ $setting->service_label ?? 'Services' }}
                </h3>

                <div class="space-y-4 text-gray-400">

    @foreach(\App\Models\Service::where('status', 1)->take(5)->get() as $service)

        <p>{{ $service->title }}</p>

    @endforeach

</div>

            </div>

            <!-- CONTACT -->

            <div>

                <h3 class="text-xl font-bold mb-6">
               {{ $setting->contact_title ?? 'Contact Info' }}
                </h3>

                <div class="space-y-4 text-gray-400">

                    @if(!empty($setting?->phone))

                        <p>
                            📞 {{ $setting->phone }}
                        </p>

                    @endif

                    @if(!empty($setting?->email))



                        <p>
                            ✉ {{ $setting->email }}
                        </p>

                    @endif

                    @if(!empty($setting?->address))

                        <p>
                            📍 {{ $setting->address }}
                        </p>

                    @endif

                </div>

                <!-- CTA -->

                <a href="{{ $setting->cta_button_link ?? '/plans' }}"
                   class="inline-block mt-8 bg-yellow-500 hover:bg-yellow-400 text-black px-6 py-4 rounded-2xl font-bold transition duration-300 shadow-lg">

                    {{ $setting->cta_button_text ?? 'Join Now' }}

                </a>

            </div>

        </div>

        <!-- BOTTOM -->

        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-5">

            <p class="text-gray-500 text-sm text-center md:text-left">
                © {{ date('Y') }} {{ $setting->site_name ?? 'DK Singh Fitness' }}.
                All rights reserved.
            </p>

            <p class="text-gray-600 text-sm">
               {{ $setting->site_tagline ?? 'Fitness & Nutrition' }}
            </p>

        </div>

    </div>

</footer>

