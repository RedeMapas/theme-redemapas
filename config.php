<?php

return [
    'redemapas.push.enabled' => (bool) env('REDEMAPAS_PUSH_ENABLED', false),
    'redemapas.push.vapid.subject' => env('REDEMAPAS_VAPID_SUBJECT', ''),
    'redemapas.push.vapid.publicKey' => env('REDEMAPAS_VAPID_PUBLIC_KEY', ''),
    'redemapas.push.vapid.privateKey' => env('REDEMAPAS_VAPID_PRIVATE_KEY', ''),
];
