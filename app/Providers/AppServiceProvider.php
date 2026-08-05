<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\Setting;
use App\Models\ThemeSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as IlluminateView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function (IlluminateView $view): void {
            static $loaded = false;
            static $setting = null;
            static $theme = null;

            if (! $loaded) {
                $setting = Schema::hasTable('settings') ? Setting::query()->first() : null;
                $theme = Schema::hasTable('theme_settings') ? ThemeSetting::query()->first() : null;
                $loaded = true;

                View::share('setting', $setting);
                View::share('theme', $theme);
            }

            $view->with('setting', $setting);
            $view->with('theme', $theme);
        });

        View::composer('partials.navbar', function (IlluminateView $view): void {
            $unreadNotifications = 0;

            if (auth()->check() && Schema::hasTable('notifications')) {
                $unreadNotifications = Notification::query()
                    ->where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
            }

            $view->with('unreadNotifications', $unreadNotifications);
        });
    }
}
