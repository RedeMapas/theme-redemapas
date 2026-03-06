<?php

namespace redemapas;

use MapasCulturais\App;

class Theme extends \MapasCulturais\Themes\BaseV2\Theme
{
    static function getThemeFolder()
    {
        return __DIR__;
    }

    function _init()
    {
        parent::_init();

        $app = App::i();
        $theme = $this;

        $app->hook('view.render(site/index):before', function () {
            $this->enqueueStyle('redemapas-home', 'redemapas-home-css', 'css/home.css');
            $this->enqueueScript('redemapas-home', 'redemapas-home-js', 'js/home.js');
            $this->bodyClasses[] = 'redemapas-home';
        });

        $app->hook('GET(site.webmanifest)', function () use ($theme) {
            /** @var \MapasCulturais\Controller $this */
            $this->json($theme->getWebmanifestData());
        });

        $app->hook('template(<<*>>.head):end', function () use ($theme) {
            $theme->printPwaHeadTags();
        });
    }

    public static function buildWebmanifestData(
        string $siteName,
        string $siteDescription,
        string $startUrl,
        string $icon192,
        string $icon512,
        string $themeColor = '#0f172a',
        string $backgroundColor = '#ffffff'
    ): array {
        return [
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
    }

    public static function buildPwaHeadTags(
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

    public function getWebmanifestData(): array
    {
        $app = App::i();
        $startUrl = rtrim($app->baseUrl, '/') ?: '/';

        return self::buildWebmanifestData(
            siteName: (string) $app->siteName,
            siteDescription: (string) $app->siteDescription,
            startUrl: $startUrl,
            icon192: $this->asset($app->config['favicon.192'], false),
            icon512: $this->asset($app->config['favicon.512'], false)
        );
    }

    public function printPwaHeadTags(): void
    {
        $app = App::i();
        $tags = self::buildPwaHeadTags(
            siteName: (string) $app->siteName,
            manifestUrl: $app->createUrl('site', 'webmanifest'),
            appleTouchIcon: $this->asset($app->config['favicon.180'], false)
        );

        foreach ($tags['links'] as $linkCfg) {
            $attrs = [];
            foreach ($linkCfg as $prop => $value) {
                $attrs[] = sprintf('%s="%s"', $prop, htmlentities((string) $value));
            }
            echo "\n<link " . implode(' ', $attrs) . ">";
        }

        foreach ($tags['metas'] as $metaCfg) {
            $attrs = [];
            foreach ($metaCfg as $prop => $value) {
                $attrs[] = sprintf('%s="%s"', $prop, htmlentities((string) $value));
            }
            echo "\n<meta " . implode(' ', $attrs) . " />";
        }
    }
}
