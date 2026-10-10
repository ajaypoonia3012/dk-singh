@props(['setting' => null])

@php
    $setting = $setting ?? (isset($__data['setting']) ? $__data['setting'] : null);
    $enabled = (bool) ($setting?->powder_promo_enabled ?? false);

    if (! $enabled) {
        return;
    }

    $placement = $setting?->powder_promo_placement ?? 'all_plus_product_card';

    // Show if placement includes product card / products page
    if ($placement === 'all') {
        // If explicitly set to only top banner without product card
        return;
    }

    $badge = $setting?->powder_promo_badge ?: 'HERBAL & WELLNESS POWDERS';
    $discountText = $setting?->powder_promo_discount_text ?: 'FEATURED RANGE';
    $promoCode = $setting?->powder_promo_code ?: '';
    $text = $setting?->powder_promo_text ?: 'Explore our authentic herbal & wellness powder collection designed to support your fitness journey.';
    $btnText = $setting?->powder_promo_button_text ?: 'Explore Powders';
@endphp

<div class="mb-14 relative overflow-hidden rounded-3xl bg-gradient-to-br from-zinc-900 via-neutral-900 to-zinc-950 border border-amber-500/30 text-white shadow-2xl p-6 sm:p-10">
    <!-- Decorative background glow and watermark -->
    <div class="absolute -top-24 -right-24 w-80 h-80 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-yellow-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

        <!-- Left info -->
        <div class="max-w-2xl">
            <div class="flex flex-wrap items-center gap-2.5 mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    {{ $badge }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-yellow-400 text-zinc-950">
                    {{ $discountText }}
                </span>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white mb-4">
                Boost Your Transformation With <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500">Pure Herbal Powders</span>
            </h2>

            <p class="text-base sm:text-lg text-zinc-300 leading-relaxed mb-6">
                {{ $text }}
            </p>

            <!-- Trust features -->
            <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-zinc-400">
                <span class="flex items-center gap-1.5 text-zinc-300">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Ayurvedic Formulations
                </span>
                <span class="flex items-center gap-1.5 text-zinc-300">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Product Range
                </span>
                <span class="flex items-center gap-1.5 text-zinc-300">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Fast Doorstep Delivery
                </span>
            </div>
        </div>

        <!-- Right Coupon Voucher Box -->
        <div class="lg:w-80 flex-shrink-0 bg-zinc-950/80 backdrop-blur-md rounded-2xl border border-amber-500/40 p-6 flex flex-col items-center text-center shadow-xl">
            <span class="text-[11px] font-bold uppercase tracking-widest text-amber-400/90 mb-1">
                Campaign Reference
            </span>

            @if(filled($promoCode))
                <div class="w-full my-3 py-3 px-4 rounded-xl bg-zinc-900 border-2 border-dashed border-amber-500/50 flex items-center justify-between gap-3">
                    <span class="font-mono text-xl font-black tracking-widest text-amber-300" id="card-code-display">
                        {{ $promoCode }}
                    </span>
                    <button
                        type="button"
                        id="card-copy-btn"
                        data-code="{{ $promoCode }}"
                        class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-zinc-950 font-bold text-xs transition duration-200 cursor-pointer shadow-xs active:scale-95"
                    >
                        Copy
                    </button>
                </div>
            @endif

            <p class="text-xs text-zinc-400 mb-4">
                Explore our curated herbal powder collection below or quote this campaign with your wellness advisor.
            </p>

            <a
                href="#products-catalog"
                class="w-full py-3 px-4 rounded-xl font-black text-sm bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 text-zinc-950 hover:brightness-110 transition duration-200 shadow-md shadow-amber-500/20 text-center"
            >
                {{ $btnText }} ↓
            </a>
        </div>

    </div>
</div>

<script>
(function() {
    var copyBtn = document.getElementById('card-copy-btn');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', function() {
        var code = copyBtn.getAttribute('data-code');
        if (!code) return;

        var onDone = function() {
            copyBtn.textContent = 'Copied! ✓';
            copyBtn.classList.remove('bg-amber-500', 'hover:bg-amber-400', 'text-zinc-950');
            copyBtn.classList.add('bg-emerald-500', 'text-white');
            setTimeout(function() {
                copyBtn.textContent = 'Copy';
                copyBtn.classList.add('bg-amber-500', 'hover:bg-amber-400', 'text-zinc-950');
                copyBtn.classList.remove('bg-emerald-500', 'text-white');
            }, 2000);
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(code).then(onDone).catch(function() {
                fallbackCopy(code, onDone);
            });
        } else {
            fallbackCopy(code, onDone);
        }
    });

    function fallbackCopy(text, cb) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            if (cb) cb();
        } catch (err) {}
        document.body.removeChild(textArea);
    }
})();
</script>
