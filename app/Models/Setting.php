<?php

namespace App\Models;

use App\Models\Concerns\HasGroupedConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasGroupedConfiguration;

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
        'dark_logo',
        'apple_touch_icon',

        'phone',
        'email',
        'support_email',
        'whatsapp',

        'address',
        'legal_business_name',
        'tax_id',

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
        'copyright_text',
        'fitness_hub_label',
        'supplements_label',
        'supplements_heading',

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

        'maintenance_enabled',
        'maintenance_message',

    ];

    protected $casts = [
        'enterprise_configuration' => 'array',
    ];

    protected function groupedConfigurationColumn(): string
    {
        return 'enterprise_configuration';
    }

    protected function groupedConfigurationDefaults(): array
    {
        return [
            'dark_logo' => null,
            'apple_touch_icon' => null,
            'support_email' => null,
            'legal_business_name' => null,
            'tax_id' => null,
            'copyright_text' => null,
            'fitness_hub_label' => 'Fitness Hub',
            'coaching_programs_label' => 'Coaching & Programs',
            'followers_label' => 'Followers',
            'years_label' => 'Years Experience',
            'transformations_label' => 'Transformations',
            'maintenance_enabled' => false,
            'maintenance_message' => null,
        ];
    }

    public function getCoachingProgramsLabelAttribute(): string
    {
        return $this->enterprise_configuration['coaching_programs_label']
            ?? ($this->attributes['coaching_programs_label'] ?? 'Coaching & Programs');
    }

    public function getMapEmbedUrlAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // If an iframe snippet was saved, extract the src URL
        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $value, $matches)) {
            $extracted = trim($matches[1]);
            if (filter_var($extracted, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $extracted)) {
                return $extracted;
            }
        }

        $trimmed = trim($value);
        if (filter_var($trimmed, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $trimmed)) {
            return $trimmed;
        }

        return null;
    }

    protected function groupedConfigurationTypes(): array
    {
        return [
            'maintenance_enabled' => 'boolean',
        ];
    }

    public static bool $isSyncing = false;

    protected static function booted(): void
    {
        static::saved(function (Setting $setting): void {
            Cache::forget(self::CACHE_KEY);
            \App\Providers\AppServiceProvider::clearSharedViewData();

            if (static::$isSyncing) {
                return;
            }

            static::$isSyncing = true;
            try {
                $syncData = [];
                if ($setting->wasChanged('hero_title') || ($setting->wasRecentlyCreated && filled($setting->hero_title))) {
                    $syncData['heading'] = $setting->hero_title;
                }
                if ($setting->wasChanged('hero_subtitle') || ($setting->wasRecentlyCreated && filled($setting->hero_subtitle))) {
                    $syncData['subheading'] = $setting->hero_subtitle;
                }
                if ($setting->wasChanged('cta_button_text') || ($setting->wasRecentlyCreated && filled($setting->cta_button_text))) {
                    $syncData['button_text'] = $setting->cta_button_text;
                }
                if ($setting->wasChanged('cta_button_link') || ($setting->wasRecentlyCreated && filled($setting->cta_button_link))) {
                    $syncData['button_link'] = $setting->cta_button_link;
                }
                if ($setting->wasChanged('followers_label') || ($setting->wasRecentlyCreated && filled($setting->followers_label))) {
                    $syncData['followers_label'] = $setting->followers_label;
                }
                if ($setting->wasChanged('years_label') || ($setting->wasRecentlyCreated && filled($setting->years_label))) {
                    $syncData['years_label'] = $setting->years_label;
                }
                if ($setting->wasChanged('transformations_label') || ($setting->wasRecentlyCreated && filled($setting->transformations_label))) {
                    $syncData['transformations_label'] = $setting->transformations_label;
                }

                if (! empty($syncData)) {
                    $hero = HeroSetting::first();
                    if ($hero) {
                        $hero->update($syncData);
                    }
                }
            } finally {
                static::$isSyncing = false;
            }
        });

        static::deleted(function (): void {
            Cache::forget(self::CACHE_KEY);
            \App\Providers\AppServiceProvider::clearSharedViewData();
        });
    }
}
