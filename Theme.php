<?php

namespace redemapas;

use MapasCulturais\App;
use MapasCulturais\Entities\Notification;
use MapasCulturais\Themes\RedeMapas\Controllers\Push;
use MapasCulturais\Themes\RedeMapas\Jobs\SendWebPushNotification;
use MapasCulturais\Themes\RedeMapas\Push\PushConfigBuilder;
use MapasCulturais\Themes\RedeMapas\Pwa\HeadTagsBuilder;
use MapasCulturais\Themes\RedeMapas\Pwa\WebmanifestBuilder;

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

        if (!$app->getController('push')) {
            $app->registerController('push', Push::class);
        }

        $app->hook('view.render(site/index):before', function () {
            $this->enqueueStyle('redemapas-home', 'redemapas-home-css', 'css/home.css');
            $this->enqueueScript('redemapas-home', 'redemapas-home-js', 'js/home.js');
            $this->enqueueScript('redemapas-home', 'redemapas-pwa-launcher', 'js/push-notifications.js');
            $this->bodyClasses[] = 'redemapas-home';
        });

        $app->hook('view.render(<<*>>):before', function () {
            $this->enqueueScript('app-v2', 'redemapas-pwa-launcher', 'js/push-notifications.js');
        });

        $app->hook('mapas.printJsObject:before', function () use ($theme) {
            $config = $theme->getPushClientConfig();
            $config['strings'] = [
                'enable'      => \MapasCulturais\i::__('Ativar notificações'),
                'enabled'     => \MapasCulturais\i::__('Notificações ativadas'),
                'blocked'     => \MapasCulturais\i::__('Notificações bloqueadas'),
                'unsupported' => \MapasCulturais\i::__('Notificações não suportadas'),
                'unavailable' => \MapasCulturais\i::__('Notificações indisponíveis'),
                'installApp'  => \MapasCulturais\i::__('Instalar aplicativo'),
            ];
            $this->jsObject['redemapasPush'] = $config;
        });

        $app->hook('GET(site.webmanifest)', function () use ($theme) {
            /** @var \MapasCulturais\Controller $this */
            $this->json($theme->getWebmanifestData());
        });

        $app->hook('template(<<*>>.head):end', function () use ($theme) {
            $theme->printPwaHeadTags();
        });

        $app->hook('entity(Notification).insert:after', function () use ($app) {
            /** @var Notification $this */
            if (($app->config['redemapas.push.enabled'] ?? false) && $this->user) {
                $app->enqueueJob(SendWebPushNotification::SLUG, ['notification' => $this]);
            }
        });
    }

    function register()
    {
        parent::register();
        $app = App::i();
        if (!$app->getRegisteredJobType(SendWebPushNotification::SLUG)) {
            $app->registerJobType(new SendWebPushNotification(SendWebPushNotification::SLUG));
        }
    }

    public static function buildWebmanifestData(
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
        return WebmanifestBuilder::build(
            siteName: $siteName,
            siteDescription: $siteDescription,
            startUrl: $startUrl,
            icon192: $icon192,
            icon512: $icon512,
            wideScreenshot: $wideScreenshot,
            mobileScreenshot: $mobileScreenshot,
            themeColor: $themeColor,
            backgroundColor: $backgroundColor
        );
    }

    public static function buildPwaHeadTags(
        string $siteName,
        string $manifestUrl,
        string $appleTouchIcon,
        string $themeColor = '#0f172a'
    ): array {
        return HeadTagsBuilder::build(
            siteName: $siteName,
            manifestUrl: $manifestUrl,
            appleTouchIcon: $appleTouchIcon,
            themeColor: $themeColor
        );
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
            icon512: $this->asset($app->config['favicon.512'], false),
            wideScreenshot: $this->asset('img/home/home-circuits/circuits.jpg', false),
            mobileScreenshot: $this->asset('img/home/home-main-header/banner.png', false)
        );
    }

    public static function buildPushClientConfig(
        bool $enabled,
        string $publicKey,
        string $subscribeUrl,
        string $unsubscribeUrl,
        string $serviceWorkerUrl
    ): array {
        return PushConfigBuilder::buildClientConfig(
            enabled: $enabled,
            publicKey: $publicKey,
            subscribeUrl: $subscribeUrl,
            unsubscribeUrl: $unsubscribeUrl,
            serviceWorkerUrl: $serviceWorkerUrl
        );
    }

    public function getPushClientConfig(): array
    {
        $app = App::i();
        return self::buildPushClientConfig(
            enabled: (bool) ($app->config['redemapas.push.enabled'] ?? false),
            publicKey: (string) ($app->config['redemapas.push.vapid.publicKey'] ?? ''),
            subscribeUrl: $app->createUrl('push', 'subscribe'),
            unsubscribeUrl: $app->createUrl('push', 'unsubscribe'),
            serviceWorkerUrl: $app->createUrl('push', 'serviceWorker')
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
