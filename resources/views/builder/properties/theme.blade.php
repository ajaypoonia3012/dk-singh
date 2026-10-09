<div class="space-y-6">

    <div class="border-b border-slate-200 dark:border-zinc-800 pb-3">
        <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
            <span>🎨</span> Theme & Design System
        </h3>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
            Canonical source of truth for global colors, typography, buttons, cards, and design tokens.
        </p>
    </div>

    {{-- THEME PRESETS / TEMPLATES --}}
    <div class="bg-amber-500/10 dark:bg-amber-500/5 p-4 rounded-xl border border-amber-500/30 space-y-3">
        <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-amber-900 dark:text-amber-300 uppercase tracking-wider flex items-center gap-1.5">
                <span>⭐</span> Theme Presets
            </h4>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-200 dark:bg-amber-900/60 text-amber-800 dark:text-amber-200">
                1-Click Setup
            </span>
        </div>

        <p class="text-xs text-slate-600 dark:text-zinc-300">
            Apply a professionally curated design preset. You can still customize any color or token below.
        </p>

        <div>
            <select
                wire:model="selectedThemePreset"
                class="w-full text-xs font-semibold rounded-lg border border-amber-300 dark:border-amber-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
            >
                @foreach($themePresets as $presetKey => $preset)
                    <option value="{{ $presetKey }}">
                        {{ $preset['name'] }} ({{ $preset['badge'] }})
                    </option>
                @endforeach
            </select>
        </div>

        <button
            type="button"
            wire:click="applyThemePreset"
            class="w-full py-2 px-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-black font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-1.5"
        >
            <span>✨</span>
            <span>Apply Selected Preset</span>
        </button>
    </div>

    {{-- BRAND COLORS --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-4">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>🖌️</span> Brand Colors
        </h4>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Primary Brand Color
            </label>
            <div class="flex items-center gap-2">
                <input
                    type="color"
                    wire:model.live="theme.primary_color"
                    class="w-10 h-10 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white"
                >
                <input
                    type="text"
                    wire:model.live="theme.primary_color"
                    class="flex-1 text-xs font-mono rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
                    placeholder="#facc15"
                >
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Secondary Brand Color
            </label>
            <div class="flex items-center gap-2">
                <input
                    type="color"
                    wire:model.live="theme.secondary_color"
                    class="w-10 h-10 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white"
                >
                <input
                    type="text"
                    wire:model.live="theme.secondary_color"
                    class="flex-1 text-xs font-mono rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
                    placeholder="#111111"
                >
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Accent / Surface Color
            </label>
            <div class="flex items-center gap-2">
                <input
                    type="color"
                    wire:model.live="theme.accent_color"
                    class="w-10 h-10 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white"
                >
                <input
                    type="text"
                    wire:model.live="theme.accent_color"
                    class="flex-1 text-xs font-mono rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
                    placeholder="#ffffff"
                >
            </div>
        </div>
    </div>

    {{-- TYPOGRAPHY --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-4">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>✍️</span> Typography
        </h4>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Heading Font Family
            </label>
            <select
                wire:model.live="theme.heading_font"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
            >
                <option value="Poppins">Poppins (Athletic / Premium)</option>
                <option value="Inter">Inter (Clean / Editorial)</option>
                <option value="Montserrat">Montserrat (Geometric / Bold)</option>
                <option value="Oswald">Oswald (Condensed / High Energy)</option>
                <option value="Plus Jakarta Sans">Plus Jakarta Sans (Modern Modernist)</option>
                <option value="system-ui">System UI (Native)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Body Font Family
            </label>
            <select
                wire:model.live="theme.body_font"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
            >
                <option value="Poppins">Poppins</option>
                <option value="Inter">Inter</option>
                <option value="Roboto">Roboto</option>
                <option value="Outfit">Outfit</option>
                <option value="system-ui">System UI</option>
            </select>
        </div>
    </div>

    {{-- BUTTONS & CARDS GEOMETRY --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-4">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>🔘</span> Components & Geometry
        </h4>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Button Corner Radius
            </label>
            <select
                wire:model.live="theme.button_radius"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
            >
                <option value="0rem">Square (0px)</option>
                <option value="0.375rem">Small (6px)</option>
                <option value="0.5rem">Medium (8px)</option>
                <option value="0.75rem">Large (12px)</option>
                <option value="1rem">Extra Large (16px)</option>
                <option value="9999px">Full Pill (Rounded)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Card Corner Radius
            </label>
            <select
                wire:model.live="theme.card_radius"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
            >
                <option value="0rem">Square (0px)</option>
                <option value="0.5rem">Small (8px)</option>
                <option value="0.75rem">Medium (12px)</option>
                <option value="1rem">Large (16px)</option>
                <option value="1.5rem">Extra Large (24px)</option>
                <option value="2rem">Super Large (32px)</option>
            </select>
        </div>
    </div>

    {{-- LAYOUT & DARK MODE --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-4">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>📐</span> Layout & Dark Mode
        </h4>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Max Container Width
                </label>
                <input
                    type="number"
                    wire:model="theme.container_width"
                    class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
                    placeholder="1280"
                >
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Section Padding (px)
                </label>
                <input
                    type="number"
                    wire:model="theme.section_padding"
                    class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white"
                    placeholder="96"
                >
            </div>
        </div>

        <div class="flex items-center justify-between pt-2">
            <div>
                <span class="text-xs font-bold text-slate-900 dark:text-white block">Dark Mode</span>
                <span class="text-[11px] text-slate-500">Enable high-contrast dark aesthetic</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input
                    type="checkbox"
                    wire:model="theme.dark_mode_enabled"
                    class="sr-only peer"
                >
                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
            </label>
        </div>
    </div>

    {{-- SAVE BUTTON --}}
    <button
        wire:click="saveThemeSettings"
        type="button"
        class="wb-btn-primary flex items-center justify-center gap-2"
    >
        <span>💾</span>
        <span>Save Design System & Theme</span>
    </button>

</div>
