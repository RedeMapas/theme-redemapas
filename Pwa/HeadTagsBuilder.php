<?php

declare(strict_types=1);

namespace MapasCulturais\Themes\RedeMapas\Pwa;

class HeadTagsBuilder
{
    public static function build(
        string $siteName,
        string $manifestUrl,
        string $appleTouchIcon,
        string $themeColor = '#0f172a'
    ): array {
        return [
            'links' => [
                ['rel' => 'manifest', 'href' => $manifestUrl],
            ],
            'metas' => [
                ['name' => 'theme-color', 'content' => $themeColor],
                ['name' => 'mobile-web-app-capable', 'content' => 'yes'],
                ['name' => 'apple-mobile-web-app-capable', 'content' => 'yes'],
                ['name' => 'apple-mobile-web-app-title', 'content' => $siteName],
                ['name' => 'apple-mobile-web-app-status-bar-style', 'content' => 'default'],
                ['name' => 'msapplication-TileColor', 'content' => $themeColor],
                ['name' => 'msapplication-TileImage', 'content' => $appleTouchIcon],
            ],
        ];
    }
}
