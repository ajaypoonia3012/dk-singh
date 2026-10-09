@extends('layouts.app')

@section('title', 'Contact Coach DK Singh | Start Your Transformation')
@section('meta_description', 'Get in touch with DK Singh Fitness & Nutrition. Inquire about 1-on-1 coaching, transformation programs, and personalized nutrition consultations.')

@section('content')

<section class="theme-surface-muted theme-section">

    <div class="theme-page-container">

        <!-- HEADING -->

        <div class="text-center mb-20">

            <p class="uppercase tracking-[5px] theme-text-primary font-bold mb-4">
                {{ $setting->contact_title }}
            </p>

            <h1 class="text-5xl lg:text-6xl font-black theme-text-secondary mb-6">
                {{ $setting->contact_heading }}
            </h1>

            <p class="max-w-3xl mx-auto text-xl theme-text-neutral leading-relaxed">
    {{ $setting->contact_description }}
</p>

        </div>

        <div class="grid lg:grid-cols-2 gap-16">

            <!-- CONTACT INFO -->

            <div data-aos="fade-right">

                <div class="theme-stack-lg">

                    <!-- CARD -->

                    <div class="theme-card theme-radius theme-card-padding theme-shadow">

                        <div class="flex items-start theme-content-gap">

                            <div class="w-16 h-16 theme-radius theme-status-warning flex items-center justify-center text-3xl">
                                <svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg>
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Phone Number
                                </h3>

                                <p class="theme-text-neutral text-lg">
                                    {{ $setting->phone }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- CARD -->

                    <div class="theme-card theme-radius theme-card-padding theme-shadow">

                        <div class="flex items-start theme-content-gap">

                            <div class="w-16 h-16 theme-radius theme-status-warning flex items-center justify-center text-3xl">
                                <svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg>
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Email Address
                                </h3>

                                <p class="theme-text-neutral text-lg">
                                    {{ $setting->email }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <!-- CARD -->

                    <div class="theme-card theme-radius theme-card-padding theme-shadow">

                        <div class="flex items-start theme-content-gap">

                            <div class="w-16 h-16 theme-radius theme-status-warning flex items-center justify-center text-3xl">
                                <svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg>
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Location
                                </h3>

                                <p class="theme-text-neutral leading-relaxed mb-5">
                           {{ $setting->address }}
                        </p>

                        <a href="{{ $setting->map_link }}"
   target="_blank"
   class="theme-text-primary font-semibold hover:theme-text-primary transition">

    Open in Google Maps

</a>


                            </div>

                        </div>

                    </div>

                    <!-- CARD -->

                    <div class="theme-card theme-radius theme-card-padding theme-shadow">

                        <div class="flex items-start theme-content-gap">

                            <div class="w-16 h-16 theme-radius theme-status-warning flex items-center justify-center text-3xl">
                                <svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg>
                            </div>

                            <div>

                                <h3 class="text-2xl font-black mb-3">
                                    Working Hours
                                </h3>

                               <p class="theme-text-neutral text-lg">
    {{ $setting->working_hours }}
</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FORM -->

            <div data-aos="fade-left">

                <div class="theme-card theme-radius theme-card-padding-lg theme-shadow">

                    <h2 class="text-4xl font-black mb-6">
                        Send Message
                    </h2>

                    @if(!empty($selectedService))
                        <div class="mb-8 p-5 theme-radius theme-card border-2 theme-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider theme-status-warning theme-text-secondary mb-1">
                                    Interested Coaching Service
                                </span>
                                <h3 class="text-xl font-bold theme-text-secondary">
                                    {{ $selectedService->title }} ({{ $selectedService->duration }})
                                </h3>
                                <p class="text-xs theme-text-neutral mt-0.5">Your inquiry will be directly routed for this 1-on-1 coaching package.</p>
                            </div>
                            <span class="theme-text-primary font-black text-2xl">₹{{ number_format($selectedService->price) }}</span>
                        </div>
                    @endif

<form method="POST" action="{{ route('contact.submit') }}" class="theme-stack-lg">

    @csrf

    @if(!empty($selectedService))
        <input type="hidden" name="service" value="{{ $selectedService->title }}">
    @endif

    <div>

        <label class="theme-label block mb-3 font-bold theme-text-neutral">
            Full Name
        </label>

        <input
            type="text"
            name="name"
            placeholder="Enter your name"
            class="theme-form-control w-full theme-radius border theme-border px-6 py-5 focus:outline-none focus:ring-2 theme-border"
        >

    </div>

    <div>

        <label class="theme-label block mb-3 font-bold theme-text-neutral">
            Email Address
        </label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            class="theme-form-control w-full theme-radius border theme-border px-6 py-5 focus:outline-none focus:ring-2 theme-border"
        >

    </div>

    <div>

        <label class="theme-label block mb-3 font-bold theme-text-neutral">
            Phone Number
        </label>

        <input
            type="text"
            name="phone"
            placeholder="Enter your number"
            class="theme-form-control w-full theme-radius border theme-border px-6 py-5 focus:outline-none focus:ring-2 theme-border"
        >

    </div>

    <div>

        <label class="theme-label block mb-3 font-bold theme-text-neutral">
            Message
        </label>

        <textarea
            rows="6"
            name="message"
            placeholder="Write your message..."
            class="theme-form-control w-full theme-radius border theme-border px-6 py-5 focus:outline-none focus:ring-2 theme-border"
        ></textarea>

    </div>

    <button
        type="submit"
        class="w-full theme-radius theme-status-warning hover:theme-status-warning theme-text-secondary font-black py-5 transition duration-300 theme-shadow">

        Send Message

    </button>

</form>


                </div>

            </div>

        </div>

    </div>

</section>

@endsection
