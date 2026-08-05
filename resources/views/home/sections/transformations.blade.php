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

                <div
                    data-aos="zoom-in"
                    class="group bg-white rounded-[32px] overflow-hidden shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-500">

                    <!-- IMAGE -->

                    <div class="relative overflow-hidden">

                        <img
                            src="{{ $transformation->image ? asset('storage/' . $transformation->image) : asset('images/transformation-placeholder.jpg') }}"
                            alt="{{ $transformation->title }}"
                            loading="lazy"
                            decoding="async"
                            class="h-[420px] w-full object-cover group-hover:scale-105 transition duration-700"
                        >

                        <!-- RESULT BADGE -->

                        <div class="absolute top-5 left-5 bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-black shadow-lg">

                            {{ $transformation->goal }}

                        </div>

                    </div>

                    <!-- CONTENT -->

                    <div class="p-8 bg-white">

                        <div class="flex text-yellow-500 text-[20px] mb-5 tracking-[2px]">
                            ★★★★★
                        </div>

                        <h3 class="text-[28px] leading-tight font-black text-[#111111] mb-4">
                            {{ $transformation->name }}
                        </h3>

                        <p class="text-[16px] leading-[32px] text-gray-600 mb-8 font-medium">

                            "{{ $transformation->story }}"

                        </p>

                        <div class="flex items-center justify-between border-t border-gray-200 pt-5">

                            <span class="text-[14px] font-bold text-gray-500 uppercase tracking-[1px]">
                                {{ $transformation->duration }}
                            </span>

                            <span class="text-yellow-600 font-black text-[14px] uppercase tracking-[1px]">
                                {{ $setting->verified_client_label }}
                            </span>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif