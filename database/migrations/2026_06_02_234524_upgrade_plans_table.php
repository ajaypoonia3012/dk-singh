<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {

            $table->string('slug')->nullable()->unique()->after('name');

            $table->decimal('discount_price', 10, 2)->nullable()->after('price');

            $table->string('billing_cycle')->default('month')->after('duration');

            $table->string('badge')->nullable()->after('billing_cycle');

            $table->string('button_text')->default('Join Now')->after('badge');

            $table->string('button_link')->nullable()->after('button_text');

            $table->string('access_type')->default('basic')->after('button_link');

            $table->string('thumbnail')->nullable()->after('access_type');

            $table->integer('sort_order')->default(0)->after('thumbnail');

        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {

            $table->dropColumn([

                'slug',
                'discount_price',
                'billing_cycle',
                'badge',
                'button_text',
                'button_link',
                'access_type',
                'thumbnail',
                'sort_order',

            ]);

        });
    }
};