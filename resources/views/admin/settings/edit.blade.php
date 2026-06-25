
@extends('layouts.app')

@section('content')

<section class="p-10">

    <div class="max-w-5xl mx-auto">

        <div class="mb-10">

            <h1 class="text-4xl font-black text-black">
                Website Settings
            </h1>

            <p class="text-gray-500 mt-2">
                Manage your website branding, SEO, contact info and CTA globally.
            </p>

        </div>

        @if(session('success'))

            <div class="mb-6 rounded-2xl bg-green-100 text-green-700 px-6 py-4 font-semibold">
                {{ session('success') }}
            </div>

        @endif

        <form
            action="{{ route('admin.settings.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-10"
        >

            @csrf
            @method('PUT')

            <!-- BRANDING -->

            <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">

                <h2 class="text-2xl font-bold mb-6">
                    Branding
                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="font-semibold mb-2 block">
                            Site Name
                        </label>

                        <input
 type="text"
                            name="site_name"
                            value="{{ old('site_name', $setting->site_name ??'') }}"
                            class="w-full rounded-2xl border-gray-300"
                        >

                    </div>

                    <div>

                        <label class="font-semibold mb-2 block">
                            Site Tagline
                        </label>

                        <input
                            type="text"
                            name="site_tagline"
                            value="{{ old('site_tagline', $setting->site_tagline ?? '') }}"
                            class="w-full rounded-2xl border-gray-300"
                        >

                    </div>

                    <div>

                        <label class="font-semibold mb-2 block">
                            Logo
                        </label>

                        <input
                            type="file"
                            name="logo"
                            class="w-full"
                        >

                    </div>

                    <div>

                        <label class="font-semibold mb-2 block">
                            Favicon
                        </label>

                        <input
                            type="file"
                            name="favicon"
                            class="w-full"
                        >

                    </div>

                </div>

            </div>

            <!-- CONTACT -->

            <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">

                <h2 class="text-2xl font-bold mb-6">
                    Contact Information
                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <div>

                        <label class="font-semibold mb-2 block">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $setting->phone ?? '') }}"
                            class="w-full rounded-2xl border-gray-300"
                        >

                    </div>

                    <div>

                        <label class="font-semibold mb-2 block">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $setting->email ?? '') }}"
                            class="w-full rounded-2xl border-gray-300"
                        >

                    </div>

                    <div class="md:col-span-2">

                        <label class="font-semibold mb-2 block">
                            Address
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            class="w-full rounded-2xl border-gray-300"
                        >{{ old('address', $setting->address ?? '') }}</textarea>

                    </div>

                </div>

            </div>

            <!-- SOCIALS -->

            <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">

                <h2 class="text-2xl font-bold mb-6">
                    Social Media
                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <input type="text" name="instagram" placeholder="Instagram URL"
                        value="{{ old('instagram', $setting->instagram ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                    <input type="text" name="youtube" placeholder="YouTube URL"
                        value="{{ old('youtube', $setting->youtube ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                    <input type="text" name="facebook" placeholder="Facebook URL"
                        value="{{ old('facebook', $setting->facebook ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                    <input type="text" name="twitter" placeholder="Twitter URL"
                        value="{{ old('twitter', $setting->twitter ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                </div>

            </div>

            <!-- CTA -->

            <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">

                <h2 class="text-2xl font-bold mb-6">
                    CTA Button
                </h2>

                <div class="grid md:grid-cols-2 gap-6">

                    <input type="text" name="cta_button_text"
                        placeholder="Button Text"
                        value="{{ old('cta_button_text', $setting->cta_button_text ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                    <input type="text" name="cta_button_link"
                        placeholder="Button Link"
                        value="{{ old('cta_button_link', $setting->cta_button_link ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                </div>

            </div>

            <!-- SEO -->

            <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">

                <h2 class="text-2xl font-bold mb-6">
                    SEO Settings
                </h2>

                <div class="space-y-6">

                    <input type="text"
                        name="meta_title"
                        placeholder="Meta Title"
                        value="{{ old('meta_title', $setting->meta_title ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                    <textarea
                        name="meta_description"
                        rows="4"
                        placeholder="Meta Description"
                        class="w-full rounded-2xl border-gray-300"
                    >{{ old('meta_description', $setting->meta_description ?? '') }}</textarea>

                    <input type="text"
                        name="meta_keywords"
                        placeholder="Meta Keywords"
                        value="{{ old('meta_keywords', $setting->meta_keywords ?? '') }}"
                        class="w-full rounded-2xl border-gray-300">

                </div>

            </div>

            <!-- FOOTER -->

            <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100">

                <h2 class="text-2xl font-bold mb-6">
                    Footer
                </h2>

                <textarea
                    name="footer_text"
                    rows="4"
                    class="w-full rounded-2xl border-gray-300"
                >{{ old('footer_text', $setting->footer_text ?? '') }}</textarea>

            </div>

            <button
                type="submit"
                class="bg-yellow-500 hover:bg-yellow-400 text-black px-10 py-5 rounded-2xl font-bold shadow-xl transition duration-300"
            >
                Save Settings
            </button>

        </form>

    </div>

</section>

@endsection
