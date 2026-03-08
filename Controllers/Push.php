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
        $app->user->setMetadata(self::USER_METADATA_KEY, $updated);
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
        $app->user->setMetadata(self::USER_METADATA_KEY, $updated);
        $app->user->save(true);
        $app->enableAccessControl();

        $this->json(['success' => true, 'count' => count($updated)]);
    }

    public function GET_serviceWorker(): void
    {
        $app = App::i();
        $swPath = __DIR__ . '/../assets/js/sw.js';

        if (!is_file($swPath)) {
            $app->halt(404, 'Service worker not found');
            return;
        }

        $app->response = $app->response
            ->withHeader('Content-Type', 'application/javascript; charset=utf-8')
            ->withHeader('Service-Worker-Allowed', '/')
            ->withHeader('Cache-Control', 'no-cache');

        $app->halt(200, file_get_contents($swPath));
    }

    public static function normalizeSubscriptions(mixed $value): array
    {
        if (is_object($value)) {
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
