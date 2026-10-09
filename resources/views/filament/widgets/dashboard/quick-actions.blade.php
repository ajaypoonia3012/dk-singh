<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-gray-950 dark:text-white flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold text-sm">⚡</span>
                    Quick Actions
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Frequent publishing and administrative shortcuts</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            {{-- Article CMS --}}
            <a href="/admin/blog-posts/create"
               class="group flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200/80 dark:border-white/10 bg-white dark:bg-gray-900/60 hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-sm hover:-translate-y-0.5 transition-all text-center">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform mb-2">
                    ✍️
                </div>
                <div class="font-semibold text-xs text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">New Article</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Editorial CMS</div>
            </a>

            {{-- Exercise Library --}}
            <a href="/admin/exercises/create"
               class="group flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200/80 dark:border-white/10 bg-white dark:bg-gray-900/60 hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-sm hover:-translate-y-0.5 transition-all text-center">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform mb-2">
                    🏋️
                </div>
                <div class="font-semibold text-xs text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">New Exercise</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Movement Vault</div>
            </a>

            {{-- Media --}}
            <a href="/admin/media/create"
               class="group flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200/80 dark:border-white/10 bg-white dark:bg-gray-900/60 hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-sm hover:-translate-y-0.5 transition-all text-center">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform mb-2">
                    🖼️
                </div>
                <div class="font-semibold text-xs text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Upload Media</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Media Assets</div>
            </a>

            {{-- Product --}}
            <a href="/admin/products/create"
               class="group flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200/80 dark:border-white/10 bg-white dark:bg-gray-900/60 hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-sm hover:-translate-y-0.5 transition-all text-center">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform mb-2">
                    📦
                </div>
                <div class="font-semibold text-xs text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">New Product</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Commerce Store</div>
            </a>

            {{-- Workout Plan --}}
            <a href="/admin/workout-plans/create"
               class="group flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200/80 dark:border-white/10 bg-white dark:bg-gray-900/60 hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-sm hover:-translate-y-0.5 transition-all text-center">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform mb-2">
                    💪
                </div>
                <div class="font-semibold text-xs text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Workout Plan</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Client Program</div>
            </a>

            {{-- Membership Plan --}}
            <a href="/admin/plans/create"
               class="group flex flex-col items-center justify-center p-4 rounded-xl border border-gray-200/80 dark:border-white/10 bg-white dark:bg-gray-900/60 hover:border-amber-500 dark:hover:border-amber-500 hover:shadow-sm hover:-translate-y-0.5 transition-all text-center">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform mb-2">
                    💳
                </div>
                <div class="font-semibold text-xs text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Membership Plan</div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Tier Config</div>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>