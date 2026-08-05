<div class="w-full">

    {{-- TOP BAR --}}
    <div class="bg-white rounded-xl shadow p-4 mb-6">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold">
                    🎨 Website Builder
                </h2>

                <p class="text-sm text-gray-500">
                    Build your homepage visually.
                </p>

            </div>

            <div class="flex items-center gap-2">

                <button
                    wire:click="changeDevice('desktop')"
                    class="px-4 py-2 rounded-lg border {{ $selectedDevice == 'desktop' ? 'bg-amber-500 text-white' : '' }}">
                    🖥 Desktop
                </button>

                <button
                    wire:click="changeDevice('tablet')"
                    class="px-4 py-2 rounded-lg border {{ $selectedDevice == 'tablet' ? 'bg-amber-500 text-white' : '' }}">
                    📱 Tablet
                </button>

                <button
                    wire:click="changeDevice('mobile')"
                    class="px-4 py-2 rounded-lg border {{ $selectedDevice == 'mobile' ? 'bg-amber-500 text-white' : '' }}">
                    📲 Mobile
                </button>

            </div>

        </div>

    </div>

    {{-- BUILDER --}}
    <div
        class="grid gap-6"
        style="grid-template-columns:280px minmax(0,1fr) 340px;height:85vh;"
    >

        {{-- LEFT --}}
        <div class="overflow-hidden rounded-xl">

            @include('builder.sidebar')

        </div>

        {{-- CENTER --}}
        <div class="min-w-0 overflow-hidden rounded-xl">

            @include('builder.canvas')

        </div>

        {{-- RIGHT --}}
        <div
            wire:key="properties-{{ optional($selectedSection)->id }}"
            class="bg-white rounded-xl shadow p-5 h-[85vh] overflow-y-auto"
        >

            @if($selectedSection)

                @switch($selectedSection->section)

                    @case('hero')
                        @include('builder.properties.hero')
                        @break

                    @case('homepage_cards')
                        @include('builder.properties.homepage-cards')
                        @break

                    @case('products')
                        @include('builder.properties.products')
                        @break

                    @case('programs')
                        @include('builder.properties.programs')
                        @break

                    @case('transformations')
                        @include('builder.properties.transformations')
                        @break

                    @case('testimonials')
                        @include('builder.properties.testimonials')
                        @break

                    @case('blogs')
                        @include('builder.properties.blogs')
                        @break

                    @case('contact')
                        @include('builder.properties.contact')
                        @break

                @endswitch

            @endif

        </div>

    </div>

    {{-- GLOBAL MEDIA PICKER --}}
    <x-media.picker-modal wire:model="showMediaPicker">

        <livewire:media.media-library />

    </x-media.picker-modal>

</div>