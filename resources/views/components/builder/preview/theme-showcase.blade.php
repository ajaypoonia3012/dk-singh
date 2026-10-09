@php
    $primaryColor = $theme['primary_color'] ?? '#facc15';
    $secondaryColor = $theme['secondary_color'] ?? '#111111';
    $accentColor = $theme['accent_color'] ?? '#ffffff';
    $headingFont = $theme['heading_font'] ?? 'Poppins';
    $bodyFont = $theme['body_font'] ?? 'Poppins';
    $buttonRadius = $theme['button_radius'] ?? '1rem';
    $cardRadius = $theme['card_radius'] ?? '1.5rem';
    $isDark = !empty($theme['dark_mode_enabled']);
@endphp

<div
    class="p-6 md:p-10 space-y-10 {{ $isDark ? 'bg-zinc-950 text-white' : 'bg-slate-50 text-slate-900' }} min-h-[700px] transition-colors duration-300"
    style="font-family: {{ $bodyFont }}, sans-serif;"
>

    {{-- HEADER BAR BANNER SIMULATION --}}
    <div
        class="p-4 rounded-2xl flex items-center justify-between border shadow-sm"
        style="background-color: {{ $isDark ? '#18181b' : '#ffffff' }}; border-color: {{ $isDark ? '#27272a' : '#e2e8f0' }};"
    >
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm"
                style="background-color: {{ $primaryColor }}; color: #000000;"
            >
                DK
            </div>
            <div>
                <span class="font-bold text-sm tracking-tight" style="font-family: {{ $headingFont }}, sans-serif;">
                    {{ $general['site_name'] ?? 'DK Singh Fitness' }}
                </span>
                <span class="text-[10px] block opacity-60">Design System Live Preview</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span
                class="text-xs px-3 py-1 font-bold inline-block"
                style="border-radius: {{ $buttonRadius }}; background-color: {{ $primaryColor }}; color: #000000;"
            >
                Join Now
            </span>
        </div>
    </div>

    {{-- SECTION 1: TYPOGRAPHY HIERARCHY --}}
    <div
        class="p-6 rounded-2xl border space-y-4"
        style="background-color: {{ $isDark ? '#18181b' : '#ffffff' }}; border-color: {{ $isDark ? '#27272a' : '#e2e8f0' }}; border-radius: {{ $cardRadius }};"
    >
        <div class="flex items-center justify-between border-b pb-2" style="border-color: {{ $isDark ? '#27272a' : '#f1f5f9' }};">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500">Typography Scale</span>
            <span class="text-[11px] font-mono opacity-60">Heading: {{ $headingFont }} | Body: {{ $bodyFont }}</span>
        </div>

        <div class="space-y-3">
            <h1
                class="text-3xl md:text-4xl font-extrabold tracking-tight"
                style="font-family: {{ $headingFont }}, sans-serif;"
            >
                H1: Transform Your Body, Elevate Your Mind
            </h1>

            <h2
                class="text-2xl font-bold tracking-tight"
                style="font-family: {{ $headingFont }}, sans-serif;"
            >
                H2: High-Performance Nutrition & Athletic Training
            </h2>

            <h3
                class="text-lg font-semibold tracking-tight"
                style="font-family: {{ $headingFont }}, sans-serif;"
            >
                H3: Structured Progressive Overload with Weekly Check-ins
            </h3>

            <p class="text-sm opacity-80 leading-relaxed max-w-2xl">
                Body text: Our evidence-based programming blends metabolic conditioning, hypertrophy cycles, and micronutrient balance to create lasting body recomposition without unsustainable restriction.
            </p>
        </div>
    </div>

    {{-- SECTION 2: BUTTONS & INTERACTIVE ELEMENTS --}}
    <div
        class="p-6 rounded-2xl border space-y-4"
        style="background-color: {{ $isDark ? '#18181b' : '#ffffff' }}; border-color: {{ $isDark ? '#27272a' : '#e2e8f0' }}; border-radius: {{ $cardRadius }};"
    >
        <div class="flex items-center justify-between border-b pb-2" style="border-color: {{ $isDark ? '#27272a' : '#f1f5f9' }};">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500">Buttons & Actions</span>
            <span class="text-[11px] font-mono opacity-60">Radius: {{ $buttonRadius }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Primary Button --}}
            <button
                type="button"
                class="px-5 py-2.5 font-bold text-xs tracking-wide shadow-md transition hover:scale-105"
                style="border-radius: {{ $buttonRadius }}; background-color: {{ $primaryColor }}; color: #000000;"
            >
                Primary Button
            </button>

            {{-- Secondary Button --}}
            <button
                type="button"
                class="px-5 py-2.5 font-bold text-xs tracking-wide transition hover:opacity-90"
                style="border-radius: {{ $buttonRadius }}; background-color: {{ $secondaryColor }}; color: #ffffff;"
            >
                Secondary Button
            </button>

            {{-- Outline Button --}}
            <button
                type="button"
                class="px-5 py-2.5 font-bold text-xs tracking-wide border-2 transition"
                style="border-radius: {{ $buttonRadius }}; border-color: {{ $primaryColor }}; color: {{ $isDark ? '#ffffff' : $secondaryColor }};"
            >
                Outline Button
            </button>

            {{-- Ghost Button --}}
            <button
                type="button"
                class="px-4 py-2 font-semibold text-xs tracking-wide opacity-80 hover:opacity-100 transition"
            >
                Ghost Action →
            </button>
        </div>
    </div>

    {{-- SECTION 3: CARDS & CONTENT CONTAINERS --}}
    <div
        class="p-6 rounded-2xl border space-y-4"
        style="background-color: {{ $isDark ? '#18181b' : '#ffffff' }}; border-color: {{ $isDark ? '#27272a' : '#e2e8f0' }}; border-radius: {{ $cardRadius }};"
    >
        <div class="flex items-center justify-between border-b pb-2" style="border-color: {{ $isDark ? '#27272a' : '#f1f5f9' }};">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500">Card System Showcase</span>
            <span class="text-[11px] font-mono opacity-60">Card Radius: {{ $cardRadius }}</span>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            {{-- Feature Card 1 --}}
            <div
                class="p-5 border flex flex-col justify-between shadow-xs transition hover:shadow-md"
                style="background-color: {{ $isDark ? '#27272a' : '#f8fafc' }}; border-color: {{ $isDark ? '#3f3f46' : '#e2e8f0' }}; border-radius: {{ $cardRadius }};"
            >
                <div>
                    <div class="text-2xl mb-2">⚡</div>
                    <h4 class="font-bold text-sm mb-1" style="font-family: {{ $headingFont }}, sans-serif;">
                        Elite 1-on-1 Coaching
                    </h4>
                    <p class="text-xs opacity-75 leading-relaxed">
                        Customized workout splits and bi-weekly metabolic adaptations.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-zinc-700/60 flex items-center justify-between">
                    <span class="text-xs font-bold" style="color: {{ $primaryColor }};">₹14,999/mo</span>
                    <span class="text-[10px] opacity-60">Popular</span>
                </div>
            </div>

            {{-- Feature Card 2 --}}
            <div
                class="p-5 border flex flex-col justify-between shadow-xs transition hover:shadow-md"
                style="background-color: {{ $isDark ? '#27272a' : '#f8fafc' }}; border-color: {{ $isDark ? '#3f3f46' : '#e2e8f0' }}; border-radius: {{ $cardRadius }};"
            >
                <div>
                    <div class="text-2xl mb-2">🥗</div>
                    <h4 class="font-bold text-sm mb-1" style="font-family: {{ $headingFont }}, sans-serif;">
                        Custom Macro Nutrition
                    </h4>
                    <p class="text-xs opacity-75 leading-relaxed">
                        Indian diet friendly with vegetarian, eggitarian, and non-veg options.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-zinc-700/60 flex items-center justify-between">
                    <span class="text-xs font-bold" style="color: {{ $primaryColor }};">Included</span>
                    <span class="text-[10px] opacity-60">Flexible</span>
                </div>
            </div>

            {{-- Feature Card 3 (Hero Accent) --}}
            <div
                class="p-5 border flex flex-col justify-between shadow-md"
                style="background-color: {{ $secondaryColor }}; color: #ffffff; border-color: {{ $primaryColor }}; border-radius: {{ $cardRadius }};"
            >
                <div>
                    <div class="text-2xl mb-2">🏆</div>
                    <h4 class="font-bold text-sm mb-1 text-white" style="font-family: {{ $headingFont }}, sans-serif;">
                        15,000+ Verified Clients
                    </h4>
                    <p class="text-xs opacity-80 leading-relaxed text-zinc-300">
                        Over 15 years transforming bodies across 24 countries worldwide.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-zinc-700 flex items-center justify-between">
                    <span class="text-xs font-extrabold" style="color: {{ $primaryColor }};">Verified</span>
                    <span class="text-[10px] text-zinc-400">99.4% Success</span>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: BADGES & FORM INPUTS --}}
    <div
        class="p-6 rounded-2xl border space-y-4"
        style="background-color: {{ $isDark ? '#18181b' : '#ffffff' }}; border-color: {{ $isDark ? '#27272a' : '#e2e8f0' }}; border-radius: {{ $cardRadius }};"
    >
        <div class="flex items-center justify-between border-b pb-2" style="border-color: {{ $isDark ? '#27272a' : '#f1f5f9' }};">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500">Badges & Form Controls</span>
        </div>

        <div class="flex flex-wrap gap-2 items-center">
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                ✓ Success Active
            </span>
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                ⚡ Premium Tier
            </span>
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800">
                ℹ️ Verified Coach
            </span>
            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800">
                ⭐ 5.0 Rating
            </span>
        </div>

        <div class="grid md:grid-cols-2 gap-4 pt-2">
            <div>
                <label class="block text-xs font-semibold mb-1 opacity-80">Form Field Example</label>
                <input
                    type="text"
                    disabled
                    value="user@example.com"
                    class="w-full text-xs p-2.5 border rounded-lg opacity-90 cursor-not-allowed"
                    style="background-color: {{ $isDark ? '#27272a' : '#f8fafc' }}; border-color: {{ $isDark ? '#3f3f46' : '#cbd5e1' }}; border-radius: {{ $buttonRadius }};"
                >
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 opacity-80">Select Dropdown Example</label>
                <select
                    disabled
                    class="w-full text-xs p-2.5 border rounded-lg opacity-90 cursor-not-allowed"
                    style="background-color: {{ $isDark ? '#27272a' : '#f8fafc' }}; border-color: {{ $isDark ? '#3f3f46' : '#cbd5e1' }}; border-radius: {{ $buttonRadius }};"
                >
                    <option>12-Week Transformation Plan</option>
                </select>
            </div>
        </div>
    </div>

</div>
