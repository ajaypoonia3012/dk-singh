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

                <div
                    data-aos="zoom-in"
                    class="group bg-[#f6f3eb] rounded-[32px] p-10 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-500">

                    <div class="flex text-yellow-500 text-[22px] mb-6 tracking-[2px]">
                        ★★★★★
                    </div>

                    <p class="text-gray-600 leading-[34px] text-[17px] mb-10 font-medium">
                        "{{ $testimonial->review }}"
                    </p>

                    <div class="flex items-center gap-4">

                        <img
                            src="{{ $testimonial->image ? asset('storage/' . $testimonial->image) : asset('images/user-placeholder.jpg') }}"
                            alt="{{ $testimonial->title }}"
                            loading="lazy"
                            decoding="async"
                            class="w-16 h-16 rounded-full object-cover border-4 border-yellow-500">

                        <div>

                            <h3 class="text-[22px] font-black text-[#111111]">
                                {{ $testimonial->name }}
                            </h3>

                            <p class="text-yellow-600 font-bold text-sm uppercase tracking-[1px]">
                                {{ $testimonial->designation }}
                            </p>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif