@if($theme?->show_testimonials)

<!-- TESTIMONIALS -->

<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[3px] mb-4">
                {{ $setting->testimonials_title }}
            </p>

            <h2 class="text-5xl font-black text-[#111111] mb-5">
                {{ $setting->testimonials_heading }}
            </h2>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $setting->testimonials_description }},
                confidence, and lifestyle with {{ $setting->site_name }}.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($testimonials as $testimonial)
                @php
                    $hasAvatar = filled($testimonial->image) && file_exists(public_path('storage/' . $testimonial->image));
                    $initials = '';
                    if (!empty($testimonial->name)) {
                        $words = preg_split('/\s+/', trim($testimonial->name));
                        $initials = strtoupper(substr($words[0] ?? '', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    }
                    $initials = $initials ?: 'DK';
                @endphp
                <div
                    data-aos="zoom-in"
                    class="group bg-[#f6f3eb] rounded-2xl p-6 shadow-md hover:shadow-xl hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">

                    <div>
                        <div class="flex text-yellow-500 text-base mb-3 tracking-[2px]">
                            ★★★★★
                        </div>

                        <p class="text-gray-700 leading-relaxed text-sm md:text-base mb-6 font-normal">
                            “{{ Str::limit($testimonial->clean_review, 160) }}”
                        </p>
                    </div>

                    <div class="flex items-center gap-3.5 pt-4 border-t border-gray-200/60 mt-auto">
                        @if($hasAvatar)
                            <img
                                src="{{ asset('storage/' . $testimonial->image) }}"
                                alt="{{ $testimonial->name ? $testimonial->name . ' - Client Testimonial' : 'DK Singh Fitness Client' }}"
                                loading="lazy"
                                decoding="async"
                                class="w-12 h-12 rounded-full object-cover border-2 border-yellow-500 shrink-0"
                            >
                        @else
                            <div
                                class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm tracking-wide shrink-0 border-2 bg-slate-900 text-yellow-400 border-yellow-500/50"
                                aria-hidden="true"
                            >
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <h3 class="text-base font-bold text-[#111111] truncate">
                                {{ $testimonial->name }}
                            </h3>

                            <p class="text-yellow-600 font-bold text-xs uppercase tracking-wider truncate">
                                {{ $testimonial->designation ?: 'Verified Client' }}
                            </p>
                        </div>
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif