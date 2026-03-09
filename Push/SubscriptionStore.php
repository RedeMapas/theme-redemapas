<?php

declare(strict_types=1);

namespace MapasCulturais\Themes\RedeMapas\Push;

class SubscriptionStore
{
    public static function upsert(array $currentSubscriptions, array $incomingSubscription, string $userAgent = ''): array
    {
        $endpoint = trim((string) ($incomingSubscription['endpoint'] ?? ''));
        $p256dh = (string) ($incomingSubscription['keys']['p256dh'] ?? '');
        $auth = (string) ($incomingSubscription['keys']['auth'] ?? '');
        $encoding = (string) ($incomingSubscription['contentEncoding'] ?? 'aes128gcm');

        if ($endpoint === '' || $p256dh === '' || $auth === '') {
            return $currentSubscriptions;
        }

        $normalized = [
            'endpoint' => $endpoint,
            'keys' => [
                'p256dh' => $p256dh,
                'auth' => $auth,
            ],
            'contentEncoding' => $encoding,
            'userAgent' => $userAgent,
            'updatedAt' => date(DATE_ATOM),
        ];

        $updated = false;
        foreach ($currentSubscriptions as $index => $subscription) {
            if (($subscription['endpoint'] ?? '') === $endpoint) {
                $currentSubscriptions[$index] = $normalized;
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            $currentSubscriptions[] = $normalized;
        }

        return array_values($currentSubscriptions);
    }

    public static function removeByEndpoint(array $currentSubscriptions, string $endpoint): array
    {
        $endpoint = trim($endpoint);
        if ($endpoint === '') {
            return array_values($currentSubscriptions);
        }

        $filtered = array_filter(
            $currentSubscriptions,
            fn (array $subscription) => (($subscription['endpoint'] ?? '') !== $endpoint)
        );

        return array_values($filtered);
    }
}
