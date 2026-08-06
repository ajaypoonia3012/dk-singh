<?php

namespace Tests\Feature\Performance;

use App\Filament\Resources\CommunicationLogResource;
use App\Filament\Resources\MembershipResource;
use App\Filament\Resources\ShipmentResource;
use App\Filament\Resources\UserResource;
use App\Filament\Widgets\Dashboard\MembershipChart;
use App\Filament\Widgets\Dashboard\RevenueChart;
use App\Filament\Widgets\LeadsChart;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ReflectionMethod;
use Tests\TestCase;

class CorePerformanceOptimizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_performance_indexes_are_created(): void
    {
        $this->assertTrue(Schema::hasIndex('orders', ['payment_status', 'created_at']));
        $this->assertTrue(Schema::hasIndex('memberships', ['status', 'expires_at']));
        $this->assertTrue(Schema::hasIndex('contact_leads', ['status', 'created_at']));
        $this->assertTrue(Schema::hasIndex('notifications', ['user_id', 'is_read']));
        $this->assertTrue(Schema::hasIndex('homepage_cards', ['is_active', 'sort_order']));
        $this->assertTrue(Schema::hasIndex('orders', ['user_id', 'created_at']));
        $this->assertTrue(Schema::hasIndex('coach_notes', ['user_id', 'is_visible', 'created_at']));
        $this->assertTrue(Schema::hasIndex('action_plans', ['user_id', 'is_completed', 'created_at']));
        $this->assertTrue(Schema::hasIndex('progress_logs', ['user_id', 'created_at']));
        $this->assertTrue(Schema::hasIndex('weekly_check_ins', ['user_id', 'created_at']));
        $this->assertTrue(Schema::hasIndex('workout_completions', ['user_id', 'workout_plan_id']));
        $this->assertTrue(Schema::hasIndex('diet_completions', ['user_id', 'diet_plan_id']));
        $this->assertTrue(Schema::hasIndex('shipments', ['order_id']));
        $this->assertTrue(Schema::hasIndex('shipments', ['courier_provider_id']));
    }

    public function test_setting_models_invalidate_their_cached_records(): void
    {
        Cache::put(Setting::CACHE_KEY, 'stale');
        Setting::query()->create(['site_name' => 'DK Singh Fitness']);
        $this->assertFalse(Cache::has(Setting::CACHE_KEY));

        Cache::put(ThemeSetting::CACHE_KEY, 'stale');
        ThemeSetting::query()->create(['theme_name' => 'Default']);
        $this->assertFalse(Cache::has(ThemeSetting::CACHE_KEY));
    }

    public function test_dashboard_stats_use_six_queries_or_fewer(): void
    {
        DB::flushQueryLog();
        DB::enableQueryLog();

        $stats = app(DashboardService::class)->getStatsOverview();

        $this->assertLessThanOrEqual(6, count(DB::getQueryLog()));
        $this->assertSame(0, $stats['orders']);
        $this->assertSame(0.0, $stats['revenue']);
    }

    public function test_chart_widgets_use_one_query_each(): void
    {
        foreach ([LeadsChart::class, MembershipChart::class, RevenueChart::class] as $widgetClass) {
            DB::flushQueryLog();
            DB::enableQueryLog();

            $method = new ReflectionMethod($widgetClass, 'getData');
            $method->invoke(new $widgetClass);

            $this->assertCount(1, DB::getQueryLog(), $widgetClass);
        }
    }

    public function test_relationship_columns_are_eager_loaded(): void
    {
        $this->assertEqualsCanonicalizing(
            ['user', 'plan'],
            array_keys(MembershipResource::getEloquentQuery()->getEagerLoads()),
        );
        $this->assertEqualsCanonicalizing(
            ['order', 'courierProvider'],
            array_keys(ShipmentResource::getEloquentQuery()->getEagerLoads()),
        );
        $this->assertEqualsCanonicalizing(
            ['user', 'provider'],
            array_keys(CommunicationLogResource::getEloquentQuery()->getEagerLoads()),
        );
        $this->assertContains(
            'activeMembership.plan',
            array_keys(UserResource::getEloquentQuery()->getEagerLoads()),
        );
    }
}
