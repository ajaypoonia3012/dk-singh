<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ThemeSetting extends Model
{
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
        'animations_enabled' => 'boolean',
        'page_loader_enabled' => 'boolean',
        'scroll_reveal_enabled' => 'boolean',
        'dark_mode_enabled' => 'boolean',
        'dark_mode_toggle' => 'boolean',
        'font_scale' => 'decimal:2',
        'letter_spacing' => 'decimal:2',
        'line_height' => 'decimal:2',
        'spacing_scale' => 'decimal:2',
        'heading_weight' => 'integer',
        'body_weight' => 'integer',
        'hero_overlay_opacity' => 'integer',
        'navbar_height' => 'integer',
        'container_width' => 'integer',
        'section_padding' => 'integer',
        'sidebar_width' => 'integer',

    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
