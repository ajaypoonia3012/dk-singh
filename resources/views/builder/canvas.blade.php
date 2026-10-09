<div class="wb-preview bg-slate-100 dark:bg-zinc-950 rounded-2xl border border-slate-200/90 dark:border-white/10 shadow-sm h-full flex flex-col overflow-hidden min-w-0">

    {{-- PREVIEW TOOLBAR / BROWSER FRAME HEADER --}}
    <div class="px-4 py-2.5 bg-white dark:bg-zinc-900 border-b border-slate-200/80 dark:border-zinc-800 flex items-center justify-between gap-3 flex-shrink-0">
        {{-- Window Dots --}}
        <div class="flex items-center gap-1.5 flex-shrink-0">
            <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span>
            <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span>
            <span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>
        </div>

        {{-- Simulated Browser URL Pill --}}
        <div class="flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 text-xs font-mono text-slate-600 dark:text-zinc-300 shadow-inner max-w-md w-full justify-center truncate">
            <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="truncate">https://dksinghfitness.com/</span>
            <span class="text-amber-600 dark:text-amber-400 font-semibold text-[11px] flex-shrink-0">#{{ $builderTab ?? 'preview' }}</span>
        </div>

        {{-- Active Viewport Scale Label --}}
        <div class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-wider flex-shrink-0">
            @if(($selectedDevice ?? 'desktop') === 'mobile')
                Mobile (390px)
            @elseif(($selectedDevice ?? 'desktop') === 'tablet')
                Tablet (768px)
            @else
                Desktop (100%)
            @endif
        </div>
    </div>

    {{-- PREVIEW CANVAS VIEWPORT --}}
    <div
        class="flex-1 overflow-y-auto p-4 lg:p-6 flex justify-center items-start min-w-0"
        style="background-color: #f8fafc; background-image: radial-gradient(#cbd5e1 1.2px, transparent 1.2px); background-size: 16px 16px;"
    >

        @if(($selectedDevice ?? 'desktop') === 'mobile')
            {{-- MOBILE DEVICE FRAME (390px iPhone Style) --}}
            <div class="w-[390px] max-w-full my-auto transition-all duration-300 shadow-2xl rounded-[44px] border-[10px] border-slate-950 bg-black overflow-hidden ring-1 ring-slate-800 flex-shrink-0">
                {{-- Bezel / Dynamic Island --}}
                <div class="w-full bg-slate-950 pt-2 pb-1 flex justify-center items-center">
                    <div class="w-24 h-5 bg-black rounded-full flex items-center justify-between px-2">
                        <span class="w-2 h-2 rounded-full bg-blue-900/60"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-900 border border-slate-800"></span>
                    </div>
                </div>

                {{-- Screen --}}
                <div class="bg-white dark:bg-black overflow-y-auto max-h-[720px] min-h-[480px]">
                    @include('builder.canvas-content')
                </div>

                {{-- Home Indicator --}}
                <div class="w-full bg-slate-950 py-1.5 flex justify-center items-center">
                    <div class="w-28 h-1 bg-slate-700 rounded-full"></div>
                </div>
            </div>

        @elseif(($selectedDevice ?? 'desktop') === 'tablet')
            {{-- TABLET DEVICE FRAME (768px iPad Style) --}}
            <div class="w-[768px] max-w-full my-auto transition-all duration-300 shadow-2xl rounded-[32px] border-[10px] border-slate-900 bg-black overflow-hidden ring-1 ring-slate-800 flex-shrink-0">
                {{-- Top Bezel with Camera --}}
                <div class="w-full bg-slate-900 py-1.5 flex justify-center items-center">
                    <div class="w-2 h-2 bg-slate-800 rounded-full"></div>
                </div>

                {{-- Screen --}}
                <div class="bg-white dark:bg-black overflow-y-auto max-h-[760px] min-h-[520px]">
                    @include('builder.canvas-content')
                </div>

                {{-- Bottom Bezel --}}
                <div class="w-full bg-slate-900 py-2"></div>
            </div>

        @else
            {{-- DESKTOP FULL-WIDTH BROWSER FRAME (100% Canvas) --}}
            <div class="w-full transition-all duration-300 shadow-xl rounded-xl border border-slate-200/90 dark:border-white/10 bg-white dark:bg-black overflow-hidden min-w-0">
                <div class="bg-white dark:bg-black overflow-y-auto max-h-[780px] min-w-0">
                    @include('builder.canvas-content')
                </div>
            </div>
        @endif

    </div>

</div>