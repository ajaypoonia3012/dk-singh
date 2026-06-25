<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {

            $table->string('title')->after('id');

            $table->string('slug')->unique()->nullable();

            $table->text('description')->nullable();

            $table->longText('content')->nullable();

            $table->string('goal')->nullable();

            $table->string('image')->nullable();

            $table->decimal('price', 10, 2)->default(0);

            $table->boolean('status')->default(true);

        });
    }

    public function down(): void
    {
        Schema::table('diet_plans', function (Blueprint $table) {

            $table->dropColumn([
                'title',
                'slug',
                'description',
                'content',
                'goal',
                'image',
                'price',
                'status',
            ]);

        });
    }
};