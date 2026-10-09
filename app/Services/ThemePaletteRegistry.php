<?php

namespace App\Services;

use Illuminate\Support\HtmlString;
use InvalidArgumentException;

class ThemePaletteRegistry
{
    /**
     * Named Theme Presets mapped to core palettes with curated design intent.
     *
     * @var array<string, array{name: string, description: string, palette_key: string, badge: string}>
     */
    public const PRESETS = [
        'dk-singh-signature' => [
            'name' => 'DK Singh Signature',
            'description' => 'Premium fitness brand with black, gold, and white typography and high-impact cards.',
            'palette_key' => 'classic-gold',
            'badge' => 'Signature',
        ],
        'editorial-fitness' => [
            'name' => 'Editorial Fitness',
            'description' => 'Clean, article-focused layout with restrained borders and large modern typography.',
            'palette_key' => 'minimal-white',
            'badge' => 'Editorial',
        ],
        'modern-fitness' => [
            'name' => 'Modern Fitness',
            'description' => 'Energetic high-contrast dark theme with vibrant accents and rounded cards.',
            'palette_key' => 'modern-dark',
            'badge' => 'Modern',
        ],
        'minimal' => [
            'name' => 'Minimal',
            'description' => 'Neutral, clean palette with minimal shadows and subtle card geometry.',
            'palette_key' => 'minimal-white',
            'badge' => 'Minimal',
        ],
        'bold-performance' => [
            'name' => 'Bold Performance',
            'description' => 'Strong athletic typography, high contrast, and dynamic CTA treatments.',
            'palette_key' => 'fitness-red',
            'badge' => 'Performance',
        ],
    ];

    /**
     * @return array<string, array{name: string, description: string, preview: array<int, string>, tokens: array<string, mixed>}>
     */
    public function all(): array
    {
        return config('theme-palettes', []);
    }

    /** @return array<string, array{name: string, description: string, palette_key: string, badge: string}> */
    public function presets(): array
    {
        return self::PRESETS;
    }

    /** @return array<string, string> */
    public function presetOptions(): array
    {
        return collect(self::PRESETS)
            ->mapWithKeys(fn (array $preset, string $key): array => [$key => $preset['name']])
            ->all();
    }

    /**
     * Resolve a preset key or palette key to a registered palette key.
     */
    public function resolvePaletteKey(string $key): string
    {
        if (isset(self::PRESETS[$key])) {
            return self::PRESETS[$key]['palette_key'];
        }

        return $key;
    }

    /** @return array<string, string> */
    public function options(): array
    {
        return collect($this->all())
            ->mapWithKeys(fn (array $palette, string $key): array => [$key => $palette['name']])
            ->all();
    }

    /** @return array<string, HtmlString> */
    public function descriptions(): array
    {
        return collect($this->all())
            ->mapWithKeys(function (array $palette, string $key): array {
                $swatches = collect($palette['preview'])
                    ->map(fn (string $color): string => sprintf(
                        '<span style="display:inline-block;width:1.5rem;height:1.5rem;border-radius:.375rem;background:%s;border:1px solid rgba(0,0,0,.12)"></span>',
                        e($color),
                    ))
                    ->implode('');

                return [$key => new HtmlString(sprintf(
                    '<span style="display:block;margin-bottom:.5rem;color:#6b7280">%s</span><span style="display:flex;gap:.375rem">%s</span>',
                    e($palette['description']),
                    $swatches,
                ))];
            })
            ->all();
    }

    /**
     * @return array{name: string, description: string, preview: array<int, string>, tokens: array<string, mixed>}
     */
    public function get(string $key): array
    {
        $resolvedKey = $this->resolvePaletteKey($key);
        $palette = $this->all()[$resolvedKey] ?? null;

        if (! is_array($palette)) {
            throw new InvalidArgumentException("Unknown theme palette [{$key}].");
        }

        return $palette;
    }

    /** @return array<string, mixed> */
    public function tokens(string $key): array
    {
        return $this->get($key)['tokens'];
    }
}
