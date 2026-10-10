@props(['setting' => null])

@php
    $setting = $setting ?? (isset($__data['setting']) ? $__data['setting'] : null);
    $enabled = (bool) ($setting?->powder_promo_enabled ?? false);

    if (! $enabled) {
        return;
    }

    $placement = $setting?->powder_promo_placement ?? 'all_plus_product_card';
    $isProductsPage = request()->is('products*');
    $isHomePage = request()->is('/');

    // Check display placement visibility
    $shouldDisplay = match ($placement) {
        'products' => $isProductsPage,
        'home_and_products' => $isHomePage || $isProductsPage,
        default => true, // 'all' or 'all_plus_product_card'
    };

    if (! $shouldDisplay) {
        return;
    }

    $badge = $setting?->powder_promo_badge ?: 'FEATURED COLLECTION';
    $discountText = $setting?->powder_promo_discount_text ?: 'HERBAL & WELLNESS';
    $promoCode = $setting?->powder_promo_code ?: '';
    $text = $setting?->powder_promo_text ?: 'Explore our authentic herbal & wellness powder collection.';
    $btnText = $setting?->powder_promo_button_text ?: 'Explore Powders';
    $btnLink = $setting?->powder_promo_button_link ?: '/products';
    $themeStyle = $setting?->powder_promo_theme ?: 'amber-gold';
    $dismissible = (bool) ($setting?->powder_promo_dismissible ?? true);

    $themeClasses = match ($themeStyle) {
        'emerald-wellness' => [
            'bar' => 'bg-gradient-to-r from-emerald-900 via-teal-800 to-emerald-900 text-white border-b border-emerald-500/30',
            'badge' => 'bg-emerald-950/90 text-emerald-300 border-emerald-400/40',
            'discount' => 'text-emerald-200',
            'chip' => 'bg-black/30 hover:bg-black/50 text-emerald-200 border-emerald-400/50',
            'btn' => 'bg-emerald-400 text-zinc-950 hover:bg-emerald-300 shadow-emerald-950/50',
            'close' => 'text-emerald-200/80 hover:text-white hover:bg-emerald-800/60',
        ],
        'crimson-energy' => [
            'bar' => 'bg-gradient-to-r from-red-800 via-rose-700 to-red-900 text-white border-b border-rose-500/30',
            'badge' => 'bg-black/70 text-rose-300 border-rose-400/40',
            'discount' => 'text-yellow-300',
            'chip' => 'bg-black/40 hover:bg-black/60 text-white border-rose-300/40',
            'btn' => 'bg-white text-rose-900 hover:bg-rose-100 shadow-rose-950/50',
            'close' => 'text-rose-200/80 hover:text-white hover:bg-rose-800/60',
        ],
        'midnight-luxury' => [
            'bar' => 'bg-gradient-to-r from-zinc-950 via-stone-900 to-zinc-950 text-zinc-100 border-b border-amber-500/40',
            'badge' => 'bg-amber-500/20 text-amber-300 border-amber-400/40',
            'discount' => 'text-amber-400',
            'chip' => 'bg-zinc-800/90 hover:bg-zinc-800 text-amber-300 border-amber-400/40',
            'btn' => 'bg-gradient-to-r from-amber-400 to-yellow-500 text-zinc-950 hover:from-amber-300 hover:to-yellow-400 shadow-amber-950/50',
            'close' => 'text-zinc-400 hover:text-white hover:bg-zinc-800',
        ],
        default => [ // amber-gold (signature brand)
            'bar' => 'bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-500 text-zinc-950 border-b border-amber-400 shadow-sm',
            'badge' => 'bg-zinc-950 text-amber-300 border-zinc-900',
            'discount' => 'text-zinc-950 font-black',
            'chip' => 'bg-zinc-950/90 hover:bg-zinc-950 text-amber-300 border-zinc-900',
            'btn' => 'bg-zinc-950 text-white hover:bg-black hover:text-amber-300 shadow-zinc-950/20',
            'close' => 'text-zinc-900/80 hover:text-black hover:bg-amber-600/20',
        ],
    };
@endphp

<aside
    id="dk-powder-promo-banner"
    class="relative z-40 transition-all duration-300 {{ $themeClasses['bar'] }}"
    role="region"
    aria-label="Product Powder Promotional Showcase"
    style="display: none;"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 sm:py-2">
        <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2 text-xs sm:text-sm">

            <!-- Left: Badge + Offer Headline -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 flex-1 min-w-[280px]">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider border shadow-xs {{ $themeClasses['badge'] }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current animate-ping"></span>
                    <span>⚡ {{ $badge }}</span>
                </span>

                @if(filled($discountText))
                    <span class="font-black tracking-tight uppercase px-2 py-0.5 rounded-md bg-black/10 {{ $themeClasses['discount'] }}">
                        {{ $discountText }}
                    </span>
                @endif

                <p class="font-medium leading-snug line-clamp-1 sm:line-clamp-none">
                    {{ $text }}
                </p>
            </div>

            <!-- Right: Campaign Reference Chip + CTA Button + Optional Dismiss -->
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0 ml-auto">

                @if(filled($promoCode))
                    <button
                        type="button"
                        id="dk-powder-code-btn"
                        data-code="{{ $promoCode }}"
                        title="Click to copy campaign reference"
                        class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-mono font-bold border transition duration-200 cursor-pointer shadow-xs {{ $themeClasses['chip'] }}"
                    >
                        <span class="text-[10px] uppercase font-sans font-semibold opacity-75">Ref:</span>
                        <span id="dk-powder-code-label" class="tracking-wider">{{ $promoCode }}</span>
                        <svg id="dk-copy-icon" class="w-3.5 h-3.5 opacity-75 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <svg id="dk-check-icon" class="w-3.5 h-3.5 hidden text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                @endif

                @if(filled($btnText))
                    <a
                        href="{{ $btnLink }}"
                        class="inline-flex items-center gap-1 px-3.5 py-1 rounded-md text-xs font-bold transition duration-200 shadow-sm hover:scale-[1.02] active:scale-[0.98] {{ $themeClasses['btn'] }}"
                    >
                        <span>{{ $btnText }}</span>
                        <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif

                @if($dismissible)
                    <button
                        type="button"
                        id="dk-powder-dismiss-btn"
                        aria-label="Dismiss promotional banner"
                        class="p-1 rounded-md transition duration-150 {{ $themeClasses['close'] }}"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif

            </div>
        </div>
    </div>
</aside>

<script>
(function() {
    var banner = document.getElementById('dk-powder-promo-banner');
    if (!banner) return;

    var dismissible = {{ $dismissible ? 'true' : 'false' }};
    var storageKey = 'dk_powder_promo_dismissed_{{ md5($promoCode . $discountText) }}';

    // Check if dismissed in this session
    if (dismissible && sessionStorage.getItem(storageKey) === '1') {
        return; // remains hidden
    }

    // Show banner smoothly
    banner.style.display = 'block';

    // Dismiss handling
    var dismissBtn = document.getElementById('dk-powder-dismiss-btn');
    if (dismissBtn) {
        dismissBtn.addEventListener('click', function() {
            banner.style.opacity = '0';
            banner.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                banner.style.display = 'none';
            }, 250);
            if (dismissible) {
                sessionStorage.setItem(storageKey, '1');
            }
        });
    }

    // Copy campaign reference code
    var codeBtn = document.getElementById('dk-powder-code-btn');
    if (codeBtn) {
        codeBtn.addEventListener('click', function() {
            var code = codeBtn.getAttribute('data-code');
            if (!code) return;

            var copyIcon = document.getElementById('dk-copy-icon');
            var checkIcon = document.getElementById('dk-check-icon');
            var label = document.getElementById('dk-powder-code-label');

            var originalText = label ? label.textContent : '';

            var onSuccess = function() {
                if (copyIcon) copyIcon.classList.add('hidden');
                if (checkIcon) checkIcon.classList.remove('hidden');
                if (label) label.textContent = 'COPIED!';

                setTimeout(function() {
                    if (copyIcon) copyIcon.classList.remove('hidden');
                    if (checkIcon) checkIcon.classList.add('hidden');
                    if (label) label.textContent = originalText;
                }, 2000);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code).then(onSuccess).catch(function() {
                    fallbackCopy(code, onSuccess);
                });
            } else {
                fallbackCopy(code, onSuccess);
            }
        });
    }

    function fallbackCopy(text, cb) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
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
