<div class="p-6 md:p-10 space-y-8 bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-white min-h-[700px]">

    {{-- GOOGLE SERP PREVIEW CARD --}}
    <div class="p-6 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200/90 dark:border-white/10 shadow-sm space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500 flex items-center gap-1.5">
                <span>🔍</span> Google Search Snippet Simulation
            </span>
            <span class="text-[11px] font-mono text-slate-400">Desktop SERP</span>
        </div>

        <div class="space-y-1 max-w-2xl">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-xs">
                    🌐
                </div>
                <div>
                    <div class="text-xs text-slate-800 dark:text-zinc-200 font-medium">dksinghfitness.com</div>
                    <div class="text-[11px] text-slate-500">https://dksinghfitness.com</div>
                </div>
            </div>

            <h3 class="text-lg text-blue-600 dark:text-blue-400 hover:underline font-medium cursor-pointer pt-1">
                {{ !empty($general['meta_title']) ? $general['meta_title'] : ($general['site_name'] . ' | Elite Fitness Coaching & Personalized Nutrition') }}
            </h3>

            <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed pt-0.5">
                {{ !empty($general['meta_description']) ? $general['meta_description'] : 'Join DK Singh Fitness & Nutrition for personalized 1-on-1 coaching, science-backed workout programs, and custom Indian macro meal planning.' }}
            </p>
        </div>
    </div>

    {{-- BRAND BANNER PREVIEW --}}
    <div class="p-8 rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200/90 dark:border-white/10 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-2">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-500 flex items-center gap-1.5">
                <span>🏷️</span> Brand Identity & Header Simulation
            </span>
            <span class="text-[11px] font-mono text-slate-400">Live Header</span>
        </div>

        <div class="p-4 bg-slate-950 text-white rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if(!empty($general['logo']))
                    <img src="{{ asset('storage/'.$general['logo']) }}" alt="Brand Logo" class="h-9 max-w-[160px] object-contain">
                @else
                    <div class="w-9 h-9 rounded-lg bg-amber-500 text-black font-black flex items-center justify-center text-sm">
                        DK
                    </div>
                @endif
                <div>
                    <div class="font-extrabold text-sm tracking-tight text-white">
                        {{ $general['site_name'] ?? 'DK Singh Fitness' }}
                    </div>
                    @if(!empty($general['site_tagline']))
                        <div class="text-[10px] text-zinc-400">
                            {{ $general['site_tagline'] }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-4 text-xs font-semibold text-zinc-300">
                <span>Programs</span>
                <span>Transformations</span>
                <span>Store</span>
                <span>Fitness Hub</span>
                <span class="px-3 py-1 rounded-lg bg-amber-500 text-black font-bold">Get Started</span>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 space-y-2">
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider block">
                    Contact Channels
                </span>
                <div class="text-xs space-y-1.5 text-slate-600 dark:text-zinc-300">
                    <div>📧 <strong>Email:</strong> {{ $general['email'] ?? 'Not set' }}</div>
                    <div>📱 <strong>Phone:</strong> {{ $general['phone'] ?? 'Not set' }}</div>
                    <div>💬 <strong>WhatsApp:</strong> {{ $general['whatsapp'] ?? 'Not set' }}</div>
                    <div>📍 <strong>Address:</strong> {{ $general['address'] ?? 'Not set' }}</div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60 space-y-2">
                <span class="text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider block">
                    Connected Social Channels
                </span>
                <div class="text-xs space-y-1.5 text-slate-600 dark:text-zinc-300">
                    <div>📸 <strong>Instagram:</strong> {{ !empty($general['instagram']) ? $general['instagram'] : 'Not configured' }}</div>
                    <div>🎥 <strong>YouTube:</strong> {{ !empty($general['youtube']) ? $general['youtube'] : 'Not configured' }}</div>
                    <div>📘 <strong>Facebook:</strong> {{ !empty($general['facebook']) ? $general['facebook'] : 'Not configured' }}</div>
                    <div>🐦 <strong>Twitter / X:</strong> {{ !empty($general['twitter']) ? $general['twitter'] : 'Not configured' }}</div>
                </div>
            </div>
        </div>
    </div>

</div>
