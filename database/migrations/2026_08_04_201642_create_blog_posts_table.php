<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('blog_category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('media_id')
                ->nullable()
                ->constrained('media')
                ->nullOnDelete();

            $table->string('title');

            $table->string('slug')->unique();

            $table->text('excerpt')->nullable();

            $table->longText('content')->nullable();

            $table->string('author')->default('DK Singh');

            $table->unsignedInteger('reading_time')->default(5);

            $table->unsignedBigInteger('views')->default(0);

            $table->boolean('featured')->default(false);

            $table->boolean('status')->default(true);

            $table->timestamp('published_at')->nullable();

            $table->string('seo_title')->nullable();

            $table->text('seo_description')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};