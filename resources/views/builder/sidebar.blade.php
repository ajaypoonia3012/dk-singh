<div class="bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200/90 dark:border-white/10 shadow-sm h-full flex flex-col overflow-hidden">

    {{-- SIDEBAR NAVIGATION TABS --}}
    <div class="p-3 border-b border-slate-200/80 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50">
        <div class="grid grid-cols-4 gap-1 p-1 rounded-xl bg-slate-200/70 dark:bg-zinc-800 text-[11px] font-bold">
            <button
                type="button"
                wire:click="setBuilderTab('sections')"
                class="py-1.5 px-2 rounded-lg text-center transition flex flex-col items-center gap-0.5 {{ ($builderTab ?? 'sections') === 'sections' ? 'bg-white dark:bg-zinc-900 text-amber-600 dark:text-amber-400 shadow-xs font-black' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900' }}"
            >
                <span class="text-xs">🗂️</span>
                <span>Sections</span>
            </button>

            <button
                type="button"
                wire:click="setBuilderTab('cards')"
                class="py-1.5 px-2 rounded-lg text-center transition flex flex-col items-center gap-0.5 {{ ($builderTab ?? 'sections') === 'cards' ? 'bg-white dark:bg-zinc-900 text-amber-600 dark:text-amber-400 shadow-xs font-black' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900' }}"
            >
                <span class="text-xs">🃏</span>
                <span>Cards</span>
            </button>

            <button
                type="button"
                wire:click="setBuilderTab('general')"
                class="py-1.5 px-2 rounded-lg text-center transition flex flex-col items-center gap-0.5 {{ ($builderTab ?? 'sections') === 'general' ? 'bg-white dark:bg-zinc-900 text-amber-600 dark:text-amber-400 shadow-xs font-black' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900' }}"
            >
                <span class="text-xs">🌐</span>
                <span>General</span>
            </button>

            <button
                type="button"
                wire:click="setBuilderTab('theme')"
                class="py-1.5 px-2 rounded-lg text-center transition flex flex-col items-center gap-0.5 {{ ($builderTab ?? 'sections') === 'theme' ? 'bg-white dark:bg-zinc-900 text-amber-600 dark:text-amber-400 shadow-xs font-black' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900' }}"
            >
                <span class="text-xs">🎨</span>
                <span>Theme</span>
            </button>
        </div>
    </div>

    {{-- TAB 1: SECTIONS LIST --}}
    @if(($builderTab ?? 'sections') === 'sections')
        <div class="p-3 border-b border-slate-200/80 dark:border-zinc-800 flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Homepage Sections
                </h3>
                <p class="text-[11px] text-slate-500 dark:text-zinc-400">
                    Order & visibility synchronized
                </p>
            </div>
            <span class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold text-xs">
                {{ count($sections) }} total
            </span>
        </div>

        <div class="p-3 space-y-2.5 overflow-y-auto flex-1">
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
                    @class([
                        'rounded-xl border transition-all duration-200 group',
                        'shadow-sm' => $isSelected,
                    ])
                    style="{{ $isSelected ? 'border: 2px solid #f59e0b !important; background-color: rgba(245, 158, 11, 0.08) !important; box-shadow: 0 0 0 1px #f59e0b;' : 'border: 1px solid #e2e8f0; background-color: #ffffff;' }}"
                >
                    <button
                        wire:click="selectSection({{ $section->id }})"
                        class="w-full text-left p-3 focus:outline-none"
                    >
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-sm flex-shrink-0 {{ $isSelected ? 'ring-2 ring-amber-500/30' : '' }}">
                                    {{ $icon }}
                                </span>
                                <div class="min-w-0">
                                    <div class="font-bold text-xs text-slate-900 dark:text-white truncate {{ $isSelected ? 'text-amber-950 font-black' : '' }}">
                                        {{ $section->title }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 dark:text-zinc-400 capitalize">
                                        {{ $section->template ?? 'Default' }} template
                                    </div>
                                </div>
                            </div>

                            {{-- INTERACTIVE VISIBILITY TOGGLE (Auto-syncs with ThemeSetting.show_*) --}}
                            <div class="flex-shrink-0">
                                <button
                                    type="button"
                                    wire:click.stop="toggleSection({{ $section->id }})"
                                    title="Click to toggle section visibility"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold transition hover:scale-105 {{ $section->enabled ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-100 text-slate-600 border border-slate-300' }}"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full {{ $section->enabled ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    <span>{{ $section->enabled ? 'Active' : 'Hidden' }}</span>
                                </button>
                            </div>
                        </div>
                    </button>

                    {{-- REORDER BAR --}}
                    <div class="border-t border-slate-100 dark:border-zinc-800/80 px-3 py-1.5 flex justify-between items-center bg-slate-50/60 dark:bg-zinc-900/60 rounded-b-xl">
                        <div class="flex items-center gap-1">
                            <button
                                wire:click="moveUp({{ $section->id }})"
                                type="button"
                                title="Move section up"
                                class="w-6 h-6 rounded-md flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition text-[11px] font-bold border border-slate-200 bg-white"
                            >
                                ▲
                            </button>

                            <button
                                wire:click="moveDown({{ $section->id }})"
                                type="button"
                                title="Move section down"
                                class="w-6 h-6 rounded-md flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-slate-200 transition text-[11px] font-bold border border-slate-200 bg-white"
                            >
                                ▼
                            </button>
                        </div>

                        <span class="text-[10px] font-mono font-bold text-slate-400">
                            Order #{{ $section->sort_order }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

    {{-- TAB 2: HOMEPAGE CARDS --}}
    @elseif(($builderTab ?? 'sections') === 'cards')
        <div class="p-3 border-b border-slate-200/80 dark:border-zinc-800 flex items-center justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                    Homepage Cards
                </h3>
                <p class="text-[11px] text-slate-500 dark:text-zinc-400">
                    High-impact hero grid cards
                </p>
            </div>
            <button
                type="button"
                wire:click="addHomepageCard"
                class="px-2 py-1 rounded-md bg-amber-500 hover:bg-amber-600 text-black font-extrabold text-[11px] shadow-xs flex items-center gap-1"
            >
                <span>+</span> Add
            </button>
        </div>

        <div class="p-3 space-y-2 overflow-y-auto flex-1">
            @foreach($homepageCards as $card)
                @php
                    $isCardSelected = $selectedHomepageCardId === $card->id;
                @endphp
                <div
                    class="p-3 rounded-xl border transition {{ $isCardSelected ? 'border-amber-500 bg-amber-500/10 shadow-xs' : 'border-slate-200 bg-white dark:bg-zinc-800/60' }}"
                >
                    <div class="flex items-center justify-between gap-2">
                        <button
                            type="button"
                            wire:click="selectHomepageCard({{ $card->id }})"
                            class="text-left flex-1 min-w-0"
                        >
                            <div class="font-bold text-xs truncate text-slate-900 dark:text-white flex items-center gap-1.5">
                                <span>{{ $card->icon ?? '⭐' }}</span>
                                <span class="truncate">{{ $card->title }}</span>
                            </div>
                            <div class="text-[10px] text-slate-500 truncate mt-0.5">
                                {{ $card->subtitle ?? 'No subtitle' }}
                            </div>
                        </button>

                        <button
                            type="button"
                            wire:click="toggleHomepageCard({{ $card->id }})"
                            class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $card->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}"
                        >
                            {{ $card->is_active ? 'Active' : 'Hidden' }}
                        </button>
                    </div>

                    <div class="mt-2 pt-2 border-t border-slate-100 dark:border-zinc-700/60 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1">
                            <button
                                wire:click="moveHomepageCardUp({{ $card->id }})"
                                class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-[10px]"
                            >▲</button>
                            <button
                                wire:click="moveHomepageCardDown({{ $card->id }})"
                                class="w-6 h-6 rounded bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-[10px]"
                            >▼</button>
                            <button
                                wire:click="duplicateHomepageCard({{ $card->id }})"
                                title="Duplicate card"
                                class="px-1.5 h-6 rounded bg-slate-100 hover:bg-slate-200 text-[10px] font-semibold"
                            >Copy</button>
                        </div>
                        <button
                            wire:click="deleteHomepageCard({{ $card->id }})"
                            class="text-red-500 hover:text-red-700 text-[11px] font-bold"
                        >Delete</button>
                    </div>
                </div>
            @endforeach
        </div>

    {{-- TAB 3: GENERAL WEBSITE SHORTCUTS --}}
    @elseif(($builderTab ?? 'sections') === 'general')
        <div class="p-4 space-y-4 overflow-y-auto flex-1">
            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/30">
                <span class="text-xs font-bold text-amber-900 dark:text-amber-300 block">Single Source of Truth</span>
                <p class="text-[11px] text-slate-600 dark:text-zinc-300 mt-1 leading-relaxed">
                    General settings are canonically stored in the <strong>Setting</strong> model and rendered on public pages, metadata, and footer.
                </p>
            </div>

            <div class="space-y-2 text-xs">
                <div class="p-3 rounded-xl border border-slate-200 bg-white dark:bg-zinc-800/80">
                    <span class="font-bold block text-slate-900 dark:text-white">Site Name</span>
                    <span class="text-slate-500 block truncate mt-0.5">{{ $general['site_name'] ?? 'Not set' }}</span>
                </div>
                <div class="p-3 rounded-xl border border-slate-200 bg-white dark:bg-zinc-800/80">
                    <span class="font-bold block text-slate-900 dark:text-white">Email</span>
                    <span class="text-slate-500 block truncate mt-0.5">{{ $general['email'] ?? 'Not set' }}</span>
                </div>
                <div class="p-3 rounded-xl border border-slate-200 bg-white dark:bg-zinc-800/80">
                    <span class="font-bold block text-slate-900 dark:text-white">SEO Title</span>
                    <span class="text-slate-500 block truncate mt-0.5">{{ $general['meta_title'] ?? 'Default' }}</span>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 text-center">
                Configure full identity & assets in the right panel.
            </p>
        </div>

    {{-- TAB 4: THEME & DESIGN SHORTCUTS --}}
    @elseif(($builderTab ?? 'sections') === 'theme')
        <div class="p-4 space-y-4 overflow-y-auto flex-1">
            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/30">
                <span class="text-xs font-bold text-amber-900 dark:text-amber-300 block">Design System Source</span>
                <p class="text-[11px] text-slate-600 dark:text-zinc-300 mt-1 leading-relaxed">
                    Theme settings are canonically stored in <strong>ThemeSetting</strong> and drive CSS variables across the website.
                </p>
            </div>

            <div class="space-y-2">
                <div class="p-3 rounded-xl border border-slate-200 bg-white dark:bg-zinc-800/80">
                    <span class="text-xs font-bold block text-slate-900 dark:text-white mb-2">Active Palette Swatches</span>
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-md shadow-xs border" style="background-color: {{ $theme['primary_color'] ?? '#facc15' }};"></span>
                        <span class="w-6 h-6 rounded-md shadow-xs border" style="background-color: {{ $theme['secondary_color'] ?? '#111111' }};"></span>
                        <span class="w-6 h-6 rounded-md shadow-xs border" style="background-color: {{ $theme['accent_color'] ?? '#ffffff' }};"></span>
                    </div>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 bg-white dark:bg-zinc-800/80 text-xs">
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Heading Font:</span>
                        <span class="font-bold">{{ $theme['heading_font'] ?? 'Poppins' }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Button Radius:</span>
                        <span class="font-bold">{{ $theme['button_radius'] ?? '1rem' }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Card Radius:</span>
                        <span class="font-bold">{{ $theme['card_radius'] ?? '1.5rem' }}</span>
                    </div>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 text-center">
                Configure tokens & 1-click presets in the right panel.
            </p>
        </div>
    @endif

</div>