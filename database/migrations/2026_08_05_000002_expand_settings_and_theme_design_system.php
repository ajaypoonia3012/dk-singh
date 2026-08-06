<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->string('dark_logo')->nullable();
            $table->string('apple_touch_icon')->nullable();
            $table->string('support_email')->nullable();
            $table->string('legal_business_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('copyright_text')->nullable();
            $table->string('fitness_hub_label')->default('Fitness Hub');
            $table->boolean('maintenance_enabled')->default(false);
            $table->text('maintenance_message')->nullable();
        });

        Schema::table('theme_settings', function (Blueprint $table): void {
            $table->string('success_color')->default('#22c55e');
            $table->string('warning_color')->default('#f59e0b');
            $table->string('danger_color')->default('#ef4444');
            $table->string('info_color')->default('#3b82f6');
            $table->string('neutral_color')->default('#6b7280');
            $table->string('heading_font')->default('Poppins');
            $table->string('body_font')->default('Poppins');
            $table->decimal('font_scale', 4, 2)->default(1);
            $table->unsignedSmallInteger('heading_weight')->default(800);
            $table->unsignedSmallInteger('body_weight')->default(400);
            $table->decimal('letter_spacing', 5, 2)->default(0);
            $table->decimal('line_height', 4, 2)->default(1.5);
            $table->string('primary_button_text_color')->default('#111111');
            $table->string('secondary_button_background')->default('#111111');
            $table->string('secondary_button_text_color')->default('#ffffff');
            $table->string('button_radius')->default('1rem');
            $table->string('button_shadow')->default('0 10px 25px rgba(0,0,0,.12)');
            $table->string('button_hover_animation')->default('translateY(-2px)');
            $table->string('card_background')->default('#ffffff');
            $table->string('card_radius')->default('1.5rem');
            $table->string('card_shadow')->default('0 20px 40px rgba(0,0,0,.08)');
            $table->unsignedSmallInteger('navbar_height')->default(96);
            $table->string('navbar_background')->default('#ffffff');
            $table->string('navbar_text_color')->default('#111111');
            $table->string('navbar_hover_color')->default('#facc15');
            $table->string('hero_overlay_color')->default('#000000');
            $table->unsignedTinyInteger('hero_overlay_opacity')->default(40);
            $table->string('hero_gradient')->nullable();
            $table->string('footer_background')->default('#000000');
            $table->string('footer_text_color')->default('#ffffff');
            $table->string('footer_link_color')->default('#9ca3af');
            $table->string('input_radius')->default('1rem');
            $table->string('input_border_color')->default('#d1d5db');
            $table->string('input_focus_color')->default('#facc15');
            $table->unsignedSmallInteger('container_width')->default(1280);
            $table->unsignedSmallInteger('section_padding')->default(96);
            $table->decimal('spacing_scale', 4, 2)->default(1);
            $table->unsignedSmallInteger('sidebar_width')->default(320);
            $table->boolean('animations_enabled')->default(true);
            $table->boolean('page_loader_enabled')->default(false);
            $table->boolean('scroll_reveal_enabled')->default(true);
            $table->boolean('dark_mode_enabled')->default(false);
            $table->boolean('dark_mode_toggle')->default(false);
            $table->string('dark_background')->default('#111111');
            $table->string('dark_surface')->default('#1f2937');
            $table->string('dark_text')->default('#f9fafb');
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn([
                'dark_logo',
                'apple_touch_icon',
                'support_email',
                'legal_business_name',
                'tax_id',
                'copyright_text',
                'fitness_hub_label',
                'maintenance_enabled',
                'maintenance_message',
            ]);
        });

        Schema::table('theme_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'success_color', 'warning_color', 'danger_color', 'info_color', 'neutral_color',
                'heading_font', 'body_font', 'font_scale', 'heading_weight', 'body_weight',
                'letter_spacing', 'line_height', 'primary_button_text_color',
                'secondary_button_background', 'secondary_button_text_color', 'button_radius',
                'button_shadow', 'button_hover_animation', 'card_background', 'card_radius',
                'card_shadow', 'navbar_height', 'navbar_background', 'navbar_text_color',
                'navbar_hover_color', 'hero_overlay_color', 'hero_overlay_opacity', 'hero_gradient',
                'footer_background', 'footer_text_color', 'footer_link_color', 'input_radius',
                'input_border_color', 'input_focus_color', 'container_width', 'section_padding',
                'spacing_scale', 'sidebar_width', 'animations_enabled', 'page_loader_enabled',
                'scroll_reveal_enabled', 'dark_mode_enabled', 'dark_mode_toggle', 'dark_background',
                'dark_surface', 'dark_text', 'custom_css', 'custom_js',
            ]);
        });
    }
};
