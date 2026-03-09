<?php

declare(strict_types=1);

namespace MapasCulturais\Themes\RedeMapas\Controllers;

use MapasCulturais\App;
use MapasCulturais\Controller;
use MapasCulturais\Themes\RedeMapas\Push\SubscriptionStore;

class Push extends Controller
{
    public const USER_METADATA_KEY = 'redemapasPushSubscriptions';

    public function POST_subscribe(): void
    {
        $this->requireAuthentication();
        $app = App::i();

        $payload = is_array($this->postData) ? $this->postData : [];
        $subscription = $payload['subscription'] ?? $payload;
        if (!is_array($subscription)) {
            $this->errorJson(['error' => 'Invalid subscription payload'], 400);
            return;
        }

        $current = self::normalizeSubscriptions($app->user->getMetadata(self::USER_METADATA_KEY));
        $updated = SubscriptionStore::upsert($current, $subscription, (string) $app->request->getUserAgent());

        $app->disableAccessControl();
        $app->user->setMetadata(self::USER_METADATA_KEY, json_encode($updated));
        $app->user->save(true);
        $app->enableAccessControl();

        $this->json(['success' => true, 'count' => count($updated)]);
    }

    public function POST_unsubscribe(): void
    {
        $this->requireAuthentication();
        $app = App::i();

        $payload = is_array($this->postData) ? $this->postData : [];
        $endpoint = (string) ($payload['endpoint'] ?? '');
        if ($endpoint === '') {
            $this->errorJson(['error' => 'Missing endpoint'], 400);
            return;
        }

        $current = self::normalizeSubscriptions($app->user->getMetadata(self::USER_METADATA_KEY));
        $updated = SubscriptionStore::removeByEndpoint($current, $endpoint);

        $app->disableAccessControl();
        $app->user->setMetadata(self::USER_METADATA_KEY, json_encode($updated));
        $app->user->save(true);
        $app->enableAccessControl();

        $this->json(['success' => true, 'count' => count($updated)]);
    }

    public function GET_serviceWorker(): void
    {
        // Service worker is now served as static file at /sw.js
        // This method is kept for backward compatibility
        $app = App::i();
        $app->halt(404, 'Service worker not available via controller');
    }

    public static function normalizeSubscriptions(mixed $value): array
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        } elseif (is_object($value)) {
            $value = json_decode(json_encode($value), true);
        }

        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter(
            $value,
            fn ($item) => is_array($item) && !empty($item['endpoint']) && !empty($item['keys']['p256dh']) && !empty($item['keys']['auth'])
        ));
    }
}
