<nav x-data="{ open: false }" class="theme-navbar border-b">
    <x-theme.page-container>
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="theme-navbar-link">
                        <x-application-logo class="block h-9 w-auto fill-current" />
                    </a>
                </div>

                <div class="hidden sm:ms-10 sm:flex theme-content-gap">
                    <x-nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.edit')">
                        {{ __('Settings') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="theme-navbar-link inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium focus:outline-none transition duration-150">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="ms-1 fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('member.profile')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="theme-navbar-link w-full text-left px-4 py-2 text-sm">{{ __('Log Out') }}</button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button
                    type="button"
                    @click="open = ! open"
                    class="theme-navbar-toggle inline-flex items-center justify-center p-2 focus:outline-none transition duration-150"
                    :aria-expanded="open"
                    aria-controls="responsive-navigation"
                    aria-label="{{ __('Toggle navigation') }}"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </x-theme.page-container>

    <div id="responsive-navigation" :class="{'block': open, 'hidden': ! open}" class="theme-navbar-mobile hidden sm:hidden">
        <div class="pt-2 pb-3 theme-stack-sm">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.edit')">
                {{ __('Settings') }}
            </x-responsive-nav-link>
        </div>

        <div class="theme-divider pt-4 pb-1 border-t">
            <div class="px-4 theme-stack-sm">
                <div class="theme-text-secondary font-medium text-base">{{ Auth::user()->name }}</div>
                <div class="theme-text-neutral font-medium text-sm">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 theme-stack-sm">
                <x-responsive-nav-link :href="route('member.profile')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="theme-navbar-link w-full text-left px-4 py-2">{{ __('Log Out') }}</button>
                </form>
            </div>
        </div>
    </div>
</nav>
