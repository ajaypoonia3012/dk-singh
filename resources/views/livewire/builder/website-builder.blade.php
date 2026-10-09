<div class="wb-root w-full flex flex-col min-w-0" style="min-height: calc(100vh - 8rem);">

    <style>
        /* Scoped Website Builder Design System & Layout Tokens */
        .wb-device-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
        }
        .wb-device-btn.active {
            background-color: #f59e0b !important;
            color: #0f172a !important;
            border-color: #d97706 !important;
            font-weight: 800 !important;
            box-shadow: 0 1px 3px rgba(245, 158, 11, 0.3) !important;
        }
        .wb-device-btn:not(.active) {
            background-color: transparent;
            color: #64748b;
        }
        .wb-device-btn:not(.active):hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .dark .wb-device-btn:not(.active) {
            color: #a1a1aa;
        }
        .dark .wb-device-btn:not(.active):hover {
            background-color: #27272a;
            color: #ffffff;
        }

        /* 3-Column Responsive Grid Enforcement */
        @media (min-width: 1280px) {
            .wb-workspace-grid {
                display: grid !important;
                grid-template-columns: minmax(240px, 280px) minmax(440px, 520px) minmax(0, 1fr) !important;
                height: calc(100vh - 12rem) !important;
                min-height: 680px !important;
            }
        }
        @media (max-width: 1279px) and (min-width: 1024px) {
            .wb-workspace-grid {
                display: grid !important;
                grid-template-columns: 240px minmax(400px, 480px) minmax(0, 1fr) !important;
                height: calc(100vh - 12rem) !important;
                min-height: 680px !important;
            }
        }
        @media (max-width: 1023px) {
            .wb-workspace-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 1.25rem !important;
            }
            .wb-workspace-grid > div {
                min-height: 500px !important;
            }
        }

        /* Preview Grid Utilities */
        .wb-grid-1 { display: grid !important; grid-template-columns: 1fr !important; }
        .wb-grid-2 { display: grid !important; grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
        .wb-grid-3 { display: grid !important; grid-template-columns: repeat(3, minmax(0, 1fr)) !important; }
        .wb-grid-4 { display: grid !important; grid-template-columns: repeat(4, minmax(0, 1fr)) !important; }
    </style>

    {{-- CLEAN UNIFIED HEADER (Phase 4) --}}
    <header class="wb-header bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200/90 dark:border-white/10 shadow-sm px-5 py-3.5 mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">

        {{-- Left: Page Title & Context --}}
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-2">
                    Website Builder
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                        Live Preview
                    </span>
                </h1>
                <p class="text-xs text-slate-500 dark:text-zinc-400">
                    Customize your homepage layout and design from one place.
                </p>
            </div>
        </div>

        {{-- Right: Viewport Controls & Live Site Link --}}
        <div class="flex items-center gap-3 self-end md:self-auto flex-wrap">

            {{-- Responsive Device Switcher --}}
            <div class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-zinc-800 border border-slate-200/80 dark:border-zinc-700/60 shadow-inner">
                <button
                    wire:click="changeDevice('desktop')"
                    type="button"
                    class="wb-device-btn {{ ($selectedDevice ?? 'desktop') === 'desktop' ? 'active' : '' }}"
                    title="Switch to Desktop canvas"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Desktop</span>
                </button>

                <button
                    wire:click="changeDevice('tablet')"
                    type="button"
                    class="wb-device-btn {{ ($selectedDevice ?? 'desktop') === 'tablet' ? 'active' : '' }}"
                    title="Switch to Tablet canvas (768px)"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>Tablet</span>
                </button>

                <button
                    wire:click="changeDevice('mobile')"
                    type="button"
                    class="wb-device-btn {{ ($selectedDevice ?? 'desktop') === 'mobile' ? 'active' : '' }}"
                    title="Switch to Mobile canvas (390px)"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>Mobile</span>
                </button>
            </div>

            {{-- Open Live Website Link --}}
            <a
                href="/"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold border border-slate-200 dark:border-zinc-700 hover:border-amber-500 text-slate-700 dark:text-zinc-300 hover:text-amber-600 bg-white dark:bg-zinc-800 shadow-xs transition"
                title="Open public website in new tab"
            >
                <span>View Live Site</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>

        </div>

    </header>

    {{-- PROFESSIONAL 3-AREA WORKSPACE (Phase 3) --}}
    <main class="wb-workspace-grid gap-4 flex-1 min-h-0">

        {{-- AREA 1 (LEFT): BUILDER NAVIGATION --}}
        <aside class="min-w-0 h-full overflow-hidden" aria-label="Builder Navigation Area">
            @include('builder.navigation')
        </aside>

        {{-- AREA 2 (CENTER): MAIN BUILDER CONTROL AREA --}}
        <section class="min-w-0 h-full overflow-hidden" aria-label="Builder Controls Area">
            @include('builder.controls')
        </section>

        {{-- AREA 3 (RIGHT): LIVE PREVIEW CANVAS --}}
        <section class="min-w-0 h-full overflow-hidden" aria-label="Live Preview Area">
            @include('builder.canvas')
        </section>

    </main>

    {{-- GLOBAL MEDIA PICKER MODAL --}}
    <x-filament::modal
        id="website-builder-media-picker"
        width="7xl"
        heading="Media Library"
        :close-by-clicking-away="false"
        sticky-header
        x-on:modal-closed="$wire.mediaPickerClosed()"
    >
        <div class="h-[75vh] min-h-0 overflow-hidden">
            <livewire:media.media-library wire:key="website-builder-media-library" />
        </div>
    </x-filament::modal>

</div>
