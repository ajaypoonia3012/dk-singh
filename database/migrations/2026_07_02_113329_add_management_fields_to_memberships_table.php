<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memberships', function (Blueprint $table) {

            $table->string('source')
                ->default('admin')
                ->after('status');

            $table->boolean('auto_renew')
                ->default(false)
                ->after('source');

            $table->string('payment_reference')
                ->nullable()
                ->after('auto_renew');

            $table->timestamp('cancelled_at')
                ->nullable()
                ->after('payment_reference');

            $table->text('cancel_reason')
                ->nullable()
                ->after('cancelled_at');

            $table->text('notes')
                ->nullable()
                ->after('cancel_reason');

        });
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {

            $table->dropColumn([
                'source',
                'auto_renew',
                'payment_reference',
                'cancelled_at',
                'cancel_reason',
                'notes',
            ]);

        });
    }
};