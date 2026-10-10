<?php

namespace App\Providers;

use App\Models\ActionPlan;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\CoachNote;
use App\Models\CommunicationLog;
use App\Models\CommunicationProvider;
use App\Models\ContactLead;
use App\Models\CourierProvider;
use App\Models\DietPlan;
use App\Models\Exercise;
use App\Models\HomepageCard;
use App\Models\Media;
use App\Models\MediaCategory;
use App\Models\Membership;
use App\Models\MessageTemplate;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\Testimonial;
use App\Models\ThemeSetting;
use App\Models\Transformation;
use App\Models\TransformationPhoto;
use App\Models\User;
use App\Models\WebsiteSection;
use App\Models\WorkoutPlan;
use App\Policies\AdminPolicy;
use Filament\Forms\Components\FileUpload;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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

    private static mixed $sharedSetting = null;
    private static mixed $sharedTheme = null;
    private static bool $hasLoadedShared = false;

    public static function clearSharedViewData(): void
    {
        self::$sharedSetting = null;
        self::$sharedTheme = null;
        self::$hasLoadedShared = false;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        self::clearSharedViewData();

        $this->registerResourcePolicies();
        $this->configureRateLimiters();
        $this->configureSecureUploads();

        View::composer('*', function (IlluminateView $view): void {
            if (! self::$hasLoadedShared) {
                self::$sharedSetting = Schema::hasTable('settings')
                    ? (Cache::get(Setting::CACHE_KEY) ?? Setting::query()->first())
                    : null;
                self::$sharedTheme = Schema::hasTable('theme_settings')
                    ? (Cache::get(ThemeSetting::CACHE_KEY) ?? ThemeSetting::query()->first())
                    : null;
                self::$hasLoadedShared = true;

                View::share('setting', self::$sharedSetting);
                View::share('theme', self::$sharedTheme);
            }

            $viewSetting = $view->offsetExists('setting') ? $view->offsetGet('setting') : self::$sharedSetting;
            $viewTheme = $view->offsetExists('theme') ? $view->offsetGet('theme') : self::$sharedTheme;

            $effectiveSetting = ($viewSetting instanceof Setting)
                ? $viewSetting
                : new Setting(['site_name' => config('app.name', 'DK Singh Fitness')]);
            $effectiveTheme = ($viewTheme instanceof ThemeSetting)
                ? $viewTheme
                : new ThemeSetting;

            $view->with('setting', $effectiveSetting);
            $view->with('theme', $effectiveTheme);
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

    private function registerResourcePolicies(): void
    {
        $models = [
            ActionPlan::class,
            Blog::class,
            BlogCategory::class,
            BlogPost::class,
            BlogTag::class,
            CoachNote::class,
            CommunicationLog::class,
            CommunicationProvider::class,
            ContactLead::class,
            CourierProvider::class,
            DietPlan::class,
            Exercise::class,
            HomepageCard::class,
            Media::class,
            MediaCategory::class,
            Membership::class,
            MessageTemplate::class,
            Notification::class,
            Order::class,
            Plan::class,
            Product::class,
            Program::class,
            Service::class,
            Setting::class,
            Shipment::class,
            Testimonial::class,
            ThemeSetting::class,
            Transformation::class,
            TransformationPhoto::class,
            User::class,
            WebsiteSection::class,
            WorkoutPlan::class,
        ];

        foreach ($models as $model) {
            Gate::policy($model, AdminPolicy::class);
        }
    }

    private function configureRateLimiters(): void
    {
        RateLimiter::for('contact', fn (Request $request): Limit => Limit::perMinute(5)
            ->by($request->user()?->getAuthIdentifier() ?? $request->ip()));

        RateLimiter::for('payments', fn (Request $request): Limit => Limit::perMinute(10)
            ->by($request->user()?->getAuthIdentifier() ?? $request->ip()));

        RateLimiter::for('admin', fn (Request $request): Limit => Limit::perMinute(120)
            ->by($request->user()?->getAuthIdentifier() ?? $request->ip()));
    }

    private function configureSecureUploads(): void
    {
        FileUpload::configureUsing(function (FileUpload $upload): void {
            $upload
                ->maxSize(20 * 1024)
                ->acceptedFileTypes([
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                    'image/gif',
                    'image/x-icon',
                    'video/mp4',
                    'video/webm',
                    'application/pdf',
                ]);
        });
    }
}
