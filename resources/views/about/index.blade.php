@extends('layouts.app')

@section('title', 'About DK Singh | Elite Fitness Coaching & Mission')
@section('meta_description', 'Discover DK Singh Fitness & Nutrition: science-backed physique transformations, individualized workout regimens, and metabolic nutrition strategies.')

@section('content')

<!-- HERO -->

<section class="theme-surface-muted theme-section overflow-hidden">

    <div class="theme-page-container">

        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- IMAGE -->

            <div data-aos="fade-right">

                <img
src="{{ $setting && $setting->about_image
    ? asset('storage/' . $setting->about_image)
    : 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1200&auto=format&fit=crop' }}"
alt="About Coach DK Singh"
class="w-full h-[650px] object-cover theme-radius theme-shadow"
>

            </div>

            <!-- CONTENT -->

            <div data-aos="fade-left">

                <p class="uppercase tracking-[5px] theme-text-primary font-bold mb-4">
                    {{ $setting?->about_title ?: 'About '.$setting?->site_name }}
                </p>

                <h1 class="text-5xl lg:text-7xl font-black leading-tight theme-text-secondary mb-8">
                    Fitness Meets
                    <span class="theme-text-primary">
                        Transformation
                    </span>
                </h1>

  
<p class="text-xl theme-text-neutral leading-relaxed mt-8">

    {{ $setting->about_description ?? '' }}

</p>

<p class="text-xl theme-text-neutral leading-relaxed mt-6">

    {{ $setting->about_description_2 ?? '' }}

</p>


                <!-- STATS -->

                <div class="grid grid-cols-2 sm:grid-cols-4 theme-content-gap mb-10">

                    <div class="theme-card theme-radius p-6 theme-shadow text-center">

                        <h3 class="text-4xl font-black theme-text-primary mb-2">
                            3M+
                        </h3>

                        <p class="text-sm theme-text-neutral">
                            Community
                        </p>

                    </div>

                    <div class="theme-card theme-radius p-6 theme-shadow text-center">

                        <h3 class="text-4xl font-black theme-text-primary mb-2">
                            15+
                        </h3>

                        <p class="text-sm theme-text-neutral">
                            Years Experience
                        </p>

                    </div>

                    <div class="theme-card theme-radius p-6 theme-shadow text-center">

                        <h3 class="text-4xl font-black theme-text-primary mb-2">
                            15K+
                        </h3>

                        <p class="text-sm theme-text-neutral">
                            Transformations
                        </p>

                    </div>

                    <div class="theme-card theme-radius p-6 theme-shadow text-center">

                        <h3 class="text-4xl font-black theme-text-primary mb-2">
                            24/7
                        </h3>

                        <p class="text-sm theme-text-neutral">
                            Support
                        </p>

                    </div>

                </div>

                <!-- BUTTONS -->

                <div class="flex flex-wrap theme-content-gap">

                    <a href="/plans"
                       class="theme-status-warning hover:theme-status-warning theme-text-secondary font-bold px-10 py-5 theme-radius transition duration-300 theme-shadow">

                        {{ $setting->about_cta_text }}

                    </a>

                    <a href="/contact"
                       class="border-2 theme-border hover:theme-surface-strong hover:theme-text-on-strong theme-text-secondary font-bold px-10 py-5 theme-radius transition duration-300">

                        {{ $setting->contact_cta_text }}

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- MISSION -->

<section class="theme-card theme-section">

    <div class="theme-page-container text-center">

        <p class="uppercase tracking-[5px] theme-text-primary font-bold mb-4">
            Our Mission
        </p>

        <h2 class="text-5xl font-black theme-text-secondary mb-8">
            Helping People Build
            <span class="theme-text-primary">
                Confidence & Discipline
            </span>
        </h2>

        <p class="max-w-4xl mx-auto text-xl theme-text-neutral leading-relaxed">
            Our mission is to help people transform physically and mentally
            through structured fitness programs, proper nutrition guidance,
            mindset coaching, and long-term sustainable lifestyle changes.
        </p>

    </div>

</section>

@endsection
