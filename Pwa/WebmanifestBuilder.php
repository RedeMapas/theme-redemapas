<?php

declare(strict_types=1);

namespace MapasCulturais\Themes\RedeMapas\Pwa;

class WebmanifestBuilder
{
    public static function build(
        string $siteName,
        string $siteDescription,
        string $startUrl,
        string $icon192,
        string $icon512,
        string $wideScreenshot = '',
        string $mobileScreenshot = '',
        string $themeColor = '#0f172a',
        string $backgroundColor = '#ffffff'
    ): array {
        $manifest = [
            'name' => $siteName,
            'short_name' => $siteName,
            'description' => $siteDescription,
            'start_url' => $startUrl,
            'scope' => '/',
            'display' => 'standalone',
            'theme_color' => $themeColor,
            'background_color' => $backgroundColor,
            'icons' => [
                ['src' => $icon192, 'type' => 'image/png', 'sizes' => '192x192'],
                ['src' => $icon512, 'type' => 'image/png', 'sizes' => '512x512'],
            ],
        ];

        $screenshots = [];
        if ($wideScreenshot !== '') {
            $screenshots[] = [
                'src' => $wideScreenshot,
                'type' => 'image/jpeg',
                'sizes' => '570x382',
                'form_factor' => 'wide',
            ];
        }

        if ($mobileScreenshot !== '') {
            $screenshots[] = [
                'src' => $mobileScreenshot,
                'type' => 'image/png',
                'sizes' => '664x677',
            ];
        }

        if ($screenshots) {
            $manifest['screenshots'] = $screenshots;
        }

        return $manifest;
    }
}
