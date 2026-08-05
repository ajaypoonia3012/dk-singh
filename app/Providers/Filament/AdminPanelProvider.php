<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
->brandName(
    \App\Models\Setting::first()?->site_name
    ?? config('app.name')
)
->login()

->sidebarCollapsibleOnDesktop()

->sidebarWidth('18rem')

->collapsedSidebarWidth('4.5rem')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
         

 ->widgets([

    \App\Filament\Widgets\StatsOverview::class,

    \App\Filament\Widgets\Dashboard\QuickActions::class,

    \App\Filament\Widgets\Dashboard\RevenueChart::class,

    \App\Filament\Widgets\Dashboard\MembershipChart::class,

    \App\Filament\Widgets\Dashboard\RecentOrders::class,

    \App\Filament\Widgets\Dashboard\RecentMembers::class,

    \App\Filament\Widgets\Dashboard\ExpiringMemberships::class,

    \App\Filament\Widgets\Dashboard\ActivityFeed::class,

    \App\Filament\Widgets\LeadsChart::class,

    \App\Filament\Widgets\LeadsOverview::class,

])
        

    ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
