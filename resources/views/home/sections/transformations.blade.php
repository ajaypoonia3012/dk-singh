@if($theme?->show_transformations)

<!-- TRANSFORMATIONS -->

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[3px] mb-4">
                {{ $setting->transformation_label }}
            </p>

            <h2 class="text-5xl font-black text-[#111111] mb-5">
                {{ $setting->transformations_heading }}
            </h2>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $setting->transformations_page_description }}
            </p>

        </div>

        <!-- GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($transformations as $transformation)
                @php
                    $imgPath = $transformation->image ?: ($transformation->after_image ?: $transformation->before_image);
                    $hasImg = filled($imgPath) && file_exists(public_path('storage/' . $imgPath));
                @endphp
                <div
                    data-aos="zoom-in"
                    class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">

                    <!-- IMAGE -->
                    <div>
                        <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-900">
                            @if($hasImg)
                                <img
                                    src="{{ asset('storage/' . $imgPath) }}"
                                    alt="{{ $transformation->title ?: ($transformation->name ?: 'DK Singh Fitness Client Transformation') }}"
                                    loading="lazy"
                                    decoding="async"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                >
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-slate-900 to-slate-800 text-slate-300">
                                    <span class="text-3xl mb-2">⚡</span>
                                    <span class="font-bold text-sm tracking-wider uppercase text-yellow-500">Verified Result</span>
                                    <span class="text-xs text-slate-400 mt-1">DK Singh Coaching Protocol</span>
                                </div>
                            @endif

                            @if(filled($transformation->goal))
                                <div class="absolute top-4 left-4 bg-yellow-500 text-black px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                    {{ $transformation->goal }}
                                </div>
                            @endif
                        </div>

                        <!-- CONTENT -->
                        <div class="p-6 bg-white">
                            <div class="flex text-yellow-500 text-base mb-2 tracking-[2px]">
                                ★★★★★
                            </div>

                            <h3 class="text-xl font-bold text-[#111111] mb-2">
                                {{ $transformation->name }}
                            </h3>

                            <p class="text-sm leading-relaxed text-gray-600 line-clamp-3">
                                “{{ Str::limit(trim(strip_tags($transformation->story ?? $transformation->description)), 140) }}”
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2">
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                                {{ $transformation->duration ?: '12 Weeks' }}
                            </span>

                            <span class="text-yellow-600 font-bold text-xs uppercase tracking-wider">
                                {{ $setting->verified_client_label ?? 'Verified Client' }}
                            </span>
                        </div>
                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif