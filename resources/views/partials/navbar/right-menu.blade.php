<div class="hidden lg:flex items-center gap-3 xl:gap-4 text-sm shrink-0">

    @guest

        <a href="/login"
           class="theme-navbar-link transition duration-300 font-semibold">
            {{ $setting?->login_label ?: 'Login' }}
        </a>

        <a href="/register"
           class="btn-primary-small">
            {{ $setting?->register_label ?: $setting?->cta_button_text ?: 'Join Now' }}
        </a>

    @endguest

    @auth

        @if(auth()->user()->account_type === 'admin')

            <a href="/admin"
               class="btn-primary-small !py-1.5 !px-3 !text-xs font-semibold">
                {{ $setting?->admin_panel_label ?: 'Dashboard' }}
            </a>

        @elseif(auth()->user()->activeMembership)

            <a href="/member/dashboard"
               class="btn-primary-small">
                {{ $setting?->my_plan_label ?: 'My Plan' }}
            </a>

        @else

            <a href="{{ route('account.orders') }}"
               class="btn-primary-small">
                {{ $setting?->my_orders_label ?: 'My Orders' }}
            </a>

        @endif

        @if(auth()->user()->activeMembership)

            <div class="relative group">

                <a href="{{ route('member.notifications') }}"
                   class="relative text-2xl">

                    🔔

                    @if(($unreadNotifications ?? 0) > 0)

                        <x-theme.badge status="danger" class="absolute -top-2 -right-2 text-[10px] px-1.5 py-0.5">
                            {{ $unreadNotifications }}
                        </x-theme.badge>

                    @endif

                </a>

            </div>

        @endif

        <span class="theme-text-secondary font-bold">
            {{ auth()->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="theme-link-danger transition duration-300"
            >
                {{ $setting?->logout_label ?: 'Logout' }}
            </button>
        </form>

    @endauth

</div>
