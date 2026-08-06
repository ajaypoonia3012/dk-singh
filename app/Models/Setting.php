<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const CACHE_KEY = 'site.settings';

    protected $fillable = [

        'hero_title',
        'hero_subtitle',
        'hero_image',

        'about_title',
        'about_description',
        'about_description_2',
        'about_image',

        'blog_label',
        'product_label',
        'service_label',
        'program_label',
        'business_niche',
        'working_hours',

        'instagram_followers',
        'years_experience',
        'transformations',

        'cta_button_text',
        'cta_button_link',

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

        'home_label',
        'about_label',
        'contact_label',
        'plan_label',
        'transformation_label',

        'login_label',
        'register_label',

        'admin_panel_label',
        'my_plan_label',
        'my_orders_label',
        'logout_label',
        'home_heading',
        'services_heading',
        'programs_heading',
        'transformations_heading',

        'about_cta_text',
        'contact_cta_text',

        'view_programs_text',
        'view_transformations_text',

        'meta_title',
        'meta_description',
        'meta_keywords',

        'footer_text',

        'contact_title',
        'contact_heading',
        'contact_description',

        'business_display_name',
        'map_embed_url',
        'map_link',

        'google_site_verification',
        'google_analytics_id',

        'footer_links_heading',
        'footer_services_heading',

        'services_page_label',
        'services_page_description',

        'programs_page_label',
        'programs_page_description',

        'transformations_page_label',
        'transformations_page_title',
        'transformations_page_description',

        'hero_card_title',
        'hero_card_text',

        'programs_description',
        'services_description',

        'testimonials_title',
        'testimonials_heading',
        'testimonials_description',

        'contact_map_text',

        'bmi_label',
        'bmi_heading',
        'bmi_description',

        'verified_client_label',

        'followers_label',
        'years_label',
        'transformations_label',

    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
