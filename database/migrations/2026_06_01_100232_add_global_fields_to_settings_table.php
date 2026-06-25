<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->string('site_name')->nullable();
            $table->string('site_tagline')->nullable();

            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('whatsapp')->nullable();

            $table->text('address')->nullable();

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            $table->text('footer_text')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->dropColumn([
                'site_name',
                'site_tagline',
                'logo',
                'favicon',
                'phone',
                'email',
                'whatsapp',
                'address',
                'facebook',
                'instagram',
                'youtube',
                'twitter',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'footer_text'
            ]);

        });
    }
};