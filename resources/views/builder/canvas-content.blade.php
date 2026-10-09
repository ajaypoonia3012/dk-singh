@if(($builderTab ?? 'sections') === 'theme')
    {{-- THEME DESIGN SYSTEM SHOWCASE --}}
    @include('components.builder.preview.theme-showcase')

@elseif(($builderTab ?? 'sections') === 'general')
    {{-- GENERAL BRANDING & CONTACT SHOWCASE --}}
    @include('components.builder.preview.general-showcase')

@elseif(($builderTab ?? 'sections') === 'cards')
    {{-- HOMEPAGE CARDS HIGH-IMPACT GRID --}}
    @include('components.builder.preview.homepage-cards')

@elseif(($builderTab ?? 'sections') === 'sections')
    {{-- COMPLETE LIVE HOMEPAGE PREVIEW (Real Canonical Models & Public Components) --}}
    <div class="space-y-0 w-full bg-white dark:bg-black text-slate-900 dark:text-white">
        @php
            $enabledSections = collect($sections ?? [])->where('enabled', true)->sortBy('sort_order');
        @endphp

        @if($enabledSections->count())
            @foreach($enabledSections as $sec)
                <div
                    id="preview-section-{{ $sec->section }}"
                    wire:key="preview-section-{{ $sec->id }}"
                    class="relative transition-all {{ optional($selectedSection)->id === $sec->id ? 'ring-2 ring-amber-500/70' : '' }}"
                >
                    {{-- Active section label tag for visual confirmation in builder --}}
                    @if(optional($selectedSection)->id === $sec->id)
                        <div class="absolute top-2 left-2 z-30 px-2 py-0.5 rounded-md bg-amber-500 text-slate-950 font-black text-[10px] uppercase tracking-wider shadow-sm flex items-center gap-1">
                            <span>Editing:</span>
                            <span>{{ $sec->title }}</span>
                        </div>
                    @endif

                    @switch($sec->section)
                        @case('hero')
                            @include('components.builder.preview.hero')
                            @break

                        @case('homepage_cards')
                            @include('components.builder.preview.homepage-cards')
                            @break

                        @case('products')
                            @include('components.builder.preview.products')
                            @break

                        @case('programs')
                            @include('components.builder.preview.programs')
                            @break

                        @case('transformations')
                            @include('components.builder.preview.transformations')
                            @break

                        @case('testimonials')
                            @include('components.builder.preview.testimonials')
                            @break

                        @case('blogs')
                            @include('components.builder.preview.blogs')
                            @break

                        @case('contact')
                            @include('components.builder.preview.contact')
                            @break

                        @default
                            <div class="py-12 text-center text-slate-400">
                                <p class="text-xs font-semibold">{{ $sec->title }} section</p>
                            </div>
                    @endswitch
                </div>
            @endforeach
        @else
            <div class="py-24 text-center text-slate-400 px-6">
                <span class="text-4xl mb-3 block">👁️</span>
                <p class="text-sm font-bold text-slate-700 dark:text-zinc-300">All Homepage Sections Are Hidden</p>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Enable one or more sections in the Homepage Sections panel on the left to see the live homepage simulation.
                </p>
            </div>
        @endif
    </div>

@else
    {{-- FALLBACK TO SELECTED SECTION COMPONENT --}}
    @if($selectedSection)
        @switch($selectedSection->section)
            @case('hero')
                @include('components.builder.preview.hero')
                @break
            @case('homepage_cards')
                @include('components.builder.preview.homepage-cards')
                @break
            @case('products')
                @include('components.builder.preview.products')
                @break
            @case('programs')
                @include('components.builder.preview.programs')
                @break
            @case('transformations')
                @include('components.builder.preview.transformations')
                @break
            @case('testimonials')
                @include('components.builder.preview.testimonials')
                @break
            @case('blogs')
                @include('components.builder.preview.blogs')
                @break
            @case('contact')
                @include('components.builder.preview.contact')
                @break
        @endswitch
    @endif
@endif
