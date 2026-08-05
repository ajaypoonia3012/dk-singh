@extends('layouts.app')

@section('content')


@include('home.sections.hero')



@if($theme?->show_programs)
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
@if($products->count())

<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[4px]">
                {{ $setting->supplements_label }}
            </p>

            <h2 class="text-5xl font-black mt-4">
                {{ $setting->supplements_heading }}
            </h2>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($products as $product)

                <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                    @if($product->image)

                        <img
                            src="{{ asset('storage/'.$product->image) }}"
                            class="w-full h-72 object-cover"
                        >

                    @endif

                    <div class="p-6">

                        <h3 class="text-2xl font-bold mb-3">
                            {{ $product->name }}
                        </h3>

                        <div class="text-yellow-500 text-3xl font-black mb-4">
                            ₹{{ number_format($product->price) }}
                        </div>

                        <a
                            href="{{ route('products.show',$product->slug) }}"
                            class="inline-block bg-yellow-500 px-6 py-3 rounded-xl font-bold"
                        >
                            View Product
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif
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


<!-- BMI -->

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-6xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[3px] mb-4">
                {{ $setting->bmi_label }}
            </p>

            <h2 class="text-5xl font-black text-[#111111] mb-5">
                BMI Calculator
            </h2>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $setting->bmi_heading }} {{ $setting->bmi_description }}
            </p>

        </div>

        <!-- BMI CARD -->

        <div data-aos="fade-up" class="bg-[#111111] rounded-[40px] p-10 md:p-14 shadow-2xl">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <!-- LEFT -->

                <div>

                    <div class="mb-8">

                        <label class="block text-white font-semibold mb-3 text-lg">
                            Height (cm)
                        </label>

                        <input
                            type="number"
                            id="height"
                            placeholder="Enter your height"
                            class="w-full bg-white rounded-2xl px-6 py-5 text-lg focus:outline-none focus:ring-4 focus:ring-yellow-500">
                    </div>

                    <div class="mb-10">

                        <label class="block text-white font-semibold mb-3 text-lg">
                            Weight (kg)
                        </label>

                        <input
                            type="number"
                            id="weight"
                            placeholder="Enter your weight"
                            class="w-full bg-white rounded-2xl px-6 py-5 text-lg focus:outline-none focus:ring-4 focus:ring-yellow-500">
                    </div>

                    <button
                        onclick="calculateBMI()"
                        class="bg-yellow-500 hover:bg-yellow-400 hover:scale-105 text-black font-black px-10 py-5 rounded-2xl transition duration-300 shadow-lg hover:shadow-yellow-500/20">

                        Calculate BMI

                    </button>

                </div>

                <!-- RIGHT -->

                <div class="bg-white rounded-[32px] p-10 shadow-2xl">

                    <p class="text-gray-500 uppercase tracking-[2px] font-semibold mb-3">
                        Your BMI
                    </p>

                    <h3 id="bmi-result"
                        class="text-6xl font-black text-yellow-500 mb-6">

                        --

                    </h3>

                    <h4 id="bmi-status"
                        class="text-3xl font-black text-[#111111] mb-6">

                        Enter your details

                    </h4>

                    <div class="border-t border-gray-200 pt-6">

                        <p class="text-gray-500 uppercase tracking-[2px] font-semibold mb-3">
                            Recommended Goal
                        </p>

                        <p id="bmi-goal"
                           class="text-xl text-gray-700 leading-relaxed">

                            Fill your details to see your recommended fitness path.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<script>

function calculateBMI() {

    let height = document.getElementById('height').value;
    let weight = document.getElementById('weight').value;

    if(height === '' || weight === '') {

        alert('Please enter height and weight');

        return;
    }

    height = height / 100;

    let bmi = (weight / (height * height)).toFixed(1);

    document.getElementById('bmi-result').innerText = bmi;

    let status = '';
    let goal = '';

    if(bmi < 18.5) {

        status = 'Underweight';
        goal = 'Muscle gain and nutrition optimization program recommended.';

    } else if(bmi < 25) {

        status = 'Healthy';
        goal = 'Maintain your fitness with performance training programs.';

    } else if(bmi < 30) {

        status = 'Overweight';
        goal = 'Fat loss transformation program recommended.';

    } else {

        status = 'Obese';
        goal = 'Structured weight loss coaching strongly recommended.';
    }

    document.getElementById('bmi-status').innerText = status;
    document.getElementById('bmi-goal').innerText = goal;
}

</script>
<!-- ABOUT -->

<section class="py-24 bg-white">

    <div class="max-w-[1400px] mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <div data-aos="fade-right">

                <img
          

src="{{ !empty($setting?->about_image) ? asset('storage/' . $setting->about_image) : 'https://images.unsplash.com/photo-1534367610401-9f5ed68180aa?q=80&w=1200&auto=format&fit=crop' }}"
                    
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

                <div class="grid grid-cols-2 gap-5 mt-12">

    @foreach($homepageCards as $card)

        <div class="bg-[#f6f3eb] rounded-2xl p-6 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition duration-300">

            <div class="text-3xl mb-3">
                {{ $card->icon }}
            </div>

            <h3 class="font-black text-xl">
                {{ $card->title }}
            </h3>

            <p class="text-gray-600 mt-2">
                {{ $card->subtitle }}
            </p>

        </div>

    @endforeach

</div>

            </div>

        </div>

    </div>

</section>

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

<div data-aos="zoom-in"
     class="group bg-white rounded-[32px] overflow-hidden shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-500">

    <!-- IMAGES -->

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

        <!-- STARS -->

        <div class="flex text-yellow-500 text-[20px] mb-5 tracking-[2px]">
            ★★★★★
        </div>

        <!-- NAME -->

        <h3 class="text-[28px] leading-tight font-black text-[#111111] mb-4">
            {{ $transformation->name }}
        </h3>

        <!-- STORY -->

        <p class="text-[16px] leading-[32px] text-gray-600 mb-8 font-medium">

            "{{ $transformation->story }}"

        </p>

        <!-- FOOTER -->

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

        <!-- TESTIMONIAL GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($testimonials as $testimonial)

            <div data-aos="zoom-in"
                 class="group bg-[#f6f3eb] rounded-[32px] p-10 shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-500">

                <!-- STARS -->

                <div class="flex text-yellow-500 text-[22px] mb-6 tracking-[2px]">
                    ★★★★★
                </div>

                <!-- REVIEW -->

                <p class="text-gray-600 leading-[34px] text-[17px] mb-10 font-medium">

                    "{{ $testimonial->review }}"

                </p>

                <!-- CLIENT -->

                <div class="flex items-center gap-4">

                    <img
                        src="{{ $testimonial->image ? asset('storage/' . $testimonial->image) : asset('images/user-placeholder.jpg') }}"
alt="{{ $testimonial->title }}"
loading="lazy"
    decoding="async"

                        class="w-16 h-16 rounded-full object-cover border-4 border-yellow-500"
                    >

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
            


<!-- CONTACT -->

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-16">

            <h2 class="text-5xl font-black text-[#111111] mb-4">
                {{ $setting->contact_title ?? 'Get In Touch' }}
            </h2>

            <p class="text-lg text-gray-600">
{{ $setting->contact_description ?? 'Ready to start your fitness journey? Contact us today.' }}           
 </p>

        </div>

        <!-- MAIN GRID -->

        <div class="grid lg:grid-cols-2 gap-8 items-center">

            <!-- LEFT SIDE -->

            <div data-aos="fade-right" class="space-y-6">

                <!-- MAP CARD -->

                <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-sm">

                    <iframe
                        src="{{ $setting->map_embed_url }}"
                        width="100%"
                        height="300"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>

                    <div class="p-8">

            <h3 class="text-3xl font-black text-[#111111] mb-3">
                        {{ $setting->business_display_name }}
                        </h3>

                        <p class="text-gray-600 leading-relaxed mb-5">
                            {{ $setting->contact_map_text }}
                        </p>

                        <a href="{{ $setting->map_link }}"
   target="_blank"
   class="text-yellow-600 font-semibold hover:text-yellow-500 transition">

    Open in Google Maps

</a>

                    </div>

                </div>

            </div>

            <!-- RIGHT SIDE -->

            <div data-aos="fade-left" class="bg-white rounded-3xl border border-gray-200 shadow-sm p-10">
@if(session('success'))

<div class="bg-green-100 text-green-700 px-6 py-4 rounded-2xl mb-6">

    {{ session('success') }}

</div>

@endif

                <form action="/contact-submit" method="POST" class="space-y-6">

    @csrf

                    <div class="grid md:grid-cols-2 gap-6">

                        <div>

                            <label class="block mb-2 text-sm font-semibold text-[#111111]">
                                Name
                            </label>

                            <input type="text"
       				   name="name"
			            placeholder="Enter your full name"
                                   class="w-full rounded-2xl border border-gray-300 bg-[#f6f3eb] px-5 py-4 focus:outline-none focus:ring-2 focus:ring-yellow-500">

                        </div>

                        <div>

                            <label class="block mb-2 text-sm font-semibold text-[#111111]">
                                Email
                            </label>

                            <input type="email"
       				name="email"
				placeholder="Enter your email address"
                                   class="w-full rounded-2xl border border-gray-300 bg-[#f6f3eb] px-5 py-4 focus:outline-none focus:ring-2 focus:ring-yellow-500">

                        </div>

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-semibold text-[#111111]">
                            Phone
                        </label>

                        <input type="text"
       			name="phone"
			placeholder="Enter your phone number"
                               class="w-full rounded-2xl border border-gray-300 bg-[#f6f3eb] px-5 py-4 focus:outline-none focus:ring-2 focus:ring-yellow-500">

                    </div>

                    <div>

                        <label class="block mb-2 text-sm font-semibold text-[#111111]">
                            Message
                        </label>

                        <textarea name="message"
				rows="6"
			placeholder="Write your message"
                                  class="w-full rounded-2xl border border-gray-300 bg-[#f6f3eb] px-5 py-4 focus:outline-none focus:ring-2 focus:ring-yellow-500"></textarea>

                    </div>

                    <button type="submit"
    class="w-full bg-yellow-500 hover:bg-yellow-400 hover:scale-105 text-black font-bold py-4 rounded-2xl transition duration-300 shadow-md hover:shadow-2xl">

   {{ $setting->contact_cta_text ?? 'Send Message' }}

</button>

                </form>

            </div>

        </div>

        <!-- BOTTOM INFO CARDS -->

        <div class="grid md:grid-cols-3 gap-6 mt-10">

            <!-- PHONE -->

           <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 hover:-translate-y-2 hover:shadow-xl transition duration-300 flex items-center gap-5">

                <div class="w-14 h-14 rounded-2xl bg-yellow-500 flex items-center justify-center text-white font-bold text-lg">
                    📞
                </div>

                <div>

                    <h4 class="font-bold text-[#111111] text-xl mb-1">
                        Phone
                    </h4>

                    <p class="text-gray-600">
                        {{ $setting->phone ?? '+91 72400 73888' }}
                    </p>

                </div>

            </div>

            <!-- EMAIL -->

           <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 hover:-translate-y-2 hover:shadow-xl transition duration-300 flex items-center gap-5">

                <div class="w-14 h-14 rounded-2xl bg-yellow-500 flex items-center justify-center text-white font-bold text-lg">
                    ✉️
                </div>

                <div>

                    <h4 class="font-bold text-[#111111] text-xl mb-1">
                        Email
                    </h4>

                    <p class="text-gray-600">
               {{ $setting->email }}
                    </p>

                </div>

            </div>

            <!-- HOURS -->

            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 hover:-translate-y-2 hover:shadow-xl transition duration-300 flex items-center gap-5">

                <div class="w-14 h-14 rounded-2xl bg-yellow-500 flex items-center justify-center text-white font-bold text-lg">
                    🕒
                </div>

                <div>

                    <h4 class="font-bold text-[#111111] text-xl mb-1">
                        Working Hours
                    </h4>

                    <p class="text-gray-600">
                        {{ $setting->working_hours }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>