<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\Notification;

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
        if (Schema::hasTable('settings')) {

    $setting = Setting::first();

    View::share('setting', $setting);
}

if (Schema::hasTable('theme_settings')) {

    $theme = ThemeSetting::first();

    View::share('theme', $theme);
}

        View::composer('*', function ($view) {

            $count = 0;

            if (auth()->check()) {

                $count = Notification::where(
                    'user_id',
                    auth()->id()
                )
                ->where(
                    'is_read',
                    false
                )
                ->count();
            }

            $view->with(
                'unreadNotifications',
                $count
            );
        });
    }
}