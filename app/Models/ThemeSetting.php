<?php

namespace App\Models;

use App\Models\Concerns\HasGroupedConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSetting extends Model
{
    use HasGroupedConfiguration;

    public const CACHE_KEY = 'site.theme';

    protected $fillable = [

        'primary_color',
        'secondary_color',
        'accent_color',
        'success_color',
        'warning_color',
        'danger_color',
        'info_color',
        'neutral_color',

        'heading_font',
        'body_font',
        'font_scale',
        'heading_weight',
        'body_weight',
        'letter_spacing',
        'line_height',

        'primary_button_text_color',
        'secondary_button_background',
        'secondary_button_text_color',
        'button_radius',
        'button_shadow',
        'button_hover_animation',

        'card_background',
        'card_radius',
        'card_shadow',
        'navbar_height',
        'navbar_background',
        'navbar_text_color',
        'navbar_hover_color',
        'hero_overlay_color',
        'hero_overlay_opacity',
        'hero_gradient',
        'footer_background',
        'footer_text_color',
        'footer_link_color',
        'input_radius',
        'input_border_color',
        'input_focus_color',
        'container_width',
        'section_padding',
        'spacing_scale',
        'sidebar_width',
        'animations_enabled',
        'page_loader_enabled',
        'scroll_reveal_enabled',
        'dark_mode_enabled',
        'dark_mode_toggle',
        'dark_background',
        'dark_surface',
        'dark_text',
        'custom_css',
        'custom_js',

        'show_hero',

        'show_programs',
        'show_services',
        'show_products',
        'show_blogs',
        'show_transformations',
        'show_plans',

        'show_about',
        'show_bmi',
        'show_homepage_cards',
        'show_testimonials',
        'show_contact',

        'announcement_enabled',
        'announcement_text',
        'announcement_link',

        'popup_enabled',
        'popup_title',
        'popup_description',
        'popup_button_text',
        'popup_button_link',
        'popup_image',

        'clients_count',
        'coached_count',
        'programs_count',
        'countries_count',

        'theme_name',

    ];

    protected $casts = [

        'show_hero' => 'boolean',

        'show_programs' => 'boolean',
        'show_services' => 'boolean',
        'show_products' => 'boolean',
        'show_blogs' => 'boolean',
        'show_transformations' => 'boolean',
        'show_plans' => 'boolean',

        'show_about' => 'boolean',
        'show_bmi' => 'boolean',
        'show_homepage_cards' => 'boolean',
        'show_testimonials' => 'boolean',
        'show_contact' => 'boolean',

        'announcement_enabled' => 'boolean',
        'popup_enabled' => 'boolean',
        'design_configuration' => 'array',

    ];

    protected function groupedConfigurationColumn(): string
    {
        return 'design_configuration';
    }

    protected function groupedConfigurationDefaults(): array
    {
        return [
            'success_color' => '#22c55e',
            'warning_color' => '#f59e0b',
            'danger_color' => '#ef4444',
            'info_color' => '#3b82f6',
            'neutral_color' => '#6b7280',
            'heading_font' => 'Poppins',
            'body_font' => 'Poppins',
            'font_scale' => 1.0,
            'heading_weight' => 800,
            'body_weight' => 400,
            'letter_spacing' => 0.0,
            'line_height' => 1.5,
            'primary_button_text_color' => '#111111',
            'secondary_button_background' => '#111111',
            'secondary_button_text_color' => '#ffffff',
            'button_radius' => '1rem',
            'button_shadow' => '0 10px 25px rgba(0,0,0,.12)',
            'button_hover_animation' => 'translateY(-2px)',
            'card_background' => '#ffffff',
            'card_radius' => '1.5rem',
            'card_shadow' => '0 20px 40px rgba(0,0,0,.08)',
            'navbar_height' => 96,
            'navbar_background' => '#ffffff',
            'navbar_text_color' => '#111111',
            'navbar_hover_color' => '#facc15',
            'hero_overlay_color' => '#000000',
            'hero_overlay_opacity' => 40,
            'hero_gradient' => null,
            'footer_background' => '#000000',
            'footer_text_color' => '#ffffff',
            'footer_link_color' => '#9ca3af',
            'input_radius' => '1rem',
            'input_border_color' => '#d1d5db',
            'input_focus_color' => '#facc15',
            'container_width' => 1280,
            'section_padding' => 96,
            'spacing_scale' => 1.0,
            'sidebar_width' => 320,
            'animations_enabled' => true,
            'page_loader_enabled' => false,
            'scroll_reveal_enabled' => true,
            'dark_mode_enabled' => false,
            'dark_mode_toggle' => false,
            'dark_background' => '#111111',
            'dark_surface' => '#1f2937',
            'dark_text' => '#f9fafb',
            'custom_css' => null,
            'custom_js' => null,
        ];
    }

    protected function groupedConfigurationTypes(): array
    {
        return [
            'font_scale' => 'float',
            'heading_weight' => 'integer',
            'body_weight' => 'integer',
            'letter_spacing' => 'float',
            'line_height' => 'float',
            'navbar_height' => 'integer',
            'hero_overlay_opacity' => 'integer',
            'container_width' => 'integer',
            'section_padding' => 'integer',
            'spacing_scale' => 'float',
            'sidebar_width' => 'integer',
            'animations_enabled' => 'boolean',
            'page_loader_enabled' => 'boolean',
            'scroll_reveal_enabled' => 'boolean',
            'dark_mode_enabled' => 'boolean',
            'dark_mode_toggle' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
