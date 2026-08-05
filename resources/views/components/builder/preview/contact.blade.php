@php
    $settings = \App\Models\Setting::first();
@endphp


<section class="bg-white py-20 h-full overflow-auto">

    <div class="max-w-4xl mx-auto px-6">

        <div class="text-center mb-12">

            <h2 class="text-5xl font-black">

    {{ $settings->contact_heading }}

</h2>

          <p class="mt-4 text-gray-500">

    {{ $settings->contact_description }}

</p>

        </div>

        <div class="grid gap-6">

            <input
                class="border rounded-xl p-4"
                placeholder="Full Name">

            <input
                class="border rounded-xl p-4"
                placeholder="Email">

            <input
                class="border rounded-xl p-4"
                placeholder="Phone Number">

            <textarea
                class="border rounded-xl p-4 h-40"
                placeholder="Your Message"></textarea>

            <button
                class="bg-yellow-500 rounded-xl py-4 font-bold">

                Send Message

            </button>

</div>



        </div>

    </div>

</section>


