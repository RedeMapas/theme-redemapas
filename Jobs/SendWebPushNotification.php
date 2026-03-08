<?php

declare(strict_types=1);

namespace MapasCulturais\Themes\RedeMapas\Jobs;

use MapasCulturais\App;
use MapasCulturais\Definitions\JobType;
use MapasCulturais\Entities\Job;
use MapasCulturais\Entities\Notification;
use MapasCulturais\Themes\RedeMapas\Controllers\Push as PushController;
use MapasCulturais\Themes\RedeMapas\Push\SubscriptionStore;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class SendWebPushNotification extends JobType
{
    public const SLUG = 'redemapas.send_webpush_notification';

    protected function _generateId(array $data, string $start_string, string $interval_string, int $iterations): string
    {
        $notification = $data['notification'] ?? null;
        if ($notification instanceof Notification) {
            return (string) $notification->id;
        }

        return md5(json_encode($data));
    }

    protected function _execute(Job $job): bool
    {
        $app = App::i();
        $notification = $job->notification;
        if (!$notification instanceof Notification || !$notification->user) {
            return true;
        }

        if (!($app->config['redemapas.push.enabled'] ?? false)) {
            return true;
        }

        if (!class_exists(WebPush::class) || !class_exists(Subscription::class)) {
            $app->log->warning('[redemapas-webpush] Biblioteca minishlink/web-push não encontrada');
            return true;
        }

        $publicKey = (string) ($app->config['redemapas.push.vapid.publicKey'] ?? '');
        $privateKey = (string) ($app->config['redemapas.push.vapid.privateKey'] ?? '');
        $subject = (string) ($app->config['redemapas.push.vapid.subject'] ?? '');
        if ($publicKey === '' || $privateKey === '' || $subject === '') {
            $app->log->warning('[redemapas-webpush] Configuração VAPID incompleta');
            return true;
        }

        $subscriptions = PushController::normalizeSubscriptions(
            $notification->user->getMetadata(PushController::USER_METADATA_KEY)
        );
        if (!$subscriptions) {
            return true;
        }

        $auth = [
            'VAPID' => [
                'subject' => $subject,
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ],
        ];

        $webPush = new WebPush($auth, ['TTL' => 300]);
        $payload = $this->buildPayload($notification);
        foreach ($subscriptions as $subscriptionData) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscriptionData['endpoint'],
                    'publicKey' => $subscriptionData['keys']['p256dh'],
                    'authToken' => $subscriptionData['keys']['auth'],
                    'contentEncoding' => $subscriptionData['contentEncoding'] ?? 'aes128gcm',
                ]),
                $payload
            );
        }

        $invalidEndpoints = [];
        foreach ($webPush->flush() as $report) {
            $endpoint = (string) $report->getRequest()->getUri();
            if ($report->isSuccess()) {
                continue;
            }

            $reason = strtolower((string) $report->getReason());
            if (str_contains($reason, '410') || str_contains($reason, '404') || str_contains($reason, 'expired') || $report->isSubscriptionExpired()) {
                $invalidEndpoints[] = $endpoint;
            }
            $app->log->warning("[redemapas-webpush] Falha no endpoint {$endpoint}: {$reason}");
        }

        if ($invalidEndpoints) {
            $updated = $subscriptions;
            foreach (array_unique($invalidEndpoints) as $endpoint) {
                $updated = SubscriptionStore::removeByEndpoint($updated, $endpoint);
            }

            $app->disableAccessControl();
            $notification->user->setMetadata(PushController::USER_METADATA_KEY, $updated);
            $notification->user->save(true);
            $app->enableAccessControl();
        }

        return true;
    }

    private function resolveNotificationUrl(Notification $notification): string
    {
        $app = App::i();
        $fallback = $app->createUrl('panel', 'index');

        try {
            $request = $notification->request ?? null;
            if (!$request) {
                return $fallback;
            }

            $destination = $request->destination ?? null;
            if (!$destination || !method_exists($destination, '__get')) {
                return $fallback;
            }

            $url = $destination->singleUrl ?? null;
            return ($url && is_string($url) && $url !== '') ? $url : $fallback;
        } catch (\Throwable) {
            return $fallback;
        }
    }

    private function buildPayload(Notification $notification): string
    {
        $app = App::i();
        $body = trim(strip_tags((string) $notification->message));
        if (mb_strlen($body) > 180) {
            $body = mb_substr($body, 0, 177) . '...';
        }

        $icon = $app->view->asset($app->config['favicon.192'], false);
        $url = $this->resolveNotificationUrl($notification);

        return json_encode([
            'title' => (string) $app->siteName,
            'body' => $body,
            'icon' => $icon,
            'badge' => $icon,
            'url' => $url,
            'notificationId' => $notification->id,
        ], JSON_UNESCAPED_UNICODE);
    }
}
