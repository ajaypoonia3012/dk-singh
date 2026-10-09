<?php

namespace App\Support;

use App\Models\Media;
use App\Models\ThemeSetting;

class LoginAppearance
{
    /**
     * @return array<string, int|string|null>
     */
    public function resolve(?ThemeSetting $theme, string $context): array
    {
        $prefix = $context === 'admin' ? 'admin_login' : 'public_login';
        $mediaId = $theme?->getAttribute("{$prefix}_background_media_id");
        $media = filled($mediaId)
            ? Media::query()->whereKey($mediaId)->where('active', true)->first()
            : null;

        return [
            'context' => $context === 'admin' ? 'admin' : 'public',
            'mode' => $theme?->getAttribute("{$prefix}_background_mode") ?: 'theme',
            'color' => $theme?->getAttribute("{$prefix}_background_color") ?: '#111111',
            'image' => $media?->url,
            'fit' => $theme?->getAttribute("{$prefix}_background_fit") ?: 'cover',
            'position' => $theme?->getAttribute("{$prefix}_background_position") ?: 'center',
            'overlay_color' => $theme?->getAttribute("{$prefix}_overlay_color") ?: '#000000',
            'overlay_opacity' => max(0, min(100, (int) ($theme?->getAttribute("{$prefix}_overlay_opacity") ?? 0))),
            'animation' => $theme?->getAttribute("{$prefix}_animation") ?: 'none',
            'speed' => max(8, min(60, (int) ($theme?->getAttribute("{$prefix}_animation_speed") ?? 18))),
            'intensity' => max(0, min(100, (int) ($theme?->getAttribute("{$prefix}_animation_intensity") ?? 20))),
        ];
    }
}
