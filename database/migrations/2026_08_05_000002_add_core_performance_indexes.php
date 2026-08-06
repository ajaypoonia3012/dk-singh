<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_index');
            $table->index(['user_id', 'created_at'], 'orders_user_created_index');
        });

        Schema::table('memberships', function (Blueprint $table): void {
            $table->index(['status', 'expires_at'], 'memberships_status_expires_index');
            $table->index('created_at', 'memberships_created_index');
        });

        Schema::table('contact_leads', function (Blueprint $table): void {
            $table->index(['status', 'created_at'], 'contact_leads_status_created_index');
            $table->index('created_at', 'contact_leads_created_index');
        });

        Schema::table('notifications', function (Blueprint $table): void {
            $table->index(['user_id', 'is_read'], 'notifications_user_unread_index');
        });

        Schema::table('coach_notes', function (Blueprint $table): void {
            $table->index(['user_id', 'is_visible', 'created_at'], 'coach_notes_user_visible_created_index');
        });

        Schema::table('action_plans', function (Blueprint $table): void {
            $table->index(['user_id', 'is_completed', 'created_at'], 'action_plans_user_completed_created_index');
        });

        Schema::table('progress_logs', function (Blueprint $table): void {
            $table->index(['user_id', 'created_at'], 'progress_logs_user_created_index');
        });

        Schema::table('weekly_check_ins', function (Blueprint $table): void {
            $table->index(['user_id', 'created_at'], 'weekly_check_ins_user_created_index');
        });

        Schema::table('workout_completions', function (Blueprint $table): void {
            $table->index(['user_id', 'workout_plan_id'], 'workout_completions_user_plan_index');
        });

        Schema::table('diet_completions', function (Blueprint $table): void {
            $table->index(['user_id', 'diet_plan_id'], 'diet_completions_user_plan_index');
        });

        Schema::table('shipments', function (Blueprint $table): void {
            $table->index('order_id', 'shipments_order_index');
            $table->index('courier_provider_id', 'shipments_courier_provider_index');
        });

        Schema::table('homepage_cards', function (Blueprint $table): void {
            $table->index(['is_active', 'sort_order'], 'homepage_cards_active_sort_index');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex('orders_payment_created_index');
            $table->dropIndex('orders_user_created_index');
        });

        Schema::table('memberships', function (Blueprint $table): void {
            $table->dropIndex('memberships_status_expires_index');
            $table->dropIndex('memberships_created_index');
        });

        Schema::table('contact_leads', function (Blueprint $table): void {
            $table->dropIndex('contact_leads_status_created_index');
            $table->dropIndex('contact_leads_created_index');
        });

        Schema::table('notifications', function (Blueprint $table): void {
            $table->dropIndex('notifications_user_unread_index');
        });

        Schema::table('coach_notes', function (Blueprint $table): void {
            $table->dropIndex('coach_notes_user_visible_created_index');
        });

        Schema::table('action_plans', function (Blueprint $table): void {
            $table->dropIndex('action_plans_user_completed_created_index');
        });

        Schema::table('progress_logs', function (Blueprint $table): void {
            $table->dropIndex('progress_logs_user_created_index');
        });

        Schema::table('weekly_check_ins', function (Blueprint $table): void {
            $table->dropIndex('weekly_check_ins_user_created_index');
        });

        Schema::table('workout_completions', function (Blueprint $table): void {
            $table->dropIndex('workout_completions_user_plan_index');
        });

        Schema::table('diet_completions', function (Blueprint $table): void {
            $table->dropIndex('diet_completions_user_plan_index');
        });

        Schema::table('shipments', function (Blueprint $table): void {
            $table->dropIndex('shipments_order_index');
            $table->dropIndex('shipments_courier_provider_index');
        });

        Schema::table('homepage_cards', function (Blueprint $table): void {
            $table->dropIndex('homepage_cards_active_sort_index');
        });
    }
};
