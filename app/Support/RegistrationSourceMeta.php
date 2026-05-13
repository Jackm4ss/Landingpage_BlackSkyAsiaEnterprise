<?php

namespace App\Support;

final class RegistrationSourceMeta
{
    /**
     * @var array<string, array{label: string, color: string, icon: string}>
     */
    private const SOURCES = [
        'direct' => [
            'label' => 'Direct / Website',
            'color' => '#0EA5E9',
            'icon' => 'heroicon-o-home',
        ],
        'instagram' => [
            'label' => 'Instagram',
            'color' => '#E1306C',
            'icon' => 'si-instagram',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'color' => '#1877F2',
            'icon' => 'si-facebook',
        ],
        'tiktok' => [
            'label' => 'TikTok',
            'color' => '#14B8A6',
            'icon' => 'si-tiktok',
        ],
        'google' => [
            'label' => 'Google Organic',
            'color' => '#22C55E',
            'icon' => 'si-google',
        ],
        'newsletter' => [
            'label' => 'Newsletter',
            'color' => '#A855F7',
            'icon' => 'heroicon-o-envelope',
        ],
        'partner' => [
            'label' => 'Partner Vendors',
            'color' => '#F43F5E',
            'icon' => 'heroicon-o-link',
        ],
        'other' => [
            'label' => 'Other',
            'color' => '#94A3B8',
            'icon' => 'heroicon-o-sparkles',
        ],
    ];

    /**
     * @return array{label: string, color: string, icon: string}
     */
    public static function for(?string $source): array
    {
        $key = strtolower(trim((string) $source));

        return self::SOURCES[$key] ?? self::SOURCES['other'];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_map(
            static fn (array $meta): string => $meta['label'],
            self::SOURCES,
        );
    }
}
