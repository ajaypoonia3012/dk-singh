<div class="wb-sidebar bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200/90 dark:border-white/10 shadow-sm h-full flex flex-col overflow-hidden min-w-0">

    {{-- HEADER --}}
    <div class="p-4 border-b border-slate-200/80 dark:border-zinc-800">
        <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500">
            Website Manager
        </h2>
        <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-0.5 font-medium">
            Single Source of Truth
        </p>
    </div>

    {{-- PRIMARY VERTICAL NAVIGATION (Phase 5) --}}
    <nav class="p-3 space-y-1.5 border-b border-slate-200/80 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50" aria-label="Builder Navigation">

        {{-- 1. SECTIONS TAB --}}
        <button
            type="button"
            wire:click="setBuilderTab('sections')"
            class="w-full flex items-center justify-between p-2.5 rounded-xl text-left transition {{ ($builderTab ?? 'sections') === 'sections' ? 'bg-amber-500 text-slate-950 font-black shadow-sm ring-1 ring-amber-600/30' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 font-semibold' }}"
        >
            <div class="flex items-center gap-3 min-w-0">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ ($builderTab ?? 'sections') === 'sections' ? 'bg-slate-950/15 text-slate-950' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </span>
                <div class="min-w-0">
                    <span class="text-xs block truncate leading-tight">Sections</span>
                    <span class="text-[10px] block truncate opacity-75 font-normal">Homepage layout</span>
                </div>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold flex-shrink-0 {{ ($builderTab ?? 'sections') === 'sections' ? 'bg-slate-950/20 text-slate-950' : 'bg-slate-200/80 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400' }}">
                {{ $sections ? count($sections) : 0 }}
            </span>
        </button>

        {{-- 2. CARDS TAB --}}
        <button
            type="button"
            wire:click="setBuilderTab('cards')"
            class="w-full flex items-center justify-between p-2.5 rounded-xl text-left transition {{ ($builderTab ?? 'sections') === 'cards' ? 'bg-amber-500 text-slate-950 font-black shadow-sm ring-1 ring-amber-600/30' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 font-semibold' }}"
        >
            <div class="flex items-center gap-3 min-w-0">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ ($builderTab ?? 'sections') === 'cards' ? 'bg-slate-950/15 text-slate-950' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </span>
                <div class="min-w-0 pr-1">
                    <span class="text-xs block font-bold truncate leading-tight">Cards</span>
                    <span class="text-[10px] block truncate opacity-75 font-normal">Feature cards</span>
                </div>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold flex-shrink-0 {{ ($builderTab ?? 'sections') === 'cards' ? 'bg-slate-950/20 text-slate-950' : 'bg-slate-200/80 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400' }}">
                {{ $homepageCards ? count($homepageCards) : 0 }}
            </span>
        </button>

        {{-- 3. GENERAL TAB --}}
        <button
            type="button"
            wire:click="setBuilderTab('general')"
            class="w-full flex items-center justify-between p-2.5 rounded-xl text-left transition {{ ($builderTab ?? 'sections') === 'general' ? 'bg-amber-500 text-slate-950 font-black shadow-sm ring-1 ring-amber-600/30' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 font-semibold' }}"
        >
            <div class="flex items-center gap-3 min-w-0">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ ($builderTab ?? 'sections') === 'general' ? 'bg-slate-950/15 text-slate-950' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </span>
                <div class="min-w-0 pr-1">
                    <span class="text-xs block font-bold truncate leading-tight">General</span>
                    <span class="text-[10px] block truncate opacity-75 font-normal">Brand, Contact & SEO</span>
                </div>
            </div>
            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0" title="Canonical Setting active"></span>
        </button>

        {{-- 4. THEME TAB --}}
        <button
            type="button"
            wire:click="setBuilderTab('theme')"
            class="w-full flex items-center justify-between p-2.5 rounded-xl text-left transition {{ ($builderTab ?? 'sections') === 'theme' ? 'bg-amber-500 text-slate-950 font-black shadow-sm ring-1 ring-amber-600/30' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 font-semibold' }}"
        >
            <div class="flex items-center gap-3 min-w-0">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ ($builderTab ?? 'sections') === 'theme' ? 'bg-slate-950/15 text-slate-950' : 'bg-slate-100 dark:bg-zinc-800 text-slate-600 dark:text-zinc-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 5 5 0 015-5h2a2 2 0 002-2 4 4 0 014-4 2 2 0 012 2v2a4 4 0 01-4 4h-2a2 2 0 00-2 2 5 5 0 01-5 5z"></path>
                    </svg>
                </span>
                <div class="min-w-0 pr-1">
                    <span class="text-xs block font-bold truncate leading-tight">Theme</span>
                    <span class="text-[10px] block truncate opacity-75 font-normal">Design & Tokens</span>
                </div>
            </div>
            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider flex-shrink-0 {{ ($builderTab ?? 'sections') === 'theme' ? 'bg-slate-950 text-amber-400' : 'bg-slate-200 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400' }}">
                Active
            </span>
        </button>

    </nav>

    {{-- SECONDARY CONTEXT / DIRECTORY PANEL --}}
    <div class="p-3 space-y-3 overflow-y-auto flex-1 text-xs">

        @if(($builderTab ?? 'sections') === 'sections')
            <div class="px-1">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 block mb-2">
                    Section Directory
                </span>
                <div class="space-y-1">
                    @foreach($sections as $sec)
                        <button
                            type="button"
                            wire:click="selectSection({{ $sec->id }})"
                            class="w-full flex items-center justify-between px-2.5 py-1.5 rounded-lg text-left transition {{ optional($selectedSection)->id === $sec->id ? 'bg-amber-500/10 text-amber-900 dark:text-amber-300 font-bold border border-amber-500/30' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}"
                        >
                            <span class="truncate flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full {{ $sec->enabled ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-zinc-600' }}"></span>
                                <span class="truncate">{{ $sec->title }}</span>
                            </span>
                            <span class="text-[10px] font-mono text-slate-400">#{{ $sec->sort_order }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

        @elseif(($builderTab ?? 'sections') === 'cards')
            <div class="px-1 space-y-2">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 block">
                    Card Quick Stats
                </span>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200/80 dark:border-zinc-700/60 space-y-2 text-[11px]">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Total Cards:</span>
                        <span class="font-bold text-slate-900 dark:text-white">{{ count($homepageCards) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Active Displayed:</span>
                        <span class="font-bold text-emerald-600">{{ collect($homepageCards)->where('is_active', true)->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Source:</span>
                        <span class="font-bold text-slate-700 dark:text-zinc-300">homepage_cards</span>
                    </div>
                </div>
            </div>

        @elseif(($builderTab ?? 'sections') === 'general')
            <div class="px-1 space-y-2">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 block">
                    Canonical Source
                </span>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200/80 dark:border-zinc-700/60 space-y-1.5 text-[11px]">
                    <p class="font-semibold text-slate-700 dark:text-zinc-300">Setting Model (MySQL)</p>
                    <p class="text-[10px] text-slate-500 leading-normal">
                        Synchronized across public layout headers, contact components, and global metadata.
                    </p>
                </div>
            </div>

        @elseif(($builderTab ?? 'sections') === 'theme')
            <div class="px-1 space-y-2">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 block">
                    Design Tokens Source
                </span>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200/80 dark:border-zinc-700/60 space-y-1.5 text-[11px]">
                    <p class="font-semibold text-slate-700 dark:text-zinc-300">ThemeSetting Model</p>
                    <p class="text-[10px] text-slate-500 leading-normal">
                        Emits CSS custom properties (<code class="text-[10px] bg-slate-200 dark:bg-zinc-700 px-1 rounded">:root</code>) to all public and admin views.
                    </p>
                </div>
            </div>
        @endif

    </div>

    {{-- FOOTER STATUS --}}
    <div class="p-3 border-t border-slate-200/80 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50 text-[10px] text-slate-400 flex items-center justify-between">
        <span class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Live Sync Active</span>
        </span>
        <span class="font-mono">v2.1</span>
    </div>

</div>
