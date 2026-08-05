<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transformations', function (Blueprint $table) {

            $table->decimal('before_weight', 5, 2)->nullable()->after('weight_loss');

            $table->decimal('after_weight', 5, 2)->nullable()->after('before_weight');

            $table->string('program')->nullable()->after('goal');

            $table->string('coach')
                ->default('DK Singh')
                ->after('program');

            $table->integer('sort_order')
                ->default(0)
                ->after('status');

            $table->string('seo_title')->nullable();

            $table->text('seo_description')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('transformations', function (Blueprint $table) {

            $table->dropColumn([
                'before_weight',
                'after_weight',
                'program',
                'coach',
                'sort_order',
                'seo_title',
                'seo_description',
            ]);

        });
    }
};