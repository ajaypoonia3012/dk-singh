<?php

namespace App\Services\Builder;

use App\Models\HomepageCard;
use App\Models\Setting;

class BuilderState
{
    protected Setting $setting;

    public function __construct()
    {
        $this->setting = Setting::first();
    }

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    public function hero(): array
    {
        return [
            'title' => $this->setting->hero_title,
            'subtitle' => $this->setting->hero_subtitle,
            'button_text' => $this->setting->cta_button_text,
            'button_link' => $this->setting->cta_button_link,
            'image' => $this->setting->hero_image,
        ];
    }

    public function saveHero(array $hero): void
    {
        $this->setting->update([
            'hero_title' => $hero['title'] ?? '',
            'hero_subtitle' => $hero['subtitle'] ?? '',
            'cta_button_text' => $hero['button_text'] ?? '',
            'cta_button_link' => $hero['button_link'] ?? '',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HOMEPAGE CARDS
    |--------------------------------------------------------------------------
    */

    public function homepageCards()
    {
        return HomepageCard::orderBy('sort_order')->get();
    }

    public function findHomepageCard($id): ?HomepageCard
    {
        return HomepageCard::find($id);
    }

    public function saveHomepageCard($card): void
    {
        $card->save();
    }
}