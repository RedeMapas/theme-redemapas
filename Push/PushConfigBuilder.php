<?php

declare(strict_types=1);

namespace MapasCulturais\Themes\RedeMapas\Push;

class PushConfigBuilder
{
    public static function buildClientConfig(
        bool $enabled,
        string $publicKey,
        string $subscribeUrl,
        string $unsubscribeUrl,
        string $serviceWorkerUrl
    ): array {
        $publicKey = trim($publicKey);
        $isEnabled = $enabled && $publicKey !== '';

        return [
            'enabled' => $isEnabled,
            'publicKey' => $publicKey,
            'subscribeUrl' => $subscribeUrl,
            'unsubscribeUrl' => $unsubscribeUrl,
            'serviceWorkerUrl' => $serviceWorkerUrl,
        ];
    }
}
