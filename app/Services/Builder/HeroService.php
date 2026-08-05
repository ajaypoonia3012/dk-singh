<?php

namespace App\Services\Builder;

use App\Models\HeroSetting;

class HeroService
{
    public function get(): HeroSetting
    {
        return HeroSetting::firstOrCreate(
            [],
            [
                'heading' => 'Transform Your Body',
                'subheading' => 'Fitness Starts Today',
                'button_text' => 'Join Now',
                'button_text' => 'Join Now',

'button_link' => '/plans',

'view_button_text' => 'View Transformations',

'view_button_link' => '/transformations',
                'video' => null,
                'overlay_color' => '#000000',
                'overlay_opacity' => 40,
                'template' => 'default',
                'enabled' => true,
            ]
        );
    }

    public function update(array $data): HeroSetting
    {
        $hero = $this->get();

        $hero->update($data);

        return $hero->fresh();
    }
}