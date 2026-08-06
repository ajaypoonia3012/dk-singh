<div class="hidden lg:flex items-center gap-5 text-sm">

    @guest

        <a href="/login"
           class="hover:text-[var(--primary-color)] transition duration-300 font-semibold">

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
               class="btn-primary-small">
                {{ $setting?->admin_panel_label ?: 'Admin Panel' }}
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

                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5">
                            {{ $unreadNotifications }}
                        </span>

                    @endif

                </a>

            </div>

        @endif

        <span class="text-black font-bold">
            {{ auth()->user()->name }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="hover:text-red-500 transition duration-300"
            >
                {{ $setting?->logout_label ?: 'Logout' }}
            </button>
        </form>

    @endauth

</div>
