@extends('layouts.app')

@section('content')

<section class="bg-[#f6f3eb] py-24">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-20">

            <p class="uppercase tracking-[5px] text-yellow-500 font-bold mb-4">
                {{ $setting->contact_title }}
            </p>

            <h1 class="text-5xl lg:text-6xl font-black text-[#111111] mb-6">
                {{ $setting->contact_heading }}
                </span>
            </h1>

            <p class="max-w-3xl mx-auto text-xl text-gray-600 leading-relaxed">
    {{ $setting->contact_description }}
</p>

        </div>

        <div class="grid lg:grid-cols-2 gap-16">

            <!-- CONTACT INFO -->

            <div data-aos="fade-right">

                <div class="space-y-8">

                    <!-- CARD -->

                    <div class="bg-white rounded-[32px] p-8 shadow-xl">

                        <div class="flex items-start gap-5">

                            <div class="w-16 h-16 rounded-2xl bg-yellow-500 flex items-center justify-center text-3xl">
                                📞
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Phone Number
                                </h3>

                                <p class="text-gray-600 text-lg">
                                    {{ $setting->phone }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- CARD -->

                    <div class="bg-white rounded-[32px] p-8 shadow-xl">

                        <div class="flex items-start gap-5">

                            <div class="w-16 h-16 rounded-2xl bg-yellow-500 flex items-center justify-center text-3xl">
                                ✉️
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Email Address
                                </h3>

                                <p class="text-gray-600 text-lg">
                                    {{ $setting->email }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- CARD -->

                    <div class="bg-white rounded-[32px] p-8 shadow-xl">

                        <div class="flex items-start gap-5">

                            <div class="w-16 h-16 rounded-2xl bg-yellow-500 flex items-center justify-center text-3xl">
                                📍
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Location
                                </h3>

                                <p class="text-gray-600 leading-relaxed mb-5">
                           {{ $setting->address }}
                        </p>

                        <a href="{{ $setting->map_link }}"
   target="_blank"
   class="text-yellow-600 font-semibold hover:text-yellow-500 transition">

    Open in Google Maps

</a>


                            </div>

                        </div>

                    </div>

                    <!-- CARD -->

                    <div class="bg-white rounded-[32px] p-8 shadow-xl">

                        <div class="flex items-start gap-5">

                            <div class="w-16 h-16 rounded-2xl bg-yellow-500 flex items-center justify-center text-3xl">
                                ⏰
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Working Hours
                                </h3>

                               <p class="text-gray-600 text-lg">
    {{ $setting->working_hours }}
</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FORM -->

            <div data-aos="fade-left">

                <div class="bg-white rounded-[40px] p-10 shadow-2xl">

                    <h2 class="text-4xl font-black mb-10">
                        Send Message
                    </h2>

<form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">

    @csrf

    <div>

        <label class="block mb-3 font-bold text-gray-700">
            Full Name
        </label>

        <input
            type="text"
            name="name"
            placeholder="Enter your name"
            class="w-full rounded-2xl border border-gray-300 px-6 py-5 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        >

    </div>

    <div>

        <label class="block mb-3 font-bold text-gray-700">
            Email Address
        </label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            class="w-full rounded-2xl border border-gray-300 px-6 py-5 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        >

    </div>

    <div>

        <label class="block mb-3 font-bold text-gray-700">
            Phone Number
        </label>

        <input
            type="text"
            name="phone"
            placeholder="Enter your number"
            class="w-full rounded-2xl border border-gray-300 px-6 py-5 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        >

    </div>

    <div>

        <label class="block mb-3 font-bold text-gray-700">
            Message
        </label>

        <textarea
            rows="6"
            name="message"
            placeholder="Write your message..."
            class="w-full rounded-2xl border border-gray-300 px-6 py-5 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        ></textarea>

    </div>

    <button
        type="submit"
        class="w-full rounded-2xl bg-yellow-500 hover:bg-yellow-400 text-black font-black py-5 transition duration-300 shadow-lg">

        Send Message

    </button>

</form>


                </div>

            </div>

        </div>

    </div>

</section>

@endsection