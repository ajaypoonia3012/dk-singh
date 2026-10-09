<section class="relative bg-zinc-950 text-white rounded-3xl overflow-hidden shadow-2xl" style="background-color: #0b0b0e;">

    @if(!($hero['enabled'] ?? true))
        <div class="absolute top-4 right-4 z-30 rounded-full bg-red-600 px-3 py-1 text-xs font-bold text-white shadow-md">
            Disabled
        </div>
    @endif

    {{-- Atmospheric Background Overlay --}}
    <div
        class="absolute inset-0 pointer-events-none z-0"
        style="background-color: {{ $hero['overlay_color'] ?? '#000000' }}; opacity: {{ ((int) ($hero['overlay_opacity'] ?? 0)) / 100 }};">
    </div>

    {{-- Subtle Gold Accent Glow --}}
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    @php
        $isMobile = ($selectedDevice ?? 'desktop') === 'mobile';
        $isTablet = ($selectedDevice ?? 'desktop') === 'tablet';
    @endphp

    <div @class([
        'relative z-10 items-center',
        'wb-grid-1 gap-6 p-6' => $isMobile,
        'wb-grid-2 gap-8 p-8' => $isTablet,
        'wb-grid-2 gap-10 p-10' => !$isMobile && !$isTablet,
    ])>

        {{-- LEFT CONTENT --}}
        <div class="space-y-6">

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                <p class="text-xs font-bold uppercase tracking-[3px] text-amber-400">
                    {{ $setting->site_name ?? 'DK Singh Fitness' }}
                </p>
            </div>

            <h1 @class([
                'font-black leading-tight text-white tracking-tight',
                'text-3xl' => $isMobile,
                'text-4xl' => $isTablet,
                'text-5xl lg:text-6xl' => !$isMobile && !$isTablet,
            ])>
                {!! nl2br(e($hero['heading'] ?? $setting->hero_title ?? 'Transform Your Body, Elevate Your Mind')) !!}
            </h1>

            <p @class([
                'leading-relaxed text-zinc-300 font-normal',
                'text-sm' => $isMobile,
                'text-base' => $isTablet,
                'text-lg' => !$isMobile && !$isTablet,
            ])>
                {{ $hero['subheading'] ?? $setting->hero_subtitle ?? 'Elite 1-on-1 coaching, science-backed workout plans, and customized nutrition strategies crafted by DK Singh.' }}
            </p>

            {{-- Buttons --}}
            <div class="flex flex-wrap gap-3 pt-2">
                <a
                    href="{{ $hero['button_link'] ?? $setting->cta_button_link ?? '#' }}"
                    class="px-6 py-3 rounded-xl font-extrabold text-sm transition shadow-lg inline-flex items-center justify-center gap-2"
                    style="background-color: #f59e0b; color: #000000; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);">
                    {{ $hero['button_text'] ?? $setting->cta_button_text ?? 'Start Your Journey' }}
                    <span>→</span>
                </a>

                <a
                    href="{{ $hero['view_button_link'] ?? '#' }}"
                    class="px-6 py-3 rounded-xl border border-zinc-700 hover:border-amber-500/60 text-white font-bold text-sm transition bg-white/5 hover:bg-white/10 backdrop-blur-sm inline-flex items-center justify-center">
                    {{ $hero['view_button_text'] ?? $setting->view_transformations_text ?? 'View Transformations' }}
                </a>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 pt-6 border-t border-zinc-800/80">
                <div>
                    <h3 class="text-2xl lg:text-3xl font-black text-amber-400">
                        {{ $setting->instagram_followers ?? '3M+' }}
                    </h3>
                    <p class="text-xs text-zinc-400 mt-1 uppercase tracking-wider font-medium">
                        {{ $hero['followers_label'] ?? 'Members' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-2xl lg:text-3xl font-black text-amber-400">
                        {{ $setting->years_experience ?? '15+' }}
                    </h3>
                    <p class="text-xs text-zinc-400 mt-1 uppercase tracking-wider font-medium">
                        {{ $hero['years_label'] ?? 'Years Exp' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-2xl lg:text-3xl font-black text-amber-400">
                        {{ $setting->transformations ?? '5000+' }}
                    </h3>
                    <p class="text-xs text-zinc-400 mt-1 uppercase tracking-wider font-medium">
                        {{ $hero['transformations_label'] ?? 'Success Stories' }}
                    </p>
                </div>
            </div>

        </div>

        {{-- RIGHT IMAGE --}}
        <div class="relative flex justify-center">
            <div class="absolute inset-0 bg-amber-500/10 blur-2xl rounded-full pointer-events-none"></div>

            <div class="relative z-10 w-full rounded-2xl overflow-hidden border border-zinc-800/60 shadow-2xl bg-zinc-900">
                <img
                    src="{{ $heroBackgroundMediaId && ($selected = collect($heroBackgroundOptions)->firstWhere('id', $heroBackgroundMediaId)) ? asset('storage/'.$selected->path) : asset('images/dk-hero.jpeg') }}"
                    alt="{{ $setting->site_name ?? 'DK Singh Fitness' }}"
                    @class([
                        'w-full object-cover',
                        'h-72' => $isMobile,
                        'h-96' => $isTablet,
                        'h-[460px] lg:h-[500px]' => !$isMobile && !$isTablet,
                    ])>

                <div class="absolute bottom-4 left-4 right-4 p-3 rounded-xl bg-black/60 backdrop-blur-md border border-white/10 flex items-center justify-between">
                    <div>
                        <div class="text-[11px] font-bold text-amber-400 uppercase tracking-wider">Coach DK Singh</div>
                        <div class="text-xs font-semibold text-white">Master Fitness & Nutritionist</div>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                </div>
            </div>
        </div>

    </div>

</section>
