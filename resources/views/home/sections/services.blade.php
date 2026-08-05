@if($theme?->show_services)

<!-- SERVICES SECTION -->

<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[3px] mb-4">
                {{ $setting->service_label }}
            </p>

            <h2 class="text-5xl font-black">
                {{ $setting->services_heading ?? 'Fitness Solutions' }}
            </h2>

            <p class="text-gray-600 mt-6 text-xl max-w-3xl mx-auto">
                {{ $setting->services_description }}
            </p>

        </div>

        <div class="grid md:grid-cols-3 gap-8">

            @foreach($services as $service)

                <div class="bg-[#f8f8f8] rounded-3xl p-8 shadow-lg">

                    @if($service->image)

                        <img
                            src="{{ asset('storage/' . $service->image) }}"
                            class="w-full h-56 object-cover rounded-2xl mb-6"
                        >

                    @endif

                    <p class="text-yellow-500 font-bold mb-3">
                        {{ $service->category ?? 'Fitness' }}
                    </p>

                    <h3 class="text-3xl font-black mb-4">
                        {{ $service->title }}
                    </h3>

                    <p class="text-gray-600 leading-relaxed mb-6">
                        {{ \Illuminate\Support\Str::limit($service->description, 120) }}
                    </p>

                    <div class="flex items-center justify-between">

                        <span class="font-black text-2xl">
                            ₹{{ number_format($service->price, 0) }}
                        </span>

                        <a
                            href="{{ route('services.show', $service->slug) }}"
                            class="text-yellow-500 font-bold"
                        >
                            Learn More →
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif