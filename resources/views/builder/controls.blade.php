<div class="wb-controls bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200/90 dark:border-white/10 shadow-sm h-full flex flex-col overflow-hidden min-w-0">

    {{-- DYNAMIC CONTENT BASED ON ACTIVE BUILDER TAB --}}
    @if(($builderTab ?? 'sections') === 'sections')

        {{-- TOP HEADER FOR SECTIONS TAB --}}
        <div class="p-4 border-b border-slate-200/80 dark:border-zinc-800 flex items-center justify-between gap-3 bg-slate-50/50 dark:bg-zinc-900/50 flex-shrink-0">
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    Homepage Sections
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">
                    Order &amp; visibility synchronized with ThemeSetting
                </p>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-700 dark:text-amber-400 font-bold text-xs">
                {{ count($sections) }} Total
            </span>
        </div>

        {{-- SCROLLABLE SECTIONS LIST & ACTIVE SECTION FORM --}}
        <div class="p-4 space-y-4 overflow-y-auto flex-1 min-w-0">

            {{-- SECTION LIST CARDS --}}
            <div class="space-y-2.5">
                @php
                    $sectionIcons = [
                        'hero' => '⚡',
                        'homepage_cards' => '🗂️',
                        'blogs' => '✍️',
                        'programs' => '🏋️',
                        'products' => '📦',
                        'transformations' => '✨',
                        'testimonials' => '💬',
                        'contact' => '📍',
                    ];
                @endphp

                @foreach($sections as $section)
                    @php
                        $isSelected = optional($selectedSection)->id === $section->id;
                        $icon = $sectionIcons[$section->section] ?? '📄';
                    @endphp

                    <div
                        wire:key="section-card-{{ $section->id }}"
                        @class([
                            'rounded-xl border transition-all duration-200 overflow-hidden',
                            'border-amber-500 bg-amber-50/30 dark:bg-amber-950/20 shadow-sm ring-1 ring-amber-500/30' => $isSelected,
                            'border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-slate-300' => ! $isSelected,
                        ])
                    >
                        {{-- CARD HEADER ROW (Clean un-nested layout) --}}
                        <div class="p-3 flex items-center justify-between gap-3">

                            {{-- Left: Clickable Section Selection --}}
                            <div
                                wire:click="selectSection({{ $section->id }})"
                                class="flex items-center gap-3 min-w-0 flex-1 cursor-pointer"
                            >
                                <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-sm flex-shrink-0 {{ $isSelected ? 'ring-2 ring-amber-500' : '' }}">
                                    {{ $icon }}
                                </span>
                                <div class="min-w-0">
                                    <div class="font-bold text-xs text-slate-900 dark:text-white truncate {{ $isSelected ? 'text-amber-900 dark:text-amber-300 font-black' : '' }}">
                                        {{ $section->title }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-zinc-400 capitalize">
                                        {{ $section->template ?? 'Default' }} template
                                    </div>
                                </div>
                            </div>

                            {{-- Right: Independent Visibility Toggle Button --}}
                            <div class="flex-shrink-0">
                                <button
                                    type="button"
                                    wire:click="toggleSection({{ $section->id }})"
                                    title="Click to toggle visibility (auto-syncs with ThemeSetting)"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition hover:scale-105 {{ $section->enabled ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700' : 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400 border border-slate-300 dark:border-zinc-700' }}"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full {{ $section->enabled ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    <span>{{ $section->enabled ? 'Active' : 'Hidden' }}</span>
                                </button>
                            </div>

                        </div>

                        {{-- CARD ACTION FOOTER --}}
                        <div class="border-t border-slate-100 dark:border-zinc-800/80 px-3 py-1.5 flex justify-between items-center bg-slate-50/60 dark:bg-zinc-900/60 text-xs">
                            <div class="flex items-center gap-1.5">
                                <button
                                    type="button"
                                    wire:click="moveUp({{ $section->id }})"
                                    title="Move section up"
                                    class="w-6 h-6 rounded-md flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition text-[11px] font-bold border border-slate-200 bg-white dark:bg-zinc-800 dark:text-zinc-300"
                                >
                                    ▲
                                </button>
                                <button
                                    type="button"
                                    wire:click="moveDown({{ $section->id }})"
                                    title="Move section down"
                                    class="w-6 h-6 rounded-md flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition text-[11px] font-bold border border-slate-200 bg-white dark:bg-zinc-800 dark:text-zinc-300"
                                >
                                    ▼
                                </button>
                                <span class="text-[10px] font-mono font-bold text-slate-400 ml-1">
                                    Order #{{ $section->sort_order }}
                                </span>
                            </div>

                            <button
                                type="button"
                                wire:click="selectSection({{ $section->id }})"
                                class="text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:text-amber-700 hover:underline"
                            >
                                {{ $isSelected ? 'Editing' : 'Configure →' }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- INLINE SECTION PROPERTIES CONFIGURATION (FOR SELECTED SECTION) --}}
            @if($selectedSection)
                <div class="mt-6 pt-5 border-t border-slate-200 dark:border-zinc-800">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-900 dark:text-white flex items-center gap-2">
                                <span>Configure:</span>
                                <span class="text-amber-600 dark:text-amber-400">{{ $selectedSection->title }}</span>
                            </h4>
                            <p class="text-[11px] text-slate-500">Edit content, headings, and media for this section</p>
                        </div>
                    </div>

                    <div class="bg-slate-50/70 dark:bg-zinc-800/40 rounded-xl p-4 border border-slate-200/80 dark:border-zinc-700/60">
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

                            @default
                                <p class="text-xs text-slate-500">Section properties configured via section cards above.</p>
                        @endswitch
                    </div>
                </div>
            @endif

        </div>

    @elseif(($builderTab ?? 'sections') === 'cards')

        {{-- CARDS TAB PROPERTIES --}}
        <div class="p-4 border-b border-slate-200/80 dark:border-zinc-800 flex items-center justify-between gap-3 bg-slate-50/50 dark:bg-zinc-900/50 flex-shrink-0">
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">
                    Homepage Cards
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">
                    High-impact hero grid cards (Single source: HomepageCard)
                </p>
            </div>
            <button
                type="button"
                wire:click="addHomepageCard"
                class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs shadow-xs flex items-center gap-1.5 transition"
            >
                <span>+</span> Add Card
            </button>
        </div>

        <div class="p-4 overflow-y-auto flex-1 min-w-0">
            @include('builder.properties.homepage-cards')
        </div>

    @elseif(($builderTab ?? 'sections') === 'general')

        {{-- GENERAL TAB PROPERTIES --}}
        <div class="p-4 border-b border-slate-200/80 dark:border-zinc-800 flex items-center justify-between gap-3 bg-slate-50/50 dark:bg-zinc-900/50 flex-shrink-0">
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">
                    General Website Settings
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">
                    Brand identity, contact info, and SEO (Single source: Setting)
                </p>
            </div>
            <button
                type="button"
                wire:click="saveGeneral"
                class="px-3.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs shadow-xs transition"
            >
                Save General
            </button>
        </div>

        <div class="p-4 overflow-y-auto flex-1 min-w-0">
            @include('builder.properties.general')
        </div>

    @elseif(($builderTab ?? 'sections') === 'theme')

        {{-- THEME TAB PROPERTIES --}}
        <div class="p-4 border-b border-slate-200/80 dark:border-zinc-800 flex items-center justify-between gap-3 bg-slate-50/50 dark:bg-zinc-900/50 flex-shrink-0">
            <div>
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">
                    Theme &amp; Design System
                </h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">
                    1-Click presets &amp; tokens (Single source: ThemeSetting)
                </p>
            </div>
            <button
                type="button"
                wire:click="saveThemeSettings"
                class="px-3.5 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs shadow-xs transition"
            >
                Save Theme
            </button>
        </div>

        <div class="p-4 overflow-y-auto flex-1 min-w-0">
            @include('builder.properties.theme')
        </div>

    @endif

</div>
