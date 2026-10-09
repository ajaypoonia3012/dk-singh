<div class="space-y-6">

    <div class="border-b border-slate-200 dark:border-zinc-800 pb-3">
        <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
            <span>🌐</span> General Website & Branding
        </h3>
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
            Canonical source of truth for site identity, contact, social channels, and search metadata.
        </p>
    </div>

    {{-- BRAND IDENTITY --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-4">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>🏷️</span> Brand Identity
        </h4>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Site Name <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                wire:model="general.site_name"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
                placeholder="DK Singh Fitness & Nutrition"
            >
            @error('general.site_name') <span class="text-[11px] text-red-500 font-semibold">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Site Tagline
            </label>
            <input
                type="text"
                wire:model="general.site_tagline"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
                placeholder="Transform Your Body, Transform Your Life"
            >
            @error('general.site_tagline') <span class="text-[11px] text-red-500 font-semibold">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Business Niche / Category
            </label>
            <input
                type="text"
                wire:model="general.business_niche"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
                placeholder="Elite Fitness & Nutrition Coaching"
            >
        </div>
    </div>

    {{-- BRAND ASSETS --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-4">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>🖼️</span> Brand Logos & Favicon
        </h4>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Primary Logo
            </label>
            @if(!empty($general['logo']))
                <div class="mb-2 p-2 bg-slate-900 rounded-lg inline-block">
                    <img src="{{ asset('storage/'.$general['logo']) }}" alt="Site Logo" class="h-8 max-w-[160px] object-contain">
                </div>
            @endif
            <input
                type="file"
                wire:model="logoImage"
                accept="image/*"
                class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-500 file:text-black hover:file:bg-amber-600 cursor-pointer"
            >
            <p class="text-[10px] text-slate-500 mt-1">Recommended: PNG/WebP with transparent background.</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Favicon
            </label>
            @if(!empty($general['favicon']))
                <div class="mb-2 p-1.5 bg-slate-100 rounded inline-block">
                    <img src="{{ asset('storage/'.$general['favicon']) }}" alt="Favicon" class="w-6 h-6 object-contain">
                </div>
            @endif
            <input
                type="file"
                wire:model="faviconImage"
                accept="image/x-icon,image/png,image/webp"
                class="w-full text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300 cursor-pointer"
            >
            <p class="text-[10px] text-slate-500 mt-1">Recommended: 32×32 or 64×64 PNG/ICO.</p>
        </div>
    </div>

    {{-- CONTACT DETAILS --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-4">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>📞</span> Contact Channels
        </h4>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Contact Email
            </label>
            <input
                type="email"
                wire:model="general.email"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
                placeholder="contact@dksinghfitness.com"
            >
            @error('general.email') <span class="text-[11px] text-red-500 font-semibold">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Phone
                </label>
                <input
                    type="text"
                    wire:model="general.phone"
                    class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
                    placeholder="+91 98765 43210"
                >
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    WhatsApp
                </label>
                <input
                    type="text"
                    wire:model="general.whatsapp"
                    class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
                    placeholder="+91 98765 43210"
                >
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                Physical / Studio Address
            </label>
            <textarea
                wire:model="general.address"
                rows="2"
                class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500"
                placeholder="New Delhi, India"
            ></textarea>
        </div>
    </div>

    {{-- SOCIAL CHANNELS --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-3">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>🔗</span> Social Channels
        </h4>

        <div>
            <label class="block text-[11px] font-semibold text-slate-600 dark:text-zinc-400 mb-1">Instagram URL</label>
            <input type="url" wire:model="general.instagram" class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 text-slate-900 dark:text-white" placeholder="https://instagram.com/dksingh">
        </div>

        <div>
            <label class="block text-[11px] font-semibold text-slate-600 dark:text-zinc-400 mb-1">YouTube URL</label>
            <input type="url" wire:model="general.youtube" class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 text-slate-900 dark:text-white" placeholder="https://youtube.com/@dksingh">
        </div>

        <div>
            <label class="block text-[11px] font-semibold text-slate-600 dark:text-zinc-400 mb-1">Facebook URL</label>
            <input type="url" wire:model="general.facebook" class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 text-slate-900 dark:text-white" placeholder="https://facebook.com/dksingh">
        </div>

        <div>
            <label class="block text-[11px] font-semibold text-slate-600 dark:text-zinc-400 mb-1">Twitter / X URL</label>
            <input type="url" wire:model="general.twitter" class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2 text-slate-900 dark:text-white" placeholder="https://x.com/dksingh">
        </div>
    </div>

    {{-- SEO DEFAULTS --}}
    <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-slate-200/80 dark:border-zinc-700/60 space-y-3">
        <h4 class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
            <span>🔍</span> Global SEO Defaults
        </h4>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Default Meta Title</label>
            <input type="text" wire:model="general.meta_title" class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white" placeholder="DK Singh Fitness | Personal Training & Nutrition">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Default Meta Description</label>
            <textarea wire:model="general.meta_description" rows="3" class="w-full text-xs rounded-lg border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-2.5 text-slate-900 dark:text-white" placeholder="Join India's premier fitness coaching system..."></textarea>
        </div>
    </div>

    {{-- SAVE BUTTON --}}
    <button
        wire:click="saveGeneral"
        type="button"
        class="wb-btn-primary flex items-center justify-center gap-2"
    >
        <span>💾</span>
        <span>Save General Website Settings</span>
    </button>

</div>
